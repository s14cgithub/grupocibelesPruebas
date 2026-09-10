<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="insertarFacturacionDetalles")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	require_once($ruta."Verifactu/wortice/lanzarFactura.php");

	$numPresupuesto = isset($_POST["numPresupuesto"]) ? $_POST["numPresupuesto"] : '';
	$facturaOriginal = isset($_POST["facturaOriginal"]) ? $_POST["facturaOriginal"] : '';
	$numeroFacturaCompleto = isset($_POST["numeroFacturaCompleto"]) ? $_POST["numeroFacturaCompleto"] : '';
	$campana = isset($_POST["campana"]) ? $_POST["campana"] : '';

	$idEmpleado = $_SESSION["idEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$camposDetalleTemporal = ['concepto','descripcion','unidades','precio','total','ordenTipo','orden','tipoIva'];

	if ($facturaOriginal != '')
	{
		$filtrosDetalleTemporal = ['facturaOriginal' => $facturaOriginal, 'idEmpleado' => $idEmpleado];
	}
	else
	{
		$filtrosDetalleTemporal = ['presupuesto' => $numPresupuesto, 'idEmpleado' => $idEmpleado];
	}

	$resDetalleTemporal = mostrarFacturasDetallesTemporal($conn, $bbddSql, $camposDetalleTemporal, $filtrosDetalleTemporal, [], []);

	$resultado = array();

	foreach ($resDetalleTemporal['datos'] as $detalle)
	{
		$datos = $detalle;
		$datos['presupuesto'] = $numPresupuesto;
		$datos['numeroFacturaCompleto'] = $numeroFacturaCompleto;
		$datos['campana'] = $campana;

		$resultado[] = insertarFacturacionDetalles($conn, $bbddSql, $datos);
	}

	eliminarFacturasDetallesTemporal($conn, $bbddSql, ['idEmpleado' => $idEmpleado], []);

	$huboErrorDetalle = false;
	foreach ($resultado as $fila) {
		if (empty($fila['ok'])) {
			$huboErrorDetalle = true;
			break;
		}
	}

	if (!$huboErrorDetalle && $numeroFacturaCompleto != '') {
		//$resultado["error"] = "entra";
		$prueba = lanzarFactura($conn, $bbddSql, $numeroFacturaCompleto, false);
	}
	else
	{
		//$resultado["error"] = "no entra";
	}

	sqlsrv_close($conn);

	echo json_encode($prueba);

	//echo json_encode($resultado);

}

?>
