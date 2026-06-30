<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarFranqueoTipo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	
	$id = $datos["id"];	
	$tipo = $datos["tipo"];
	$unidades = $datos["unidades"];
	$importe = $datos["importe"];	
	$ot = $datos["ot"];
	$otSidi = $datos["otSidi"];
	$fecha = $datos["fecha"];
	$idCliente = $datos["idCliente"];	
	$referencia = $datos["referencia"];
	$importeTotal = $datos["total"];

	$anioSeleccionado =  date("Y", strtotime($fecha));


	$destino = "";
	$gramos = "";
	$tipo1 = "";
	
	$valores = explode("-", $tipo);
	
	$contador =0;
	foreach ($valores as $valor) 
	{
    	if ($contador==0)
		{
			$destino = $valor;
		}
		else if($contador==1)
		{
			$gramos = $valor;
		}
		else
		{
			$tipo1 .= $valor."-";
		}
		$contador++;
	}

	$tipo1 = substr($tipo1, 0, -1);


	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	//Obtenemos el importe del tipo antiguo
	$campos = [		
		'importe'	
	];
	$joins = array();
	$filtros = [
		'referencia' => $referencia,
		'id' => $id		
	];

	 $filtrosOperadores = array();
	 $group = array();
	 $order = array();

	$datosAntiguosFranqueoTipos = cargarFranqueoTipos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);

	//Obtenemos el importe de los datos de franqueo antiguo
	$campos2 = [
		'fecha',
		'idCliente',
		'ot',
		'otSidi',
		'importe',
		'envios',
		'comprobado'		
	];
	$joins2 = array();
	$filtros2 = [
		'referencia' => $referencia		
	];

	 $filtrosOperadores2 = array();
	 
	 $order2 = array();
	
	$datosAntiguosFranqueo = cargarFranqueo($conn, $bbddSql, $campos2, $joins2, $filtros2, $filtrosOperadores2, $order2);


	//Se guarda el cambio en el tipo de franqueo
	$datos3 = [
		'tipo' => $tipo1,
		'unidades' => $unidades,
		'importe' => $importe
	];			

	$filtros3 = [
		'id' => $id
	];		

	$filtrosOperadores3 = array();		

	modificarFranqueoTipos($conn, $bbddSql, $datos3, $filtros3, $filtrosOperadores3);


	//se obtiene la suma del importe y de las unidades de franqueoTipos

	$campos4 = [		
		'importeTotal',
		'unidadesTotal'	
	];
	$joins4 = ['tabla3', 'tabla6'];
	$filtros4 = [
		'referencia' => $referencia		
	];

	 $filtrosOperadores4 = array();
	 $group4 = ['idCliente'];
	 $order4 = array();

	$datosNuevosParaFranqueo = cargarFranqueoTipos($conn, $bbddSql, $campos4, $joins4, $filtros4, $filtrosOperadores4, $group4, $order4);

	//se obtiene los datos nuevos para el franqueo
	$datos5 = [
		'importe',
		'descripcionTarifaLeft',
		'tituloTarifasProducto',
		'gramosTarifaLeft',
		'unidades'
	];

	$joins5 = [
		'tabla4',
		'tabla5'
	];

	$filtros5 = [
		'referencia' => $referencia			
	];	

	$filtrosOperadores5 = array();
	$group5 = array();

	$order5 = [
		['campo' => 'ordenTarifaProducto', 'dir' => 'ASC'],
		['campo' => 'gramosTarifasLeft', 'dir' => 'ASC']
	];
	
	$datosActuales = cargarFranqueoTipos($conn, $bbddSql, $datos5, $joins5, $filtros5, $filtrosOperadores5, $group5, $order5);
	//echo json_encode($datosActuales);exit;
	$importeTotal=0;
	$enviosTotal=0;
	$detalle="";
	$anadidos="";

	foreach ($datosActuales["datos"] as $valor)
	{
		$importeTotal = $importeTotal + $valor["importe"];

		if ($valor["titulo"]=="" || $valor["titulo"]==NULL)
		{
			$valores = explode(" ", $valor["descripcion"]);

			$anadidos=$valores[0];
		}
		else
		{
			$detalle = $detalle . $valor["titulo"]."(".$valor["gramos"]."): ".$valor["unidades"]."|";
			$enviosTotal = $enviosTotal + $valor["unidades"];
			//$anadidos="";
		}

		//echo "\nimporte: ".$importeTotal;
		//echo "\nenvios: ".$enviosTotal;
	}
	$detalle = substr($detalle, 0, -1);

	$fechaFormateada = date('d/m/Y', strtotime($fecha));


	//se guarda los datos genericos en franqueoTipos
	$datos6 = [
		'ot' => $ot,
		'otSidi' => $otSidi,
		'fecha' => $fecha,
		'idCliente' => $idCliente
	];			

	$filtros6 = [
		'referencia' => $referencia			
	];		

	$filtrosOperadores6 = array();

	modificarFranqueoTipos($conn, $bbddSql, $datos6, $filtros6, $filtrosOperadores6);



	//se guarda los datos en franqueo
	$datos7 = [
		'fecha' => $fechaFormateada,
		'idCliente' => $idCliente,			
		'ot' => $ot,
		'otSidi' => $otSidi,
		'importe' => $importeTotal,
		'envios' => $enviosTotal,
		'detalle' => $detalle,
		'anadidos' => $anadidos		
	];			

	$filtros7 = [
		'referencia' => $referencia			
	];		

	$filtrosOperadores7 = array();

	$res = modificarFranqueo($conn, $bbddSql, $datos7, $filtros7, $filtrosOperadores7);


	




	//si hay que modificar saldos
	if ($datosAntiguosFranqueo["datos"][0]["comprobado"]==1 || $datosAntiguosFranqueo["datos"][0]["comprobado"]=="1")
	{

		//se obtiene el codigo saldo del cliente antiguo:
		$campos8 = [
			'codigo_saldo'	
		];

		$joins8 = array();

		$filtros8 = [			
			'codigo' => $datosAntiguosFranqueo["datos"][0]["idCliente"]
		];

		$filtrosOperadores8 = array();		
		$order8 = array();

		$codigoSaldoClienteAntiguo = cargarClientes($conn, $bbddSql, $campos8, $filtros8, $filtrosOperadores8, $order8, $joins8);
		$codigoSaldoClienteAntiguo1 = $codigoSaldoClienteAntiguo["datos"][0]["codigo_saldo"];
		
		
		
		//Se calcula el saldo del cliente antiguo
		$campos9 = [
			'importePF'	
		];

		$joins9 = array();

		$filtros9 = [
			'codigo_saldo' => $codigoSaldoClienteAntiguo1,
			'codigo' => $codigoSaldoClienteAntiguo1						
		];

		$filtrosOperadores9 = array();		
		$order9 = array();

		$saldoClienteAntiguo = cargarClientes($conn, $bbddSql, $campos9, $filtros9, $filtrosOperadores9, $order9, $joins9);
		
		$saldoClienteAntiguo1 = $saldoClienteAntiguo["datos"][0]["importePF"];

		$fecha1 = date("d-m-Y");

		//Se devuelve el importe al cliente antiguo
		$nuevoSaldoClienteAntiguo = $saldoClienteAntiguo1 + $datosAntiguosFranqueo["datos"][0]["importe"];

		$datos10 = [
			'codigoCliente' => $codigoSaldoClienteAntiguo1,
			'fecha' => $fecha1,
			'formaPago' => '',
			'importe' => $datosAntiguosFranqueo["datos"][0]["importe"],
			'presupuesto' => '',
			'fechaCuadre' => NULL,
			'informacionCuadre' => 'modificacion desde franqueo grabacion',
			'saldoPostPF' => $nuevoSaldoClienteAntiguo,
			'clayma' => 0
		];
		insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos10);

		

		$datos11 = [
			'fechaCobroPF' => $fecha1,
			'importePF' => $nuevoSaldoClienteAntiguo
		];

		$datosIncremento11 = array();		

		$filtros11 = [
			'codigo' => $codigoSaldoClienteAntiguo1
		];

		$filtrosOperadores11 = array();

		modificarClientes($conn, $bbddSql, $datos11, $filtros11, $filtrosOperadores11, $datosIncremento11);


		//se obtiene el codigo saldo del cliente nuevo

		$campos12 = [
			'codigo_saldo'	
		];

		$joins12 = array();

		$filtros12 = [			
			'codigo' => $idCliente
		];

		$filtrosOperadores12 = array();		
		$order12 = array();

		$codigoSaldoClienteNuevo = cargarClientes($conn, $bbddSql, $campos12, $filtros12, $filtrosOperadores12, $order12, $joins12);
		$codigoSaldoClienteNuevo1 = $codigoSaldoClienteNuevo["datos"][0]["codigo_saldo"];
		

		//Se calcula el saldo del cliente nuevo
		$campos13 = [
			'importePF'	
		];

		$joins13 = array();

		$filtros13 = [
			'codigo_saldo' => $codigoSaldoClienteNuevo1,
			'codigo' => $codigoSaldoClienteNuevo1						
		];

		$filtrosOperadores13 = array();		
		$order13 = array();

		$saldoClienteNuevo = cargarClientes($conn, $bbddSql, $campos13, $filtros13, $filtrosOperadores13, $order13, $joins13);
		
		$saldoClienteNuevo1 = $saldoClienteNuevo["datos"][0]["importePF"];
		

		
		//Se cobra el importe al cliente nuevo
		$nuevoSaldoClienteNuevo = $saldoClienteNuevo1 - $importeTotal;

		$datos14 = [
			'codigoCliente' => $codigoSaldoClienteNuevo1,
			'fecha' => $fecha1,
			'formaPago' => '',
			'importe' => $importeTotal*-1,
			'presupuesto' => '',
			'fechaCuadre' => NULL,
			'informacionCuadre' => 'modificacion desde franqueo grabacion',
			'saldoPostPF' => $nuevoSaldoClienteNuevo,
			'clayma' => 0
		];
		insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos14);

		$datos15 = [
			'fechaCobroPF' => $fecha1,
			'importePF' => $nuevoSaldoClienteNuevo
		];

		$datosIncremento15 = array();		

		$filtros15 = [
			'codigo' => $codigoSaldoClienteNuevo1
		];

		$filtrosOperadores15 = array();

		modificarClientes($conn, $bbddSql, $datos15, $filtros15, $filtrosOperadores15, $datosIncremento15);
	}

	sqlsrv_close($conn);
	
	echo json_encode($res);
	
	
	
	
}


?>
