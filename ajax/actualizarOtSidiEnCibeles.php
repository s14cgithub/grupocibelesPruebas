<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="actualizarOtSidiACibeles")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	echo actualizarOtSidiACibeles($conexion);
	
	
	
}


?>