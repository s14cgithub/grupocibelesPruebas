<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTarifasGranFormato")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	

	$resultado = mostrarTarifasGranFormato($conexion);
	
	
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