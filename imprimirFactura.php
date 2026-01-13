<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirFactura")
{
	$ruta = '/';
	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFacturaGroupPag.php");
	
		
	$anioSeleccionado = isset($_POST["anioSeleccionado0"]) ? $_POST["anioSeleccionado0"] : 0;
	if ($anioSeleccionado==0 || $anioSeleccionado=="0")
	{
		$anioSeleccionado = $_POST["anioSeleccionado1"];
	}
	
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
	
	/*
	echo ("<br>numeroFactura: ".$numFactura);
	echo ("<br>año: ".$anioSeleccionado);
	echo ("<br>/////////-----------------------///////////");
*/
	$datosFactura = verFactura($conexion,$numFactura,$anioSeleccionado);
		
	
	//echo $contadorMax."<br>";
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
		$datosDetalles = verFacturaDetalleSumatorio($conexion,$numFactura,$anioSeleccionado); 
		$numPresupuesto = $datosFactura[0]["presupuesto"];
		$eltitulo="";
	}
	else if ($combinados==0)
	{
		$datosDetalles = verFacturaDetalle($conexion,$numFactura,$anioSeleccionado);
		$presupuestosAimprimir = $datosFactura[0]["presupuesto"];
		$eltitulo=$datosFactura[0]["descripcion"];
	}	
	else
	{
		$presupuestosAimprimir = verNumPresupuestosCombinados($conexion,$numFactura,$anioSeleccionado);		
		$eltitulo="";
		$presupuestosAimprimirContador = count($presupuestosAimprimir);
		$numPresupuesto="";
	}
		
		$pos = strpos($numPresupuesto,"_V");
		if ($pos === false) 
		{
    		//echo "La cadena '$findme' no fue encontrada en la cadena '$mystring'";
		} 
		else 
		{
			$numPresupuesto = substr($numPresupuesto,0,$pos);
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
	
	
	//$datosPrefactura = $datosFactura[0]["laprefactura"];
	$datosPrefactura = 0;
		
	if ($datosPrefactura==1)
	{		
		$datosNuevaPagina1 = $numFactura."/".$datosFactura[0]["fecha"]->format('y'). " PRE";		
	}
	else
	{
		//$datosNuevaPagina1 = $numFactura."/".$datosFactura[0]["fecha"]->format('y'). " MA";
		$datosNuevaPagina1 = $datosFactura[0]["serieFactura"]." ".$numFactura."/".$datosFactura[0]["fecha"]->format('y');
	}
	
	$datosNuevaPagina2 = "Fecha: ".$datosFactura[0]["fecha"]->format('d/m/Y');
	
		
		
	
	$limiteAlturaDatos = 260;//280
	$alturaSiguientePagina = 20;//50
	
	
	$datosCliente =  cargarClientes($conexion," where codigo_saldo=".$datosFactura[0]["codigo"]." and codigo=".$datosFactura[0]["codigo"]);
	
	$retener = "";
		
		
	//echo $datosFactura[0]["codigo"];
		
		
		
	if ($datosCliente[0]["retener"]==1)
	{
		$retener = " -  R";
	}
	
	
	$datosCliente=null;
		
	nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosFactura,$retener,$margenInicial,$datosPrefactura);	           
		
	
	
	
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
	
	
	
	
	if ($datosFactura[0]["observaciones"]!="" && $datosFactura[0]["observaciones"]!="NINGUNO" ) 
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
		
		
	}
	
	
	
	$importeCombinadoPorOt=0.00;
	$primevaVezImporteCombinadoPorOt=true;
	
	$contadorGenerico=0;
	
		
	while ($contadorGenerico<$presupuestosAimprimirContador)
	{		
		
		if ($altura>190)//190
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosFactura,$retener,$margenInicial,$datosPrefactura);
		}
		
		
		if ($combinados==1 && $sumatorio != 1) 
		{ 
			$numPresupuesto = $presupuestosAimprimir[$contadorGenerico]["presupuesto"];
			
			
			$datosDetalles = verFacturaDetallePresupuesto($conexion,$numFactura,$numPresupuesto,$anioSeleccionado);
			
			$eltitulo = $datosDetalles[0]["campana"];
			
			
			//IMPRIMIR TOTAL DE OT
			/*if ($primevaVezImporteCombinadoPorOt==true)
			{
				$primevaVezImporteCombinadoPorOt=false;
			}
			else
			{
				$altura += 5;
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY($margen,$altura);		
				
				$pdf->Cell(0,5,"SubTotal: ".$importeCombinadoPorOt,0,1,'R',false);
					
				$importeCombinadoPorOt=0.00;
				$altura += 5;
			}*/
			
			
			
			
		}
		else
		{
			//echo ' no entra<br>Combinados: '.$combinados.'<br>Sumatorio:'.$sumatorio;
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
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosFactura,$retener,$margenInicial,$datosPrefactura);
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
				$ladescripion = str_replace('°',signo_grado,$ladescripion);
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
			$elConcepto = str_replace('°',signo_grado,$elConcepto);
			$elConcepto = str_replace('ª',signo_ordinal,$elConcepto);
			
			if ($elConcepto!="" and $elConcepto!=" " and $elConcepto!="  ")
			{
				$elConcepto=$elConcepto.". ";
			}

			//$ladescripion = $ladescripion." altura: ".$altura;

			
			if (($datosFactura[0]["codigo"]==40 || $datosFactura[0]["codigo"]==3110)   && strpos($elConcepto.$ladescripion,'..') )
			{
				$datitos=explode('..',$elConcepto.$ladescripion);
				for ($cont1=0;$cont1<count($datitos)-1;$cont1++)
				{
					$pdf->SetXY($margen,$altura);	
					$pdf->MultiCell(110,5,($datitos[$cont1]),0,'L',true);

					if ($cont1==0)
					{
						$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["concepto"].$ladescripion));
						$numeroDeFilas = ceil ($anchoDescripcion / (109));
						if ($numeroDeFilas<1)
						{
							$numeroDeFilas = 1;
						}	
						$altura = $altura + (5*$numeroDeFilas);
						$altura = $altura - 5;
					}
					else
					{
						$altura += 5;
					}
				}
				
			}
			else
			{ 

				$datosDecripcion = $elConcepto.$ladescripion;

				if ($row["exentoIVA"]==true)
				{					
					$datosDecripcion .= " (Exento de IVA)";
				}

				$pdf->SetXY($margen,$altura);		
			
				$pdf->MultiCell(110,5,($datosDecripcion),0,'L',true);

				

				$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosDecripcion));
				$numeroDeFilas = ceil ($anchoDescripcion / (109));
				if ($numeroDeFilas<1)
				{
					$numeroDeFilas = 1;
				}	
				$altura = $altura + (5*$numeroDeFilas);
				$altura = $altura - 5;
			}
			
			/**/
			
			
			///////////////////////////////
			
			///////////////////////////////////////
			

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




			if ($altura>$limiteAlturaDatos)//265
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosFactura,$retener,$margenInicial,$datosPrefactura);	
			}

		}
		
		if ($combinados==1)
		{
			$altura += 5;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);		

			$pdf->Cell(0,5,"SubTotal: ".number_format($importeCombinadoPorOt,2,',','.')." ".EURO,0,1,'R',false);

			$importeCombinadoPorOt=0.00;
			$altura += 5;
		}

		
		$contadorGenerico++;
	
	}
		
	/*if ($combinados==1)
	{
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);		

		$pdf->Cell(0,5,"SubTotal: ".$importeCombinadoPorOt,0,1,'R',false);

		$importeCombinadoPorOt=0.00;
		$altura += 5;
	}*/
		
		
	if ($altura>230)//230$limiteAlturaDatos
	{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosFactura,$retener,$margenInicial,$datosPrefactura);
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
	$pdf->SetFont('Arial','',6);
	
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
	
	$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
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
	
/*
	$valorExentoIva = (isset($datosFactura[0]) && array_key_exists('precioNetoExentoIva', $datosFactura[0]))? $datosFactura[0]['precioNetoExentoIva']: null;
	if ($valorExentoIva !== null && is_numeric($valorExentoIva) && (float)$valorExentoIva > 0)
	{
		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Exento IVA:",0,0,'R',false);	
		
		$pdf->SetXY($ancho+35,$altura);	
		$pdf->Cell(20,5,number_format($datosFactura[0]["precioNetoExentoIva"],2,',','.')." ".EURO,0,0,'R',false);
		
	}
	*/
	
	
	if  ($imprimirIRPF == true)
	{
		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"IRPF 19%:",0,0,'R',false);	
		
		$pdf->SetXY($ancho+35,$altura);	
		$pdf->Cell(20,5,number_format($datosFactura[0]["irpf"],2,',','.')." ".EURO,0,0,'R',false);
		
	}
	




	//$pdf->SetXY($ancho+35,$altura);		
	//$pdf->Cell(20,5,number_format($datosFactura[0]["iva"],2,',','.')." ".EURO,1,0,'L',false);	
	
	$altura += 7;
	$pdf->SetXY($ancho,$altura);			
	$pdf->Cell(20,5,"TOTAL:",0,0,'R',false);
	
	$pdf->SetXY($ancho+35,$altura);			
	$pdf->Cell(20,5,number_format($datosFactura[0]["precioTotal"],2,',','.')." ".EURO,0,0,'R',false);	
	
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	
		
		//NO BORRAR ESTO
	if (floatval($datosFactura[0]["provision"])>0)
	{
		if  ($imprimirIRPF != true)
		{
			$altura += 3;
		}
		$altura += 7;
		$pdf->SetXY($ancho,$altura);			
		$pdf->Cell(20,5,"Provision de Fondo:",0,0,'R',false);

		$pdf->SetXY($ancho+35,$altura);			
		$pdf->Cell(20,5,number_format($datosFactura[0]["provision"],2,',','.')." ".EURO,0,0,'R',false);	

		$altura += 5;
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
			"nif"      => cifCibeles,
			"numserie" => $datosNuevaPagina1,
			"fecha"    => $datosFactura[0]["fecha"]->format('d-m-Y'),
			"importe"  => $precioVerifacturaTotal
		];

		$verifactu_qrDataUrl = urlCodigoQrVerifactu . http_build_query($params);	
		
		$tmpFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
		QRcode::png($verifactu_qrDataUrl, $tmpFile, QR_ECLEVEL_L, 4);

		$pdf->Image($tmpFile, 85, 238, 30, 30, 'PNG');

		// Texto debajo del QR
		$pdf->SetXY(81, 238+30);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(38,5,'QR de pruebas',0,0,'C');

		unlink($tmpFile);

		
		//$pdf->SetXY(81, 238+30);
		//$pdf->SetFont('Arial','',10);
		
		
	} 
	else
	{
		
	} 









	//////////////////////////


	
	$pdf->Output("I","Factura - ".$numFactura."-".date("dmy")." .pdf","UTF-8");
	

}
	
	


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$datosNuevaPagina1,$datosNuevaPagina2,$datosFactura,$retener,$margenInicial,&$datosPrefactura)
{
	$pdf->AddPage();
	
	$fechaLimite  = new DateTime(fechaCambioVerifactu);
	
	
	$pdf->SetFont('Arial','B',50);	
	$pdf->SetXY(40,30);
	//$pdf->Cell(0,0,$datosFactura[0]["fecha"]>= fechaCambioVerifactu."||||||".$datosFactura[0]["fecha"]->format('d/m/Y')."||||||".$fechaLimite,0,1,'C',false);
	$fechaFactura111 = $datosFactura[0]["fecha"]; 

	$esMayor = ($fechaFactura111 > $fechaLimite) ? "SI" : "NO";
	//$pdf->Cell(0,0,$esMayor,0,1,'C',false);
	
	
	if (strlen($datosFactura[0]["verifactu_message"])>5 || ($datosFactura[0]["verifactu_idSolicitud"]==NULL && $esMayor=="SI" ))
	
	{
		
		//$pdf->SetTextColor(colorPrevisualizacionR,colorPrevisualizacionG,colorPrevisualizacionB);
		$pdf->SetFont('Arial','B',50);	
		$pdf->SetXY(40,10);
		$pdf->Cell(0,0,utf8_decode("REVISAR FACTURA"),0,1,'C',false);	
		$pdf->SetXY(0,95);
		$pdf->Cell(0,0,utf8_decode("REVISAR FACTURA"),0,1,'C',false);
		$pdf->SetTextColor(0,0,0);
	}



	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	//$altura = $altura + 0;
	$altura = 6;//10
	$margen = 20;
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	
	$datosPrefactura=0;
	if ($datosPrefactura===1)
	{
		$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
		$pdf->Cell(35,5,utf8_decode("PREFACTURA Nº"),1,1,'C',false);
		$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
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
		$pdf->Cell(35,6,$datosNuevaPagina1,1,1,'C',false);	
	}
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,$datosNuevaPagina2,0,1,'C',false);
	
	$margen = $margenInicial;
	$altura = 35;
	
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	
	if ($datosPrefactura==1)
	{
		$pdf->Cell(0,0,"PREFACTURA",0,1,'L',false);
	}
	else
	{
		$pdf->Cell(0,0,"FACTURA",0,1,'L',false);		
	}
	
	
	
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);	
	
	
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
	$numeroDeFilas = ceil ($anchoDescripcion / 80);//84
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
	$anchoDescripcion = $pdf->GetStringWidth($datosFactura[0]["provincia"]."(".$datosFactura[0]["nombrePais"].")");
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
	
	
	
	//$altura = $alturaSiguientePagina;
	$margen = 180;
	
	$altura=$altura+5;
}



?>

	
	
	
	
	
		


