<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];		
	$descripcion = $_POST["descripcion"];	
	$cantidad = $_POST["cantidad"];
	$precioUnitario = $_POST["precioUnitario"];
	$precioTotal = $_POST["precioTotal"];
	$precioVenta = $_POST["precioVenta"];
	$margen = $_POST["margen"];
	$presupuesto = $_POST["presupuesto"];
	
	
	
	
	
	
	$resultado = modificarDetalleCompraTercero($conexion, $idDetalle, $descripcion, $cantidad, $precioUnitario, $precioTotal, $precioVenta, $margen);
	
	if ($resultado=="")
	{
		
	}
	else
	{
		echo $resultado;
	}
	
	
	
	
	/////////////////////////////////////////////
		
	
	
	$descripcion1 = log_modificacion;
	$tabla = comprasATercerosDetalles_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	$numPresupuesto = $presupuesto;
	$datosAntiguos="";
	
	$columna = comprasATercerosDetalles_Descripcion;
	$datosNuevos = $descripcion;
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	
	
	
	$columna = comprasATercerosDetalles_cantidad;
	$datosNuevos = $cantidad;
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);
	
	$columna = comprasATercerosDetalles_precioUnidad;
	$datosNuevos = $precioUnitario;
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);

	$columna = comprasATercerosDetalles_precioVenta;
	$datosNuevos = $precioVenta;
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);	


	$columna = comprasATercerosDetalles_total;
	$datosNuevos = $precioTotal;
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);

	
	$columna = comprasATercerosDetalles_margen;
	$datosNuevos = $margen;
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$numPresupuesto);


	
	////////////////////////////////////////////////////////////////////////
	
	
	
	
}

?>