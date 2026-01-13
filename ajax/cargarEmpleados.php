<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarEmpleados")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idEmpleado=$_POST["idEmpleado"];
	$precioHora=$_POST["precioHora"];
	$horasLaborales=$_POST["horasLaborales"];	
	$orden=$_POST["orden"];
	$desc=$_POST["desc"];
	
	
	if ($desc=="false")
	{
		$desc = "asc";
	}
	else
		$desc = "desc";
	
	
	
	
	$registros = cargarEmpleados($conexion,$idEmpleado,$precioHora,$horasLaborales,$orden,$desc)	;
	
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
