<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarOrigen")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$tamanio=cargarOrigenPapel($conexion);
	
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
