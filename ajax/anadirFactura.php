<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirFactura")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	if (mostrarSePuedeFacturar($conn, $bbddSql) == 1)
	{
		$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();

		$clayma = isset($datos['clayma']) && $datos['clayma']==1;

		if ($clayma)
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

		$datos['fecha'] = $fecha;
		$datos['combinadoSumatorio'] = 0;

		$camposCliente = ['nombre_empresa','direccion','codigo_postal','localidad','provincia','pais','codigoPais','nif','nombrePais','envio_nombre','envio_att','envio_domicilio','envio_cp','envio_poblacion','envio_provincia','envio_pais','retener'];

		if ($clayma)
		{
			$resCliente = cargarClientesClayma($conn, $bbddSql, $camposCliente, ['codigo' => $datos['idCodigoCliente']], [], [], [], ['tabla4']);
		}
		else
		{
			$resCliente = cargarClientes($conn, $bbddSql, $camposCliente, ['codigo' => $datos['idCodigoCliente']], [], [], ['tabla4']);
		}

		if (empty($resCliente['datos'])) {
			sqlsrv_close($conn);
			echo json_encode(array('error' => 'No se ha encontrado el cliente para generar la factura', 'ok' => false));
			exit;
		}

		$cliente = $resCliente['datos'][0];
		$datos['dirPost_nombreEmpresa'] = $cliente['nombre_empresa'];
		$datos['dirPost_direccion'] = $cliente['direccion'];
		$datos['dirPost_cp'] = $cliente['codigo_postal'];
		$datos['dirPost_poblacion'] = $cliente['localidad'];
		$datos['dirPost_provincia'] = $cliente['provincia'];
		$datos['dirPost_pais'] = $cliente['pais'];
		$datos['dirPost_codigoPais'] = $cliente['codigoPais'];
		$datos['dirPost_Nif'] = $cliente['nif'];
		$datos['dirPost_pais'] = $cliente['nombrePais'];

		if ($cliente['envio_domicilio']=="" && $cliente['envio_cp']=="" && $cliente['envio_poblacion']=="" && $cliente['envio_provincia']=="") {
			$datos['dirEnv_nombreEmpresa'] = $cliente['nombre_empresa'];
			$datos['dirEnv_direccion'] = $cliente['direccion'];
			$datos['dirEnv_cp'] = $cliente['codigo_postal'];
			$datos['dirEnv_poblacion'] = $cliente['localidad'];
			$datos['dirEnv_provincia'] = $cliente['provincia'];
			$datos['dirEnv_pais'] = $cliente['nombrePais'];
			$datos['dirEnv_att'] = '';
		} else {
			$datos['dirEnv_nombreEmpresa'] = $cliente['envio_nombre'];
			$datos['dirEnv_direccion'] = $cliente['envio_domicilio'];
			$datos['dirEnv_cp'] = $cliente['envio_cp'];
			$datos['dirEnv_poblacion'] = $cliente['envio_poblacion'];
			$datos['dirEnv_provincia'] = $cliente['envio_provincia'];
			$datos['dirEnv_pais'] = $cliente['envio_pais'];
			$datos['dirEnv_att'] = $cliente['envio_att'];
		}

		$datos['retener'] = $cliente['retener'];

		// fechaRealizacion: en una rectificativa (RECT/SUST) se copia de la factura origen;
		// en una factura normal se coge de fechaTerminado del presupuesto de origen.
		if (isset($datos['serieFactura']) && ($datos['serieFactura'] == 'RECT' || $datos['serieFactura'] == 'SUST') && !empty($datos['origenFactura']))
		{
			$resOrigen = $clayma
				? mostrarFacturacionClayma($conn, $bbddSql, ['fechaRealizacion'], [], ['numeroFacturaCompleto' => $datos['origenFactura']], [], [])
				: mostrarFacturacion($conn, $bbddSql, ['fechaRealizacion'], [], ['numeroFacturaCompleto' => $datos['origenFactura']], [], []);
			if (!empty($resOrigen['datos'])) {
				$datos['fechaRealizacion'] = $resOrigen['datos'][0]['fechaRealizacion'];
			}
		}
		else
		{
			$resPresupuesto = cargarPresupuestos($conn, $bbddSql, ['fechaTerminado'], [], ['presupuesto' => $datos['presupuesto']], [], []);
			if (!empty($resPresupuesto['datos'])) {
				$datos['fechaRealizacion'] = $resPresupuesto['datos'][0]['fechaTerminado'];
			}
		}

		if ($clayma)
		{
			$res = insertarFacturacionClayma($conn, $bbddSql, $datos);
		}
		else
		{
			$res = insertarFacturacion($conn, $bbddSql, $datos);
		}

		//LOG
		$datosNuevos = "Cliente: " . $datos['cliente'] ."|CodigoCliente: ". $datos['idCodigoCliente'] . "|Clayma: " . ($clayma?1:0) . "|Neto: ".$datos['precioNeto']. "|Iva: ".$datos['iva']. "|Irpf: ".$datos['irpf']."|Total: ".$datos['precioTotal']."|Provision: ".$datos['provision']."|aPagar: ".$datos['aPagar']." Prefactura: ".$datos['prefactura'];

		$datos2 = array(
			'usuario' => $_SESSION['usuario'],
			'descripcion' => log_creacion,
			'tabla' => facturas_tabla,
			'datosAntiguos' => '',
			'datosNuevos' => $datosNuevos,
			'columna' => 'todas',
			'idRegistro' => 0,
			'presupuesto' => $datos['presupuesto'],
			'clayma' => $clayma ? 1 : 0
		);
		insertarRegistro($conn, $bbddSql, $datos2);

		sqlsrv_close($conn);

		echo json_encode($res);
	}
	else
	{
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'No se puede generar la factura en este momento', 'ok' => false));
	}
}

?>
