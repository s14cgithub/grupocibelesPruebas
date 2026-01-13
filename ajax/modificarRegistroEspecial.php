<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistroEspecial")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	$concepto=$_POST["concepto"];
	$unidades=$_POST["unidades"];
	$precioUnitario=$_POST["precioUnitario"];
	
	
	
	
	
	
	modificarRegistroEspecial($conexion,$id,$concepto,$unidades,$precioUnitario);
	
	
	
	
}



?>