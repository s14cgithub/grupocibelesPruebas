<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarRegistroFranqueo2")
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
		'comprobado',
		'importe',
		'codigo_saldo'
	];
	$joins = ['tabla2'];

	$filtrosOperadores = array();
	$order = array();

	$datosFranqueo = cargarFranqueo($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);


	if ($datosFranqueo["datos"][0]["comprobado"]==1)
	{
		$codigoSaldo = $datosFranqueo["datos"][0]["codigo_saldo"];
		//se mira el saldo actual del cliente
		$campos2 = [
			'importePF'	
		];

		$joins2 = array();

		$filtros2 = [
			'codigo_saldo' => $codigoSaldo,
			'codigo' => $codigoSaldo					
		];

		$filtrosOperadores2 = array();		
		$order2 = array();

		$saldoClienteAntiguo = cargarClientes($conn, $bbddSql, $campos2, $filtros2, $filtrosOperadores2, $order2, $joins2);
		$saldoClienteAntiguo1 = $saldoClienteAntiguo["datos"][0]["importePF"];	

		//Se devuelve el importe al cliente antiguo

		$nuevoSaldo = $saldoClienteAntiguo1 + $datosFranqueo["datos"][0]["importe"];
		/*
		echo "<br>actual: ".$saldoClienteAntiguo1;
		echo "<br>importe: ".$datosFranqueo["datos"][0]["importe"];
		echo "<br>nuevo: ".$nuevoSaldo;
		exit;
		*/
		$fecha1 = date("d-m-Y");

		$datos3 = [
			'codigoCliente' => $codigoSaldo,
			'fecha' => $fecha1,
			'formaPago' => '',
			'importe' => $datosFranqueo["datos"][0]["importe"],
			'presupuesto' => '',
			'fechaCuadre' => NULL,
			'informacionCuadre' => 'registro borrado en franqueo',
			'saldoPostPF' => $nuevoSaldo,
			'clayma' => 0
		];
		insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos3);


		$datos4 = [
			'fechaCobroPF' => $fecha1,
			'importePF' => $nuevoSaldo
		];

		$datosIncremento4 = array();		

		$filtros4 = [
			'codigo' => $codigoSaldo
		];

		$filtrosOperadores4 = array();

		modificarClientes($conn, $bbddSql, $datos4, $filtros4, $filtrosOperadores4, $datosIncremento4);

	}


	eliminarFranqueo($conn, $bbddSql, $filtros, $filtrosOperadores);
	
	$res = eliminarFranqueoTipos($conn, $bbddSql, $filtros, $filtrosOperadores);

	sqlsrv_close($conn);
	echo json_encode($res);


}



?>