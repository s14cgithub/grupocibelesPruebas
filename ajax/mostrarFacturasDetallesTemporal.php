<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarFacturasDetallesTemporal")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();
	$group = isset($_POST["group"]) ? json_decode($_POST["group"], true) : array();
	$joins = isset($_POST["joins"]) ? json_decode($_POST["joins"], true) : array();

	if (isset($filtros['idUsuario']))
	{
		$filtros['idEmpleado'] = $_SESSION["idEmpleado"];
	}

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = mostrarFacturasDetallesTemporal($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $group, $joins);

	sqlsrv_close($conn);

	echo json_encode($resultado);
}

?>
