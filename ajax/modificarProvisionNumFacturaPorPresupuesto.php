<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarProvisionNumFacturaPorPresupuesto")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numPresupuesto = $_POST["numPresupuesto"];
	
	$numFactura = $_POST["numFactura"];
	
	$anioSeleccionado = $_POST["anioSeleccionado"];	

	$numeroFacturaCompleto = $_POST["facCompleto"];
	
	
	
		
	echo modificarProvisionNumFacturaPorPresupuesto($conexion,$numPresupuesto,$numFactura,$anioSeleccionado,$numeroFacturaCompleto);	
	
			
	
}


?>
