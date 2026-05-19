<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	
	$numPresupuesto=isset($_POST["numPresupuesto"])?$_POST["numPresupuesto"]:0;

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	


	////////////////////////////////////
	//LOG
	$campos = [
		'id',
		'presupuesto',
		'unidades',
		'precio',
		'descripcion',
		'notaCibeles',
		'orden',
		'idConcepto',
		'idTipo'
	];

	$joins2 = array();

	$idDetalle2 = $filtros['id'];

	$filtros2 = [
		'id' => $idDetalle2
	];

	$filtrosOperadores2 = array();
	$order2 = array();

	$datosDetalles = cargarDetallesPresupuesto($conn, $bbddSql, $campos, $joins2, $filtros2, $filtrosOperadores2, $order2);

	//echo json_encode($datosDetalles);
	
	$datos2 = array(
		'usuario' => $_SESSION['usuario'],
		'descripcion' => log_eliminacion,
		'tabla' => presupuestoDetalle_tabla,
		'datosAntiguos' => "id: ".$datosDetalles['datos'][0][presupuestoDetalle_Id]."||Presupuesto: ".$datosDetalles['datos'][0][presupuestoDetalle_Presupuesto]."||Unidades: ".$datosDetalles['datos'][0][presupuestoDetalle_Unidad]."||Precio: ".$datosDetalles['datos'][0][presupuestoDetalle_Precio]."||Descripcion: ".$datosDetalles['datos'][0][presupuestoDetalle_ColumnaDescripcion]."||NotaCibeles: ".$datosDetalles['datos'][0][presupuestoDetalle_NotaCibeles]."||Orden: ".$datosDetalles['datos'][0][presupuestoDetalle_Orden]."||idConcepto: ".$datosDetalles['datos'][0][presupuestoDetalle_ColumnaConcepto]."||idTipo: ".$datosDetalles['datos'][0][presupuestoDetalle_idTipo],
		'datosNuevos' => "",
		'columna' => "todas",
		'idRegistro' => $idDetalle2,
		'presupuesto' => $numPresupuesto
	);

	insertarRegistro($conn,$bbddSql, $datos2);



	$eliminarDetallePresupuesto = eliminarDetallePresupuesto($conn,$bbddSql, $filtros, $filtrosOperadores);
	//echo $cargarClientes['sql'];
	sqlsrv_close($conn);		
	
	echo json_encode($eliminarDetallePresupuesto);	
		
}

?>