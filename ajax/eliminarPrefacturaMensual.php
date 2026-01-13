<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="borrarPrefacturaMensual")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numFactura = $_POST["numFactura"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
			
	
	echo eliminarPrefacturaMensual($conexion,$numFactura,$anioSeleccionado);
	
	
	/*$descripcion1 = "eliminacion";
	$tabla = preFactura_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	$datosNuevos="";
	
	$columna = "todas";
	
	$datosAntiguos = "Presupuesto: ".$resultado[0][preFacturaDetalle_Presupuesto]."||Unidades: ".$resultado[0][preFacturaDetalle_Unidad]."||Precio: ".$resultado[0][preFacturaDetalle_Precio]."||Descripcion: ".$resultado[0][preFacturaDetalle_ColumnaDescripcion]."||NotaCibeles: ".$resultado[0][preFacturaDetalle_NotaCibeles]."||Concepto: ".$resultado[0][preFacturaDetalle_Concepto];
					
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');	
	
	
	
	eliminarDetallePreFactura($conexion,$idDetalle);*/
	
	
}



?>