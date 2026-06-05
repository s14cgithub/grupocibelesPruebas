<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	

	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();
	$joins=isset($_POST["joins"])?json_decode($_POST["joins"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$filtrosLike=isset($_POST["filtrosLike"])?json_decode($_POST["filtrosLike"], true):array();
	$meses=isset($_POST["meses"])?$_POST["meses"]:0;
	$pantallaOrigen=isset($_POST["pantallaOrigen"])?$_POST["pantallaOrigen"]:'';
	//echo $meses>0;
	//exit;
	if ($pantallaOrigen=='presupuestos_listado')
	{
		$_SESSION["presupuestoListado_texto"] = $filtrosLike[0]['valor'];
		$_SESSION["presupuestoListado_queBusca"] = $filtrosLike[0]['campo'];
		$_SESSION["presupuestoListado_Bajada"] = isset($filtros['otBajada'])?1:0;
		$_SESSION["presupuestoListado_Abierta"] = isset($filtros["otAbierta"])?1:0;
		$_SESSION["presupuestoListado_meses"] = $meses;
		$_SESSION["presupuestoListado_fechaAceptacion"] = $filtros['fechaAceptacion'];	
		$_SESSION["presupuestoListado_orden"] =  $order[0]['campo'];
		$_SESSION["presupuestoListado_Desc"] = $order[0]['dir'];
	}


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	


	$fecha = "";
	//$meses=0;

	//$meses = $filtros['meses'];

	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		
		//$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		//$fecha = " fecha >='".$fechaInicio."'";
		//$fecha = $fechaInicio;
		//echo $fecha; exit;

		$fechaInicio = date('01-m-Y',strtotime("-{$meses} month"));


		$filtrosOperadores[] = [
			'campo1' => 'fecha',
			'operador' => '>=',
			'valor' => $fechaInicio
		];

		
	}

	//$filtros["fecha"] =  $fecha;

	

	$cargarPresupuestos = cargarPresupuestosConNumFacturas($conn,$bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $filtrosLike, $order);
	
	sqlsrv_close($conn);
	
	echo json_encode($cargarPresupuestos);

	
	
}

?>