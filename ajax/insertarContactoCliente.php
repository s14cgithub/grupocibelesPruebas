<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarContactoCliente")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idCliente = $_POST["idCliente"];
	$idSexo = $_POST["idSexo"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$departamento = $_POST["departamento"];
	$cargo = $_POST["cargo"];
	$telefono = $_POST["telefono"];
	$movil = $_POST["movil"];
	$email = $_POST["email"];
	$comentario = $_POST["comentario"];	
	
	
	$clayma = $_POST["clayma"];
	
	if ($clayma=="true")
	{
		echo insertarContactoClienteClayma($conexion,$idCliente,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario);
	}
	else
	{
		echo insertarContactoCliente($conexion,$idCliente,$idSexo,$nombre,$apellidos,$departamento,$cargo,$telefono,$movil,$email,$comentario);	
	}	
}


?>
