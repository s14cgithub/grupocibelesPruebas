<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRegistroUsuario")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$id = $_POST["id"];
	$idEmpleado = $_POST["idEmpleado"];
	$usuario = $_POST["usuario"];
	$contrasena = trim($_POST["contrasena"]);

	$datos = array(
		'idEmpleado' => $idEmpleado,
		'usuario' => $usuario
	);

	// si no se ha escrito una contrasena nueva, no se toca la que ya habia
	if ($contrasena != '') {
		$datos['contrasena'] = $contrasena;
	}

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = modificarRegistroLogin($conn, $bbddSql, $datos, array('id' => $id));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Los datos del usuario han sido modificados'));
	}
}

?>