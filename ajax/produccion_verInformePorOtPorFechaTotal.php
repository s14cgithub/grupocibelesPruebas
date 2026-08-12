<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verInformePorDia")
{
	$ruta = '../';

	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();

	$fechaInicio = isset($filtros["fechaInicio"]) ? $filtros["fechaInicio"] : '';
	$fechaFin = isset($filtros["fechaFin"]) ? $filtros["fechaFin"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resDetalle = cargarOtPorFechas($conn, $bbddSql, $fechaInicio, $fechaFin);

	sqlsrv_close($conn);

	if ($resDetalle['error'] != '')
	{
		echo json_encode(array('error' => $resDetalle['error'], 'datos' => array()));
		exit;
	}

	$minutosTrabajados = 0;
	$horasTrabajados = 0;
	$cantidad = 0;
	$cantidad2 = 0;
	$cantidadRealizada = 0;

	foreach ($resDetalle['datos'] as $fila)
	{
		$minutosTrabajados += $fila['minutosTrabajados'];
		$horasTrabajados += $fila['horasTrabajados'];
		$cantidad += $fila['cantidad'];
		$cantidad2 += $fila['cantidad2'];
		$cantidadRealizada += $fila['cantidadRealizada'];
	}

	$tantoPorcientoRealizado = ($cantidad2 == 0 || $cantidad == 0) ? 0 : (int)($cantidadRealizada * 100 / $cantidad2);

	$total = array(array(
		'minutosTrabajados' => $minutosTrabajados,
		'horasTrabajados' => $horasTrabajados,
		'cantidad' => $cantidad,
		'cantidad2' => $cantidad2,
		'cantidadRealizada' => $cantidadRealizada,
		'tantoPorcientoRealizado' => $tantoPorcientoRealizado
	));

	echo json_encode(array('error' => '', 'datos' => $total));
}

?>
