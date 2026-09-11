<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRegistro")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idCliente = $_POST["idCliente"];
	$hora = $_POST["hora"];
	$rutaValor = $_POST["ruta"];
	$contacto = $_POST["contacto"];
	$incidencia = $_POST["incidencia"];
	$fecha = $_POST["fecha"];

	$datos = array(
		'idCliente' => $idCliente,
		'ruta' => $rutaValor,
		'hora' => $hora,
		'incidencia' => $incidencia,
		'contacto' => $contacto,
		'fecha' => $fecha
	);

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = insertarRutasAdicionales($conn, $bbddSql, $datos);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Ruta Insertada'));
	}
}

?>