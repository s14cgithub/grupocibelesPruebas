<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["previsualizarAccion"]) && $_POST["previsualizarAccion"]=="previsualizarFactura")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");

	$clayma = isset($_POST['clayma']) && $_POST['clayma']==1;

	if ($clayma)
	{
		require($ruta."Archivos Comunes/cabeceraPieFacturaClayma.php");
	}
	else
	{
		require($ruta."Archivos Comunes/cabeceraPieFacturaGroupPag.php");
	}
	
	
	
	
	$numFactura = "";	
	
	$numPresupuesto = $_POST["imprimirNumPresupuestoPrevisualizacion"];
	$sumatorio1 = $_POST["imprimirCombinadoSumatorioPrevisualizacion"];
	
	$sumatorio=0;
	if ($sumatorio1=="true")
	{
		$sumatorio=1;
	}
	
	
	$usuario = $_SESSION["idEmpleado"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resCabecera = mostrarFacturasTemporal($conn, $bbddSql, ['idCliente','pedido','cantidad','formaPagoTexto','descripcion','detallada','presupuesto'], ['usuario' => $usuario], [], [], ['tabla2']);
	$datosFactura = $resCabecera['datos'];

	$formaPagoTexto = $datosFactura[0]["formaPagoTexto"];

	$resDesglose = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['total','tipoIva'], ['idEmpleado' => $usuario], [], []);
	$desgloseIva = array();
	$hayTipoIva = false;
	foreach ($resDesglose['datos'] as $rowDesglose)
	{
		if (isset($rowDesglose['tipoIva']))
		{
			$hayTipoIva = true;
			$tipo = $rowDesglose['tipoIva'];

			if ($tipo!=0)
			{
				if (!isset($desgloseIva[$tipo]))
				{
					$desgloseIva[$tipo] = 0;
				}
				$desgloseIva[$tipo] += floatval($rowDesglose['total']) * $tipo / 100;
			}
		}
	}

	foreach ($desgloseIva as $tipoRedondeo => $importeRedondeo)
	{
		$desgloseIva[$tipoRedondeo] = round($importeRedondeo, 2);
	}

	if ($hayTipoIva && !isset($desgloseIva[21]))
	{
		$desgloseIva[21] = 0;
	}

	foreach ($desgloseIva as $tipoFiltro => $importeFiltro)
	{
		if ($tipoFiltro!=21 && $importeFiltro==0)
		{
			unset($desgloseIva[$tipoFiltro]);
		}
	}
	ksort($desgloseIva);
	
	
	$eltitulo = $datosFactura[0]["descripcion"];	
	$combinados=1;
	$presupuestosAimprimir = "";
	$presupuestosAimprimirContador=1;
	
	
	
	
	if ($sumatorio==1)
	{		
		$resDetalleSumatorio = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['concepto','unidadesSumatorio','precio','totalSumatorio','descripcion','tipoIva'], ['idEmpleado' => $usuario], [], [['campo'=>'concepto','dir'=>'ASC']], ['concepto','descripcion','precio','tipoIva']);
		$datosDetalles = $resDetalleSumatorio['datos'];
		
		$resCombinadosTemp = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['presupuestoDistinct'], ['idEmpleado' => $usuario], [], [['campo'=>'presupuesto','dir'=>'ASC']]);
		$aux = $resCombinadosTemp['datos'];
		$numPresupuesto = "Comb: ";
		$contador1=0;
		while ($contador1<count($aux))
		{
			$numPresupuesto .= $aux[$contador1]["presupuesto"] . " - ";
			$contador1++;
		}
		
		$numPresupuesto = substr($numPresupuesto, 0, strlen($numPresupuesto)-3);
		
		
		$eltitulo="";
	}
	/*else if ($combinados==0)
	{
		$datosDetalles = verFacturaDetalle($conexion,$numFactura);
		$presupuestosAimprimir = $datosFactura[0]["presupuesto"];
		$eltitulo=$datosFactura[0]["descripcion"];
	}*/	
	else
	{
		$resCombinados = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['presupuestoDistinct','campana'], ['idEmpleado' => $usuario], [], [['campo'=>'presupuesto','dir'=>'ASC']], [], ['tabla2']);
		$presupuestosAimprimir = $resCombinados['datos'];
		$eltitulo="";
		$presupuestosAimprimirContador = count($presupuestosAimprimir);
		$numPresupuesto="";
	}
	
	
	
	
	$nombrePresupuestoCompleto = $numPresupuesto;
	
	
	$fecha = date('d/m/Y');
	$idCliente = $datosFactura[0]["idCliente"];
	$camposCliente = ['retener','codigo_saldo','nombre_empresa','direccion','codigo_postal','localidad','provincia','nif_subcliente','envio_att','envio_nombre','envio_domicilio','envio_cp','envio_poblacion','envio_provincia','envio_pais','nuestraCuenta','sinIva'];

	if ($clayma)
	{
		$resCliente = cargarClientesClayma($conn, $bbddSql, $camposCliente, ['codigo_saldo' => $idCliente, 'codigo' => $idCliente], [], []);
	}
	else
	{
		$resCliente = cargarClientes($conn, $bbddSql, $camposCliente, ['codigo_saldo' => $idCliente, 'codigo' => $idCliente], [], []);
	}

	$datosCliente = $resCliente['datos'];
	$pedido = $datosFactura[0]["pedido"];
	$detallada = $datosFactura[0]["detallada"];
	$formaPago = $formaPagoTexto;
	$cuentaBancaria = $datosCliente[0]["nuestraCuenta"];
	
	
	$resSumatorioPrecio = mostrarFacturasTemporal($conn, $bbddSql, ['precioNetoSumatorio','provisionSumatorio','irpfSumatorio'], ['usuario' => $usuario, 'idCliente' => $idCliente], [], []);
	$sumatorioPrecio = $resSumatorioPrecio['datos'];
	
	$precioNeto = $sumatorioPrecio[0]["precioNeto"];
	$provision = $sumatorioPrecio[0]["provision"];

	// El iva se calcula a partir de $desgloseIva (mismas lineas, agrupadas por tipoIva) en vez de
	// sumar los iva ya redondeados de cada presupuesto por separado, para que la previsualizacion
	// cuadre siempre con lo que anadirFacturaCombinada.php va a guardar.
	$iva = round(array_sum($desgloseIva), 2);

	$irpf = 0;
	if ($sumatorioPrecio[0]["irpf"]!=0)
	{
		$irpf = round($precioNeto*19/100, 2)*-1; //esto se hace para evitar fallos en los redondeos
	}

	$precioTotal = round($precioNeto + $iva + $irpf, 2);
	$aPagar = round($precioTotal - $provision, 2);
	
	/*
	if ($datosCliente[0]["sinIva"]==1)
	{
		$iva=0;
		$precioTotal=$precioNeto + $irpf;
		$aPagar = $precioTotal - $provision + $irpf;
	}
	*/
	
	$pdf = new cabeceraFactura('P','mm','A4');
	
	$pdf->StartPageGroup();
	
		

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	
	
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
		
	
	
	
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	
	
	
	
	$datosNuevaPagina1 = '';
	$datosNuevaPagina2 = "Fecha: ".date('d-m-Y');
	
	$limiteAlturaDatos = 280;
	$alturaSiguientePagina = 20;//50
	
	
	//$datosCliente =  cargarClientes($conexion," where codigo_saldo=".$datosFactura[0]["codigo"]." and codigo=".$datosFactura[0]["codigo"]);
	
	$retener = "";
		
		
	//echo $datosFactura[0]["codigo"];
		
		
		
	if ($datosCliente[0]["retener"]==1)
	{
		$retener = " -  R";
	}
	
	
	//$datosCliente=null;
		
	nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosCliente,$retener,$margenInicial,$clayma);	           
		
		
	//$alturaSiguientePagina = 40;//50
	
	
	//aqui
		
		
		
		
		
		
	
	
	
	
	
	
	////////////////////
	/*$altura += 10;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DESCRIPCION:  ",0,1,'L',false);*/

	//$altura += 10;
	//$margen += 30;
	
	/*$pdf->SetXY($margen,$altura);	
	$pdf->SetFont('Arial','B',10);	
	//$eltitulo=$datosFactura[0]["descripcion"];
	//$eltitulo = "Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021";
	$pdf->MultiCell(0,5,utf8_decode($eltitulo),0,'L',false);
	//$pdf->MultiCell(0,5,utf8_decode("Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021"."aaaaa  aaaa "),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($eltitulo));
	$numeroDeFilas = ceil ($anchoDescripcion / (180-30));
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);*/
	
	////////////////////
	$margen=$margenInicial;
	if ($pedido!="" && $pedido!="NINGUNO" )
	{		
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"PEDIDO:  ".utf8_decode($pedido),0,1,'L',false);
	}
	
	$cantidadGenerica="";
	if ($cantidadGenerica!="" && $cantidadGenerica!="0" )
	{
		$altura += 10;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,"Cantidad: ".number_format($cantidadGenerica,0,',','.'),0,0,'L',false);
	}
	
	
	
	
	/*if ($datosFactura[0]["observaciones"]!="" && $datosFactura[0]["observaciones"]!="NINGUNO" ) 
	{		
		
		$altura += 5;
		
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$observaciones = "Observaciones: ". $datosFactura[0]["observaciones"];
		$observaciones = str_replace("\n", '. ',$observaciones);
		$pdf->MultiCell(0,5,utf8_decode($observaciones),0,'L',false);		
		$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($observaciones));
		$numeroDeFilas = ceil ($anchoDescripcion / (180));
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);
		
		
	}*/
	
	
	
	$importeCombinadoPorOt=0.00;
	
	$contadorGenerico=0;
	
	while ($contadorGenerico<$presupuestosAimprimirContador)
	{
		
		
		if ($altura>190)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosCliente,$retener,$margenInicial,$clayma);
		}
		
		
		if ($combinados==1 && $sumatorio != 1) 
		{ 
			$numPresupuesto = $presupuestosAimprimir[$contadorGenerico]["presupuesto"];
			$eltitulo = $presupuestosAimprimir[$contadorGenerico]["campana"];
			
			$resDetallePresupuesto = mostrarFacturasDetallesTemporal($conn, $bbddSql, ['concepto','descripcion','tipoIva','precio','total','unidades'], ['presupuesto' => $numPresupuesto, 'idEmpleado' => $usuario], [], [['campo'=>'ordenTipo','dir'=>'ASC'],['campo'=>'orden','dir'=>'ASC']]);
			$datosDetalles = $resDetallePresupuesto['datos'];
			
			//$eltitulo = $datosDetalles[0]["campana"];
		}
		
		
		$altura += 5;
		$pdf->SetFont('Arial','B',12);
		
		if(substr($numPresupuesto, 0, 16) === "Factura Original")
		{			
			/*$pdf->SetXY($margen,$altura);		
			$pdf->Cell(0,5,"Factura Original: ".substr($numPresupuesto, 17),0,1,'L',false);
			$altura += 5;*/
		}
		else
		{
			$pdf->SetXY($margen,$altura);		
			$pdf->Cell(0,5,"OT: ".$numPresupuesto,0,1,'L',false);
		}
		
		
		
		$margen += 30;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,5,utf8_decode($eltitulo),0,'L',false);
		//$pdf->MultiCell(0,5,utf8_decode("Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021"."aaaaa  aaaa "),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($eltitulo));
		$numeroDeFilas = ceil ($anchoDescripcion / (180-30));
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		
		
		
		$margen = $margenInicial;
		$altura += 10;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"CONCEPTOS",0,1,'L',false);
		
		//DETALLES
		$pdf->SetFont('Arial','B',8);


		$mostrarPrecio = $detallada;

		$mostrarPrecio = 1;
		//$altura += 5;
		$margen = 180;


		if ($mostrarPrecio==0)
		{		
			$pdf->SetXY($margen-10,$altura);
			$pdf->Cell(20,5,"UNIDADES",0,0,'R',false);
		}
		else
		{	
			$pdf->SetXY($margen-50,$altura);
			$pdf->Cell(20,5,"UNIDADES",0,0,'R',false);

			$pdf->SetXY($margen-30,$altura);
			$pdf->Cell(20,5,"PRECIO",0,0,'R',false);

			$pdf->SetXY($margen-10,$altura);
			$pdf->Cell(20,5,"TOTAL",0,0,'R',false);
		}



		
		$altura +=7;
		$contador=1;
		$margen = $margenInicial;

		//$contadorConceptos=0;
		foreach ($datosDetalles as $row) 
		{
			//$contador++;
			$pdf->SetFont('Arial','',9);

			//$pdf->Cell(125-5,5,$row["proceso"],1,0,'L',false);

			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosCliente,$retener,$margenInicial,$clayma);
			}


			if ($contador%2==0)
			{

				$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
			}
			else
			{
				$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);
			}

			//$alturaConcepto=0;
			$alturaConcepto=$altura;

			$ladescripion="";
			if ($row["descripcion"]!=""&&$row["descripcion"]!=null)
			{
				/*$ladescripion=" (".$row["descripcion"].")";	
				
				$ladescripion = str_replace('€',EURO,$ladescripion);
				$ladescripion = str_replace('ñ',ene,$ladescripion);
				$ladescripion = str_replace('Ñ',ene_may,$ladescripion);
				$ladescripion = str_replace('á',a_acento,$ladescripion);
				$ladescripion = str_replace('é',e_acento,$ladescripion);
				$ladescripion = str_replace('í',i_acento,$ladescripion);
				$ladescripion = str_replace('ó',o_acento,$ladescripion);
				$ladescripion = str_replace('ú',u_acento,$ladescripion);
				$ladescripion = str_replace('Á',a_acento_may,$ladescripion);
				$ladescripion = str_replace('É',e_acento_may,$ladescripion);
				$ladescripion = str_replace('Í',i_acento_may,$ladescripion);
				$ladescripion = str_replace('Ó',o_acento_may,$ladescripion);
				$ladescripion = str_replace('Ú',u_acento_may,$ladescripion);
				$ladescripion = str_replace('º',signo_grado,$ladescripion);
				$ladescripion = str_replace('ª',signo_ordinal,$ladescripion);	*/			
				
				$ladescripion=" (".$row["descripcion"].")";	
				//$ladescripion = reemplazarSimbolos($ladescripion);
				
	
			}
			
			/*$elConcepto = str_replace('€',EURO,$row["concepto"]);
			$elConcepto = str_replace('ñ',ene,$elConcepto);
			$elConcepto = str_replace('Ñ',ene_may,$elConcepto);
			$elConcepto = str_replace('á',a_acento,$elConcepto);
			$elConcepto = str_replace('é',e_acento,$elConcepto);
			$elConcepto = str_replace('í',i_acento,$elConcepto);
			$elConcepto = str_replace('ó',o_acento,$elConcepto);
			$elConcepto = str_replace('ú',u_acento,$elConcepto);
			$elConcepto = str_replace('Á',a_acento_may,$elConcepto);
			$elConcepto = str_replace('É',e_acento_may,$elConcepto);
			$elConcepto = str_replace('Í',i_acento_may,$elConcepto);
			$elConcepto = str_replace('Ó',o_acento_may,$elConcepto);
			$elConcepto = str_replace('Ú',u_acento_may,$elConcepto);
			$elConcepto = str_replace('º',signo_grado,$elConcepto);
			$elConcepto = str_replace('ª',signo_ordinal,$elConcepto);*/
			
			//$elConcepto = reemplazarSimbolos($row["concepto"]);
			$elConcepto = $row["concepto"];
			//$ladescripion = $ladescripion." altura: ".$altura;

			//$ladescripion = utf8_decode($elConcepto.$ladescripion);
			
			$ladescripcion = mb_convert_encoding($elConcepto.$ladescripion, 'ISO-8859-1', 'UTF-8');

			if (isset($row["tipoIva"]))
			{
				if ($row["tipoIva"]==0)
				{
					$ladescripcion .= " (Exento de IVA)";
				}
				else if ($row["tipoIva"]!=21)
				{
					$ladescripcion .= " (IVA ".$row["tipoIva"]."%)";
				}
			}

			$pdf->SetXY($margen,$altura);		
			//$pdf->MultiCell(110,5,utf8_decode($row["concepto"].$ladescripion),0,'L',true);
			$pdf->MultiCell(110,5,($ladescripcion),0,'L',true);
//var_dump(mb_detect_encoding($elConcepto.$ladescripcion, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));

			$anchoDescripcion = $pdf->GetStringWidth(mb_convert_encoding($ladescripcion, 'ISO-8859-1', 'UTF-8'));
			$numeroDeFilas = ceil ($anchoDescripcion / (109));
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);
			$altura = $altura - 5;

			//$pdf->SetXY($margen-5,$altura);
			//$pdf->MultiCell(125-5,5,ceil ($anchoDescripcion / (119)),0,'L',false);

			$unidad=0.000;
			$precio=0.00;
			$total="";


			$unidad = number_format($row["unidades"],3,',','.');

			if ($unidad=="null" || $unidad==null || $unidad==="0,000" )
			{
				$unidad="";
			}
			
			
			/*if ($unidad===0)
			{
				$unidad = "";
			}*/
			else if ($row["concepto"]=='Manipulado de Productos No Bonificables')
			{
				$unidad = number_format($row["unidades"],0,',','.')." %";
			}

			$precio = number_format($row["precio"],2,',','.');

			if ($precio === "0,00")
			{
				$precio = "";
			}
			else
			{
				$precio .= " ".EURO;
			}
			
			if ($elConcepto=="Manipulado de Productos No Bonificables" && ($unidad=="null" || $unidad==null || $unidad==="0,000"))
			{
				$precio="";
			}

			//$total = $row["unidades"] * $row["precio"];

			$total = number_format($row["total"],2,',','.');
			$importeCombinadoPorOt = $importeCombinadoPorOt + $row["total"]; 

			//echo ("<br>hola".$total);         



			if ($total==="0,00")
			{
				$total="";
			}
			else
			{
				$total .= " ".EURO;
			}	
			//else
			{
				//$total .= " ".EURO;
				$margen = 180;

				$pdf->SetFont('Arial','',8);
				if ($mostrarPrecio==0)
				{		
					$pdf->SetXY($margen-50,$alturaConcepto);
					$pdf->Cell(60,5*$numeroDeFilas,$unidad,0,0,'R',true);
				}
				else
				{			
					$pdf->SetXY($margen-50,$alturaConcepto);
					$pdf->Cell(20,5*$numeroDeFilas,$unidad,0,0,'R',true);

					$pdf->SetXY($margen-30,$alturaConcepto);
					$pdf->Cell(20,5*$numeroDeFilas,$precio,0,0,'R',true);			


					$pdf->SetXY($margen-10,$alturaConcepto);			
					$pdf->Cell(20,5*$numeroDeFilas,$total,0,0,'R',true);			
				}



				$margen = $margenInicial;
				$altura = $altura + 5;
				$contador++;
			}




			if ($altura>265)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosCliente,$retener,$margenInicial,$clayma);	
			}

		}
		
		////////////////////////////////
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);		

		$pdf->Cell(0,5,"SubTotal: ".number_format($importeCombinadoPorOt,2,',','.')." ".EURO,0,1,'R',false);

		$importeCombinadoPorOt=0.00;
		$altura += 5;
		/////////////////////////////////
		
	
		$contadorGenerico++;
	
	}
	if ($altura>230)
	{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosCliente,$retener,$margenInicial,$clayma);
	}
	
	
	//PIE DE PAGINA
	
	$altura = 235;

	$margen=$margenInicial;
	if ($clayma)
	{
		$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	}
	else
	{
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	}
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	
	$altura = 240;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(20,$altura);	
	$pdf->Cell(50,5,"FORMA DE PAGO:",0,0,'L',false);
	$altura += 8;
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY(20,$altura);
	
	$pdf->MultiCell(95,5,utf8_decode($formaPago),0,'L',false);
	
	
	//$cuentaBancaria = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br>", $cuentaBancaria);
	//$cuentaBancaria = str_replace("\r", "<br>\n", $cuentaBancaria);
	$cuentaBancaria = str_replace(";", "\n", $cuentaBancaria);
		
		
	//$cuentaBancaria = $datosFactura[0]["cuentaDelBanco"];
	
	
	//$cuentaBancaria = $datosFactura[0]["cuentaDelBanco"];
	$altura += 10;
	$pdf->SetXY(20,$altura);
	$pdf->MultiCell(95,5,utf8_decode($cuentaBancaria),0,'L',false);
	
	
	$imprimirIRPF=false;
	if  ($irpf!=0.00 && $irpf!="0.00" && $irpf != "" && $irpf != "NULL" )
	{
		$imprimirIRPF = true;
	}

	$numLineasIva = count($desgloseIva)>0 ? count($desgloseIva) : 1;
	$totalLineas = 2 + $numLineasIva + ($imprimirIRPF ? 1 : 0); // Base + IVA(s) + IRPF? + Total

	$espaciado = 7;
	$fuenteCaja = 10;
	$alturaCelda = 5;
	$offsetInicial = ($totalLineas <= 3) ? 4 : 0;

	if ($totalLineas > 4)
	{
		$alturaCelda = 4;
		$offsetInicial = 2;
		$espaciado = (27 - $offsetInicial - $alturaCelda) / ($totalLineas - 1);
		$fuenteCaja = max(6, 10 - ($totalLineas - 4));
	}

	$altura = 240;
	
	$ancho = $pdf->GetPageWidth()-20-70;
	
	if ($clayma)
	{
		$pdf->SetFillColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	}
	else
	{
		$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
	}
	$pdf->Rect($ancho, $altura, 70, 27,'F');
	
	
	$pdf->SetFont('Arial','B',$fuenteCaja);
	$pdf->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);
	
	
	$altura += $offsetInicial;

	$ancho+=10;
	
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,$alturaCelda,"Base Imponible:",0,0,'R',false);
	
	
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,$alturaCelda,number_format($precioNeto,2,',','.')." ".EURO,0,0,'R',false);	
	
	if (count($desgloseIva)>0)
	{
		foreach ($desgloseIva as $tipoIvaLinea => $importeIvaLinea)
		{
			$altura += $espaciado;
			$pdf->SetXY($ancho,$altura);			
			$pdf->Cell(20,$alturaCelda,"IVA ".$tipoIvaLinea."%:",0,0,'R',false);	
			
			$pdf->SetXY($ancho+35,$altura);	
			$pdf->Cell(20,$alturaCelda,number_format($importeIvaLinea,2,',','.')." ".EURO,0,0,'R',false);
		}
	}
	else
	{
		$altura += $espaciado;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,$alturaCelda,"IVA 21%:",0,0,'R',false);	
		
		$pdf->SetXY($ancho+35,$altura);	
		$pdf->Cell(20,$alturaCelda,number_format($iva,2,',','.')." ".EURO,0,0,'R',false);
	}
	
	if  ($imprimirIRPF == true)
	{
		$altura += $espaciado;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,$alturaCelda,"IRPF 19%:",0,0,'R',false);	
		
		$pdf->SetXY($ancho+35,$altura);	
		$pdf->Cell(20,$alturaCelda,number_format($irpf,2,',','.')." ".EURO,0,0,'R',false);
	}
	
	$altura += $espaciado;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,$alturaCelda,"TOTAL:",0,0,'R',false);
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,$alturaCelda,number_format($precioTotal,2,',','.')." ".EURO,0,0,'R',false);	
	
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	
	if (floatval($provision)>0)
	{
		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Provision de Fondo:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($provision,2,',','.')." ".EURO,0,0,'R',false);	

		$altura += 5;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Total a Pagar:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($aPagar,2,',','.')." ".EURO,0,0,'R',false);	
	}
		
		//$contadorMax++;
	

		
		
	
	$pdf->Output("I","Factura - ".$numFactura."-".date("dmy")." .pdf","UTF-8");
	
	sqlsrv_close($conn);
	
}
	
	


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosCliente,$retener,$margenInicial,$clayma=false)
{
	$pdf->AddPage();
	
	$pdf->SetTextColor(colorPrevisualizacionR,colorPrevisualizacionG,colorPrevisualizacionB);
	$pdf->SetFont('Arial','B',50);
	
	
	$pdf->SetXY(40,10);
	$pdf->Cell(0,0,utf8_decode("BORRADOR"),0,1,'C',false);
	
	
	$pdf->SetXY(0,95);
	$pdf->Cell(0,0,utf8_decode("BORRADOR"),0,1,'C',false);
	
		
	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(0,0,0);
	
	
	if ($clayma)
	{
		$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	}
	else
	{
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	}
	$pdf->SetLineWidth(0.8);
	//$altura = $altura + 0;
	$altura = 10;
	$margen = 20;
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,utf8_decode("BORRADOR"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,$datosNuevaPagina1,1,1,'C',false);	
	
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,$datosNuevaPagina2,0,1,'C',false);
	
	$margen = $margenInicial;
	$altura = 35;
	
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"BORRADOR",0,1,'L',false);
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);	
	
	
	$pdf->Cell(0,5,"Cod. Cliente: ".$datosCliente[0]["codigo_saldo"].$retener,0,1,'R',false);
	
	
	$altura = $altura + 5;
	if ($clayma)
	{
		$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	}
	else
	{
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	}
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$altura = $altura + 5;
	$alturaDireccion = $altura;
	$pdf->SetFont('Arial','U',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION FISCAL",0,1,'L',false);
	
	
	$pdf->SetFont('Arial','B',8);
	$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(84,4,utf8_decode($datosCliente[0]["nombre_empresa"]),0,'L',false);
	
	$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["nombre_empresa"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(84,4,utf8_decode($datosCliente[0]["direccion"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["direccion"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["codigo_postal"]." - ".$datosCliente[0]["localidad"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["codigo_postal"]." - ".$datosCliente[0]["localidad"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["provincia"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["provincia"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["nif_subcliente"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["nif_subcliente"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	$alturaMax=$altura;
	
	
	$pdf->SetFont('Arial','U',10);
	$altura = $alturaDireccion;
	$margen = $pdf->GetPageWidth()/2;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION ENVIO",0,1,'L',false);
	
	$pdf->SetFont('Arial','B',8);
	$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
		
	
	if($datosCliente[0]["envio_domicilio"]=="" && $datosCliente[0]["envio_cp"]=="" && $datosCliente[0]["envio_poblacion"]=="" && $datosCliente[0]["envio_provincia"]=="")
	{
		$pdf->MultiCell(84,4,utf8_decode($datosCliente[0]["nombre_empresa"]),0,'L',false);
	
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["nombre_empresa"]);
		if ($anchoDescripcion>0)
		{
			$numeroDeFilas = ceil ($anchoDescripcion / 84);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);
		}

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(84,4,utf8_decode($datosCliente[0]["direccion"]),0,'L',false);

		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["direccion"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);



		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["codigo_postal"]." - ".$datosCliente[0]["localidad"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["codigo_postal"]." - ".$datosCliente[0]["localidad"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["provincia"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["provincia"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		

		
	}
	else
	{
		$pdf->MultiCell(84,4,utf8_decode($datosCliente[0]["envio_att"]),0,'L',false);
	
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["envio_att"]);
		if ($anchoDescripcion>0)
		{
			$numeroDeFilas = ceil ($anchoDescripcion / 84);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);
		}

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(84,4,utf8_decode($datosCliente[0]["envio_nombre"]),0,'L',false);

		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["envio_nombre"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);



		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["envio_domicilio"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["envio_domicilio"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["envio_cp"]." - ".$datosCliente[0]["envio_poblacion"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["envio_cp"]." - ".$datosCliente[0]["envio_poblacion"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["envio_provincia"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["envio_provincia"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($datosCliente[0]["envio_pais"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($datosCliente[0]["envio_pais"]);
		if ($anchoDescripcion>0)
		{
			$numeroDeFilas = ceil ($anchoDescripcion / 84);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);	
		}
	}
	
	if ($alturaMax>$altura)
	{
		$altura = $alturaMax;
	}
	
	$margen=$margenInicial;
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	
	
	//$altura = $alturaSiguientePagina;
	$margen = 180;
	
	$altura=$altura+5;
}

?>
	
	
	
	
		


