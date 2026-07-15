<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarClienteDireccionRutas")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();


	
	
	$filtros1["idCliente"] = $filtros["idCliente"];	
	$datos1 = array(
		"activo" => 0	
	);	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	modificarClientesDirecRutasClayma($conn, $bbddSql, $datos1, $filtros1, $filtrosOperadores);

	$filtros2["id"] = $filtros["id"];	
	$filtrosOperadores2 = array();

	$res = modificarClientesDirecRutasClayma($conn, $bbddSql, $datos, $filtros2, $filtrosOperadores2);

	
	sqlsrv_close($conn);
	echo json_encode($res);
	
}


?>
