<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarPeriodosFacturacion")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$comerciales=cargarPeriodosFacturacion($conexion);
	
	if (count($comerciales)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($comerciales);
	}
		
}

?>
