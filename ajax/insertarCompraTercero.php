<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarCompraTercero")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$idComercial = $_POST["idComercial"];
	$presupuesto = $_POST["presupuesto"];
	$idProveedor = $_POST["idProveedor"];
	$contactoProveedor = $_POST["contactoProveedor"];
	$formaPago = $_POST["formaPago"];
	$anual = $_POST["anual"];
	
	$anual1=0;
	if ($anual=="true")
	{
		$anual1=1;
	}
	
		
	
	
	echo insertarComprarTercero($conexion,$idComercial,$presupuesto,$idProveedor,$contactoProveedor,$formaPago,$anual1);
	
	
	
	
	
		
	
	
}


?>
