<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarFacturaCorreosPendientes")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	//$id = $_POST["id"];
	
	$factura = $_POST["factura"];
	//$fecha = $_POST["fecha"];
	$formaPago = $_POST["formaPago"];
	
	
	
	
	
	//$idRegistro = $id;
	
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	$tabla = facturasCorreos_tabla;
	$descripcion = log_modificacion;

	$presupuesto = $factura;
	
	
	
	
	$columna = facturasCorreos_formaPago;	
	$datosAntiguos="";
	$datosNuevos = $formaPago;
	
		
	echo insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	
	       
	
	
		
	echo modificarFacturasCorreosPendientes($conexion,$factura,$formaPago);	
	
	
	
	
}


?>
