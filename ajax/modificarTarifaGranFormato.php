<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarTarifaGranFormato")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idGranFormato=$_POST["idGranFormato"];	
	$precio=$_POST["precio"];	
	
	echo modificarTarifaGranFormato($conexion,$idGranFormato,$precio);
}

?>
