<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarGramajePapel")
{ 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	

	$res =  modificarGramajePapel($conn,$bbddSql, $datos, $filtros,$filtrosOperadores);
	
	sqlsrv_close($conn);
	
	echo json_encode($res);

}

?>
