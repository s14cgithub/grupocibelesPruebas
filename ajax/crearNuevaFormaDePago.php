<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="crearFormaDePago")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$formaPago = $_POST["formaPago"];	
	
	
	
	
	
		
	crearNuevaFormaPago($conexion,$formaPago);
	
	$usuario = $_SESSION['usuario'];
	$descripcion="creacion";
	$tabla = presupuesto_tabla;
	$columna = presupuesto_formaPago_tabla;
	$datosAntiguos="";	
	$datosNuevos = $formaPago;	
	$idRegistro=0;
		
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	
	
	
}

?>