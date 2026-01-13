<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	
	$idContacto = $_POST["idContacto"];
	$idSexo = $_POST["idSexo"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$departamento = $_POST["departamento"];
	$cargo = $_POST["cargo"];
	$telefono = $_POST["telefono"];
	$movil = $_POST["movil"];
	$email = $_POST["email"];
	$comentario = $_POST["comentario"];
	
	
	echo modificarContactoProveedor($conexion,$idContacto,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario);	
	
	
	
		
	
	
			
	
}


?>
