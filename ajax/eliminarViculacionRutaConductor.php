<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarVinculacionRutaConductor")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$id = $_POST["id"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = eliminarVinculacionRutaConductor($conn, $bbddSql, array('id' => $id));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Vinculacion Eliminada'));
	}
}

?>