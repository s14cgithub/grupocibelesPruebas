<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verPresupuestosCombinados")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$idEmpleado = $_SESSION["idEmpleado"];
	

	
	$presupuestos=mostrarPresupuestosCombinados($conexion,$idEmpleado);
	
	
	
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
