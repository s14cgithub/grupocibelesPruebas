<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$nombre = $_POST["nombre"];
	$nif = $_POST["nif"];
	$servicio = $_POST["servicio"];
	$direccion = $_POST["direccion"];
	$localidad = $_POST["localidad"];
	$provincia = $_POST["provincia"];
	$cp = $_POST["cp"];
	$precioComparado = $_POST["precioComparado"];
	$telefono = $_POST["telefono"];

	
	echo insertarProveedor($conexion,$nombre,$nif,$servicio,$direccion,$localidad,$provincia,$cp,$precioComparado,$telefono);
	
	
}


?>
