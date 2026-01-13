<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verSiTieneNombreYdniRuta")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idCliente = $_POST["idCliente"];
	$horaRuta = $_POST["horaRuta"];
			
	
	echo verSiTieneNombreYdniRuta ($conexion, $idCliente,$horaRuta);
	
	
	
	
}

?>