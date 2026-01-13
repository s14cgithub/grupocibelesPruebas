<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistro")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$idRegistro = $_POST["idRegistro"];
	$idProceso = $_POST["idProceso"];
	$idCliente = $_POST["idCliente"];	
	
	
	$usuario = $_SESSION['usuario'];
	$tabla = tabla_registroHora;
	$descripcion = "modificacion";
	
	
	$modificar = true;		
	$datosAntiguos = "";		
	$datosNuevos = "idProceso: ".$idProceso. "| idCliente: ".$idCliente;		
	$columna = "procesos";		

	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');		

	modificarRegistroHoraSinProceso($conexion,$idRegistro,$idProceso,$idCliente);
	
}


?>
