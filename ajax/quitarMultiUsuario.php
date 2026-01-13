<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="quitarMultiUsuario")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idMultiEmpleado = $_POST["idMultiEmpleado"];
	$idUsuario = $_SESSION["idEmpleado"];
	

	
	
	echo quitarMultiUsuario($conexion, $idUsuario, $idMultiEmpleado);
	
	
}


?>