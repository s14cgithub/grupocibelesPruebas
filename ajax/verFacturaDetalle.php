<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDetalles")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numFactura=$_POST["numFactura"];
	$anioSeleccionado=$_POST["anioSeleccionado"];
	$clayma=$_POST["clayma"];
	
	if ($clayma=="true")
	{ 
		$registros = verFacturaDetalleClayma($conexion,$numFactura,$anioSeleccionado);
	}
	else
	{
		$registros = verFacturaDetalle($conexion,$numFactura,$anioSeleccionado);
	}
	
	
	
	if (count($registros)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($registros);
	}
		
}

?>
