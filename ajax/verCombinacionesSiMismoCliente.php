<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verCombinacionesSiMismoCliente")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$clayma = $_POST["clayma"];	
	$usuario = $_SESSION["idEmpleado"];	
	
	
	
	
	if ($clayma=="true")
	{
		$resultado = verCombinacionesSiMismoClienteClayma($conexion,$usuario);
	}
	else
	{
		$resultado = verCombinacionesSiMismoCliente($conexion,$usuario);
	}
	
	
	
		
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("Error: no nada para combinar");
	}	
	else
	{
		echo  json_encode($resultado);
	}
	
}


?>