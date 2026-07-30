<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="copiarFacturacionDetallesATemporal")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$facturaOriginal = isset($_POST["facturaOriginal"]) ? $_POST["facturaOriginal"] : '';
	$clayma = isset($_POST["clayma"]) ? $_POST["clayma"] : 'false';

	$idEmpleado = $_SESSION["idEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$camposDetalleOriginal = ['concepto','descripcion','unidades','precio','total','tipoIva','ordenTipo','orden'];
	$filtrosDetalleOriginal = ['numeroFacturaCompleto' => $facturaOriginal];
	$orderDetalleOriginal = [['campo' => 'ordenTipo', 'dir' => 'ASC'], ['campo' => 'orden', 'dir' => 'ASC']];

	if ($clayma=="true")
	{
		$resDetalleOriginal = cargarFacturacionDetallesClayma($conn, $bbddSql, $camposDetalleOriginal, $filtrosDetalleOriginal, [], [], $orderDetalleOriginal);
	}
	else
	{
		$resDetalleOriginal = cargarFacturacionDetalles($conn, $bbddSql, $camposDetalleOriginal, $filtrosDetalleOriginal, [], [], $orderDetalleOriginal);
	}

	$resultado = array();

	foreach ($resDetalleOriginal['datos'] as $detalle)
	{
		$datos = $detalle;
		$datos['idEmpleado'] = $idEmpleado;
		$datos['presupuesto'] = '';
		$datos['facturaOriginal'] = $facturaOriginal;

		$resultado[] = insertarFacturasDetallesTemporal($conn, $bbddSql, $datos);
	}

	sqlsrv_close($conn);

	echo json_encode($resultado);
}

?>
