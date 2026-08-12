<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarPdfImpreso")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$numPedido = isset($_POST["numPedido"]) ? $_POST["numPedido"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = modificarComprasTercerosPdfImpreso($conn, $bbddSql, array('pedido' => $numPedido));

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
