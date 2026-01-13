<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTarifasProductos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	$contactos=cargarlistadoTarifasProductos($conexion);
	
	if (count($contactos)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($contactos);
	}
		
}

?>
