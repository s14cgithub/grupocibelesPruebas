<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirFactura")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFactura2.php");
	
	
	
	
	$numFactura = $_POST["imprimirNumFactura"];	
	
	$datosFactura = verFactura($conexion,$numFactura);
	$datosDetalles = verFacturaDetalle($conexion,$numFactura);
	
	
	//$nombrePresupuestoCompleto = $datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"];
	$nombrePresupuestoCompleto = $datosFactura[0]["presupuesto"];
	
	$pdf = new cabeceraFactura('P','mm','A4');

	//$pdf->SetXY(-10,-5);

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 0;
	
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,$numFactura."/".$datosFactura[0]["fecha"]->format('y'),1,1,'C',false);
	
	$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,"OT ".$datosFactura[0]["presupuesto"],1,1,'C',false);	
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,"Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y'),0,1,'C',false);
	
	
	
	
	
	$margen = $margenInicial;
	
	$altura = $altura + 15;
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"FACTURA",0,1,'L',false);
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"Cod. Cliente: ".$datosFactura[0]["codigo"],0,1,'R',false);
	
	
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
	$pdf->MultiCell(84,4,utf8_decode($datosFactura[0]["nombre_empresa"]),0,'L',false);
	
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["nombre_empresa"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["direccion"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["direccion"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["codigo_postal"]." - ".$datosFactura[0]["localidad"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["codigo_postal"]." - ".$datosFactura[0]["localidad"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["provincia"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["provincia"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["nif_subcliente"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["nif_subcliente"]);
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
	$pdf->MultiCell(84,4,utf8_decode($datosFactura[0]["envio_att"]),0,'L',false);
	
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["envio_att"]);
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
	$pdf->MultiCell(84,4,utf8_decode($datosFactura[0]["envio_nombre"]),0,'L',false);
	
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["envio_nombre"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["envio_domicilio"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["envio_domicilio"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["envio_cp"]." - ".$datosFactura[0]["envio_poblacion"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["envio_cp"]." - ".$datosFactura[0]["envio_poblacion"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["envio_provincia"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["envio_provincia"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["envio_pais"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["envio_pais"]);
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
	
	////////////////////
	$altura += 10;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DESCRIPCION:  ",0,1,'L',false);

	//$altura += 10;
	$margen += 30;
	
	$pdf->SetXY($margen,$altura);	
	$pdf->SetFont('Arial','B',10);	
	$eltitulo=$datosFactura[0]["descripcion"];
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
	
	if ($datosFactura[0]["pedido"]!="")
	{
		$margen=$margenInicial;
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"PEDIDO:  ".utf8_decode($datosFactura[0]["pedido"]),0,1,'L',false);
	}
	
	/*$cantidad=0;
	if ($datosFactura[0]["cantidad"]!=null&&$datosFactura[0]["cantidad"]!="null"&&$datosFactura[0]["cantidad"]!="")
	{
		$cantidad=$datosFactura[0]["cantidad"];
	}	
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"UNIDADES:  ".$cantidad,0,1,'R',false);*/
	
	
	
	$altura += 10;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"CONCEPTOS",0,1,'L',false);
	
	
	
	//DETALLES
	$pdf->SetFont('Arial','B',8);
	
	
	$mostrarPrecio = $datosFactura[0]["detallada"];
	
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
	$alturaSiguientePagina = 50;
	$altura +=7;
	$contador=0;
	$margen = $margenInicial;
	
	
	foreach ($datosDetalles as $row) 
	{
		$contador++;
		$pdf->SetFont('Arial','',9);
		
		//$pdf->Cell(125-5,5,$row["proceso"],1,0,'L',false);
		
		if ($altura>$limiteAlturaDatos)
		{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
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
		
		//$pdf->SetXY($margen-5,$altura);
		//$pdf->MultiCell(125-5,5,ceil ($anchoDescripcion / (119)),0,'L',false);
		
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
		}
		
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
		
		
		if ($altura>265)
		{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
		}
		
		
	}
	
	
	///////////////////////////PARA RELLENAR/////////////////////////
	
	$contadorPrueba=0;
	while ($contadorPrueba<30)
	{
		$margen = $margenInicial;
		$altura += 5;
		$pdf->SetXY($margen,$altura);	
		$pdf->Cell(20,0,"para rellenar ".$contadorPrueba. " altura: ".$altura,0.0,'R',true);	
		$contadorPrueba++;
		
		if ($altura>265)
		{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
		}
		
	}
	
	/////////////////////////////
	
	
	if ($altura>240)
	{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
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
	
	$pdf->MultiCell(95,5,utf8_decode($datosFactura[0]["formaPago"]),0,'L',false);
	
	
	//$cuentaBancaria = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br>", $cuentaBancaria);
	//$cuentaBancaria = str_replace("\r", "<br>\n", $cuentaBancaria);
	$cuentaBancaria = str_replace(";", "\n", $datosFactura[0]["cuentaDelBanco"]);
	
	
	//$cuentaBancaria = $datosFactura[0]["cuentaDelBanco"];
	$altura += 10;
	$pdf->SetXY(20,$altura);
	$pdf->MultiCell(95,5,utf8_decode($cuentaBancaria),0,'L',false);
	
	
	$altura = 240;
	
	$ancho = $pdf->GetPageWidth()-20-70;
	
	$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->Rect($ancho, $altura, 70, 20,'F');
	
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);
	
	
	$ancho+=10;
	
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"Base Imponible:",0,0,'R',false);
	
	
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,5,number_format($datosFactura[0]["precioNeto"],2,',','.')." ".EURO,0,0,'R',false);	
	
	$altura += 7;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"IVA 21%:",0,0,'R',false);	
	
	$pdf->SetXY($ancho+35,$altura);	
	$pdf->Cell(20,5,number_format($datosFactura[0]["iva"],2,',','.')." ".EURO,0,0,'R',false);
	
	//$pdf->SetXY($ancho+35,$altura);		
	//$pdf->Cell(20,5,number_format($datosFactura[0]["iva"],2,',','.')." ".EURO,1,0,'L',false);	
	
	$altura += 7;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"TOTAL:",0,0,'R',false);
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,5,number_format($datosFactura[0]["precioTotal"],2,',','.')." ".EURO,0,0,'R',false);	
	
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	
	$altura += 10;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"Provision de Fondo:",0,0,'R',false);
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,5,number_format($datosFactura[0]["provision"],2,',','.')." ".EURO,0,0,'R',false);	
	
	$altura += 7;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"Total a Pagar:",0,0,'R',false);
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,5,number_format($datosFactura[0]["aPagar"],2,',','.')." ".EURO,0,0,'R',false);	
	
	
	
	/*$altura = $limiteAlturaDatos;
	$altura = $altura + 13;
	$pdf->SetDrawColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	
	
	$altura = $altura + 5;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07
Cibeles Mailing S.A. A-81339186"),0,'C',false);*/
	
	
	
	
	$pdf->Output("I","Factura - ".$numFactura."-".date("dmy")." .pdf","UTF-8");
	
	
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



?>
	


