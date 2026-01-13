<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="anadirDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];	
	$proceso = $_POST["proceso"];	
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];	
	$usuario = $_SESSION["idEmpleado"];	
	
	

	if ($_POST["exentoIVA"]=="true")
		$exentoIVA = 1;
	else
		$exentoIVA = 0;

		
	echo insertarDetallePrefactura($conexion,$presupuesto,$proceso,$descripcion,$nota,$unidad,$precio,$usuario,$exentoIVA);
	
	
}

?>