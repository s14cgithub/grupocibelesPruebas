<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="domiciliados")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	//require($ruta."Archivos Comunes/pagegroup.php");
	//require($ruta."Archivos Comunes/rotate.php");
	//require($ruta."Archivos Comunes/cabeceraPieFactura.php");
	
	
	$fechaInicio = $_POST["fechaDomInicio"];	
	$fechaFin = $_POST["fechaDomFin"];
	
	$numClienteDomiciliado = $_POST["numClienteDomiciliado"];
	
	
	
	

	$campos = [
		'factura',
		'codigo_saldo',
		'nombre_empresa',
		'nombreFranqueoReal',
		'concepto',
		'neto',
        'iva',
        'importe',
        'anticipo',
        'aPagar',
        'codigoSubcliente',
        'franqueoCliente',
        'vencimiento',
        'fecha',
        'presupuesto',
        'domiciliada', //siempre debe estar
        'formaPagoReal' //siempre debe estar
	];

	$joins = array();

	
	$fechaInicio = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin = date("d-m-Y", strtotime($fechaFin));

	$filtros = array(
		"domiciliada" => 1,
		"sinFormaPago" => 1
	);

	if ($numClienteDomiciliado!="")
	{		
		$filtros["codigo_saldo"] = $numClienteDomiciliado;
	}	

	

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
		'nombre_empresa' => 'ASC',
		'fecha' => 'ASC',
		'factura' => 'ASC'
	);	

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];


	$datosFactura = mostrarFacturacionCibelesYCorreos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $filtrosLike, $order);

	
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
	$pdf->Cell(35,5,utf8_decode("Listado de Facturación Domiciliados de Cibeles Mailing"),0,1,'L',false);
	
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
	//$datosFactura = verFacturasDomiciliadasSinPagar($conexion,$fechaInicio, $fechaFin);	
	
	nuevaPagina($pdf,$altura,$margen,$margenInicial);
	
	$clienteAntiguo = "asjdkf3829348jasdfAntiguoa";
	$idCliente=-1;
	
	$importeTotal = 0.00;
	$importeTotalTodo = 0.00;
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
		
		
		if ($clienteAntiguo!=$row["nombre_empresa"])
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
			
			
			
			if (!$primeraVez)
			{
				$campos = [
					'presupuesto',
					'importe',
					'fechaCreacion'
				];

				$joins = array();
				$filtros = array(
					"cobrada" => 2,
					"idCliente" => $idCliente
				);

				$filtrosOperadores = array(
					array(
						'campo1' => 'fechaCreacion',
						'operador' => '>=',
						'valor' => $fechaInicio
					),
					array(
						'campo1' => 'fechaCreacion',
						'operador' => '<=',
						'valor' => $fechaFin
					)
				);

				$order = array();
				$group = array();

				$datosPF = cargarProvisionDeFondos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);
				///////////////////////////////////////////

				
				
				$pdf->SetTextColor(255,128,0);
				
				foreach ($datosPF["datos"] as $rowPF) 
				{
					$margen = $margenInicial+20;
					$altura += 5;
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY($margen,$altura);
					
					$pdf->Cell(15,0,utf8_decode("Provisión de Fondo"),0,1,'R',true); 

					$margen = $margenInicial+50;	
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(20,0,$rowPF["presupuesto"],0,1,'R',true);

					$margen = $margenInicial+75;	
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(20,0,number_format($rowPF["importe"],2,',','.')." ".EURO,0,1,'R',true);
				
					$margen += 35;
					
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(20,0,utf8_decode($rowPF["fechaCreacion"]->format('d/m/Y')),0,1,'R',true);
				
				}
				$altura += 10;


				////////////////////////////////////////////




				$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
				$pdf->SetXY($margen,$altura-10);
				$pdf->Cell(0,0,"Total: ".number_format($importeTotal,2,',','.')." ".EURO,0,1,'R',true);
				$importeTotal=0.00;
				$pdf->SetTextColor(0,0,0);
			}
			else
			{
				$primeraVez=false;
			}
			

			$clienteAntiguo=$row["nombre_empresa"];
			$pdf->SetFont('Arial','B',10);

			$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
			$margen = $margenInicial;
			$pdf->SetXY($margen,$altura);
			$idCliente = $row["codigo_saldo"];
			$pdf->Cell(0,0,$row["codigo_saldo"],0,1,'L',true);
			$margen += 10;
			$pdf->SetXY($margen,$altura);
			
			$pdf->Cell(0,0,utf8_decode($clienteAntiguo),0,1,'L',true);
			$margen = $margenInicial;
			$pdf->SetTextColor(0,0,0);
			$altura += 5;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			
			$pdf->Cell(0,0,utf8_decode($row["concepto"]." / "),0,1,'L',true);
			
			
			$margenConcepto = $pdf->GetStringWidth($row["concepto"]);
			$margenConcepto += 3;
			
			
			$pdf->SetTextColor(colorVerdeR,colorVerdeG,colorVerdeB);
			$pdf->SetXY($margen+$margenConcepto ,$altura);
			$pdf->Cell(0,0,utf8_decode($row["vencimiento"]),0,1,'L',true);
			
			$pdf->SetTextColor(0,0,0);
			
			
			$altura += 5;
			$margen = $margenInicial+20;
			$pdf->SetFont('Arial','B',8);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,utf8_decode("FACTURA"),0,1,'L',true);

			$margen = $margenInicial+50;			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,utf8_decode("OT"),0,1,'R',true);
			
			$margen = $margenInicial+75;//45			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,utf8_decode("IMPORTE"),0,1,'R',true);
			
			
			
			
			
			$margen += 35;
			//$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(20,0,utf8_decode("FECHA"),0,1,'R',true);
			
			$altura += 5;
		}
		
		
		
		//$altura += 5;
		$margen = $margenInicial+20;
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(15,0,utf8_decode($row["factura"]),0,1,'R',true); 
		

		$margen = $margenInicial+50;	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,$row["presupuesto"],0,1,'R',true);


		$margen = $margenInicial+75;	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,number_format($row["aPagar"],2,',','.')." ".EURO,0,1,'R',true);
		
		$margen += 35;
		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,utf8_decode($row["fecha"]->format('d/m/Y')),0,1,'R',true);
		
		
		$importeTotal += $row["aPagar"];
		$importeTotalTodo += $row["aPagar"];
		
		
		
		
		
	}







	$altura += 5;

	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Total: ".number_format($importeTotal,2,',','.')." ".EURO,0,1,'R',true);
	$importeTotal=0.00;


	///////////////////////////////////////////

	$campos = [
		'presupuesto',
		'importe',
		'fechaCreacion'
	];

	$joins = array();
	$filtros = array(
		"cobrada" => 2,
		"idCliente" => $idCliente
	);

	$filtrosOperadores = array(
		array(
			'campo1' => 'fechaCreacion',
			'operador' => '>=',
			'valor' => $fechaInicio
		),
		array(
			'campo1' => 'fechaCreacion',
			'operador' => '<=',
			'valor' => $fechaFin
		)
	);

	$order = array(		
		'fechaCreacion' => 'ASC',
		'presupuesto' => 'ASC'
	);	
	$group = array();

	$datosPF = cargarProvisionDeFondos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order);
	
	
	$pdf->SetTextColor(255,128,0);
	
	foreach ($datosPF["datos"] as $rowPF) 
	{
		$margen = $margenInicial+20;
		$altura += 5;
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($margen,$altura);
		
		$pdf->Cell(15,0,utf8_decode("Provisión de Fondo"),0,1,'R',true); 

		$margen = $margenInicial+50;	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,$rowPF["presupuesto"],0,1,'R',true);

		$margen = $margenInicial+75;	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,number_format($rowPF["importe"],2,',','.')." ".EURO,0,1,'R',true);
	
		$margen += 35;
		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(20,0,utf8_decode($rowPF["fechaCreacion"]->format('d/m/Y')),0,1,'R',true);
	
	}


	////////////////////////////////////////////






	
	$altura += 15;
	$pdf->SetTextColor(colorRojoR,colorRojoG,colorRojoB);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY($margenInicial,$altura+5);
	$pdf->Cell(0,0,"Total: ".number_format($importeTotalTodo,2,',','.')." ".EURO,0,1,'C',true);
	
	
	
	
	
	
	
	$pdf->Output("I",date("dmy")." .pdf","UTF-8");
	sqlsrv_close($conn);
	
}
	
	


function nuevaPagina(&$pdf,&$altura,&$margen,$margenInicial)
{
	
	$margen=$margenInicial;
	$altura = 30;
	
	
}
	
	



?>
	
	
	
	
	
	
		


