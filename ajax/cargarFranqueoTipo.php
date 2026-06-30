<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarFranqueoTipo")
{
	session_start(); 
	
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();
	$joins = isset($_POST["joins"])?json_decode($_POST["joins"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$filtrosLike=isset($_POST["filtrosLike"])?json_decode($_POST["filtrosLike"], true):array();
	$group=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();
	$anioTarifas=isset($_POST["anioTarifas"])?$_POST["anioTarifas"]:'';
	

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarFranqueoTipos($conn,$bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order, $anioTarifas, $filtrosLike);
	
	sqlsrv_close($conn);
	
	echo json_encode($res);
	

}

?>
