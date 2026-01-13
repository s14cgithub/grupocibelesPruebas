<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarNombreRuta")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	session_start(); 
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$nombre= $_POST["nombre"];	
	$dni= $_POST["dni"];	
	
	
	echo cargarDniRuta($conexion,$nombre,$dni);
	
	
		
}

?>
