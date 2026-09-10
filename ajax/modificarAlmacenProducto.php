<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarProducto")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$id = $_POST["id"];
	$producto = $_POST["producto"];
	$codigo = $_POST["codigo"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$datos = array('nombre' => $producto, 'codigo' => $codigo);
	$filtros = array('id' => $id);

	$resultado = modificarAlmacenProducto($conn, $bbddSql, $datos, $filtros);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Producto Modificado'));
	}
}

?>