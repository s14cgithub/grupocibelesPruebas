<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="copiarTemporalDetallesRec")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	

	$idEmpleado = $_SESSION["idEmpleado"];

	$facturaOriginal = $_POST["facturaOriginal"];	
	$clayma = $_POST["clayma"];

	$datosNumFactura=explode('/',$facturaOriginal);
	$anioSeleccionado = 2000+ $datosNumFactura[1];	

	$datosNumFactura2=explode(' ',$datosNumFactura[0]);
	$soloNumFactura = $datosNumFactura2[1];

	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;

		echo copiarTemporalDetallesRecClayma($conexion, $idEmpleado,$anioSeleccionado,$soloNumFactura,$facturaOriginal);
	}
	else
	{
		echo copiarTemporalDetallesRec($conexion, $idEmpleado,$anioSeleccionado,$soloNumFactura,$facturaOriginal);
	}
		
	
	
	


	
}

?>