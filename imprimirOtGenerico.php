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
	
	require($ruta."Archivos Comunes/cabeceraPieInforme.php");
	
	$numPresupuesto = $_POST["imprimirNumOT"];	
	
	$datosPresupuesto = verPresupuesto($conexion,$numPresupuesto);
	$datosDetalles = cargarDetallesPresupuesto($conexion,$numPresupuesto);
	
	$pdf = new PDF('P','mm','A4');

	//$pdf->SetXY(-10,-5);

	
	$pdf->AddPage();
	$pdf->AliasNbPages();
	//$pdf->SetAutoPageBreak(false);
	//$contador=0;	
	
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('Arial','B',16);	
	$altura=18;
	$margen=85;
	$pdf->Text($margen,$altura,"ORDEN DE TRABAJO");
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	$altura=35;
	$margen=10;
	$alturaSiguientePagina=40;
	$limiteAlturaDatos=250;
	
	$pdf->Text($margen,$altura,$datosPresupuesto[0]["fecha"]->format('d/m/Y'));
	
	$pdf->SetTextColor(13,140,252);
	$pdf->SetFont('Arial','B',12);
	$pdf->Text($margen,$altura+5,"PRESUPUESTO:");
	$pdf->SetTextColor(0,0,0);
	//$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen+35,$altura+5,$datosPresupuesto[0]["inicialComercial"]." - ".$datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"]);
	$nombrePresupuestoCompleto = $datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"];
	
	
	$pdf->SetFont('Arial','B',8);
	$pdf->Text($margen,$altura+15,utf8_decode($datosPresupuesto[0]["nombreComercial"])." - ".$datosPresupuesto[0]["telefonoComercial"]);
	
	
	
	$altura=$altura;//40
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY(110,$altura);
	$pdf->MultiCell(80,5,utf8_decode($datosPresupuesto[0]["cliente"]."\n".$datosPresupuesto[0]["persona"]),0,'L',false);
	
	
	
	$altura = $altura + 23; //75
	$pdf->SetFont('Arial','',14);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(25,0,utf8_decode("ACEPTACION:"),0,1,'L',false);
	$pdf->SetFont('Arial','B',14);
	$pdf->SetXY($margen+35,$altura);
	$pdf->Cell(25,0,utf8_decode($datosPresupuesto[0]["fechaAceptacion"]->format('d/m/Y')),0,1,'L',false);
	
	
	$pdf->SetFont('Arial','',14);
	$pdf->SetXY($margen+125,$altura);
	$pdf->Cell(25,0,utf8_decode("COMPROMISO:"),0,1,'L',false);
	$pdf->SetFont('Arial','B',14);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["fechaCompromiso"]->format('d/m/Y')),0,1,'R',false);
	
	
	
	$pdf->SetTextColor(13,140,252);
	$pdf->SetFont('Arial','B',14);
	$altura = $altura + 10; //75
	$pdf->SetXY($margen,$altura);
	//$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["campana"]),0,1,'C',false); 


	$campana2 = reemplazarSimbolos(($datosPresupuesto[0]["campana"]));
	$pdf->MultiCell(0,5,($campana2),0,'C',false);
	$anchoDescripcion = $pdf->GetStringWidth($campana2);
	$numeroDeFilas = ceil ($anchoDescripcion / (180));
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}	
	$altura = $altura + (5*$numeroDeFilas);






	
	$altura = $altura + 8;//85
	$pdf->SetXY($margen,$altura);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	
	$cantidad = $datosPresupuesto[0]["cantidad"];
	
	if ($cantidad=="null" || $cantidad==null || $cantidad==0 )
	{
		$cantidad=0;
	}
	else
	{
		$pdf->Cell(0,0,"Cantidad: ".number_format($cantidad,0,',','.'),0,0,'C',false);
	}
		
	//$pdf->Cell(0,0,"Cantidad: ".$cantidad,0,0,'C',false);
	
	
	$pdf->SetTextColor(255,0,0);
	$pdf->SetFont('Arial','',10);
	$altura = $altura + 8; //75
	$pdf->SetXY($margen,$altura);
	//$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["notaCibeles"]),0,1,'L',false);
	
	
	
	//$alturaVieja=$altura;
			
	$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosPresupuesto[0]["notaCibeles"]));
	$numeroDeFilas = $anchoDescripcion / ((200)-1);
	if ($numeroDeFilas<1)
	{
		$numeroDeFilas = 1;
	}

	$altura = $altura + (5*$numeroDeFilas);
	
	
	
	//$notaCibeles2 = reemplazarSimbolos(($datosPresupuesto[0]["notaCibeles"]));
	$pdf->MultiCell(200,4,reemplazarSimbolos($datosPresupuesto[0]["notaCibeles"]),0,'L',false);
	
	
	//$pdf->Cell(0,0,reemplazarSimbolos($datosPresupuesto[0]["notaCibeles"]),0,1,'L',false);
	
	$pdf->SetTextColor(0,0,0);
	
	$altura = $altura + 4;//95
	$pdf->Line($margen, $altura, 200, $altura);
	
	
	
	
	
	
	$altura = $altura + 5;//85
	$ancho = $pdf->GetPageWidth();
	$pdf->SetTextColor(0,0,0);
	
	$pdf->SetFont('Arial','B',8);
	
	
	//$mostrarPrecio = $datosPresupuesto[0]["detallada"];
	$mostrarPrecio = 1;
	
	
	if ($mostrarPrecio==0)
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
	}
	
	
	$grupoAnterior = -1;
	
	$altura = $altura;
	$limite = sizeof($datosDetalles);
	
	$totalImporte=0.00;
	
	foreach ($datosDetalles as $row) 
	{
		
		if ($row["idTipo"]!=$grupoAnterior)
		{
			$pdf->SetFont('Arial','BU',9);
			$grupoAnterior = $row["idTipo"];
			$altura = $altura+2;
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);		
			}
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(125,5,utf8_decode($row["tipoProceso"]),0,0,'L',false);
			$altura = $altura+5;
		}
		
		$pdf->SetFont('Arial','B',9);
		
		//$pdf->Cell(125-5,5,$row["proceso"],1,0,'L',false);
		
		if ($altura>$limiteAlturaDatos)
		{
			nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
		}
		$pdf->SetXY($margen+5,$altura);
		$pdf->MultiCell(125-5,5,puntoVineta." ".utf8_decode($row["proceso"]),0,'L',false);
		
		
		
		$unidad=0.000;
		$precio=0.00;
		$total=0.000;
		
		
		$unidad = number_format($row["unidades"],3,',','.');
		
		if ($unidad==="0,000")
		{
			$unidad = "";
		}
		
		$precio = number_format($row["precio"],2,',','.');
		
		if ($precio === "0,00")
		{
			$precio = "";
		}
		else
		{
			$precio .= " ".EURO;
		}
		
		$total = $row["unidades"] * $row["precio"];
		
		//$total = number_format($row["unidades"] * $row["precio"],2,',','.');
		
		//$total = number_format($total,2,',','.');
		$total = round($total,2); 
		
		
		$totalImporte = $totalImporte + $total;
		
		/*$total = number_format($total,2,',','.');
		if ($total==="0,00")
		{
			$total = "";
		}
		else
		{
			$total .= " ".EURO;
		}*/
		
		
		$pdf->SetFont('Arial','',8);
		if ($mostrarPrecio==0)
		{		
			$pdf->SetXY(180,$altura);
			$pdf->Cell(20,5,$unidad,0,0,'R',false);
		}
		else
		{			
			$pdf->SetXY(140,$altura);
			if ($unidad==="0,000")
			{
				//$pdf->Cell(20,5,$unidad,0,0,'R',false);	
			}
			else
			{
				$pdf->Cell(20,5,$unidad,0,0,'R',false);
			}
			
			$pdf->SetXY(160,$altura);
			if ($precio=="")
			{
				//$pdf->Cell(20,5,$precio,0,0,'R',false);	
			}
			else
			{
				$pdf->Cell(20,5,$precio,0,0,'R',false);	
			}		
			
			
			$pdf->SetXY(180,$altura);			
			
			
			$total=round(($total) * 1000) / 1000;
			$total=round(($total) * 100) / 100;
		
			$total = number_format($total,2,',','.');
			
			
			if ($total==="0,00")
			{
				//$pdf->Cell(20,5,number_format($total,2,',','.'),0,0,'R',false);
			}
			else
			{
				$pdf->Cell(20,5,$total." ".EURO,0,0,'R',false);
			}		
		}
		
		$altura = $altura + 5;
		if ($row["descripcion"]!="")
		{
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}
			
			$alturaVieja=$altura;
			
			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["descripcion"]));
			$numeroDeFilas = $anchoDescripcion / ((125-10)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}
			
			$alturaVieja = $altura + (5*$numeroDeFilas);
			
			if ($alturaVieja>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}
			
			
			$pdf->SetXY($margen+10,$altura);	
			
			/*$notas2 = str_replace('€',EURO,$row["descripcion"]);
			$notas2 = str_replace('ñ',ene,$notas2);
			$notas2 = str_replace('Ñ',ene_may,$notas2);
			$notas2 = str_replace('á',a_acento,$notas2);
			$notas2 = str_replace('é',e_acento,$notas2);
			$notas2 = str_replace('í',i_acento,$notas2);
			$notas2 = str_replace('ó',o_acento,$notas2);
			$notas2 = str_replace('ú',u_acento,$notas2);
			$notas2 = str_replace('Á',a_acento_may,$notas2);
			$notas2 = str_replace('É',e_acento_may,$notas2);
			$notas2 = str_replace('Í',i_acento_may,$notas2);
			$notas2 = str_replace('Ó',o_acento_may,$notas2);
			$notas2 = str_replace('Ú',u_acento_may,$notas2);
			
			
			$pdf->MultiCell(125-10,4,$notas2,0,'L',false);*/
			$pdf->MultiCell(125-10,4,reemplazarSimbolos($row["descripcion"]),0,'L',false);
			
			
			$altura = $altura + (6*$numeroDeFilas);
			
		}
		
		$pdf->SetTextColor(255,0,0);
		if ($row["notaCibeles"]!="")
		{
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}
			
			$alturaVieja=$altura;
			
			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["notaCibeles"]));
			$numeroDeFilas = $anchoDescripcion / ((125-10)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}
			
			$alturaVieja = $altura + (5*$numeroDeFilas);
			
			if ($alturaVieja>$limiteAlturaDatos)
			{
				$pdf->SetTextColor(0,0,0);
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}
			
			
			$pdf->SetXY($margen+10,$altura);	
			
		
			
			/*$notas2 = str_replace('€',EURO,$row["notaCibeles"]);
			$notas2 = str_replace('ñ',ene,$notas2);
			$notas2 = str_replace('Ñ',ene_may,$notas2);
			$notas2 = str_replace('á',a_acento,$notas2);
			$notas2 = str_replace('é',e_acento,$notas2);
			$notas2 = str_replace('í',i_acento,$notas2);
			$notas2 = str_replace('ó',o_acento,$notas2);
			$notas2 = str_replace('ú',u_acento,$notas2);
			$notas2 = str_replace('Á',a_acento_may,$notas2);
			$notas2 = str_replace('É',e_acento_may,$notas2);
			$notas2 = str_replace('Í',i_acento_may,$notas2);
			$notas2 = str_replace('Ó',o_acento_may,$notas2);
			$notas2 = str_replace('Ú',u_acento_may,$notas2);
			
			//$pdf->MultiCell(125-10,4,$notas2,0,'L',false);
			
			$pdf->MultiCell(125-10,4,($notas2),0,'L',false);*/
			
			
			$pdf->MultiCell(125-10,4,reemplazarSimbolos($row["notaCibeles"]),0,'L',false);
			
			
			//$pdf->MultiCell(125-10,4,utf8_decode($row["notaCibeles"]),0,'L',false);
			
			
			//Precio 40,00 ?/millar Para - de 2.000 unidades
			
			
			$altura = $altura + (6*$numeroDeFilas);
			
		}
		//////////////////////
		if ($row["notaAdmonProd"]!="")
		{
			if ($altura>$limiteAlturaDatos)
			{
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}
			
			$alturaVieja=$altura;
			
			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($row["notaAdmonProd"]));
			$numeroDeFilas = $anchoDescripcion / ((125-10)-1);
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}
			
			$alturaVieja = $altura + (5*$numeroDeFilas);
			
			if ($alturaVieja>$limiteAlturaDatos)
			{
				$pdf->SetTextColor(0,0,0);
				nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);	
			}			
			
			$pdf->SetXY($margen+10,$altura);
			
			$pdf->MultiCell(125-10,4,reemplazarSimbolos($row["notaAdmonProd"]),0,'L',false);
			
			$altura = $altura + (6*$numeroDeFilas);
			
		}
		//////////////////////



		$pdf->SetTextColor(0,0,0);
		
		
	}
	
	$altura = $altura + 0;//95
	$pdf->Line($margen, $altura, 200, $altura);
	$altura = $altura + 1;
	$pdf->SetY($altura);
	
	
	//$mostrarTotalPresupuesto = $datosPresupuesto[0]["idVisualizarTotalPresu"];
	$mostrarTotalPresupuesto = 1;
	
	
	
	if ($mostrarTotalPresupuesto==1)	
	{
		//$pdf->SetY($altura+20);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(0,5,utf8_decode("Total Presupuesto (IVA no incluido): ").number_format($totalImporte,2,',','.')." ".EURO,0,0,'R',false);
	}
	
	$mostrarTotalFranqueo = $datosPresupuesto[0]["idVisualizarTotalFranqueo"];
	if ($mostrarTotalFranqueo>1)	
	{
		$altura = $altura + 5;//95
		$pdf->SetY($altura);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(0,5,utf8_decode($datosPresupuesto[0]["ivaFranqueo"]).": ". number_format($datosPresupuesto[0]["importeFranqueo"], 2, ',', '.') . " ".EURO,0,0,'R',false);		
	}
	
	
	//$altura = $altura + 45;
	
	if ($altura>260)
	{
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);
	}
	
	/*$altura = 210;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',8);
	
	$pdf->Cell(0,5,"FORMA DE PAGO: ",0,0,'L',false);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen+28,$altura);
	$pdf->Cell(0,5,utf8_decode($datosPresupuesto[0]["textoFormaPago"]),0,0,'L',false);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,5,utf8_decode("Estos precios se aumentarán con el IVA correspondiente, en este momento el 21%"),0,0,'L',false);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,utf8_decode("Atención Comercial: "),0,0,'L',false);
	
	
	$pdf->SetXY($margen+30,$altura);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(0,5,utf8_decode($datosPresupuesto[0]["nombreComercial"]." - ".$datosPresupuesto[0]["telefonoComercial"]),0,0,'L',false);
	
	$pdf->SetXY($margen+120,$altura);
	$pdf->MultiCell(50,5,utf8_decode("Raúl Barberá Alfonso\nDirector Comercial"),0,'C',false);
	
	
	$altura = $altura + 7;
	$pdf->SetXY($margen,$altura);	
	$pdf->SetFont('Arial','',8);	
	$pdf->MultiCell(0,5,utf8_decode("Presupuesto Aceptado por:\n(Imprescindible sello, nombre, cargo y firma)"),0,'L',false);
	
	
	$altura = $altura + 12;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,4,utf8_decode("*Todos los sobrantes que no tengan contratado la devolución, serán retirados en 14 días de nuestras instalaciones, pasado ese tiempo se destruirán, no admitiendose ningún tipo de reclamación.\nEn el supuesto que deseen la devolución del sobrante se factura un coste de 10 ").EURO.utf8_decode(" hasta 30 kg, debiendo comunicarlo antes de la finalización del trabajo.\n*Este presupuesto queda sujeto a la aceptación PREVIA de las Condiciones Generales, disponibles en nuestra página web: www.grupocibeles.es/ccgg.pdf"),0,'L',false);
	

		//$ancho = $pdf->GetPageWidth();
	$ruta="//172.26.0.44/d/Comercial/Privado/Presupuestos/PRUEBAS/";
	if ($dr = opendir($ruta)) 
	{ 
	 // leemos el directorio
	 $contador=0;
	$prueba=5;
	 while (($file = readdir($dr)) !== false) 
	 { 
		$contador++;
		// validamos que los datos devueltos sean un directorio y no archivo
		//if (is_dir($directorio . $file) && $file!="." && $file!="..")
		if (!is_dir($file) && $file!=".." && $file!="..")		
		{ 
			if (substr($file,0, strpos($file, "-")-1) == "Presupuesto ".$nombrePresupuestoCompleto)
			{
				$prueba=$prueba+5;
				$pdf->SetXY($margen+50,$prueba);
				$pdf->MultiCell(50,5,utf8_decode(substr($file,0,  strpos($file, "-"))),0,'C',false); 
				unlink($ruta.$file);
			}

		   //de ser un directorio lo imprimimos en pantalla
		   //echo "<br>: $directorio$file"; 
			//$resultado .= '<option value="'.$contador.'">'.$file.'</option>';
		} 
	 } 
	 // al finalizar cerramos el directorio
	 closedir($dr);
	} 
	
	*/
	
	
	//$pdf->Output("F",$ruta."Presupuesto ".$nombrePresupuestoCompleto." - ".$nombrePresupuesto." - ".date("dmy")." .pdf","UTF-8");
	$pdf->Output("I",$ruta."Presupuesto - ".date("dmy")." .pdf","UTF-8");
	
	
	// presupuesto + numero + letra + campaña + fechaActual(6 digitos)
}
else
{
	
}


function nuevaPagina(&$pdf,&$altura,$alturaSiguientePagina,$mostrarPrecio)
{
	$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	$altura = $alturaSiguientePagina;
	if ($mostrarPrecio==0)
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
		
	}
	$altura=$altura+5;
}

function redondear_dos_decimal($valor) {
	$float_redondeado=0.00;
   $float_redondeado=round($valor * 100, 0) / 100;
   return $float_redondeado;
}

?>
	//$formasDePago=cargarFormasDePago($conexion);
	
	
	
	
	
		


