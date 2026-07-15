<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarClientesContactos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();
	$joins = isset($_POST["joins"]) ? json_decode($_POST["joins"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];


	$res = cargarClientesContactosClayma($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order,$joins);
	
	echo json_encode($res);
	sqlsrv_close($conn);

		
}

?>
