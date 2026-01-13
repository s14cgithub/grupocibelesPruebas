<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="copiarPresupuestoAFacturaDetalleTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numPresupuesto = $_POST["numPresupuesto"];
	$opcion = $_POST["opcion"];
	$usuario = $_SESSION["idEmpleado"];
	


	
	if ($opcion==1)//no esta en el array
	{//echo 'hola1';
		borrarFacturaDetallesTemporal($conexion,$numPresupuesto,$usuario);	
		insertarPresupuestoDetallesATemporal($conexion,$numPresupuesto,$usuario);
		
		borrarPrefacturaCabeceraTemporal2($conexion,$usuario,$numPresupuesto);		//nunca deberia de ocurrir..... pero por si acaso
		
	}
	else if ($opcion==2)//esta en el array: esto se hace para que no se borre las modificaciones realizadas anteriormente
	{//echo 'hola2';
		$datos = mostrarFacturasDetallesTemporal($conexion,$numPresupuesto,$usuario);
		if (count($datos)<=0)
		{			
			borrarFacturaDetallesTemporal($conexion,$numPresupuesto,$usuario);		
			insertarPresupuestoDetallesATemporal($conexion,$numPresupuesto,$usuario);			
		}
		
		//para los datos del cliente (facturasTemporal)
		$datos2 = verSiHayDatosCombinacionPrefactura2($conexion,$numPresupuesto,$usuario);
		if (count($datos2)<=0)
		{			
			//borrarPrefacturaCabeceraTemporal2($conexion,$usuario,$numPresupuesto);	
			
			/////////////////////////////////
			
			$texto = $numPresupuesto;
			$queBusca=presupuesto_Presupuesto;
			$orden=1;
			$desc="";
			
			$resultado1 = mostrarPresupuestos($conexion,$orden,$desc,$texto,$queBusca);
			if (count($resultado1)<=0)
			{
				$resultado = mostrarPresupuestosClayma($conexion,$orden,$desc,$texto,$queBusca);
			}
			else
			{
				$resultado = mostrarPresupuestos($conexion,$orden,$desc,$texto,$queBusca);
			}
			
			
			
			$idCliente = $resultado[0]["codigo_saldo"];
			$clayma = $resultado[0]["clayma"];
			$pedido = $resultado[0]["pedcli"];
			$cantidad = $resultado[0]["cantidad"];
			$formaPago = $resultado[0]["idFormaPago"];
			$campana = $resultado[0]["campana2"];
			$detallada = $resultado[0]["detallada"];			
			$Neto = 0;
			$iva = 0;
			$total = 0;
			$provisionTotal = 0;
			$aPagar = 0;			
			$presupuesto = $numPresupuesto;
			
			
			insertarPrefacturaCabeceraTemporal($conexion, $idCliente, $clayma, $usuario, $pedido, $cantidad, $formaPago, $campana, $detallada, $Neto, $iva, $total, $provisionTotal, $aPagar,$presupuesto);
				
				
		}
		
	}
	

	
	
	
	
	
	
}


?>
