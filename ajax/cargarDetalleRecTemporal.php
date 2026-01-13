<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarDetallesRegistrosFacRec")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$facturaOriginal = $_POST["facturaOriginal"];
	$clayma = $_POST["clayma"];
	
	$usuario = $_SESSION["idEmpleado"];	

	$clayma1=0;
	if ($clayma=="true")
		$clayma1=1;
	
	$resultado = mostrarFacRecDetallesTemporal($conexion,$facturaOriginal,$clayma1,$usuario);
	
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
