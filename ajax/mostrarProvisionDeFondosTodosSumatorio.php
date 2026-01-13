<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarProvisionFondoSumatorio")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$condicion = $_POST["condicion"];
	
	$datosCondicion = explode("order by",$condicion);
	//echo "\nCondicion: ".$condicion."\n";
	//echo $condicion;
	
	
	$resultado = mostrarProvisionDeFondosTodos_Sumatorio($conexion,$datosCondicion[0]);
	
	
	
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