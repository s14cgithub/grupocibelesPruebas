<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarAlbaran")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$idRegistro = $_POST["id"];
	
	
	
	echo eliminarAlbaran($conexion,$idRegistro);
	
	
			
	
}


?>
