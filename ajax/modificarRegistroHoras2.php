<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRegistroHoras")
{
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = modificarRegistroHoras($conn, $bbddSql, $datos, $filtros);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
