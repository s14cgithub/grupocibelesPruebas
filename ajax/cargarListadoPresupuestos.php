<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoPresupuestos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$presupuestos=mostrarListadoPresupuestosActivos($conexion);
	
	if (count($presupuestos)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($presupuestos);
	}
		
}

?>
