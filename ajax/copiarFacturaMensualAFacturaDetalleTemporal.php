<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="copiarFacturaAFacturaDetalleTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numeroFactura = $_POST["numeroFactura"];	
	$usuario = $_SESSION["idEmpleado"];
	//echo 'hola';
	$numPresupuesto = "Factura Original: ".$numeroFactura;
	$numeroFactura = $numeroFactura;
	
	
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	borrarFacturaDetallesTemporal($conexion,$numPresupuesto,$usuario);		
	insertarFacturasDetallesATemporal($conexion,$numPresupuesto,$usuario,$numeroFactura,$anioSeleccionado);
	
}


?>
