<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarObservacionCliente")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idCliente = $_POST["idCliente"];
	$asunto = $_POST["asunto"];
	$texto = $_POST["texto"];
	
	$idEmpleado = $_SESSION["idEmpleado"];
	
	
	$clayma = $_POST["clayma"];
	
	if ($clayma=="true")
	{
		echo insertarObservacionClienteClayma($conexion,$idCliente,$asunto,$texto,$idEmpleado);	
	}
	else
	{
		echo insertarObservacionCliente($conexion,$idCliente,$asunto,$texto,$idEmpleado);	
	}	
	
		
	
	
}


?>
