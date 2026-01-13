<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarProveedores")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";
	
	
	$clientes=cargarProveedores($conexion,$condicion);
	
	
	if (count($clientes)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($clientes);
	}
		
}

?>
