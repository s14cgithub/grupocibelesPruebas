<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verInformePorOtDetalle")
{
	$ruta = '../';

	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$ot = isset($_POST["ot"]) ? $_POST["ot"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarDetallesPresupustoInformeOtCostesTotales($conn, $bbddSql, $ot);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
