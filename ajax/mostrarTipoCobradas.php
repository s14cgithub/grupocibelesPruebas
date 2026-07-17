<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarTipoCobradas")
{
		$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	

	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();


    $joins = array();
    $filtrosOperadores = array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];


	$res = cargarProvisionesDeFondo_tipoCobrada($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);
	
	echo json_encode($res);
	sqlsrv_close($conn);
}
		


?>
