<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarClienteContacto")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");


	$id = $_POST["id"];
	$sexo = $_POST["sexo"];
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$departamento = $_POST["departamento"];
	$cargo = $_POST["cargo"];
	$telefono = $_POST["telefono"];
	$movil = $_POST["movil"];
	$email = $_POST["email"];
	$comentario = $_POST["comentario"];
	
	
	$clayma=$_POST["clayma"];
	
	
	
	if ($clayma=="true")
	{
		echo modificarClienteContactoClayma($conexion,$id, $sexo, $nombre, $apellidos, $departamento, $cargo, $telefono, $movil, $email, $comentario);	
	}
	else
	{
		echo modificarClienteContacto($conexion,$id, $sexo, $nombre, $apellidos, $departamento, $cargo, $telefono, $movil, $email, $comentario);	
	}
	
	
	
		
	
	
			
	
}


?>
