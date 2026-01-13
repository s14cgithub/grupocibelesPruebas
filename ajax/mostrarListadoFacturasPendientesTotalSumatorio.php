<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarListadoFacturasPendientesTotal")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	
	$condicion = $_POST["condicion"];
	
	
	$condicion1 = explode("order by", $condicion);
	
	
	
	$resultado = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, $condicion1[0]);
	
	
	
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