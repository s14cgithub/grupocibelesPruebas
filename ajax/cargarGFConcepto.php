<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarGFTipoProceso")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$tamanio=cargarGFConcepto($conexion);
	
	if (count($tamanio)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($tamanio);
	}
		
}

?>
