<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosPresupuestosCombinados")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuestos = $_POST["presupuestos"];
	$idEmpleado = $_SESSION["idEmpleado"];
	$combinadoSumatorio = $_POST["combinadoSumatorio"];
	
	if ($combinadoSumatorio=="false")
		$combinadoSumatorio=0;
	else
		$combinadoSumatorio=1;
	
	$presupuestos2 = explode(" - ", $presupuestos);
	//echo ($presupuestos2[0]);
	
	$texto = $presupuestos2[0];
	
	
	$queBusca=presupuesto_Presupuesto;
	$orden=1;
	$desc="";
	
	
	
	$resultado = mostrarPresupuestosClayma($conexion,$orden,$desc,$texto,$queBusca);
	
	
	$inicialComercial = $resultado[0]["inicialComercial"];
	$cliente = $resultado[0]["cliente"];
	//$fecha = getdate();
	$fecha = date('Y/m/d');
	
	$pedidoCliente = $resultado[0]["pedcli"];
	$cantidad = 0;
	$formaPago = $resultado[0]["idFormaPago"];
	//$numCuenta = $resultado[0]["numCuentaBanco"];
	$numCuenta = $resultado[0]["nuestraCuenta"];
	$campana = '';
	$detallada = $resultado[0]["detallada"];
	
	$contador = 0;
	$provisionDeFondo = 0.00;
	
	
	while ($contador<count($presupuestos2))
	{	
		$provisiones = verProvisionDeFondoPorPresupuesto2($conexion, $presupuestos2[$contador]);		
		
		if (count($provisiones)>0)
		{			
			$provisionDeFondo += $provisiones[0]["importe"];
		}		
		
		$contador++;
	}
	
	$contador = 0;	
	
	$baseImponible = 0.00;
	$iva = 0.00;
	$total = 0.00;
	$aPagar = 0.00;
	while ($contador<count($presupuestos2))
	{	
		$detalles = mostrarFacturasDetallesTemporal($conexion,$presupuestos2[$contador],$idEmpleado);	
		
		$contador2=0;
		
		while ($contador2<count($detalles))
		{			
			$baseImponible += $detalles[$contador2]["total"];
			$contador2++;
		}		
		
		$contador++;
	}
	
	
	$iva = $baseImponible * 0.21;
	$total = $baseImponible + $iva;
	$aPagar = $total - $provisionDeFondo;
	
	
	//$texto="";
	//$texto=$presupuestos;
	
	
	$resultado1 = insertarFacturaClayma($conexion, 'Comb: '.$presupuestos, $cliente, $fecha, $pedidoCliente, $cantidad, $formaPago,$numCuenta, $campana, $detallada, $baseImponible, $iva, $total, $provisionDeFondo, $aPagar, $inicialComercial,$combinadoSumatorio);
	
	$numFactura = 0;
	if(strpos( $resultado1, "Error" ) === 0 or count($resultado1)>0)
	{
		//echo 'entra1';
		$resultado3 = mostrarNumFacturaPorPresupuestoClayma($conexion, 'Comb: '.$presupuestos);
		$numFactura = $resultado3[0]["numero"];
		
		$contador = 0;
		$seguir = true;
		while ($contador<count($presupuestos2) && $seguir == true)
		{
			$resultado4 = copiarTemporalPreFacturaAFacturaDetalleClayma($conexion,$numFactura,$presupuestos2[$contador],1);
			
			if($resultado4== "")
			{
				//echo 'entra2';
				$resultado5 = eliminarDetallePreFacturaPorPresupuesto($conexion,$presupuestos2[$contador]);
				if($resultado5== "Detalle eliminAda en la tabla temporal")
				{				
					$resultado6 = modificarProvisionNumFactura2($conexion, $numFactura,$presupuestos2[$contador]);	
					
					if($resultado6== "Numero de factura modificada en la provision de fondos")
					{
						//echo "entra222";
					}
					else
					{
						$seguir = false;
						echo $resultado6;
					}
				}
				else
				{
					$seguir = false;
					echo $resultado5;
				}
				
			}
			else
			{
				echo strpos( $resultado4, "Error" );
				//echo 'no entra';
				$seguir = false;
				echo $resultado4;
			}
				
			
			
			
			$contador++;
		}
		
		if ($seguir == true)
		{
			echo $numFactura;
		}
		
		
		
		
	}
	else
	{
		echo 'Error: error al crear la factura';
		echo $resultado1;
	}
	
	
}

?>