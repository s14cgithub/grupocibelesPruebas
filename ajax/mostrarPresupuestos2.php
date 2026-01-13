<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$condicion = $_POST["condicion"];
	
	$modo = isset($_POST["modo"]) ? $_POST["modo"] : "0";

	$resultado = mostrarPresupuestos2($conexion,$condicion,$modo);
	
	
	
	
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