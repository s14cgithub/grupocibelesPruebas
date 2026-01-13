<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarFacturaMensualAbono")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	
	
	$numFactura = $_POST['numFactura'];
	
	
	echo eliminarFacturaMensualPendiente ($conexion, $numFactura);		
		
	
	
	
	
	
	
	
	
	
			
	
}


?>
