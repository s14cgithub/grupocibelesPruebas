<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="gestionAjustarSaldo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
		
	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	
	$idCliente = $datos["idCliente"];
	$importe = $datos["importe"];
	$concepto = $datos["concepto"];
	$fechaCuadre = date('d-m-Y');
	$fecha = $fechaCuadre;
	//$fechaCuadre = $_POST["fecha"];
	
	//$fechaCuadre = date("d-m-Y", strtotime($fechaCuadre));
	
	
	
	
	$campos = [
		'importePF'	
	];

	$filtros = array(
		"codigo" => $idCliente,
		"codigo_saldo" => $idCliente
	);	

	$filtrosOperadores = array();
	$order = array();
	$joins = array();
	$filtrosLike = array();
	

	
	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$saldo = cargarClientes($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins, $filtrosLike);
	
	
	
	$nuevoSaldo = $saldo["datos"][0]["importePF"] + $importe;
	



	$datos2 = [
			'codigoCliente' => $idCliente,
			'fecha' => $fecha,
			'formaPago' => '',
			'importe' => $importe,
			'presupuesto' => '',
			'fechaCuadre' => $fecha,
			'informacionCuadre' => $concepto,
			'saldoPostPF' => $nuevoSaldo,
			'clayma' => 0
		];

	insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos2);
	
	
	$datos3 = [
		'fechaCobroPF' => $fecha,
		'importePF' => $nuevoSaldo
	];

	$datosIncremento3 = array();		

	$filtros3 = [
		'codigo' => $idCliente
	];

	$filtrosOperadores3 = array();

	$res = modificarClientes($conn, $bbddSql, $datos3, $filtros3, $filtrosOperadores3, $datosIncremento3);
	
	echo json_encode($res);
	sqlsrv_close($conn);
	
}


?>
