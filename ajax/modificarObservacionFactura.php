<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarObservacionFactura")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numeroFactura=$_POST["numeroFactura"];
	$observacion=$_POST["observacion"];
	$clayma = $_POST["clayma"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	if ($clayma == "true")
	{
		echo modificarObservacionFacturaClayma($conexion,$numeroFactura,$observacion,$anioSeleccionado);
	}
	else
	{
		echo modificarObservacionFactura($conexion,$numeroFactura,$observacion,$anioSeleccionado);
	}
	
	
	
		
}

?>
