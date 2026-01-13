<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarListadoSaldosSumatorio")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	
	
	$condicion = $_POST["condicion"];
	
	$clayma = $_POST["clayma"];
	
	
	
	$condicion2  = explode("order by", $condicion);
	
	
	
	
	if ($clayma == 1)
	{
		$resultado = mostrarListadoSaldosSumatorioClayma($conexion, $condicion2[0]);
	}
	else
	{
		$resultado = mostrarListadoSaldosSumatorio($conexion, $condicion2[0]);
	}

		

	
	
	
	
	
	
	if (count($resultado)<=0)
	{
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>