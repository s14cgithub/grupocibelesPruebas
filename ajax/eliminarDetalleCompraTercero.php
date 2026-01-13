<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];
	
	
	$descripcion1 = log_eliminacion;
	$tabla = comprasATercerosDetalles_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	$datosNuevos="";
	
	$columna = "todas";
	
	$datosAntiguos = "";
					
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	
	
	
	echo eliminarDetalleComprarTercero($conexion,$idDetalle);
	
	
}



?>