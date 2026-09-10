<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarProducto")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$producto = $_POST["producto"];
	$idCliente = $_POST["idCliente"];
	$codigo = $_POST["codigo"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = array('id');
	$joins = array();

	$comprobarNombre = mostrarAlmacenProductos($conn, $bbddSql, $campos, $joins, array('idSubCliente' => $idCliente), array(array('campo1' => 'nombre', 'valor' => $producto, 'operador' => '=')), array());

	if ($comprobarNombre['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $comprobarNombre['error']));
		exit;
	}

	if (count($comprobarNombre['datos']) > 0) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'El producto ya existe con el cliente seleccionado'));
		exit;
	}

	$comprobarCodigo = mostrarAlmacenProductos($conn, $bbddSql, $campos, $joins, array(), array(array('campo1' => 'codigo', 'valor' => $codigo, 'operador' => '=')), array());

	if ($comprobarCodigo['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $comprobarCodigo['error']));
		exit;
	}

	if (count($comprobarCodigo['datos']) > 0) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'El codigo ya existe'));
		exit;
	}

	$datos = array('nombre' => $producto, 'idSubCliente' => $idCliente, 'codigo' => $codigo);
	$resultado = insertarAlmacenProducto($conn, $bbddSql, $datos);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Producto Guardado'));
	}
}

?>