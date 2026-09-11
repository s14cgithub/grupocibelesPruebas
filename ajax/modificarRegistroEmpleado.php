<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRegistroEmpleado")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idEmpleado = $_POST["idEmpleado"];
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();

	// precioHora/activo deben llegar como tipo numerico real al driver (si se enlazan como string,
	// SQL Server intenta convertir nvarchar->numeric al actualizar y puede dar error de desbordamiento)
	if (isset($datos['precioHora'])) {
		$datos['precioHora'] = (float) $datos['precioHora'];
	}
	if (isset($datos['activo'])) {
		$datos['activo'] = (int) $datos['activo'];
	}

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = modificarRegistroEmpleado($conn, $bbddSql, $datos, array('id' => $idEmpleado));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Empleado modificado'));
	}
}

?>