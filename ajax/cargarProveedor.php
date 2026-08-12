<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarProveedores")
{
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();
	$filtrosLike = isset($_POST["filtrosLike"]) ? json_decode($_POST["filtrosLike"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarProveedores($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $filtrosLike);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
