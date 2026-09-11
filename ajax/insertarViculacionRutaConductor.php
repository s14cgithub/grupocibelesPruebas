<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRutasConductoresVinculaciones")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idEmpleado = $_POST["idEmpleado"];
	$rutaValor = $_POST["ruta"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = array('id');
	$joins = array();
	$filtros = array('idConductorOruta' => array('idConductor' => $idEmpleado, 'ruta' => $rutaValor));

	$comprobarVinculacion = mostrarRutasVinculaciones($conn, $bbddSql, $campos, $joins, $filtros, array(), array());

	if ($comprobarVinculacion['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $comprobarVinculacion['error']));
		exit;
	}

	if (count($comprobarVinculacion['datos']) > 0) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'El conductor o la ruta ya esta vinculado'));
		exit;
	}

	$resultado = insertarVinculacionRutaConductor($conn, $bbddSql, array('idConductor' => $idEmpleado, 'ruta' => $rutaValor));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Vinculacion Guardada'));
	}
}

?>