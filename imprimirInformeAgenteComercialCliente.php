<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&&$_POST["imprimirAccion"]=="clientesAgenteComercial")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	
	
	
	
	$FacPendientes_CibelesCorreos  = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='cibeles' and cliente = 'SOCIEDAD ESTATAL CORREOS Y TELEGRAFOS, S.A.'");
	
	$FacPendientes_CibelesCibLogistic  = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='cibeles' and cliente = 'CIBELES LOGISTICS, S.L.'");
	
	$FacPendientes_CibelesCibHerrajardin  = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='cibeles' and cliente = 'HERRAJARDIN S.L.'");
	
	$FacPendientes_CibelesCibHermes  = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='cibeles' and cliente = 'HERMES SOBRES, S.A.'");
	
	$FacPendientes_CibelesResto = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='cibeles' and cliente != 'SOCIEDAD ESTATAL CORREOS Y TELEGRAFOS, S.A.' and cliente != 'CIBELES LOGISTICS, S.L.' and cliente != 'HERRAJARDIN S.L.' and cliente != 'HERMES SOBRES, S.A.'");
	
	$FacPendientes_Correos = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='correos'");
	
	$FacPendientes_ClaymaCibLogistic = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='clayma' and cliente ='CIBELES LOGISTICS, S.L.'");
	
	$FacPendientes_ClaymaHermes = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='clayma' and cliente ='HERMES SOBRES, S.A.'");
	
	$FacPendientes_ClaymaResto = mostrarListadoFacturasPendientesTotal_Sumatorio($conexion, " where origen2='clayma' and cliente !='CIBELES LOGISTICS, S.L.' and cliente !='HERMES SOBRES, S.A.'");
	
	//$FacPendientes_CibelesCorreos[0]["aPagar"]
	
	
	$saldosYfijas = mostrarListadoSaldosSumatorio($conexion, " where  fac_idProvisionFondos=1 and fac_pfFijaImporte>0 and activo=1");
	
	
	
		

	$pdf = new PDF_PageGroup('P','mm','A4');
	$alturaSiguientePagina = 35;
	$margenInicial=20;
	//$pdf->SetXY(-10,-5);

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	$pdf->StartPageGroup();
	
	nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
	//$pdf->addPage();
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	






	$margenInicial = 20;
	$margen=$margenInicial;

	$altura=15;
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0, diaDeLaSemanaActual().date(", d")." de ".mesActual()." del ". date("Y"),0,1,'R',false);
	


	$altura += 25;


	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',16);
	$pdf->SetXY($margen,$altura);


	$pdf->Cell(0,0,"SITUACION GRUPO CIBELES",0,1,'C',false);
	
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',12);
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Cibeles - Manipulados",0,1,'L',false);
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',10);
	$altura += 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Correos",0,1,'L',false);
	
	$importeCibelesCorreos=0;
	if (count($FacPendientes_CibelesCorreos)>0)
	{
		$importeCibelesCorreos = $FacPendientes_CibelesCorreos[0]["aPagar"];
	}
	
	$pdf->Cell(0,0,number_format($importeCibelesCorreos,2,',','.')." ".EURO,0,1,'R',false);
	
	
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Cibeles Logistic",0,1,'L',false);
	
	$importeCibelesCibLogistic=0;
	if (count($FacPendientes_CibelesCibLogistic)>0)
	{
		$importeCibelesCibLogistic = $FacPendientes_CibelesCibLogistic[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeCibelesCibLogistic,2,',','.')." ".EURO,0,1,'R',false);
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Herrajardin",0,1,'L',false);
	
	$importeCibelesCibHerrajardin=0;
	if (count($FacPendientes_CibelesCibHerrajardin)>0)
	{
		$importeCibelesCibHerrajardin = $FacPendientes_CibelesCibHerrajardin[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeCibelesCibHerrajardin,2,',','.')." ".EURO,0,1,'R',false);
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Hermes Sobres",0,1,'L',false);
	
	$importeCibelesCibHermesSobres=0;
	if (count($FacPendientes_CibelesCibHermes)>0)
	{
		$importeCibelesCibHermesSobres = $FacPendientes_CibelesCibHermes[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeCibelesCibHermesSobres,2,',','.')." ".EURO,0,1,'R',false);
	
	
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes Resto Clientes",0,1,'L',false);
	
	$importeCibelesResto=0;
	if (count($FacPendientes_CibelesResto)>0)
	{
		$importeCibelesResto = $FacPendientes_CibelesResto[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeCibelesResto,2,',','.')." ".EURO,0,1,'R',false);
	
	
	$altura += 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.2);
	$pdf->Line($margen+140, $altura, 190, $altura);
	
	$totalCibelesManipulados = $importeCibelesCorreos+$importeCibelesResto+$importeCibelesCibLogistic+$importeCibelesCibHerrajardin+$importeCibelesCibHermesSobres;
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,number_format($totalCibelesManipulados,2,',','.')." ".EURO,0,1,'R',false);
	
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',12);
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Clayma - Manipulados",0,1,'L',false);
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',10);
	
	
	$altura += 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Cibeles Logistic",0,1,'L',false);
	$importeClaymaCibLogistic=0;
	if (count($FacPendientes_ClaymaCibLogistic)>0)
	{
		$importeClaymaCibLogistic = $FacPendientes_ClaymaCibLogistic[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeClaymaCibLogistic,2,',','.')." ".EURO,0,1,'R',false);
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Hermes Sobres",0,1,'L',false);
	$importeClaymaHermes=0;
	if (count($FacPendientes_ClaymaHermes)>0)
	{
		$importeClaymaHermes = $FacPendientes_ClaymaHermes[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeClaymaHermes,2,',','.')." ".EURO,0,1,'R',false);
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Resto Clientes",0,1,'L',false);
	$importeClaymaResto=0;
	if (count($FacPendientes_ClaymaResto)>0)
	{
		$importeClaymaResto = $FacPendientes_ClaymaResto[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeClaymaResto,2,',','.')." ".EURO,0,1,'R',false);
	
	$altura += 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.2);
	$pdf->Line($margen+140, $altura, 190, $altura);
	
	$totaClaymaManipulados = $importeClaymaCibLogistic+$importeClaymaHermes+$importeClaymaResto;
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,number_format($totaClaymaManipulados,2,',','.')." ".EURO,0,1,'R',false);
	
	
	$pdf->SetFont('Arial','B',10);
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total Manipulados: ".number_format($totaClaymaManipulados + $totalCibelesManipulados,2,',','.')." ".EURO,0,1,'R',false);
	
	
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',12);
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("Franqueo en Máquina"),0,1,'L',false);
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',10);
	
	
	$altura += 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Facturas Pendientes de Correos",0,1,'L',false);
	$importeCorreos=0;
	if (count($FacPendientes_Correos)>0)
	{
		$importeCorreos = $FacPendientes_Correos[0]["aPagar"];
	}
	$pdf->Cell(0,0,number_format($importeCorreos,2,',','.')." ".EURO,0,1,'R',false);
	
	
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Provisiones de Fondos Fijas",0,1,'L',false);
	$importeCorreosPfF=0;
	if (count($saldosYfijas)>0)
	{
		$importeCorreosPfF = $saldosYfijas[0]["importeFijoTotal"];
	}
	$pdf->Cell(0,0,number_format($importeCorreosPfF,2,',','.')." ".EURO,0,1,'R',false);
	
	$altura += 05;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Saldo",0,1,'L',false);
	$importeSaldo=0;
	if (count($saldosYfijas)>0)
	{
		$importeSaldo = $saldosYfijas[0]["saldoTotal"];
	}
	$pdf->Cell(0,0,number_format($importeSaldo,2,',','.')." ".EURO,0,1,'R',false);
	
	$totalCuentaTexto = "Total Cuenta: ";
	
	if ($importeSaldo-$importeCorreos>0)
	{
		$totalCuentaTexto .= "Saldo a Favor del Cliente";
	}
	else if ($importeSaldo-$importeCorreos<0)
	{
		$totalCuentaTexto .= "Saldo a Favor de Cibeles Mailing";
	}
	
	
	$pdf->SetFont('Arial','B',10);
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,$totalCuentaTexto.": ".number_format($importeSaldo-$importeCorreos,2,',','.')." ".EURO,0,1,'R',false);


	








	$pdf->Output("I","Informe Franqueo Clientes".date("dmy")." .pdf","UTF-8");
	
	
}

function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$margenInicial)
{		
			
	$pdf->AddPage();
	$pdf->AliasNbPages();
	
	$pdf->SetAutoPageBreak(false);
	$margen=20;
	$altura=10;
	
	$pdf->SetXY($margen,$altura);
	$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);
	
	
	$margen = $margenInicial;
	
	
	$altura = 280;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$margenLineaDerecha=190;
	$pdf->Line($margen, $altura, $margenLineaDerecha, $altura);

	$altura += 2; 
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margenInicial,$altura);	


	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);

	$pdf->MultiCell(0,4,utf8_decode('C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07 Cibeles Mailing S.A. A-81339186'),0,'C',false);

	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);	
	$pdf->SetFont('Arial','',6);
	$altura -= 7; 	
	$pdf->SetXY($margenInicial,$altura);	
	
	$pdf->Cell(0,5,utf8_decode('Página ').$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias(),0,0,'C');
	
	$altura = $alturaSiguientePagina;	

}
	
	






?>
	
	
	
	
	
		


