<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccionPortada"])&$_POST["imprimirAccionPortada"]=="imprimirOT")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	
	
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/code128.php");
	/*class PDF extends PDF_PageGroup
	{
		function Footer()
		{
			//$this->SetY(-20);
			//$this->Cell(0, 6, 'Page '.$this->GroupPageNo().'/'.$this->PageGroupAlias(), 0, 0, 'C');
		}
	}*/
	
	
	$numPresupuesto = $_POST["imprimirNumOTPortada"];	
	
	
	//$departamentos = verDepartamentosProcesos($conexion);
	//$departamentosEnDetalles = cargarDetallesPresupuestoDepartamento($conexion,$numPresupuesto);
	$esClayma = verSiOtEsDeClayma($conexion,$numPresupuesto);
	if ($esClayma[0]["clayma"]==1)
	{
		$datosPresupuesto = verPresupuestoParaOtClayma($conexion,$numPresupuesto);
	}
	else
	{
		$datosPresupuesto = verPresupuestoParaOt($conexion,$numPresupuesto);
	}
	
	
	$datosDetalles = cargarDetallesPresupuesto($conexion,$numPresupuesto,"paraOt");
	
	//$departamentosFijos = array("BENJA","SOBRANTE", "ALMACEN","MATERIALES");
	
	$pdf = new PDF('L','mm','A3');
	
	$altura=-1;
	$margen=220;
	$alturaSiguientePagina=15;
	$limiteAlturaDatos = 265;
	$mostrarPrecio=0;
	$numPagina=0;
	
	$altura=0;
	$pdf->StartPageGroup();

	//$pdf->AddPage('L','A3');
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);
	$pdf->AliasNbPages();
	$numPagina=1;

	$pdf->SetFont('Arial','B',30);	
	$altura=$altura + 18;
	$pdf->Text($margen,$altura,"OT ".$datosPresupuesto[0]["presupuesto"]);

	$pdf->SetXY($pdf->GetPageWidth()-60,$altura);
	$pdf->Image('imagenes/Logo Grupo Cibeles.jpg',(($pdf->GetPageWidth())/4)+(($pdf->GetPageWidth())/2)-(27/2),5,27);	


	


	$pdf->SetTextColor(13,140,252);

	$pdf->SetXY($margen+132,$altura);

	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(18,0,utf8_decode("Nº Factura:"),0,1,'L',false);
	
	$pdf->SetXY($margen+150,$altura-13);
	$pdf->SetLineWidth(1);
	$pdf->Cell(40,15,(""),1,1,'L',false);
	$pdf->SetLineWidth(0.2);
		
			
			
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',15);
	$altura=$altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(140,8,utf8_decode($datosPresupuesto[0]["cliente"]),1,'L',false);
			
			
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($pdf->GetPageWidth()-60,$altura);
	$pdf->MultiCell(0,8,utf8_decode($datosPresupuesto[0]["nombre_franqueo"]),1,'R',false);
	//$pdf->MultiCell(0,10,utf8_decode("PRICEWATERHOUSECOOPERS"),1,'R',false);




	$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosPresupuesto[0]["cliente"]));
	$numeroDeFilas = $anchoDescripcion / ((140)-1);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}

	$altura = $altura + (6*$numeroDeFilas);

	/*if ($altura>$limiteAlturaDatos)
	{
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);	
	}*/




	$altura=$altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(0,8,utf8_decode($datosPresupuesto[0]["nombre_empresa"]),0,'L',false);


	$altura = $altura + 10; 
	$pdf->SetFont('Arial','BI',10);
	$pdf->SetTextColor(13,140,252);
	$pdf->SetXY($margen+90,$altura);
	$pdf->Cell(25,0,utf8_decode("F.Inicio:"),0,1,'L',false);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',18);
	$pdf->SetXY($margen+15+90,$altura);
	$pdf->Cell(25,0,utf8_decode($datosPresupuesto[0]["fechaInicioReal"]->format('d/m/Y')),0,1,'L',false);

	$pdf->SetFont('Arial','BI',10);
	$pdf->SetTextColor(13,140,252);
	$pdf->SetXY($margen+143,$altura);
	$pdf->Cell(25,0,utf8_decode("F.Final:"),0,1,'L',false);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',18);
	//$pdf->SetXY($margen+15,$altura);
	$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["fechaTerminado"]->format('d/m/Y')),0,1,'R',false);
			
			
	$altura = $altura + 5; 
	$pdf->SetFont('Arial','B',15);			
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,5,utf8_decode($datosPresupuesto[0]["campana2"]),0,'C',false);



	$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosPresupuesto[0]["campana2"]));
	$numeroDeFilas = $anchoDescripcion / ((140)-1); 
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}

	$altura = $altura + (6*$numeroDeFilas);

	/*if ($altura>$limiteAlturaDatos)
	{
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);	
	}*/

	$altura = $altura + 3; 

	$pdf->SetDrawColor(13,140,252);
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,11,utf8_decode(" "),1,'C',false);
	//$pdf->SetDrawColor(0,0,0);
	$altura = $altura+4; 
	$pdf->SetFont('Arial','I',8);
	$pdf->SetTextColor(13,140,252);

	$pdf->Text($margen+5,$altura,"Presupuesto:");
	$pdf->Text($margen+70,$altura,"Provision de Fondos:");
	$pdf->Text($margen+140,$altura,"Ingreso:");

	$pdf->SetTextColor(0,0,0);
	$pdf->Text($margen+23,$altura,$datosPresupuesto[0]["inicialComercial"]." - ".$datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"]);


	$altura = $altura + 5; 
	$pdf->SetTextColor(13,140,252);
	$pdf->Text($margen+5,$altura,"Contacto:");
	//pdf->Text($margen+70,$altura,utf8_decode("Teléfono:"));
	//$pdf->Text($margen+140,$altura,"Email:");

			
	$pdf->Text($margen+19,$altura,strtoupper(utf8_decode($datosPresupuesto[0]["persona"])));
	
	
	
	$altura = $altura + 3; 

	$pdf->SetDrawColor(13,140,252);
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,8,utf8_decode(" "),1,'C',false);
	
	$pdf->SetFont('Arial','B',8);
	$altura = $altura + 1; 
	$margen=$margen+3;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"INF");
	
	$margen=$margen+20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"Man");
	
	$margen=$margen+21;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"MEC");
	
	$margen=$margen+21;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"Franqueo");
	
	$margen=$margen+28;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"Sobrante");
	
	$margen=$margen+28;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"G.F.");
	
	$margen=$margen+21;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,"Medias");
	
	$margen=$margen+25;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(5,5,"",1,1,'R',false);
	$pdf->Text($margen+6,$altura+4,utf8_decode("Albarán"));
	
	
	$margen=220;
	
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetTextColor(0,0,0);	
	$altura = $altura + 7; 
	
			
			
	$pdf->SetTextColor(0,0,0);		
			//$alturaSiguientePagina=40;
			//$limiteAlturaDatos = 265;
			//PIE DE PAGINA
	ponerPiePagina($pdf);
	
	
	
	
	//detalles
	$altura = $altura+5;
	$departamentoAnterior="asdfjkidj234234";
	foreach ($datosDetalles as $row) 
	{
		if ( $row["departamento"]!=$departamentoAnterior)
		{
			$pdf->SetFont('Arial','B',9);
			$departamentoAnterior = $row["departamento"];
			$altura = $altura+0;
			
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina,$margen);		
			}
			
			$pdf->SetTextColor(13,140,252);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(125,5,utf8_decode(strtoupper($row["departamento"])),0,0,'L',false);
			$altura = $altura+5;
		}
		
		
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0,0,0);
		if ($altura>$limiteAlturaDatos)
		{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina,$margen);	
		}
		
		$unidad=0.000;
		$unidad = number_format($row["unidades2"],3,',','.');
		
		if ($unidad==="0,000")
		{
			$unidad = "";
		}
		else if (substr($unidad,-3)==="000")
		{
			$pos = strpos($unidad, ",");
			$unidad = substr($unidad,0,$pos);
		}
		
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(18,5,$unidad,0,'R',false);
		$pdf->SetXY($margen+20,$altura);
		$pdf->MultiCell(0,5,utf8_decode($row["proceso"]),0,'L',false);
		
		$altura = $altura+5;
		$pdf->SetFont('Arial','',9);
		if ($row["descripcion"]!="")
		{
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina,$margen);	
			}
			
			$pdf->SetXY($margen+25,$altura);	
			
			
			/*$descripion = str_replace('€',EURO,$row["descripcion"]);
			$descripion = str_replace('ñ',ene,$descripion);
			$descripion = str_replace('Ñ',ene_may,$descripion);
			$descripion = str_replace('á',a_acento,$descripion);
			$descripion = str_replace('é',e_acento,$descripion);
			$descripion = str_replace('í',i_acento,$descripion);
			$descripion = str_replace('ó',o_acento,$descripion);
			$descripion = str_replace('ú',u_acento,$descripion);
			$descripion = str_replace('Á',a_acento_may,$descripion);
			$descripion = str_replace('É',e_acento_may,$descripion);
			$descripion = str_replace('Í',i_acento_may,$descripion);
			$descripion = str_replace('Ó',o_acento_may,$descripion);
			$descripion = str_replace('Ú',u_acento_may,$descripion);
			
			$descripion = str_replace('º',signo_grado,$descripion);
			$descripion = str_replace('ª',signo_ordinal,$descripion);
			$descripion = str_replace('%',signo_tantoPorciento,$descripion);
			
			
			$pdf->MultiCell(165,4,$descripion,0,'L',false);*/
			
			$pdf->MultiCell(165,4,reemplazarSimbolos($row["descripcion"]),0,'L',false);
			
			
			

			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["descripcion"]));
			$numeroDeFilas = $anchoDescripcion / ((165)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}

			$altura = $altura + (7*$numeroDeFilas);

			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina,$margen);
			}			

			
			
			
			
		}
		$pdf->SetTextColor(255,0,0);
		if ($row["notaCibeles"]!="")
		{
			
			$pdf->SetXY($margen+30,$altura);	
			
			
			/*$notas2 = str_replace('€',EURO,$row["notaCibeles"]);
			$notas2 = str_replace('ñ',ene,$notas2);
			$notas2 = str_replace('Ñ',ene_may,$notas2);
			$notas2 = str_replace('á',a_acento,$notas2);
			$notas2 = str_replace('é',e_acento,$notas2);
			$notas2 = str_replace('í',i_acento,$notas2);
			$notas2 = str_replace('ó',o_acento,$notas2);
			$notas2 = str_replace('ú',u_acento,$notas2);
			$notas2 = str_replace('Á',a_acento_may,$notas2);
			$notas2 = str_replace('É',e_acento_may,$notas2);
			$notas2 = str_replace('Í',i_acento_may,$notas2);
			$notas2 = str_replace('Ó',o_acento_may,$notas2);
			$notas2 = str_replace('Ú',u_acento_may,$notas2);
			
			$notas2 = str_replace('º',signo_grado,$notas2);
			$notas2 = str_replace('ª',signo_ordinal,$notas2);
			$notas2 = str_replace('%',signo_tantoPorciento,$notas2);
			
			
			
			$pdf->MultiCell(160,4,$notas2 ,0,'L',false);*/
			
			
			$pdf->MultiCell(165,4,reemplazarSimbolos($row["notaCibeles"]),0,'L',false);
			
			
			
			$anchoDescripcion = $pdf->GetStringWidth(reemplazarSimbolos($row["notaCibeles"]));
			$numeroDeFilas = $anchoDescripcion / ((160)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}

			$altura = $altura + (7*$numeroDeFilas);

			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina,$margen);
			}	
			
			
			
			
			
			
		}
		$pdf->SetTextColor(0,0,0);
		
		//el codigo de barras
		$pdf->SetXY($margen+100,$altura);		
		//$pdf->MultiCell(125-10,4,utf8_decode($row["id"]."-".$row["presupuesto"]),0,'L',false);
		
		
		
	}
			
			
	
	
	
	
	
	$pdf->Output("I",$ruta."OT - ".date("dmy")." .pdf","UTF-8");
	
	
	

	
	
}
else
{
	
}











function ponerPiePagina(&$pdf)
{	
	
	$margen=220;
	$pdf->SetTextColor(13,140,252);	
	$altura = 265+7;
	$pdf->SetFont('Arial','BI',8);
	
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Unidades Reales:  ___________________________________________________",0,0,'R',false);
	//$pdf->Text($margen,$altura,"Unidades Reales:  ___________________________________________________");
	
	
	$pdf->SetLineWidth(1);
	$ancho=25;
	$espacioEntreRect=2.5;	
	$altura = $altura+3;
	$pdf->Rect($margen, $altura, $ancho, 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*1), $altura, $ancho, 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*2), $altura, $ancho , 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*3), $altura, $ancho , 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*4), $altura, $ancho , 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*5), $altura, $ancho , 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*6), $altura, $ancho , 10,'D');
	$pdf->SetLineWidth(0.2);
	
	$altura = $altura+3;
	$pdf->SetFont('Arial','BI',7);	
	$pdf->Text($margen+1,$altura,"Franqueo Pagado");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*1),$altura,"Tipsa");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*2),$altura,"Paq Correos");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*3),$altura,"Nuestros Medios");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*4),$altura,"Franqueo Maquina");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*5),$altura,"Importe Franqueo");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*6),$altura,"Recoge Cliente");
	
	//$pdf->SetXY($margen,$altura);	
	//$pdf->MultiCell(0,5,"asdfdsa",1,'L',false);
	
	$altura = $altura+11;
	//$margen=10;	
	$pdf->SetFont('Arial','BI',7);
				
	$pdf->Text($margen,$altura,date('d/m/Y H:i:s'));
	
	$pdf->SetXY($margen+140,$altura);
	$pdf->Cell(0,0,"FR 09 01 02",0,0,'R',false);
	
	//$altura = $altura+5;
	
	//$paginaActual= $pdf->GroupPageNo();
	//$paginasTotal = intval($pdf->PageGroupAlias());
			//$paginasTotal = $paginasTotal/ 2;
	//$pdf->Text($margen,290,$paginaActual.'/'.$paginasTotal);
	//$pdf->Text($margen,290,ceil($pdf->GroupPageNo()/2).'/'.$pdf->PageGroupAlias());
		
	//$pdf->Text($margen,290,$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias());
	
	
}


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$mostrarPrecio,&$numPagina,&$margen)
{
	if ($numPagina==1)
	{	
		$pdf->AddPage();
		$margen=10;
	}
	else
	{
		$margen=220;
	}


	$numPagina++;
	//pdf->AliasNbPages(false);
	//$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
	//$pdf->SetAutoPageBreak(false);
	$altura = $alturaSiguientePagina;
	/*if ($mostrarPrecio==0)
	{		
		$pdf->SetXY(180,$altura);
		$pdf->Cell(20,0,"UNIDADES",0,0,'R',false);
	}
	else
	{		
		$pdf->SetXY(140,$altura);
		$pdf->Cell(20,0,"UNIDADES",0,0,'R',false);

		$pdf->SetXY(160,$altura);
		$pdf->Cell(20,0,"PRECIO",0,0,'R',false);

		$pdf->SetXY(180,$altura);
		$pdf->Cell(20,0,"TOTAL",0,0,'R',false);
		
	}*/
	$altura=$altura+5;
	
	
}




?>

	
	
	
	
	
		


