<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="versiHayConversor")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$presupuesto = isset($_POST["presupuesto"]) ? $_POST["presupuesto"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = mostrarValorConversorTamanio($conn, $bbddSql, $presupuesto);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
