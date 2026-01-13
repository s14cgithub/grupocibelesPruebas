<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="borrarTemporalDetallesRec")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	

	$idEmpleado = $_SESSION["idEmpleado"];
	
	
	echo borrarDetallesTemporalesFacRec($conexion,$idEmpleado);
	
	
}

?>