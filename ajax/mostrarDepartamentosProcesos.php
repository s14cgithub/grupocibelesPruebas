<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarDepProcesos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$departamento=cargarDepartamentoProcesoBBDD($conexion);
	
	
	if (count($departamento)<=0)
	{
		echo json_encode("");
		echo ("Error2: No hay departamentos para mostrar: ");
	}
	else
	{
		echo json_encode($departamento);
	}
		
}

?>
