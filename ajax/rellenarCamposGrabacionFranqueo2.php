<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="rellenarCamposGrabacionFranqueo")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$producto = $_POST["producto"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$titulos=rellenarCamposGrabacionFranqueo2($conexion,$producto,$anioSeleccionado);
	
	if (count($titulos)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($titulos);
	}
		
}

?>
