<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="copiarPresupuestoAFacturaDetalleTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$numPresupuesto = isset($_POST["numPresupuesto"])?$_POST["numPresupuesto"]:"";
	$opcion = isset($_POST["opcion"])?$_POST["opcion"]:"";

	if ($numPresupuesto != "" && $opcion != "")
	{
		$usuario = $_SESSION["idEmpleado"];
	

		$conn1 = conectarSQL($conexion);
		$conn = $conn1['conn'];
		$bbddSql = $conn1['bbdd'];
		
		if ($opcion==1)//no esta en el array
		{//echo 'hola1';
			

			eliminarFacturasDetallesTemporal($conn, $bbddSql, ['presupuesto' => $numPresupuesto, 'idEmpleado' => $usuario], []);

			$detalles = cargarDetallesPresupuesto($conn, $bbddSql, ['presupuesto','proceso','descripcion','notaCibeles','unidades','unidades2','precio','ordenTipo','orden','idTipo','exentoIVA'], [], ['presupuesto' => $numPresupuesto], [], []);

			foreach ($detalles['datos'] as $detalle) {
				if ($detalle['precio']==0 && in_array($detalle['idTipo'], array(1,2,7,9))) {
					continue;
				}

				$unidades = ($detalle['unidades2']!==null) ? $detalle['unidades2'] : $detalle['unidades'];
				$total = round($unidades * $detalle['precio'], 2);

				insertarFacturasDetallesTemporal($conn, $bbddSql, array(
					'idEmpleado' => $usuario,
					'presupuesto' => $detalle['presupuesto'],
					'concepto' => $detalle['proceso'],
					'descripcion' => $detalle['descripcion'],
					'notaCibeles' => $detalle['notaCibeles'],
					'unidades' => $unidades,
					'precio' => $detalle['precio'],
					'total' => $total,
					'ordenTipo' => $detalle['ordenTipo'],
					'orden' => $detalle['orden'],
					'idTipoProceso' => $detalle['idTipo'],
					'exentoIVA' => $detalle['exentoIVA'],
					'tipoIva' => ($detalle['exentoIVA']==1 ? 0 : 21)
				));
			}

			$res = eliminarFacturasTemporal($conn, $bbddSql, ['usuario' => $usuario, 'presupuesto' => $numPresupuesto], []);		//nunca deberia de ocurrir..... pero por si acaso

			
		}
		else if ($opcion==2)//esta en el array: esto se hace para que no se borre las modificaciones realizadas anteriormente
		{//echo 'hola2';
			$datos = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['id'], ['presupuesto' => $numPresupuesto, 'idEmpleado' => $usuario], [], []);
			if (count($datos['datos'])<=0)
			{			
				eliminarFacturasDetallesTemporal($conn, $bbddSql, ['presupuesto' => $numPresupuesto, 'idEmpleado' => $usuario], []);

				$detalles = cargarDetallesPresupuesto($conn, $bbddSql, ['presupuesto','proceso','descripcion','notaCibeles','unidades','unidades2','precio','ordenTipo','orden','idTipo','exentoIVA'], [], ['presupuesto' => $numPresupuesto], [], []);

				foreach ($detalles['datos'] as $detalle) {
					if ($detalle['precio']==0 && in_array($detalle['idTipo'], array(1,2,7,9))) {
						continue;
					}

					$unidades = ($detalle['unidades2']!==null) ? $detalle['unidades2'] : $detalle['unidades'];
					$total = round($unidades * $detalle['precio'], 2);

					insertarFacturasDetallesTemporal($conn, $bbddSql, array(
						'idEmpleado' => $usuario,
						'presupuesto' => $detalle['presupuesto'],
						'concepto' => $detalle['proceso'],
						'descripcion' => $detalle['descripcion'],
						'notaCibeles' => $detalle['notaCibeles'],
						'unidades' => $unidades,
						'precio' => $detalle['precio'],
						'total' => $total,
						'ordenTipo' => $detalle['ordenTipo'],
						'orden' => $detalle['orden'],
						'idTipoProceso' => $detalle['idTipo'],
						'exentoIVA' => $detalle['exentoIVA'],
						'tipoIva' => ($detalle['exentoIVA']==1 ? 0 : 21)
					));
				}
			}
			
			//para los datos del cliente (facturasTemporal)
			$datos2 = mostrarFacturasTemporal($conn, $bbddSql, ['id'], ['usuario' => $usuario, 'presupuesto' => $numPresupuesto], [], []);
			if (count($datos2['datos'])<=0)
			{			
				//borrarPrefacturaCabeceraTemporal2($conexion,$usuario,$numPresupuesto);	
				
				/////////////////////////////////
				
				$camposClayma = cargarPresupuestos($conn, $bbddSql, ['clayma'], [], ['presupuesto' => $numPresupuesto], [], []);
				$esClayma = (!empty($camposClayma['datos']) && $camposClayma['datos'][0]['clayma'] == 1);

				$campos = ['clayma','pedcli','cantidad','idFormaPago','campana2','detallada'];
				$campos[] = $esClayma ? 'codigo_saldoClayma' : 'codigo_saldo';
				$joins = [$esClayma ? 'tabla8' : 'tabla7'];

				$resultado2 = cargarPresupuestos($conn, $bbddSql, $campos, $joins, ['presupuesto' => $numPresupuesto], [], []);
				$resultado = $resultado2['datos'];

				$idCliente = $resultado[0]["codigo_saldo"];
				$clayma = $resultado[0]["clayma"];
				$pedido = $resultado[0]["pedcli"];
				$cantidad = $resultado[0]["cantidad"];
				$formaPago = $resultado[0]["idFormaPago"];
				$campana = $resultado[0]["campana2"];
				$detallada = $resultado[0]["detallada"];			
				$Neto = 0;
				$iva = 0;
				$total = 0;
				$provisionTotal = 0;
				$aPagar = 0;			
				$presupuesto = $numPresupuesto;
				
				
				insertarFacturasTemporal($conn, $bbddSql, array(
					'idCliente' => $idCliente,
					'usuario' => $usuario,
					'clayma' => $clayma,
					'pedido' => $pedido,
					'cantidad' => $cantidad,
					'formaPago' => $formaPago,
					'descripcion' => $campana,
					'detallada' => $detallada,
					'precioNeto' => $Neto,
					'iva' => $iva,
					'precioTotal' => $total,
					'provision' => $provisionTotal,
					'aPagar' => $aPagar,
					'presupuesto' => $presupuesto
				));
					
					
			}

			$res = $datos2 ;
			
		}
		
		sqlsrv_close($conn);

		echo json_encode($res);
	}
	else
	{
		$res["error"] = "Parametros vacios";
	}

	
	
	
	
	
	
	
}


?>
