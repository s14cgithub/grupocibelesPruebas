<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRegistro")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$id = $_POST["id"];
	$idCliente = $_POST["idCliente"];
	$Lruta = $_POST["Lruta"];
	$Lhora = $_POST["Lhora"];
	$Mruta = $_POST["Mruta"];
	$Mhora = $_POST["Mhora"];
	$Xruta = $_POST["Xruta"];
	$Xhora = $_POST["Xhora"];
	$Jruta = $_POST["Jruta"];
	$Jhora = $_POST["Jhora"];
	$Vruta = $_POST["Vruta"];
	$Vhora = $_POST["Vhora"];
	$contacto = $_POST["contacto"];
	$incidencia = $_POST["incidencia"];

	$datos = array(
		'idCliente' => $idCliente,
		'lunesRuta' => $Lruta,
		'lunesHora' => ($Lhora == "") ? null : $Lhora,
		'martesRuta' => $Mruta,
		'martesHora' => ($Mhora == "") ? null : $Mhora,
		'miercolesRuta' => $Xruta,
		'miercolesHora' => ($Xhora == "") ? null : $Xhora,
		'juevesRuta' => $Jruta,
		'juevesHora' => ($Jhora == "") ? null : $Jhora,
		'viernesRuta' => $Vruta,
		'viernesHora' => ($Vhora == "") ? null : $Vhora,
		'contacto' => $contacto,
		'incidencia' => $incidencia
	);

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = modificarRutasPlantilla($conn, $bbddSql, $datos, array('id' => $id));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Ruta Modificada'));
	}
}

?>