<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="grabarFranqueo")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	$idProducto = $_POST["idProducto"];
	$idCliente = $_POST["idCliente"];
	$fecha = $_POST["fecha"];
	$envios = $_POST["totalEnvios"];
	$ot = $_POST["ot"];
	$tipoDetalle = $_POST["tipoDetalle"];


	
	$idEmpleado = $_SESSION["idEmpleado"];
	
	
	$anioSeleccionado =   date("Y", strtotime($fecha));
	$anioActual = date('Y');
	
	if ($anioActual==$anioSeleccionado)
	{		
		insertarGrabacionFranqueoPagado($conexion,$fecha,$idCliente,$idProducto,$envios,$ot,$tipoDetalle,$idEmpleado,$anioSeleccionado);
		
	}
	else
	{
		echo "Error: Solo se puede grabar si la fecha esta dentro de este año";
	}
	
	
	
	
	
	
	
}

?>
