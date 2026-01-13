<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarProducto")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$producto = $_POST["producto"];
	$idCliente = $_POST["idCliente"];
	$codigo = $_POST["codigo"];

	
	$condicion = " where t1.nombre= '".$producto."' and t1.idSubCliente=".$idCliente;	
	$resultado = mostrarAlmacenProductos($conexion, $condicion);
	
	if (count($resultado)>0)
	{
		echo "Error: el producto ya exite con el cliente seleccionado";
	}	
	else
	{
		$condicion = " where t1.codigo= '".$codigo."'";	
		$resultado2 = mostrarAlmacenProductos($conexion, $condicion);
		if (count($resultado2)>0)
		{
			echo "Error: el codigo ya exite";
		}	
		else
		{
			echo insertarAlmacenProducto($conexion,$idCliente, $producto,$codigo);
		}
		
	}		
}


?>