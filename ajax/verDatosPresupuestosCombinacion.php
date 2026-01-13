<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosPresupuestosCombinados")
{
	//echo "entra";
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
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	$usuario = $_SESSION["idEmpleado"];	
	$datosFactura =verSiHayDatosCombinacionPrefactura($conexion,$usuario);//facturaTemporal
	
	$campana = $datosFactura[0]["descripcion"];	
	$combinados=1;
	$presupuestosAimprimir = "";
	$presupuestosAimprimirContador=1;
	
	
	if ($combinadoSumatorio==1)
	{		
		$datosDetalles = verDetalleFacturaTemporalSumatorio($conexion,$usuario); 
		//$numPresupuesto = $datosFactura[0]["presupuesto"].'llll';
		
		
		$aux=verNumPresupuestosCombinadosTemporal($conexion,$usuario);		
		$numPresupuesto = "Comb: ";
		$contador1=0;
		while ($contador1<count($aux))
		{
			$numPresupuesto .= $aux[$contador1]["presupuesto"] . " - ";
			$contador1++;
		}
		
		$numPresupuesto = substr($numPresupuesto, 0, strlen($numPresupuesto)-3);
		
		
		$campana="";
	}
	else
	{
		$presupuestosAimprimir = verNumPresupuestosCombinadosTemporal($conexion,$usuario);		
		//$campana="";
		$presupuestosAimprimirContador = count($presupuestosAimprimir);
		$numPresupuesto="";
	}

	//$fecha = date('d/m/Y');
	$idCliente = $datosFactura[0]["idCliente"];
	$datosCliente =  cargarClientes($conexion," where codigo_saldo=".$idCliente." and codigo = ".$idCliente);
	$pedido = $datosFactura[0]["pedido"];
	$detallada = $datosFactura[0]["detallada"];
	$formaPago = $datosFactura[0]["formaPagoTexto"];
	$cuentaBancaria = $datosCliente[0]["nuestraCuenta"];
	$nombreCliente = $datosCliente[0]["nombre_empresa"];
	
	
	$sumatorioPrecio = sumatorioPreciosFacturasTemporal($conexion, $usuario, $idCliente);
	
	$precioNeto = $sumatorioPrecio[0]["neto"];
	//$iva = $sumatorioPrecio[0]["iva"];
	//$precioTotal = $sumatorioPrecio[0]["total"];
	$provision = $sumatorioPrecio[0]["provision"];
	$aPagar = $sumatorioPrecio[0]["aPagar"];
	$cantidad = 0;
		
	
	$precioNeto = round($precioNeto,2);
		
	$iva = $sumatorioPrecio[0]["iva"];
	$precioTotal = $sumatorioPrecio[0]["total"];
	//$iva = round($precioNeto * 0.21,2);
	//$precioTotal =  round($precioNeto * 1.21,2);
	
	

	$irpf =  $sumatorioPrecio[0]["irpf"];
	if ($irpf!=0)
	{
 		$irpf=round($precioNeto*19/100)*-1;	//esto se hace para evitar fallos en los redondeos
	}
	else
	{
		$irpf=0;
	} 
	
		
	$campana = "";
	$inicialComercial=0;
	
	
	$resultado2=verEstadoFacturacionFinMes($conexion);
	if ($resultado2[0]["activado"]=="0")
	{
		//$fecha = $resultado2[0]["fechaImprimir"]->format("d/m/Y");
		$fecha = $resultado2[0]["fechaImprimir"]->format("Y-m-d");
	}
	else
	{
		//$fecha = date('d/m/Y');
		$fecha = date('Y-m-d');
	}
	
	$anioSeleccionado = substr($fecha, 0, 4);	
	$prefactura = 0;
	$resultado1 = insertarFactura($conexion, 'Comb: '.$presupuestos, $nombreCliente,$idCliente, $fecha, $pedido, $cantidad, $formaPago,$cuentaBancaria, $campana, $detallada, $precioNeto, $iva, $irpf, $precioTotal, $provision, $aPagar, $inicialComercial,$anioSeleccionado,$combinadoSumatorio,$prefactura);
	        
	
	
	$numFactura = 0;
	
	if(strpos( $resultado1, "Error" ) === 0 or count($resultado1)>0)
	{
		//echo 'entra1';
		$resultado3 = mostrarNumFacturaPorPresupuesto($conexion, 'Comb: '.$presupuestos);
		$numFactura = $resultado3[0]["numero"];
		$numeroFacturaCompleto = $resultado3[0]["numeroFacturaCompleto"];
		
		$contador = 0;
		$seguir = true;
		
		while ($contador<count($presupuestos2) && $seguir == true)
		{
			if ($combinadoSumatorio==0)
			{
				$campana="sin nombre";
				$contador2=0;
				$seguir2=true;
				while ($contador2<count($presupuestosAimprimir)&& $seguir2)
				{
					if($presupuestos2[$contador]==$presupuestosAimprimir[$contador2]["presupuesto"])
					{
						$seguir2=false;
					}
					$campana = $presupuestosAimprimir[$contador2]["campana"];
					$contador2++;
				}
				
			}
			
			$resultado4 = copiarTemporalPreFacturaAFacturaDetalle($conexion,$numFactura,$presupuestos2[$contador],$idEmpleado,$anioSeleccionado,1,$campana);
			
			if($resultado4== "")
			{
				//echo 'entra2';
				$resultado5 = eliminarDetallePreFacturaPorPresupuesto($conexion,$presupuestos2[$contador]);
				$resultado7 = borrarPrefacturaCabeceraTemporal3($conexion,$presupuestos2[$contador]);
				//$resultado8 = borrarPrefacturaCabeceraTemporal($conexion,$usuario);
				
				
				
				if($resultado5== "Detalle eliminada en la tabla temporal")
				{				
					$resultado6 = modificarProvisionNumFactura2($conexion, $numFactura,$presupuestos2[$contador],$numeroFacturaCompleto);	
					
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
					echo ("Error<br>");
					echo $resultado5;
				}
				
			}
			else
			{
				echo strpos( $resultado4, "Error" );				
				$seguir = false;
				echo $resultado4;
			}
				
			
			
			
			$contador++;
		}
		
		if ($seguir == true)
		{
			echo $numFactura."||||".$anioSeleccionado;
			echo "||||"; //importante - no quitar esto
			echo lanzarFacturaCibeles($conexion,$numFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);
		}
		
	}
	else
	{
		echo 'Error: error al crear la factura';
		echo $resultado1;
	}
	
	
}

?>