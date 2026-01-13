<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	
	
	

	$meses = $_POST["meses"];
	$campana =  $_POST["orden"];

	
	//echo ".aaaaa".$campana." aaaaa";
	
	$fecha = "";
	//$meses=0;


	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		$fecha = " fecha >='".$fechaInicio."'";
	}
	
	$condicion = "where ".$fecha;	
	$condicion .= " order by ".$campana;
	
	$resultado = mostrarPresupuestos3($conexion,$condicion);
	
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo  ("");
	}	
	else
	{
		echo  json_encode($resultado);
		//echo $resultado;
	}
}


?>