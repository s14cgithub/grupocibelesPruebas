<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="borrarPermisosUsuario")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$id_usuario = $_POST["id_usuario"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = borrarPermisosUsuario($conn, $bbddSql, array('id_usuario' => $id_usuario));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => ''));
	}
}

?>