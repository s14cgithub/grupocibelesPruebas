<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirDetalle")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	$numPresupuesto = isset($_POST["numPresupuesto"]) ? $_POST["numPresupuesto"] : '';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = insertarDetalleCompraTercero($conn, $bbddSql, $datos);

	if ($res['error'] == '')
	{
		$datosNuevos = "descripcion: ".$datos['descripcion']."|cantidad: ".$datos['cantidad']."|precioUnitario: ".$datos['precioUnidad']."|precioVenta: ".$datos['precioVenta']."|precioTotal: ".$datos['total']."|margen: ".$datos['margen'];

		insertarRegistro($conn, $bbddSql, array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => 'Creacion',
			'datosAntiguos' => '',
			'datosNuevos' => $datosNuevos,
			'tabla' => 'comprasTercerosDetalles',
			'columna' => 'todas',
			'idRegistro' => 0,
			'presupuesto' => $numPresupuesto
		));
	}

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
