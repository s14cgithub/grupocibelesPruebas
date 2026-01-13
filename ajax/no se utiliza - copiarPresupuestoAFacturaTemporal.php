<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="consultaCopiarPresupuestoAFacturaTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numPresupuesto = $_POST["numPresupuesto"];
	
	borrarFacturaTemporal($conexion,$numPresupuesto);	
	
	insertarPresupuestoATemporal($conexion,$numPresupuesto);
	
	$resultado = mostrarFacturasTemporal($conexion,$numPresupuesto);
	
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
