<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarPresupuestador")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	
	
	$presupuestadores=cargarPresupuestadores($conexion);
	
	if (count($presupuestadores)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($presupuestadores);
	}
		
}

?>
