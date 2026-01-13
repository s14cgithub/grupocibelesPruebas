<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistroEmpleado")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idEmpleado = $_POST["idEmpleado"];
	
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$precioHora = $_POST["precioHora"];
	$horasLaborales = $_POST["horasLaborales"];
	
	
	
		
	echo modificarRegistroEmpleado($conexion,$idEmpleado,$nombre,$apellidos,$precioHora,$horasLaborales);	
	
			
	
}


?>
