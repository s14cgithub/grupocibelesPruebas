<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirMensuales")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFactura2.php");
	
	require_once('PHPMailer/enviarFactura.php');
		
	
	$numFacturaInicio = $_POST["numFacInicio"];	
	$numFacturaFin = $_POST["numFacFin"];
	
	
	$datosFactura = verRangoFacturasPorNumeroYCdSinRetener($conexion,$numFacturaInicio, $numFacturaFin);
	
	
	
	
	$pdf = new cabeceraFactura('P','mm','A4');

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	
	$margenInicial=20;
	$margen=$margenInicial;
	$altura=10;	
	
	//$contador=0;
	
	$registrosEmail="";
	$registrosUtilizados = array();
	foreach ($datosFactura as $valor)
	{
		//$contador++;
		$numFactura = $valor["numero"];
		
		$datosDetalles = verFacturaDetalle($conexion,$numFactura);
		
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);
		
		
		$margen=$margenInicial;
		
		
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.8);
		$altura=10;	

		$margen += 135;
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
		$altura = $altura + 5;
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(35,6,$numFactura."/". $valor["fecha"]->format('y'),1,1,'C',false);


		$altura = $altura + 6;
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(35,5,"OT ".$valor["presupuesto"],1,1,'C',false);	
		
		
		$pdf->SetFont('Arial','',10);
		$altura = $altura + 8;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(35,5,"Fecha: ". $valor["fecha"]->format('d/m/Y'),0,1,'C',false);

		$margen = $margenInicial;

		$altura = $altura + 15;

		$pdf->SetFont('Arial','B',18);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,"FACTURA",0,1,'L',false);

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		
		
		$datosCliente =  cargarClientes($conexion," where codigo_saldo=".$valor["codigo"]);
	
		$retener = "";
		if ($datosCliente[0]["retener"]==1)
		{
			$retener = " -  R";
		}

		$datosCliente=null;

		$pdf->Cell(0,5,"Cod. Cliente: ". $valor["codigo"].$retener,0,1,'R',false);
		
		
		
		//$pdf->Cell(0,5,"Cod. Cliente: ". $valor["codigo"],0,1,'R',false);

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
		$pdf->MultiCell(84,4,utf8_decode( $valor["nombre_empresa"]),0,'L',false);

		$anchoDescripcion = $pdf->GetStringWidth( $valor["nombre_empresa"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);


		$pdf->SetXY($margen,$altura);		
		$pdf->MultiCell(0,4,utf8_decode( $valor["direccion"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth( $valor["direccion"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);


		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode( $valor["codigo_postal"]." - ". $valor["localidad"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth( $valor["codigo_postal"]." - ". $valor["localidad"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode( $valor["provincia"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth( $valor["provincia"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode( $valor["nif_subcliente"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth( $valor["nif_subcliente"]);
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
		$pdf->MultiCell(84,4,utf8_decode( $valor["envio_att"]),0,'L',false);

		$anchoDescripcion = $pdf->GetStringWidth($valor["envio_att"]);
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
		$pdf->MultiCell(84,4,utf8_decode($valor["envio_nombre"]),0,'L',false);

		$anchoDescripcion = $pdf->GetStringWidth($valor["envio_nombre"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);



		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($valor["envio_domicilio"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($valor["envio_domicilio"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($valor["envio_cp"]." - ".$valor["envio_poblacion"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($valor["envio_cp"]." - ".$valor["envio_poblacion"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($valor["envio_provincia"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($valor["envio_provincia"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($valor["envio_pais"]),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth($valor["envio_pais"]);
		if ($anchoDescripcion>0)
		{
			$numeroDeFilas = ceil ($anchoDescripcion / 84);
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

		$margen=$margenInicial;
		$pdf->SetLineWidth(0.8);
		$pdf->Line($margen, $altura, 190, $altura);

		$altura += 10;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"DESCRIPCION:  ",0,1,'L',false);

		/*$altura += 10;
		$margen += 10;
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($margen,$altura);


		$laFechaInicio="";
		$laFechaFin="";

		if ($valor["fechaInicio"]!="" && $valor["fechaInicio"]!=null && $valor["fechaInicio"]!="null")
		{
			$laFechaInicio = $valor["fechaInicio"]->format('d/m/Y');
		}

		if ($valor["fechaFin"]!="" && $valor["fechaFin"]!=null && $valor["fechaFin"]!="null")
		{
			$laFechaFin = $valor["fechaFin"]->format('d/m/Y');
		}

		$pdf->Cell(0,5,utf8_decode("Manipulación y envio de su Correspondencia diario del"),0,1,'L',false);

		$margen = 110;
		$pdf->SetFont('Arial','B',9);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,utf8_decode($laFechaInicio),0,1,'L',false);

		$margen = 130;
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(0,5,utf8_decode("al"),0,1,'L',false);

		$margen = 135;
		$pdf->SetFont('Arial','B',9);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,utf8_decode($laFechaFin),0,1,'L',false);*/


		$margen += 30;
	
		$pdf->SetXY($margen,$altura);	
		$pdf->SetFont('Arial','B',10);	
		$eltitulo=$valor["descripcion"];
		//$eltitulo = "Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021";
		$pdf->MultiCell(0,5,utf8_decode($eltitulo),0,'L',false);
		//$pdf->MultiCell(0,5,utf8_decode("Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021"."aaaaa  aaaa "),0,'L',false);
		$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($eltitulo));
		$numeroDeFilas = ceil ($anchoDescripcion / (180-30));
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);
	
		////////////////////

		if ($valor["pedido"]!="")
		{
			$margen=$margenInicial;
			$altura += 5;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,"PEDIDO:  ".utf8_decode($valor["pedido"]),0,1,'L',false);
		}
		
		
		
		////////////////////////////////////////////
		

		$altura += 10;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"CONCEPTOS",0,1,'L',false);

		///////////////////////////////
		//DETALLES
		$pdf->SetFont('Arial','B',9);

		$mostrarPrecio = $valor["detallada"];
	
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
		
		
		$limiteAlturaDatos = 280;
		//$alturaSiguientePagina = 50;
		$altura +=7;
		$contador=1;
		$margen = $margenInicial;

		//$contadorConceptos=0;
		foreach ($datosDetalles as $row) 
		{
			//$contador++;
			$pdf->SetFont('Arial','',9);

			//$pdf->Cell(125-5,5,$row["proceso"],1,0,'L',false);

			/*if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}*/


			if ($contador%2==0)
			{
				$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
			}
			else
			{
				$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);
			}
			
			$alturaConcepto=$altura;

			$ladescripion="";
			if ($row["descripcion"]!=""&&$row["descripcion"]!=null)
			{
				$ladescripion=" (".$row["descripcion"].")";			
			}



			$pdf->SetXY($margen,$altura);		
			$pdf->MultiCell(110,5,utf8_decode($row["concepto"].$ladescripion),0,'L',true);


			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["concepto"].$ladescripion));
			$numeroDeFilas = ceil ($anchoDescripcion / (109));
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);
			$altura = $altura - 5;

			

			$unidad="";
			$precio="";
			$total="";


			$unidad = number_format($row["unidades"],3,',','.');

			if ($unidad===0)
			{
				$unidad = "";
			}
			else if ($row["concepto"]=='Manipulado de Productos No Bonificables')
			{
				$unidad = number_format($row["unidades"],0,',','.')." %";
			}

			$precio = number_format($row["precio"],2,',','.');

			if ($precio===0.00)
			{
				$precio = "";
			}
			else
			{
				$precio .= " ".EURO;
			}

			//$total = $row["unidades"] * $row["precio"];

			$total = number_format($row["total"],2,',','.');

			//echo ("<br>hola".$total);         



			if ($total===0)
			{
				$total = "";
			}
			else
			{
				$total .= " ".EURO;
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




			/*if ($altura>265)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}*/

		}

		

		//PIE DE PAGINA

		$altura = 235;

		$margen=$margenInicial;
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

		$pdf->MultiCell(95,5,utf8_decode($valor["formaPago"]),0,'L',false);


		//$cuentaBancaria = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br>", $cuentaBancaria);
		//$cuentaBancaria = str_replace("\r", "<br>\n", $cuentaBancaria);

		$cuentaBancaria = str_replace(";", "\n", $valor["cuentaDelBanco"]);
		$altura += 10;
		$pdf->SetXY(20,$altura);
		$pdf->MultiCell(95,5,utf8_decode($cuentaBancaria),0,'L',false);

		$altura = 240;

		$ancho = $pdf->GetPageWidth()-20-70;
		//$altura = $altura + 25;
		$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->Rect($ancho, $altura, 70, 20,'F');


		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);


		$ancho+=10;

		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Base Imponible:",0,0,'R',false);



		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($valor["precioNeto"],2,',','.')." ".EURO,0,0,'R',false);	

		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"IVA 21%:",0,0,'R',false);	

		$pdf->SetXY($ancho+35,$altura);	
		$pdf->Cell(20,5,number_format($valor["iva"],2,',','.')." ".EURO,0,0,'R',false);

		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"TOTAL:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($valor["precioTotal"],2,',','.')." ".EURO,0,0,'R',false);	


		$pdf->SetFont('Arial','',8);
		$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);

		$altura += 10;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Provision de Fondo:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($valor["provision"],2,',','.')." ".EURO,0,0,'R',false);	

		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Total a Pagar:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($valor["aPagar"],2,',','.')." ".EURO,0,0,'R',false);
		
		
		
		
		if ($valor["retener"]==0 && $valor["email"]!="")
		{
			//////////////////////////////			
			
			//////////////////////////////////////////
			//echo "<br>".$numFactura;
			
			$docFactura = duplicarPdf($numFactura,$valor,$datosDetalles);
			//envio de email
			//$pdfenvio=null;
			
			//$docFactura = $pdfEmail->Output("S","Factura -".date("dmy")." .pdf","UTF-8");
			
			//$docFactura = $pdfEnvio->Output("S","","UTF-8");
			//require_once('PHPMailer/enviarFactura.php');
			
			$mail->AddAddress($valor["email"], $valor["email"]);
			$mail->AddStringAttachment($docFactura, 'factura.pdf', 'base64', 'application/pdf'); 
			
			
		
			$nombreEmpresa = $valor["nombre_empresa"];
			
			$registrosEmail .= "\n".$nombreEmpresa." -- ".$valor["email"]." -- factura.pdf";
			
			
			//$rutaFacturasCorreos = '//172.26.0.44/d/Administracion/Compartida/facturasCorreos';
			$rutaFacturasCorreos = 'facturasCorreos';
			
			$nombresArchivos = scandir($rutaFacturasCorreos);
			
			
			
			
			for ($contadorRegistros=0 ; $contadorRegistros < count($nombresArchivos) ; $contadorRegistros++)
			{
				if (!in_array($contadorRegistros, $registrosUtilizados))
				{
					if ($nombresArchivos[$contadorRegistros]=='.' ||$nombresArchivos[$contadorRegistros]=='..'  )
					{
						$registrosUtilizados[] = $contadorRegistros;
					}
					else
					{
						$nombreLeido = substr($nombresArchivos[$contadorRegistros],0,strlen($nombresArchivos[$contadorRegistros])-26);
						if ($nombreLeido == $nombreEmpresa)
						{
							//$mail->Body .= "\n".substr($nomFile,0,strlen($nomFile)-26);
							$mail->addStringAttachment(file_get_contents($rutaFacturasCorreos."/".$nombresArchivos[$contadorRegistros]), $nombresArchivos[$contadorRegistros]);
							$registrosEmail .= "\n".$nombreEmpresa." -- ".$valor["email"]." -- ".$rutaFacturasCorreos."/".$nombresArchivos[$contadorRegistros];
						}
						$registrosUtilizados[] = $contadorRegistros;
					}
				}
				
			}
			
			
			
			/*foreach ($nombresArchivos as $nomFile)
			{				
				if ($nomFile!='.'&& $nomFile!='..')
				{
					$nombreLeido = substr($nomFile,0,strlen($nomFile)-26);
					
					//$mail->Body .= "\n".$nombreLeido;
					
					if ($nombreLeido == $nombreEmpresa)
					{
						//$mail->Body .= "\n".substr($nomFile,0,strlen($nomFile)-26);
						$mail->addStringAttachment(file_get_contents($rutaFacturasCorreos."/".$nomFile), $nomFile);
						$registrosEmail .= "\n".$nombreEmpresa." -- ".$valor["email"]." -- ".$rutaFacturasCorreos."/".$nomFile;
					}					
				}
			}*/
			
			//Enviamos el correo
			
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			
			
			if(!$mail->Send()) 
			{
			  echo "Hubo un error: " . $mail->ErrorInfo;
			} 
			else 
			{
			  //echo "Mensaje enviado con exito.";
			}
			
			$mail->clearAllRecipients();
			$mail->clearAttachments();
		}
		
	}	
	
	$pdf->Output("I","Factura -".date("dmy")." .pdf","UTF-8");
	
}
	
	


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$mostrarPrecio)
{
	$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	$altura = $alturaSiguientePagina;
	$margen = 180;
	if ($mostrarPrecio==0)
	{		
		$pdf->SetXY($margen-10,$altura);
		$pdf->Cell(20,0,"UNIDADES",0,0,'R',false);
	}
	else
	{	
		$pdf->SetXY($margen-50,$altura);
		$pdf->Cell(20,0,"UNIDADES",0,0,'R',false);
		
		$pdf->SetXY($margen-30,$altura);
		$pdf->Cell(20,0,"PRECIO",0,0,'R',false);
		
		$pdf->SetXY($margen-10,$altura);
		$pdf->Cell(20,0,"TOTAL",0,0,'R',false);
	}
	$altura=$altura+5;
}

function dibujarContraste (&$pdf,$margen,$altura, $contador)
{
	$pdf->SetXY($margen,$altura);
	if ($contador%2==0)
	{
		$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);			
	}
	else
	{
		$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);			
	}
	$pdf->Cell(0,5,'',0,1,'L',true);
}



function duplicarPdf($numFactura1,$valor,$datosDetalles)
{
	$pdfEmail = new cabeceraFactura('P','mm','A4');
	$pdfEmail->SetLeftMargin(20);
	$pdfEmail->SetRightMargin(20);
	$pdfEmail->AddPage();
	//$pdfEmail->AliasNbPages();
	$pdfEmail->SetAutoPageBreak(false);	
	$pdfEmail->SetFont('Arial','B',10);
	
	$margenInicial=20;
	$margen=$margenInicial;
	$pdfEmail->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdfEmail->SetLineWidth(0.8);
		$altura=10;	

		$margen += 135;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->SetFont('Arial','',10);
		$pdfEmail->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
		$altura = $altura + 5;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->SetFont('Arial','B',12);
		$pdfEmail->Cell(35,6,$numFactura1."/". $valor["fecha"]->format('y'),1,1,'C',false);

		$altura = $altura + 6;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->SetFont('Arial','',10);
		$pdfEmail->Cell(35,5,"OT ".$valor["presupuesto"],1,1,'C',false);	
	
		
		$pdfEmail->SetFont('Arial','',10);
		$altura = $altura + 8;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(35,5,"Fecha: ". $valor["fecha"]->format('d/m/Y'),0,1,'C',false);
		
		$margen = $margenInicial;
	
		$altura = $altura + 15;

		$pdfEmail->SetFont('Arial','B',18);
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(0,0,"FACTURA",0,1,'L',false);
		
		$pdfEmail->SetFont('Arial','',8);
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(0,5,"Cod. Cliente: ". $valor["codigo"],0,1,'R',false);
		
		$altura = $altura + 5;
		$pdfEmail->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdfEmail->SetLineWidth(0.8);
		$pdfEmail->Line($margen, $altura, 190, $altura);
		
		$altura = $altura + 5;
		$alturaDireccion = $altura;
		$pdfEmail->SetFont('Arial','U',10);
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(0,5,"DIRECCION FISCAL",0,1,'L',false);
		
		$pdfEmail->SetFont('Arial','B',8);
		$altura = $altura + 6;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(84,4,utf8_decode( $valor["nombre_empresa"]),0,'L',false);
		
		$anchoDescripcion = $pdfEmail->GetStringWidth( $valor["nombre_empresa"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		
		$pdfEmail->SetXY($margen,$altura);		
		$pdfEmail->MultiCell(0,4,utf8_decode( $valor["direccion"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth( $valor["direccion"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);
		
		
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode( $valor["codigo_postal"]." - ". $valor["localidad"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth( $valor["codigo_postal"]." - ". $valor["localidad"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode( $valor["provincia"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth( $valor["provincia"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode( $valor["nif_subcliente"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth( $valor["nif_subcliente"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);
		$alturaMax=$altura;


		$pdfEmail->SetFont('Arial','U',10);
		$altura = $alturaDireccion;
		$margen = $pdfEmail->GetPageWidth()/2;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(0,5,"DIRECCION ENVIO",0,1,'L',false);
		
		$pdfEmail->SetFont('Arial','B',8);
		$altura = $altura + 6;
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(84,4,utf8_decode( $valor["envio_att"]),0,'L',false);

		$anchoDescripcion = $pdfEmail->GetStringWidth($valor["envio_att"]);
		if ($anchoDescripcion>0)
		{
			$numeroDeFilas = ceil ($anchoDescripcion / 84);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);
		}

		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(84,4,utf8_decode($valor["envio_nombre"]),0,'L',false);

		$anchoDescripcion = $pdfEmail->GetStringWidth($valor["envio_nombre"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);



		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode($valor["envio_domicilio"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth($valor["envio_domicilio"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode($valor["envio_cp"]." - ".$valor["envio_poblacion"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth($valor["envio_cp"]." - ".$valor["envio_poblacion"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode($valor["envio_provincia"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth($valor["envio_provincia"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 84);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);

		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->MultiCell(0,4,utf8_decode($valor["envio_pais"]),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth($valor["envio_pais"]);
		if ($anchoDescripcion>0)
		{
			$numeroDeFilas = ceil ($anchoDescripcion / 84);
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

		$margen=$margenInicial;
		$pdfEmail->SetLineWidth(0.8);
		$pdfEmail->Line($margen, $altura, 190, $altura);
		
		$altura += 10;
		$pdfEmail->SetFont('Arial','B',10);
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(0,5,"DESCRIPCION:  ",0,1,'L',false);
		
		$margen += 30;
	
		$pdfEmail->SetXY($margen,$altura);	
		$pdfEmail->SetFont('Arial','B',10);	
		$eltitulo=$valor["descripcion"];
		//$eltitulo = "Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021";
		$pdfEmail->MultiCell(0,5,utf8_decode($eltitulo),0,'L',false);
		//$pdfEmail->MultiCell(0,5,utf8_decode("Manipulación y envío de su Correspondencia diario del 01/05/2021 al 31/05/2021"."aaaaa  aaaa "),0,'L',false);
		$anchoDescripcion = $pdfEmail->GetStringWidth(utf8_decode($eltitulo));
		$numeroDeFilas = ceil ($anchoDescripcion / (180-30));
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);
	
		////////////////////

		if ($valor["pedido"]!="")
		{
			$margen=$margenInicial;
			$altura += 5;
			$pdfEmail->SetFont('Arial','B',10);
			$pdfEmail->SetXY($margen,$altura);
			$pdfEmail->Cell(0,5,"PEDIDO:  ".utf8_decode($valor["pedido"]),0,1,'L',false);
		}
		
		
		
		
		$altura += 10;
		$pdfEmail->SetFont('Arial','B',10);
		$pdfEmail->SetXY($margen,$altura);
		$pdfEmail->Cell(0,5,"CONCEPTOS",0,1,'L',false);
		
		///////////////////////////////
		
		///////////////////////////////
		//DETALLES
		$pdfEmail->SetFont('Arial','B',9);

		$mostrarPrecio = $valor["detallada"];
	
		$mostrarPrecio = 1;
		//$altura += 5;
		$margen = 180;


		if ($mostrarPrecio==0)
		{		
			$pdfEmail->SetXY($margen-10,$altura);
			$pdfEmail->Cell(20,5,"UNIDADES",0,0,'R',false);
		}
		else
		{	
			$pdfEmail->SetXY($margen-50,$altura);
			$pdfEmail->Cell(20,5,"UNIDADES",0,0,'R',false);

			$pdfEmail->SetXY($margen-30,$altura);
			$pdfEmail->Cell(20,5,"PRECIO",0,0,'R',false);

			$pdfEmail->SetXY($margen-10,$altura);
			$pdfEmail->Cell(20,5,"TOTAL",0,0,'R',false);
		}
		
		
		$limiteAlturaDatos = 280;
		//$alturaSiguientePagina = 50;
		$altura +=7;
		$contador=1;
		$margen = $margenInicial;

		//$contadorConceptos=0;
		foreach ($datosDetalles as $row) 
		{
			//$contador++;
			$pdfEmail->SetFont('Arial','',9);

			//$pdfEmail->Cell(125-5,5,$row["proceso"],1,0,'L',false);

			/*if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}*/


			if ($contador%2==0)
			{
				$pdfEmail->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
			}
			else
			{
				$pdfEmail->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);
			}
			
			$alturaConcepto=$altura;

			$ladescripion="";
			if ($row["descripcion"]!=""&&$row["descripcion"]!=null)
			{
				$ladescripion=" (".$row["descripcion"].")";			
			}



			$pdfEmail->SetXY($margen,$altura);		
			$pdfEmail->MultiCell(110,5,utf8_decode($row["concepto"].$ladescripion),0,'L',true);


			$anchoDescripcion = $pdfEmail->GetStringWidth(utf8_decode($row["concepto"].$ladescripion));
			$numeroDeFilas = ceil ($anchoDescripcion / (109));
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}	
			$altura = $altura + (5*$numeroDeFilas);
			$altura = $altura - 5;

			

			$unidad="";
			$precio="";
			$total="";


			$unidad = number_format($row["unidades"],3,',','.');

			if ($unidad===0)
			{
				$unidad = "";
			}
			else if ($row["concepto"]=='Manipulado de Productos No Bonificables')
			{
				$unidad = number_format($row["unidades"],0,',','.')." %";
			}

			$precio = number_format($row["precio"],2,',','.');

			if ($precio===0.00)
			{
				$precio = "";
			}
			else
			{
				$precio .= " ".EURO;
			}

			//$total = $row["unidades"] * $row["precio"];

			$total = number_format($row["total"],2,',','.');

			//echo ("<br>hola".$total);         



			if ($total===0)
			{
				$total = "";
			}
			else
			{
				$total .= " ".EURO;
				$margen = 180;

				$pdfEmail->SetFont('Arial','',8);
				if ($mostrarPrecio==0)
				{		
					$pdfEmail->SetXY($margen-50,$alturaConcepto);
					$pdfEmail->Cell(60,5*$numeroDeFilas,$unidad,0,0,'R',true);
				}
				else
				{			
					$pdfEmail->SetXY($margen-50,$alturaConcepto);
					$pdfEmail->Cell(20,5*$numeroDeFilas,$unidad,0,0,'R',true);

					$pdfEmail->SetXY($margen-30,$alturaConcepto);
					$pdfEmail->Cell(20,5*$numeroDeFilas,$precio,0,0,'R',true);			


					$pdfEmail->SetXY($margen-10,$alturaConcepto);			
					$pdfEmail->Cell(20,5*$numeroDeFilas,$total,0,0,'R',true);			
				}



				$margen = $margenInicial;
				$altura = $altura + 5;
				$contador++;
			}




			/*if ($altura>265)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}*/

		}
		
		//PIE DE PAGINA
	
		$altura = 235;

		$margen=$margenInicial;
		$pdfEmail->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdfEmail->SetLineWidth(0.8);
		$pdfEmail->Line($margen, $altura, 190, $altura);


		$altura = 240;		
		$pdfEmail->SetFont('Arial','B',10);
		$pdfEmail->SetXY(20,$altura);	
		$pdfEmail->Cell(50,5,"FORMA DE PAGO:",0,0,'L',false);
		$altura += 8;
		$pdfEmail->SetFont('Arial','',10);
		$pdfEmail->SetXY(20,$altura);

		$pdfEmail->MultiCell(95,5,utf8_decode($valor["formaPago"]),0,'L',false);


		//$cuentaBancaria = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br>", $cuentaBancaria);
		//$cuentaBancaria = str_replace("\r", "<br>\n", $cuentaBancaria);

		$cuentaBancaria = str_replace(";", "\n", $valor["cuentaDelBanco"]);
		$altura += 10;
		$pdfEmail->SetXY(20,$altura);
		$pdfEmail->MultiCell(95,5,utf8_decode($cuentaBancaria),0,'L',false);

		$altura = 240;

		$ancho = $pdfEmail->GetPageWidth()-20-70;
		//$altura = $altura + 25;
		$pdfEmail->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
		$pdfEmail->Rect($ancho, $altura, 70, 20,'F');


		$pdfEmail->SetFont('Arial','B',10);
		$pdfEmail->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);


		$ancho+=10;

		$pdfEmail->SetXY($ancho,$altura);			
		$pdfEmail->Cell(20,5,"Base Imponible:",0,0,'R',false);



		$pdfEmail->SetXY($ancho+35,$altura);			
		$pdfEmail->Cell(20,5,number_format($valor["precioNeto"],2,',','.')." ".EURO,0,0,'R',false);	

		$altura += 7;
		$pdfEmail->SetXY($ancho,$altura);			
		$pdfEmail->Cell(20,5,"IVA 21%:",0,0,'R',false);	

		$pdfEmail->SetXY($ancho+35,$altura);	
		$pdfEmail->Cell(20,5,number_format($valor["iva"],2,',','.')." ".EURO,0,0,'R',false);

		$altura += 7;
		$pdfEmail->SetXY($ancho,$altura);			
		$pdfEmail->Cell(20,5,"TOTAL:",0,0,'R',false);

		$pdfEmail->SetXY($ancho+35,$altura);			
		$pdfEmail->Cell(20,5,number_format($valor["precioTotal"],2,',','.')." ".EURO,0,0,'R',false);	


		$pdfEmail->SetFont('Arial','',8);
		$pdfEmail->SetTextColor(colorNegroR,colorNegroG,colorNegroB);

		$altura += 10;
		$pdfEmail->SetXY($ancho,$altura);			
		$pdfEmail->Cell(20,5,"Provision de Fondo:",0,0,'R',false);

		$pdfEmail->SetXY($ancho+35,$altura);			
		$pdfEmail->Cell(20,5,number_format($valor["provision"],2,',','.')." ".EURO,0,0,'R',false);	

		$altura += 7;
		$pdfEmail->SetXY($ancho,$altura);			
		$pdfEmail->Cell(20,5,"Total a Pagar:",0,0,'R',false);

		$pdfEmail->SetXY($ancho+35,$altura);			
		$pdfEmail->Cell(20,5,number_format($valor["aPagar"],2,',','.')." ".EURO,0,0,'R',false);
	
		$docFactura = $pdfEmail->Output("S","Factura -".date("dmy")." .pdf","UTF-8");
		return $docFactura;
}

?>
	
	
	
	
	
		


	