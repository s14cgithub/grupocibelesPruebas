<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="grabarFranqueo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];	
	$fecha = $_POST["fecha"];
	$ot = $_POST["ot"];
	$envios = $_POST["envios"];
	$detalle = $_POST["detalle"];
	
	
	
	$anioSeleccionado =  date("Y", strtotime($fecha));

	echo modificarFranqueoPagado($conexion,$anioSeleccionado,$id,$ot,$envios,$detalle);
		
	
	
	
	
}


?>
