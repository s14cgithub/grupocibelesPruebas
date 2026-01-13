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

	//$pdf->SetXY(-10,-5);

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	
	$margenInicial=20;
	$margen=$margenInicial;
	$altura=10;	
	
	//$contador=0;
	
	$registrosEmail="";
	
	foreach ($datosFactura as $valor)
	{		
		$numFactura = $valor["numero"];
		
		$datosDetalles = verFacturaDetalle($conexion,$numFactura);
		
		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);
		
		
		//////////////////////
		$pdfEmail = new cabeceraFactura('P','mm','A4');

	

		$pdfEmail->SetLeftMargin(20);
		$pdfEmail->SetRightMargin(20);
		
		/////////////////////
		
		
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

		//$altura = $altura + 6;
		//$pdf->SetXY($margen,$altura);
		//$pdf->SetFont('Arial','',10);
		//$pdf->Cell(35,5,"OT ".$datosFactura[0]["presupuesto"],1,1,'C',false);	
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
		$pdf->Cell(0,5,"Cod. Cliente: ". $valor["codigo"],0,1,'R',false);
		
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
		
		$altura += 10;
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
		$pdf->Cell(0,5,utf8_decode($laFechaFin),0,1,'L',false);
		
		
		
		$margen = $margenInicial;
		$altura += 10;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"CONCEPTOS SUJETOS A I.V.A.",0,1,'L',false);
		
		///////////////////////////////
		
		$pdf->SetFont('Arial','B',9);
				
		$margen = $pdf->GetPageWidth()-40;
		//$altura += 15;

		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,utf8_decode("Importe"),0,1,'R',false);

		$margen -= 20;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,utf8_decode("Precio"),0,1,'R',false);

		$margen -= 20;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,utf8_decode("Unidades"),0,1,'R',false);		
		
		
		/////////////////////////////
		$contadorConceptos=0;
		
		///////////////////
		
		/*$margen += 120;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,"Euros",0,1,'R',false);*/
		
		$altura += 10;
		
		
		$contadorConceptos++;
		dibujarContraste($pdf,$margenInicial, $altura, $contadorConceptos);
		
		
		$margen = $margenInicial;
		
		
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(0,5,utf8_decode("Fijo Mensual"),0,1,'L',false);
		
		//$margen += 110;
		$margen = $pdf->GetPageWidth()-40;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,utf8_decode($valor["fac_cuotaRecogida"])." ".EURO,0,1,'R',false);
		
		$margen -= 20;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,utf8_decode($valor["fac_cuotaRecogida"])." ".EURO,0,1,'R',false);
		
		$margen -= 20;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,"1",0,1,'R',false);
		
		if ($valor["fac_cobroUnitarioEnvio"]>0 && $valor["cantidad"]>0)
		{			
			$margen = $margenInicial;
			$altura += 5;
			
			$contadorConceptos++;
			dibujarContraste($pdf,$margenInicial, $altura, $contadorConceptos);
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,utf8_decode("Manipulación Postal"),0,1,'L',false);

			$totalManipulado = 0.00;
			$totalManipulado = number_format($valor["cantidad"]*$valor["fac_cobroUnitarioEnvio"],2,',','.');
			$margen = $pdf->GetPageWidth()-40;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,$totalManipulado." ".EURO,0,1,'R',false);

			$margen -= 20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,number_format($valor["fac_cobroUnitarioEnvio"],3,',','.')." ".EURO,0,1,'R',false);

			$margen -= 20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,number_format($valor["cantidad"],0,',','.'),0,1,'R',false);		
		
		}
		
		
		if ($valor["importeFranqueo"]!="" && $valor["importeFranqueo"]!=null && $valor["importeFranqueo"]!="null" && $valor["importeFranqueo"]>0 && $valor["fac_porCientoNoBonificable"]!="" && $valor["fac_porCientoNoBonificable"]!=null && $valor["fac_porCientoNoBonificable"]!="null" && $valor["fac_porCientoNoBonificable"]>0)
		{
			$margen = $margenInicial;
			$altura += 5;
			
			$contadorConceptos++;
			dibujarContraste($pdf,$margenInicial, $altura, $contadorConceptos);
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,utf8_decode("Manipulado de Productos No Bonificables"),0,1,'L',false);

			$elPrecioFranqueo=0;
			$tantoPorCiento=0;
			/*if ($valor["importeFranqueo"]!="" && $valor["importeFranqueo"]!=null && $valor["importeFranqueo"]!="null")
			{*/
				$elPrecioFranqueo = $valor["importeFranqueo"];		
			//}
			$tantoPorCiento  = number_format($elPrecioFranqueo * $valor["fac_porCientoNoBonificable"] /100,2,',','.');
			//$tantoPorCiento  = number_format(16.05 * 15 /100,2,',','.');

			//$pdf->Cell(20,5,$valor["importeFranqueo"],0,1,'R',false);

			$margen = $pdf->GetPageWidth()-40;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,$tantoPorCiento." ".EURO,0,1,'R',false);

			$margen -= 20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,$elPrecioFranqueo." ".EURO,0,1,'R',false);

			$margen -= 20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,$valor["fac_porCientoNoBonificable"]." %",0,1,'R',false);
		
		
		}
		
		
		
		
		//se muestra los detalles
		
		
		foreach ($datosDetalles as $valorDetalle)
		{
			$pdf->SetFont('Arial','',9);
			$margen = $margenInicial;
			$altura += 5;
			
			$contadorConceptos++;
			dibujarContraste($pdf,$margenInicial, $altura, $contadorConceptos);
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,utf8_decode($valorDetalle["concepto"]),0,1,'L',false);	
			
			$margen = $pdf->GetPageWidth()-40;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,number_format($valorDetalle["total"],2,',','.')." ".EURO,0,1,'R',false);		
			$margen -= 20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,number_format($valorDetalle["precio"],3,',','.')." ".EURO,0,1,'R',false);		
			$margen -= 20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,5,number_format($valorDetalle["unidades"],3,',','.'),0,1,'R',false);			
			
		}
		
		if ($valor["fac_otrosConceptosFijos"]!="" && $valor["fac_otrosConceptosFijos"]!=null && $valor["fac_otrosConceptosFijos"]!="null")
		{
			$margen = $margenInicial;
			$altura += 5;
			
			$contadorConceptos++;
			dibujarContraste($pdf,$margenInicial, $altura, $contadorConceptos);
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,5,utf8_decode($valor["fac_otrosConceptosFijos"]),0,1,'L',false);
			
		}
		
		//PIE DE PAGINA
	
		$altura = 235;
		
		$margen=$margenInicial;
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.8);
		$pdf->Line($margen, $altura, 190, $altura);
		
		
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY(20,$altura);	
		$pdf->Cell(50,5,"FORMA DE PAGO:",0,0,'L',false);
		$altura += 8;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(20,$altura);

		$pdf->MultiCell(95,5,utf8_decode($valor["formaPago"]),0,'L',false);


		//$cuentaBancaria = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br>", $cuentaBancaria);
		//$cuentaBancaria = str_replace("\r", "<br>\n", $cuentaBancaria);

		$cuentaBancaria = "ES48 0049 1839 4621 1043 1601\nES21 2100 1945 2402 0000 7147";
		$altura += 10;
		$pdf->SetXY(20,$altura);
		$pdf->MultiCell(95,5,utf8_decode($cuentaBancaria),0,'L',false);
		
		$altura = 215;
	
		$ancho = $pdf->GetPageWidth()-20-70;
		$altura = $altura + 25;
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
			//envio de email
			//$pdfenvio=null;
			$pdfEnvio= new $pdf;
			$docFactura = $pdfEnvio->Output("S","Factura -".date("dmy")." .pdf","UTF-8");
			//$docFactura = $pdfEnvio->Output("S","","UTF-8");
			//require_once('PHPMailer/enviarFactura.php');
			
			$mail->AddAddress($valor["email"], $valor["email"]);
			$mail->AddStringAttachment($docFactura, 'factura.pdf', 'base64', 'application/pdf'); 
			
			
		
			$nombreEmpresa = $valor["nombre_empresa"];
			
			$registrosEmail .= "\n".$nombreEmpresa." -- ".$valor["email"]." -- factura.pdf";
			
			
			$rutaFacturasCorreos = '//172.26.0.44/d/Administracion/Compartida/facturasCorreos';
			
			$nombresArchivos = scandir($rutaFacturasCorreos);
			
			
			foreach ($nombresArchivos as $nomFile)
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
			}			
			
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
		$pdf->SetFillColor(colorContrasteR,colorContrasteG,colorContrasteB);			
	}
	else
	{
		$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);			
	}
	$pdf->Cell(0,5,'',0,1,'L',true);
}



?>
	<!--$formasDePago=cargarFormasDePago($conexion);-->
	
	
	
	
	
		


