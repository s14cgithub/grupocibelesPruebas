<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarAbonosPendientes")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$abono = $_POST["abono"];
	$formaPago = $_POST["formaPago"];
	$clayma = $_POST["clayma"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$clayma1=0;
	
	
	if ($clayma=="true")
	{
		$resultado = modificarAbonosPendientesClayma($conexion, $abono, $formaPago, $anioSeleccionado);
		$clayma1 = 1;
	}
	else
	{
		$resultado = modificarAbonosPendientes($conexion, $abono, $formaPago, $anioSeleccionado);
	}


	$idRegistro = "0";	
	$usuario = $_SESSION['usuario'];	
	$descripcion = log_modificacion;		
	$datosAntiguos="";
	$datosNuevos = $formaPago;		
		
	$tabla = abono_tabla;
	$columna = abono_formaDepago;
	$presupuesto = $abono."/".$anioSeleccionado;
	
	echo  insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,$clayma1);
		
	
	
	
	
	
}


?>