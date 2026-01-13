<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarRutasConductoresVinculaciones")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$idEmpleado = $_POST["idEmpleado"];
	$ruta = $_POST["ruta"];
	
	
	
	if(verSiExisteVinculacion($conexion,$idEmpleado,$ruta))
	{
		echo ("Error: El conductor o la ruta ya esta vinculado");
	}
	else
	{
		echo insertarVinculacionRutaConductor($conexion,$idEmpleado,$ruta);
	}	
		
}

?>
