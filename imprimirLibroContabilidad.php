<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="libroConta")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	//require($ruta."Archivos Comunes/pagegroup.php");
	//require($ruta."Archivos Comunes/rotate.php");
	//require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	
	$fechaInicio = $_POST["fechaInicio"];	
	$fechaFin = $_POST["fechaFin"];
	
	
	
	
	
	$pdf = new FPDF('P','mm','A4');

	//$pdf->SetXY(-10,-5);

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	//$pdf->AddPage();
		
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	
	
	$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 0;
	
	
	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,utf8_decode("Listado de Facturación Cibeles Mailing"),0,1,'L',false);
	
	$altura = $altura + 10;
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen,$altura);
	
	$pdf->Cell(35,5,utf8_decode("Día del listado: ".date('d/m/Y')),0,1,'L',false);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,utf8_decode("Facturas del día ".$fechaInicio." al ".$fechaFin),0,1,'R',false);
	$altura = $altura + 10;
	
	
	
	
	
	
	$contador=0;
	//$alturaSiguientePagina=50;
	$limiteAlturaDatos=260;
	$datosFactura = verLibroContabilidad($conexion,$fechaInicio, $fechaFin);	
	
	nuevaPagina($pdf,$altura,$margen,$margenInicial);
	
	
	foreach ($datosFactura as $row) 
	{
		//$pdf->Cell(35,5,$contador,0,1,'L',false);
		$contador++;
		$pdf->SetFont('Arial','',8);
		$altura +=5;
		//$pdf->Cell(125-5,5,$row["proceso"],1,0,'L',false);
		
		if ($altura>$limiteAlturaDatos)
		{
			$pdf->AddPage();
			nuevaPagina($pdf,$altura,$margen,$margenInicial);
		}
		
		
		if ($contador%2==0)
		{			
			$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
		}
		else
		{
			$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);
		}
		
		
		$margen = $margenInicial;
		/*$pdf->SetXY($margen,$altura);
		$pdf->Cell(35,5,utf8_decode($row["numero"]),0,1,'L',false);*/

		//$altura += 5;
		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(10,5,($row["numero"].$row["serie"]),0,1,'R',true);

		$margen += 10;
		$pdf->SetXY($margen,$altura);
		$empresa = $row["codigo"]." ".$row["cliente"];
		$empresa = substr($empresa,0,30);
		$pdf->Cell(50,5,utf8_decode($empresa),0,0,'L',true);


		$margen += 50;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(15,5,$row["fecha"]->format('d/m/Y'),0,0,'C',true);
		$margen += 15;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(21,5,number_format($row["aPagar"],2,',','.')." ".EURO,0,0,'R',true);

		$margen += 21;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(18,5,number_format($row["precioNeto"],2,',','.')." ".EURO,0,1,'R',true);
		$margen += 18;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(18,5,number_format($row["iva"],2,',','.')." ".EURO,0,1,'R',true);
		$margen += 18;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(22,5,number_format($row["precioTotal"],2,',','.')." ".EURO,0,1,'R',true);
		
		$margen += 22;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(16,5,$row["abono"],0,1,'R',true);
		
		
	}
	
	
	
	
	
	
	
	$pdf->Output("I",date("dmy")." .pdf","UTF-8");
	
	
}
	
	


function nuevaPagina(&$pdf,&$altura,&$margen,$margenInicial)
{
	
	$margen=$margenInicial;
	$altura = 30;
	
	//$margen += 20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(10,5,utf8_decode("Nº Fac"),"B",1,'C',false);
	
	$margen += 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(50,5,utf8_decode("Empresa"),'B',1,'C',false);
	
	
	$margen += 50;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(15,5,utf8_decode("Fecha"),'B',1,'C',false);
	$margen += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(23,5,utf8_decode("PagoCuenta"),'B',1,'C',false);
	
	$margen += 21;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(18,5,utf8_decode("Neto"),'B',1,'R',false);
	$margen += 18;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(18,5,utf8_decode("IVA"),'B',1,'R',false);
	$margen += 18;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(22,5,utf8_decode("Total"),'B',1,'R',false);
	
	$margen += 22;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(16,5,utf8_decode("Abo."),'B',1,'R',false);
	
	
	
	/*$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	
	$margen=$margenInicial;
	$pdf->Line($margen, $altura, $margen, $altura+5);
	
	$margen+=20;//EMPRESA
	$pdf->Line($margen, $altura, $margen, $altura+5);
	
	$margen+=50;//FECHA
	$pdf->Line($margen, $altura, $margen, $altura+5);
	
	$margen+=15;//pago a cuenta
	$pdf->Line($margen, $altura, $margen, $altura+5);
	
	$margen+=21;
	$pdf->Line($margen, $altura, $margen, $altura+5);
	
	$margen+=21;
	$pdf->Line($margen, $altura, $margen, $altura+5);
	$margen+=21;
	$pdf->Line($margen, $altura, $margen, $altura+5);
	
	
	$margen=$pdf->GetPageWidth()-20;
	$pdf->Line($margen, $altura, $margen, $altura+5);*/
}
	
	



?>
	
	
	
	
	
	
		


