<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="insertarTamanio")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	
	$valor = $_POST["valor"];

	echo insertarTamanioPapel($conexion, $valor);
	
}


?>