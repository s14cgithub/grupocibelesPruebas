<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarFormaDePago")
{
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarFormasDePagoComprasATerceros($conn, $bbddSql, $campos, $order);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
