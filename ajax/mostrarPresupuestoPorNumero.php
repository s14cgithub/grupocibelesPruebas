<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarPresupuestoPorNumero")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$orden = 'presupuesto';
	$desc = '';
	$texto =  $_POST["numeroPresupuesto"];
	$queBusca =  presupuesto_Presupuesto;
	
	
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