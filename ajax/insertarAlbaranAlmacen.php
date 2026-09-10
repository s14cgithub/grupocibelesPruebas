<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="grabarAlbaranAlmacen")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idMovimientos = $_POST["idMovimientos"];
	$observaciones = $_POST["observaciones"];

	$empresa = $_POST["empresa"];
	$direccion = $_POST["direccion"];
	$cp = $_POST["cp"];
	$localidad = $_POST["localidad"];
	$provincia = $_POST["provincia"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	// ultimo albaran guardado, para calcular el siguiente secuencial del dia
	$campos = array('id', 'secuencial', 'fecha');
	$filtros = array();
	$filtrosOperadores = array();
	$order = array(array('campo' => 'id', 'dir' => 'DESC'));

	$ultimoAlbaran = mostrarAlmacen_Albaranes($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order);

	if ($ultimoAlbaran['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $ultimoAlbaran['error']));
		exit;
	}

	$primerAlbaran = (count($ultimoAlbaran['datos']) <= 0);

	$fechaActual = date('d-m-Y');
	$diaActual = date('d');
	$mesActual = date('m');
	$anioActual = date('y');
	$secuencialNum = 0;
	$secuencial = "0000";

	if (!$primerAlbaran) {
		$fechaUltimoGuardado = $ultimoAlbaran['datos'][0]["fecha"]->format('d-m-Y');
		$secuencialUltimoGuardado = $ultimoAlbaran['datos'][0]["secuencial"];

		if ($fechaActual == $fechaUltimoGuardado) {
			$secuencialNum = intval($secuencialUltimoGuardado);
			$secuencialNum++;
			$secuencial = strval($secuencialNum);
		}
	}

	while (strlen($secuencial) < 4) {
		$secuencial = "0" . $secuencial;
	}

	$numeroAlbaran = $anioActual . $mesActual . $diaActual . $secuencial;

	$movimientos = explode("|||", $idMovimientos);

	$empresaEnvio = "";
	$direccionEnvio = "";
	$cpEnvio = "";
	$localidadEnvio = "";
	$provinciaEnvio = "";

	if ($direccion != "" && $direccion != "null" && $direccion != null) {
		$empresaEnvio = $empresa;
		$direccionEnvio = $direccion;
		$cpEnvio = $cp;
		$localidadEnvio = $localidad;
		$provinciaEnvio = $provincia;
	} else {
		$camposEnvio = array('nombre_empresa', 'direccion', 'codigo_postal', 'localidad', 'provincia', 'envio_nombre', 'envio_domicilio', 'envio_cp', 'envio_poblacion', 'envio_provincia');
		$filtrosEnvio = array('idMovimiento' => $movimientos[0]);

		$datosEnvio = verDatosDeEnvioPorIdMovimientoAlmacen($conn, $bbddSql, $camposEnvio, $filtrosEnvio);

		if ($datosEnvio['error'] != '') {
			sqlsrv_close($conn);
			echo json_encode(array('error' => $datosEnvio['error']));
			exit;
		}

		if ($datosEnvio['datos'][0]["envio_domicilio"] != "" && $datosEnvio['datos'][0]["envio_domicilio"] != "null" && $datosEnvio['datos'][0]["envio_domicilio"] != null) {
			$empresaEnvio = $datosEnvio['datos'][0]["envio_nombre"];
			$direccionEnvio = $datosEnvio['datos'][0]["envio_domicilio"];
			$cpEnvio = $datosEnvio['datos'][0]["envio_cp"];
			$localidadEnvio = $datosEnvio['datos'][0]["envio_poblacion"];
			$provinciaEnvio = $datosEnvio['datos'][0]["envio_provincia"];
		} else {
			$empresaEnvio = $datosEnvio['datos'][0]["nombre_empresa"];
			$direccionEnvio = $datosEnvio['datos'][0]["direccion"];
			$cpEnvio = $datosEnvio['datos'][0]["codigo_postal"];
			$localidadEnvio = $datosEnvio['datos'][0]["localidad"];
			$provinciaEnvio = $datosEnvio['datos'][0]["provincia"];
		}
	}

	$fecha1 = date("d-m-Y", strtotime($fechaActual));

	$datosAlbaran = array(
		'id' => $numeroAlbaran,
		'secuencial' => $secuencial,
		'observaciones' => $observaciones,
		'fecha' => $fecha1,
		'envioNombreEmpresa' => $empresaEnvio,
		'envioDireccion' => $direccionEnvio,
		'envioCp' => $cpEnvio,
		'envioLocalidad' => $localidadEnvio,
		'envioProvincia' => $provinciaEnvio
	);

	$resultadoAlbaran = insertarAlbaranAlmacen($conn, $bbddSql, $datosAlbaran);

	if (!$resultadoAlbaran['ok']) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $resultadoAlbaran['error']));
		exit;
	}

	$errorDetalle = '';
	$contador = 0;

	// el ultimo elemento es un "" que queda tras el "|||" final que añade el JS
	while ($contador < count($movimientos) - 1) {
		$datosDetalle = array('idAlbaran' => $numeroAlbaran, 'idMovimiento' => $movimientos[$contador]);
		$resultadoDetalle = insertarAlbaranDetalle($conn, $bbddSql, $datosDetalle);

		if (!$resultadoDetalle['ok'] && $errorDetalle == '') {
			$errorDetalle = $resultadoDetalle['error'];
		}

		$resultadoMovimiento = modificarValorAlbaranEnMovimientos($conn, $bbddSql, $movimientos[$contador]);

		if (!$resultadoMovimiento['ok'] && $errorDetalle == '') {
			$errorDetalle = $resultadoMovimiento['error'];
		}

		$contador++;
	}

	sqlsrv_close($conn);

	if ($errorDetalle != '') {
		echo json_encode(array('error' => $errorDetalle));
	} else {
		echo json_encode(array('error' => '', 'numeroAlbaran' => $numeroAlbaran));
	}
}

?>