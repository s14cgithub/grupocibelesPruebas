<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoTipoAlabaran")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$tipo=cargarListadoTipoAlabaran($conexion);
	
	if (count($tipo)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($tipo);
	}
		
}

?>
