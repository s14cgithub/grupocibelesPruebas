<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarFacturasDetallesTemporal")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();

	if (isset($filtros['idUsuario']))
	{
		$filtros['idEmpleado'] = $_SESSION["idEmpleado"];
	}

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = eliminarFacturasDetallesTemporal($conn, $bbddSql, $filtros, $filtrosOperadores);

	sqlsrv_close($conn);

	echo json_encode($resultado);
}

?>
