<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&&$_POST["imprimirAccion"]=="imprimirInforme")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	$fechaInicio = $_POST["fechaInicioConsumoProductoModal2"];	
	$fechaFin = $_POST["fechaFinConsumoProductoModal2"];	
	
	
	$datos = verConsumoPorProductosFranqueo2($conexion, $fechaInicio, $fechaFin);
	
	
	
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
	$pdf->SetFont('Arial','BI',14);
	$pdf->SetXY($margen,$altura);
	
	$pdf->Cell(0,0,"CONSUMO POR PRODUCTOS - APLICADO DESCUENTOS DE CORREOS",0,1,'C',false);
	
	
	$altura += 15;
	$margen=$margenInicial;
	$alturaSiguientePagina=15;
	$limiteAlturaDatos = 260;
	$mostrarPrecio=0;
	$numPagina=0;	
	
	$contador=0;
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"PRODUCTO",0,1,'L',false);
	
	
	$separacionColumnas = 25;
	
	$margen += 20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"UNIDADES",0,1,'R',false);
	
	
	
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"LOCAL",0,1,'R',false);

	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"D1",0,1,'R',false);
	
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"D2",0,1,'R',false);
	
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"EXT",0,1,'R',false);
	
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"TOTAL",0,1,'R',false);
	
	
	
	
	
	
	
	$margen = $margenInicial;
	$pdf->SetFont('Arial','',10);
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.5);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$pdf->SetTextColor(0,0,0);
	$unidadesTotal=0;
	$importeTotal=0.00;
	$importeTotalSinIva=0.00;
	
	//$unidadesTotalCorreos=0;
	//$importeTotalCorreos=0.00;
	//$importeTotalSinIvaCorreos = 0.00;
	$retribucionCorreos = "asdkfalñsdkfj234jaskdñfaj";
	
	$productoAnterior = "asdfasdfasdf";
	//$orden=0;
	$importeTotalExtranjero=0;
	$totalLinea = 0;
	$totalUnidades=0;
	$totalLocal=0;
	$totalD1=0;
	$totalD2=0;
	$totalEXT=0;
	$totalTotal=0;
	
	$totalUnidadesDef=0;
	$totalLocalDef=0;
	$totalD1Def=0;
	$totalD2Def=0;
	$totalEXTDef=0;
	$totalTotalDef=0;
	
	while ($contador<count($datos)) 
	{
		
		if ($retribucionCorreos!=$datos[$contador]["retribucionCorreos"])//se imprime el total de la retribucion de correos
		{
			if ($retribucionCorreos == "asdkfalñsdkfj234jaskdñfaj")
			{
				$altura -= 5;
			}
			else
			{
				$altura += 10;
				$margen = 40;
				$pdf->SetFont('Arial','B',8);
				//$pdf->SetXY($margen+30,$altura);
				//$pdf->Cell(25,0,"Total:",0,1,'R',false);
				$margen = $margenInicial+20;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,"Total: ".number_format($totalUnidades,0,',','.'),0,1,'R',false);
				
				$margen += $separacionColumnas;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,number_format( $totalLocal ,2,',','.')." ".EURO,0,1,'R',false);

				$margen += $separacionColumnas;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,number_format( $totalD1 ,2,',','.')." ".EURO,0,1,'R',false);
				
				$margen += $separacionColumnas;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,number_format( $totalD2 ,2,',','.')." ".EURO,0,1,'R',false);
				
				$margen += $separacionColumnas;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,number_format( $totalEXT ,2,',','.')." ".EURO,0,1,'R',false);
				
				$margen += $separacionColumnas;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,number_format($totalTotal ,2,',','.')." ".EURO,0,1,'R',false);
				
				
				//$unidadesTotalCorreos=0;
				//$importeTotalSinIvaCorreos=0.00;
				//$importeTotalCorreos = 0.00;
				
				
				
				
				
				
				$totalUnidadesDef += $totalUnidades;
				$totalLocalDef += $totalLocal;
				$totalD1Def += $totalD1;
				$totalD2Def += $totalD2;
				$totalEXTDef += $totalEXT;
				$totalTotalDef += $totalTotal;
				
				$totalLinea = 0;
				$totalUnidades=0;
				$totalLocal=0;
				$totalD1=0;
				$totalD2=0;
				$totalEXT=0;
				$totalTotal=0;
				
				
			}
			
			$margen = $margenInicial;
			$retribucionCorreos = $datos[$contador]["retribucionCorreos"];
			$altura +=10;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$datos[$contador]["retribucionCorreos"],0,1,'L',false);
			
		}
		
		if ($datos[$contador]["producto2"]!=$productoAnterior)
		{
			$margen =$margenInicial;
			$altura +=8;
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$datos[$contador]["producto2"],0,1,'L',false);
			
			$margen = $margenInicial+20;
			//$orden=0;
			$importeTotalExtranjero=0;
			$totalLinea=0;
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($separacionColumnas,0,number_format($datos[$contador]["sumaUnidades"],0,',','.'),0,1,'R',false);
			$productoAnterior = $datos[$contador]["producto2"];
			
			//$totalUnidades += $datos[$contador]["sumaUnidades"];
		
		}
		else
		{
			//$margen += $separacionColumnas;
		}
		
		
		
		$contadorParaSumar = 0;
		if ($datos[$contador]["orden"]>3)
		{
			
			$importeTotalExtranjero = $datos[$contador]["sinIva"];
			$totalLinea += $datos[$contador]["sinIva"];
			
			//$totalUnidades = $datos[$contador]["unidades"];
			//$totalEXT =$datos[$contador]["sinIva"];
			
			
			if ($contador+1<count($datos))
			{
				if ($datos[$contador+1]["orden"]>3 && $datos[$contador]["producto2"]==$datos[$contador+1]["producto2"])
				{
					$importeTotalExtranjero+=$datos[$contador+1]["sinIva"];
					$totalLinea += $datos[$contador+1]["sinIva"];
					
					$totalUnidades += $datos[$contador+1]["unidades"];
					$totalEXT +=$datos[$contador+1]["sinIva"];
					//$contador++;
					$contadorParaSumar++;
								
				}
			}
			if ($contador+2<count($datos))
			{
				if ($datos[$contador+2]["orden"]>3 && $datos[$contador]["producto2"]==$datos[$contador+2]["producto2"])
				{
					$importeTotalExtranjero+=$datos[$contador+2]["sinIva"];
					$totalLinea += $datos[$contador+2]["sinIva"];
					
					$totalUnidades += $datos[$contador+2]["unidades"];
					$totalEXT +=$datos[$contador+2]["sinIva"];
					//$contador++;					
					$contadorParaSumar++;
				}
				
			}
			
			$margen += $separacionColumnas;	
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($separacionColumnas,0,number_format($importeTotalExtranjero,2,',','.')." ".EURO,0,1,'R',false);
			
			$margen += $separacionColumnas;	
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($separacionColumnas,0,number_format($totalLinea,2,',','.')." ".EURO,0,1,'R',false);
			
		}		
		else
		{
			/*$margen += $separacionColumnas;	
			
			if ($datos[$contador]["retribucionCorreos"]=="ENVIOS ESPECIALES")
			{
				$margen += $separacionColumnas;	
				$margen += $separacionColumnas;	
				$margen += $separacionColumnas;	
				$margen += $separacionColumnas;	
			}
			
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($separacionColumnas,0,number_format($datos[$contador]["sinIva"],2,',','.')." ".EURO,0,1,'R',false);
			$totalLinea += $datos[$contador]["sinIva"];*/
			
			$margen = $margenInicial+20;
			if ($datos[$contador]["retribucionCorreos"]=="ENVIOS ESPECIALES")
			{
				$margen += $separacionColumnas;
				$margen += $separacionColumnas;	
				$margen += $separacionColumnas;	
				$margen += $separacionColumnas;	
				$margen += $separacionColumnas;	
			}
			else if  ($datos[$contador]["orden"]==1)
			{
				$margen += $separacionColumnas;
			}
			else if  ($datos[$contador]["orden"]==2)
			{
				$margen += $separacionColumnas;
				$margen += $separacionColumnas;
			}
			else if  ($datos[$contador]["orden"]==3)
			{
				$margen += $separacionColumnas;
				$margen += $separacionColumnas;
				$margen += $separacionColumnas;
			}
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($separacionColumnas,0,number_format($datos[$contador]["sinIva"],2,',','.')." ".EURO,0,1,'R',false);
			$totalLinea += $datos[$contador]["sinIva"];
			
		}
		
		if ($contador+1<count($datos))
		{
			if ($productoAnterior != $datos[$contador+1]["producto2"] && $datos[$contador]["orden"]<=3 )
			{
				if  ($datos[$contador]["orden"]==1)
				{
					$margen += $separacionColumnas;
					$margen += $separacionColumnas;
					$margen += $separacionColumnas;
				}
				else if  ($datos[$contador]["orden"]==2)
				{
					$margen += $separacionColumnas;
					$margen += $separacionColumnas;
				}
				else if  ($datos[$contador]["orden"]==3)
				{	
					$margen += $separacionColumnas;
				}
				
				
				
				//$margen += $separacionColumnas;	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,"0 ".EURO,0,1,'R',false);
				$margen += $separacionColumnas;	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($separacionColumnas,0,number_format($totalLinea,2,',','.')." ".EURO,0,1,'R',false);
			}
			
		}
		
		
		//$orden += 1;
		
		if ($datos[$contador]["orden"]==1)//LOCAL
		{
			$totalLocal +=$datos[$contador]["sinIva"];
		}
		if ($datos[$contador]["orden"]==2)//D1
		{
			$totalD1 +=$datos[$contador]["sinIva"];
		}
		if ($datos[$contador]["orden"]==3)//D2
		{
			$totalD2 +=$datos[$contador]["sinIva"];
		}
		if ($datos[$contador]["orden"]==4||$datos[$contador]["orden"]==5||$datos[$contador]["orden"]==6)//EXT
		{
			$totalEXT +=$datos[$contador]["sinIva"];
		}
		
		$totalTotal = $totalLocal + $totalD1 + $totalD2 + $totalEXT;
		
		$totalUnidades += $datos[$contador]["unidades"];
		
		

		
		$unidadesTotal += $datos[$contador]["unidades"];
		$importeTotal += $datos[$contador]["conIva"];
		$importeTotalSinIva += $datos[$contador]["sinIva"];
		
		//$unidadesTotalCorreos += $datos[$contador]["unidades"];
		//$importeTotalCorreos += $datos[$contador]["conIva"];
		//$importeTotalSinIvaCorreos += $datos[$contador]["sinIva"];
		
		$contador += $contadorParaSumar;
		$contador++;
	}
	
	
	$altura += 10;
	$margen = 40;
	$pdf->SetFont('Arial','B',8);
			
	$margen = $margenInicial+20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"Total: ".number_format($totalUnidades,0,',','.'),0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"0 ".EURO,0,1,'R',false);

	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalD1 ,2,',','.')." ".EURO,0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalD2 ,2,',','.')." ".EURO,0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalEXT ,2,',','.')." ".EURO,0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format($totalLocal ,2,',','.')." ".EURO,0,1,'R',false);
	
	

	
	$totalTotalDef += $totalLocal;
	$totalUnidadesDef += $totalUnidades; 
	
	
	
	
	
	$altura += 8;
	$margen = 120;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.2);
	$pdf->Line($margen, $altura, 190, $altura);
	
	$altura += 8;
	$margen = 50;
	$pdf->SetFont('Arial','UB',8);
	//$pdf->SetXY($margen+30,$altura);
	//$pdf->Cell(25,0,"Total:",0,1,'R',false);
	//$margen = $margenInicial;
	
	
	//$importeTotalSinIva = $importeTotalSinIva- 53751.28;

	

	
	
	$margen = $margenInicial+20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,"Total: ".number_format($totalUnidadesDef,0,',','.'),0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalLocalDef ,2,',','.')." ".EURO,0,1,'R',false);

	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalD1Def ,2,',','.')." ".EURO,0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalD2Def ,2,',','.')." ".EURO,0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format( $totalEXTDef ,2,',','.')." ".EURO,0,1,'R',false);
				
	$margen += $separacionColumnas;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell($separacionColumnas,0,number_format($totalTotalDef ,2,',','.')." ".EURO,0,1,'R',false);
	
	
	
	$importeTotalSinIva= $totalTotalDef;
	//$importeTotalSinIva= 4441396.28;
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

		$pdf->Cell(25,0,"Objetivo: ".number_format( $resultado[0]["objetivoFranqueo"] ,2,',','.') ." ".EURO,0,1,'L',false);


		$pdf->SetXY($margen+50,$altura);

		$diferenciaPorciento = number_format( $objetivo2*100/$objetivo ,2,',','.');

		
		$pdf->Cell(0,0,"Media anual actual: ".number_format($media ,2,',','.')." ".EURO,0,1,'R',false);


		
		//$probabilidad = -1;
		$altura += 8;
		$pdf->SetXY($margen,$altura);

		
		
		
		if ($diasPorFranquear=="0")
		{
			$pdf->Cell(0,0,utf8_decode("Dias que Faltan: ").$diasPorFranquear." (".number_format( 0 ,2,',','.')." ".EURO.")",0,1,'R',false);
		}
		else
		{
			$pdf->Cell(0,0,utf8_decode("Dias que Faltan: ").$diasPorFranquear." (".number_format( ($objetivo - $importeTotalSinIva)/$diasPorFranquear ,2,',','.') ." ".EURO.")",0,1,'R',false);
		}
		
		
		
		$pdf->Cell(0,0,"Resta para el ".$tantoPorciento."% (".number_format($objetivo,2,',','.') ." ".EURO."): ".number_format( $objetivo - $importeTotalSinIva ,2,',','.') ." ".EURO. " (".$diferenciaPorciento."%)",0,1,'L',false);


	
		$altura +=8;
		$pdf->SetXY($margen,$altura);
		if ($diasPorFranquear=="0")
		{
			$pdf->Cell(0,0,utf8_decode("Dias que Faltan: ").$diasPorFranquear." (".number_format( 0 ,2,',','.')." ".EURO.")",0,1,'R',false);
		}
		else
		{
			$pdf->Cell(0,0,utf8_decode("Dias que Faltan: ").$diasPorFranquear." (".number_format( ( $resultado[0]["objetivoFranqueo"] - $importeTotalSinIva)/$diasPorFranquear ,2,',','.') ." ".EURO.")",0,1,'R',false);
		}


		$diferencia100=number_format(($resultado[0]["objetivoFranqueo"] - $importeTotalSinIva)*100/$resultado[0]["objetivoFranqueo"] ,2,',','.');


		

		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,"Resta para el 100% (".number_format( $resultado[0]["objetivoFranqueo"]  ,2,',','.') ." ".EURO."): ".number_format( $resultado[0]["objetivoFranqueo"] - $importeTotalSinIva ,2,',','.') ." ".EURO. " (".$diferencia100."%)",0,1,'L',false);
		$altura +=8;
		$pdf->SetXY($margen,$altura);

		if ($probabilidad>0)
		{
			$pdf->SetTextColor(colorVerdeR,colorVerdeG,colorVerdeB);
		}
		if ($probabilidad<0)
		{
			$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
		}


		$pdf->Cell(0,0,utf8_decode("Estimación: ").number_format( $probabilidad ,2,',','.')." ".EURO,0,1,'R',false);
	}
	
	
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	
		
	
	
	$pdf->Output("I",$ruta."OT - ".date("dmy")." .pdf","UTF-8");
	
	
	

	
	
}
else
{
	
}


?>

	
	
	
	
	
		


