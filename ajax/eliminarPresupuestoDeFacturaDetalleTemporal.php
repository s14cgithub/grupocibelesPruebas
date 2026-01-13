<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="mostrarEliminarPresupuestoDeFacturaDetalleTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numPresupuesto = $_POST["numPresupuesto"];
	$usuario = $_SESSION["idEmpleado"];
	
	
	
		borrarFacturaDetallesTemporal($conexion,$numPresupuesto,$usuario);	
	
	
	
	

	
	
	
	
	
	
}


?>
