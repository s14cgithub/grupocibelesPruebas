<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarNumNoFacturable")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numero = $_POST["numero"];
	
	$motivo = $_POST["motivo"];
	
	
	$condicion = " where numNoFactura = '".$numero."'";
	
	$datosPresupuesto = mostrarPresupuestos2($conexion,$condicion);
	
	
	
	
	$usuario = $_SESSION['usuario'];
	$descripcion = log_modificacion;
	$tabla = presupuesto_tabla;
	$datosAntiguos = $numero;
	$datosNuevos = "Motivo: ".$motivo;	
	$columna = presupuesto_numeroNoFactura;
	$idRegistro = 0;
	$numPresupuesto = $datosPresupuesto[0]["presupuesto"];



	echo insertarRegistro ($conexion, $usuario, $descripcion,$datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);
	
	
	echo modificarPresupuestoAnularNoFac($conexion,$numero);
	
	
		
	
	
	
	
}



?>