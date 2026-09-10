<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="crearMovimientoAlmacen")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$almacen = $_POST["almacen"];
	$fecha = $_POST["fecha"];
	$cliente = $_POST["cliente"];
	$producto = $_POST["producto"];
	$modalidad = $_POST["modalidad"];
	$hueco = $_POST["hueco"];
	$cantidad = $_POST["cantidad"];
	$observacion = $_POST["observacion"];
	$ot = $_POST["ot"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	// ultimo movimiento de este hueco/producto/subcliente, para partir de su cantidadTotal
	$campos = array('cantidadTotal');
	$joins = array();
	$filtros = array('idSubCliente' => $cliente, 'idProducto' => $producto, 'idHueco' => $hueco);
	$filtrosOperadores = array();
	$order = array(array('campo' => 'id', 'dir' => 'DESC'));

	$ultimoMovimiento = mostrarAlmacenMovimientos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);

	if ($ultimoMovimiento['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $ultimoMovimiento['error']));
		exit;
	}

	$cantidadTotal = 0;
	if (count($ultimoMovimiento['datos']) > 0) {
		$cantidadTotal = $ultimoMovimiento['datos'][0]['cantidadTotal'];
	}

	if ($cantidad > 0 && $modalidad == 1) {
		$cantidad = $cantidad * (-1);
	}

	$cantidadTotal = $cantidadTotal + $cantidad;

	$fecha1 = date("d-m-Y", strtotime($fecha));

	$datosMovimiento = array(
		'idAlmacen' => $almacen,
		'fecha' => $fecha1,
		'idSubCliente' => $cliente,
		'idProducto' => $producto,
		'idModalidad' => $modalidad,
		'idHueco' => $hueco,
		'cantidad' => $cantidad,
		'observaciones' => $observacion,
		'ot' => $ot,
		'cantidadTotal' => $cantidadTotal
	);

	$resultadoMovimiento = insertarMovimientoAlmacen($conn, $bbddSql, $datosMovimiento);

	if (!$resultadoMovimiento['ok']) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $resultadoMovimiento['error']));
		exit;
	}

	$resultadoProducto = modificarAlmacenProducto($conn, $bbddSql, array('cantidadTotalDelta' => $cantidad), array('id' => $producto));

	sqlsrv_close($conn);

	if (!$resultadoProducto['ok']) {
		echo json_encode(array('error' => $resultadoProducto['error']));
		exit;
	}

	echo json_encode(array('error' => '', 'mensaje' => 'Movimiento Guardado'));
}

?>