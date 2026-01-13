<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarTarifaTipoImpresora")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idTipoImpresora=$_POST["idTipoImpresora"];	
	$precio=$_POST["precio"];	
	
	echo modificarTarifaTipoImpresora($conexion,$idTipoImpresora,$precio);
}

?>
