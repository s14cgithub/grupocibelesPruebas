<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cambiarContrasenia")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	//require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	$usuario = $_SESSION['usuario'];
	$contrasena = $_POST["contra"];
	
		
	echo cambiarLogin($conexion,$usuario,$contrasena);
		
		
}

?>
