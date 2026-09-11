<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarTarifaTipoImpresora")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idTipoImpresora = $_POST["idTipoImpresora"];
	$precio = $_POST["precio"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = modificarTarifaTipoImpresora($conn, $bbddSql, array('precioClick' => $precio), array('id' => $idTipoImpresora));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Tarifa Modificada'));
	}
}

?>