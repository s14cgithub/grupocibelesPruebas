<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="cargarAnios")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$anios=cargarAnios($conexion);
	
	//echo "num: ".count($anios);
	if (count($anios)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($anios);
		
	}
		
}

?>
