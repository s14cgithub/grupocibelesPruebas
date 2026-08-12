<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarNumeroFactura")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idPedido = isset($_POST["idPedido"]) ? $_POST["idPedido"] : '';
	$numeroFactura = isset($_POST["numeroFactura"]) ? $_POST["numeroFactura"] : '';
	$fechaFactura = isset($_POST["fechaFactura"]) ? date("d-m-Y", strtotime($_POST["fechaFactura"])) : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = modificarComprasTerceros(
		$conn, $bbddSql,
		array('numeroFacturaCompra' => $numeroFactura, 'fechaFacturaCompra' => $fechaFactura),
		array('pedido' => $idPedido)
	);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
