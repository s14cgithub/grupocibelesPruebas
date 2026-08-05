<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="guardarFacturaEspecial2")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = insertarFacturasEspeciales($conn, $bbddSql, $datos);

	sqlsrv_close($conn);

	echo json_encode($res);
	
	
	
		
}

?>
