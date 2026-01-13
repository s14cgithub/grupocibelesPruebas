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
	require($ruta."FPDI-2.3.6/src/autoload.php");
	
	//
	//
	
	require($ruta."FPDI-2.3.6/src/Fpdi.php");
	//require($ruta."Archivos Comunes/cabeceraPieFactura3.php");

	
	$margenInicial=20;
	$margen=$margenInicial;
	$altura=10;	
	
	
	
	$pdf = new \setasign\Fpdi\Fpdi();
	//$pdf = new cabeceraFactura('P','mm','A4');
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	$pdf->SetAutoPageBreak(false);	
	nuevaPagina($pdf,$altura,$margenInicial);
		
		
		
		
		
		$margen=$margenInicial;
		
		
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.8);
	
	$altura=10;	

		$margen += 135;
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(35,5,utf8_decode("FACTURA Nº"),1,1,'C',false);
	
	
	nuevaPagina($pdf,$altura,$margenInicial);
	$margen=$margenInicial;
	$altura=10;	
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,utf8_decode("PRUEBA"),1,1,'C',false);
	
	
	$pdfEnvio = $pdf->importPage(1);
	
	
	$pdf->Output("I","Factura -".date("dmy")." .pdf","UTF-8");
	
}


function nuevaPagina(&$pdf,&$altura,&$margenInicial)
{
	$pdf->AddPage();
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	//$pdf->Text($margen,$altura,$datosPresupuesto[0]["fecha"]->format('d/m/Y'));

	//$pdf->SetTextColor(13,140,252);

	$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);


	$pdf->SetFont('Arial','',6);
	
	$altura = 280;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);

	$altura += 2; 
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margenInicial,$altura);	


	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);

	$pdf->MultiCell(0,4,utf8_decode('C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07 Cibeles Mailing S.A. A-81339186'),0,'C',false);


	$altura -= 5; 
	
}
	
	




?>
	
	
	
	
	
		


	