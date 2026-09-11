<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="crearPermisosNuevo")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idLogin = $_POST["idLogin"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = insertarPermisos($conn, $bbddSql, array('id_usuario' => $idLogin));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => ''));
	}
}

?>