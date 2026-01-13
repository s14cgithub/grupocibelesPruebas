<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirAlbaran")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	
	
	
	
	//$numFactura = $_POST["imprimirNumFactura"];	
	
	
	
	$numAlbaran = $_POST["formNumAlbaran"];
	
	//$numAlbaran=2206090002;
	
	$pdf = new PDF_PageGroup('P','mm','A4');
	
	$altura=10;
	$margenInicial=20;
	$margen=$margenInicial;
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	$pdf->StartPageGroup();
	
	
	
	
	
	$resultado = verAlbaranAlmacen($conexion, $numAlbaran);
	
	$alturaDatos=0;
	
	nuevaPagina($pdf,$altura,$margenInicial,$numAlbaran,$resultado,$alturaDatos);
	
	$altura = $alturaDatos;
	
	$contador=0;
	while ($contador < count($resultado))
	{
		
		$altura += 5;
		
		if ($altura>= 272)
		{
			nuevaPagina($pdf,$altura,$margenInicial,$numAlbaran,$resultado,$alturaDatos);	
			$altura = $alturaDatos;
			$altura += 5;
		}
		
		
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($resultado[$contador]["codigo"]),0,'L',false);

		$pdf->SetXY($margen+50,$altura);
		$pdf->MultiCell(0,4,utf8_decode($resultado[$contador]["nombre"]),0,'L',false);

		$pdf->SetXY($margen,$altura);
		$laCantidad = $resultado[$contador]["cantidad"];
		if ($laCantidad<0)
		{
			$laCantidad= $laCantidad * -1;
		}
		$pdf->MultiCell(0,4,$laCantidad,0,'R',false);
		
		
		
		$contador++;
	}
	
	if ($altura>= 232)
	{
		nuevaPagina($pdf,$altura,$margenInicial,$numAlbaran,$resultado,$alturaDatos);	
		$altura = $alturaDatos;
		$altura += 5;
	}
	
	$altura = 240;
	$margen = 40;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,utf8_decode("Grupo Cibeles"),0,1,'L',false);	
	$pdf->Image("imagenes/selloParaAlbaran.jpg",$margen-15,$altura+5, 45, 30, 'JPG');
	
	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,5,utf8_decode("Conforme Cliente"),0,1,'L',false);
	
	
	$pdf->Output("I","Albaran - ".$numAlbaran."-".date("dmy")." .pdf","UTF-8");
	
	
}
	
	


function nuevaPagina(&$pdf,&$altura,$margenInicial,$numAlbaran,$resultado,&$alturaDatos)
{
	$pdf->AddPage();
	$pdf->AliasNbPages();
	
	$pdf->SetAutoPageBreak(false);
	$margen=$margenInicial;
	$altura=10;
	
	$pdf->SetXY($margen,$altura);
	$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);
	
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$margen += 135;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(35,5,utf8_decode("ALBARAN Nº"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,$numAlbaran,1,1,'C',false);	
	
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 12;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0, diaDeLaSemanaActual().date(", d")." de ".mesActual()." del ". date("Y"),0,1,'R',false);
	
	$margen = $margenInicial;
	$altura = 38;
	
	
	$pdf->SetFont('Arial','B',18);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"ALBARAN - ".$resultado[0]["modalidad"],0,1,'L',false);
	
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);	
	
	
	$pdf->Cell(0,5,"Cod. Cliente: ".$resultado[0]["codigo_saldo"],0,1,'R',false);
	
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);
	
	
	$altura = $altura + 5;
	$alturaDireccion = $altura;
	$pdf->SetFont('Arial','U',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION FISCAL",0,1,'L',false);
	
	/*$empresa=$resultado[0]["envioNombreEmpresa"];
	$direccion=$resultado[0]["envioDireccion"];
	$cp=$resultado[0]["envioCp"];
	$localidad=$resultado[0]["envioLocalidad"];
	$provincia=$resultado[0]["envioProvincia"];
	
	if ($resultado[0]["almacen"]=="CIBELES")
	{
		$empresa="Cibeles Mailing";
		$direccion="Calle Torneros 12-14";
		$cp = "28906";
		$localidad="Getafe";
		$provincia = "Madrid";
	}
	else
	{
		$empresa="Hermes Sobres (Cibeles Mailing)";
		$direccion="Avenida de la Industria, 30";
		$cp = "28760";
		$localidad="Tres Cantos";
		$provincia = "Madrid";
	}*/	
	
	
	$pdf->SetFont('Arial','B',8);
	$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(84,4,utf8_decode($resultado[0]["nombre_empresa"]),0,'L',false);
	
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["nombre_empresa"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(84,4,utf8_decode($resultado[0]["direccion"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["direccion"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($resultado[0]["codigo_postal"]." - ".$resultado[0]["localidad"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["codigo_postal"]." - ".$resultado[0]["localidad"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($resultado[0]["provincia"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["provincia"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);
	
	
	
	
	$pdf->SetFont('Arial','U',10);
	$altura = $alturaDireccion;
	$margen = $pdf->GetPageWidth()/2;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,"DIRECCION DE ENVIO",0,1,'L',false);
	
	$pdf->SetFont('Arial','B',8);
	$altura = $altura + 6;
	$pdf->SetXY($margen,$altura);
	
	$pdf->MultiCell(84,4,utf8_decode($resultado[0]["envioNombreEmpresa"]),0,'L',false);
	
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["envioNombreEmpresa"]);
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
	$pdf->MultiCell(84,4,utf8_decode($resultado[0]["envioDireccion"]),0,'L',false);

	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["envioDireccion"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);



	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($resultado[0]["envioCp"]." - ".$resultado[0]["envioLocalidad"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["envioCp"]." - ".$resultado[0]["envioLocalidad"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);

	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode($resultado[0]["envioProvincia"]),0,'L',false);
	$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["envioProvincia"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 84);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);

	$altura += 5;
	$margen=$margenInicial;
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 190, $altura);	
	$altura += 5;
	
	if ($resultado[0]["observaciones"]!="")
	{
		$pdf->SetFont('Arial','B',12);
		
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,"OBSERVACIONES",0,'L',false);
		
		$altura += 10;
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,4,utf8_decode($resultado[0]["observaciones"]),0,'L',false);
		
		$anchoDescripcion = $pdf->GetStringWidth($resultado[0]["observaciones"]);
		$numeroDeFilas = ceil ($anchoDescripcion / 169);
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}	
		$altura = $altura + (5*$numeroDeFilas);
		
		$altura += 5;
		$margen=$margenInicial;
		$pdf->SetLineWidth(0.8);
		$pdf->Line($margen, $altura, 190, $altura);	
		$altura += 5;
	}
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode("CÓDIGO"),0,'L',false);
	
	$pdf->SetXY($margen+50,$altura);
	$pdf->MultiCell(0,4,utf8_decode("DESCRIPCIÓN"),0,'L',false);
	
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(0,4,utf8_decode("UNIDADES"),0,'R',false);
	
	
	$alturaDatos = $altura+3;
	
	
	$altura = 280;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$margenLineaDerecha=190;
	$pdf->Line($margen, $altura, $margenLineaDerecha, $altura);

	$altura += 2; 
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margenInicial,$altura);	


	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);

	if ($resultado[0]["almacen"]=="CIBELES")
	{
		$pdf->MultiCell(0,4,utf8_decode('C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07'),0,'C',false);
	}
	else
	{
		$pdf->MultiCell(0,4,utf8_decode('Avd/ de la Industria, 30, 28760 Tres Cantos (Madrid). Tel.: 91 803 21 25'),0,'C',false);
	}
	

	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);	
	$pdf->SetFont('Arial','',6);
	$altura -= 7; 	
	$pdf->SetXY($margenInicial,$altura);	
	
	$pdf->Cell(0,5,utf8_decode('Página ').$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias(),0,0,'C');
	
	

}



?>

	
	
	
	
	
		


