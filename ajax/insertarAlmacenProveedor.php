<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$proveedor = $_POST["proveedor"];

	
	$condicion = " where nombre = '".$proveedor."'";
	
	$resultado = mostrarAlmacenProveedores($conexion, $condicion);
	
	if (count($resultado)>0)
	{
		echo "Error: el proveedor ya existe";
	}
	else
	{
		echo insertarAlmacenProveedor($conexion,$proveedor);
	}
	
	
	
	
	
	
	
		
	
	
}


?>
