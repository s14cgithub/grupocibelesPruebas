<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarDatos")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	echo date('d/m/Y');
}


?>