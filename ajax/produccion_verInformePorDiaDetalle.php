<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verInformePorDiaDetalle")
{
	$ruta = '../';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$fechaAbuscar = $_POST["fecha"];
	$fechaAbuscarFin = $_POST["fechaFin"];
	$nombreEmpleado = $_POST["nombreEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$res = cargarHorasDetallesEmpleado($conn, $bbddSql, $fechaAbuscar, $fechaAbuscarFin, $nombreEmpleado);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>