<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarRutasParaVincular")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = mostrarRutasParaVincular($conn, $bbddSql);

	sqlsrv_close($conn);

	echo json_encode($resultado);
}

?>