<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirPresu")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	require($ruta."FPDF/fpdf.php");
	
	require($ruta."Archivos Comunes/cabeceraPieInforme.php");
	
	
	
	$numPresupuesto = $_POST["imprimirNumPresupuesto"];	
	$nombrePresupuesto = $_POST["nombreCarpetaForm"];
	
	
	$datosPresupuesto = verPresupuesto($conexion,$numPresupuesto);
	$datosDetalles = cargarDetallesPresupuesto($conexion,$numPresupuesto);
	
	
	//$nombrePresupuestoCompleto = $datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"];
	
	
	
	if ($datosPresupuesto[0]["clayma"]=="0")
	{
		$datosCliente = verCodigoSaldoPorNombreCliente($conexion, $datosPresupuesto[0]["cliente"]);
	}
	else
	{
		$datosCliente = verCodigoSaldoPorNombreClienteClayma($conexion, $datosPresupuesto[0]["cliente"]);
	}
	
	
	
	
	$idCliente="0000";
	if (count($datosCliente)>0)
	{
		$idCliente = $datosCliente[0]["codigo_saldo"];
	}
	
	$nombrePresupuestoCompleto = $datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"]." ".$idCliente;
	
	//SE MIRA SI EL PDF YA ESTA CREADO
	
	//$ruta="//172.26.0.44/d/Comercial/Privado/Presupuestos/Pruebas/";
	
	/*$existe = false;
	
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
					/*$prueba=$prueba+5;
					$pdf->SetXY($margen+50,$prueba);
					$pdf->MultiCell(50,5,utf8_decode(substr($file,0,  strpos($file, "-"))),0,'C',false); 
					unlink($ruta.$file);*/
					/*$existe = true;
				}

			   //de ser un directorio lo imprimimos en pantalla
			   //echo "<br>: $directorio$file"; 
				//$resultado .= '<option value="'.$contador.'">'.$file.'</option>';
			} 
		 } 
		 // al finalizar cerramos el directorio
		 closedir($dr);
	} 
	
	
	
	
	
	if ($existe)
	{
		echo "EL PDF YA HA SIDO GENERADO\nCREAR UNA NUEVA VERSION";
	}
	else*/
	{
		
		
	
	
	
	
	
	
	
	
	
	
	
	$pdf = new PDF('P','mm','A4');

	//$pdf->SetXY(-10,-5);


	$pdf->AddPage();
	$pdf->AliasNbPages();
	//$pdf->SetAutoPageBreak(false);
	$contador=0;	
	
/*while ($contador<=count($datosPresupuesto))	
	{
		//$datosPresupuesto[$contador]["fecha"];
		$contador++;
	}*/

	$pdf->SetFont('Arial','B',10);
	$altura=35;
	$margen=10;
	$alturaSiguientePagina=40;
	$limiteAlturaDatos=250;
	
	$pdf->Text($margen,$altura,$datosPresupuesto[0]["fecha"]->format('d/m/Y'));
	
	$pdf->SetTextColor(13,140,252);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen,$altura+5,"PRESUPUESTO:");
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen+30,$altura+5,$datosPresupuesto[0]["inicialComercial"]." - ".$datosPresupuesto[0]["presupuesto"]." ".$datosPresupuesto[0]["letra"]);
	
	
	$altura += 7;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(80,5,"Cod_cliente: ".$idCliente,0,'L',false);
	
	
	$altura -= 7;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(110,$altura);
	$pdf->MultiCell(80,5,utf8_decode($datosPresupuesto[0]["cliente"])."\n".utf8_decode($datosPresupuesto[0]["persona"])."\n".utf8_decode($datosPresupuesto[0]["direccion"])."\n".$datosPresupuesto[0]["cp"]." ".utf8_decode($datosPresupuesto[0]["poblacion"]),0,'L',false);
	
	
	$pdf->SetTextColor(13,140,252);
	$pdf->SetFont('Arial','B',14);
	$altura = $altura + 34; //75
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode($datosPresupuesto[0]["campana"]),0,1,'C',false);
	
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
		//$pdf->Cell(0,0,"Cantidad: ".number_format($cantidad,2,',','.'),0,0,'C',false);
	}
		
	
	
	$altura = $altura + 4;//95
	$pdf->Line($margen, $altura, 200, $altura);
	
	
	
	
	
	
	$altura = $altura + 5;//85
	$ancho = $pdf->GetPageWidth();
	$pdf->SetTextColor(0,0,0);
	
	$pdf->SetFont('Arial','B',8);
	
	
	$mostrarPrecio = $datosPresupuesto[0]["detallada"];
	
	
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
		$total="";
		
		
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
		
		
		
		$totalImporte = $totalImporte + $total;
		
		/*if ($total==0)
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
			
			
			/*$temporal = str_replace('€',EURO,$row["descripcion"]);			
			$temporal = str_replace('ñ',ene,$temporal);
			$temporal = str_replace('Ñ',ene_may,$temporal);
			$temporal = str_replace('á',a_acento,$temporal);
			$temporal = str_replace('é',e_acento,$temporal);
			$temporal = str_replace('í',i_acento,$temporal);
			$temporal = str_replace('ó',o_acento,$temporal);
			$temporal = str_replace('ú',u_acento,$temporal);
			$temporal = str_replace('Á',a_acento_may,$temporal);
			$temporal = str_replace('É',e_acento_may,$temporal);
			$temporal = str_replace('Í',i_acento_may,$temporal);
			$temporal = str_replace('Ó',o_acento_may,$temporal);
			$temporal = str_replace('Ú',u_acento_may,$temporal);
			$temporal = str_replace('º',signo_grado,$temporal);
			$temporal = str_replace('ª',signo_ordinal,$temporal);
			
			
			
			
			
			$pdf->MultiCell(125-10,4,$temporal,0,'L',false);*/
			
			$pdf->MultiCell(125-10,4,reemplazarSimbolos($row["descripcion"]),0,'L',false);
			
			$altura = $altura + (6*$numeroDeFilas);
			
		}
		
	}
	
	$altura = $altura + 0;//95
	$pdf->Line($margen, $altura, 200, $altura);
	$altura = $altura + 1;
	$pdf->SetY($altura);
	
	
	$mostrarTotalPresupuesto = $datosPresupuesto[0]["idVisualizarTotalPresu"];
	
	
	
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
	if ($altura>210)
	{
		nuevaPagina($pdf,$altura,$alturaSiguientePagina,$mostrarPrecio);
	}
	
	$altura = 210;
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
	$pdf->MultiCell(50,5,utf8_decode("Alfonso Rodriguez Del Amo\nDirector Comercial"),0,'C',false);
	
	
	$altura = $altura + 7;
	$pdf->SetXY($margen,$altura);	
	$pdf->SetFont('Arial','',8);	
	$pdf->MultiCell(0,5,utf8_decode("Presupuesto Aceptado por:\n(Imprescindible sello, nombre, cargo y firma)"),0,'L',false);
	
	
	$altura = $altura + 12;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,4,utf8_decode("*Todos los sobrantes que no tengan contratado la devolución, serán retirados en 14 días de nuestras instalaciones, pasado ese tiempo se destruirán, no admitiendose ningún tipo de reclamación.\nEn el supuesto que deseen la devolución del sobrante se factura un coste de 10 ").EURO.utf8_decode(" hasta 30 kg, debiendo comunicarlo antes de la finalización del trabajo.\n*Este presupuesto queda sujeto a la aceptación PREVIA de las Condiciones Generales, disponibles en nuestra página web: www.grupocibeles.es/ccgg.pdf"),0,'L',false);
	

		//$ancho = $pdf->GetPageWidth();
	
	
	
	
	
		
		
	$pdf->Output("F",rutaPresupuestos.$nombrePresupuestoCompleto." - Presupuesto - ".$nombrePresupuesto." - ".date("dmy").".pdf","UTF-8");
	$pdf->Output("I",rutaPresupuestos."Presupuesto ".$nombrePresupuestoCompleto." - ".$nombrePresupuesto." - ".date("dmy").".pdf","UTF-8");
	
	}
	
	
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

?>
	<!--$formasDePago=cargarFormasDePago($conexion);-->
	
	
	
	
	
		


