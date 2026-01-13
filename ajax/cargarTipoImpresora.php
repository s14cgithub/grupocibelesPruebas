<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTipoImpresoras")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$tiposImpresoras=mostrarTarifasTipoImpresora($conexion);
	
	if (count($tiposImpresoras)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($tiposImpresoras);
	}
		
}

?>
