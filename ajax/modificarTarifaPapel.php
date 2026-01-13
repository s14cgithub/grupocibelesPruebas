<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarTarifaPapel")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idPapel=$_POST["idPapel"];	
	$precio=$_POST["precio"];	
	
	echo modificarTarifaPapel($conexion,$idPapel,$precio);
}

?>
