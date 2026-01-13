<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarOt")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];
	
	$campana = $_POST["campana"];
	$cantidad = $_POST["cantidad"];
	$fechaInicio = $_POST["fechaInicio"];
	$fechaTerminado = $_POST["fechaTerminado"];
	
	
	
	
	$resultado = verPresupuesto($conexion,$presupuesto);
	
	$descripcion = log_modificacion;
	$tabla = presupuesto_tabla;
	
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	$columna = presupuesto_ColumnaCampana2;
	if ($campana<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $campana;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_ColumnaCantidad2;
	if ($cantidad<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $cantidad;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_fechaInicioReal;
	$valor = "";
	if ($resultado[0][$columna]==null||$resultado[0][$columna]=="null" ||$resultado[0][$columna]=="")
	{		
	}
	else
	{
		$valor = $resultado[0][$columna]->format('d/m/Y');
	}
	
	
	
	if ($fechaInicio!=$valor)
	{
		$datosAntiguos = $valor;
		$datosNuevos = $fechaInicio;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	
	
	$columna = presupuesto_fechaTerminado;
	$valor = "";
	if ($resultado[0][$columna]==null||$resultado[0][$columna]=="null" ||$resultado[0][$columna]=="")
	{		
	}
	else
	{
		$valor = $resultado[0][$columna]->format('d/m/Y');
	}
	
	
	if ($fechaTerminado!=$valor)
	{
		$datosAntiguos = $valor;
		$datosNuevos = $fechaTerminado;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	
	
	
	echo modificarOtProduccion($conexion,$presupuesto,$campana,$cantidad,$fechaInicio,$fechaTerminado);	
	
			
	
}


?>
