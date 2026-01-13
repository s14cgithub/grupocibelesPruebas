<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarImporteEnClienteFac")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idCliente = $_POST["idCliente"];
	$importe = $_POST["importe"];
	$clayma = $_PSOT["clayma"];
		
	
	/////////////////////////////////////////////
	
	
	if ($clayma=="true")
	{
		echo modificarImporteEnClienteFacClayma($conexion,$idCliente,$importe);
	}
	else
	{
		echo modificarImporteEnClienteFac($conexion,$idCliente,$importe);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
			
	
}


?>
