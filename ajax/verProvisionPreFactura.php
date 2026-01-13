<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verProvision")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["presupuesto"];
			
	$resultado =  verProvisionDeFondoPorPresupuesto($conexion,$presupuesto);
	
	echo json_encode($resultado);
	
	
}

?>