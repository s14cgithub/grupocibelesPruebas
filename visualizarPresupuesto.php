<?php 
session_start(); 
require("comprobarSesion.php");


//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$nombrePdf = "Z:/Comercial/Privado/PRESUPUESTOS/General/".$_GET["nombre"];
	
	
	echo $nombrePdf;		
		
	header("Content-type: application/pdf");	
	
	$filename = $nombrePdf;
	$file = $nombrePdf;
	
	
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary'); 
    header('Accept-Ranges: bytes');
    @readfile($file);
	
	
}
	

?>

	
	
	
	
	
		


