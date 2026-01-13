<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminar!_3281Detalle")
{
	
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
	
	$resultado = cargarUnDetalleFactura($conexion,$idDetalle,$anioSeleccionado,$clayma1);
	
	$descripcion1 = log_eliminacion;
	$tabla = facturasDetalles_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];

	$datosNuevos="";
	
	$columna = "todas";
	
	$datosAntiguos = "Factura: ".$resultado[0][facturaDetalle_factura]."||Unidades: ".$resultado[0][facturaDetalle_Unidad]."||Precio: ".$resultado[0][facturaDetalle_Precio]."||Descripcion: ".$resultado[0][facturaDetalle_ColumnaDescripcion]."||NotaCibeles: ".$resultado[0][facturaDetalle_NotaCibeles]."||Concepto: ".$resultado[0][facturaDetalle_Concepto];
					
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	
	
	
	echo eliminarDetalleFactura ($conexion, $idDetalle,$anioSeleccionado, $clayma1);
	
	
}



?>