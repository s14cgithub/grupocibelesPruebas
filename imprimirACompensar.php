<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="aCompensar")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	//require($ruta."Archivos Comunes/pagegroup.php");
	//require($ruta."Archivos Comunes/rotate.php");
	//require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	$fechaInicio = $_POST["fechaAComInicio"];	
	$fechaFin = $_POST["fechaAComFin"];
	
	
	
	
	
	$pdf = new FPDF('P','mm','A4');

	//$pdf->SetXY(-10,-5);

	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);
	
	//$pdf->AddPage();
		
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	$altura=10;
	
	$margenInicial=20;
	$margen=$margenInicial;
	
	
	$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 0;
	
	
	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(35,5,utf8_decode("Listado de Facturación A Compensar de Cibeles Mailing"),0,1,'L',false);
	
	$altura = $altura + 10;
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen,$altura);
	
	$pdf->Cell(35,5,utf8_decode("Día del listado: ".date('d/m/Y')),0,1,'L',false);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,utf8_decode("Facturas del día ".date("d-m-Y", strtotime($fechaInicio))." al ".date("d-m-Y", strtotime($fechaFin))),0,1,'R',false);
	$altura = $altura + 10;
	
	
	$contador=0;
	//$alturaSiguientePagina=50;
	$limiteAlturaDatos=260;



	$campos = [
		'nombreFranqueoReal2',
		'codigo_saldo',
		'importePF',		
		'concepto',
		'factura',
		'fecha',
		'aPagar'		
        //'domiciliada', //siempre debe estar
        //'formaPagoReal' //siempre debe estar
	];

	$joins = array();

	
	$fechaInicio = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin = date("d-m-Y", strtotime($fechaFin));

	$filtros = array(
		"conCompensacion" => 1,
		"sinFormaPago" => 1
	);	

	$filtrosOperadores = array(
		array(
			'campo1' => 'fecha',
			'operador' => '>=',
			'valor' => $fechaInicio
		),
		array(
			'campo1' => 'fecha',
			'operador' => '<=',
			'valor' => $fechaFin
		)
	);	

	$joins = array();
	$filtrosLike = array();


	$order = array(
		array('campo' => 'nombreFranqueoReal2', 'dir' => 'ASC'),
		array('campo' => 'factura', 'dir' => 'ASC')
	);


	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];


	$datosFactura = mostrarFacturacionCibelesYCorreos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $filtrosLike, $order);
	
	//echo json_encode($datosFactura);
	//exit();
	
	nuevaPagina($pdf,$altura,$margen,$margenInicial);
	
	$clienteAntiguo = "asjdkf3829348jasdfAntiguoa";
	
	$importeTotal = 0.00;
	$importeTotalCibeles= 0.00;
	$importeTotalCorreos = 0.00;
	
	$primeraVez=true;
	foreach ($datosFactura["datos"] as $row) 
	{		
		$contador++;
		$pdf->SetFont('Arial','',8);
		$altura +=5;		
		
		if ($altura>$limiteAlturaDatos)
		{
			$pdf->AddPage();
			nuevaPagina($pdf,$altura,$margen,$margenInicial);
		}
		
		
		if ($contador%2==0)
		{			
			$pdf->SetFillColor(colorNaranjaR,colorNaranjaG,colorNaranjaB);
		}
		else
		{
			$pdf->SetFillColor(colorBlancoR,colorBlancoG,colorBlancoB);
		}
		
		
		$margen = $margenInicial;
		
		$nombreFranqueo=$row["nombreFranqueoReal2"];
		
		/*
		if ($nombreFranqueo=='')
		{
			$nombreFranqueo = $row["nombre_franqueo"];
		}
		*/
		if ($clienteAntiguo!=$nombreFranqueo)
		{
			$altura +=10;
			if ($altura>$limiteAlturaDatos-20)
			{
				$pdf->AddPage();
				nuevaPagina($pdf,$altura,$margen,$margenInicial);
			}
			
			
			
			
			$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
			$pdf->SetLineWidth(0.3);
			$pdf->Line($margen, $altura-5, 190, $altura-5);
			
			$clienteAntiguo=$nombreFranqueo;
			$pdf->SetFont('Arial','B',10);
			
			if (!$primeraVez)
			{
				$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
				$pdf->SetXY($margen,$altura-10);
				$pdf->Cell(0,0,"Total: ".number_format($importeTotal,2,',','.')." ".EURO,0,1,'R',true);
				$pdf->SetXY($margenInicial,$altura-10);
				$pdf->Cell(70,0,"Total Cibeles: ".number_format($importeTotalCibeles,2,',','.')." ".EURO,0,1,'L',true);
				$pdf->SetXY($margen-140,$altura-10);
				$pdf->Cell(70,0,"Total Correos: ".number_format($importeTotalCorreos,2,',','.')." ".EURO,0,1,'L',true);
				$importeTotal=0.00;
				$importeTotalCibeles= 0.00;
				$importeTotalCorreos = 0.00;				
				$pdf->SetTextColor(0,0,0);
			}
			else
			{
				$primeraVez=false;
			}
			
			$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
			$margen = $margenInicial;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$row["codigo_saldo"],0,1,'L',true);
			$margen += 10;
			$pdf->SetXY($margen,$altura);
			
			$pdf->Cell(0,0,utf8_decode($clienteAntiguo),0,1,'L',true);
			$pdf->SetXY($margen+200,$altura);
			$pdf->Cell(0,0,"Saldo: ".number_format($row["importePF"],2,',','.')." ".EURO,0,1,'R',true);
			
			
			
			$margen = $margenInicial;
			$pdf->SetTextColor(0,0,0);
			$altura += 5;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode($row["concepto"]),0,1,'L',true);
			
			$altura += 5;
			$margen = $margenInicial+20;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode("FACTURA"),0,1,'L',true);
			
			
			$margen += 30;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode("FECHA"),0,1,'L',true);
			
			$margen += 40;
			//$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,utf8_decode("A PAGAR"),0,1,'R',true);
			
			$altura += 5;
		}
		
		
		
		//$altura += 5;
		$margen = $margenInicial+20;
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(15,0,utf8_decode($row["factura"]),0,1,'R',true);
		
		$margen += 30;		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(15,0,$row["fecha"]->format('d/m/Y'),0,1,'R',true);
		
		
		$margen += 40;
		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,number_format($row["aPagar"],2,',','.')." ".EURO,0,1,'R',true);
		
		
		$importeTotal += $row["aPagar"];
		
		
		
		$pos = strpos($row["factura"],  'K');
		$pos2 = strpos($row["factura"],  'Q');
		$pos3 = strpos($row["factura"],  '054');
		$pos4 = strpos($row["factura"],  '041');
		$pos5 = strpos($row["factura"],  '048');
		
		if (($pos !== false && $pos==0) ||($pos2 !== false && $pos2==0)  || ($pos3 !== false && $pos3==0) || ($pos4 !== false && $pos4==0) || ($pos5 !== false && $pos5==0) )
		{
			$importeTotalCorreos += $row["aPagar"];
		}
		else
		{
			$importeTotalCibeles += $row["aPagar"];
		}
			
			
			
		
		
		
		
		
		
	}
	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura+5);
	$pdf->Cell(0,0,"Total: ".number_format($importeTotal,2,',','.')." ".EURO,0,1,'R',true);
	
	$pdf->SetXY($margenInicial,$altura+5);
	$pdf->Cell(70,0,"Total Cibeles: ".number_format($importeTotalCibeles,2,',','.')." ".EURO,0,1,'L',true);
	$pdf->SetXY($margen-230,$altura+5);
	$pdf->Cell(70,0,"Total Correos: ".number_format($importeTotalCorreos,2,',','.')." ".EURO,0,1,'L',true);
	
	$importeTotal=0.00;
	$importeTotalCibeles= 0.00;
	$importeTotalCorreos = 0.00;
			
	
	
	
	
	
	
	
	$pdf->Output("I",date("dmy")." .pdf","UTF-8");
	sqlsrv_close($conn);
	
}
	
	


function nuevaPagina(&$pdf,&$altura,&$margen,$margenInicial)
{
	
	$margen=$margenInicial;
	$altura = 30;
	
	
}
	
	



?>
	
	
	
	
	
	
		


