<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarRegistroEmpleado")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = eliminarRegistroEmpleado($conn, $bbddSql, $filtros);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Empleado eliminado'));
	}
}

?>