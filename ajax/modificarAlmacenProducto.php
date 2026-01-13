<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarProducto")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$id = $_POST["id"];
	$producto = $_POST["producto"];
	$codigo = $_POST["codigo"];	

	echo modificarProductoAlmacen($conexion,$id, $producto, $codigo);
	
	/*$condicion = " where t1.nombre= '".$producto."' and t1.idProveedor=".$idProveedor." and t1.id != ".$id;	
	$resultado = mostrarAlmacenProductos($conexion, $condicion);
	
	if (count($resultado)>0)
	{
		echo "Error: el producto ya exite con el proveedor seleccionado";
	}	
	else
	{
		$condicion2 = " where t1.codigo= '".$codigo."'";	
		$resultado2 = mostrarAlmacenProductos($conexion, $condicion2);
		if (count($resultado2)>0)
		{
			echo "Error: el codigo ya exite";
		}	
		else
		{
			echo insertarAlmacenProducto($conexion,$idProveedor, $producto,$codigo);
		}		
	}	*/
	
	
}


?>
