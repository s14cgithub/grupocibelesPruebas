<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verDatosPresupuestosCombinados")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	require_once($ruta."Verifactu/wortice/lanzarFactura.php");

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	if (mostrarSePuedeFacturar($conn, $bbddSql) == 1)
	{
		$presupuestos = $_POST["presupuestos"];
		$combinadoSumatorio = $_POST["combinadoSumatorio"];

		$clayma = isset($_POST['clayma']) && $_POST['clayma']==1;

		if ($combinadoSumatorio=="false")
			$combinadoSumatorio=0;
		else
			$combinadoSumatorio=1;

		$presupuestos2 = explode(" - ", $presupuestos);

		$usuario = $_SESSION["idEmpleado"];
		$resDatosFactura = mostrarFacturasTemporal($conn, $bbddSql, ['idCliente','pedido','descripcion','detallada','formaPagoTexto'], ['usuario' => $usuario], [], [], ['tabla2']);
		
		$datosFactura = $resDatosFactura['datos'];

		
		$idCliente = $datosFactura[0]["idCliente"];
		$pedido = $datosFactura[0]["pedido"];
		$campana = $datosFactura[0]["descripcion"];
		$detallada = $datosFactura[0]["detallada"];
		$formaPagoTexto = $datosFactura[0]["formaPagoTexto"];


		$presupuestosAimprimir = "";

		
		if ($combinadoSumatorio!=1) //se mira los presupuestos que hay para combinar		
		{
			$resCombinados = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['presupuestoDistinct','campana'], ['idEmpleado' => $usuario], [], [['campo'=>'presupuesto','dir'=>'ASC']], [], ['tabla2']);
			$presupuestosAimprimir = $resCombinados['datos'];			
		}


		$camposCliente = ['nombre_empresa','direccion','codigo_postal','localidad','provincia','pais','codigoPais','nif','nombrePais','envio_nombre','envio_att','envio_domicilio','envio_cp','envio_poblacion','envio_provincia','envio_pais','retener','nuestraCuenta'];

		if ($clayma)
		{
			$resCliente = cargarClientesClayma($conn, $bbddSql, $camposCliente, ['codigo_saldo' => $idCliente, 'codigo' => $idCliente], [], [], [], ['tabla4']);
		}
		else
		{
			$resCliente = cargarClientes($conn, $bbddSql, $camposCliente, ['codigo_saldo' => $idCliente, 'codigo' => $idCliente], [], [], ['tabla4']);
		}

		$datosCliente = $resCliente['datos'][0];


		$resSumatorioPrecio = mostrarFacturasTemporal($conn, $bbddSql, ['precioNetoSumatorio','provisionSumatorio','irpfSumatorio'], ['usuario' => $usuario, 'idCliente' => $idCliente], [], []);
		$sumatorioPrecio = $resSumatorioPrecio['datos'];

		$precioNeto = isset($sumatorioPrecio[0]["precioNeto"]) && $sumatorioPrecio[0]["precioNeto"] !== '' ? $sumatorioPrecio[0]["precioNeto"] : 0;
		$provision = isset($sumatorioPrecio[0]["provision"]) && $sumatorioPrecio[0]["provision"] !== '' ? $sumatorioPrecio[0]["provision"] : 0;

		// El iva se recalcula desde las lineas de detalle (agrupado por tipoIva, redondeado por tipo)
		// en vez de sumar los iva ya redondeados de cada presupuesto por separado, para que cuadre
		// siempre con el desglose que se muestra en la previsualizacion.
		$resDesglose = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['total','tipoIva'], ['idEmpleado' => $usuario], [], []);
		$desgloseIva = array();
		foreach ($resDesglose['datos'] as $rowDesglose)
		{
			if (isset($rowDesglose['tipoIva']) && $rowDesglose['tipoIva']!=0)
			{
				$tipo = $rowDesglose['tipoIva'];
				if (!isset($desgloseIva[$tipo]))
				{
					$desgloseIva[$tipo] = 0;
				}
				$desgloseIva[$tipo] += floatval($rowDesglose['total']) * $tipo / 100;
			}
		}
		foreach ($desgloseIva as $tipoRedondeo => $importeRedondeo)
		{
			$desgloseIva[$tipoRedondeo] = round($importeRedondeo, 2);
		}
		$iva = round(array_sum($desgloseIva), 2);

		$irpf = 0;
		if (isset($sumatorioPrecio[0]["irpf"]) && $sumatorioPrecio[0]["irpf"]!=0)
		{
			$irpf = round($precioNeto*19/100, 2)*-1; //esto se hace para evitar fallos en los redondeos
		}

		$precioTotal = round($precioNeto + $iva + $irpf, 2);
		$aPagar = round($precioTotal - $provision, 2);

		$campana = "";
		$inicialComercial=0;


		if ($clayma)
		{
			$resFecha = mostrarFacturarFechaActualClayma($conn, $bbddSql, ['activado','fechaImprimir']);
		}
		else
		{
			$resFecha = mostrarFacturarFechaActual($conn, $bbddSql, ['activado','fechaImprimir']);
		}
		if ($resFecha['datos'][0]["activado"]=="0")
		{
			$fecha = $resFecha['datos'][0]["fechaImprimir"]->format("d/m/Y");
		}
		else
		{
			$fecha = date('d/m/Y');
		}

		$anioSeleccionado = substr($fecha, -4);
		$prefactura = 0;

		$datos = array(
			'presupuesto' => 'Comb: '.$presupuestos,
			'cliente' => $datosCliente["nombre_empresa"],
			'idCodigoCliente' => $idCliente,
			'fecha' => $fecha,
			'pedido' => $pedido,
			'cantidad' => 0,
			'formaPago' => $formaPagoTexto,
			'numCuentaBanco' => $datosCliente["nuestraCuenta"],
			'descripcion' => $campana,
			'detallada' => $detallada,
			'precioNeto' => $precioNeto,
			'iva' => $iva,
			'irpf' => $irpf,
			'precioTotal' => $precioTotal,
			'provision' => $provision,
			'aPagar' => $aPagar,
			'inicialComercial' => $inicialComercial,
			'combinadoSumatorio' => $combinadoSumatorio,
			'prefactura' => $prefactura,
			'serieFactura' => 'FAC',
			'dirPost_nombreEmpresa' => $datosCliente['nombre_empresa'],
			'dirPost_direccion' => $datosCliente['direccion'],
			'dirPost_cp' => $datosCliente['codigo_postal'],
			'dirPost_poblacion' => $datosCliente['localidad'],
			'dirPost_provincia' => $datosCliente['provincia'],
			'dirPost_pais' => $datosCliente['nombrePais'],
			'dirPost_codigoPais' => $datosCliente['codigoPais'],
			'dirPost_Nif' => $datosCliente['nif'],
			'retener' => $datosCliente['retener']
		);

		if ($datosCliente['envio_domicilio']=="" && $datosCliente['envio_cp']=="" && $datosCliente['envio_poblacion']=="" && $datosCliente['envio_provincia']=="")
		{
			$datos['dirEnv_nombreEmpresa'] = $datosCliente['nombre_empresa'];
			$datos['dirEnv_direccion'] = $datosCliente['direccion'];
			$datos['dirEnv_cp'] = $datosCliente['codigo_postal'];
			$datos['dirEnv_poblacion'] = $datosCliente['localidad'];
			$datos['dirEnv_provincia'] = $datosCliente['provincia'];
			$datos['dirEnv_pais'] = $datosCliente['nombrePais'];
			$datos['dirEnv_att'] = '';
		}
		else
		{
			$datos['dirEnv_nombreEmpresa'] = $datosCliente['envio_nombre'];
			$datos['dirEnv_direccion'] = $datosCliente['envio_domicilio'];
			$datos['dirEnv_cp'] = $datosCliente['envio_cp'];
			$datos['dirEnv_poblacion'] = $datosCliente['envio_poblacion'];
			$datos['dirEnv_provincia'] = $datosCliente['envio_provincia'];
			$datos['dirEnv_pais'] = $datosCliente['envio_pais'];
			$datos['dirEnv_att'] = $datosCliente['envio_att'];
		}

		// fechaRealizacion de la factura combinada = fechaTerminado de uno cualquiera de los presupuestos combinados
		$resPresupuesto = cargarPresupuestos($conn, $bbddSql, ['fechaTerminado'], [], ['presupuesto' => $presupuestos2[0]], [], []);
		if (!empty($resPresupuesto['datos'])) {
			$datos['fechaRealizacion'] = $resPresupuesto['datos'][0]['fechaTerminado'];
		}

		if ($clayma)
		{
			$resultado1 = insertarFacturacionClayma($conn, $bbddSql, $datos);
		}
		else
		{
			$resultado1 = insertarFacturacion($conn, $bbddSql, $datos);
		}

		$numFactura = 0;

		if ($resultado1['ok'])
		{
			$numFactura = $resultado1['numero'];
			$numeroFacturaCompleto = $resultado1['numeroFacturaCompleto'];

			$contador = 0;
			$seguir = true;
			$error = '';
			$erroresDetalle = false;

			while ($contador<count($presupuestos2) && $seguir == true)
			{
				if ($combinadoSumatorio==0)
				{
					$campana="sin nombre";
					$contador2=0;
					$seguir2=true;
					while ($contador2<count($presupuestosAimprimir)&& $seguir2)
					{
						if($presupuestos2[$contador]==$presupuestosAimprimir[$contador2]["presupuesto"])
						{
							$seguir2=false;
						}
						$campana = $presupuestosAimprimir[$contador2]["campana"];
						$contador2++;
					}

				}

				$resDetalleTemporal = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['concepto','descripcion','unidades','precio','total','ordenTipo','orden','tipoIva'], ['presupuesto' => $presupuestos2[$contador], 'idEmpleado' => $usuario], [], []);

				foreach ($resDetalleTemporal['datos'] as $detalle)
				{
					$datosDetalle = $detalle;
					$datosDetalle['presupuesto'] = $presupuestos2[$contador];
					$datosDetalle['numeroFacturaCompleto'] = $numeroFacturaCompleto;
					$datosDetalle['campana'] = $campana;

					if ($clayma)
					{
						$resDetInsert = insertarFacturacionDetallesClayma($conn, $bbddSql, $datosDetalle);
					}
					else
					{
						$resDetInsert = insertarFacturacionDetalles($conn, $bbddSql, $datosDetalle);
					}

					if (!$resDetInsert['ok'])
					{
						$erroresDetalle = true;
					}
				}

				eliminarFacturasDetallesTemporal($conn, $bbddSql, ['presupuesto' => $presupuestos2[$contador]], []);
				eliminarFacturasTemporal($conn, $bbddSql, ['presupuesto' => $presupuestos2[$contador]], []);

				$resultado6 = modificarProvisionFondo($conn, $bbddSql, ['facCompletaAplicada' => $numeroFacturaCompleto], ['presupuesto' => $presupuestos2[$contador], 'cobrada' => 2, 'tipo' => 3, 'sinFacturaAplicada' => 1], []);

				if (!$resultado6['ok'])
				{
					$seguir = false;
					$error = $resultado6['error'];
				}

				$contador++;
			}

			if ($seguir == true)
			{
				if (!$erroresDetalle)
				{
					lanzarFactura($conn, $bbddSql, $numeroFacturaCompleto, $clayma);
				}

				echo json_encode(array('error' => '', 'ok' => true, 'numFactura' => $numFactura, 'numeroFacturaCompleto' => $numeroFacturaCompleto, 'anioSeleccionado' => $anioSeleccionado));
			}
			else
			{
				echo json_encode(array('error' => $error, 'ok' => false));
			}

		}
		else
		{
			echo json_encode(array('error' => 'Error al crear la factura: '.$resultado1['error'], 'ok' => false));
		}

		sqlsrv_close($conn);
	}
	else
	{
		sqlsrv_close($conn);
		echo json_encode(array('error' => 'No se puede generar la factura en este momento', 'ok' => false));
	}

}


?>
