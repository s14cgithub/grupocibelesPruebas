<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="eliminarTotoFacturaDetalleTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$usuario = $_SESSION["idEmpleado"];
	
	
		borrarPrefacturaCabeceraTemporal($conexion,$usuario);	
		borrarFacturaDetallesTemporalPorIdEmpleado($conexion,$usuario);	
	
	
	
	

	
	
	
	
	
	
}


?>
