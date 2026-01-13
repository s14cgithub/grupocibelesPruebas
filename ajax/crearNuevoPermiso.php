<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="crearPermisosNuevo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idLogin = $_POST["idLogin"];	
	
	
		
	echo crearNuevoPermiso($conexion,$idLogin);
	
	
}

?>