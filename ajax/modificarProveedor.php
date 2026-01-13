<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idProveedor = $_POST["idProveedor"];
	
	$nombre = $_POST["nombre"];
	$nif = $_POST["nif"];
	$servicio = $_POST["servicio"];
	$direccion = $_POST["direccion"];
	$localidad = $_POST["localidad"];
	$provincia = $_POST["provincia"];
	$cp = $_POST["cp"];
	$precioComparado = $_POST["precioComparado"];
	$fechaAlta = $_POST["fechaAlta"];
	$homologado = $_POST["homologado"];
	$motivoDeshomologado = $_POST["motivoDeshomologado"];
	
	
	
	$homologado1=0;
	if ($homologado=="true")
	{
		$homologado1=1;
	}
	
	
	
	echo modificarProveedor($conexion,$idProveedor,$nombre,$nif,$servicio,$direccion,$localidad,$provincia,$cp,$precioComparado,$fechaAlta,$homologado1,$motivoDeshomologado);	
	
	
	
	
		
	
	
			
	
}


?>
