<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];
	$nota = $_POST["nota"];	
	$unidad = $_POST["unidad"];	
	$notaAdmonProd = $_POST["notaAdmonProd"];	
	
	
	
	/////////////////////////////////////////////
	
	$resultado = cargarUnDetallePresupuesto($conexion,$idDetalle);
	
	$descripcion1 = log_modificacion;
	$tabla = presupuestoDetalle_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	
	
	$numPresupuesto = $resultado[0]["presupuesto"];
	
	
	$columna = presupuestoDetalle_NotaCibeles;
	if ($nota<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $nota;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	
	
	$columna = presupuestoDetalle_NotaCibelesAdmonProd;
	if ($notaAdmonProd<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $notaAdmonProd;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	$columna = presupuestoDetalle_Unidad2;
	if ($unidad<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $unidad;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	}
	
	////////////////////////////////////////////////////////////////////////
	
	echo modificarDetallePresupuesto2($conexion,$idDetalle,$nota,$unidad,$notaAdmonProd);
	
	
}

?>