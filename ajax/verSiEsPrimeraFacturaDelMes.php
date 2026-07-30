<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verSiEsPrimeraFacturaDelMes")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$clayma = isset($_POST['clayma']) && $_POST['clayma']==1;

	if ($clayma)
	{
		$resFecha = mostrarFacturarFechaActualClayma($conn, $bbddSql, ['activado']);
	}
	else
	{
		$resFecha = mostrarFacturarFechaActual($conn, $bbddSql, ['activado']);
	}

	$avisar = false;

	if ($resFecha['datos'][0]['activado'] == 1)
	{
		$primerDiaMes = date('Y-m-01');
		$filtrosOperadores = [['campo1' => 'fecha', 'valor' => $primerDiaMes, 'operador' => '>=']];

		if ($clayma)
		{
			$resExiste = mostrarFacturacionClayma($conn, $bbddSql, ['numero'], [], [], $filtrosOperadores, []);
		}
		else
		{
			$resExiste = mostrarFacturacion($conn, $bbddSql, ['numero'], [], [], $filtrosOperadores, []);
		}

		$avisar = count($resExiste['datos']) <= 0;
	}

	sqlsrv_close($conn);

	echo json_encode(array('error' => '', 'avisar' => $avisar));
}

?>
