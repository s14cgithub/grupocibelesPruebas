<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRecSustitucionPendientes")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$numFactura = $_POST["factura"];
	$formaPago = $_POST["formaPago"];
	$clayma = $_POST["clayma"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$clayma1=0;
	
	if ($clayma=="true")
	{
		$resultado = modificarRecSustitucionPendientesClayma($conexion, $numFactura, $formaPago,$anioSeleccionado);
		$clayma1=1;
	}
	else
	{
		$resultado = modificarRecSustitucionPendientes($conexion, $numFactura, $formaPago,$anioSeleccionado);
	}



	$idRegistro = "0";	
	$usuario = $_SESSION['usuario'];	
	$descripcion = log_modificacion;		
	$datosAntiguos="";
	$datosNuevos = $formaPago;		
		
	$tabla = facturas_tabla;
	$columna = facturas_formaDepago;
	$presupuesto = $numFactura."/".$anioSeleccionado;
	
	echo insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,$clayma1);		
	
	
}


?>