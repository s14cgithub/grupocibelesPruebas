<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarPermisos")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = mostrarPermisos($conn, $bbddSql, $campos, $filtros);

	sqlsrv_close($conn);

	echo json_encode($resultado);
}

?>