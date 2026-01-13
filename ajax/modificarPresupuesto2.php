<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro2")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["numPresupuesto"];
	
	$otBajada = $_POST["otBajada"];
	$otAbierta = $_POST["otAbierta"];
	$fechaTerminado = $_POST["fechaTerminado"];	
	
	
	if ($fechaTerminado==null||$fechaTerminado=="null" ||$fechaTerminado=="")
	{
		$fechaTerminado=null;
	}
	
	
	
	$resultado = verPresupuesto($conexion,$presupuesto);
	
	$descripcion = log_modificacion;
	$tabla = presupuesto_tabla;
	$columna = presupuesto_otBajada;
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	
	if ($otBajada<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $otBajada;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	
	$columna = presupuesto_otAbierta;
	
	if ($otAbierta<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $otAbierta;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}
	
	$columna = presupuesto_fechaTerminado;
	
	/*if ($fechaTerminado<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $fechaTerminado;			
		
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	}*/
	
	
	
	
	
	
		//echo '\nentra2';
	echo modificarPresupuesto2($conexion,$presupuesto,$otBajada,$otAbierta,$fechaTerminado);	
	
			
	
}


?>
