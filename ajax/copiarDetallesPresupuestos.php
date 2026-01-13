<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="copiarDetalle")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$viejoPresupuesto = $_POST["viejoPresupuesto"];
	$nuevoPresupuesto = $_POST["nuevoPresupuesto"];
	
	
	
	$resultado = copiarDetallePresupuesto($conexion,$viejoPresupuesto,$nuevoPresupuesto);
	
	
	cambiarDireccionadoPresupuesto($conexion,$nuevoPresupuesto);
	
	
	echo ($resultado);
	

	
	
		
		
	
}


?>