<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="listadoFacturas_Sumatorio")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	
	$condicion = $_POST["condicion"];	
	$condicion1 = explode("order by", $condicion);
	
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	
	$resultado = mostrarListadoFacturas_SumatorioTodo($conexion, $condicion1[0], $anioSeleccionado);
	
	
	
	
	
	
	
	
	
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