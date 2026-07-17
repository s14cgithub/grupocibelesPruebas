<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarProvisionFondo")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'presupuesto',
		'importe',
		'fechaCreacion',
		'tipo',
		'cobrada',
		'fechaCobro',
		'formaPago',
		'id',
	];
	$joins = array();

	$filtrosOperadores = array();
	$group = array();
	$order = array();

	$datosPF = cargarProvisionDeFondos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);

	
	$presupuesto = $datosPF["datos"][0]["presupuesto"];
	$importe = $datosPF["datos"][0][provisionDeFondo_importe];
	
	if ($datosPF["datos"][0][provisionDeFondo_fechaCreacion] == "" or $datosPF["datos"][0][provisionDeFondo_fechaCreacion] == null )
	{
		$fechaCreacion = null;
	}
	else
	{
		$fechaCreacion = $datosPF["datos"][0][provisionDeFondo_fechaCreacion]->format('Y-m-d H:i:s');
	}
	
	
	$tipo = $datosPF["datos"][0][provisionDeFondo_tipo]; 
	$cobrada = $datosPF["datos"][0][provisionDeFondo_cobrada]; 
	
	if ($datosPF["datos"][0][provisionDeFondo_fechaCobro] == "" or $datosPF["datos"][0][provisionDeFondo_fechaCobro] == null )
	{
		$fechaCobro = null;
	}
	else
	{
		$fechaCobro = $datosPF["datos"][0][provisionDeFondo_fechaCobro]->format('Y-m-d H:i:s');
	}

	$formaPago = $datosPF["datos"][0][provisionDeFondo_formaPago];
	
	
	
	
	$datosAntiguos = "presupuesto: ".$presupuesto."||"."importe: ".$importe."||"."fechaCreacion: ".$fechaCreacion."||"."tipo: ".$tipo."||"."cobrada: ".$cobrada."||"."fechaCobro: ".$fechaCobro."||"."formaPago: ".$formaPago;
	

	
	$datos = array(
		'usuario' => $_SESSION['usuario'],
		'descripcion' => log_eliminacion,
		'tabla' => provisionDeFondo_tabla ,
		'datosAntiguos' => $datosAntiguos,
		'datosNuevos' => '',
		'columna' => "todas",
		'idRegistro' => $datosPF["datos"][0]["id"]
	);

	insertarRegistro($conn,$bbddSql, $datos);	
		
	$res = eliminarProvisionFondos($conn, $bbddSql, $filtros, $filtrosOperadores);

	sqlsrv_close($conn);
	echo json_encode($res);
}


?>
