<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarRegistroEmpleado")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idEmpleado = $_POST["idEmpleado"];
	

	
	
	
		
	echo eliminarRegistroEmpleado($conexion,$idEmpleado);	
	
			
	
}


?>
