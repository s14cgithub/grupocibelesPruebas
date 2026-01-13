<?php 
//echo "entra1";
if(isset($_POST["accion"])&$_POST["accion"]=="modificar4-1Detalle!32Factura")
{
	//echo "entra2";
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];
	$numFactura = $_POST["numFactura"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	$clayma = $_POST["clayma"];
	
	$proceso = $_POST["concepto"];	
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];
	$total = $_POST["total"];
	
			
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
	}
	
	/////////////////////////////////////////////
	
	$resultado =  cargarUnDetalleFactura($conexion,$idDetalle,$anioSeleccionado,$clayma1);
	
	//echo $resultado;

	$descripcion1 = log_modificacion;
	$tabla = facturas_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	
	
	
	$columna = facturaDetalle_Concepto;
	if ($proceso<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $proceso;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	
	$columna = facturaDetalle_ColumnaDescripcion;
	if ($descripcion<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $descripcion;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	$columna = facturaDetalle_NotaCibeles;
	if ($nota<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $nota;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	$columna = facturaDetalle_Unidad;
	if ($unidad<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $unidad;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	$columna = facturaDetalle_Precio;
	if ($precio<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $precio;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	
	
	////////////////////////////////////////////////////////////////////////
	
	
	
	
		
	echo modificarDetalleFactura ($conexion, $idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$total,$anioSeleccionado,$clayma1);
	
	
}

?>