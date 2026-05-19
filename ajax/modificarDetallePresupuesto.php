<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?$_POST["filtrosOperadores"]:array();
	
	
	//echo json_encode($res);
	$numPresupuesto=isset($_POST["numPresupuesto"])?$_POST["numPresupuesto"]:0;

	//LOG
	$campos2 = [
		'idConcepto',
		'descripcion',
		'notaCibeles',
		'notaAdmonProd',
		'unidades',
		'precio',
		'orden',
		'exentoIVA',
		'pesoGramos'
	];

	$joins2 = array();

	$idDetalle2 = $filtros['id'];

	$filtros2 = [
		'id' => $idDetalle2
	];

	$filtrosOperadores2 = array();
	$order2 = array();

	$datosDetalles = cargarDetallesPresupuesto($conn, $bbddSql, $campos2, $joins2, $filtros2, $filtrosOperadores2, $order2);

	$columna = presupuestoDetalle_ColumnaConcepto;
	if ($datos['idConcepto']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['idConcepto'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);	
		
	}

	$columna = presupuestoDetalle_ColumnaDescripcion;
	if ($datos['descripcion']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['descripcion'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_NotaCibeles;
	if ($datos['notaCibeles']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['notaCibeles'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_NotaCibelesAdmonProd;
	if ($datos['notaAdmonProd']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['notaAdmonProd'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_Unidad;
	if ($datos['unidades']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['unidades'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_Precio;
	if ($datos['precio']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['precio'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_Orden;
	if ($datos['orden']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['orden'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_exentoIVA;
	if ($datos['exentoIVA']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['exentoIVA'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}

	$columna = presupuestoDetalle_pesoGramos;
	if ($datos['pesoGramos']<>$datosDetalles['datos'][0][$columna])
	{
		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_modificacion,
			'tabla' => presupuestoDetalle_tabla ,
			'datosAntiguos' => $datosDetalles['datos'][0][$columna],
			'datosNuevos' => $datos['pesoGramos'],
			'columna' => $columna,
			'idRegistro' => $idDetalle2,
			'presupuesto' => $numPresupuesto

		);
		insertarRegistro($conn,$bbddSql, $datos2);
	}





	$res =  modificarDetallePresupuesto($conn,$bbddSql, $datos, $filtros,$filtrosOperadores);
	
	sqlsrv_close($conn);
	
	echo json_encode($res);

	
	
	
}

?>