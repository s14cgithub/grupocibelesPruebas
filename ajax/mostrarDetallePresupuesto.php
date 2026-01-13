<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarDetalles")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$numPresupuesto = $_POST["numPresupuesto"];
	
	
		
	
	
	
	$detalles=cargarDetallesPresupuesto($conexion,$numPresupuesto);
	//$detalles.=cargarDetallesPresupuesto($conexion,$numPresupuesto);
	
	if (count($detalles)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay detalles para mostrar: ");
	}
	else
	{
		echo json_encode($detalles);
		
	}
		
}

?>
