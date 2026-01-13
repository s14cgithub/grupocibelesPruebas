<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarSubProcesos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	//echo (1);
	$tipoProceso = $_POST["idTipoProceso"];
	$departamento = $_POST["idDepartamento"];
	$subProcesos=cargarProcesoBBDD($conexion,$tipoProceso,$departamento);
	
	if (count($subProcesos)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($subProcesos);
	}
		
}

?>
