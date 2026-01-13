<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="grabarFranqueo")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	
	$idProducto = $_POST["idProducto"];
	$idCliente = $_POST["idCliente"];
	$fecha = $_POST["fecha"];
	$totalEnvios = $_POST["totalEnvios"];
	$ot = $_POST["ot"];
	$otSidi = $_POST["otSidi"];
	$contenido = $_POST["contenido"];
	$anadidos = $_POST["anadidos"];
	
	$idEmpleado = $_SESSION["idEmpleado"];
	$detalle="";
	
	//importe y detalle
	
	$datos = explode("|193fea32|", $contenido);
	$importeTotal=0;
	
	$anioSeleccionado =   date("Y", strtotime($fecha));
	$anioActual = date('Y');
	
	if ($anioActual==$anioSeleccionado)
	{
		foreach ($datos as $valor)
		{
			$valores = explode("|3432ji31|", $valor);

			$tipo = $valores[0];
			$cantidad = $valores[1];

			$datosFranqueo = verImporteProductoFranqueo($conexion, $tipo, $cantidad,$anioSeleccionado);

			$importeTotal += $datosFranqueo[0]["importe"];

			$detalle .= $datosFranqueo[0]["titulo"]."(".$datosFranqueo[0]["gramos"]."): ".$cantidad;
			$detalle .= "|";		

			/*echo ("\n".$datosFranqueo[0]["tipos"].": ".$datosFranqueo[0]["importe"]);
			echo ("\nPrecioNeto: ".$datosFranqueo[0]["precioNeto"]);
			echo ("\nUnidades: ".$cantidad);		
			echo ("\n");*/

			$seguir=false;
			if ($anadidos=="AcuseRecibo")
			{
				$datosFranqueo2 = verImporteProductoFranqueoAcuseRecibo($conexion, $tipo, $cantidad,$anioSeleccionado);
				$seguir = true;
			}
			else if($anadidos=="PEE")
			{
				$datosFranqueo2 = verImporteProductoFranqueoPEE($conexion, $tipo, $cantidad,$anioSeleccionado);
				$seguir = true;
			}
			if ($seguir==true)
			{
				$importeTotal += $datosFranqueo2[0]["importe"];
			}



		}

		$detalle = substr($detalle, 0, -1);


		//echo ("\nImporte: ".$importeTotal);	

		insertarGrabacionFranqueo($conexion,$fecha,$idCliente,$ot,$otSidi,$importeTotal,$totalEnvios,$idProducto,$detalle,$idEmpleado,$anadidos,$anioSeleccionado);


		$referencia = verUltimaReferenciaPorUsuario($conexion,$idEmpleado,$anioSeleccionado);

		foreach ($datos as $valor)
		{
			$valores = explode("|3432ji31|", $valor);

			$tipo = $valores[0];
			$cantidad = $valores[1];

			$datosFranqueo = verImporteProductoFranqueo($conexion, $tipo, $cantidad,$anioSeleccionado);		

			//echo ("\nReferencia: ".$referencia[0]["referencia"]);

			echo insertarGrabacionFranqueoTipos($conexion,$idCliente, $ot,$otSidi, $fecha, $tipo, $cantidad, $datosFranqueo[0]["importe"],$referencia[0]["referencia"],$datosFranqueo[0]["importeSinIva"],$anioSeleccionado);


			$seguir=false;
			if ($anadidos=="AcuseRecibo")
			{
				$datosFranqueo2 = verImporteProductoFranqueoAcuseRecibo($conexion, $tipo, $cantidad,$anioSeleccionado);
				$seguir = true;
			}
			else if($anadidos=="PEE")
			{
				$datosFranqueo2 = verImporteProductoFranqueoPEE($conexion, $tipo, $cantidad,$anioSeleccionado);
				$seguir = true;
			}

			if ($seguir==true)
			{
				//echo("hola: ".$datosFranqueo2[0]["tipos"]);
				echo insertarGrabacionFranqueoTipos($conexion,$idCliente, $ot,$otSidi, $fecha, $datosFranqueo2[0]["tipos"], $cantidad, $datosFranqueo2[0]["importe"],$referencia[0]["referencia"],$datosFranqueo2[0]["importeSinIva"],$anioSeleccionado);
			}
		}

		$fechaActual = date('d-m-Y');
		$fechaGuardada = date("d-m-Y", strtotime($fecha));

		if (($_SESSION["permiso_franqueoF12"]==1||$_SESSION["permiso_franqueoF12"]==2) && $fechaGuardada<$fechaActual)
		{
			echo ejecutarF12PorReferencia($conexion, $referencia[0]["referencia"],$anioSeleccionado);

			$resultado = verImportesTotalesFranqueoPorReferencia($conexion, $referencia[0]["referencia"],$anioSeleccionado);		

			$contador=0;

			while ($contador<count($resultado))
			{
				$fecha = date("d-m-Y");
				$importe = floatval($resultado[$contador]["importeTotal"])*-1;
				$codigoCliente = $resultado[$contador]["idCodigoSaldo"];


				///////////////////////////////////////

				$saldo = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente);
				$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
				$informacionCuadre='';
				$fechaCuadre='';
				$formaPago='';
				$presupuesto='';
				echo insertarMovimientoPF($conexion,$codigoCliente,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo);	


				////////////////////////////////////////

				echo modificarDatosPFenCliente($conexion,$codigoCliente,$fecha,$importe);

				$contador++;
			}

		}
	}
	else
	{
		echo "Error: Solo se puede grabar si la fecha esta dentro de este año";
	}
	
	
	
	
	
	
	
}

?>
