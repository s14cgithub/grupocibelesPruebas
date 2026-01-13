<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="crearFacturasMensuales")
{
	//echo "<br>0.1<br>";
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	$fechaFac = $_POST["fechaFac"];
	
	
	$datosFacturas =  recogerDatosFacturasMensuales($conexion,$fechaInicio,$fechaFin,$fechaFac);		
	
	//echo "<br>0.2<br>";
	//echo "<br>\nNuero de registros: ".count($datosFacturas);
	
	foreach ($datosFacturas as $valor)
	{
		//echo "<br>0.3<br>";
		$codigoCliente = $valor["codigo"];
		$codigoSaldoCliente = $valor["codigo_saldo"];
		
		
		
		$nombreCliente=$valor["nombre_empresa"];
		$descripcion="Manipulación y envío de su Correspondencia diaria del ".$fechaInicio. " al ". $fechaFin;//nombre de campaña
		$presupuesto="mensual";
		$fecha=$fechaFac;
		$inicialComercial=0;//preguntar
		$prefactura = $valor["prefactura"];
		if ($prefactura!=1)
		{
			$prefactura=0;
		}
		
		
		//echo ("Error: entra");
		$precioNetoTotal=0;
		//echo "<br>1<br>";
		if ($valor["conceptos"]!="" && $valor["conceptos"]!="null" && $valor["conceptos"]!=null)//para calcular el importe total
		{
			//echo ("\n2");
			//echo "<br>2<br>";
			$datosDetalles = recogerDatosFacturasMensualesDetalles($conexion,$codigoCliente);
			
			foreach ($datosDetalles as $valor2)
			{
				//echo ("\n3");
				$temporal = $valor2["unidades"] * $valor2["precioUnitario"];
				
				//echo ("\nunidades: ".$valor2["unidades"]."; importe: ".$valor2["precioUnitario"]);
				
				$precioNetoTotal = $precioNetoTotal + $temporal;
				//echo insertarDatosFacturasDetallesMensuales($conexion, )
				
			}
		}
		
		//number_format($elPrecioFranqueo * $valor["fac_porCientoNoBonificable"] /100,2,',','.');
		
		$fijoMensual = $valor["fac_cuotaRecogida"];	
				
		$precioFranqueo=$valor["importe"]=="null"||$valor["importe"]==null?0:$valor["importe"];
		$manipuladoNoBonificable = $precioFranqueo * $valor["fac_porCientoNoBonificable"] /100;
		$otroConceptosCliente = $valor["fac_importeFijoOtrosConcepto"];
		
		
		$cantidadFranqueo=$valor["envios"]=="null"||$valor["envios"]==null?0:$valor["envios"];
		$manipulacionPostal = $cantidadFranqueo * $valor["fac_cobroUnitarioEnvio"];
		
		//echo "Error:";
		//echo ("\n".$manipulacionPostal);
		
		//conceptos detalles = 196.95		
		//fijoMensual = 141.02
		//manipulacionPostal = 0.2
		//manipuladoNoBonificable = 2.41
		
		
		$precioNetoTotal = $precioNetoTotal + $fijoMensual + $manipulacionPostal + $manipuladoNoBonificable + $otroConceptosCliente;	
		//$precioNetoTotal = $precioNetoTotal + $fijoMensual + $manipulacionPostal;		
		//echo "Error:";
		//echo ("\n".$precioNetoTotal);
		
		$precioNetoTotal = round($precioNetoTotal,2);
		
		if ($valor["sinIva"]==1 || $valor["sinIva"]=="1")
		{
			$iva = 0.00;
			$precioTotal =  $precioNetoTotal;
			$provision=0;//??????????????
			$aPagar=$precioTotal + 0;
		}
		else
		{
			$iva = round($precioNetoTotal * 0.21,2);
			$precioTotal =  round($precioNetoTotal * 1.21,2);
			$provision=0;//??????????????
			$aPagar=$precioTotal + 0;
		}
		
		
		
		
		//$cantidadFranqueo=$valor["envios"];
		//$pedido = "";
		$pedido = $valor["pedidoCliente"];
		$formaPago = $valor["formaPago"];
		$detallada=0;
		$formaPagoReal="";//???????????
		$fechaPago="";	
		$cd=1;
		
		$anioSeleccionado = date ("Y",strtotime($fecha));
		
		if ($cantidadFranqueo=="")
		{
			$cantidadFranqueo=0;
		}
		
		$resultado =  insertarFacturasMensuales($conexion,$nombreCliente,$codigoSaldoCliente,$descripcion,$presupuesto,$fecha,$inicialComercial,$precioNetoTotal,$iva,$precioTotal,$provision,$aPagar,$cantidadFranqueo,$pedido,$formaPago,$detallada,$formaPagoReal,$fechaPago,$cd,$fechaInicio, $fechaFin,$precioFranqueo, $valor["nuestraCuenta"], $anioSeleccionado,$prefactura);
		
		
		if (!$resultado["ok"]) 
		{
			// Si hay error → se muestra
			echo $resultado["mensaje"]."<br>\n";
			continue; // o break para salir del bucle
		}
			
		
		
		//die ($resultado);
		$numeroFactura = $resultado["data"][0]["numero"];
		
		//echo $numeroFactura;
		
		
		
		
		
		
		if ($valor["conceptos"]!="" and $valor["conceptos"]!="null" and $valor["conceptos"]!=null)//para grabar los detalles de la factura
		{
			
			$datosDetalles = recogerDatosFacturasMensualesDetalles($conexion,$codigoCliente);
			
			foreach ($datosDetalles as $valor2)
			{
				$temporal = $valor2["unidades"] * $valor["importe"];
				$precioNetoTotal = $precioNetoTotal + $temporal;
				//echo insertarDatosFacturasDetallesMensuales($conexion, )
				
				$total1 = $valor2["unidades"] * $valor2["precioUnitario"];
				
				echo insertarFacturasDetallesManuales($conexion, $numeroFactura, $valor2["concepto"], $valor2["unidades"], $valor2["precioUnitario"], $total1, '','',1000,1000, $anioSeleccionado);
				
				
			}
		}
		
		//FijoMensual
		insertarFacturasDetallesManuales($conexion, $numeroFactura, 'Fijo Mensual', 1, $fijoMensual, $fijoMensual, '','',1000,1,$anioSeleccionado);
		
		//Manipulacion Postal
		insertarFacturasDetallesManuales($conexion, $numeroFactura, 'Manipulación Postal', $cantidadFranqueo, $valor["fac_cobroUnitarioEnvio"], $manipulacionPostal, '','',1000,2,$anioSeleccionado);
		
		//Manipulado de Productos No Bonificables
		insertarFacturasDetallesManuales($conexion, $numeroFactura, 'Manipulado de Productos No Bonificables', $valor["fac_porCientoNoBonificable"], $precioFranqueo, $manipuladoNoBonificable, '','',1000,3,$anioSeleccionado);
		
		//Otros Conceptos
		//if ($valor["fac_importeFijoOtrosConcepto"]!="" and $valor["fac_importeFijoOtrosConcepto"]!="null" and $valor["fac_importeFijoOtrosConcepto"]!=null and $valor["fac_importeFijoOtrosConcepto"]!=0.00)//para grabar los detalles de la factura
		{
			insertarFacturasDetallesManuales($conexion, $numeroFactura, $valor["fac_otrosConceptosFijos"], 1, $valor["fac_importeFijoOtrosConcepto"], $valor["fac_importeFijoOtrosConcepto"], '','',1000,3,$anioSeleccionado);
		}
		
		
		//echo "<br>\nNumero Factura: ".$numeroFactura;
		//lanzarFacturaCibeles($conexion,$numeroFactura,$anioSeleccionado);
		
		
	}//foreach

	$condicion = " where t1.fecha > '".fechaCambioVerifactu."' and (t1.verifactu_idSolicitud is null) ";
	$datosDeFacturas = mostrarFacturas($conexion,$condicion,$anioSeleccionado);

	$contador = 0;
	foreach ($datosDeFacturas as $valor3)
	{
		$numeroDeFactura = $valor3["numero"];
		
		
		lanzarFacturaCibeles($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);
		usleep(150000); // 0.15 segundos

		$contador++;
		if ($contador % 25 === 0) {
			sleep(2); // 2 segundos
			if (function_exists('gc_collect_cycles')) {
				gc_collect_cycles(); // limpia memoria de objetos JSON/etc cada 25 llamadas
			}
		}
		
		
	}


	
	
	
			
	
}


?>
