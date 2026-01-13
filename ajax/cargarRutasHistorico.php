<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarRutasHistorico")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	session_start(); 
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$resultado=cargarRutasHistorico($conexion);
	
	if (count($resultado)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($resultado);
	}
		
}

?>
