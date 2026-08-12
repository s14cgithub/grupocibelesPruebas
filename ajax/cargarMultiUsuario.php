<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarMultiUsuario")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$idUsuario = $_SESSION["idEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = ['idEmpleado', 'nombreEmpleado'];
	$joins = ['tabla_empleadoAnadido'];
	$filtros = ['idUsuario' => $idUsuario];
	$order = array();

	$res = cargarRegistroHoras_multiusuario($conn, $bbddSql, $campos, $joins, $filtros, $order);
	$resultado = $res['datos'];
	
	if (count($resultado)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($resultado);
	}
		
}

?>
