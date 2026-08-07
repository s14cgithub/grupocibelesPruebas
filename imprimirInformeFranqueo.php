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
	$separarPorFechas = $_POST["imprimirPorFechasInformeFranqueoModal"];
	$saldoYdetalleCorreos = $_POST["imprimirCorreosDetalleModal"];
	$ot = $_POST["imprimirOtInformeFranqueoModal1"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
	$fechaFin1 = date("Y-m-d", strtotime($fechaFin));
	$anioTarifasInformeFranqueo = date("Y", strtotime($fechaInicio));

	$camposFranqueoInforme = ['nombreEmpresa','ot','descripcion','gramos','unitario','unidadesSuma','importeTotal','importeTotalSinIva','unitarioSinIva','codigo_saldo'];
	$groupFranqueoInforme = ['nombre_empresa','ot','descripcion','gramos','codigo_saldo'];

	$filtrosFranqueoInforme = ['comprobado' => 1];
	if ($idCliente != "todos")
	{
		$filtrosFranqueoInforme['codigo_saldo'] = $idCliente;
	}

	$filtrosOperadoresFranqueoInforme = [
		['campo1' => 'fecha', 'valor' => $fechaInicio1, 'operador' => '>='],
		['campo1' => 'fecha', 'valor' => $fechaFin1, 'operador' => '<=']
	];

	$filtrosLikeFranqueoInforme = [];
	if ($ot !== '')
	{
		$filtrosLikeFranqueoInforme[] = ['campo' => 'ot', 'valor' => $ot];
	}

	$ordenPorCodigoSaldo=0;
	if ($separarPorFechas=="true")
	{	
		$camposFranqueoInforme[] = 'fecha';
		$groupFranqueoInforme[] = 'fecha';
	}
	else
	{
		if ($_SESSION["idEmpleado"]== 11)
		{
			$ordenPorCodigoSaldo=1;
			
		}
	}

	$orderFranqueoInforme = [];
	if ($ordenPorCodigoSaldo==1)
	{
		$orderFranqueoInforme[] = ['campo' => 'codigo_saldo', 'dir' => 'ASC'];
	}
	$orderFranqueoInforme[] = ['campo' => 'nombre_empresa', 'dir' => 'ASC'];
	$orderFranqueoInforme[] = ['campo' => 'ot', 'dir' => 'ASC'];
	$orderFranqueoInforme[] = ['campo' => 'descripcion', 'dir' => 'ASC'];
	$orderFranqueoInforme[] = ['campo' => 'gramos', 'dir' => 'ASC'];

	$resFranqueoInforme = cargarFranqueoTipos($conn, $bbddSql, $camposFranqueoInforme, ['tabla2','tabla3'], $filtrosFranqueoInforme, $filtrosOperadoresFranqueoInforme, $groupFranqueoInforme, $orderFranqueoInforme, $anioTarifasInformeFranqueo, $filtrosLikeFranqueoInforme);

	$datosFranqueo = $resFranqueoInforme['datos'];
	
	
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

		
		$primeraVezFechas=true;		
		
		$clienteAntiguo="asdjfaksdjfk238492834usdjfasf";
		$fechaAntigua="asdjfaksdjfk238492834usdjfasf";	
			
		
		$totalPrecioFecha=0.00;
		$totalUnidadesFecha=0.00;
		$importeTotalFranqueo=0.00;
		
		$imprimirTotalFecha=false;
		
		
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


					/*if ($saldo=="true")
					{

						$saldoAnterior = 0.00;
						//$datosSaldo = verHistoricoSaldo($conexion, date("d-m-Y",strtotime($fechaInicio."- 1 days")),$idCliente);
						$datosSaldo = verHistoricoSaldo($conexion, date("d-m-Y",strtotime($fechaInicio."- 1 days")),$datosFranqueo[$contador]["codigo_saldo"]);
						
						



						if (count($datosSaldo)<=0)
						{
							//$datosSaldo = mirarDatosEmpresaPorCodigo($conexion,$idCliente);
							$datosSaldo = mirarDatosEmpresaPorCodigo($conexion,$datosFranqueo[$contador]["codigo_saldo"]);
							$saldoAnterior = $datosSaldo[0]["importePF"];
						}
						else
						{
							$saldoAnterior = $datosSaldo[0]["saldoPostPF"];
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
				$pdf->Cell(0,0,$datosFranqueo[$contador]["codigo_saldo"] . " - " . utf8_decode($nombreCliente),0,1,'L',false); 
				
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
			
			
			
			if ($separarPorFechas=="true")
			{
				$fechaFranqueo = $datosFranqueo[$contador]["fecha"]->format('d/m/Y');
				if ($fechaAntigua!=$fechaFranqueo)
				{
					
					$fechaAntigua=$fechaFranqueo;
				
					
					if ($altura>260)
					{			
						//nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
					}
					if (!$primeraVezFechas)
					{
						//$imprimirTotalFecha=true;
					}
					else
					{
						$primeraVezFechas=false;
						$imprimirTotalFecha=false;
						
						$altura += 10;
						$pdf->SetTextColor(0,0,0);
						$pdf->SetFont('Arial','B',12);
						$pdf->SetXY($margen,$altura);
						$pdf->Cell(0,0,$fechaFranqueo,0,1,'L',false);
						
						$altura -= 5;
					}




					$imprimirTotalFecha=true;



					/*$altura += 10;
					$pdf->SetTextColor(0,0,0);
					$pdf->SetFont('Arial','B',10);
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,$fechaFranqueo,0,1,'L',false);*/

					$descripcion="aaaaa4568!ajajajajaja8a8a8";
					$ot="aaaaa4568!ajajajajaja8a8a8";
					//titulos($pdf,$altura,$margenInicial,$margen);
					
					
					
				}
				else
				{
					$imprimirTotalFecha=false;
				}
				
				
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
					
					
					
					
					
					//////////////////////////////////////////////////////////////////////////////////////
					
					if ($separarPorFechas=="true" && $imprimirTotalFecha==true)
					{
						
						

							if ($altura>260)
							{			
								nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
							}
							if (!$primeraVezFechas)
							{
								if ($altura>260)//252
								{			
									nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
								}

								$pdf->SetFont('Arial','B',8);
								$altura += 5;

								$margen = 120;
								$pdf->SetXY($margen,$altura);
								$pdf->Cell(15,0,"Total (Fecha):",0,1,'R',false);
								$margen = 120;
								$pdf->SetXY($margen,$altura);
								$pdf->Cell(0,0,$totalUnidadesFecha,0,1,'C',false); //aqui
								$margen = $margenInicial;
								$pdf->SetXY($margen,$altura);
								$pdf->Cell(0,0,number_format($totalPrecioFecha,2,',','.')." ".EURO,0,1,'R',false); //aqui

								$altura += 3;
								$margen = 115;
								$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
								$pdf->SetLineWidth(0.2);
								$pdf->Line($margen, $altura, 190, $altura);
								
								
								if ($altura>235)//252
								{			
									nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
								}
								
								
								$altura += 10;
								$pdf->SetTextColor(0,0,0);
								$pdf->SetFont('Arial','B',12);
								$pdf->SetXY($margenInicial,$altura);
								$pdf->Cell(0,0,$fechaFranqueo,0,1,'L',false);

								$altura -= 5;
								
								
								
								
								
							}
							else
							{
								$primeraVezFechas=false;
							}




							$margen = $margenInicial;
							$totalUnidadesFecha=0.00;
							$totalPrecioFecha=0.00;





						


					}
					
					
					
					//////////////////////////////////////////////////////////////////////////////////////
					
				}
				else
				{
					$primeraVez=false;
				}

				if ($altura>235)
				{			
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
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

			if ($altura>250)
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

				$pdf->Cell(0,0,number_format($datosFranqueo[$contador]["importeTotalSinIva"],2,',','.')." ".EURO,0,1,'R',false);


				$margen = $margenInicial;


				$unidadesExtension += $datosFranqueo[$contador]["unidades"];
				$totalExtension += $datosFranqueo[$contador]["importeTotalSinIva"];

				$unidadesTotal += $datosFranqueo[$contador]["unidades"];
				$totalTotal += $datosFranqueo[$contador]["importeTotalSinIva"];
				$importeTotalFranqueo += $datosFranqueo[$contador]["importeTotalSinIva"];
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
				
				
				$totalUnidadesFecha += $datosFranqueo[$contador]["unidades"];
				$totalPrecioFecha += $datosFranqueo[$contador]["importeTotal"];
				

				$unidadesTotal += $datosFranqueo[$contador]["unidades"];
				$totalTotal += $datosFranqueo[$contador]["importeTotal"];
				$importeTotalFranqueo += $datosFranqueo[$contador]["importeTotal"];
			}
			

			$contador++;
		}//fin while

		$pdf->SetFont('Arial','B',8);
		$altura += 5;

		$margen = 120;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(15,0,"Total:",0,1,'C',false);
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
		
		$altura += 5;
		


		if ($separarPorFechas=="true" || $imprimirTotalFecha==true)
		{
			$margen = 120;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(15,0,"Total (Fecha):",0,1,'R',false);
			$margen = 120;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,$totalUnidadesFecha,0,1,'C',false);//aqui aqui
			$margen = $margenInicial;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($totalPrecioFecha,2,',','.')." ".EURO,0,1,'R',false);//aqui
		}
		$altura += 3;
		$margen = 115;
		$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		$pdf->SetLineWidth(0.2);
		$pdf->Line($margen, $altura, 190, $altura);
		
		//$altura += 10;

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
			
			////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$filtrosSaldoSinFranqueoInforme = ['codigoCliente' => $idCliente, 'presupuesto_no_vacio' => 1, 'formaPago_no_vacio' => 1];
			$filtrosOperadoresSaldoSinFranqueoInforme = [
				['campo1' => 'fecha', 'valor' => $fechaInicio1, 'operador' => '>='],
				['campo1' => 'fecha', 'valor' => $fechaFin1, 'operador' => '<=']
			];
			$resSumatorioSaldoSinFranqueo = cargarProvisionDeFondo_movimientos($conn, $bbddSql, ['sumaMovimientosSinFranqueo'], $filtrosSaldoSinFranqueoInforme, $filtrosOperadoresSaldoSinFranqueoInforme, []);
			$sumatorioSaldoSinFranqueo = $resSumatorioSaldoSinFranqueo['datos'];

			if ($saldoYdetalleCorreos=="true")
			{
				$camposFacturasCorreosInforme = ['presupuesto','fecha','importe'];
				$orderFacturasCorreosInforme = [['campo' => 'id', 'dir' => 'ASC']];

				$resFacturasCorreosInforme = cargarProvisionDeFondo_movimientos($conn, $bbddSql, $camposFacturasCorreosInforme, $filtrosSaldoSinFranqueoInforme, $filtrosOperadoresSaldoSinFranqueoInforme, $orderFacturasCorreosInforme);
				$datosFacturasCorreos = $resFacturasCorreosInforme['datos'];

				$contador=0;
				$altura += 5;

				while ($contador<count($datosFacturasCorreos))
				{
					if ($altura>260)
					{			
						nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
					}
					
					$altura += 5;
					$pdf->SetTextColor(0,0,0);
					$pdf->SetFont('Arial','',8);
					$pdf->SetXY($margen,$altura);
					$pdf->Cell(0,0,utf8_decode($datosFacturasCorreos[$contador]["presupuesto"])." (".$datosFacturasCorreos[$contador]["fecha"]->format('d/m/Y').")",0,1,'L',false);
					$pdf->Cell(0,0,number_format($datosFacturasCorreos[$contador]["importe"],2,',','.')." ".EURO,0,1,'R',false);

					$contador++;
				}

				$altura += 10;
				$margen = 60;
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,"Total: ".number_format($sumatorioSaldoSinFranqueo[0]["sumaMovimientosSinFranqueo"],2,',','.')." ".EURO,0,1,'R',false);
			}
			
			
			
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
			
			$saldoAnterior = 0.00;
			$fechaHistoricoSaldo = date("Y-m-d", strtotime($fechaInicio1."- 1 days"));
			$codigoClienteHistoricoSaldo = $datosFranqueo[count($datosFranqueo)-1]["codigo_saldo"];

			$filtrosHistoricoSaldo = ['fechaMovimientoMax' => $fechaHistoricoSaldo, 'codigoClienteMovimientoMax' => $codigoClienteHistoricoSaldo];
			$resHistoricoSaldo = cargarProvisionDeFondo_movimientos($conn, $bbddSql, ['saldoPostPF'], $filtrosHistoricoSaldo, [], []);
			$datosSaldo = $resHistoricoSaldo['datos'];



			if (count($datosSaldo)<=0)
			{
				$resDatosSaldoCliente = cargarClientes($conn, $bbddSql, ['importePF'], ['codigo' => $codigoClienteHistoricoSaldo], [], []);
				$datosSaldo = $resDatosSaldoCliente['datos'];
				$saldoAnterior = $datosSaldo[0]["importePF"];
			}
			else
			{
				$saldoAnterior = $datosSaldo[0]["saldoPostPF"];
			}


			if ($altura>245)
			{			
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$margenInicial);
			}

			$altura += 5;

			$pdf->SetFillColor(colorAzulR,colorAzulG,colorAzulB);
			$pdf->Rect(70, $altura+5, 70, 25,'F');


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
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(50,0,"Total Ingresos:",0,1,'R',false);

			$altura += 5;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(50,0,"Saldo Actual:",0,1,'R',false);

			//SEGUNDA COLUMNA

			
			$filtrosSumatorioSaldos = ['codigoCliente' => $idCliente, 'informacionCuadre' => 'Ajuste de Saldo'];
			$filtrosOperadoresSumatorioSaldos = [
				['campo1' => 'fecha', 'valor' => $fechaInicio1, 'operador' => '>='],
				['campo1' => 'fecha', 'valor' => $fechaFin1, 'operador' => '<=']
			];
			$resSumatorioSaldos = cargarProvisionDeFondo_movimientos($conn, $bbddSql, ['sumaMovimientosSinFranqueo'], $filtrosSumatorioSaldos, $filtrosOperadoresSumatorioSaldos, []);//con ajuste de saldo
			//echo json_encode($resSumatorioSaldos);
			//exit;
			$sumatorioSaldos = $resSumatorioSaldos['datos'];
			$saldoAnteriorYajustesSaldo=$saldoAnterior+$sumatorioSaldos[0]["sumaMovimientosSinFranqueo"];//saldo anterior
			$altura -= 15;
			$margen = 80;
			$pdf->SetFont('Arial','',10);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(50,0,number_format($saldoAnteriorYajustesSaldo,2,',','.')." ".EURO,0,1,'R',false); //esto esta bien
			$altura += 5;
			$pdf->SetXY($margen,$altura);
			$totalTotal = $importeTotalFranqueo;
			$pdf->Cell(50,0,number_format($totalTotal*-1,2,',','.')." ".EURO,0,1,'R',false); //esto esta bien
			
			$altura += 5;			
			$pdf->SetXY($margen,$altura);

			$pdf->Cell(50,0,number_format($sumatorioSaldoSinFranqueo[0]["sumaMovimientosSinFranqueo"],2,',','.')." ".EURO,0,1,'R',false); //esto esta bien
			
			

			$altura += 5;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);

			
			
			

			
			
			//$aPagar = $saldoAnterior + $sumatorioSaldos[0]["sumaMovimientos"];

			$aPagar = $saldoAnteriorYajustesSaldo + ($totalTotal*-1) + $sumatorioSaldoSinFranqueo[0]["sumaMovimientosSinFranqueo"];


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
	
	
	
	
	
		


