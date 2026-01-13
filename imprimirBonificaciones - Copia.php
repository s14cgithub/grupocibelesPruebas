<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="Bonificaciones")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	require($ruta."FPDF/fpdf.php");	
	
	
	$fechaInicio = $_POST["fechaInicioBon"];	
	$fechaFin = $_POST["fechaFinBon"];
	$codigoCliente = $_POST["numeroClienteBon"];
	
	
	

	
	{
		require($ruta."Archivos Comunes/pagegroup.php");
		require($ruta."Archivos Comunes/rotate.php");
		require($ruta."Archivos Comunes/cabeceraPieFactura.php");
		
		$pdf = new cabeceraFactura('P','mm','A4');

		$pdf->SetLeftMargin(20);
		$pdf->SetRightMargin(20);

		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);



		$margenInicial=20;
		$margen=$margenInicial;


		//$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		//$pdf->SetLineWidth(0.8);

		$altura=15;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, diaDeLaSemanaActual().date(", d")." de ".mesActual()." del ". date("Y"),0,1,'R',false);
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, "Informe del ".date("d-m-Y", strtotime($fechaInicio)). " al ".date("d-m-Y", strtotime($fechaFin)),0,1,'R',false);

		
		//insertarTitulos($pdf,$altura,$margenInicial);
		insertarTitulo1($pdf,$altura,$margenInicial);
		
		$datos = verBonificacionesFranqueo($conexion,$fechaInicio, $fechaFin,$codigoCliente); 
		//echo "<br>".$datos."<br>";		
		//echo "<br>".count($datos)."<br>";
		
		$productoAntiguo = "asjdkaakj22f";
		
		$sumaTotalImporte=0;
		$sumaTotalBonificacion=0;
		$sumaImporte=0;
		$sumaBonificacion=0;
		
		$primeraVez=true;
		
		for ($contador=0;$contador<count($datos);$contador++)
		{
			
			if ($altura>260)
			{
				$pdf->AddPage();
				insertarTitulo1($pdf,$altura,$margenInicial);
				//insertarTitulo2($pdf,$altura,$productoAntiguo,$margenInicial);
				$altura += 5;
			}
			
			if($productoAntiguo!=utf8_decode($datos[$contador]["descripcion"]))
			{
				
				
				if (!$primeraVez)
				{
					$altura += 5;
					$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
					$pdf->SetLineWidth(0.2);
					$pdf->Line(110, $altura, 190, $altura);					
					
					
					$pdf->SetFont('Arial','B',10);
					$margen += 95;
					$altura += 5;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(20,0,number_format($sumaImporte,2,',','.')." ".EURO,0,1,'R',false);
					
					
					
					$pdf->SetXY($margen,$altura);
					
					$pdf->Cell(0,0,number_format($sumaBonificacion,2,',','.')." ".EURO,0,1,'R',false);
					
					$sumaBonificacion=0;
					$sumaImporte=0;
				}
				else
				{
					$primeraVez=false;
				}
				
				if ($altura>260)
				{
					$pdf->AddPage();
					insertarTitulo1($pdf,$altura,$margenInicial);
					//insertarTitulo2($pdf,$altura,$productoAntiguo,$margenInicial);
					$altura += 5;
				}
				
				$productoAntiguo = utf8_decode($datos[$contador]["descripcion"]);
				//insertarTitulos($pdf,$altura,$margenInicial,$productoAntiguo);
				insertarTitulo2($pdf,$altura,$productoAntiguo,$margenInicial);
				
				
			}
			
			
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',10);
			
			$margen = $margenInicial;
			$altura += 5;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode($datos[$contador]["nombre_empresa"]),0,1,'L',false);
			
			
			$margen += 95;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,number_format($datos[$contador]["importe"],2,',','.')." ".EURO,0,1,'R',false);
			
			$margen += 23;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,number_format($datos[$contador]["descuentoPorCiento"],0,',','.')." %",0,1,'R',false);
			
			
			$margen = $pdf->GetPageWidth()-20;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($datos[$contador]["bonificacion"],2,',','.')." ".EURO,0,1,'R',false);
			
			
			
			//$aux=number_format($datos[$contador]["importe"],2,',','.');
			
			$aux=$datos[$contador]["importe"];
			
			
			$margen = $margenInicial;
			
			$sumaImporte=$sumaImporte+$aux;
			$sumaTotalImporte=$sumaTotalImporte+$aux;
			
			//$aux=number_format($datos[$contador]["bonificacion"],2,',','.');
			$aux=$datos[$contador]["bonificacion"];
			$sumaBonificacion=$sumaBonificacion+$aux;
			$sumaTotalBonificacion=$sumaTotalBonificacion+$aux;
			
			
			
			
			
			
					
		}
		
		
		$altura += 5;
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.2);
		$pdf->Line(110, $altura, 190, $altura);

		$pdf->SetFont('Arial','B',10);
		$margen += 95;
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,number_format($sumaImporte,2,',','.')." ".EURO,0,1,'R',false);

		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,number_format($sumaBonificacion,2,',','.')." ".EURO,0,1,'R',false);
		
		
		
		$pdf->SetFont('Arial','B',12);
		//$margen += 95;
		$altura += 15;
		
		$pdf->SetXY($margen-35,$altura);
		$pdf->Cell(20,0,"TOTAL: ",0,1,'R',false);
		
		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,number_format($sumaTotalImporte,2,',','.')." ".EURO,0,1,'R',false);

		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,number_format($sumaTotalBonificacion,2,',','.')." ".EURO,0,1,'R',false);
		
		
		
		
		
		
	}
	
	
	$pdf->Output("I",$ruta."InformeBonificaciones - ".date("dmy")." .pdf","UTF-8");
	
	
}
else
{
	
}


function insertarTitulo1(&$pdf,&$altura,$margenInicial)
{
	//$pdf->AddPage();
	
	
	$margen = $margenInicial;
	
	
	$altura=10;
	$altura += 5;
	$altura += 8;
	$altura += 15;

	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',16);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("CÁLCULO BONIFICACIÓN GIALSA"),0,1,'C',false);

		
	

}


function insertarTitulo2(&$pdf,&$altura,&$productoAntiguo,$margenInicial)
{
	//$pdf->AddPage();
	
	
	$margen = $margenInicial;	


	
	$pdf->SetTextColor(0,0,0);
	

	
	$pdf->SetFont('Arial','BU',10);
	
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($productoAntiguo),0,1,'L',false);
				
	
	//$altura += 5;
	
	


	$margen += 95;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,0,utf8_decode("IMPORTE"),0,1,'R',false);

	$margen += 28;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,0,utf8_decode("DESCUENTO"),0,1,'R',false);
	
	$margen = $pdf->GetPageWidth()-20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("BONIFICACIÓN"),0,1,'R',false);


	$pdf->SetFont('Arial','',8);
	
	$altura += 5;
	

}






?>
	
	
	
	
	
		


