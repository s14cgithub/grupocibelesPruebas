<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="rellenarHistoricoFranqueoEnviosEspeciales")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$franqueo=mostrarHistoricoFranqueoEnviosEspeciales($conexion);
	
	
	
	if (count($franqueo)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($franqueo);
	}
		
}

?>
