<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="grabarFranqueo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];	
	$fecha = $_POST["fecha"];
	
	
	
	$anioSeleccionado =  date("Y", strtotime($fecha));

	echo eliminarFranqueoPagado($conexion,$anioSeleccionado,$id);
		
	
	
	
	
}


?>
