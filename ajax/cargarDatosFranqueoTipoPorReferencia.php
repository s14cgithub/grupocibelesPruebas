<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarDatosFranqueoTipoPorReferencia")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$referencia = $_POST["referencia"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	
	
	$resultado = cargarDatosFranqueoTipoPorReferencia($conexion,$referencia,$anioSeleccionado);
	
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