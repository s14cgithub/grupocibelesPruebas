<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verEstadoFacturacionFinMes")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$estado = isset($_POST["estado"]) ? $_POST["estado"] : ''; //0 poner fecha del ultimo dia del mes anterior, si no existe ninguna factura con fecha del mes actual
																//1 poner fecha actual

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$fechaActual = date("d-m-Y");
	$anioSeleccionado = date('Y');
	$anioNuevo = false;

	$filtrosOperadoresAnio = [
		['campo1' => 'fecha', 'valor' => $anioSeleccionado.'-01-01', 'operador' => '>='],
		['campo1' => 'fecha', 'valor' => $anioSeleccionado.'-12-31', 'operador' => '<=']
	];

	$resFechaMax = mostrarFacturacion($conn, $bbddSql, ['fechaMax'], [], [], $filtrosOperadoresAnio, []);
	$ultimaFechaCibeles = [['fecha' => $resFechaMax['datos'][0]['fechaMax']]];

	if ($ultimaFechaCibeles[0]["fecha"] == null || $ultimaFechaCibeles[0]["fecha"] == "null" )
	{
		$anioNuevo = true;
	}

	$mesAnterior = strtotime ( '-1 month' , strtotime ($fechaActual));
	$ultimoDia_MesAnterior= date("t/m/Y",$mesAnterior);

	if ($estado==="0" && $anioNuevo==false)
	{
		$numero1 = intval(date("Y",$mesAnterior));
		$numero2 = intval($ultimaFechaCibeles[0]["fecha"]->format("Y"));

		$ultimoDiaMesAnteriorFecha = new DateTime();
		$ultimoDiaMesAnteriorFecha->setTimestamp($mesAnterior);
		$ultimoDiaMesAnteriorFecha->modify('last day of this month');
		$ultimoDiaMesAnteriorFecha->setTime(23, 59, 59);

		if ($ultimoDiaMesAnteriorFecha >= $ultimaFechaCibeles[0]["fecha"] && $numero1>=$numero2 ) //no existe ninguna factura de este mes
		{
			$res = modificarFacturarFechaActual($conn, $bbddSql, ['activado' => 0, 'fechaImprimir' => $ultimoDia_MesAnterior]);
			sqlsrv_close($conn);
			echo json_encode(array('error' => $res['error'], 'activado' => 0));
		}
		else
		{
			sqlsrv_close($conn);
			echo json_encode(array('error' => 'Existe facturas con fecha del mes actual'));
		}
	}
	else if ($estado==="0")
	{
		$res = modificarFacturarFechaActual($conn, $bbddSql, ['activado' => 0, 'fechaImprimir' => $ultimoDia_MesAnterior]);
		sqlsrv_close($conn);
		echo json_encode(array('error' => $res['error'], 'activado' => 0));
	}
	else if ($estado==="1")
	{
		$res = modificarFacturarFechaActual($conn, $bbddSql, ['activado' => 1, 'fechaImprimir' => $fechaActual]);
		sqlsrv_close($conn);
		echo json_encode(array('error' => $res['error'], 'activado' => 1));
	}
	else
	{
		$resEstado = mostrarFacturarFechaActual($conn, $bbddSql, ['activado']);
		sqlsrv_close($conn);
		echo json_encode(array('error' => $resEstado['error'], 'activado' => $resEstado['datos'][0]['activado']));
	}
}

?>
