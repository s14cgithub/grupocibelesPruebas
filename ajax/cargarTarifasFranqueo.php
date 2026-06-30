<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarTarifasFranqueo")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();
	$joins =isset($_POST["joins"])?json_decode($_POST["joins"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$filtrosOperadores=isset($_POST["filtrosOperadores"])?$_POST["filtrosOperadores"]:array();
	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();

	$anioSeleccionado=isset($_POST["anioSeleccionado"])?$_POST["anioSeleccionado"]:array();
	$cantidad=isset($_POST["cantidad"])?$_POST["cantidad"]:0;

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	$res = cargarTarifasFranqueo($conn,$bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order,$anioSeleccionado,$cantidad);
	
	sqlsrv_close($conn);
	
	
	echo json_encode($res);	
	

}

?>
