<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verDatosPresupuestosCombinados")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	if (mostrarSePuedeImprimir($conn, $bbddSql) == 1)
	{
		$presupuestos = $_POST["presupuestos"];
		$combinadoSumatorio = $_POST["combinadoSumatorio"];

		if ($combinadoSumatorio=="false")
			$combinadoSumatorio=0;
		else
			$combinadoSumatorio=1;

		$presupuestos2 = explode(" - ", $presupuestos);

		$usuario = $_SESSION["idEmpleado"];
		$resDatosFactura = mostrarFacturasTemporal($conn, $bbddSql, ['idCliente','pedido','descripcion','detallada','formaPago'], ['usuario' => $usuario], [], []);
		$datosFactura = $resDatosFactura['datos'];

		$campana = $datosFactura[0]["descripcion"];
		$presupuestosAimprimir = "";


		if ($combinadoSumatorio==1)
		{
			$campana="";
		}
		else
		{
			$resCombinados = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['presupuestoDistinct','campana'], ['idEmpleado' => $usuario], [], [['campo'=>'presupuesto','dir'=>'ASC']], [], ['tabla2']);
			$presupuestosAimprimir = $resCombinados['datos'];
			//$campana="";
		}

		$idCliente = $datosFactura[0]["idCliente"];
		$camposCliente = ['nombre_empresa','direccion','codigo_postal','localidad','provincia','pais','codigoPais','nif','nombrePais','envio_nombre','envio_att','envio_domicilio','envio_cp','envio_poblacion','envio_provincia','envio_pais','retener','nuestraCuenta'];
		$resCliente = cargarClientesClayma($conn, $bbddSql, $camposCliente, ['codigo_saldo' => $idCliente, 'codigo' => $idCliente], [], [], [], ['tabla4']);
		$datosCliente = $resCliente['datos'][0];
		$pedido = $datosFactura[0]["pedido"];
		$detallada = $datosFactura[0]["detallada"];

		$resFormasDePago = cargarFormasDePago($conn, $bbddSql, ['id','concepto'], [], []);
		$formaPago = "";
		foreach ($resFormasDePago as $filaFormaPago)
		{
			if ($filaFormaPago['id']==$datosFactura[0]['formaPago'])
			{
				$formaPago = $filaFormaPago['concepto'];
			}
		}

		$cuentaBancaria = $datosCliente["nuestraCuenta"];
		$nombreCliente = $datosCliente["nombre_empresa"];


		$resSumatorioPrecio = mostrarFacturasTemporal($conn, $bbddSql, ['precioNetoSumatorio','ivaSumatorio','precioTotalSumatorio','provisionSumatorio','aPagarSumatorio','irpfSumatorio'], ['usuario' => $usuario, 'idCliente' => $idCliente], [], []);
		$sumatorioPrecio = $resSumatorioPrecio['datos'];

		$precioNeto = $sumatorioPrecio[0]["precioNeto"];
		$provision = $sumatorioPrecio[0]["provision"];
		$aPagar = $sumatorioPrecio[0]["aPagar"];
		$cantidad = 0;

		$precioNeto = round($precioNeto,2);

		$iva = $sumatorioPrecio[0]["iva"];
		$precioTotal = $sumatorioPrecio[0]["precioTotal"];

		$irpf =  $sumatorioPrecio[0]["irpf"];
		if ($irpf!=0)
		{
	 		//$irpf=round($precioNeto*19/100)*-1;	//esto se hace para evitar fallos en los redondeos
		}
		else
		{
			$irpf=0;
		}

		$campana = "";
		$inicialComercial=0;


		$resFecha = mostrarFacturarFechaActualClayma($conn, $bbddSql, ['activado','fechaImprimir']);
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
			'cliente' => $nombreCliente,
			'idCodigoCliente' => $idCliente,
			'fecha' => $fecha,
			'pedido' => $pedido,
			'cantidad' => $cantidad,
			'formaPago' => $formaPago,
			'numCuentaBanco' => $cuentaBancaria,
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

		$resultado1 = insertarFacturacionClayma($conn, $bbddSql, $datos);

		$numFactura = 0;

		if ($resultado1['ok'])
		{
			$numFactura = $resultado1['numero'];
			$numeroFacturaCompleto = $resultado1['numeroFacturaCompleto'];

			$contador = 0;
			$seguir = true;
			$error = '';

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

					insertarFacturacionDetallesClayma($conn, $bbddSql, $datosDetalle);
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
		echo json_encode(array('error' => 'No se puede imprimir en este momento', 'ok' => false));
	}

}


?>
