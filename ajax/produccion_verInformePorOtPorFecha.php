<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verInformePorDia")
{
	$ruta = '../';

	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();

	$fechaInicio = isset($filtros["fechaInicio"]) ? $filtros["fechaInicio"] : '';
	$fechaFin = isset($filtros["fechaFin"]) ? $filtros["fechaFin"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarOtPorFechas($conn, $bbddSql, $fechaInicio, $fechaFin);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
