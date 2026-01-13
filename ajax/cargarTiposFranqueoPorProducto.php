<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTiposFranqueoPorProducto")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idProductoPadre = $_POST["idProductoPadre"];
	
	
	$resultado = cargarTiposFranqueoPorProducto($conexion,$idProductoPadre);
	
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