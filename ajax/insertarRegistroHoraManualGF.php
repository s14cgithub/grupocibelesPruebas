<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRegistroHoraManual")
{
	$ruta = '../';
	session_start();
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();

	//el idEmpleado siempre es el de la sesion, nunca del cliente
	$idEmpleado = $_SESSION["idEmpleado"];
	$datos["idEmpleado"] = $idEmpleado;

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = insertarRegistroHoras($conn, $bbddSql, $datos);

	if ($res['error'] == '')
	{
		$resUltimo = cargarRegistrosHoras($conn, $bbddSql, ['id'], [], ['maxIdPorEmpleado' => $idEmpleado], [], array());
		$resEmpleado = cargarEmpleados($conn, $bbddSql, ['nombre','apellidos'], ['id' => $idEmpleado], array(), array());

		if (count($resUltimo['datos']) > 0 && count($resEmpleado['datos']) > 0)
		{
			$nombreEmpleado = $resEmpleado['datos'][0]["nombre"]." ".$resEmpleado['datos'][0]["apellidos"];
			modificarRegistroHoras($conn, $bbddSql, ['nombreEmpleado' => $nombreEmpleado], ['id' => $resUltimo['datos'][0]["id"]]);
		}
	}

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
