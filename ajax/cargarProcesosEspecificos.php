<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarProcesos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$ot=$_POST["ot"];
	$idDepartamento=$_POST["idDepartamento"];	
	
	$procesos=cargarProcesosEspecificos($conexion,$ot,$idDepartamento);
	
	if (count($procesos)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($procesos);
	}
		
}

?>
