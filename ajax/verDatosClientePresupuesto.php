<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosClientePresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$cliente = $_POST["cliente"];	
	$clayma = $_POST["clayma"];
	
	
	
	if ($clayma ==  "true")
	{
		$resultado = verDatosClientePorSubclienteClayma($conexion,$cliente);
	}
	else
	{
		$resultado = $resultado = verDatosClientePorSubcliente($conexion,$cliente);
	}
	
		
	
	
	
	
		
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("no existe el cliente");
	}	
	else
	{
		echo  json_encode($resultado);
	}
	
}


?>