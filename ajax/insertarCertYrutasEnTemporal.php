<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarCertYrutasEnTemporal")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$mes = $_POST["mes"];
	$anio = $_POST["anio"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resEstado = mostrarFacturarFechaActual($conn, $bbddSql, ['activado','fechaImprimir']);

	if ($resEstado['datos'][0]['activado']=="0")
	{
		$fechaFac = $resEstado['datos'][0]['fechaImprimir']->format("d-m-Y");
	}
	else
	{
		$fechaFac = date('d-m-Y');
	}

	$fecha = new DateTime($anio.'-'.$mes.'-01');
	$primerDia = $fecha->format('d/m/Y');

	$fecha->modify('first day of next month');
	$ultimoDia = $fecha->format('d/m/Y');

	$res = insertarCertYrutasEnTemporal($conn, $bbddSql, ['fechaFac' => $fechaFac, 'primerDia' => $primerDia, 'ultimoDia' => $ultimoDia]);

	sqlsrv_close($conn);

	$res['fechaFac'] = $fechaFac;

	echo json_encode($res);
}

?>
