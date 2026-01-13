<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarNumeroFactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	
	
	
	$idPedido =  $_POST["idPedido"];
	$numeroFactura =  $_POST["numeroFactura"];
	$fechaFactura =  $_POST["fechaFactura"];
	
	
	
	echo insertarFacturaEnCompraTerceros($conexion,$idPedido,$numeroFactura,$fechaFactura);
	
}


?>
