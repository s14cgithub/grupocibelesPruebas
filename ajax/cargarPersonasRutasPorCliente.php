<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarPersonasRutasPorCliente")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	session_start(); 
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idCliente= $_POST["idCliente"];	
	
	
	$personas=cargarPersonasRutasPorCliente($conexion,$idCliente);
	
	if (count($personas)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($personas);
	}
		
}

?>
