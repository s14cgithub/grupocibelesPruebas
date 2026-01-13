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
	require($ruta."Archivos Comunes/cabeceraPieFacturaGroupPag.php");
	
	
	
	
	//$numFactura = $_POST["imprimirNumFactura"];	
	
	
	
	$numFactura = isset($_POST["imprimirNumFactura"]) ? $_POST["imprimirNumFactura"] : 0;
	$minNumFac = isset($_POST["imprimirPrimeraFactura"]) ? $_POST["imprimirPrimeraFactura"] : $numFactura;
	$MaxNumFac = isset($_POST["imprimirUltimaFactura"]) ? $_POST["imprimirUltimaFactura"] : $numFactura;
	
	//$minNumFac = 1;
	//$MaxNumFac = 16;
	
	
	$contadorMax=$minNumFac;
	$pdf = new cabeceraFactura('P','mm','A4');
	while ($contadorMax<=$MaxNumFac)
		
	{
		$numFactura = $contadorMax;
		$pdf->StartPageGroup();
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	$datosFactura = verFactura($conexion,$numFactura);
	
	$presupuestosAimprimir = "";
	$presupuestosAimprimirContador=1;
	$sumatorio = $datosFactura[0]["combinadoSumatorio"];
	$numPresupuesto=$datosFactura[0]["presupuesto"];
	
	$combinados=0;
	if (substr($datosFactura[0]["presupuesto"], 0, 5) === 'Comb:')
	{
		$combinados=1;
	}
	
	
	if ($sumatorio==1)
	{
		$datosDetalles = verFacturaDetalleSumatorio($conexion,$numFactura); 
		$numPresupuesto = $datosFactura[0]["presupuesto"];
		$eltitulo="";
	}
	else if ($combinados==0)
	{
		$datosDetalles = verFacturaDetalle($conexion,$numFactura);
		$presupuestosAimprimir = $datosFactura[0]["presupuesto"];
		$eltitulo=$datosFactura[0]["descripcion"];
	}	
	else
	{
		$presupuestosAimprimir = verNumPresupuestosCombinados($conexion,$numFactura);		
		$eltitulo="";
		$presupuestosAimprimirContador = count($presupuestosAimprimir);
		$numPresupuesto="";
	}
	
	
	
	$nombrePresupuestoCompleto = $datosFactura[0]["presupuesto"];
	
	//$pdf = new cabeceraFactura('P','mm','A4');

	

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	
	
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	
	
	/*$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 0;
	
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,$numFactura."/".$datosFactura[0]["fecha"]->format('y'). " MA",1,1,'C',false);	
	
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,"Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y'),0,1,'C',false);*/
	
	$datosNuevaPagina1 = $numFactura."/".$datosFactura[0]["fecha"]->format('y'). " MA";
	$datosNuevaPagina2 = "Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y');
	
	$limiteAlturaDatos = 280;
	$alturaSiguientePagina = 20;//50
	nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2);	
	$alturaSiguientePagina = 40;//50
	$margen = $margenInicial;
	
	$altura = $altura + 15;
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"FACTURA",0,1,'L',false);
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	
	$datosCliente =  cargarClientes($conexion," where codigo_saldo=".$datosFactura[0]["codigo"]." and codigo=".$datosFactura[0]["codigo"]);
	
	$retener = "";
		
		
	//echo $datosFactura[0]["codigo"];
		
		
	if ($datosCliente[0]["retener"]==1)
	{
		$retener = " -  R";
	}
	
	
	$datosCliente=null;
	
	$pdf->Cell(0,5,"Cod. Cliente: ".$datosFactura[0]["codigo"].$retener,0,1,'R',false);
	
	
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
	$pdf->MultiCell(84,4,utf8_decode($datosFactura[0]["direccion"]),0,'L',false);
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
		
	
	if($datosFactura[0]["envio_domicilio"]=="" && $datosFactura[0]["envio_cp"]=="" && $datosFactura[0]["envio_poblacion"]=="" && $datosFactura[0]["envio_provincia"]=="")
	{
		$pdf->MultiCell(84,4,utf8_decode($datosFactura[0]["nombre_empresa"]),0,'L',false);
	
		$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["nombre_empresa"]);
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
		$pdf->MultiCell(84,4,utf8_decode($datosFactura[0]["direccion"]),0,'L',false);

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

		

		
	}
	else
	{
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
	}
	
	if ($alturaMax>$altura)
	{
		$altura = $alturaMax;
	}
		
		
		
		
		
		
	
	
	if ($alturaMax>$altura)
	{
		$altura = $alturaMax;
	}
	
	$margen=$margenInicial;
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	////////////////////
	/*$altura += 10;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DESCRIPCION:  ",0,1,'L',false);*/

	//$altura += 10;
	$margen += 30;
	
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
	if ($datosFactura[0]["pedido"]!="" && $datosFactura[0]["pedido"]!="NINGUNO" )
	{
		
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"PEDIDO:  ".utf8_decode($datosFactura[0]["pedido"]),0,1,'L',false);
	}
	
	
	if ($datosFactura[0]["cantidad"]!="" && $datosFactura[0]["cantidad"]!="0" )
	{
		$altura += 10;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,"Cantidad: ".number_format($datosFactura[0]["cantidad"],0,',','.'),0,0,'L',false);
	}
	
	/*$cantidad=0;
	if ($datosFactura[0]["cantidad"]!=null&&$datosFactura[0]["cantidad"]!="null"&&$datosFactura[0]["cantidad"]!="")
	{
		$cantidad=$datosFactura[0]["cantidad"];
	}	
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"UNIDADES:  ".$cantidad,0,1,'R',false);*/
	
	
	if ($datosFactura[0]["observaciones"]!="" && $datosFactura[0]["observaciones"]!="NINGUNO" ) 
	{
		
		/*$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"Observaciones:  ".utf8_decode($datosFactura[0]["observaciones"]),0,1,'L',false);*/
		
		$altura += 5;
		//////////////////////
		//$margen += 30;
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
		/////////////////////
		
	}
	
	
	
	
	
	$contadorGenerico=0;
	
	while ($contadorGenerico<$presupuestosAimprimirContador)
	{
		
		
		if ($altura>190)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2);	
		}
		
		
		if ($combinados==1 && $sumatorio != 1) 
		{ 
			$numPresupuesto = $presupuestosAimprimir[$contadorGenerico]["presupuesto"];
			//$eltitulo = $presupuestosAimprimir[$contadorGenerico]["campana"];
			
			$datosDetalles = verFacturaDetallePresupuesto($conexion,$numFactura,$numPresupuesto);
			
			$eltitulo = $datosDetalles[0]["campana"];
		}
		else
		{
			//echo ' no entra<br>Combinados: '.$combinados.'<br>Sumatorio:'.$sumatorio;
		}
		
		$altura += 10;
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
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2);	
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
				$ladescripion = str_replace('ª',signo_ordinal,$ladescripion);				
	
			}
			
			$elConcepto = str_replace('€',EURO,$row["concepto"]);
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
			$elConcepto = str_replace('ª',signo_ordinal,$elConcepto);

			//$ladescripion = $ladescripion." altura: ".$altura;

			$pdf->SetXY($margen,$altura);		
			//$pdf->MultiCell(110,5,utf8_decode($row["concepto"].$ladescripion),0,'L',true);
			$pdf->MultiCell(110,5,($elConcepto.$ladescripion),0,'L',true);


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
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2);	
			}

		}
		
		$contadorGenerico++;
	
	}
	if ($altura>230)
	{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2);	
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
	
	if (floatval($datosFactura[0]["provision"])>0)
	{
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
	}
	
	
	
	
	
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
	
	
	
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		
		$contadorMax++;
	}
		
		
	
	$pdf->Output("I","Factura - ".$numFactura."-".date("dmy")." .pdf","UTF-8");
	
	
}
	
	


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2)
{
	$pdf->AddPage();
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 0;
	$altura =10;
	$margen = 20;
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,$datosNuevaPagina1,1,1,'C',false);	
	
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,$datosNuevaPagina2,0,1,'C',false);
	
	
	
	$altura = $alturaSiguientePagina;
	$margen = 180;
	
	$altura=$altura+5;
}



?>
	<!--$formasDePago=cargarFormasDePago($conexion);-->
	
	
	
	
	
		


