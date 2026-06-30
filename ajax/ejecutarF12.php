<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="ejecutarF12")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'codigo_saldo',
		'importeTotal'
	];

	$joins = ['tabla2'];

	$filtros = [
		'comprobado' => 0	
	];
	$filtrosOperadores = array();

	$group = [	
		'codigo_saldo'
	];

	$order = array(
		'idCliente' => 'ASC'	
	);

	$datosFranqueoTipo = cargarFranqueoTipos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);
	
	$contador=0;

	while ($contador<count($datosFranqueoTipo["datos"]))
	{
		$fecha = date("d-m-Y");
		$importe = floatval($datosFranqueoTipo["datos"][$contador]["importeTotal"]);
		$codigoSaldoCliente = $datosFranqueoTipo["datos"][$contador]["codigo_saldo"];

		$campos2 = [
			'importePF'	
		];

		$joins2 = array();

		$filtros2 = [
			'codigo_saldo' => $codigoSaldoCliente,
			'codigo' => $codigoSaldoCliente						
		];

		$filtrosOperadores2 = array();		
		$order2 = array();

		$saldoCliente = cargarClientes($conn, $bbddSql, $campos2, $filtros2, $filtrosOperadores2, $order2, $joins2);
		
		$nuevoSaldo = floatval(isset($saldoCliente["datos"][0]["importePF"]) ? $saldoCliente["datos"][0]["importePF"] : 0) - floatval($importe);
			
		
		$datos3 = [
			'codigoCliente' => $codigoSaldoCliente,
			'fecha' => $fecha,
			'formaPago' => '',
			'importe' => $importe*-1,
			'presupuesto' => '',
			'fechaCuadre' => NULL,
			'informacionCuadre' => 'f12',
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
			'codigo' => $codigoSaldoCliente
		];

		$filtrosOperadores4 = array();

		$prueba = modificarClientes($conn, $bbddSql, $datos4, $filtros4, $filtrosOperadores4, $datosIncremento4);

		
		$contador++;
	}


	//cambiar el estado de los registros de franqueo a comprobado
	$datos5 = [
		'comprobado' => 1	
	];			

	$filtros5 = [
		'comprobado' => 0		
	];		

	$filtrosOperadores5 = array();

	
	modificarFranqueo($conn, $bbddSql, $datos5, $filtros5, $filtrosOperadores5);
	//echo json_encode($prueba);
	//exit;
	$res = modificarFranqueoTipos($conn, $bbddSql, $datos5, $filtros5, $filtrosOperadores5);
	

	$datos6 = [
		'idAutorizacionFranqueo' => 1	
	];			

	$filtros6 = [
		'idAutorizacionFranqueo' => 3	
	];		

	$filtrosOperadores6 = array();
	$datosIncremento6 = array();

	//cambiar clientes autorizados un dia
	modificarClientes($conn, $bbddSql, $datos6, $filtros6, $filtrosOperadores6, $datosIncremento6);	
	
	
	sqlsrv_close($conn);	
	echo json_encode($res);
	
	
}



?>