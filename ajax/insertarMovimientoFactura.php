<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarMovimientoFacturas")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$numFactura =  $_POST["factura"];
	
	
	
	echo insertarMovimientoFacturaCibeles($conexion,$numFactura);	
	
	
	/*$usuario = $_SESSION['usuario'];
	$tabla = provisionDeFondoMovimientos_tabla;
	$descripcion = "creacion";
	$columna = "todas";
	$datosAntiguos = "";
	
	$datosNuevos = "codigoCliente: ".$codigoCliente."||fecha: ".$fecha."||formaPago: ".$formaPago."||importe: ".$importe."||presupuesto: ".$presupuesto."||fechaCuadre: ".$fechaCuadre."||informacionCuadre: ".$informacionCuadre;
	
	//echo("\n".$datosAntiguos."\n");
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro);*/
	
	
	
			
	
}


?>
