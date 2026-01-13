<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarImpresoras")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$impresoras=cargarImpresoras($conexion);
	
	if (count($impresoras)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($impresoras);
	}
		
}

?>
