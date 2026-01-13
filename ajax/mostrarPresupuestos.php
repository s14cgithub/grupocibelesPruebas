<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$orden = $_POST["orden"];
	$desc = $_POST["desc"];
	$texto =  $_POST["texto"];
	$queBusca =  $_POST["queBusca"];
	
	
	$resultado = mostrarPresupuestos($conexion,$orden,$desc,$texto,$queBusca);
	
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>