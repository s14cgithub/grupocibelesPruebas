<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="comprobarCodigoProceso")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idEmpleado=$_POST["idEmpleado"];
	
	
	
	$registros = verUltimoRegistroTrabajo($conexion,$idEmpleado);
	
	if (count($registros)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($registros);
	}
		
}

?>
