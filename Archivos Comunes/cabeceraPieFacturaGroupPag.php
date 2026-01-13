<?php 

class cabeceraFactura extends PDF_Rotate 
{
// Cabecera de página
	function Header()
	{		
		$altura=5;
		
		
		$margenInicial=20;
		$margenLineaDerecha=190;
		
		if (isset($GLOBALS['informePFoPago'])&&$GLOBALS['informePFoPago']=="informePFoPago")
		{ 			
			$margenInicial=10;
			$margenLineaDerecha = 200;
		}
		
		
		unset ($GLOBALS['informePFoPago']);
		
	
		
		$margen=$margenInicial;
		//$pdf->Text($margen,$altura,$datosPresupuesto[0]["fecha"]->format('d/m/Y'));

		//$pdf->SetTextColor(13,140,252);

		$this->Image('imagenes/CibelesMailing.png',$margen,$altura,75);


		$this->SetFont('Arial','',6);
		//$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);
	
		/*$this->RotatedText($margenInicial-12,210,utf8_decode("C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07
Cibeles Mailing S.A. A-81339186"),90);*/
	
	
		//$altura = 290;
		$altura = 280;
		$this->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$this->SetLineWidth(0.8);
		$this->Line($margen, $altura, $margenLineaDerecha, $altura);
		
		$altura += 2; 
		$this->SetFont('Arial','B',10);
		$this->SetXY($margenInicial,$altura);	
		
		
		$this->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
		
		$this->MultiCell(0,4,utf8_decode('C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07         Cibeles Mailing S.A. A-81339186'),0,'C',false);
						 
		$this->SetTextColor(colorNegroR,colorNegroG,colorNegroB);	
		$this->SetFont('Arial','',6);
		$altura -= 7; 
		//$this->Text($margen,290,$this->GroupPageNo().'/'.$this->PageGroupAlias());
		$this->SetXY($margenInicial,$altura);	
		//$this->Cell(0,5,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
		$this->Cell(0,5,utf8_decode('Página ').$this->GroupPageNo().'/'.$this->PageGroupAlias(),0,0,'C');
		
		
		
	}

	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		/*$this->SetY(-15);
		$this->Image('imagenes/pieInforme.jpg',0,269,220);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->SetXY(0,260);
		$this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'R');*/
	}
}

?>
