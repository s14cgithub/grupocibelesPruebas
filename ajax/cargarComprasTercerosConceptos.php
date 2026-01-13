<?php	

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarComprasTerceros")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";
	

	$resultado=cargarComprasTercerosConceptos($conexion,$condicion);
	
	
	if (count($resultado)<=0)
	{
		echo json_encode("");
	}
	else
	{
		echo json_encode($resultado);
	}
		
}

?>
