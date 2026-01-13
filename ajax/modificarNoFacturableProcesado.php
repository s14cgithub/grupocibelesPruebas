<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cambiarProcesado")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$presupuesto = $_POST["presupuesto"];
	$procesado = $_POST["procesado"];

	$procesado1=0;
	if ($procesado=="true")
	{
		$procesado1=1;
	}
	
	
	
	$resultado = modificarNoFacturableProcesado($conexion, $presupuesto,$procesado1);
	
		
	
	
	
	
	
}


?>