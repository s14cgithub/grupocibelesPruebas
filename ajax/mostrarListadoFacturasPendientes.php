<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarListadoFacturasPendientes")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$clayma=$_POST["clayma"];
	
	$condicion=$_POST["condicion"];
	
	
	if ($clayma=="true")
	{
		
		$resultado = mostrarListadoFacturasPendientesClayma($conexion,$condicion);
	}
	else
	{
		$resultado = mostrarListadoFacturasPendientes($conexion,$condicion);
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