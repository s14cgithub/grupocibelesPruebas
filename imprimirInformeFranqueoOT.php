<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirFranqueo")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	
	$OT = $_POST["imprimirOTInformeFranqueoModal"];	
	$sinIva = $_POST["imprimirOTSinIvaInformeFranqueoModal"];
	
	$separarPorFechas = $_POST["imprimirOTSepararFechasinformeFranqueoModal"];
	
	$anioSeleccionado = $_POST["imprimirOTInformeAnioFranqueoModal"];
	
	
	//$datosFranqueo = verConsumoFranqueoGrabadosPorOT($conexion,$OT);
	
	
	if ($separarPorFechas=="true")
	{
		$datosFranqueo = verConsumoFranqueoGrabadosPorOT($conexion,$OT,1,$anioSeleccionado);
	}
	else
	{		
		$datosFranqueo = verConsumoFranqueoGrabadosPorOT($conexion,$OT,0,$anioSeleccionado);
		
	}
	
	
	
	
	if (count($datosFranqueo)<=0)
	{
		echo ("En la ot '".$OT."' no hay datos de franqueo");
		exit;
	}
	
	$nombreCliente = $datosFranqueo[0]["subcliente"];
	$ot =  $datosFranqueo[0]["ot"];
	
	$pdf = new cabeceraFactura('P','mm','A4');

	//$pdf->SetXY(-10,-5);

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
	//$pdf->SetXY($margen,$altura);
	//$pdf->Cell(0,0, "Informe del ".date("d-m-Y", strtotime($fechaInicio)). " al ".date("d-m-Y", strtotime($fechaFin)),0,1,'R',false);
	
	$altura=10;
	$altura += 5;
	$altura += 8;
	$altura += 15;
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',16);
	$pdf->SetXY($margen,$altura);
	
	
	if ($sinIva=="true")
	{
		$pdf->Cell(0,0,"INFORME DE CONSUMO DEL FRANQUEO (SIN IVA)",0,1,'C',false);
	}
	else
	{
		$pdf->Cell(0,0,"INFORME DE CONSUMO DEL FRANQUEO",0,1,'C',false);
	}
	
	
	$altura += 10;
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($nombreCliente),0,1,'L',false);
	
	$altura += 10;
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($ot),0,1,'L',false);

	$altura += 10;
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,reemplazarSimbolos(trim($datosFranqueo[0]["campana2"])),0,1,'L',false); 
	
	
	
	
	
	$contador=0;
	$total=0.000;
	$alturaSiguientePagina = 35;
	
	$ot="aaaaaa8a8a8a8a8a8a";
	$descripcion="aaaaa4568!ajajajajaja8a8a8";
	$unidadesExtension=0.00;
	$totalExtension=0.00;
	
	$unidadesTotal=0.00;
	$totalTotal=0.00;
	
	$primeraVez=true;
	
	$primeraVezFechas=true;		
	$fechaAntigua="asdjfaksdjfk238492834usdjfasf";	
	
	$totalPrecioFecha=0.00;
	$totalUnidadesFecha=0.00;		
	$imprimirTotalFecha=false;
	
	
	while ($contador<count($datosFranqueo))
	{
		if ($primeraVez && $separarPorFechas=="false")
		{
			$altura += 5;
			titulos($pdf,$altura,$margenInicial,$margen);
			$primeraVez = false;
		}
		
		
		if ($separarPorFechas=="true")
		{
			$fechaFranqueo = $datosFranqueo[$contador]["fecha"]->format('d/m/Y');
			if ($fechaAntigua!=$fechaFranqueo)
			{
				$fechaAntigua=$fechaFranqueo;

				if ($altura>260)
				{			
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
				}
				
				
				if ($primeraVezFechas)
				{
					$primeraVezFechas = false;
				}
				else
				{
					if ($altura>260)//252
					{			
						nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
					}

					$pdf->SetFont('Arial','B',8);
					$altura += 5;

					$margen = 120;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(15,0,"Total (Fecha):",0,1,'R',false);
					$margen = 120;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,$totalUnidadesFecha,0,1,'C',false);
					$margen = $margenInicial;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,number_format($totalPrecioFecha,2,',','.')." ".EURO,0,1,'R',false);

					$altura += 3;
					$margen = 115;
					$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
					$pdf->SetLineWidth(0.2);
					$pdf->Line($margen, $altura, 190, $altura);
					
					$totalPrecioFecha=0;
					$totalUnidadesFecha=0;
				}
				
				$margen = $margenInicial;
				
				$primeraVezFechas=false;
				$imprimirTotalFecha=false;

				$altura += 10;
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Arial','B',12);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,$fechaFranqueo,0,1,'L',false);


				$altura += 5;

				$descripcion="aaaaa4568!ajajajajaja8a8a8";
				//$ot="aaaaa4568!ajajajajaja8a8a8";
				
				titulos($pdf,$altura,$margenInicial,$margen);
			}	

		}
		
		
		if ($altura>260)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
		}
		
		if (trim($datosFranqueo[$contador]["descripcion"]) != $descripcion)
		{
			$descripcion = trim($datosFranqueo[$contador]["descripcion"]);
			$altura += 5;
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode($descripcion),0,1,'L',false);
			
			$altura -= 5;
			
		}
		
		$altura += 5;
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		$margen = 10;
		$pdf->SetXY($margen,$altura);		
		$pdf->Cell(0,0,utf8_decode($datosFranqueo[$contador]["gramos"]),0,1,'C',false);
		
		$margen = 60;;
		$pdf->SetXY($margen,$altura);
		
		
		if ($sinIva=="true")
		{
			$pdf->Cell(0,0,number_format($datosFranqueo[$contador]["unitarioSinIva"],2,',','.')." ".EURO,0,1,'C',false);


			$margen = 120;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$datosFranqueo[$contador]["unidades"],0,1,'C',false);


			$margen = $margenInicial;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($datosFranqueo[$contador]["importeTotalSinIva"],2,',','.')." ".EURO,0,1,'R',false);


			$margen = $margenInicial;


			$unidadesExtension += $datosFranqueo[$contador]["unidades"];
			$totalExtension += $datosFranqueo[$contador]["importeTotalSinIva"];

			$unidadesTotal += $datosFranqueo[$contador]["unidades"];
			$totalTotal += $datosFranqueo[$contador]["importeTotalSinIva"];
			
			$totalUnidadesFecha += $datosFranqueo[$contador]["unidades"];
			$totalPrecioFecha += $datosFranqueo[$contador]["importeTotalSinIva"];
		}
		else
		{
		
			$pdf->Cell(0,0,number_format($datosFranqueo[$contador]["unitario"],2,',','.')." ".EURO,0,1,'C',false);


			$margen = 120;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$datosFranqueo[$contador]["unidades"],0,1,'C',false);


			$margen = $margenInicial;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($datosFranqueo[$contador]["importeTotal"],2,',','.')." ".EURO,0,1,'R',false);


			$margen = $margenInicial;


			$unidadesExtension += $datosFranqueo[$contador]["unidades"];
			$totalExtension += $datosFranqueo[$contador]["importeTotal"];

			$unidadesTotal += $datosFranqueo[$contador]["unidades"];
			$totalTotal += $datosFranqueo[$contador]["importeTotal"];
			
			
			
			$totalUnidadesFecha += $datosFranqueo[$contador]["unidades"];
			$totalPrecioFecha += $datosFranqueo[$contador]["importeTotal"];				

			
		}
		
		//////////////////////////////////////////////////////////////////////////////////////
		
		$contador++;
	}
	
	
	if ($separarPorFechas=="true")
	{
		$pdf->SetFont('Arial','B',8);
		$altura += 5;

		$margen = 60;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,"Total (Fecha):",0,1,'C',false);
		$margen = 120;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$totalUnidadesFecha,0,1,'C',false);
		$margen = $margenInicial;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,number_format($totalPrecioFecha,2,',','.')." ".EURO,0,1,'R',false);

		$altura += 3;
		$margen = 115;
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.2);
		$pdf->Line($margen, $altura, 190, $altura);
	}
	
	
	
	//TOTAL 
	if ($altura>260)
	{			
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
	}
	
	
	$altura += 10;
	$margen = 60;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total:",0,1,'C',false);
	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,$unidadesTotal,0,1,'C',false);
	$margen = $margenInicial;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,number_format($totalTotal,2,',','.')." ".EURO,0,1,'R',false);
	
	
	/*if ($saldo=="true")
	{
		
		$saldoAnterior = 0.00;
		$datosSaldo = verHistoricoSaldo($conexion, date("d-m-Y",strtotime($fechaInicio."- 1 days")),$idCliente);
		
		
		
		if (count($datosSaldo)<=0)
		{
			$datosSaldo = mirarDatosEmpresaPorCodigo($conexion,$idCliente);
			$saldoAnterior = $datosSaldo[0]["importePF"];
		}
		else
		{
			$saldoAnterior = $verHistoricoSaldo[0]["saldoPostPF"];
		}
		
		
		if ($altura>245)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
		}
		
		$altura += 5;
		
		$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->Rect(70, $altura+5, 70, 20,'F');
	
	
		$pdf->SetFont('Arial','B',10);
		$pdf->SetTextColor(colorBlancoR,colorBlancoG,colorBlancoB);
		
		
		
		
		
		$altura += 10;
		$margen = 50;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(50,0,"Saldo anterior:",0,1,'R',false);
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(50,0,"Total Franqueo:",0,1,'R',false);
		
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(50,0,"Saldo Actual:",0,1,'R',false);
		
		//SEGUNDA COLUMNA
		$altura -= 10;
		$margen = 80;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(50,0,number_format($saldoAnterior,2,',','.')." ".EURO,0,1,'R',false);
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(50,0,number_format($totalTotal,2,',','.')." ".EURO,0,1,'R',false);
		
		$altura += 5;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		
		$aPagar = $saldoAnterior - $totalTotal;
		$pdf->Cell(50,0,number_format($aPagar,2,',','.')." ".EURO,0,1,'R',false);
		
		
		
		
		
	}*/
	
	
	
	$pdf->Output("I","Certificados".date("dmy")." .pdf","UTF-8");
	
	
}
	
	
function titulos(&$pdf,&$altura,$margenInicial,$margen)
{
	$altura += 5;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Producto",0,1,'L',false);
	
	$margen = 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Gramos",0,1,'C',false);
	$margen = 60;
	$pdf->SetXY($margen,$altura);	
	$pdf->Cell(0,0,EURO." Unitario",0,1,'C',false);	
	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Unidades",0,1,'C',false);
	$margen = $margenInicial;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total",0,1,'R',false);
	
	$margen = $margenInicial;
	$pdf->SetTextColor(0,0,0);
	
		
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.5);
	$pdf->Line($margen, $altura, 190, $altura);
}

function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$margenInicial)
{
	$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	$altura = $alturaSiguientePagina;
	
	$margen = $margenInicial;
	
		
	

}



?>
	<!--$formasDePago=cargarFormasDePago($conexion);-->
	
	
	
	
	
		


