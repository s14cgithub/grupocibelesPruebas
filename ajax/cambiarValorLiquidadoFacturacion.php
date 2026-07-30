<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cambiarValorLiquidadoFacturacion")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$numeroFacturaCompleto = isset($_POST["numeroFacturaCompleto"]) ? $_POST["numeroFacturaCompleto"] : '';
	$clayma = isset($_POST["clayma"]) ? $_POST["clayma"] : 'false';
	$liquidado = (isset($_POST["liquidado"]) && $_POST["liquidado"]=="true") ? 1 : 0;

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$datos = array('liquidado' => $liquidado);
	$filtros = array('numeroFacturaCompleto' => $numeroFacturaCompleto);

	if ($clayma=="true")
	{
		$res = modificarFacturacionClayma($conn, $bbddSql, $datos, $filtros, array());
	}
	else
	{
		$res = modificarFacturacion($conn, $bbddSql, $datos, $filtros, array());
	}

	//LOG
	$datos2 = array(
		'usuario' => $_SESSION['usuario'],
		'descripcion' => log_modificacion,
		'tabla' => facturas_tabla,
		'datosAntiguos' => '',
		'datosNuevos' => $liquidado,
		'columna' => 'liquidado',
		'idRegistro' => 0,
		'presupuesto' => '',
		'clayma' => $clayma=="true" ? 1 : 0
	);
	insertarRegistro($conn, $bbddSql, $datos2);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
