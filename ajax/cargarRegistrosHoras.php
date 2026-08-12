<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarRegistrosHoras")
{
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	session_start();

	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();
	$joins=isset($_POST["joins"])?json_decode($_POST["joins"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$group=isset($_POST["group"])?json_decode($_POST["group"], true):array();
	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();

	//el JS marca con "@sesion" el filtro que debe usar el idEmpleado logueado; el valor se coge de la sesion, nunca del cliente
	if (is_array($filtros))
	{
		foreach ($filtros as $clave => $valor)
		{
			if ($valor === "@sesion")
			{
				$filtros[$clave] = $_SESSION["idEmpleado"];
				break;
			}
		}
	}

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarRegistrosHoras($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $group);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
