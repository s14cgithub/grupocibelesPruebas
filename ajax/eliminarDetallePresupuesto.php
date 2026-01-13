<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idDetalle = $_POST["idDetalle"];
	$numPresupuesto = $_POST["numPresupuesto"];
	
	
	$resultado = cargarUnDetallePresupuesto($conexion,$idDetalle);
	
	
	$descripcion1 = "eliminacion";
	$tabla = presupuestoDetalle_tabla;	
	$idRegistro = $idDetalle;
	$usuario = $_SESSION['usuario'];
	$datosNuevos = "";
	
	$columna = "todas";
	
	$datosAntiguos = "id: ".$resultado[0][presupuestoDetalle_Id]."||Presupuesto: ".$resultado[0][presupuestoDetalle_Presupuesto]."||Unidades: ".$resultado[0][presupuestoDetalle_Unidad]."||Precio: ".$resultado[0][presupuestoDetalle_Precio]."||Descripcion: ".$resultado[0][presupuestoDetalle_ColumnaDescripcion]."||NotaCibeles: ".$resultado[0][presupuestoDetalle_NotaCibeles]."||Orden: ".$resultado[0][presupuestoDetalle_Orden]."||idConcepto: ".$resultado[0][presupuestoDetalle_ColumnaConcepto]."||idTipo: ".$resultado[0][presupuestoDetalle_idTipo];
					
		
	insertarRegistro ($conexion, $usuario, $descripcion1, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro, $numPresupuesto);	
	
	
	
	
	echo eliminarDetallePresupuesto($conexion,$idDetalle);
	
	
}

?>