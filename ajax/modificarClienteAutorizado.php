<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarClienteAutorizado")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$codigo = $_POST["codigo"];
	$valor = $_POST["valor"];
	
	echo modificarClienteAutorizado($conexion,$codigo,$valor);
	
	
	/*$descripcion = log_modificacion;
	$tabla = presupuesto_tabla;
	$columna = presupuesto_numeroNoFactura;
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	
	$usuario = $_SESSION['usuario'];
	
	$datosAntiguos = '';
	$datosNuevos = $resultado1[0]["numeroNoFactura"];

	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	
	
	echo $datosNuevos;*/
	
}

?>
