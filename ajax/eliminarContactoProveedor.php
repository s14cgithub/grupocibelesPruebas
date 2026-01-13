<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarContactoProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idContacto = $_POST["idContacto"];
			
	
	echo eliminarContactoProveedor($conexion,$idContacto);
	
	
	
	
	
}



?>