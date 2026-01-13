<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="crearProceso")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idTipo = $_POST["idTipo"];	
	$proceso = $_POST["proceso"];	
	$descripcion = $_POST["descripcion"];
	$idDepartamento = $_POST["idDepartamento"];
	
		
	echo crearNuevoProcesoPresupuesto($conexion,$idTipo,$proceso,$descripcion,$idDepartamento);
	
	
}

?>