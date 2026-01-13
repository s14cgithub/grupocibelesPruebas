<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirAlbaran")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	
	
	
	
	
	
	$numeroAlbaran = $_POST["imprimirAlbaran_numero"];		
	$empleado=$_POST["imprimirAlbaran_empleado"];
	$cliente=$_POST["imprimirAlbaran_cliente"];
	$tipo=$_POST["imprimirAlbaran_tipo"];
	$cantidad=$_POST["imprimirAlbaran_cantidad"];
	$importe=$_POST["imprimirAlbaran_importe"];
	$descripcion=$_POST["imprimirAlbaran_descripcion"];
	$fecha=$_POST["imprimirAlbaran_fecha"];	
	
	
	$datosClientes = mirarDatosEmpresaPorCodigo($conexion,$cliente);
	
	
	
	
	$tipoValor = "";
	if ($tipo=="1")
	{
		$tipoValor = "Cibeles Mailing Entrega a";
	}
	else
	{
		$tipoValor = "Cibeles Mailing Recibe de";
	}
	
	
	$pdf = new FPDF();
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	
	
	$pdf->AddPage();
	
	
	
	$margen=20;
	$altura=10;
	
	$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);
	
	
	//$altura += 5;
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen+80,$altura);
	$pdf->Cell(0,5,"ORIGINAL (para el cliente)",0,1,'R',false);
	
	$altura += 8;
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($margen+80,$altura);
	
	/*$anio= substr($fecha, 0,4);
	$mes= substr($fecha, 4,2);
	$dia= substr($fecha, 8,2);
	
	$hora= substr($fecha, 11,5);
	
	
	
	$pdf->Cell(0,5,"Fecha: ".$dia."/".$mes."/".$anio." ".$hora,0,1,'R',false);*/
	$pdf->Cell(0,5,"Fecha: ".$fecha,0,1,'R',false);
	
	
	
	
	
	$altura+= 8;
	
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($margen+130,$altura);
	$pdf->Cell(20,5,utf8_decode("Nº Albarán:"),0,0,'R',false);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,$numeroAlbaran,0,1,'R',false);
	
	$altura+= 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,$tipoValor,0,1,'C',false);
	
	$altura+= 8;
	
	$pdf->SetDrawColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
	$pdf->SetLineWidth(0.4);	
	$pdf->Line($margen, $altura,190, $altura);
	
	
	
	
	
	
	$altura += 5;
	
	$pdf->SetFont('Arial','B',9);
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"NOMBRE:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["nombre_empresa"]),0,'L',false);
	
	$altura += 13;
	
	$pdf->SetFont('Arial','B',9);
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"NIF:",0,1,'L',false);
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["nif_subcliente"]),0,'L',false);
	
	
	
	
	$altura += 8;
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION:",0,1,'L',false);
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["direccion"]),0,'L',false);
	
	$altura += 6;	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["codigo_postal"]." - ".$datosClientes[0]["localidad"]),0,'L',false);
	
	$altura += 6;	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["provincia"]),0,'L',false);
	
	
	$altura+= 8;
	
	$pdf->SetDrawColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
	$pdf->SetLineWidth(0.4);	
	$pdf->Line($margen, $altura,190, $altura);
	
	$pdf->SetFont('Arial','B',9);
	$altura += 5;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"Descripcion:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($descripcion),0,'L',false);
	
	
	
	
	
	
	$altura += 15;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"Conductor:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($empleado),0,'L',false);	
	
	
	
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen+120,$altura);
	$pdf->Cell(0,5,"Cantidad:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25+120,$altura);	
	$pdf->MultiCell(0,5,$cantidad." Unidades",0,'L',false);
	
	
	$altura += 5;
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen+120,$altura);
	$pdf->Cell(0,5,"Importe:",0,1,'L',false);
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25+120,$altura);	
	
	//$pdf->MultiCell(0,5,number_format($importe,2,',','.')." ".EURO,0,'L',false);ç
	$importe = str_replace("€",EURO, $importe);
	$pdf->MultiCell(0,5,$importe,0,'L',false);
	
	$pdf->SetFont('Arial','B',8);
	$altura += 5;
	$pdf->SetXY($margen+20,$altura);
	$pdf->Cell(0,5,"Firma, fecha y sello de la entrega",0,1,'L',false);
	
	
	
	
	
	
	
	$mitadPagina = $pdf->GetPageHeight()/2;
	$anchoPagina = $pdf->GetPageWidth();
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.1);
	
	$pdf->Line(0, $mitadPagina, $anchoPagina, $mitadPagina);
	
	
	
	
	/////////////////////////SEGUNDA PARTE//////////////////////////
	
	$altura = $mitadPagina;
	$altura += 10;
	$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);
	
	
	//$altura += 5;
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen+80,$altura);
	$pdf->Cell(0,5,"COPIA (para Cibeles)",0,1,'R',false);
	
	$altura += 8;
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($margen+80,$altura);
	
	$anio= substr($fecha, 0,4);
	$mes= substr($fecha, 5,2);
	$dia= substr($fecha, 8,2);
	
	$hora= substr($fecha, 11,5);
	
	
	
	//$pdf->Cell(0,5,"Fecha: ".$dia."/".$mes."/".$anio." ".$hora,0,1,'R',false);
	
	$pdf->Cell(0,5,"Fecha: ".$fecha,0,1,'R',false);
	
	
	
	
	
	$altura+= 8;
	
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($margen+130,$altura);
	$pdf->Cell(20,5,utf8_decode("Nº Albarán:"),0,0,'R',false);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,$numeroAlbaran,0,1,'R',false);
	
	$altura+= 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,$tipoValor,0,1,'C',false);
	
	$altura+= 8;
	
	$pdf->SetDrawColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
	$pdf->SetLineWidth(0.4);	
	$pdf->Line($margen, $altura,190, $altura);
	
	
	
	
	
	
	$altura += 5;
	
	$pdf->SetFont('Arial','B',9);
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"NOMBRE:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["nombre_empresa"]),0,'L',false);
	
	$altura += 13;
	
	$pdf->SetFont('Arial','B',9);
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"NIF:",0,1,'L',false);
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["nif_subcliente"]),0,'L',false);
	
	
	
	
	$altura += 8;
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION:",0,1,'L',false);
	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["direccion"]),0,'L',false);
	
	$altura += 6;	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["codigo_postal"]." - ".$datosClientes[0]["localidad"]),0,'L',false);
	
	$altura += 6;	
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($datosClientes[0]["provincia"]),0,'L',false);
	
	
	$altura+= 8;
	
	$pdf->SetDrawColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
	$pdf->SetLineWidth(0.4);	
	$pdf->Line($margen, $altura,190, $altura);
	
	$pdf->SetFont('Arial','B',9);
	$altura += 5;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"Descripcion:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($descripcion),0,'L',false);
	
	
	
	
	
	
	$altura += 15;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"Conductor:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetXY($margen+25,$altura);	
	$pdf->MultiCell(0,5,utf8_decode($empleado),0,'L',false);	
	
	
	
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen+120,$altura);
	$pdf->Cell(0,5,"Cantidad:",0,1,'L',false);
	
	
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25+120,$altura);	
	$pdf->MultiCell(0,5,$cantidad." Unidades",0,'L',false);
	
	
	$altura += 5;
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetXY($margen+120,$altura);
	$pdf->Cell(0,5,"Importe:",0,1,'L',false);
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	$pdf->SetXY($margen+25+120,$altura);	
	//$pdf->MultiCell(0,5,number_format($importe,2,',','.')." ".EURO,0,'L',false);
	$pdf->MultiCell(0,5,$importe,0,'L',false);
	
	$pdf->SetFont('Arial','B',8);
	$altura += 5;
	$pdf->SetXY($margen+20,$altura);
	$pdf->Cell(0,5,"Firma, fecha y sello de la entrega",0,1,'L',false);
	
	
	
	
	
	
	$pdf->Output();
	
	
	/*$pdf = new FPDF('P','mm','A4');
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	
	$altura = 10;
	$pdf->Line(0, $altura, 190, $altura);

	

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	$pdf->AddPage();
		
	$pdf->SetFont('Arial','B',10);
	
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	
	
	
	
	
	
	
	
	//$mitadPagina = $pdf->GetPageHeight();

	
	

	
	
	
	
	
	
	$pdf->Output("I","Albaran - ".$numeroAlbaran."-".date("dmy")." .pdf","UTF-8");*/
	
	
	
	
}



?>