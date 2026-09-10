<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="duplicarFacturaANegativa")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$facturaOriginal = isset($_POST["facturaOriginal"]) ? $_POST["facturaOriginal"] : '';
	$clayma = isset($_POST["clayma"]) ? $_POST["clayma"] : 'false';

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$camposFacturaOriginal = [
		'idCodigoCliente','cliente','descripcion','presupuesto','inicialComercial',
		'precioNeto','tipoIva','iva','irpf','precioNetoExentoIva','precioTotal','provision','aPagar',
		'cantidad','pedido','formaPago','detallada',
		'cd','fechaInicio','fechaFin','importeFranqueo','cuentaDelBanco','abono','combinadoSumatorio',
		'observaciones','observacionesInternas','liquidado','prefactura','comprobacionError',
		'dirPost_pais','dirPost_codigoPais','nombre_empresa','direccion','codigo_postal','localidad','provincia','nif',
		'envio_nombre','envio_domicilio','envio_cp','envio_poblacion','envio_provincia','envio_pais','envio_att','retener',
		'fechaRealizacion'
	];
	$filtrosFacturaOriginal = ['numeroFacturaCompleto' => $facturaOriginal];

	if ($clayma=="true")
	{
		$resFacturaOriginal = mostrarFacturacionClayma($conn, $bbddSql, $camposFacturaOriginal, [], $filtrosFacturaOriginal, [], []);
	}
	else
	{
		$resFacturaOriginal = mostrarFacturacion($conn, $bbddSql, $camposFacturaOriginal, [], $filtrosFacturaOriginal, [], []);
	}

	if (empty($resFacturaOriginal['datos']))
	{
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'No se ha encontrado la factura original', 'ok' => false));
		exit;
	}

	$f = $resFacturaOriginal['datos'][0];

	if ($clayma=="true")
	{
		$resFecha = mostrarFacturarFechaActualClayma($conn, $bbddSql, ['activado','fechaImprimir']);
	}
	else
	{
		$resFecha = mostrarFacturarFechaActual($conn, $bbddSql, ['activado','fechaImprimir']);
	}

	if ($resFecha['datos'][0]['activado']=="0")
	{
		$fecha = $resFecha['datos'][0]['fechaImprimir']->format("d/m/Y");
	}
	else
	{
		$fecha = date('d/m/Y');
	}

	$datosNueva = array(
		'presupuesto' => $f['presupuesto'],
		'cliente' => $f['cliente'],
		'idCodigoCliente' => $f['idCodigoCliente'],
		'descripcion' => $f['descripcion'],
		'fecha' => $fecha,
		'inicialComercial' => $f['inicialComercial'],
		'precioNeto' => $f['precioNeto'] * -1,
		'tipoIva' => $f['tipoIva'],
		'precioNetoExentoIva' => $f['precioNetoExentoIva'] * -1,
		'iva' => $f['iva'] * -1,
		'irpf' => $f['irpf'] * -1,
		'precioTotal' => $f['precioTotal'] * -1,
		'provision' => $f['provision'] * -1,
		'aPagar' => $f['aPagar'] * -1,
		'cantidad' => $f['cantidad'],
		'pedido' => $f['pedido'],
		'formaPago' => $f['formaPago'],
		'detallada' => $f['detallada'],
		'numCuentaBanco' => $f['cuentaDelBanco'],
		'combinadoSumatorio' => $f['combinadoSumatorio'],
		'prefactura' => $f['prefactura'],
		'cd' => $f['cd'],
		'fechaInicio' => $f['fechaInicio'],
		'fechaFin' => $f['fechaFin'],
		'importeFranqueo' => $f['importeFranqueo'],
		'abono' => $f['abono'],
		'observaciones' => $f['observaciones'],
		'observacionesInternas' => $f['observacionesInternas'],
		'liquidado' => $f['liquidado'],
		'comprobacionError' => $f['comprobacionError'],
		'dirPost_nombreEmpresa' => $f['nombre_empresa'],
		'dirPost_direccion' => $f['direccion'],
		'dirPost_cp' => $f['codigo_postal'],
		'dirPost_poblacion' => $f['localidad'],
		'dirPost_provincia' => $f['provincia'],
		'dirPost_pais' => $f['dirPost_pais'],
		'dirPost_codigoPais' => $f['dirPost_codigoPais'],
		'dirPost_Nif' => $f['nif'],
		'dirEnv_nombreEmpresa' => $f['envio_nombre'],
		'dirEnv_direccion' => $f['envio_domicilio'],
		'dirEnv_cp' => $f['envio_cp'],
		'dirEnv_poblacion' => $f['envio_poblacion'],
		'dirEnv_provincia' => $f['envio_provincia'],
		'dirEnv_pais' => $f['envio_pais'],
		'dirEnv_att' => $f['envio_att'],
		'retener' => $f['retener'],
		'serieFactura' => 'NEG',
		'origenFactura' => $facturaOriginal,
		'fechaRealizacion' => $f['fechaRealizacion']
	);

	if ($clayma=="true")
	{
		$resNueva = insertarFacturacionClayma($conn, $bbddSql, $datosNueva);
	}
	else
	{
		$resNueva = insertarFacturacion($conn, $bbddSql, $datosNueva);
	}

	if ($resNueva['error'] != '')
	{
		sqlsrv_close($conn);
		echo json_encode($resNueva);
		exit;
	}

	$numeroFacturaNegCompleto = $resNueva['numeroFacturaCompleto'];

	$camposDetalleOriginal = ['presupuesto','concepto','descripcion','unidades','precio','total','tipoIva','ordenTipo','orden','campana'];
	$filtrosDetalleOriginal = ['numeroFacturaCompleto' => $facturaOriginal];
	$orderDetalleOriginal = [['campo' => 'ordenTipo', 'dir' => 'ASC'], ['campo' => 'orden', 'dir' => 'ASC']];

	if ($clayma=="true")
	{
		$resDetalleOriginal = cargarFacturacionDetallesClayma($conn, $bbddSql, $camposDetalleOriginal, $filtrosDetalleOriginal, [], [], $orderDetalleOriginal);
	}
	else
	{
		$resDetalleOriginal = cargarFacturacionDetalles($conn, $bbddSql, $camposDetalleOriginal, $filtrosDetalleOriginal, [], [], $orderDetalleOriginal);
	}

	$resultadoDetalle = array();

	foreach ($resDetalleOriginal['datos'] as $detalle)
	{
		$datosDetalle = $detalle;
		$datosDetalle['numeroFacturaCompleto'] = $numeroFacturaNegCompleto;
		$datosDetalle['unidades'] = $datosDetalle['unidades'] * -1;
		$datosDetalle['total'] = $datosDetalle['total'] * -1;

		if ($clayma=="true")
		{
			$resultadoDetalle[] = insertarFacturacionDetallesClayma($conn, $bbddSql, $datosDetalle);
		}
		else
		{
			$resultadoDetalle[] = insertarFacturacionDetalles($conn, $bbddSql, $datosDetalle);
		}
	}

	sqlsrv_close($conn);

	echo json_encode(array('error' => '', 'ok' => true, 'numeroFacturaCompleto' => $numeroFacturaNegCompleto, 'detalle' => $resultadoDetalle));
}

?>
