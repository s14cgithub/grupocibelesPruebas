<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRegistro")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idCliente = $_POST["idCliente"];
	$LR = $_POST["LR"];
	$LH = $_POST["LH"];
	$MR = $_POST["MR"];
	$MH = $_POST["MH"];
	$XR = $_POST["XR"];
	$XH = $_POST["XH"];
	$JR = $_POST["JR"];
	$JH = $_POST["JH"];
	$VR = $_POST["VR"];
	$VH = $_POST["VH"];
	$contacto = $_POST["contacto"];
	$incidencia = $_POST["incidencia"];

	$datos = array(
		'idCliente' => $idCliente,
		'lunesRuta' => $LR,
		'lunesHora' => ($LH == "") ? null : $LH,
		'martesRuta' => $MR,
		'martesHora' => ($MH == "") ? null : $MH,
		'miercolesRuta' => $XR,
		'miercolesHora' => ($XH == "") ? null : $XH,
		'juevesRuta' => $JR,
		'juevesHora' => ($JH == "") ? null : $JH,
		'viernesRuta' => $VR,
		'viernesHora' => ($VH == "") ? null : $VH,
		'contacto' => $contacto,
		'incidencia' => $incidencia
	);

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = insertarRutasPlantilla($conn, $bbddSql, $datos);

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Ruta Guardada'));
	}
}

?>