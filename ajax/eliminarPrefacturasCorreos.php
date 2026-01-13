<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarPreFacturasCorreos")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	echo eliminarPreFacturasCorreos($conexion);
	
}


?>
