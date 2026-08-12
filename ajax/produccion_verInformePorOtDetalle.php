<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verInformePorOtDetalle")
{
	$ruta = '../';

	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$codigoBarras = isset($_POST["codigoBarras"]) ? $_POST["codigoBarras"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarDatosInformeOtDetalle($conn, $bbddSql, $codigoBarras);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
