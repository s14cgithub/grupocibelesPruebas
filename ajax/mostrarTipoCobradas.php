<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTipoCobradas")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	$subProcesos=cargarTiposCobrada($conexion);
	
	if (count($subProcesos)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($subProcesos);
	}
		
}

?>
