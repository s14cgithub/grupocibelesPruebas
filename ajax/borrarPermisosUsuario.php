<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarPermisosUsuario")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id_usuario = $_POST["id_usuario"];	
	
	
		
	echo borrarPermisosUsuario($conexion,$id_usuario);
	
	
}

?>