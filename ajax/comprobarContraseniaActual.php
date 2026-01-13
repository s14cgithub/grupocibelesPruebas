<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="comprobarContrasenia")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	//require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	$usuario = $_SESSION['usuario'];
	$contrasena = $_POST["contra"];
	
		
		$login =comprobarLogin($conexion,$usuario,$contrasena);
		
		$numFilas = count($login);		
		
		
		if ($numFilas > 1)
		{			
			echo ("Error: Hay más de una persona con el mismo usuario.\nContactar con el administrador.");
		}
		else if ($numFilas<1)
		{			
			echo ("Error: la contraseña actual es incorrecta");
		}
		else if ($numFilas==1)
		{
			echo("Correcto");
		}
		else
		{
			echo ("Error: al comprobar la contraseña actual\nContactar con el administrador.");
		}
}

?>
