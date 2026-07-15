<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="crearFormaDePago")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	
	$res =  insertarFormaPago($conn,$bbddSql, $datos);



	$datos = array(
		'usuario' => $_SESSION['usuario'],
		'descripcion' => log_creacion,
		'tabla' => presupuesto_tabla ,
		'datosAntiguos' => '',
		'datosNuevos' => $datos["concepto"],//fprma de pago
		'columna' => presupuesto_formaPago_tabla,
		'idRegistro' => 0
	);

	insertarRegistro($conn,$bbddSql, $datos);

	sqlsrv_close($conn);
	
	echo json_encode($res);
	
	
}

?>
