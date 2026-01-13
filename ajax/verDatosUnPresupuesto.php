<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosUnPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$numPresupuesto = $_POST["numPresupuesto"];	
	
	
	$texto = $numPresupuesto;
	$queBusca=presupuesto_Presupuesto;
	$orden=1;
	$desc="";	
	
	$resultado1 = mostrarPresupuestos($conexion,$orden,$desc,$texto,$queBusca);	
	
	if (count($resultado1)<=0)
	{
		$resultado = mostrarPresupuestosClayma($conexion,$orden,$desc,$texto,$queBusca);		
	}
	else if ($resultado1[0]["clayma"]=="1" || $resultado1[0]["clayma"]==1 )
	{
		$resultado = mostrarPresupuestosClayma($conexion,$orden,$desc,$texto,$queBusca);
	}
	else
	{
		$resultado = $resultado1;
	}
	
		
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