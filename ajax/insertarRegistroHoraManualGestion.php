<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRegistroHoraManual")
{
	$ruta = '../';
	session_start();
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$res = array('error' => '', 'datos' => array());

	//solo el gestor de PDA (permiso 2) puede dar de alta registros para otros empleados
	if (!isset($_SESSION["permiso_pdaGestion"]) || $_SESSION["permiso_pdaGestion"] != 2)
	{
		$res['error'] = "Error: sin permiso";
		echo json_encode($res);
		return;
	}

	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	//aqui el idEmpleado es el del empleado seleccionado por el gestor (viene en $datos), no el de la sesion
	$idEmpleado = isset($datos["idEmpleado"]) ? $datos["idEmpleado"] : '';

	$res = insertarRegistroHoras($conn, $bbddSql, $datos);

	if ($res['error'] == '' && $idEmpleado != '')
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
