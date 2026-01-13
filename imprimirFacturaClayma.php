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
	require($ruta."Archivos Comunes/cabeceraPieFacturaClaymaGroupPag.php");
	
	
	
	
	$anioSeleccionado = isset($_POST["anioSeleccionado3"]) ? $_POST["anioSeleccionado3"] : 0;
	if ($anioSeleccionado==0 || $anioSeleccionado=="0")
	{
		$anioSeleccionado = $_POST["anioSeleccionado2"];
	}
	
	
	
	
	
	$numFactura = isset($_POST["imprimirNumFacturaClayma"]) ? $_POST["imprimirNumFacturaClayma"] : 0;
	$minNumFac = isset($_POST["imprimirPrimeraFacturaClayma"]) ? $_POST["imprimirPrimeraFacturaClayma"] : $numFactura;
	$MaxNumFac = isset($_POST["imprimirUltimaFacturaClayma"]) ? $_POST["imprimirUltimaFacturaClayma"] : $numFactura;
	//echo $numFactura;
	$contadorMax=$minNumFac;
	$pdf = new cabeceraFactura('P','mm','A4');
	while ($contadorMax<=$MaxNumFac)
		
	{
		$numFactura = $contadorMax;
		$pdf->StartPageGroup();
		
		
	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	//echo $anioSeleccionado;
	
	$datosFactura = verFacturaClayma($conexion,$numFactura,$anioSeleccionado);

	
	
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
		$datosDetalles = verFacturaDetalleSumatorioClayma($conexion,$numFactura,$anioSeleccionado); 
		$numPresupuesto = $datosFactura[0]["presupuesto"];
		$eltitulo="";
	}
	else if ($combinados==0)
	{
		//echo "entra";
		$datosDetalles = verFacturaDetalleClayma($conexion,$numFactura,$anioSeleccionado);
		$presupuestosAimprimir = $datosFactura[0]["presupuesto"];
		$eltitulo=$datosFactura[0]["descripcion"];
	}	
	else
	{
		$presupuestosAimprimir = verNumPresupuestosCombinadosClayma($conexion,$numFactura,$anioSeleccionado);		
		$eltitulo="";
		$presupuestosAimprimirContador = count($presupuestosAimprimir);
		$numPresupuesto="";
	}
	
	
	
	
	//$nombrePresupuestoCompleto = $datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"];
	$nombrePresupuestoCompleto = $datosFactura[0]["presupuesto"];
	
	//$pdf = new cabeceraFactura('P','mm','A4');

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
	
	
	$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 0;
	
		
		
		
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
		
	$datosPrefactura1="";
	$datosPrefactura2="FACTURA";
	
	
	
	
/*
	if ($datosFactura[0]["laprefactura"]==1)
	{
		$pdf->Cell(35,5,utf8_decode("PREFACTURA Nº"),1,1,'C',false);
		$datosPrefactura1=" PRE";
		$datosPrefactura2="PREFACTURA";
	}
	else*/
	{
		$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
	}
	$fechaLimite  = new DateTime(fechaCambioVerifactu);	
		
	if (strlen($datosFactura[0]["verifactu_message"])>5 || ($datosFactura[0]["verifactu_idSolicitud"]==NULL && $datosFactura[0]["fecha"]> $fechaLimite ))
	{
		//$pdf->SetTextColor(colorPrevisualizacionR,colorPrevisualizacionG,colorPrevisualizacionB);
		$pdf->SetFont('Arial','B',50);	
		$pdf->SetXY(40,10);
		$pdf->Cell(0,0,utf8_decode("REVISAR FACTURA"),0,1,'C',false);	
		$pdf->SetXY(0,95);
		$pdf->Cell(0,0,utf8_decode("REVISAR FACTURA"),0,1,'C',false);
		$pdf->SetTextColor(0,0,0);
	}
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	//$pdf->Cell(35,6,$numFactura."/".$datosFactura[0]["fecha"]->format('y').$datosPrefactura1,1,1,'C',false);
	//$pdf->Cell(35,6,$datosFactura[0]["serieFactura"]." ".$numFactura."/".$datosFactura[0]["fecha"]->format('y').$datosPrefactura1,1,1,'C',false);


	if (strlen($datosFactura[0]["verifactu_message"])==0)
	{
		$pdf->Cell(35,6,$datosFactura[0]["serieFactura"]." ".$numFactura."/".$datosFactura[0]["fecha"]->format('y').$datosPrefactura1,1,1,'C',false);
	}

	

	//$pdf->Cell(35,6,$numFactura."/".date('y'),1,1,'C',false);
	
	/*$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,"OT ".$datosFactura[0]["presupuesto"],1,1,'C',false);	*/
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	//$pdf->Cell(35,5,"Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y'),0,1,'C',false);
	$pdf->Cell(35,5,"Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y'),0,1,'C',false);
	
	
	
	
	
	
	$margen = $margenInicial;
	
	$altura = $altura + 15;
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,$datosPrefactura2,0,1,'L',false);
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	
	
	$datosCliente =  cargarClientesClayma($conexion," where codigo_saldo=".$datosFactura[0]["codigo"]." and codigo=".$datosFactura[0]["codigo"]);
	
	$retener = "";
	if ($datosCliente[0]["retener"]==1)
	{
		$retener = " -  R";
	}
	
	$datosCliente=null;
	
	$pdf->Cell(0,5,"Cod. Cliente: ".$datosFactura[0]["codigo"].$retener,0,1,'R',false);
	
	//$pdf->Cell(0,5,"Cod. Cliente: ".$datosFactura[0]["codigo"],0,1,'R',false);
	
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$altura = $altura + 5;
	$alturaDireccion = $altura;
	$pdf->SetFont('Arial','U',10);
	//$margen = $pdf->GetPageWidth();
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
	$pdf->MultiCell(0,4,utf8_decode($datosFactura[0]["provincia"]." (".$datosFactura[0]["nombrePais"].")"),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["provincia"]." (".$datosFactura[0]["nombrePais"].")");
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
		$numeroDeFilas = ceil ($anchoDescripcion / 80);
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
	
	
	
	$limiteAlturaDatos = 280;//280
	
	$contadorGenerico=0;
	
	while ($contadorGenerico<$presupuestosAimprimirContador)
	{
		
		
		if ($altura>$limiteAlturaDatos)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$datosFactura,$numFactura, $margenInicial,$datosCliente,$margen);	
		}
		
		
		if ($combinados==1 && $sumatorio != 1) 
		{//echo 'entra<br>Combinados: '.$combinados.'<br>Sumatorio:'.$sumatorio;
			$numPresupuesto = $presupuestosAimprimir[$contadorGenerico]["presupuesto"];
			//$eltitulo = $presupuestosAimprimir[$contadorGenerico]["campana"];
			$datosDetalles = verFacturaDetallePresupuestoClayma($conexion,$numFactura,$numPresupuesto,$anioSeleccionado);
			
			
			$eltitulo = $datosDetalles[0]["campana"];
			
		}
		
		$altura += 10;
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,"OT: ".$numPresupuesto,0,1,'L',false);
		
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



		//$limiteAlturaDatos = 280;
		$alturaSiguientePagina = 25;//50
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
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$datosFactura,$numFactura, $margenInicial,$datosCliente,$margen);	
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
				//$ladescripion=" (".$row["descripcion"].")";	
				$ladescripion=$row["descripcion"];	
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
			
			if ($elConcepto!="" and $elConcepto!=" " and $elConcepto!="  ")
			{
				$elConcepto=$elConcepto.". ";
			}
			
			//$ladescripion = $ladescripion." altura: ".$altura;

			$pdf->SetXY($margen,$altura);		
			//$pdf->MultiCell(110,5,utf8_decode($row["concepto"].$ladescripion),0,'L',true);
			
			$datosDecripcion = $elConcepto.$ladescripion;

			if ($row["exentoIVA"]==true)
			{					
				$datosDecripcion .= " (Exento de IVA)";
			}
		
			$pdf->MultiCell(110,5,($datosDecripcion),0,'L',true);
						
			
			//$pdf->MultiCell(110,5,($elConcepto.$ladescripion),0,'L',true);

			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosDecripcion));
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




			if ($altura>$limiteAlturaDatos)//265
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$datosFactura,$numFactura, $margenInicial,$datosCliente,$margen);	
			}

		}
		
		$contadorGenerico++;
	
	}
	if ($altura>$limiteAlturaDatos)//230
	{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$datosFactura,$numFactura, $margenInicial,$datosCliente,$margen);	
	}
	
	
	//PIE DE PAGINA
	
	$altura = 235;

	$margen=$margenInicial;
	$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	//$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
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
	$pdf->SetFont('Arial','',6);
	//$cuentaBancaria = str_replace(";", "\n", $datosFactura[0]["cuentaDelBanco"]);
	$cuentaBancaria = "ES42 2100 1945 2202 0006 7880 CAIXESBBXXX";
	
	
	//$cuentaBancaria = $datosFactura[0]["cuentaDelBanco"];
	$altura += 10;
	$pdf->SetXY(20,$altura);
	$pdf->MultiCell(95,5,utf8_decode($cuentaBancaria),0,'L',false);
	

	$imprimirIRPF=false;
	if  ($datosFactura[0]["irpf"]!=0.00 && $datosFactura[0]["irpf"]!="0.00" && $datosFactura[0]["irpf"] != "" && $datosFactura[0]["irpf"] != "NULL" )
	{
		$imprimirIRPF = true;
	}

	
	$altura = 240;
	
	$ancho = $pdf->GetPageWidth()-20-70;
	
	$pdf->SetFillColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	$pdf->Rect($ancho, $altura, 70, 27,'F');
	
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);
	
	if  ($imprimirIRPF == false)
	{
		$altura += 4;
	}

	
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
	
	if  ($imprimirIRPF == true)
	{
		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"IRPF 19%:",0,0,'R',false);	
		
		$pdf->SetXY($ancho+35,$altura);	
		$pdf->Cell(20,5,number_format($datosFactura[0]["irpf"],2,',','.')." ".EURO,0,0,'R',false);
		
	}
	
	$altura += 7;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"TOTAL:",0,0,'R',false);
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,5,number_format($datosFactura[0]["precioTotal"],2,',','.')." ".EURO,0,0,'R',false);	
	
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	
	
	//NO BORRAR ESTO	
	/*if (floatval($datosFactura[0]["provision"])>0)
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
	}*/
	
	
	
	
	
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
	
	
	///////////////////////////

	//$verifactu_qrDataUrl = $datosFactura[0]["verifactu_qrCode"];
	/*$verifactu_issueIrsId = $datosFactura[0]["verifactu_issuerIrsId"];
	$verifactu_issuedTime = $datosFactura[0]["fecha"]->format('d-m-Y');
	$verifactu_number = $datosFactura[0]["verifactu_number"];
	$verifactu_hash = $datosFactura[0]["verifactu_hash"];
	$verifactu_url = $datosFactura[0]["verifactu_url"];
	$verifactu_queueId = $datosFactura[0]["verifactu_queueId"];
	$verifactu_requestId = $datosFactura[0]["verifactu_requestId"];
	*/


	//$qrDataUrl = "data:image/png;base64,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";

	//$pdf = new FPDF();
	//$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);

	// Título de factura
	//$pdf->Cell(40,10,'Factura Ejemplo');
	$pdf->Ln(20);

	$verifactu_qrDataUrl = $datosFactura[0]["verifactu_qrcode"];
	//$verifactu_qrDataUrl=""; //solo para pruebas



	
	//	CODIGO QR VERIFACTU
	if (strlen($verifactu_qrDataUrl)>5)
	{
			// -------- QR --------
		if (preg_match('#^data:image/png;base64,#', $verifactu_qrDataUrl)) {
			// 1) Quitar el encabezado "data:image/png;base64,"
			$base64 = explode(',', $verifactu_qrDataUrl, 2)[1];
			// 2) Decodificar a binario
			$pngData = base64_decode($base64);
			// 3) Guardar en fichero temporal
			$tmpFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
			file_put_contents($tmpFile, $pngData);


			// ============================================================
			// === RECORTAR MÁRGENES SEGÚN EL COLOR DEL PÍXEL (0,0) =======
			// ============================================================
			$src = imagecreatefrompng($tmpFile);
			if ($src !== false) {

				imagesavealpha($src, true);

				$w = imagesx($src);
				$h = imagesy($src);

				// Color de fondo = el del píxel de la esquina
				$bg = imagecolorat($src, 0, 0);

				$minX = $w;
				$minY = $h;
				$maxX = 0;
				$maxY = 0;

				for ($y = 0; $y < $h; $y++) {
					for ($x = 0; $x < $w; $x++) {
						if (imagecolorat($src, $x, $y) !== $bg) {
							if ($x < $minX) $minX = $x;
							if ($y < $minY) $minY = $y;
							if ($x > $maxX) $maxX = $x;
							if ($y > $maxY) $maxY = $y;
						}
					}
				}

				// Seguridad: si por lo que sea no encuentra nada distinto, no recortamos
				if ($maxX > $minX && $maxY > $minY) {
					$cropW = $maxX - $minX + 1;
					$cropH = $maxY - $minY + 1;

					$dst = imagecreatetruecolor($cropW, $cropH);

					// Mantener transparencia si la hubiera
					imagealphablending($dst, false);
					imagesavealpha($dst, true);
					$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
					imagefill($dst, 0, 0, $transparent);

					imagecopy($dst, $src, 0, 0, $minX, $minY, $cropW, $cropH);

					// Sobrescribir el fichero temporal con la versión recortada
					imagepng($dst, $tmpFile);

					imagedestroy($dst);
				}

				imagedestroy($src);
			}
			// ============================================================


			// 4) Insertar en el PDF
			// Posición X=150mm, Y=20mm, tamaño=38mm x 38mm (recomendado por AEAT: 30–40 mm)
			$pdf->Image($tmpFile, 82, 238, 32, 32, 'PNG');
			// Texto debajo del QR
			$pdf->SetXY(81, 238+30);
			$pdf->SetFont('Arial','',10);
			// Texto debajo del QR
			$pdf->SetXY(82-3, 238+33);
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(38,5,'QR de pruebas',0,0,'C');
			// 5) Borrar el archivo temporal
			unlink($tmpFile);
		}
	}
	else if ($datosFactura[0]["fecha"]>= fechaCambioVerifactu)	
	{ 
		$precioVerifacturaTotal = $datosFactura[0]["precioTotal"];
		if ($precioVerifacturaTotal==".00") $precioVerifacturaTotal=0;
		require 'phpqrcode/qrlib.php';

		$params = [
			"nif"      => cifClayma,
			"numserie" => $numFactura,
			"fecha"    => $datosFactura[0]["fecha"]->format('d-m-Y'),
			"importe"  => $precioVerifacturaTotal
		];

		$verifactu_qrDataUrl = urlCodigoQrVerifactu . http_build_query($params);	
		
		$tmpFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
		QRcode::png($verifactu_qrDataUrl, $tmpFile, QR_ECLEVEL_L, 4);

		$pdf->Image($tmpFile, 85, 238, 30, 30, 'PNG');
		unlink($tmpFile);

	}	
	else
	{
		
	} 
	
	








	//////////////////////////



	$pdf->Output("I","Factura - ".$numFactura."-".date("dmy")." .pdf","UTF-8");
	
	
}
	
	


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$mostrarPrecio,$datosFactura,$numFactura, $margenInicial,$datosCliente,$margen)
{
	$pdf->AddPage();


	$fechaLimite  = new DateTime(fechaCambioVerifactu);
	
	if (strlen($datosFactura[0]["verifactu_message"])>5 || ($datosFactura[0]["verifactu_idSolicitud"]==NULL && $datosFactura[0]["fecha"]> $fechaLimite ))
	{
		//$pdf->SetTextColor(colorPrevisualizacionR,colorPrevisualizacionG,colorPrevisualizacionB);
		$pdf->SetFont('Arial','B',50);	
		$pdf->SetXY(40,10);
		$pdf->Cell(0,0,utf8_decode("REVISAR FACTURA"),0,1,'C',false);	
		$pdf->SetXY(0,95);
		$pdf->Cell(0,0,utf8_decode("REVISAR FACTURA"),0,1,'C',false);
		$pdf->SetTextColor(0,0,0);
	}



	//$pdf->SetAutoPageBreak(false);
	$altura=10;
	$margen=$margenInicial;
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
		
	$datosPrefactura1="";
	$datosPrefactura2="FACTURA";
		
	if ($datosFactura[0]["laprefactura"]==1)
	{
		$pdf->Cell(35,5,utf8_decode("PREFACTURA Nº"),1,1,'C',false);
		$datosPrefactura1=" PRE";
		$datosPrefactura2="PREFACTURA";
	}
	else
	{
		$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
	}
		
		
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);

	if (strlen($datosFactura[0]["verifactu_message"])==0)
	{
		$pdf->Cell(35,6,$datosFactura[0]["serieFactura"]." ".$numFactura."/".$datosFactura[0]["fecha"]->format('y'),1,1,'C',false);	
	
	}


	//$pdf->Cell(35,6,$numFactura."/".$datosFactura[0]["fecha"]->format('y').$datosPrefactura1,1,1,'C',false);
	//$pdf->Cell(35,6,$numFactura."/".date('y'),1,1,'C',false);
	
	/*$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,"OT ".$datosFactura[0]["presupuesto"],1,1,'C',false);	*/
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	//$pdf->Cell(35,5,"Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y'),0,1,'C',false);
	$pdf->Cell(35,5,"Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y'),0,1,'C',false);
	
	
	
	
	
	
	$margen = $margenInicial;
	
	$altura = $altura + 15;
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,$datosPrefactura2,0,1,'L',false);
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	
	
	//$datosCliente =  cargarClientesClayma($conexion," where codigo_saldo=".$datosFactura[0]["codigo"]." and codigo=".$datosFactura[0]["codigo"]);
	
	$retener = "";
	if ($datosCliente[0]["retener"]==1)
	{
		$retener = " -  R";
	}
	
	$datosCliente=null;
	
	$pdf->Cell(0,5,"Cod. Cliente: ".$datosFactura[0]["codigo"].$retener,0,1,'R',false);
	
	//$pdf->Cell(0,5,"Cod. Cliente: ".$datosFactura[0]["codigo"],0,1,'R',false);
	
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorRojoClaymaR,colorRojoClaymaG,colorRojoClaymaB);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$altura = $altura + 5;
	$alturaDireccion = $altura;
	$pdf->SetFont('Arial','U',10);
	//$margen = $pdf->GetPageWidth();
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
	
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	$altura = $alturaSiguientePagina;
	$margen = 180;
	/*if ($mostrarPrecio==0)
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
	}*/
	$altura=$altura+5;
}



?>