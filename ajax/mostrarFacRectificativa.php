<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoFacturas")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$clayma = $_POST["clayma"];	
	$condicion = $_POST["condicion"];
	$anio = $_POST["anio"];
	
	if ($clayma=="true")
	{
		$resultado = mostrarFactRectificativasClayma($conexion,$condicion,$anio);
	}
	else
	{
		$resultado = mostrarFactRectificativas($conexion,$condicion,$anio);
	}
	
	
		
	
	
	if (count($resultado)<=0)
	{
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>