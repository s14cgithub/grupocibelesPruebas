<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarRegistroUsuario")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$idEmpleado = $_POST["idEmpleado"];
	$usuarioNuevo = $_POST["usuarioNuevo"];
	$contrasenaNuevo = $_POST["contrasenaNuevo"];	
	
	
	
	$resultado =  verSiExisteUsuario($conexion,$usuarioNuevo);
	
	if ($resultado == "true")
	{
		echo ("Error: El usuario ya existe");
	}
	else
	{
		echo insertarRegistroUsuario($conexion,$idEmpleado,$usuarioNuevo,$contrasenaNuevo);
	}
	
		
	
	
	
			
	
}


?>
