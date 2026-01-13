<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verPermisosPorIdLogin")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idLogin = $_POST["idLogin"];
	
	
	
	$resultado = verPermisos($conexion,$idLogin);
	
	
	
	if (count($resultado)<=0)
	{
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>