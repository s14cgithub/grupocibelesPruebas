<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarModalidad")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	

	$condicion = $_POST["condicion"];
	
	$resultado = cargarModalidadAlmacen($conexion,$condicion);
	
	
	
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