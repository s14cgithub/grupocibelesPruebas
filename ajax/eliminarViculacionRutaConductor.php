<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarVinculacionRutaConductor")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$id = $_POST["id"];

	
	
	
	
	echo eliminarVinculacionRutaConductor($conexion,$id);
	
		
}

?>
