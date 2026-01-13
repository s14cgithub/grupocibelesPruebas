<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="insertarTamanioConversor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	
	$idTamanioInicio = $_POST["idTamanioInicio"];
	$idTamanioFinal = $_POST["idTamanioFinal"];
	$valorTamanioConversor = $_POST["valorTamanioConversor"];


	echo insertarTamanioConversor($conexion, $idTamanioInicio, $idTamanioFinal, $valorTamanioConversor);
	
}


?>