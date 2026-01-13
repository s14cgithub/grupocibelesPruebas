<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarContactoProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idProveedor = $_POST["idProveedor"];
	$idSexo = $_POST["idSexo"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$departamento = $_POST["departamento"];
	$cargo = $_POST["cargo"];
	$telefono = $_POST["telefono"];
	$movil = $_POST["movil"];
	$email = $_POST["email"];
	$comentario = $_POST["comentario"];	
	
	

	
	
	
	echo insertarContactoProveedor($conexion,$idProveedor,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario);
		
}


?>
