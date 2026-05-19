<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="proximoNumero")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	//////////////////////////////////////////////

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'ultimoPresupuesto'
	];
	$joins = array();
	$filtros = array();	
	//$filtrosOperadores = array();


	$filtrosOperadores = array(
		array(
			'campo1' => 'presupuestoNoMensual',
			'operador' => '>',
			'valor' => 100
		)
	);

	$order = array();

	$res = cargarPresupuestos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);
	
	

	$ultimoNumero = $res["datos"][0]["ultimoPresupuesto"];
	$anioGuardado = substr($ultimoNumero,0,2);
	$mesGuardado = substr($ultimoNumero,2,2);
	$secuencialActual = substr($ultimoNumero,4);

	$anioActual = date("y");
	$mesActual = date("m");	
	
	$secuenciaNueva = 0;
	$secuenciaNuevaString = "";	
	
	$presupuestoNuevo = "";	

	$insertarPresupuesto = array();
	

	//rocio: 000 al 99 - manualmente
	//comerciales: 100 al infinito - automatico	

	if ($anioActual != $anioGuardado or $mesActual != $mesGuardado or $secuencialActual<100)
	{
		$secuencialActual = "100";
	}

	$secuenciaNueva = intval($secuencialActual);
	$secuenciaNueva++;

	$secuenciaNuevaString = $secuenciaNueva;

	while (strlen($secuenciaNuevaString)<3)
	{
		$secuenciaNuevaString = "0".$secuenciaNuevaString;
	}


	$presupuestoNuevo = $anioActual.$mesActual.$secuenciaNuevaString;
	//echo ($presupuestoNuevo);
	
	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();

	$datos['presupuesto'] = $presupuestoNuevo;

	$insertarPresupuesto =  insertarPresupuesto($conn,$bbddSql, $datos);

	$insertarPresupuesto['presupuestoNuevo'] = $presupuestoNuevo;
	$insertarPresupuesto['prueba1']=$ultimoNumero;

	echo json_encode($insertarPresupuesto);

	/*
	echo json_encode(array(
    'insertarPresupuesto' => $insertarPresupuesto,
    '$res' => $res
	
));
	*/
	
	
	//LOG

	$datos = array(
		'usuario' => $_SESSION['usuario'],
		'descripcion' => log_creacion,
		'tabla' => presupuesto_tabla ,
		'datosAntiguos' => '',
		'datosNuevos' => $presupuestoNuevo,
		'columna' => presupuesto_Presupuesto,
		'idRegistro' => 0 
	);

	insertarRegistro($conn,$bbddSql, $datos);	

	sqlsrv_close($conn);		
	
	
	
}


?>