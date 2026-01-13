<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirInforme")
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
	$orden = $_POST["imprimirOrden"];	
	$desc = $_POST["imprimirDesc"];
	$domiciliada = $_POST["imprimirDomiciliada"];
	
	
	$condicion = "";
	
	if ($idCliente!="0" && $idCliente!="" )
	{
		$condicion = " where idCliente=".$idCliente;
	}
	if ($fechaInicio!="")
	{
		if ($condicion=="")
		{
			$condicion = " where fecha >= '".$fechaInicio."'";
		}
		else
		{
			$condicion = $condicion." and fecha >= '".$fechaInicio."'";
		}
	}
	
	if ($fechaFin!="")
	{
		if ($condicion=="")
		{
			$condicion = " where fecha <= '".$fechaFin."'";
		}
		else
		{
			$condicion = $condicion." and fecha <= '".$fechaFin."'";
		}
	}
	
	if ($domiciliada=="true")
	{
		if ($condicion=="")
		{
			$condicion = " where domiciliada = 1";
		}
		else
		{
			$condicion = $condicion." and domiciliada = 1";
		}
	}
	
	$condicion = $condicion." order by cliente";
	/*$condicion = $condicion." order by ".$orden;
	
	if ($desc=="true")
	{
		$condicion = $condicion." desc";
	}*/
	
	
	
	
	
	
	$datosFactura = mostrarListadoFacturasPendientesTotal($conexion, $condicion);	
	
	
	if (count($datosFactura)<=0)
	{
		exit;
	}
	
	$titulo = "Listado de facturas pendientes de cobro";
	if ($domiciliada=="true")
	{
		$titulo = "Listado de facturas para cobro domiciliado";
	}
	
	
	
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
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0, "Informe del ".date("d-m-Y", strtotime($fechaInicio)). " al ".date("d-m-Y", strtotime($fechaFin)),0,1,'R',false);
	
	if ($domiciliada=="true")
	{
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, "Domiciliados",0,1,'R',false);
	}
	
	
	
	
	$altura=10;
	$altura += 5;
	$altura += 8;
	$altura += 15;
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',16);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,$titulo,0,1,'C',false);
	
	
	
	
	
	
	
	$altura += 10;
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',12);
	/*$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($nombreCliente),0,1,'L',false);*/
	
	
	
	$contador=0;
	$total=0.000;
	$alturaSiguientePagina = 35;
	
	$idCliente="aaaaaa8a8a8a8a8a8a";
	//$descripcion="aaaaa4568!ajajajajaja8a8a8";
	$unidadesExtension=0.00;
	$totalExtension=0.00;
	
	$unidadesTotal=0.00;
	$totalTotal=0.00;
	
	$primeraVez=true;
	
	while ($contador<count($datosFactura))
	{		
		if (trim($datosFactura[$contador]["idCliente"])!=$idCliente)
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
				
				if ($datosFactura[$contador]["saldo"]<0)
				{					
					$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
				}
				
				
				$margen = 60;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,"Saldo:   ".number_format($datosFactura[$contador]["saldo"],2,',','.')." ".EURO,0,1,'C',false);
				
				$pdf->SetTextColor(0,0,0);
				
				$margen = 120;
				
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,"Total:",0,1,'C',false);
				/*$margen = 120;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,$unidadesExtension,0,1,'C',false);*/
				$margen = $margenInicial;
				
				if ($totalExtension<0)
				{					
					$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
				}
				
				
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,number_format($totalExtension,2,',','.')." ".EURO,0,1,'R',false);
				$pdf->SetTextColor(0,0,0);
				$altura += 3;
				$margen = 110;
				$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
				$pdf->SetLineWidth(0.2);
				$pdf->Line($margen, $altura, 190, $altura);
			}
			else
			{
				$primeraVez=false;
			}
			
			
			
			$margen = $margenInicial;			
			$totalExtension=0.00;
			$idCliente = trim($datosFactura[$contador]["idCliente"]);
			$altura += 10;
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode($datosFactura[$contador]["idCliente"] ." - ". $datosFactura[$contador]["cliente"]),0,1,'L',false);
			
			//$descripcion="aaaaa4568!ajajajajaja8a8a8";
			titulos($pdf,$altura,$margenInicial,$margen);
			
			
			
		}
		
		if ($altura>260)
		{			
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
		}
		
		/*if (trim($datosFranqueo[$contador]["descripcion"]) != $descripcion)
		{
			$descripcion = trim($datosFranqueo[$contador]["descripcion"]);
			$altura += 5;
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode($descripcion),0,1,'L',false);
			
			$altura -= 5;
			
		}*/
		
		
		
		
		$altura += 5;
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',8);
		
		$margen = $margenInicial;
		$pdf->SetXY($margen,$altura);		
		$pdf->Cell(0,0,utf8_decode($datosFactura[$contador]["origen"]),0,1,'L',false);
		
		$margen = 45;
		$pdf->SetXY($margen,$altura-2.5);		
		$pdf->MultiCell(45, 5, utf8_decode($datosFactura[$contador]["descripcion"]),0, 'C', 0);
		
		$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosFactura[$contador]["descripcion"]));
		
		
		
		$numeroDeFilas = ceil($anchoDescripcion / 45);
		
		/*$margen =0;
		$pdf->SetXY($margen,$altura-2.5);		
		$pdf->MultiCell(45, 5, $numeroDeFilas." - ".$anchoDescripcion,0, 'L', 0);*/
		
		if ($numeroDeFilas<1)
		{
			$numeroDeFilas = 1;
		}

		
		
		

		$margen = 10;
		$pdf->SetXY($margen,$altura);		
		$pdf->Cell(0,0,utf8_decode($datosFactura[$contador]["factura"]),0,1,'C',false);
		
		$margen = 60;;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosFactura[$contador]["formaPago"]),0,1,'C',false);
		
		
		$margen = 120;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$datosFactura[$contador]["fecha"]->format('d/m/Y'),0,1,'C',false);
		
		
		$margen = $margenInicial;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,number_format($datosFactura[$contador]["aPagar"],2,',','.')." ".EURO,0,1,'R',false);
		
		
		$margen = $margenInicial;
		
		
		
		$totalExtension += $datosFactura[$contador]["aPagar"];		
		$totalTotal += $datosFactura[$contador]["aPagar"];
		
		
		$altura = $altura + (5*($numeroDeFilas-1));
		
		$contador++;
	}
	
	$pdf->SetFont('Arial','B',8);
	$altura += 5;

	if ($datosFactura[count($datosFactura)-1]["saldo"] < 0)
	{					
		$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	}


	$margen = 60;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Saldo:   ".number_format($datosFactura[count($datosFactura)-1]["saldo"] ,2,',','.')." ".EURO,0,1,'C',false);

	$pdf->SetTextColor(0,0,0);
	
	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total:",0,1,'C',false);
	//$margen = 120;
	//$pdf->SetXY($margen,$altura);
	//$pdf->Cell(0,0,$unidadesExtension,0,1,'C',false);
	$margen = $margenInicial;
	if ($totalExtension<0)
	{					
		$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	}
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,number_format($totalExtension,2,',','.')." ".EURO,0,1,'R',false);
	$pdf->SetTextColor(0,0,0);
	$altura += 3;
	$margen = 110;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.2);
	$pdf->Line($margen, $altura, 190, $altura);
	
	
	//TOTAL 
	if ($altura>260)
	{			
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
	}
	
	
	
	
	
	$altura += 10;
	$margen = 120;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total:",0,1,'C',false);
	$margen = 120;
	/*$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,$unidadesTotal,0,1,'C',false);*/
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
	$pdf->Cell(0,0,"Origen",0,1,'L',false);
	
	$margen = 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("Nº Factura"),0,1,'C',false);
	$margen = 60;
	$pdf->SetXY($margen,$altura);	
	$pdf->Cell(0,0,"Forma de Pago",0,1,'C',false);	
	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Fecha",0,1,'C',false);
	$margen = $margenInicial;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Importe",0,1,'R',false);
	
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
	
	
	
	
	
		


