<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirInforme")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	$fechaInicio = $_POST["fechaInicioConsumoProductoModal"];	
	$fechaFin = $_POST["fechaFinConsumoProductoModal"];	
	
	
	$datos = verConsumoPorProductosFranqueo($conexion, $fechaInicio, $fechaFin);
	
	
	
	$pdf = new cabeceraFactura('P','mm','A4');
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);

	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	$margenInicial=20;
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
	
	$pdf->Cell(0,0,"CONSUMO POR PRODUCTOS",0,1,'C',false);
	
	
	$altura += 15;
	$margen=$margenInicial;
	$alturaSiguientePagina=15;
	$limiteAlturaDatos = 260;
	$mostrarPrecio=0;
	$numPagina=0;	
	
	$contador=0;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"PRODUCTO",0,1,'L',false);
	
	
	
	$pdf->SetXY($margen+85,$altura);
	$pdf->Cell(25,0,"UNIDADES",0,1,'R',false);	
	
	$pdf->SetXY($margen+115,$altura);
	$pdf->Cell(25,0,"SIN IVA",0,1,'R',false);

	$pdf->SetXY(165,$altura);
	$pdf->Cell(25,0,"CON IVA",0,1,'R',false);
	
		
	$pdf->SetFont('Arial','',10);
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.5);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$pdf->SetTextColor(0,0,0);
	$unidadesTotal=0;
	$importeTotal=0.00;
	$importeTotalSinIva=0.00;
	
	$unidadesTotalCorreos=0;
	$importeTotalCorreos=0.00;
	$importeTotalSinIvaCorreos = 0.00;
	$retribucionCorreos = "asdkfalñsdkfj234jaskdñfaj";
	
	while ($contador<count($datos))
	{
		
		if ($retribucionCorreos!=$datos[$contador]["retribucionCorreos"])
		{
			if ($retribucionCorreos == "asdkfalñsdkfj234jaskdñfaj")
			{
				$altura -= 5;
			}
			else
			{
				$altura += 10;
				$margen = 50;
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY($margen+30,$altura);
				$pdf->Cell(25,0,"Total:",0,1,'R',false);
				$margen = $margenInicial;
				
				$pdf->SetXY($margen+85,$altura);
				$pdf->Cell(25,0,number_format($unidadesTotalCorreos,0,',','.'),0,1,'R',false);

				$pdf->SetXY($margen+115,$altura);
				$pdf->Cell(25,0,number_format( $importeTotalSinIvaCorreos ,2,',','.')." ".EURO,0,1,'R',false);

				$pdf->SetXY(165,$altura);
				$pdf->Cell(25,0,number_format($importeTotalCorreos,2,',','.')." ".EURO,0,1,'R',false);
				
				$unidadesTotalCorreos=0;
				$importeTotalSinIvaCorreos=0.00;
				$importeTotalCorreos = 0.00;
			}
			
			$retribucionCorreos = $datos[$contador]["retribucionCorreos"];
			$altura +=10;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$datos[$contador]["retribucionCorreos"],0,1,'L',false);
			
		}
		
		
		
		$altura +=8;
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$datos[$contador]["producto"],0,1,'L',false);

		$pdf->SetXY($margen+85,$altura);
		$pdf->Cell(25,0,number_format($datos[$contador]["unidades"],0,',','.'),0,1,'R',false);
		
		$pdf->SetXY($margen+115,$altura);
		$pdf->Cell(25,0,number_format($datos[$contador]["sinIva"],2,',','.')." ".EURO,0,1,'R',false);
		
		
		$pdf->SetXY(165,$altura);
		$pdf->Cell(25,0,number_format($datos[$contador]["conIva"],2,',','.')." ".EURO,0,1,'R',false);
		

		
		$unidadesTotal += $datos[$contador]["unidades"];
		$importeTotal += $datos[$contador]["conIva"];
		$importeTotalSinIva += $datos[$contador]["sinIva"];
		
		$unidadesTotalCorreos += $datos[$contador]["unidades"];
		$importeTotalCorreos += $datos[$contador]["conIva"];
		$importeTotalSinIvaCorreos += $datos[$contador]["sinIva"];
		
		$contador++;
	}
	
	
	
	$altura += 10;
				$margen = 50;
				$pdf->SetFont('Arial','B',8);
				$pdf->SetXY($margen+30,$altura);
				$pdf->Cell(25,0,"Total:",0,1,'R',false);
				$margen = $margenInicial;
				
				$pdf->SetXY($margen+85,$altura);
				$pdf->Cell(25,0,number_format($unidadesTotalCorreos,0,',','.'),0,1,'R',false);

				$pdf->SetXY($margen+115,$altura);
				$pdf->Cell(25,0,number_format( $importeTotalSinIvaCorreos ,2,',','.')." ".EURO,0,1,'R',false);

				$pdf->SetXY(165,$altura);
				$pdf->Cell(25,0,number_format($importeTotalCorreos,2,',','.')." ".EURO,0,1,'R',false);
	
	
	
	$altura += 8;
	$margen = 120;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.2);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$altura += 8;
	$margen = 50;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen+30,$altura);
	$pdf->Cell(25,0,"Total:",0,1,'R',false);
	$margen = $margenInicial;
	
	
	//$importeTotalSinIva = $importeTotalSinIva- 53751.28;

	
	$pdf->SetXY($margen+85,$altura);
	$pdf->Cell(25,0,number_format($unidadesTotal,0,',','.'),0,1,'R',false);
	
	$pdf->SetXY($margen+115,$altura);
	$pdf->Cell(25,0,number_format( $importeTotalSinIva ,2,',','.')." ".EURO,0,1,'R',false);

	$pdf->SetXY(165,$altura);
	$pdf->Cell(25,0,number_format($importeTotal,2,',','.')." ".EURO,0,1,'R',false);
	
	
	
	
	///////////////////////////////////////////////////////////////////////////////////////////////
	if ($_SESSION["permiso_estimacionFranqueo"]==2)
	{
		$anio =  date("Y", strtotime($fechaFin));
		$resultado = cargarDatosGenericosFranqueo($conexion," where anio = ".$anio);
		$resultado2 = verDiasFranqueados ($conexion, " where fecha >= '".date("d-m-Y", strtotime($fechaInicio))."' and fecha <= '".date("d-m-Y", strtotime($fechaFin))."' and comprobado=1", $anio);

		$objetivo = $resultado[0]["objetivoFranqueo"];
		$tantoPorciento = $resultado[0]["tantoPorcientoObjetivo"];
		$objetivo = $objetivo * $tantoPorciento / 100;
		$diasHabiles = $resultado[0]["diasHabiles"];
		$diasFranqueados = $resultado2[0]["diasFranqueados"];

		$diasPorFranquear = $diasHabiles - $diasFranqueados;

		$media = $importeTotalSinIva / $diasFranqueados;
		$estimacionFranqueo = $media * $diasPorFranquear;

		$objetivo2 = $objetivo - $importeTotalSinIva;
		$probabilidad = $estimacionFranqueo - $objetivo2;



		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.2);

		$altura += 5;
		$pdf->Line($margen, $altura, 190, $altura);



		$altura += 8;
		$margen = $margenInicial;
		$pdf->SetFont('Arial','B',10);
		$pdf->SetXY($margen,$altura);
		//$pdf->Cell(25,0,"Probabilidad: ".number_format( $probabilidad ,2,',','.') .EURO,0,1,'R',false);

		$pdf->Cell(25,0,"Objetivo: ".number_format( $resultado[0]["objetivoFranqueo"] ,2,',','.') .EURO,0,1,'L',false);


		$pdf->SetXY($margen+50,$altura);

		$diferenciaPorciento = number_format( $objetivo2*100/$objetivo ,2,',','.');

		$pdf->Cell(0,0,"Resta para el ".$tantoPorciento."%: ".number_format( $objetivo - $importeTotalSinIva ,2,',','.') .EURO. " (".$diferenciaPorciento."%)",0,1,'R',false);


		//$probabilidad = -1;
		$altura += 8;
		$pdf->SetXY($margen,$altura);

		if ($anio==2024) 
		{
			$pdf->Cell(0,0,utf8_decode("Dias que Faltan: ").$diasPorFranquear." (".number_format( ($objetivo - $importeTotalSinIva)/$diasPorFranquear ,2,',','.') .EURO.")",0,1,'L',false);
		}



		if ($probabilidad>0)
		{
			$pdf->SetTextColor(colorVerdeR,colorVerdeG,colorVerdeB);
		}
		if ($probabilidad<0)
		{
			$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
		}


		$pdf->Cell(0,0,utf8_decode("Estimación: ").number_format( $probabilidad ,2,',','.') .EURO,0,1,'R',false);
	}
	
	
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	
		
	
	
	$pdf->Output("I",$ruta."OT - ".date("dmy")." .pdf","UTF-8");
	
	
	

	
	
}
else
{
	
}


?>

	
	
	
	
	
		


