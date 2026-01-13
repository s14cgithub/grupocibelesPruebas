<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarPaises")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$condicion = isset($_POST["condicion"]) ? $_POST["condicion"] : "";
	
	
	$paises=cargarPaises($conexion,$condicion);

	

	
	if (count($paises)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($paises);
	}
		
}

?>
