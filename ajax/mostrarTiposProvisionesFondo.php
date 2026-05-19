<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTipoProvisionesFondos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	


	$campos=isset($_POST["campos"])?json_decode($_POST["campos"], true):array();

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
									 
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$order=isset($_POST["order"])?$_POST["order"]:array();

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	$provisionFondoTipos = cargarTiposProvisionesFondos($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order);
	//echo $cargarClientes['sql'];
	sqlsrv_close($conn);


	$debug = false;

	if ($debug) {
    echo '<pre>';
    print_r($provisionFondoTipos['sql']);
    print_r($provisionFondoTipos['params']);	
    echo '</pre>';

	}
	
	
	echo json_encode($provisionFondoTipos);
	
		
}

?>
