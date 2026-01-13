<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarRegistroEmpleado")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$precioHora = $_POST["precioHora"];
	$horasLaborales = $_POST["horasLaborales"];

	
	
	
		
	echo insertarRegistroEmpleado($conexion,$nombre,$apellidos,$precioHora,$horasLaborales,'');
	
			
	
}


?>
