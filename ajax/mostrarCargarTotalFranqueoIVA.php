<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarCargarTotalFranqueoIVA")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	$formasDePago=cargarTotalFranqueoIVA($conexion);
	
	
	
	
	if (count($formasDePago)<=0)
	{
		echo json_encode("");
		echo ("Error2: No hay nada para mostrar: ");
	}
	else
	{
		echo json_encode($formasDePago);
	}
		
}

?>
