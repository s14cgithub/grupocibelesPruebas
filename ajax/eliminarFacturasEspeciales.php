<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="limpiarFacturasEspeciales2")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	
		
	echo eliminarFacturasEspeciales($conexion);
	
	
	
	
}



?>