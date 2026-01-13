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
	//require($ruta."Archivos Comunes/rotate.php");
	//require($ruta."Archivos Comunes/code128.php");
	//require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	
	$idCliente = $_POST["imprimirClienteInformeFranqueoModal"];	
	$fechaInicio = $_POST["imprimirFechaInicioInformeFranqueoModal"];	
	$fechaFin = $_POST["imprimirFechaFinInformeFranqueoModal"];	
	$saldo = $_POST["imprimirSaldoFinInformeFranqueoModal"];	
	$sinIva = $_POST["imprimirSinIvaInformeFranqueoModal"];
	
	$datosFranqueo = verConsumoFranqueoGrabadosPorClienteYfechas($conexion,$idCliente,$fechaInicio,$fechaFin);
	
	//echo count($datosFranqueo[0]["nombreEmpresa"]);
	if (count($datosFranqueo)>0)
	{
	
		$nombreCliente = $datosFranqueo[0]["nombreEmpresa"];

		$pdf = new PDF_PageGroup('P','mm','A4');
		$alturaSiguientePagina = 35;
		$margenInicial=20;
		//$pdf->SetXY(-10,-5);

		$pdf->SetLeftMargin(20);
		$pdf->SetRightMargin(20);
		$pdf->StartPageGroup();
		//$pdf->AddPage();
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);

		$contador=0;
		
		$primeraVez1=true;		

		$clienteAntiguo="asdjfaksdjfk238492834usdjfasf";		
			
		while ($contador<count($datosFranqueo))
		{
			$nombreCliente = $datosFranqueo[$contador]["nombreEmpresa"];
			if ($clienteAntiguo!=$nombreCliente)
			{
				if ($primeraVez1==false)
				{
					
					if ($altura>252)
					{							
						nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
					}

					$pdf->SetFont('Arial','B',8);
					$altura += 5;

					$margen = 60;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,"Total:",0,1,'C',false);
					$margen = 120;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,$unidadesExtension,0,1,'C',false);
					$margen = $margenInicial;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,number_format($totalExtension,2,',','.')." ".EURO,0,1,'R',false);

					$altura += 3;
					$margen = 120;
					$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
					$pdf->SetLineWidth(0.2);
					$pdf->Line($margen, $altura, 190, $altura);
					
					/*$altura += 3;
					$margen = 120;
					$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
					$pdf->SetLineWidth(0.2);
					$pdf->Line($margen, $altura, 190, $altura);*/


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


					if ($saldo=="true")
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
					}
					$pdf->StartPageGroup();
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
				}
				else
				{
					$primeraVez1=false;
				}
				
				
				$margen=$margenInicial;

				$altura=15;
				$pdf->SetFont('Arial','',10);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0, diaDeLaSemanaActual().date(", d")." de ".mesActual()." del ". date("Y"),0,1,'R',false);
				$altura += 5;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0, "Informe del ".date("d-m-Y", strtotime($fechaInicio)). " al ".date("d-m-Y", strtotime($fechaFin)),0,1,'R',false);

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
				
				$clienteAntiguo = $nombreCliente;
				$pdf->Cell(0,0,utf8_decode($nombreCliente),0,1,'L',false);
				
				$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
				$pdf->SetFont('Arial','BI',16);
				$pdf->SetXY($margen,$altura);
				
				$total=0.000;
				$ot="aaaaaa8a8a8a8a8a8a";		
				$unidadesExtension=0.00;
				$totalExtension=0.00;

				$unidadesTotal=0.00;
				$totalTotal=0.00;
				$primeraVez=true;
				
			}
			
			
			
			
			
			if (trim($datosFranqueo[$contador]["ot"])!=$ot)
			{
				if ($altura>260)
				{			
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
				}
				if (!$primeraVez)
				{
					if ($altura>252)
					{			
						nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
					}

					$pdf->SetFont('Arial','B',8);
					$altura += 5;

					$margen = 60;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,"Total:",0,1,'C',false);
					$margen = 120;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,$unidadesExtension,0,1,'C',false);
					$margen = $margenInicial;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,number_format($totalExtension,2,',','.')." ".EURO,0,1,'R',false);

					$altura += 3;
					$margen = 120;
					$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
					$pdf->SetLineWidth(0.2);
					$pdf->Line($margen, $altura, 190, $altura);
				}
				else
				{
					$primeraVez=false;
				}



				$margen = $margenInicial;
				$unidadesExtension=0.00;
				$totalExtension=0.00;
				$ot = trim($datosFranqueo[$contador]["ot"]);
				$altura += 10;
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode($ot),0,1,'L',false);

				$descripcion="aaaaa4568!ajajajajaja8a8a8";
				titulos($pdf,$altura,$margenInicial,$margen);
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

				$pdf->Cell(0,0,number_format($datosFranqueo[$contador]["importeTotalSinIva2"],2,',','.')." ".EURO,0,1,'R',false);


				$margen = $margenInicial;


				$unidadesExtension += $datosFranqueo[$contador]["unidades"];
				$totalExtension += $datosFranqueo[$contador]["importeTotalSinIva2"];

				$unidadesTotal += $datosFranqueo[$contador]["unidades"];
				$totalTotal += $datosFranqueo[$contador]["importeTotalSinIva2"];
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
			}
			

			$contador++;
		}

		$pdf->SetFont('Arial','B',8);
		$altura += 5;

		$margen = 60;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,"Total:",0,1,'C',false);
		$margen = 120;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$unidadesExtension,0,1,'C',false);
		$margen = $margenInicial;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,number_format($totalExtension,2,',','.')." ".EURO,0,1,'R',false);

		$altura += 3;
		$margen = 120;
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.2);
		$pdf->Line($margen, $altura, 190, $altura);


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


		if ($saldo=="true")
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

		}



		$pdf->Output("I","Informe Franqueo Clientes".date("dmy")." .pdf","UTF-8");
	}
	else
	{
		echo "No hay datos para mostrar";
	}
	
	
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
	<!--$formasDePago=cargarFormasDePago($conexion);-->
	
	
	
	
	
		


