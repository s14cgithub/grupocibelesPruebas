<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistroUsuario")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	$idEmpleado = $_POST["idEmpleado"];	
	$usuario = $_POST["usuario"];
	$contrasena = $_POST["contrasena"];
	$contrasena = trim($contrasena);
	
		
	echo modificarRegistroLogin($conexion,$id,$idEmpleado,$usuario,$contrasena);	
	
			
	
}


?>
