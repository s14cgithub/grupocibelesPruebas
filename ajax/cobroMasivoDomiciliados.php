<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cobroMasivoDomiciliado")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	

	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();
	
	

	$fechaInicio = $filtrosOperadores[0]["valor"];
	$fechaFin = $filtrosOperadores[1]["valor"];
	$formaPago = $datos["formaPagoReal"];

	//$fechaPago = $_POST["fechaPago"];
	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	

	$campos2 = [
		'numeroFacturaCompleto',
		'idCodigoCliente'	
	];


	$joins2 = [
		'tabla2'
	];

	$filtros2 = array(
		"domiciliada" => 1,
		"sinFormaPago" => 1
	);

	$filtrosOperadores2 = $filtrosOperadores;
	

	$order2 = array();



	$resultado = mostrarFacturacion($conn, $bbddSql, $campos2, $joins2, $filtros2, $filtrosOperadores2, $order2);
	
	
	
		
	$usuario = $_SESSION['usuario'];	
	$descripcion = log_modificacion;		
	$datosAntiguos="";
	
		
	$tabla = facturas_tabla;
	$columna = facturas_formaDepago;

	$fecha = date("d-m-Y");

	$filtrosOperadores3 = array();
	for ($contador=0;$contador<count($resultado["datos"]);$contador++)
	{	
		$datos3 = array();
		$datos3 = [
			'fechaPago' => $fecha,
			'formaPagoReal' => $formaPago
		];
		$filtros3 = array();
		$filtros3 = array(
			"numeroFacturaCompleto" => $resultado["datos"][$contador]["numeroFacturaCompleto"]			
		);	
	

		modificarFacturacion($conn, $bbddSql, $datos3, $filtros3, $filtrosOperadores3);

		$datos4 = array();
		$datos4 = array(
				'usuario' => $usuario,
				'descripcion' => log_modificacion,
				'tabla' => facturas_tabla ,
				'datosAntiguos' => '',
				'datosNuevos' => $formaPago,
				'columna' => "todas",
				'idRegistro' => '',
				'presupuesto' => $resultado["datos"][$contador]["numeroFacturaCompleto"]
			);

		insertarRegistro($conn,$bbddSql, $datos4);		
			
	}
	
	

	$campos5 = [
		'numeroOficial',
		'codigo_saldo',
		'importe'
	];


	$joins5 = [
		'tabla2'
	];

	$filtros5 = array(
		"domiciliada" => 1,
		"sinFormaPago" => 1
	);

	$filtrosOperadores5 = $filtrosOperadores;

	$order5 = array();



	$resultado2 = mostrarFacturacionCorreos($conn, $bbddSql, $campos5, $joins5, $filtros5, $filtrosOperadores5, $order5);
	
	$res = array();
	
	$filtrosOperadores6 = array();
	for ($contador=0;$contador<count($resultado2["datos"]);$contador++)
	{
		
		$datos6 = array();
		$datos6 = [
			'fechaPago' => $fecha,
			'formaPago' => $formaPago
		];
		$filtros6 = array();
		$filtros6 = array(
			"numeroOficial" => $resultado2["datos"][$contador]["numeroOficial"]			
		);	
	

		modificarFacturacionCorreos($conn, $bbddSql, $datos6, $filtros6, $filtrosOperadores6);

		$datos7 = array();
		$datos7 = array(
				'usuario' => $usuario,
				'descripcion' => log_modificacion,
				'tabla' => facturasCorreos_tabla ,
				'datosAntiguos' => '',
				'datosNuevos' => $formaPago,
				'columna' => "todas",
				'idRegistro' => '',
				'presupuesto' => $resultado2["datos"][$contador]["numeroOficial"]
			);

		insertarRegistro($conn,$bbddSql, $datos7);		

		$codigoSaldo=$resultado2["datos"][$contador]["codigo_saldo"];
		

		$datos8 = [
			'importePF'					
		];
		$filtros8 = [				
			'codigo' => $codigoSaldo
		];

		$filtrosOperadores8 = array();
		$order8 = array();
		
		$datosCliente=array();
		$datosCliente = cargarClientes($conn, $bbddSql, $datos8, $filtros8, $filtrosOperadores8, $order8);


		$importe = $resultado2["datos"][$contador]["importe"];
		$nuevoSaldo = $datosCliente["datos"][0]["importePF"] + $importe;
		
		$datos9 = array();
		$datos9 = [
			'codigoCliente' => $codigoSaldo,
			'fecha' => $fecha,
			'formaPago' => $formaPago,
			'importe' => $importe,
			'presupuesto' => $resultado2["datos"][$contador]["numeroOficial"],
			'fechaCuadre' => NULL,
			'informacionCuadre' => '',
			'saldoPostPF' => $nuevoSaldo,
			'clayma' => 0
		];
		
		insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos9);

		$datos10 = [
			'fechaCobroPF' => $fecha,
			'importePF' => $nuevoSaldo
		];

		$datosIncremento10 = array();
		/*
		$datosIncremento10 = [
			'importePF' => $importe
		];
		*/

		$filtros10 = [
			'codigo' => $codigoSaldo
		];

		$filtrosOperadores10 = array();

		modificarClientes($conn, $bbddSql, $datos10, $filtros10, $filtrosOperadores10, $datosIncremento10);

	}
	
	$res = array(
		"error" => ""
	);
	echo json_encode($res);
	sqlsrv_close($conn);
	
}


?>