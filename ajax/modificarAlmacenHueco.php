<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarHueco")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$id = $_POST["id"];
	$hueco = $_POST["hueco"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = array('id');
	$filtrosOperadores = array(
		array('campo1' => 'hueco', 'valor' => $hueco, 'operador' => '='),
		array('campo1' => 'id', 'valor' => $id, 'operador' => '!=')
	);

	$comprobarHueco = mostrarAlmacenHuecos($conn, $bbddSql, $campos, $filtrosOperadores, array());

	if ($comprobarHueco['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $comprobarHueco['error']));
		exit;
	}

	if (count($comprobarHueco['datos']) > 0) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'El hueco ya existe'));
		exit;
	}

	$resultado = modificarAlmacenHuecos($conn, $bbddSql, array('hueco' => $hueco), array('id' => $id));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Hueco Actualizado'));
	}
}

?>