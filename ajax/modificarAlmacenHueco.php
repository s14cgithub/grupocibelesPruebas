<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarHueco")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$id = $_POST["id"];
	$hueco = $_POST["hueco"];
	
	
	$condicion = " where hueco = '".$hueco."' and id != ".$id;
	
	$resultado = mostrarAlmacenHuecos($conexion, $condicion);
	
	if (count($resultado)>0)
	{
		echo "Error: el hueco ya existe";
	}
	else
	{
		echo modificarAlmacenHuecos($conexion,$id,$hueco);
	}
	
		
	
	
}


?>
