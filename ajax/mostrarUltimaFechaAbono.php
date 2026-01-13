<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarUltimaFechaAbono")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	
	$fecha =  mostrarUltimaFechaAbono($conexion, $anioSeleccionado);
	
	
	
	
	if (count($fecha)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay datos que mostrar: ");
	}
	else
	{
		echo json_encode($fecha);
	}
		
}

?>
