<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTarifasTipoImpresora")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	

	$resultado = mostrarTarifasTipoImpresora($conexion);
	
	
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