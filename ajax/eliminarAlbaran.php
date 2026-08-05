<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarAlbaran")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = eliminarAlbaran($conn, $bbddSql, $filtros);

	sqlsrv_close($conn);

	echo json_encode($res);
	
	
			
	
}


?>
