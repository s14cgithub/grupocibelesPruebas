<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarDetallesPrefactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numPresupuesto = $_POST["numPresupuesto"];
	$usuario = $_SESSION["idEmpleado"];	
	
	$resultado = mostrarFacturasDetallesTemporal($conexion,$numPresupuesto,$usuario);
	
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
