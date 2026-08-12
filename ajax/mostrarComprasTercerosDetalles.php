<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarDetalles")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idPedido = isset($_POST["idPedido"]) ? $_POST["idPedido"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = array('id','pedido','descripcion','cantidad','precioUnidad','precioVenta','total','margen');

	$res = cargarComprasTercerosDetalles($conn, $bbddSql, $campos, array('pedido' => $idPedido));

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
