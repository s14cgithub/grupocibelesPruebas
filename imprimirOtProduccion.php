<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirOT")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	
	
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/code128.php");
	
	
	//$numPresupuesto = $_POST["imprimirNumOT"];	
	
	
	
	$numPresupuesto = isset($_POST["imprimirNumOT"]) ? $_POST["imprimirNumOT"] : 0;
	
	$mensual = isset($_POST["mesRango"]) ? $_POST["mesRango"] : "00";
	$anio = isset($_POST["anioRango"]) ? $_POST["anioRango"] : "";
	
	
	$minNumFac = $MaxNumFac = $numPresupuesto;
	
	if ($mensual!="00")
	{
		$minNumFac = $anio.$mensual."000";
		$MaxNumFac = $anio.$mensual."099";
	}
	
	
	$contadorMax=$minNumFac;
	//$pdf = new cabeceraFactura('P','mm','A4');	
	$pdf = new PDF('P','mm','A4');
	
	//echo "<br>".$MaxNumFac;
	
	while ($contadorMax<=$MaxNumFac)		
	{
	
		$numPresupuesto = $contadorMax;
	
	
		$departamentos = verDepartamentosProcesos($conexion);
		$departamentosEnDetalles = cargarDetallesPresupuestoDepartamento($conexion,$numPresupuesto);

		$esClayma = verSiOtEsDeClayma($conexion,$numPresupuesto);
		
		
		if (count($esClayma)>0)
		{
			if ($esClayma[0]["clayma"]==1)
			{
				$datosPresupuesto = verPresupuestoParaOtClayma($conexion,$numPresupuesto);
			}
			else
			{
				$datosPresupuesto = verPresupuestoParaOt($conexion,$numPresupuesto);
			}


			$datosDetalles = cargarDetallesPresupuesto($conexion,$numPresupuesto,"paraOt");

			//$departamentosFijos = array("BENJA","SOBRANTE", "ALMACEN","MATERIALES");
			//$departamentosFijos = array("SOBRANTE","MATERIALES");
			$departamentosFijos = array();//sin departamentos fijos

			//$pdf = new PDF('P','mm','A4');

			$altura=-1;
			$margen=10;
			$alturaSiguientePagina=15;
			$limiteAlturaDatos = 260;
			$mostrarPrecio=0;
			$numPagina=0;



			foreach ($departamentos as $row)//impresión de departamentos dinámicos
			{
				$contador=0;
				$encontrado=false;

				while ($contador<count($departamentosEnDetalles) && !$encontrado) //se mira qué departamentos dinámicos se imprimen
				{
					if (in_array($row["departamento"], $departamentosEnDetalles[$contador]))
					{
						$encontrado=true;
					}
					$contador++;
				}


				if ($encontrado)
				{
					imprimirDatos($pdf,"dinamico", $row);
				}

			}
			foreach ($departamentosFijos as $row)
			{
				/*$contador=0;
				$encontrado=false;

				while ($contador<count($departamentosEnDetalles) && !$encontrado) //se mira qué departamentos dinámicos se imprimen
				{
					if (in_array($row["departamento"], $departamentosEnDetalles[$contador]))
					{
						$encontrado=true;
					}
					$contador++;
				}*/


				//if ($encontrado)
				{
					//$pdf->Text(50,50,$row);
					//$altura += 5;
					imprimirDatos($pdf,"", $row);
				}

			}
		}
		
		
		$contadorMax++;
	}
	
	/*if ($numPagina>0)
	{
		if ($numPagina%2==0)
		{
			//echo "el $numero es par";
		}
		else
		{
			//echo "el $numero es impar";
			$pdf->AddPage();
			//$pdf->Text(50,50,"PRUEBA1: ".$numPagina);
		}
	}*/
	
	//echo "<script language='javascript'>window.open('http://172.26.0.17:8080/gestionGrupocibelesPreproduccion/imprimirOtProduccion2.php?ot=333', '_blank')</script>";
	//echo '<script language="javascript">document.getElementById("formImprimir").submit();</script>';
	//header("Location: http://www.example.com/");
	$pdf->Output("I",$ruta."OT - ".date("dmy")." .pdf","UTF-8");
	
	
	

	
	
}
else
{
	
}


function imprimirDatos(&$pdf,$tipo, $row)
{
	global $datosPresupuesto;
	global $datosDetalles;
	global $limiteAlturaDatos;
	global $mostrarPrecio;
	global $alturaSiguientePagina;
	global $numPagina;
	global $altura;
	global $margen;
	
			/*if ($numPagina>0)
			{
				if ($numPagina%2==0)
				{
					//echo "el $numero es par";
				}
				else
				{
					//echo "el $numero es impar";
					$pdf->AddPage();
					//$pdf->Text(50,50,"PRUEBA1: ".$numPagina);
				}
			}*/
			
			$altura=0;
			$pdf->StartPageGroup();
			
			//$pdf->AddPage('L','A3');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$numPagina=1;
			
			$pdf->SetFont('Arial','B',30);	
			$altura=$altura + 18;
			$pdf->Text($margen,$altura,"OT ".$datosPresupuesto[0]["presupuesto"]);

			$pdf->SetXY($pdf->GetPageWidth()-60,$altura);
			$pdf->Image('imagenes/Logo Grupo Cibeles.jpg',(($pdf->GetPageWidth())/2)-(27/2),5,27);	


			$pdf->SetTextColor(13,140,252);
			$pdf->SetFont('Arial','BI',8);				
			$pdf->Text(170,10,"FR 09 01 02");

			
			$pdf->SetTextColor(13,140,252);
			$pdf->SetFont('Arial','B',16);	
			$pdf->SetXY($margen,$altura);
			
			if ($tipo=="dinamico")
				$pdf->Cell(0,0,utf8_decode(strtoupper($row["departamento"])),0,1,'R',false);
			else
				$pdf->Cell(0,0,utf8_decode(strtoupper($row)),0,1,'R',false);
		
			$departamentoTitulo = $row["departamento"];
			
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',15);
			$altura=$altura + 5;
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(140,8,utf8_decode($datosPresupuesto[0]["cliente"]),1,'L',false);
			
			
			$pdf->SetTextColor(255,0,0);
			$pdf->SetFont('Arial','B',12);
			$pdf->SetXY($pdf->GetPageWidth()-60,$altura);
			$pdf->MultiCell(0,8,utf8_decode($datosPresupuesto[0]["nombre_franqueo"]),1,'R',false);
	
	
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetXY($margen,$altura+8);
			$pdf->MultiCell(0,8,utf8_decode($datosPresupuesto[0]["codigo"]),0,'L',false);
			
			
			
			
			
			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosPresupuesto[0]["cliente"]));
			$numeroDeFilas = $anchoDescripcion / ((140)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}

			$altura = $altura + (6*$numeroDeFilas);

			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);	
			}
			
			
			

			$altura=$altura + 8;
			$pdf->SetXY($margen,$altura);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',10);
			$pdf->MultiCell(0,8,utf8_decode($datosPresupuesto[0]["nombre_empresa"]),0,'L',false);
			
			
			$altura = $altura + 10; 
			$pdf->SetFont('Arial','BI',10);
			$pdf->SetTextColor(13,140,252);
			$pdf->SetXY($margen+90,$altura);
			$pdf->Cell(25,0,utf8_decode("F.Inicio:"),0,1,'L',false);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',18);
			$pdf->SetXY($margen+15+90,$altura);
			$pdf->Cell(25,0,utf8_decode($datosPresupuesto[0]["fechaInicioReal"]->format('d/m/Y')),0,1,'L',false);
			
			$pdf->SetFont('Arial','BI',10);
			$pdf->SetTextColor(13,140,252);
			$pdf->SetXY($margen+143,$altura);
			$pdf->Cell(25,0,utf8_decode("F.Final:"),0,1,'L',false);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',18);
			//$pdf->SetXY($margen+15,$altura);
			$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["fechaTerminado"]->format('d/m/Y')),0,1,'R',false);
			
			
			$altura = $altura + 10; 
			$pdf->SetFont('Arial','B',15);			
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(0,5,utf8_decode($datosPresupuesto[0]["campana2"]),0,'C',false);
			
			
			
			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosPresupuesto[0]["campana2"]));
			$numeroDeFilas = $anchoDescripcion / ((140)-1); 
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}

			$altura = $altura + (6*$numeroDeFilas);

			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);	
			}
			
			
			
			
			
			
			
			$altura = $altura + 10; 
				
			$pdf->SetDrawColor(13,140,252);
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(0,12,utf8_decode(" "),1,'C',false);
			$pdf->SetDrawColor(0,0,0);
			$altura = $altura+5; 
			$pdf->SetFont('Arial','I',8);
			$pdf->SetTextColor(13,140,252);
						
			$pdf->Text($margen+5,$altura,"Presupuesto:");
			$pdf->Text($margen+70,$altura,"Provision de Fondos:");
			$pdf->Text($margen+140,$altura,"Ingreso:");
			
			$pdf->SetTextColor(0,0,0);
			$pdf->Text($margen+23,$altura,$datosPresupuesto[0]["inicialComercial"]." - ".$datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"]);
			
			
			$altura = $altura + 5; 
			$pdf->SetTextColor(13,140,252);
			$pdf->Text($margen+5,$altura,"Contacto:");
			//pdf->Text($margen+70,$altura,utf8_decode("Teléfono:"));
			//$pdf->Text($margen+140,$altura,"Email:");
			
			$pdf->SetTextColor(0,0,0);			
			$pdf->Text($margen+19,$altura,strtoupper(utf8_decode($datosPresupuesto[0]["persona"])));
			
			$altura = $altura + 7; 
			$pdf->SetTextColor(255,0,0);
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY($margen,$altura);
			//$pdf->Cell(0,0,utf8_decode("¡¡¡¡POR FAVOR LEED LA OT CON MAXIMA ATENCION, ANTE CUALQUIER DUDA CONSULTAR!!!!"),0,1,'C',false);
			//$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["observaciones2"]),0,1,'C',false);
			
	
			$observaciones2 = reemplazarSimbolos($datosPresupuesto[0]["observaciones2"]);
			$pdf->MultiCell(0,5,$observaciones2,0,'C',false);
			$pdf->SetTextColor(0,0,0);	
	
			$anchoDescripcion = $pdf->GetStringWidth($observaciones2);
			$numeroDeFilas = $anchoDescripcion / ((160)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}

			$altura = $altura + (7*$numeroDeFilas);

			/*if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);
			}*/	
	
			$positions = array();
			$lastPos = 0;
			$needle = "\n";

			while (($lastPos = strpos($observaciones2, $needle, $lastPos))!== false) {
				$positions[] = $lastPos;
				$lastPos = $lastPos + strlen($needle);
			}

			//echo count($positions);
	
			$altura = $altura + (7*count($positions));
	
	
			$altura = $altura + 5; 
			$pdf->SetXY($margen,$altura);
			$pdf->SetFont('Arial','B',16);
			$pdf->Cell(0,0,"Cantidad: " .number_format($datosPresupuesto[0]["cantidad2"],0,',','.'),0,1,'C',false); 
			
	
			$altura = $altura - 5; 
	
	
			//PIE DE PAGINA
			ponerPiePagina($pdf);
			
		
			imprimirDetalles($pdf,$tipo,$altura,$row,$datosDetalles,$limiteAlturaDatos,$margen,$alturaSiguientePagina,$mostrarPrecio,$numPagina,$departamentoTitulo);
			//checkList($pdf,$numPagina);
}




//imprimirDetalles($pdf,$altura,$row,$datosDetalles);
function imprimirDetalles(&$pdf,$tipo, &$altura, $departamento, $datosDetalles, $limiteAlturaDatos, $margen, $alturaSiguientePagina, $mostrarPrecio, &$numPagina, $departamentoTitulo)
{
	$departamentoAnterior="asdfjkidj234234";
	foreach ($datosDetalles as $row) 
	{
		//if ($departamentoTitulo==$row["departamento"]) //ESTE IF ES PARA IMPRIMIR SOLO LOS DATOS CORRESPOENDIENTE DEL DEPARTAMENTO
		{
			if ( $row["departamento"]!=$departamentoAnterior)
			{
				$pdf->SetFont('Arial','B',9);
				$departamentoAnterior = $row["departamento"];
				$altura = $altura+7;

				if ($altura>$limiteAlturaDatos)
				{
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);		
				}

				$pdf->SetTextColor(13,140,252);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(125,5,utf8_decode(strtoupper($row["departamento"])),0,0,'L',false);
				$altura = $altura+5;
			}


			$pdf->SetFont('Arial','B',9);
			$pdf->SetTextColor(0,0,0);
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);	
			}


			$unidad=0.000;

			$unidad = number_format($row["unidades2"],3,',','.');




			if ($unidad==="0,000")
			{
				$unidad = "";
			}
			else if (substr($unidad,-3)==="000")
			{
				$pos = strpos($unidad, ",");
				$unidad = substr($unidad,0,$pos);
			}

			//echo "<br>".substr($unidad,-3);

			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(18,5,$unidad,0,'R',false);
			$pdf->SetXY($margen+20,$altura);
			$pdf->MultiCell(0,5,utf8_decode($row["proceso"]),0,'L',false);

			$altura = $altura+5;
			$pdf->SetFont('Arial','',9);
			if ($row["descripcion"]!="")
			{
				if ($altura>$limiteAlturaDatos)
				{
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);	
				}

				$pdf->SetXY($margen+25,$altura);


				$descripion = reemplazarSimbolos($row["descripcion"]);		


				$pdf->MultiCell(165,4,$descripion,0,'L',false);

				$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["descripcion"]));
				$numeroDeFilas = $anchoDescripcion / ((165)-1);
				if ($numeroDeFilas<1)
				{
					$numeroDeFilas = 1;
				}

				$altura = $altura + (7*$numeroDeFilas);

				if ($altura>$limiteAlturaDatos)
				{
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);
				}

			}

			$pdf->SetTextColor(255,0,0);
			if ($row["notaCibeles"]!="")
			{

				$pdf->SetXY($margen+30,$altura);

				$notas2 = reemplazarSimbolos($row["notaCibeles"]);

				$pdf->MultiCell(160,4,$notas2,0,'L',false);


				$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["notaCibeles"]));
				$numeroDeFilas = $anchoDescripcion / ((160)-1);
				if ($numeroDeFilas<1)
				{
					$numeroDeFilas = 1;
				}

				$altura = $altura + (7*$numeroDeFilas);

				if ($altura>$limiteAlturaDatos)
				{
					nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio,$numPagina);
				}	



			}
			$pdf->SetTextColor(0,0,0);

			//el codigo de barras
			$pdf->SetXY($margen+100,$altura);		
			//$pdf->MultiCell(125-10,4,utf8_decode($row["id"]."-".$row["presupuesto"]),0,'L',false);

			if ($tipo=="dinamico"&& $row["departamento"]==$departamento["departamento"])
			{

				$pdf->Code128($margen+100,$altura,$row["id"]."-".$row["presupuesto"],50,5);
				$altura = $altura + 5;	
				$pdf->SetFont('Arial','',8);
				$pdf->SetXY($margen+100,$altura);
				$pdf->Cell(50,5,$row["id"]."-".$row["presupuesto"],0,1,'C',false);
				$altura = $altura + 10;	
			}
		}
		
		
	}
}


function ponerPiePagina(&$pdf)
{	
	
	$margen=90;
	$pdf->SetTextColor(13,140,252);	
	$altura = 265;
	$pdf->SetFont('Arial','BI',8);			
	$pdf->Text($margen,$altura,"Revisado por:");
			
	$pdf->Text($margen+70,$altura,"Firma:");
	$altura = $altura+12;
	$pdf->Text($margen,$altura,"Unidades Reales:  ___________________________________________________");
	
	
	$pdf->SetLineWidth(0.5);
	$ancho=25;
	$espacioEntreRect=2;	
	$altura = $altura+3;
	$pdf->Rect($margen, $altura, $ancho, 10,'D');
	$pdf->Rect($margen +  $ancho + $espacioEntreRect , $altura, $ancho, 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*2), $altura, $ancho , 10,'D');
	$pdf->Rect($margen + (($ancho + $espacioEntreRect)*3), $altura, $ancho , 10,'D');
	$pdf->SetLineWidth(0.2);
	
	$altura = $altura+3;
	
	$pdf->Text($margen+1,$altura,"Madrid");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*1),$altura,"Cap. y Adm.");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*2),$altura,"Resto");
	$pdf->Text($margen+1+(($ancho + $espacioEntreRect)*3),$altura,"Ext. Z1/Z2");
	
	$altura = $altura+2;
	$margen=10;	
	$pdf->SetFont('Arial','I',8);
				
	$pdf->Text($margen,$altura,date('d/m/Y H:i:s'));
	$altura = $altura+5;
	
	//$paginaActual= $pdf->GroupPageNo();
	//$paginasTotal = intval($pdf->PageGroupAlias());
			//$paginasTotal = $paginasTotal/ 2;
	//$pdf->Text($margen,290,$paginaActual.'/'.$paginasTotal);
	//$pdf->Text($margen,290,ceil($pdf->GroupPageNo()/2).'/'.$pdf->PageGroupAlias());
		
	$pdf->Text($margen,290,$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias());
	
	
}


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$mostrarPrecio,&$numPagina)
{
	
	$pdf->AddPage();
	$numPagina++;
	//pdf->AliasNbPages(false);
	//$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
	//$pdf->SetAutoPageBreak(false);
	$altura = $alturaSiguientePagina;
	/*if ($mostrarPrecio==0)
	{		
		$pdf->SetXY(180,$altura);
		$pdf->Cell(20,0,"UNIDADES",0,0,'R',false);
	}
	else
	{		
		$pdf->SetXY(140,$altura);
		$pdf->Cell(20,0,"UNIDADES",0,0,'R',false);

		$pdf->SetXY(160,$altura);
		$pdf->Cell(20,0,"PRECIO",0,0,'R',false);

		$pdf->SetXY(180,$altura);
		$pdf->Cell(20,0,"TOTAL",0,0,'R',false);
		
	}*/
	$altura=$altura+5;
}


function checkList(&$pdf,&$numPagina)
{
	$pdf->AddPage();
	$numPagina++;
	$pdf->SetTextColor(0,0,0);		
	$pdf->SetFont('Arial','B',14);	
	
	$margen=10;
	$altura=5;
	
	$pdf->SetXY($margen,$altura);
	$pdf->Image('imagenes/Logo Grupo Cibeles.jpg',$margen,$altura,27);
	$altura= $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"CHECK LIST",0,0,'C',false);
	
	$altura= $altura + 5;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen+127,$altura);
	$pdf->Cell(63,8,utf8_decode("OT Nº:"),1,0,'L',false);
	
	$altura= $altura + 13;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(95,8,utf8_decode("CLIENTE:"),1,0,'L',false);
	$pdf->SetXY($margen+100,$altura);
	$pdf->Cell(90,8,utf8_decode("FECHA ENTRADA:"),1,0,'L',false);
	
	$altura= $altura + 8;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(95,8,utf8_decode("TRABAJO:"),1,0,'L',false);
	$pdf->SetXY($margen+100,$altura);
	$pdf->Cell(90,8,utf8_decode("CANTIDAD:"),1,0,'L',false);
	
	$altura= $altura + 20;
	$pdf->SetLineWidth(0.5);
	$pdf->Text($margen,$altura,"ARRANQUE TRABAJO VERIFICADO");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	
	$altura= $altura + 10;
	$pdf->Text($margen,$altura,"ALMACEN");
	
	
	$altura= $altura + 8;
	$pdf->SetFont('Arial','',8);
	$pdf->Text($margen+20,$altura,"MERCANCIA RECIBIDA SIN INCIDENCIAS (con albaran, en codiciones optimas, etc...)");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"SOBRANTE CUADRADO Y REALIZADO SU ALBARAN");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"CONTROL CALIDAD OK");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	
	$altura= $altura + 8;
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen,$altura,"INFORMATICA");
	
	$altura= $altura + 8;
	$pdf->SetFont('Arial','',8);
	$pdf->Text($margen+20,$altura,"BBDD CORRECTA");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"USO CORRECTO DE TODOS LOS CAMPOS");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,utf8_decode("MANIPUOLADO CORRECTO SEGÚN OT/PPTO"));
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"CONTROL CALIDAD OK");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	
	$altura= $altura + 8;
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen,$altura,"MANIPULADO");
	$altura= $altura + 8;
	$pdf->SetFont('Arial','',8);
	$pdf->Text($margen+20,$altura,"MANIPULADO CORRECTO SEGUN OT/PPTO");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"CONTROL CALIDAD OK");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	
	$altura= $altura + 8;
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen,$altura,"FRANQUEO/FRANQUEO PAGADO");
	$altura= $altura + 8;
	$pdf->SetFont('Arial','',8);
	$pdf->Text($margen+20,$altura,"TIPO FRANQUEO CORRECTO");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"PESO CORRRECTO");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura + 5;
	$pdf->Text($margen+20,$altura,"NO SUPERA PROVISION FONDOS");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	$altura= $altura +5;
	$pdf->Text($margen+20,$altura,"CONTROL CALIDAD OK");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	
	$altura= $altura + 8;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,30,utf8_decode(""),1,0,'L',false);
	$altura= $altura + 5;
	$pdf->Text($margen+2,$altura,"OBSERVACIONES");
	
	$altura= $altura + 35;
	$pdf->Text($margen,$altura,"ALBARAN ENTREGA CUADRA CON CANTIDAD REAL");
	$pdf->SetXY($margen+150,$altura-3);
	$pdf->Cell(6,5,"",1,0,'C',false);
	
	
	
	$pdf->SetLineWidth(0.2);
	
	$altura= $altura + 70;
	//$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
	$pdf->SetFont('Arial','I',8);
	/*if ($numPagina>0)
	{
		if ($numPagina%2==0){}
		
		else
		{
			$pdf->Text($margen,290,ceil($pdf->GroupPageNo()/2).'/'.$pdf->PageGroupAlias()/2);
		}
	}*/
	$pdf->Text($margen,290,$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias());
	//echo "<script language='javascript'>location.assign ('prueba.php')</script>";
	
	
	
}

?>

	
	
	
	
	
		


