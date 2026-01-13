<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarHuecosUtilizadosAlmacen")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	

	$idSubCliente = $_POST["idSubCliente"];
	$idProducto = $_POST["idProducto"];
	
	$resultado = mostrarHuecosUtilizadosSalida($conexion,$idProducto, $idSubCliente);

	
	
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