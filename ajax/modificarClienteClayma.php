<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarCliente")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$datosIncremento = array();
	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	

	$res = modificarClientesClayma($conn, $bbddSql, $datos, $filtros, $filtrosOperadores, $datosIncremento);
	
	sqlsrv_close($conn);	
	echo json_encode($res);
	
}


?>
