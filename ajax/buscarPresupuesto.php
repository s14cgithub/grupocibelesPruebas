<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="buscarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$numPresupuesto = $_POST["numPresuCopiar"];
	
	$resultado = verPresupuesto($conexion,$numPresupuesto);
	
	
	
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