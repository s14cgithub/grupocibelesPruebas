<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="cargarLasRutasDelDia")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$idEmpleado =  $_SESSION["idEmpleado"];
	
	
	$ruta = verRutaPorIdEmpleado($conexion, $idEmpleado);
	//echo ("ruta: ". $ruta[0]["ruta"]);
	if (count($ruta)<=0)
	{
		echo ("Error: no tienes ninguna ruta vinculada.");
	}
	else
	{
		$resultado = cargarLasRutasDelDia($conexion,$ruta[0]["ruta"]);
	
	
		if (count($resultado)<=0)
		{
			echo  json_encode("");
		}	
		else
		{
			echo  json_encode($resultado);
		}
	}
	
	
	
	
	
	
}

?>