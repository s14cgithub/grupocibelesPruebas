<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarDetallePrefactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];		
	$proceso = $_POST["concepto"];	
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidad = $_POST["unidad"];
	$precio = $_POST["precio"];
	$total = $_POST["total"];
	
	if ($_POST["exentoIVA"]=="true")
		$exentoIVA = 1;
	else
		$exentoIVA = 0;		
	
	
	/////////////////////////////////////////////
	
	$resultado = cargarUnDetallePrefactura($conexion,$idDetalle);
	
	$descripcion1 = "modificacion";
	$tabla = preFactura_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	
	
	
	$columna = preFacturaDetalle_Concepto;
	if ($proceso<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $proceso;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	
	$columna = preFacturaDetalle_ColumnaDescripcion;
	if ($descripcion<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $descripcion;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	$columna = preFacturaDetalle_NotaCibeles;
	if ($nota<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $nota;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	$columna = preFacturaDetalle_Unidad;
	if ($unidad<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $unidad;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	$columna = preFacturaDetalle_Precio;
	if ($precio<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $precio;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}

	$columna = presupuestoDetalle_exentoIVA;
	if ($exentoIVA<>$resultado[0][$columna])
	{
		$datosAntiguos = $resultado[0][$columna];
		$datosNuevos = $exentoIVA;			
		
		insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	}
	
	
	
	////////////////////////////////////////////////////////////////////////
	
	
	
	
		
	echo modificarDetallePreFactura($conexion,$idDetalle,$proceso,$descripcion,$nota,$unidad,$precio,$total,$exentoIVA);
	
	
}

?>