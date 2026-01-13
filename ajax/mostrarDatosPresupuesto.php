<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarDatosPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$numPresupuesto = $_POST["numPresupuesto"];	
	
	
	
	$resultado = verPresupuesto($conexion,$numPresupuesto);
	
	
		
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("no existe el presupuesto");
	}	
	else
	{
		echo  json_encode($resultado);
	}
	
}


?>