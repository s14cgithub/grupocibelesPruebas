<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarRegistroFranqueoEspecial2")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	

	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = array();
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];


	$datos0 = [
		'codigo_saldo',
		'importe',
		'comprobado'
	];
	$joins0 =['tabla2'];

	$filtros0 = [				
		'id' => $filtros['id']
	];

	$filtrosOperadores0 = $group0 =  $order0 = array();

	$datosFranqueoTipos = cargarFranqueoTipos($conn, $bbddSql, $datos0, $joins0, $filtros0, $filtrosOperadores0, $group0, $order0);

	

	$codigo_saldo = $datosFranqueoTipos["datos"][0]["codigo_saldo"];
	$importe = $datosFranqueoTipos["datos"][0]["importe"];
	$comprobado = $datosFranqueoTipos["datos"][0]["comprobado"];


	/////CAMBIAR SALDO
	if ($comprobado==1)
	{		
		//se obtiene el el codigo saldo y el saldo actual
		$datos2 = [
			'importePF'					
		];
		$filtros2 = [				
			'codigo' => $codigo_saldo
		];

		$filtrosOperadores2 = array();
		$order2 = array();

		$datosCliente = cargarClientes($conn, $bbddSql, $datos2, $filtros2, $filtrosOperadores2, $order2);
		
		$nuevoSaldo = floatval(isset($datosCliente["datos"][0]["importePF"]) ? $datosCliente["datos"][0]["importePF"] : 0) + floatval($importe);

		//se devuelve el importe al cliente
		$fecha = date("d-m-Y");
		$datos3 = [
				'codigoCliente' => $codigo_saldo,
				'fecha' => $fecha,
				'formaPago' => '',
				'importe' => $importe,
				'presupuesto' => '',
				'fechaCuadre' => NULL,
				'informacionCuadre' => 'registro borrado en franqueo f12',
				'saldoPostPF' => $nuevoSaldo,
				'clayma' => 0
			];
		insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos3);

		$datos4 = [
			'fechaCobroPF' => $fecha,
			'importePF' => $nuevoSaldo
		];

		$datosIncremento4 = array();		

		$filtros4 = [
			'codigo' => $codigo_saldo
		];

		$filtrosOperadores4 = array();

		modificarClientes($conn, $bbddSql, $datos4, $filtros4, $filtrosOperadores4, $datosIncremento4);
	}
	
	
	
	$res = eliminarFranqueoTipos($conn, $bbddSql, $filtros, $filtrosOperadores);

	sqlsrv_close($conn);
	
	echo json_encode($res);

}

?>