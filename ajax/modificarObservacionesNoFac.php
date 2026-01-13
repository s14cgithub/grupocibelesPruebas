<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarObservacion")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numero = $_POST["numero"];
	$observacion = $_POST["observacion"];
		
	echo modificarObservacionNoFac($conexion,$numero,$observacion);	
	
			
	
}


?>
