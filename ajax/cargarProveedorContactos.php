<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarProveedorContactos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";
	
	
	
	$contactos=cargarProveedorContactos($conexion,$condicion);
	
	
	
	if (count($contactos)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($contactos);
	}
		
}

?>
