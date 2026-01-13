<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirInformeCertificado")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	
	$idCliente = $_POST["imprimirIdCliente"];	
	$fechaInicio = $_POST["imprimirFechaInicio"];	
	$fechaFin = $_POST["imprimirFechaFin"];	
	
	$datosCertificados = verCertificadosGrabados($conexion,$idCliente,$fechaInicio,$fechaFin);
	
	
	$nombreCliente = $datosCertificados[0]["subcliente"];
	
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
	
	
	//$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	//$pdf->SetLineWidth(0.8);
	
	
	
	$altura = $altura + 5;
	$altura = $altura + 8;
	$altura = $altura + 15;
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',16);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"DETALLE GESTION CERTIFICADOS Y PAQUETES",0,1,'C',false);
	
	
	$altura += 10;
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($nombreCliente),0,1,'L',false);
	
	
	
	$altura += 10;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Fecha",0,1,'L',false);
	
	$margen+=30;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Unidades",0,1,'L',false);
	$margen+=30;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("Descripción"),0,1,'L',false);
	$margen+=50;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Importe Unitario",0,1,'L',false);
	$margen = $margenInicial;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Importe Total",0,1,'R',false);
	
	$margen = $margenInicial;
	$pdf->SetTextColor(0,0,0);
	
		
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.5);
	$pdf->Line($margen, $altura, 190, $altura);
	
	
	$contador2=0;
	$contador=0;
	$total=0.000;
	$alturaSiguientePagina = 20;
	while ($contador<count($datosCertificados))	
	{		
		
		if ($altura>260)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);	
		}
		$margen = $margenInicial;
	
		//echo $datosCertificados[$contador]["fecha"]["date"];
		$altura += 10;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,$datosCertificados[$contador]["fecha"]->format('d/m/Y'),0,1,'R',false);
		
		
		$margen+=30;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(10,0,$datosCertificados[$contador]["unidades"],0,1,'R',false);
		$margen+=30;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosCertificados[$contador]["producto"]),0,1,'L',false);
		$margen+=50;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(30,0,number_format($datosCertificados[$contador]["importeUnitario"],3,',','.')." ".EURO,0,1,'R',false);
		$margen = $margenInicial;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,number_format($datosCertificados[$contador]["total"],3,',','.')." ".EURO,0,1,'R',false);

		$total += $datosCertificados[$contador]["total"];
		
		$contador++;
		
		
	
		
		
	}
	setlocale(LC_ALL,"es_ES");
	$altura += 10;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total: ".number_format($total,3,',','.')." ".EURO,0,1,'R',false);
	
	$altura = 275;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	
	$diaTexto="";
	if (date("N")=="1")
	{
		$diaTexto = "Lunes";
	}
	else if (date("N")=="2")
	{
		$diaTexto = "Martes";
	}
	else if (date("N")=="3")
	{
		$diaTexto = "Miercoles";
	}
	else if (date("N")=="4")
	{
		$diaTexto = "Jueves";
	}
	else if (date("N")=="5")
	{
		$diaTexto = "Viernes";
	}
	else if (date("N")=="6")
	{
		$diaTexto = "Sabado";
	}
	
	else if (date("N")=="7")
	{
		$diaTexto = "Domingo";
	}
	
	$mesTexto="";
	if (date("n")=="1")
	{		
		$mesTexto = "Enero";
	}
	else if (date("n")=="2")
	{		
		$mesTexto = "Febrero";
	}
	else if (date("n")=="3")
	{		
		$mesTexto = "Marzo";
	}
	else if (date("n")=="4")
	{		
		$mesTexto = "Abril";
	}
	else if (date("n")=="5")
	{		
		$mesTexto = "Mayo";
	}
	else if (date("n")=="6")
	{		
		$mesTexto = "Junio";
	}
	else if (date("n")=="7")
	{		
		$mesTexto = "Julio";
	}
	else if (date("n")=="8")
	{		
		$mesTexto = "Agosto";
	}
	else if (date("n")=="9")
	{		
		$mesTexto = "Sepiembre";
	}
	else if (date("n")=="10")
	{		
		$mesTexto = "Octubre";
	}
	else if (date("n")=="11")
	{		
		$mesTexto = "Noviembre";
	}
	else if (date("n")=="12")
	{		
		$mesTexto = "Diciembre";
	}
	
	$pdf->Cell(0,0,$diaTexto.date(", d")." de ".$mesTexto." del ". date("Y"),0,'R',false);
	
	
	$pdf->Output("I","Certificados".date("dmy")." .pdf","UTF-8");
	
	
}
	
	


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$margenInicial)
{
	$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	$altura = $alturaSiguientePagina;
	
	$margen = $margenInicial;
	
		
	
	$altura += 15;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Fecha",0,1,'L',false);
	
	$margen+=30;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Unidades",0,1,'L',false);
	$margen+=30;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("Descripción"),0,1,'L',false);
	$margen+=50;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Importe Unitario",0,1,'L',false);
	$margen = $margenInicial;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Importe Total",0,1,'R',false);
	
	$margen = $margenInicial;
	$pdf->SetTextColor(0,0,0);
	
		
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.5);
	$pdf->Line($margen, $altura, 190, $altura);
}



?>
	<!--$formasDePago=cargarFormasDePago($conexion);-->
	
	
	
	
	
		


