<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarHueco")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$hueco = $_POST["hueco"];
	
	$condicion = " where hueco= '".$hueco."'";	
	$resultado = mostrarAlmacenHuecos($conexion, $condicion);
	
	if (count($resultado)>0)
	{
		echo "Error: el hueco ya exite.";
	}	
	else
	{
		echo insertarAlmacenHueco($conexion,$hueco);
		
	}
	
	
}


?>
