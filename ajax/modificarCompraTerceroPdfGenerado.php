<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarPdfImpreso")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numPedido = $_POST["numPedido"];	
	
	
	echo modificarComprasTercerosPdfImpreso($conexion,$numPedido);	
			
	
}


?>
