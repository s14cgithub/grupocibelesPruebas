
<?php 

class PDF extends FPDF 
{
// Cabecera de página
	function Header()
	{
		// Logo
		//$ancho = GetPageWidth();
		$this->Image('imagenes/cabeceraInforme.jpg',-5,-5,220);
		// Arial bold 15
		//$this->SetFont('Arial','B',15);
		// Movernos a la derecha
		//$this->Cell(80);
		// Título
		//$this->Cell(30,10,'Title',1,0,'C');
		// Salto de línea
		$this->Ln(20);
	}

	// Pie de página
	function Footer()
	{
		// Posición: a 1,5 cm del final
		$this->SetY(-15);
		$this->Image('imagenes/pieInforme.jpg',0,269,220);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->SetXY(0,260);
		$this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'R');
	}
}

?>