<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirInforme")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	require($ruta."FPDF/fpdf.php");	
	
	$idProducto = $_POST["imprimirInformeProducto"];	
	$fecha = $_POST["imprimirInformeFecha"];
	
	$fecha = date("d-m-Y", strtotime($fecha));

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	
	
	$campos = array(
		'nombre_franqueo',
		'nombreProducto',
		'contarNumAlbaranes',
		'enviosTotal',
		'importeTotal',
		'totalProductoFecha'
	);

	$joins = array('tabla2', 'tabla3');

	$filtros = array(
		'producto' => $idProducto,
		'fecha' => $fecha
	);

	$filtrosOperadores = array();

	$group = array(
		'nombre_franqueo',
		'nombreProducto'
	);

	$order = array(
		array('campo' => 'nombre_franqueo', 'dir' => 'ASC')
	);
	
	$datosInforme = cargarFranqueo($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $group);
	//$datosInforme = mostrarInformeFranqueoResumen($conexion,$idProducto,$fecha);
	
	//echo json_encode($datosinforme);
	//exit;
	
	if (count($datosInforme["datos"])>0)
	{
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);
		//$contador=0;	
		$pdf->SetFillColor(22,84,146);
		$pdf->Rect(10,10,$pdf->GetPageWidth()-20,24,"F");


		$pdf->Image('imagenes/Logo Cibeles Mailing Correos Vertical2.jpg',12,10,20);

		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',10);	
		$altura=280;
		$margen=50;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
		
		

		$pdf->SetTextColor(255,255,255);
		$pdf->SetFont('Arial','B',16);	
		$altura=10;
		$margen=32;
		

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,12,"RESUMEN DE ALBARANES\n".$datosInforme["datos"][0]["nombreProducto"],0,'C',false);
		
		$altura += 30;
		$margen = 10;
		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen,$altura);
		
		
		$pdf->MultiCell(50,12,"Fecha: ".date("d-m-Y", strtotime($fecha)),0,'L',false);
		
		$margen = 100;
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,12,"Importe Total: ".number_format($datosInforme["datos"][0]["total"],2,',','.')." ".EURO,0,'R',false);
		
		
		$margen = 10;
		$altura += 20;
		
		
		$primeraVez=true;
		
		
		
		
		foreach ($datosInforme["datos"] as $row) 
		{
			if ($altura>=265)
			{
				$pdf->AddPage();
				$primeraVez=true;
				
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Arial','',10);	
				$altura=280;
				$margen=50;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
				
				$margen = 10;
				$altura=10;
				
			}
			
			
			if ($primeraVez==true)
			{
				$pdf->SetFillColor(189,218,153);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(60,5,"CLIENTE",0,0,'C',true);		
				$pdf->Cell(40,5,"ALBARANES",0,0,'C',true);		
				$pdf->Cell(40,5,"ENVIOS",0,0,'C',true);		
				$pdf->Cell(50,5,"IMPORTE",0,0,'C',true);

				$pdf->SetFont('Arial','',10);
				$primeraVez = false;
			}
			
			
			$altura +=5;
			$pdf->SetXY($margen,$altura);			
			$pdf->Cell(60,5,$row["nombre_franqueo"],1,0,'C',false);
			$pdf->Cell(40,5,$row["contarNumAlbaranes"],1,0,'C',false);		
			$pdf->Cell(40,5,$row["enviosTotal"],1,0,'C',false);		
			$pdf->Cell(50,5,$row["importeTotal"],1,0,'C',false);
		}
		
		$pdf->Output("I",$ruta."Albaran - ".date("dmy")." .pdf","UTF-8");
		
	}
	sqlsrv_close($conn);
}
else
{
	
}

?>

	
	
	
	
	
		


