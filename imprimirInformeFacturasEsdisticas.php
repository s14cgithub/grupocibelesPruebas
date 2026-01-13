<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirFacEstadisticas")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	//require($ruta."Archivos Comunes/pagegroup.php");
	//require($ruta."Archivos Comunes/rotate.php");
	//require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	
	
	$anio = $_POST["anioFacturaEstd"];	
	$orden = $_POST["ordenFacturaEstd"];
	$origen = $_POST["origenFacturaEstd"];
	
	
	$pdf = new FPDF('P','mm','A4');

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	//$pdf->AddPage();
		
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	
	$margenInicial=10;
	
	
	
	$pdf->AddPage();
	//$pdf->SetAutoPag
	
	
	
	
	$margen=$margenInicial;
	$altura=10;	
	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	
	if ($origen=="Cibeles")
	{
		$pdf->Cell(35,5,utf8_decode("ESTADISTICAS DE FACTURAS DE CIBELES MAILING"),0,1,'L',false);
	}
	else
	{
		$pdf->Cell(35,5,utf8_decode("ESTADISTICAS DE FACTURAS DE CLAYMA"),0,1,'L',false);
	}
	
	
	$altura = $altura + 10;
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen,$altura);
	
	$pdf->Cell(35,5,utf8_decode("Día del listado: ".date('d/m/Y')),0,1,'L',false);
	
	$altura += 10;
	
	
	
	
	
	
	$contador=0;
	//$alturaSiguientePagina=50;
	$limiteAlturaDatos=260;
	
	
		
	
	if ($origen=="Cibeles")
	{
		$datos = verEstadisticasFacturasPorAnio($conexion, $anio, $orden);
	}
	else
	{
		$datos = verEstadisticasFacturasPorAnioClayma($conexion, $anio, $orden);
	}
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.2);
	
	nuevaPagina($pdf,$altura,$margen,$margenInicial,$origen);
	
	
	
	//$altura += 5;
	$margen=$margenInicial;
	$alturaSiguientePagina=15;
	$limiteAlturaDatos = 260;
	$mostrarPrecio=0;
	$numPagina=0;	
	
	
	
	
	
	
	
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',8);
	
	
	$totalManipulado=0.00;
	$totalFranqueo=0.00;
	$totalNumFacManipulado=0;
	$totalNumFacFranqueo=0;
	
	
	
	$contador=0;
	while ($contador<count($datos))
	{
		
		
		if ($altura>$limiteAlturaDatos)
		{
			$pdf->AddPage();
			nuevaPagina($pdf,$altura,$margen,$margenInicial,$origen);
			//$altura +=5;
		}
		
		if ($contador%2==0)
		{
			$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);	
		}
		else
		{
			$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
		}
		
		$margen=$margenInicial;
		$altura +=5;
	
		
		$codigoSaldo="";
		$codigoSaldo = $datos[$contador]["codigo_saldo"];
		while (strlen($codigoSaldo)<4)
		{
			$codigoSaldo = '0'.$codigoSaldo;
		}
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(90,5,$codigoSaldo." ".utf8_decode(ucwords(strtolower($datos[$contador]["nombre_empresa"]))),0,1,'L',true);

		$margen = 100;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,number_format($datos[$contador]["manipulado"],2,',','.')." ".EURO,0,1,'R',true);


		$margen = 120;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,number_format($datos[$contador]["franqueo"],2,',','.')." ".EURO,0,1,'R',true);
		
		$margen = 140;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(10,5,number_format($datos[$contador]["numFacManipulado"],0,',','.'),0,1,'R',true);

		$margen = 150;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(10,5,number_format($datos[$contador]["numFacturasCorreos"],0,',','.'),0,1,'R',true);
		
		
		$margen = 160;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,number_format($datos[$contador]["mediaManipulado"],2,',','.')." ".EURO,0,1,'R',true);
		
		$margen = 180;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,5,number_format($datos[$contador]["mediaFranqueo"],2,',','.')." ".EURO,0,1,'R',true);
		
		
		$totalManipulado += $datos[$contador]["manipulado"];
		$totalFranqueo += $datos[$contador]["franqueo"];
		$totalNumFacManipulado += $datos[$contador]["numFacManipulado"];
		$totalNumFacFranqueo += $datos[$contador]["numFacturasCorreos"];
		
		
		$contador++;
	}	
	
	
	
	$altura +=10;
	$margen=$margenInicial;
	
	


	
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(85,5,"Total:",0,1,'R',true);

	$margen = 100;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,5,number_format($totalManipulado,2,',','.')." ".EURO,0,1,'R',true);


	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,5,number_format($totalFranqueo,2,',','.')." ".EURO,0,1,'R',true);

	$margen = 140;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(10,5,number_format($totalNumFacManipulado,0,',','.'),0,1,'R',true);

	$margen = 150;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(10,5,number_format($totalNumFacFranqueo,0,',','.'),0,1,'R',true);


	$margen = 160;
	$pdf->SetXY($margen,$altura);
	$mediaManipulado = $totalManipulado/$totalNumFacManipulado;
	$pdf->Cell(20,5,number_format($mediaManipulado,2,',','.')." ".EURO,0,1,'R',true);

	$margen = 180;
	$pdf->SetXY($margen,$altura);
	
	if ($origen=="Cibeles")
	{
		$mediaFranqueo = $totalFranqueo/$totalNumFacFranqueo;
	}
	else		
	{
		$mediaFranqueo=0;
	}
	
	$pdf->Cell(20,5,number_format($mediaFranqueo,2,',','.')." ".EURO,0,1,'R',true);
	
	
	
	$pdf->Output("I",$ruta."OT - ".date("dmy")." .pdf","UTF-8");
	
	
	
}
else
{
	
}


function nuevaPagina(&$pdf,&$altura,&$margen,$margenInicial,$origen)
{
	
	$margen=$margenInicial;
	$altura=10;	
	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	
	if ($origen=="Cibeles")
	{
		$pdf->Cell(35,5,utf8_decode("ESTADISTICAS DE FACTURAS DE CIBELES MAILING"),0,1,'L',false);
	}
	else
	{
		$pdf->Cell(35,5,utf8_decode("ESTADISTICAS DE FACTURAS DE CLAYMA"),0,1,'L',false);
	}
	
	$altura = $altura + 10;
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen,$altura);
	
	$pdf->Cell(35,5,utf8_decode("Día del listado: ".date('d/m/Y')),0,1,'L',false);
	
	
	
	$margen=$margenInicial;
	$altura = 30;
	
	
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(90,5,utf8_decode("Empresa"),"B",1,'L',false);
	
	$margen = 100;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,5,utf8_decode("Manipulado"),'B',1,'C',false);
	
	
	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,5,utf8_decode("Franqueo"),'B',1,'C',false);
	
	$margen = 140;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(10,5,utf8_decode("Nº M."),'B',1,'R',false);
	
	$margen = 150;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(10,5,utf8_decode("Nº F."),'B',1,'R',false);
	
	$margen = 160;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,5,utf8_decode("Med. Man."),'B',1,'R',false);
	$margen += 18;
	
	$margen = 180;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,5,utf8_decode("Med. Fran."),'B',1,'R',false);
	
	
}


?>

	
	
	
	
	
		


