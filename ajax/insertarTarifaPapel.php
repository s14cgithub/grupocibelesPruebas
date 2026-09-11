<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarTarifasPapel")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idMaterialPapel_tamano = $_POST["tamanio"];
	$idMaterialPapel_tipo = $_POST["tipo"];
	$idMaterialPapel_acabado = $_POST["acabado"];
	$idMaterialPapel_gramaje = $_POST["gramaje"];
	$precio = $_POST["precio"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = array('id');
	$joins = array();
	$filtros = array(
		'idTamanio' => $idMaterialPapel_tamano,
		'idTipo' => $idMaterialPapel_tipo,
		'idAcabado' => $idMaterialPapel_acabado,
		'idGramaje' => $idMaterialPapel_gramaje
	);

	$comprobarPapel = verIdPapel($conn, $bbddSql, $campos, $joins, $filtros, array(), array());

	if ($comprobarPapel['error'] != '') {
		sqlsrv_close($conn);
		echo json_encode(array('error' => $comprobarPapel['error']));
		exit;
	}

	if (count($comprobarPapel['datos']) > 0) {
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'Ya existe ese material'));
		exit;
	}

	$datos = array(
		'idTamanio' => $idMaterialPapel_tamano,
		'idTipo' => $idMaterialPapel_tipo,
		'idAcabado' => $idMaterialPapel_acabado,
		'idGramaje' => $idMaterialPapel_gramaje,
		'precio' => $precio
	);

	$resultado = insertarTarifaPapel($conn, $bbddSql, $datos);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Tarifa Insertada'));
	}
}

?>