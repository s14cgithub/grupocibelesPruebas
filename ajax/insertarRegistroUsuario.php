<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRegistroUsuario")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idEmpleado = $_POST["idEmpleado"];
	$usuarioNuevo = $_POST["usuarioNuevo"];
	$contrasenaNuevo = $_POST["contrasenaNuevo"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$comprobarUsuario = cargarLogin($conn, $bbddSql, array('id'), array('usuario' => $usuarioNuevo), array(), array());

	if ($comprobarUsuario['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $comprobarUsuario['error']));
		exit;
	}

	if (count($comprobarUsuario['datos']) > 0) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'El usuario ya existe'));
		exit;
	}

	$datos = array(
		'idEmpleado' => $idEmpleado,
		'usuario' => $usuarioNuevo,
		'contrasena' => $contrasenaNuevo
	);

	$resultado = insertarRegistroUsuario($conn, $bbddSql, $datos);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'id' => $resultado['id'], 'mensaje' => 'Usuario insertado'));
	}
}

?>