<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarFranqueoTipo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];	
	$tipo = $_POST["tipo"];
	$unidades = $_POST["unidades"];
	$importe = $_POST["importe"];
	
	$ot = $_POST["ot"];
	$otSidi = $_POST["otSidi"];
	$fecha = $_POST["fecha"];
	
	
	$anioSeleccionado =  date("Y", strtotime($fecha));
	
	
	$idCliente = $_POST["idCliente"];
	
	$referencia = $_POST["referencia"];
	
	
	$destino = "";
	$gramos = "";
	$tipo1 = "";
	
	$valores = explode("-", $tipo);
	
	$contador =0;
	foreach ($valores as $valor) 
	{
    	if ($contador==0)
		{
			$destino = $valor;
		}
		else if($contador==1)
		{
			$gramos = $valor;
		}
		else
		{
			$tipo1 .= $valor."-";
		}
		$contador++;
	}
	
	
	
	$tipo1 = substr($tipo1, 0, -1);
	
	/*echo "\nDestino: ".$destino;
	echo "\nGramos: ".$gramos;
	echo "\ntipo: ".$tipo1;
	echo "\n";*/
	
	
	////////////////////////////////////////
	
	$datosAntiguos = verDatosFranqueoPorRefenciaEid($conexion,$referencia,$id,$anioSeleccionado);
	
	
	if ($datosAntiguos[0]["comprobado"]==0 || $datosAntiguos[0]["comprobado"]=="0")
	{
		
		echo modificarFranqueoTipoPorId($conexion,$id,$tipo1,$unidades,$importe,$anioSeleccionado);	
	
		modificarFranqueoTipoPorReferencia($conexion,$referencia,$idCliente,$fecha,$ot,$otSidi,$anioSeleccionado);


		$datos = verDatosFranqueoTipoPorReferencia($conexion,$referencia,$anioSeleccionado);

		$importeTotal=0;
		$enviosTotal=0;
		$detalle="";

		$anadidos="";

		foreach ($datos as $valor)
		{		
			$importeTotal = $importeTotal + $valor["importe"];




			if ($valor["titulo"]=="" || $valor["titulo"]==NULL)
			{
				$valores = explode(" ", $valor["descripcion"]);

				$anadidos=$valores[0];
				//echo "\naqui: ".$valores[0];

			}
			else
			{

				$detalle = $detalle . $valor["titulo"]."(".$valor["gramos"]."): ".$valor["unidades"]."|";
				$enviosTotal = $enviosTotal + $valor["unidades"];
				//$anadidos="";
			}

			//echo "\nimporte: ".$importeTotal;
			//echo "\nenvios: ".$enviosTotal;
		}

		$detalle = substr($detalle, 0, -1);

		modificarFranqueoPorReferencia($conexion,$referencia,$idCliente,$ot,$otSidi,$fecha,$importeTotal,$enviosTotal,$detalle,$anadidos,$anioSeleccionado);
		
	}
	else // comprobado = 1
	{		
		//if ($datosAntiguos[0]["idCliente"]!= $idCliente)
		{			
			
			
			////CAMBIO DE SALDO EN EL CLIENTE ANTIGUO
			$resultado1 = verCodigoSaldoPorReferenciaFranqueo($conexion,$referencia,$anioSeleccionado);
			
			$fecha1 = date("d-m-Y");
			//$importe1 = floatval($resultado1[0]["importe"]); // importe total de la referencia - ESTO ESTA MAL
//////////////////////////////////////////////////
			$importe1 = $datosAntiguos[0]["importe"];


////////////////////////////////////////////////////////


			$codigoCliente1 = $resultado1[0]["codigo_saldo"];

			$saldo1 = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente1." and codigo = ".$codigoCliente1);
			$nuevoSaldo1 = $saldo1[0]["importePF"] + $importe1;	
			$informacionCuadre1='modificacion desde franqueo grabacion';
			$fechaCuadre1='';
			$formaPago1='';
			$presupuesto1='';
			
			
			echo insertarMovimientoPF($conexion,$codigoCliente1,$fecha1,$formaPago1,$importe1,$presupuesto1,$fechaCuadre1,$informacionCuadre1,$nuevoSaldo1);
			
			echo modificarDatosPFenCliente($conexion,$codigoCliente1,$fecha1,$importe1); //se le suma el importe antiguo al saldo
			
			
			////CAMBIO DE SALDO EN EL CLIENTE NUEVO
			echo modificarFranqueoTipoPorId($conexion,$id,$tipo1,$unidades,$importe,$anioSeleccionado);	
			
			modificarFranqueoTipoPorReferencia($conexion,$referencia,$idCliente,$fecha,$ot,$otSidi,$anioSeleccionado); //se cambiar en todos los registros de la misma referencia
			
			
			
			
			
			//$resultado2 = verCodigoSaldoPorReferenciaFranqueo($conexion,$referencia,$anioSeleccionado);
			$fecha2 = date("d-m-Y");
			//$importe2 = floatval($resultado2[0]["importe"]);
			$importe2 = $importe;
			//$codigoCliente2 = $resultado2[0]["codigo_saldo"];

			$saldo2 = cargarClientes($conexion," where codigo_saldo = ".$codigoCliente1." and codigo = ".$codigoCliente1);
			$nuevoSaldo2 = $saldo2[0]["importePF"] - $importe2;	
			$informacionCuadre2='modificacion desde franqueo grabacion';
			$fechaCuadre2='';
			$formaPago2='';
			$presupuesto2='';			
			
			$importe2 = $importe2*-1;
			
			echo insertarMovimientoPF($conexion,$codigoCliente1,$fecha2,$formaPago2,$importe2,$presupuesto2,$fechaCuadre2,$informacionCuadre2,$nuevoSaldo2);
			
			echo modificarDatosPFenCliente($conexion,$codigoCliente1,$fecha2,$importe2);
			
			
			
			
			$datos = verDatosFranqueoTipoPorReferencia($conexion,$referencia,$anioSeleccionado);

			$importeTotal=0;
			$enviosTotal=0;
			$detalle="";

			$anadidos="";

			foreach ($datos as $valor)
			{		
				$importeTotal = $importeTotal + $valor["importe"];




				if ($valor["titulo"]=="" || $valor["titulo"]==NULL)
				{
					$valores = explode(" ", $valor["descripcion"]);

					$anadidos=$valores[0];
					//echo "\naqui: ".$valores[0];

				}
				else
				{

					$detalle = $detalle . $valor["titulo"]."(".$valor["gramos"]."): ".$valor["unidades"]."|";
					$enviosTotal = $enviosTotal + $valor["unidades"];
					//$anadidos="";
				}

				//echo "\nimporte: ".$importeTotal;
				//echo "\nenvios: ".$enviosTotal;
			}

			$detalle = substr($detalle, 0, -1);
			
			modificarFranqueoPorReferencia($conexion,$referencia,$idCliente,$ot, $otSidi,$fecha,$importeTotal,$enviosTotal,$detalle,$anadidos,$anioSeleccionado);
			
			
			
		}
	}
	
	
	
	
	
	
	//////////////////////////////////////
	
	
	
}


?>
