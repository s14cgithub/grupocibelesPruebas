<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="grabarFranqueoEnvioEspecial")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idCliente = $_POST["idCliente"];
	$fecha = $_POST["fecha"];
	$gramos = $_POST["gramos"];
	$ot = $_POST["ot"];
	$totalEnvios = $_POST["totalEnvios"];	
	$cantidad = $_POST["precioUnidad"];
	$precioTotal = $_POST["precioTotal"];
		
	
	
	echo insertarGrabacionFranqueoTiposEspeciales($conexion,$idCliente, $ot, $fecha, '1', $totalEnvios, $precioTotal,$gramos);
		
	
	
	
	
}

?>
