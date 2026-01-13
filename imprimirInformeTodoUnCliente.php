<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&&$_POST["imprimirAccion"]=="imprimirTodoCliente")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	
	
	$anio = $_POST["anio"];	
	$clayma = $_POST["clayma"];
	$idCliente = $_POST["idCliente"];
	
	//echo $clayma;
	
	if ($clayma=="true")
	{ 
		$datosInforme = cargarTodoUnClienteClayma($conexion,$idCliente, $anio);
		
	}
	else
	{
		$datosInforme = cargarTodoUnCliente($conexion,$idCliente, $anio);
	}
	
	
	if (count($datosInforme)>0)
	{
	
		$nombreCliente = $datosInforme[0]["nombre_empresa"];

		$pdf = new PDF_PageGroup('P','mm','A4');
		$alturaSiguientePagina = 35;
		$margenInicial=20;
		//$pdf->SetXY(-10,-5);

		$pdf->SetLeftMargin(20);
		$pdf->SetRightMargin(20);
		$pdf->StartPageGroup();
		//$pdf->AddPage();
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial,$clayma);
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);

		$contador=0;
		
		$primeraVez1=true;		

		
		
		
		$clienteAntiguo="asdjfaksdjfk238492834usdjfasf";			
		
		$totalPrecio=0.00;
		$totalPrecioTipo=0.00;
		
		
		$margen=$margenInicial;
		
		
		$codigoCliente = $datosInforme[$contador]["codigo_saldo"];
		
		titulos($pdf,$altura,$margenInicial,$margen,$anio,$codigoCliente,$nombreCliente);

		
		//$margen = $margenInicial;
		
		
		$tipoAnterior = "ajsdjñaksdaksj";
		
		
		while ($contador<count($datosInforme))
		{
			
			if ($altura>265)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial,$clayma);
				titulos($pdf,$altura,$margenInicial,$margen,$anio,$codigoCliente,$nombreCliente);
			}
			
			
			if ($tipoAnterior!='ajsdjñaksdaksj' && $tipoAnterior!=$datosInforme[$contador]["origen"])
			{				
				$altura += 8;
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY($margen+40,$altura);
				$pdf->Cell(20,0,number_format($totalPrecioTipo,2,',','.')." ".EURO,0,1,'R',false); 
				
				$totalPrecioTipo=0;				
				$altura += 8;				
			}
			
			$pdf->SetFont('Arial','',10);
			
			if ($altura>265)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial,$clayma);
				titulos($pdf,$altura,$margenInicial,$margen,$anio,$codigoCliente,$nombreCliente);
			}
			
			$tipoAnterior = $datosInforme[$contador]["origen"];
			$totalPrecio+=$datosInforme[$contador]["aPagar"];
			$totalPrecioTipo+=$datosInforme[$contador]["aPagar"];
			
			
			$margen = $margenInicial;
			$altura += 5;
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,$datosInforme[$contador]["numero"],0,1,'R',false); 
			
			
			$margen += 40;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(13,0,$datosInforme[$contador]["origen"],0,1,'C',false); 
			
			$margen += 30;
			$pdf->SetXY($margen,$altura);
			
			$detalle = $datosInforme[$contador]["presupuesto"];
			
			$detalle = str_replace("Factura Original:", "Fac:", $detalle);
			
			
			if (strlen($detalle)> 18)
			{
				$detalle  = substr($detalle,0,18);
			}
			
			//$detalle = $detalle ." " .$altura; 
			
			$pdf->Cell(10,0,$detalle,0,1,'L',false); 
			
							
			$margen += 40;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$datosInforme[$contador]["fecha"]->format('d/m/Y'),0,1,'L',false);
			 


			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($datosInforme[$contador]["aPagar"],2,',','.')." ".EURO,0,0,'R'); 
			
			
			

			$contador++;
		}
		
		$pdf->SetFont('Arial','B',10);
		$altura += 8;
			
		$pdf->SetXY($margen+40,$altura);
		$pdf->Cell(20,0,number_format($totalPrecioTipo,2,',','.')." ".EURO,0,1,'R',false); 
		
		
			
		
		
		if ($altura>265)
		{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial,$clayma);
				titulos($pdf,$altura,$margenInicial,$margen,$anio,$codigoCliente,$nombreCliente);
		}
		
		$pdf->SetFont('Arial','B',12);
		$altura += 8;
		$pdf->SetXY($margen+40,$altura);
		$pdf->Cell(20,0,"Total: ".number_format($totalPrecio,2,',','.')." ".EURO,0,1,'R',false); 

		



		$pdf->Output("I","Informe Franqueo Clientes".date("dmy")." .pdf","UTF-8");
	}
	else
	{
		echo "No hay datos para mostrar";
	}
	
	
}
	
	
function titulos(&$pdf,&$altura,$margenInicial,$margen,$anio,$codigoCliente,$nombreCliente)
{
	$margen=$margenInicial;

		$altura=15;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, diaDeLaSemanaActual().date(", d")." de ".mesActual()." del ". date("Y"),0,1,'R',false);
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, utf8_decode("Datos del año ").$anio,0,1,'R',false);


		$altura += 25;


		$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetFont('Arial','BI',16);
		$pdf->SetXY($margen,$altura);


		$pdf->Cell(0,0,"INFORME DE FACTURAS - ABONOS - FRANQUEO",0,1,'C',false);


		$altura += 15;
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$codigoCliente. " - " . utf8_decode($nombreCliente),0,1,'L',false); 
	
	
	
	
	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',12);
	$pdf->SetXY($margen,$altura);
	
	
	$altura += 15;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Numero"); 

	$margen += 40;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Tipo"); 

	$margen += 30;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Prespuesto"); 

	$margen += 40;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Fecha"); 


	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Precio",0,0,'R'); 


	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	
	$margen=$margenInicial;
	
	$altura = $altura + 5;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.5);
	$pdf->Line($margen, $altura, 190, $altura);
	$pdf->SetFont('Arial','',10);
}

function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$margenInicial,$clayma)
{		
			
	$pdf->AddPage();
	$pdf->AliasNbPages();
	
	$pdf->SetAutoPageBreak(false);
	$margen=20;
	$altura=10;
	
	$pdf->SetXY($margen,$altura);
	if ($clayma=="true")
	{
		$pdf->Image('imagenes/Clayma CMYK.jpg',$margen,$altura,40);
	}
	else
	{
		$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);
	}
	
	
	//titulos($pdf,$altura,$margenInicial,$margen);
	
	
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

	
	if ($clayma=="true")
	{
		$pdf->MultiCell(0,4,utf8_decode('C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07 Clasificación y Manipulados S.A. A-80499221'),0,'C',false);
	}
	else
	{
		$pdf->MultiCell(0,4,utf8_decode('C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07 Cibeles Mailing S.A. A-81339186'),0,'C',false);
	}
	
	
	

	$pdf->SetTextColor(colorNegroR,colorNegroG,colorNegroB);	
	$pdf->SetFont('Arial','',6);
	$altura -= 7; 	
	$pdf->SetXY($margenInicial,$altura);	
	
	$pdf->Cell(0,5,utf8_decode('Página ').$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias(),0,0,'C');
	
	$altura = $alturaSiguientePagina;	

}



?>
	
	
	
	
	
		


