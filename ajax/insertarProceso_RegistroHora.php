<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarProceso")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idRegistroHora = $_POST["idRegistroHora"];
	$idProceso = $_POST["idProceso"];	

	echo insertarProceso_RegistroHora($conexion, $idRegistroHora, $idProceso);

}


?>
