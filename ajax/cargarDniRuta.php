<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarDniRuta")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	session_start(); 
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$nombre= $_POST["nombre"];	
	
	
	$dni=cargarDniRuta($conexion,$nombre);
	
	if (count($dni)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($dni);
	}
		
}

?>
