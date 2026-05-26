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

	$_SESSION["presupuestoListado_texto"] = $filtros['texto'];
	$_SESSION["presupuestoListado_queBusca"] = $filtros['queBusca'];
	$_SESSION["presupuestoListado_Bajada"] = $filtros['bajada'];
	$_SESSION["presupuestoListado_Abierta"] = $filtros['abierta'];
	$_SESSION["presupuestoListado_meses"] = $filtros['meses'];
	$_SESSION["presupuestoListado_fechaAceptacion"] = $filtros['fechaAceptacion'];	
	$_SESSION["presupuestoListado_orden"] =  $order[0]['campo'];
	$_SESSION["presupuestoListado_Desc"] = $order[0]['dir'];
	


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	


	$fecha = "";
	//$meses=0;

	$meses = $filtros['meses'];

	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		$fecha = " fecha >='".$fechaInicio."'";
	}

	$filtros["fecha"] =  $fecha;

	$cargarPresupuestos = cargarPresupuestosConNumFacturas($conn,$bbddSql, $campos, $filtros, $order);
	
	sqlsrv_close($conn);		
	
	echo json_encode($cargarPresupuestos);	
	
}

?>