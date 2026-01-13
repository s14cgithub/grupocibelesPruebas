<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];	
	
	
	eliminarDetalleRecTemporal($conexion,$idDetalle);
	
	
}



?>