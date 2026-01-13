<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarLogin")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idEmpleado=$_POST["idEmpleado"];
	$usuario=$_POST["usuario"];
	
	/*$orden=$_POST["orden"];
	$desc=$_POST["desc"];
	
	
	if ($desc=="false")
	{
		$desc = "asc";
	}
	else
		$desc = "desc";*/
	
	
	
	
	$registros = cargarLogin($conexion,$idEmpleado,$usuario);
	
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
