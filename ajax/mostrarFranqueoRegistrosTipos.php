<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarRegistrosFranqueoTipos")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$condicion = $_POST["condicion"];

	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$resultado = cargarListadoFranqueoTipos($conexion,$condicion,$anioSeleccionado);	
	
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