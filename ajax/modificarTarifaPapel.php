<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarTarifaPapel")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idPapel = $_POST["idPapel"];
	$precio = $_POST["precio"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = modificarTarifaPapel($conn, $bbddSql, array('precio' => $precio), array('id' => $idPapel));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Tarifa Modificada'));
	}
}

?>