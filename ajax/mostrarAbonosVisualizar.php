<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarListadoAbonos")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$clayma = $_POST["clayma"];

	
	$condicion = $_POST["condicion"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	
	
	
	if ($clayma=="true")
	{
		$resultado = mostrarAbonosClayma($conexion,$condicion,$anioSeleccionado);
	}
	else
	{
		$resultado = mostrarAbonos($conexion,$condicion,$anioSeleccionado);
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