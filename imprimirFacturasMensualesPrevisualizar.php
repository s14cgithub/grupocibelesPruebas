<?php
session_start();
require("comprobarSesion.php");

if(isset($_POST["previsualizarAccion"]) && $_POST["previsualizarAccion"]=="previsualizarFacturasMensuales")
{
	$ruta = '/';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFacturaGroupPag.php");

	require 'phpqrcode/qrlib.php';

	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	$fechaFac = $_POST["fechaFac"];

	$fecha = new DateTime($fechaFin);

	$fecha2 = new DateTime($fechaFin);
	$fecha2->modify('last day of this month');
	$fechaFinCampana = $fecha2->format('d-m-Y');

	$fecha->modify('first day of next month');
	$fechaFin = $fecha->format('d-m-Y');

	$camposFacturasMensuales = array('codigo','codigo_saldo','nombre_empresa','prefactura','conceptos','fac_cuotaRecogida','importe','fac_porCientoNoBonificable','fac_importeFijoOtrosConcepto','envios','fac_cobroUnitarioEnvio','sinIva','pedidoCliente','formaPago','nuestraCuenta','fac_otrosConceptosFijos');
	$ordenFacturasMensuales = array(array('campo'=>'nombre_empresa','dir'=>'ASC'));
	$anioTarifasFacturasMensuales = date('Y', strtotime($fechaFac));

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultadoFacturasMensuales = cargarDatosFacturasMensuales($conn, $bbddSql, $camposFacturasMensuales, $fechaInicio, $fechaFin, $anioTarifasFacturasMensuales, $ordenFacturasMensuales);
	$datosFacturas = $resultadoFacturasMensuales['datos'];

	$pdf = new cabeceraFactura('P','mm','A4');
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);

	$margenInicial = 20;
	$limiteAlturaDatos = 260;

	$avisosImporteCero = array();

	foreach ($datosFacturas as $valor)
	{
		$datosCalculo = calcularDatosFacturaMensualCliente($conn, $bbddSql, $valor, $fechaInicio, $fechaFin, $fechaFac, $fechaFinCampana);

		$cab = $datosCalculo['datosFacturaCabecera'];
		$lineasDetalle = $datosCalculo['lineasDetalle'];

		if (floatval($cab['precioNeto'])==0)
		{
			$avisosImporteCero[] = $datosCalculo['codigoCliente']." - ".$datosCalculo['nombreCliente'];
		}

		$pdf->StartPageGroup();

		//DESGLOSE IVA (misma logica que imprimirFactura.php/previsualizarFactura.php)
		$desgloseIva = array();
		$hayTipoIva = false;
		foreach ($lineasDetalle as $rowDesglose)
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

		$retener = ($cab['retener']==1) ? " -  R" : "";

		$altura = 10;
		nuevaPaginaPrevisualizacionMensual($pdf, $altura, $cab, $retener, $margenInicial);

		$margen = $margenInicial;
		if ($cab['pedido']!="" && $cab['pedido']!="NINGUNO")
		{
			$altura += 5;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,"PEDIDO:  ".reemplazarSimbolos($cab['pedido']),0,1,'L',false);
		}

		if ($cab['cantidad']!="" && $cab['cantidad']!="0")
		{
			$altura += 10;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,"Cantidad: ".number_format($cab['cantidad'],0,',','.'),0,0,'L',false);
		}

		if ($altura>190)
		{
			nuevaPaginaPrevisualizacionMensual($pdf, $altura, $cab, $retener, $margenInicial);
		}

		$altura += 5;
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"OT: ".reemplazarSimbolos($cab['presupuesto']),0,1,'L',false);

		$margen += 30;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,5,reemplazarSimbolos($cab['descripcion']),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['descripcion']));
		$numeroDeFilas = ceil($anchoDescripcion / (180-30));
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

		$pdf->SetFont('Arial','B',8);
		$margen = 180;
		$pdf->SetXY($margen-50,$altura);
		$pdf->Cell(20,5,"UNIDADES",0,0,'R',false);
		$pdf->SetXY($margen-30,$altura);
		$pdf->Cell(20,5,"PRECIO",0,0,'R',false);
		$pdf->SetXY($margen-10,$altura);
		$pdf->Cell(20,5,"TOTAL",0,0,'R',false);

		$altura += 7;
		$contador = 1;
		$margen = $margenInicial;

		foreach ($lineasDetalle as $row)
		{
			$pdf->SetFont('Arial','',9);

			if ($altura>$limiteAlturaDatos)
			{
				nuevaPaginaPrevisualizacionMensual($pdf, $altura, $cab, $retener, $margenInicial);
			}

			if ($contador%2==0)
			{
				$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
			}
			else
			{
				$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);
			}

			$alturaConcepto = $altura;

			$elConcepto = $row["concepto"];
			if ($elConcepto!="" and $elConcepto!=" " and $elConcepto!="  ")
			{
				$elConcepto = $elConcepto.". ";
			}

			$datosDecripcion = reemplazarSimbolos($elConcepto);

			if (isset($row["tipoIva"]))
			{
				if ($row["tipoIva"]==0)
				{
					$datosDecripcion .= " (Exento de IVA)";
				}
				else if ($row["tipoIva"]!=21)
				{
					$datosDecripcion .= " (IVA ".$row["tipoIva"]."%)";
				}
			}

			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(110,5,($datosDecripcion),0,'L',true);

			$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($datosDecripcion));
			$numeroDeFilas = ceil($anchoDescripcion / 109);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}
			$altura = $altura + (5*$numeroDeFilas);
			$altura = $altura - 5;

			$unidad = number_format($row["unidades"],3,',','.');
			if ($unidad=="null" || $unidad==null || $unidad==="0,000")
			{
				$unidad = "";
			}
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

			if ($row["concepto"]=="Manipulado de Productos No Bonificables" && ($unidad=="null" || $unidad==null || $unidad==="0,000"))
			{
				$precio = "";
			}

			$total = number_format($row["total"],2,',','.');
			if ($total==="0,00")
			{
				$total = "";
			}
			else
			{
				$total .= " ".EURO;
			}

			$margen = 180;
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY($margen-50,$alturaConcepto);
			$pdf->Cell(20,5*$numeroDeFilas,$unidad,0,0,'R',true);

			$pdf->SetXY($margen-30,$alturaConcepto);
			$pdf->Cell(20,5*$numeroDeFilas,$precio,0,0,'R',true);

			$pdf->SetXY($margen-10,$alturaConcepto);
			$pdf->Cell(20,5*$numeroDeFilas,$total,0,0,'R',true);

			$margen = $margenInicial;
			$altura = $altura + 5;
			$contador++;

			if ($altura>265)
			{
				nuevaPaginaPrevisualizacionMensual($pdf, $altura, $cab, $retener, $margenInicial);
			}
		}

		if ($altura>230)
		{
			nuevaPaginaPrevisualizacionMensual($pdf, $altura, $cab, $retener, $margenInicial);
		}

		//PIE DE PAGINA (solo en la ultima pagina de este cliente)
		$altura = 235;
		$margen = $margenInicial;
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.8);
		$pdf->Line($margen, $altura, 190, $altura);

		$altura = 240;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY(20,$altura);
		$pdf->Cell(50,5,"FORMA DE PAGO:",0,0,'L',false);
		$altura += 8;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(20,$altura);
		$pdf->MultiCell(95,5,reemplazarSimbolos($cab['formaPago']),0,'L',false);

		$pdf->SetFont('Arial','',6);
		$cuentaBancaria = str_replace(";", "\n", $cab['numCuentaBanco']);
		$altura += 10;
		$pdf->SetXY(20,$altura);
		$pdf->MultiCell(95,5,reemplazarSimbolos($cuentaBancaria),0,'L',false);

		$numLineasIva = count($desgloseIva)>0 ? count($desgloseIva) : 1;
		$totalLineas = 2 + $numLineasIva; // Base + IVA(s) + Total

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
		$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->Rect($ancho, $altura, 70, 27,'F');

		$pdf->SetFont('Arial','B',$fuenteCaja);
		$pdf->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);

		$altura += $offsetInicial;
		$ancho += 10;

		$pdf->SetXY($ancho,$altura);
		$pdf->Cell(20,$alturaCelda,"Base Imponible:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);
		$pdf->Cell(20,$alturaCelda,number_format($cab['precioNeto'],2,',','.')." ".EURO,0,0,'R',false);

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
			$pdf->Cell(20,$alturaCelda,number_format($cab['iva'],2,',','.')." ".EURO,0,0,'R',false);
		}

		$altura += $espaciado;
		$pdf->SetXY($ancho,$altura);
		$pdf->Cell(20,$alturaCelda,"TOTAL:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);
		$pdf->Cell(20,$alturaCelda,number_format($cab['precioTotal'],2,',','.')." ".EURO,0,0,'R',false);

		$pdf->SetFont('Arial','',8);
		$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);

		//QR "borrador" (igual que previsualizarFactura.php)
		$paramsQr = [
			"nif" => cifCibeles,
			"numserie" => "borrador",
			"fecha" => "",
			"importe" => "0"
		];

		$verifactu_qrDataUrl = "borrador" . http_build_query($paramsQr);

		$tmpFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
		QRcode::png($verifactu_qrDataUrl, $tmpFile, QR_ECLEVEL_L, 4);

		$pdf->Image($tmpFile, 85, 238, 30, 30, 'PNG');
		unlink($tmpFile);
	}

	sqlsrv_close($conn);

	if (!empty($avisosImporteCero))
	{
		$pdfContenido = $pdf->Output("S","Previsualizacion Facturas Mensuales - ".date("dmy").".pdf","UTF-8");
		$pdfBase64 = base64_encode($pdfContenido);
		$mensajeAlerta = "AVISO: los siguientes clientes tienen importe 0 (Base Imponible):\n".implode("\n", $avisosImporteCero);

		echo "<script>alert(".json_encode($mensajeAlerta).");</script>";
		echo '<embed src="data:application/pdf;base64,'.$pdfBase64.'" type="application/pdf" width="100%" height="900px">';
	}
	else
	{
		$pdf->Output("I","Previsualizacion Facturas Mensuales - ".date("dmy").".pdf","UTF-8");
	}
}

function nuevaPaginaPrevisualizacionMensual(&$pdf, &$altura, $cab, $retener, $margenInicial)
{
	$pdf->AddPage();

	$pdf->SetTextColor(colorPrevisualizacionR,colorPrevisualizacionG,colorPrevisualizacionB);
	$pdf->SetFont('Arial','B',50);

	$pdf->SetXY(40,10);
	$pdf->Cell(0,0,reemplazarSimbolos("BORRADOR"),0,1,'C',false);

	$pdf->SetXY(0,95);
	$pdf->Cell(0,0,reemplazarSimbolos("BORRADOR"),0,1,'C',false);

	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(0,0,0);

	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = 5;
	$margen = 20;
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,reemplazarSimbolos("BORRADOR"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,'',1,1,'C',false);

	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,"Fecha: ".$cab['fecha'],0,1,'C',false);

	$margen = $margenInicial;
	$altura = 35;

	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"FACTURA",0,1,'L',false);

	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"Cod. Cliente: ".$cab['idCodigoCliente'].$retener,0,1,'R',false);

	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
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
	$pdf->MultiCell(84,4,reemplazarSimbolos($cab['dirPost_nombreEmpresa']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirPost_nombreEmpresa']));
	$numeroDeFilas = ceil($anchoDescripcion / 80);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(84,4,reemplazarSimbolos($cab['dirPost_direccion']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirPost_direccion']));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirPost_cp']." - ".$cab['dirPost_poblacion']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirPost_cp']." - ".$cab['dirPost_poblacion']));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirPost_provincia']." (".$cab['dirPost_pais'].")"),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirPost_provincia']." (".$cab['dirPost_pais'].")"));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirPost_Nif']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirPost_Nif']));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);
	$alturaMax = $altura;

	$pdf->SetFont('Arial','U',10);
	$altura = $alturaDireccion;
	$margen = $pdf->GetPageWidth()/2;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION ENVIO",0,1,'L',false);

	$pdf->SetFont('Arial','B',8);
	$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);

	$pdf->MultiCell(84,4,reemplazarSimbolos($cab['dirEnv_att']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirEnv_att']));
	if ($anchoDescripcion>0)
	{
		$numeroDeFilas = ceil($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}
		$altura = $altura + (5*$numeroDeFilas);
	}

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(84,4,reemplazarSimbolos($cab['dirEnv_nombreEmpresa']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirEnv_nombreEmpresa']));
	$numeroDeFilas = ceil($anchoDescripcion / 80);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirEnv_direccion']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirEnv_direccion']));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirEnv_cp']." - ".$cab['dirEnv_poblacion']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirEnv_cp']." - ".$cab['dirEnv_poblacion']));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirEnv_provincia']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirEnv_provincia']));
	$numeroDeFilas = ceil($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,reemplazarSimbolos($cab['dirEnv_pais']),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($cab['dirEnv_pais']));
	if ($anchoDescripcion>0)
	{
		$numeroDeFilas = ceil($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}
		$altura = $altura + (5*$numeroDeFilas);
	}

	if ($alturaMax>$altura)
	{
		$altura = $alturaMax;
	}

	$margen = $margenInicial;
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);

	$altura = $altura + 5;
}

?>
