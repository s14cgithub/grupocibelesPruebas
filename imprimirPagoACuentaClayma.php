<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&&$_POST["imprimirAccion"]=="imprimirPresu")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$GLOBALS['informePFoPago'] = "informePFoPago";
		
	
	require($ruta."FPDF/fpdf.php");
	
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFacturaClayma.php");
	
	
	
	
	//$numPresupuesto = $_POST["imprimirNumPresupuesto"];	
	
	$numPresupuesto = $_POST["imprimirPagoACuentaNumPresupuestoClayma"];
	$numPresupuestoContador = $_POST["imprimirPagoACuentaNumPresupuestoContadorClayma"];	

	

	if (substr($numPresupuesto, 0,1)=="9")
	{
		$campos = ['fechaCreacion',
		'nombre_empresaClayma',
		'direccionClayma',
		'codigo_postalClayma',
		'localidadClayma',
		'provinciaClayma',
		'nifClayma',
		'conceptoCampana',		
		'importe'
		];
		$joins=['tabla6'];
		$filtros = [
			'presupuesto' => $numPresupuesto		
		];
		$filtrosOperadores = array(
			array(
				'campo1' => 'codigo_saldoClayma',
				'operador' => '=',
				'campo2' => 'codigoClayma'
			)
		);					
	}
	else 
	{
		$campos = ['fechaCreacion',
		'nombre_empresaClayma',
		'direccionClayma',
		'codigo_postalClayma',
		'localidadClayma',
		'provinciaClayma',
		'nifClayma',
		'campana',	
		'importe'
		];
		$joins=['tabla4', 'tabla6'];
		$filtros = [
			'presupuesto' => $numPresupuesto,
			'contador' => $numPresupuestoContador		
		];
		$filtrosOperadores = array();					
	}

	$order=array();
	$group=array();

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	
	$datosProvisionFondo = cargarProvisionDeFondos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores,$group , $order);

	sqlsrv_close($conn);	


	
	
	

	if ($numPresupuestoContador!="")
	{
		$numPresupuesto = $numPresupuesto." - ".$numPresupuestoContador;		
	}
	else
	{
		$numPresupuestoContador="";
	}
	
	
	
	//die $datosProvisionFondo;
		
	
	$pdf = new cabeceraFactura('P','mm','A4');

	//$pdf->SetXY(-10,-5);


	$pdf->AddPage();
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);	
	$pdf->SetFont('Arial','B',10);
	
	$altura=10;
	$margen=10;
	
	//$pdf->Text($margen,$altura,$datosPresupuesto[0]["fecha"]->format('d/m/Y'));
	
	//$pdf->SetTextColor(13,140,252);
	
	//$pdf->Image('imagenes/CibelesMailing.png',$margen,$altura,75);
	
	
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$altura = $altura + 5;
	$pdf->SetXY($margen+155,$altura);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(35,5,utf8_decode("PAGO A CUENTA"),1,1,'C',false);
	$altura = $altura + 5;
	$pdf->SetXY($margen+155,$altura);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(35,5,$numPresupuesto,1,1,'C',false);
	
	
	$pdf->SetFont('Arial','',8);
	$altura = $altura + 8;
	$pdf->SetXY($margen+155,$altura);
	$pdf->Cell(35,5,"Fecha: ".$datosProvisionFondo["datos"][0]["fechaCreacion"]->format('d/m/Y'),0,1,'C',false);
	
	
	
	
	
	$altura = $altura + 25;
	
	$pdf->SetFont('Arial','B',20);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"PAGO A CUENTA",0,1,'C',false);
	
	
	
	
	$altura = $altura + 10;
	$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 200, $altura);
	
	$altura = $altura + 5;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(25,0,"CLIENTE:",0,1,'R',false);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen+25,$altura-2);	
	$pdf->MultiCell(0,4,utf8_decode($datosProvisionFondo["datos"][0]["nombre_empresa"]),0,'L',false);
	
	
	$anchoDescripcion = $pdf->GetStringWidth($datosProvisionFondo["datos"][0]["nombre_empresa"]);
	$numeroDeFilas = ceil ($anchoDescripcion / 175);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}
	//$pdf->SetXY($margen+70,$altura+10);	
	//$pdf->Cell(25,0,$numeroDeFilas,0,1,'R',false);
	$altura = $altura + (5*$numeroDeFilas);
	
	
		
	//$altura = $altura + 5;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(25,0,"DOMICILIO:",0,1,'R',false);
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen+25,$altura);
	$pdf->Cell(0,0,utf8_decode($datosProvisionFondo["datos"][0]["direccion"]),0,1,'L',false);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen+25,$altura);
	$pdf->Cell(0,0,$datosProvisionFondo["datos"][0]["codigo_postal"]." ".$datosProvisionFondo["datos"][0]["localidad"],0,1,'L',false);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen+25,$altura);
	$pdf->Cell(0,0,$datosProvisionFondo["datos"][0]["provincia"],0,1,'L',false);
	
	
		
	$altura = $altura + 5;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(25,0,"CIF:",0,1,'R',false);
	
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen+25,$altura);
	$pdf->Cell(0,0,$datosProvisionFondo["datos"][0]["nif"],0,1,'L',false);
	
	$altura = $altura + 5;
	$pdf->Line($margen, $altura, 200, $altura);
	
	
	
	$altura = $altura + 5;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(25,0,"DESCRIPCION:",0,1,'R',false);
	
	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen+30,$altura-2.5);	
	$pdf->MultiCell(0,5,utf8_decode($datosProvisionFondo["datos"][0]["campana"]),0,'C',false);
	
	
	$altura = $altura + 15;
	$margen = $margen + 2.5;
	$pdf->SetXY($margen,$altura);	
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(25,0,utf8_decode("Provisión de fondos para el franqueo de correspondencia en su nombre:"),0,1,'L',false);
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen+120,$altura);	
	$pdf->Cell(0,0,number_format($datosProvisionFondo["datos"][0]["importe"],2,',','.')." ".EURO,0,'R',false);
	
	
	$altura = $altura + 15;
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($margen,$altura-2.5);	
	$pdf->MultiCell(0,0,"Compromiso de pago:",0,'L',false);
	
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(0.2);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(5,5,"",1,'L',false);
	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("Mediante transferencia bancaria. Enviar justificante de transferencia por fax o por correo electrónico a comercial@grupocibeles.es"),0,'L',false);
	
	
	$pdf->SetFont('Arial','B',11);
	$altura = $altura + 15;	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("CAIXABANK: ES42 2100 1945 2202 0006 7880 BIC: CAIXESBBXX"),0,'L',false);
	
	/*$altura = $altura + 8;	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("SANTANDER: ES48 0049 1839 4621 1043 1601 BIC:BSCHESMMXXX"),0,'L',false);
	
	$altura = $altura + 8;	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("CAIXABANK: ES21 2100 1945 2402 0000 7147 BIC: CAIXESBBXX"),0,'L',false);*/
	
	$altura = $altura + 16;	
	$pdf->SetFont('Arial','B',9);
	$altura = $altura + 10;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(5,5,"",1,'L',false);
	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("Talón nominativo a Clasificación y Manipulados S.A."),0,'L',false);
	
	$altura = $altura + 10;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(5,5,"",1,'L',false);
	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0,5,utf8_decode("Habiendo confirmado que se dispone de saldo en su cuenta suficiente para este trabajo y el resto de trabajos en curso, cubrir el importe de esta provisión de fondos con el saldo."),0,'L',false);
	
	$altura = $altura + 25;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(5,5,"",1,'L',false);
	
	$pdf->SetXY($margen+8,$altura);	
	$pdf->MultiCell(0, 5, "Otros.......................................................................................................................................................................................",0,'L',false);
	
	
	$altura = $altura + 40;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,5,"Firma y Sello",0,'C',false);
	
	$altura = $altura + 10;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,5,"Firmado:",0,'C',false);
	
	/*$altura = $altura + 13;
	$pdf->SetDrawColor(255,189,10);
	$pdf->SetLineWidth(0.8);
	$pdf->Line($margen, $altura, 200, $altura);
	
	
	
	$altura = $altura + 5;
	$pdf->SetTextColor(13,140,252);
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,5,"C/ Torneros, 12 P.I. Los Ángeles. 28906 Getafe (Madrid). Tel.: 91 684 37 37 Fax: 91 684 34 07
Cibeles Mailing S.A. A-81339186",0,'C',false);*/
	
	
	$pdf->Output("F",rutaPresupuestos.$numPresupuesto." - ProvisionDeFondos - ".date("dmy").".pdf","UTF-8");		
	$pdf->Output("I",$ruta."Presupuesto - ".date("dmy")." .pdf","UTF-8");
	
	
	
	
}
	

?>