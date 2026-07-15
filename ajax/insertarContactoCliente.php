<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarContactoCliente")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	


	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();

	$datos["idEmpleado"] = $_SESSION["idEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	
	$res =  insertarClientesContactos($conn,$bbddSql, $datos);

	sqlsrv_close($conn);	
	echo json_encode($res);
}


?>
