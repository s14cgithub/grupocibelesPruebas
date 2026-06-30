<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="grabarFranqueo")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		

	
	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	$contenido = $_POST["contenido"];

	$datosTipos = explode("|193fea32|", $contenido);

	$idEmpleado = $_SESSION["idEmpleado"];

	
	if (strlen($idEmpleado)==1)
	{
		$idEmpleado = "0".$idEmpleado;
	}


	$detalle="";

	$anioSeleccionado =   date("Y", strtotime($datos["fecha"]));
	$anioActual = date('Y');

	
	$importeTotal=0;

	if ($anioActual==$anioSeleccionado)
	{
		$conn1 = conectarSQL($conexion);
		$conn = $conn1['conn'];
		$bbddSql = $conn1['bbdd'];

		foreach ($datosTipos as $valor)
		{
			$valores = explode("|3432ji31|", $valor);

			$tipo = $valores[0];
			$cantidad = $valores[1];


			$datos2 = [
				'importe_cantidadIndicada',
				'titulo',
				'gramos',
				'importeSinIva_cantidadIndicada'
			];
			$joins2 = ['tabla2'];

			$filtros2 = [
				'tipos' => $tipo		
			];
			$filtrosOperadores2 = array();
			$order2 = array();			

			$datosFranqueo = cargarTarifasFranqueo($conn,$bbddSql, $datos2, $joins2, $filtros2, $filtrosOperadores2, $order2,$anioSeleccionado,$cantidad);


			$importeTotal += $datosFranqueo["datos"][0]["importe"];
			$detalle .= $datosFranqueo["datos"][0]["titulo"]."(".$datosFranqueo["datos"][0]["gramos"]."): ".$cantidad;
			$detalle .= "|";
			


			$datos3 = [
				'importe_cantidadIndicada',
				'tipos',
				'importeSinIva_cantidadIndicada'				
			];
			$joins3 = array();

			
			$filtrosOperadores3 = array();
			$order3 = array();		


			$seguir=false;
			if ($datos["anadidos"] == "AcuseRecibo")
			{
				$filtros3 = [
					'tipos_acuseRecibo' => $tipo		
				];
				
				$datosFranqueo2 = cargarTarifasFranqueo($conn,$bbddSql, $datos3, $joins3, $filtros3, $filtrosOperadores3, $order3,$anioSeleccionado,$cantidad);
				$seguir = true;
			}
			else if($datos["anadidos"]=="PEE")
			{
				$filtros3 = [
					'tipos_PEE' => $tipo		
				];

				$datosFranqueo2 = cargarTarifasFranqueo($conn,$bbddSql, $datos3, $joins3, $filtros3, $filtrosOperadores3, $order3,$anioSeleccionado,$cantidad);
				$seguir = true;
			}
			if ($seguir==true)
			{
				$importeTotal += $datosFranqueo2["datos"][0]["importe"];
			}			
		}

		$detalle = substr($detalle, 0, -1);

		$datos["importe"] = $importeTotal;
		$datos["detalle"] = $detalle;
		$datos["idEmpleado"] = $idEmpleado;


		insertarGrabacionFranqueo($conn, $bbddSql, $datos);


		$datos4 = [
			'referencia'
		];

		$joins4 = array();

		$filtros4 = [
			'verUltimaReferenciaPorUsuario' => $idEmpleado	
		];

		$filtrosOperadores4 = array();
		$order4 = array();
		//$referencia = verUltimaReferenciaPorUsuario($conexion,$idEmpleado,$anioSeleccionado);
		$referencia = cargarFranqueo($conn, $bbddSql, $datos4, $joins4, $filtros4, $filtrosOperadores4, $order4);
		
		

		foreach ($datosTipos as $valor)
		{
			$valores = explode("|3432ji31|", $valor);

			$tipo = $valores[0];
			$cantidad = $valores[1];

			$datos5 = [
				'importe_cantidadIndicada',				
				'importeSinIva_cantidadIndicada'
			];
			$joins5 = ['tabla2'];

			$filtros5 = [
				'tipos' => $tipo		
			];
			$filtrosOperadores5 = array();
			$order5 = array();
			
			$datosFranqueo = cargarTarifasFranqueo($conn,$bbddSql, $datos5, $joins5, $filtros5, $filtrosOperadores5, $order5,$anioSeleccionado,$cantidad);
			


			$datos6 =  [
				'idCliente' => $datos["idCliente"],
				'ot' =>  $datos["ot"],
				'otSidi' => $datos["otSidi"],
				'fecha' => $datos["fecha"],
				'tipo' => $tipo,
				'unidades' =>  $cantidad,
				'importe' => $datosFranqueo["datos"][0]["importe"],
				'referencia' => $referencia["datos"][0]["referencia"],
				'importeSinIva' => $datosFranqueo["datos"][0]["importeSinIva"],
				'numSeguimiento' => '',
				'importado' => 0,
				'txt' => 0,
				'comprobado' => 0,
				'nombre' => '',
				'direccion' => '',
				'poblacion' => '',
				'cp' => ''				
			];			


			insertarGrabacionFranqueoTipos($conn, $bbddSql, $datos6);

			/*
		sqlsrv_close($conn);	
		echo json_encode($prueba);		
		exit();
		*/

			$datos7 = [
				'importe_cantidadIndicada',
				'tipos',
				'importeSinIva_cantidadIndicada'				
			];
			$joins7 = array();
			
			$filtrosOperadores7 = array();
			$order7 = array();	

			$seguir=false;
			if ($datos["anadidos"]=="AcuseRecibo")
			{	//echo "entra1";
				$filtros7 = [
					'tipos_acuseRecibo' => $tipo		
				];				
				$seguir = true;
			}
			else if($datos["anadidos"]=="PEE")
			{ //echo "entra2";
				$filtros7 = [
					'tipos_PEE' => $tipo		
				];
				$seguir = true;
			}

			if ($seguir==true)
			{
				$datosFranqueo2 = cargarTarifasFranqueo($conn,$bbddSql, $datos7, $joins7, $filtros7, $filtrosOperadores7, $order7,$anioSeleccionado,$cantidad);
				
				/*
				sqlsrv_close($conn);
	
				echo json_encode($datosFranqueo2);
				exit();
*/
				$datos8 = [	
					'idCliente' => $datos["idCliente"],
					'ot' =>  $datos["ot"],
					'otSidi' => $datos["otSidi"],
					'fecha' => $datos["fecha"],
					'tipo' => $datosFranqueo2["datos"][0]["tipos"],
					'unidades' =>  $cantidad,
					'importe' => $datosFranqueo2["datos"][0]["importe"],
					'referencia' => $referencia["datos"][0]["referencia"],
					'importeSinIva' => $datosFranqueo2["datos"][0]["importeSinIva"],
					'numSeguimiento' => '',
					'importado' => 0,
					'nombre' => '',
					'direccion' => '',
					'poblacion' => '',
					'cp' => ''					
				];			


				insertarGrabacionFranqueoTipos($conn, $bbddSql, $datos8);


				//echo("hola: ".$datosFranqueo2["datos"][0]["tipos"]);
				//insertarGrabacionFranqueoTipos($conexion,$idCliente, $ot,$otSidi, $fecha, $datosFranqueo2["datos"][0]["tipos"], $cantidad, $datosFranqueo2["datos"][0]["importe"],$referencia["datos"][0]["referencia"],$datosFranqueo2["datos"][0]["importeSinIva"],$anioSeleccionado);
			}
		}

		$fechaActual = date('d-m-Y');
		$fechaGuardada = date("d-m-Y", strtotime($datos["fecha"]));

		if (($_SESSION["permiso_franqueoF12"]==1||$_SESSION["permiso_franqueoF12"]==2) && $fechaGuardada<$fechaActual)
		{
			$datos9 = [
				'comprobado' => 1
			];			

			$filtros9 = [
				'comprobado' => 0,
				'referencia' => $referencia["datos"][0]["referencia"]
			];

			$filtrosOperadores9 = array();		


			modificarFranqueo($conn, $bbddSql, $datos9, $filtros9, $filtrosOperadores9);

			modificarFranqueoTipos($conn, $bbddSql, $datos9, $filtros9, $filtrosOperadores9);





			$datos10 = [
				'idCliente',
				'importeTotal',
				'codigo_saldo'
			];			

			$filtros10 = [				
				'referencia' => $referencia["datos"][0]["referencia"]
			];

			$filtrosOperadores10 = array();	
			$joins10 =['tabla2'];

			$group10 = [
				'idCliente',
				'codigo_saldo'				
			];

			$order10 = [
				['campo' => 'idCliente', 'dir' => 'ASC']
			];
			

			$resultado = cargarFranqueoTipos($conn, $bbddSql, $datos10, $joins10, $filtros10, $filtrosOperadores10, $group10, $order10);

			//echo json_encode($resultado);
			//exit;

			$contador=0;

			while ($contador<count($resultado["datos"]))
			{
				$fecha = date("d-m-Y");
				$importe = floatval($resultado["datos"][$contador]["importeTotal"])*-1;
				$codigoCliente = $resultado["datos"][$contador]["codigo_saldo"];


				///////////////////////////////////////
				$datos11 = [
					'importePF'					
				];
				$filtros11 = [				
					'codigo' => $codigoCliente
				];

				$filtrosOperadores11 = array();
				$order11 = array();

				$saldo = cargarClientes($conn, $bbddSql, $datos11, $filtros11, $filtrosOperadores11, $order11);

				//echo json_encode($saldo);
				//exit();
				//exit;
				//$nuevoSaldo = $saldo["datos"][0]["importePF"] + $importe;

				$nuevoSaldo = floatval(isset($saldo["datos"][0]["importePF"]) ? $saldo["datos"][0]["importePF"] : 0) + floatval($importe);
				
				//echo "<br>importe: ".$importe;
				//echo "<br>saldo antiguo: ".$saldo["datos"][0]["importePF"];

				$datos12 = [
					'codigoCliente' => $codigoCliente,
					'fecha' => $fecha,
					'formaPago' => '',
					'importe' => $importe,
					'presupuesto' => '',
					'fechaCuadre' => NULL,
					'informacionCuadre' => '',
					'saldoPostPF' => $nuevoSaldo,
					'clayma' => 0
				];
				
				insertarProvisionDeFondo_movimientos($conn, $bbddSql, $datos12);



				$datos13 = [
					'fechaCobroPF' => $fecha,
					'importePF' => $nuevoSaldo
				];

				$datosIncremento13 = array();
				/*
				$datosIncremento13 = [
					'importePF' => $importe
				];
				*/

				$filtros13 = [
					'codigo' => $codigoCliente
				];

				$filtrosOperadores13 = array();

				$res =  modificarClientes($conn, $bbddSql, $datos13, $filtros13, $filtrosOperadores13, $datosIncremento13);



				$contador++;
			}
			
		}
		else
		{
			$res["error"]="";
			
		}	

	}
	else
	{
		$res["datos"] = "Error: Solo se puede grabar si la fecha esta dentro de este año";		
	}

	sqlsrv_close($conn);
	
	echo json_encode($res);
	
}

?>
