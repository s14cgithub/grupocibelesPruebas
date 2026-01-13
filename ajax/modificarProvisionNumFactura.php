<?php 

if(isset($_POST["accion"])&& $_POST["accion"]=="modificarProvisionNumFactura")
{
	//no se pone facCompleto porque la prefacturaMensual ya no se va a utilizar
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idProvision = $_POST["idProvision"];
	
	$numFactura = $_POST["numFactura"];
	
	
	
	
		
	echo modificarProvisionNumFactura($conexion,$idProvision,$numFactura);	
	
			
	
}


?>
