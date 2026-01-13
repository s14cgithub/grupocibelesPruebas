<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarProvisionComercial")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idPF = $_POST["idPF"];
	
	$usuario = $_SESSION['usuario'];
	
	
	echo anularProvisionFondosDesdeComercial($conexion,$idPF,$usuario);
	
	
	
	
}



?>