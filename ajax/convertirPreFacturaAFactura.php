<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="convertirPreAFactura")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numFactura = $_POST["numFactura"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	$clayma = $_POST["clayma"];

	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
	}
	
	echo convertirPrefacturaAFactura($conexion,$numFactura,$anioSeleccionado,$clayma1);
		
}

?>
