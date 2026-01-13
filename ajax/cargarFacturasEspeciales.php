<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarFacturasEspeciales")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$albaranes=cargarFacturasEspeciales($conexion);
	
	if (count($albaranes)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($albaranes);
	}
		
}

?>
