<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="cargarPresupuestos")
{
	session_start();
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	

	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();

	$joins=isset($_POST["joins"])?json_decode($_POST["joins"], true):array();

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	
	$order=isset($_POST["order"])?json_decode($_POST["order"], true):array();

	$filtrosLike=isset($_POST["filtrosLike"])?json_decode($_POST["filtrosLike"], true):array();

	$pantallaOrigen=isset($_POST["pantallaOrigen"])?$_POST["pantallaOrigen"]:'';

	if ($pantallaOrigen=='admEmisionFacturasPendientes')
	{
		$_SESSION["prefactura_Clayma"] = (isset($filtros['clayma']) && $filtros['clayma']==1) ? "true" : "false";
		$_SESSION["prefactura_queBusca"] = isset($filtrosLike[0]['campo']) ? $filtrosLike[0]['campo'] : '';
		$_SESSION["prefactura_texto"] = isset($filtrosLike[0]['valor']) ? $filtrosLike[0]['valor'] : '';
		$_SESSION["prefactura_orden"] = isset($order[0]['campo']) ? $order[0]['campo'] : '';
		$_SESSION["prefactura_Desc"] = (isset($order[0]['dir']) && strtoupper($order[0]['dir'])=='DESC') ? "true" : "false";
	}

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	$cargarPresupuestos = cargarPresupuestos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order, $filtrosLike);
	//echo $cargarClientes['sql'];
	sqlsrv_close($conn);		
	
	echo json_encode($cargarPresupuestos);		
	
}

?>
