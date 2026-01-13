<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="anadirDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	


	$facturaOriginal = $_POST["facturaOriginal"];	
	$clayma = $_POST["clayma"];		
	$proceso = $_POST["proceso"];	
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];	
	$usuario = $_SESSION["idEmpleado"];	

	$clayma1=0;
	if ($clayma=="true")
		$clayma1=1;
	
	

	if ($_POST["exentoIVA"]=="true")
		$exentoIVA = 1;
	else
		$exentoIVA = 0;

		
	echo insertarDetalleRecTemporal($conexion,$facturaOriginal,$proceso,$descripcion,$nota,$unidad,$precio,$usuario,$exentoIVA,$clayma1);
	
	
}

?>