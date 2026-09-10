<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="crearFacturasMensuales")
{
	//echo "<br>0.1<br>";
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	require_once($ruta."Verifactu/wortice/lanzarFactura.php");



	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	$fechaFac = $_POST["fechaFac"];

	$fecha = new DateTime($fechaFin);


	$fecha2 = new DateTime($fechaFin);
	$fecha2->modify('last day of this month');
	$fechaFinCampana = $fecha2->format('d-m-Y');

	$fecha->modify('first day of next month');
	$fechaFin = $fecha->format('d-m-Y');




	$camposFacturasMensuales = array('codigo','codigo_saldo','nombre_empresa','prefactura','conceptos','fac_cuotaRecogida','importe','fac_porCientoNoBonificable','fac_importeFijoOtrosConcepto','envios','fac_cobroUnitarioEnvio','sinIva','pedidoCliente','formaPago','nuestraCuenta','fac_otrosConceptosFijos');
	$ordenFacturasMensuales = array(array('campo'=>'nombre_empresa','dir'=>'ASC'));
	$anioTarifasFacturasMensuales = date('Y', strtotime($fechaFac));

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	modificarSePuedeFacturar($conn, $bbddSql, 0);

	$resultadoFacturasMensuales = cargarDatosFacturasMensuales($conn, $bbddSql, $camposFacturasMensuales, $fechaInicio, $fechaFin, $anioTarifasFacturasMensuales, $ordenFacturasMensuales);

	$datosFacturas = $resultadoFacturasMensuales['datos'];

	//echo "<br>0.2<br>";
	//echo "<br>\nNuero de registros: ".count($datosFacturas);

	$facturasCreadas = array();
	$erroresFacturas = array();

	foreach ($datosFacturas as $valor)
	{
		$datosCalculo = calcularDatosFacturaMensualCliente($conn, $bbddSql, $valor, $fechaInicio, $fechaFin, $fechaFac, $fechaFinCampana);

		$codigoCliente = $datosCalculo['codigoCliente'];
		$nombreCliente = $datosCalculo['nombreCliente'];
		$datosFacturaCabecera = $datosCalculo['datosFacturaCabecera'];
		$lineasDetalle = $datosCalculo['lineasDetalle'];

		$resultado = insertarFacturacion($conn, $bbddSql, $datosFacturaCabecera);


		if (!$resultado["ok"])
		{
			$erroresFacturas[] = array(
				'codigoCliente' => $codigoCliente,
				'nombreCliente' => $nombreCliente,
				'error' => $resultado["error"]
			);
			continue; // o break para salir del bucle
		}



		//die ($resultado);
		$numeroFactura = $resultado["numero"];
		$numeroFacturaCompleto = $resultado["numeroFacturaCompleto"];

		$facturasCreadas[] = array(
			'codigoCliente' => $codigoCliente,
			'nombreCliente' => $nombreCliente,
			'numeroFacturaCompleto' => $numeroFacturaCompleto
		);

		//echo $numeroFactura;

		$erroresDetalle = false;

		foreach ($lineasDetalle as $linea)
		{
			$linea['numeroFacturaCompleto'] = $numeroFacturaCompleto;
			$resDetalle = insertarFacturacionDetalles($conn, $bbddSql, $linea);

			if (!$resDetalle['ok'])
			{
				$erroresDetalle = true;
			}
		}

		if (!$erroresDetalle)
		{
			lanzarFactura($conn, $bbddSql, $numeroFacturaCompleto, false);
		}

		//echo "<br>\nNumero Factura: ".$numeroFactura;
		//lanzarFacturaCibeles($conexion,$numeroFactura,$anioSeleccionado);


	}//foreach

	modificarSePuedeFacturar($conn, $bbddSql, 1);

	sqlsrv_close($conn);

	echo json_encode(array(
		'ok' => empty($erroresFacturas),
		'facturasCreadas' => $facturasCreadas,
		'errores' => $erroresFacturas
	));
/*
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

*/





}


?>
