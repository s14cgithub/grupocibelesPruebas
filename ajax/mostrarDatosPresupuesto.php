<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarDatosPresupuesto")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$numPresupuesto = isset($_POST["numPresupuesto"]) ? $_POST["numPresupuesto"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarPresupuestos(
		$conn, $bbddSql,
		array('cliente', 'fecha', 'numFactura', 'fechaFac'),
		array('tabla10'),
		array('presupuesto' => $numPresupuesto),
		array(),
		array()
	);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
