<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarProveedor")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$id = $_POST["id"];
	$proveedor = $_POST["proveedor"];
	
	
	$condicion = " where nombre = '".$proveedor."' and id != ".$id;
	
	$resultado = mostrarAlmacenProveedores($conexion, $condicion);
	
	if (count($resultado)>0)
	{
		echo "Error: el proveedor ya existe";
	}
	else
	{
		echo modificarAlmacenProveedor($conexion,$id,$proveedor);
	}
	
		
	
	
}


?>
