<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosPresuNoFac")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$numero = $_POST["numero"];	
	
	$condicion = " where numNoFactura = ".$numero;
	
	$resultado = mostrarPresupuestos2($conexion, $condicion);
	
	
	
	
		
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("Error: no existe ese presupuesto");
	}	
	else
	{
		echo  json_encode($resultado);
	}
	
}


?>