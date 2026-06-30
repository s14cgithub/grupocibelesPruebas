

<?php 
session_start(); 

require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="previsualizarPresu")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");		
		
	
	require($ruta."FPDF/fpdf.php");
	//require($ruta."FPDF1-84/fpdf.php");
	
	require($ruta."Archivos Comunes/cabeceraPieInforme.php");	


	$numPresupuesto = $_POST['previsualizarNumPresupuesto'] ?  $_POST['previsualizarNumPresupuesto']: null;


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	

	$campos = [
		'presupuesto',
		'letra',
		'codigoCliente',
		'fecha',
		'inicialComercial',
		'cliente',
		'persona',
		'direccion',
		'cp',
		'poblacion',
		'campanaObservacion',
		'campana',
		'cantidad',
		'detallada',
		'idVisualizarTotalPresu',
		'idVisualizarTotalFranqueo',
		'ivaFranqueo',
		'importeFranqueo',
		'textoFormaPago',
		'nombreComercial',
		'telefonoComercial'			
		];
		
	$joins = ['tabla2', 'tabla3','tabla4'];



	$filtros = ['presupuesto' => $numPresupuesto];
	$filtrosOperadores = array();
	$order = array();

	$datosPresupuesto = cargarPresupuestos($conn,$bbddSql, $campos, $joins, $filtros,$filtrosOperadores, $order);
	
	
	$campos2 = [
		'concepto',
		'idTipo',
		'tipoProceso',
		'proceso',
		'unidades',
		'precio',
		'descripcion'
		];

	$joins2 = array();	
	$filtros2 = ['presupuesto' => $numPresupuesto];
	$filtrosOperadores2 = array();
	$order2 = [
			['campo' => 'ordenTipoProceso', 'dir' => 'ASC'],
			['campo' => 'idTipo', 'dir' => 'ASC'],
			['campo' => 'orden', 'dir' => 'ASC'],
			['campo' => 'id', 'dir' => 'ASC']

		];

	$datosDetalles = cargarDetallesPresupuesto($conn,$bbddSql, $campos2, $joins2, $filtros2,$filtrosOperadores2, $order2);

	$nombrePresupuestoCompleto = $datosPresupuesto["datos"][0]["presupuesto"]." ".$datosPresupuesto["datos"][0]["letra"];


	$idCliente = $datosPresupuesto["datos"][0]["codigoCliente"] !=null ? $datosPresupuesto["datos"][0]["codigoCliente"]:"-1" ; //si es -1 meter el codigoCliente en la tabla

	sqlsrv_close($conn);

	
	{	
	
	$pdf = new PDF('P','mm','A4');

	//$pdf->SetXY(-10,-5);


	$pdf->AddPage();
	$pdf->AliasNbPages();
	
	$contador=0;	
	
		
	$pdf->SetTextColor(255,192,203);
	$pdf->SetFont('Arial','B',50);
	
	$pdf->SetXY(0,20);
	$pdf->Cell(0,0,utf8_decode("PREVISUALIZACION"),0,1,'C',false);
		
		
		
	$pdf->SetFont('Arial','B',10);
	
	$borradorYinicial=0;
	$borradorContador=0;
	$borradorContador2=0;
	while ($borradorYinicial<220)
	{
		while ($borradorContador<220)
		{
			$pdf->SetXY($borradorContador,$borradorContador2);
			$pdf->Cell(5,0,utf8_decode("borrador"),0,1,'L',false);
			$borradorContador +=20;
			$borradorContador2 += 5;		
		}
		
		$borradorContador=0;
		$borradorContador2=$borradorYinicial;
		$borradorYinicial+=10;
		
	}
	
	/*$pdf->SetXY(0,100);
	$pdf->Cell(0,0,utf8_decode("borrador"),0,1,'C',false);*/
	$pdf->SetFont('Arial','B',50);	
	$pdf->SetXY(0,250);
	$pdf->Cell(0,0,utf8_decode("PREVISUALIZACION"),0,1,'C',false);
		
		

		
		
		
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	$altura=35;
	$margen=10;
	$alturaSiguientePagina=40;
	$limiteAlturaDatos=250;
	
	$pdf->Text($margen,$altura,$datosPresupuesto["datos"][0]["fecha"]->format('d/m/Y'));
	
	$pdf->SetTextColor(13,140,252);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen,$altura+5,"PRESUPUESTO:");
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen+30,$altura+5,$datosPresupuesto["datos"][0]["inicialComercial"]." - ".$datosPresupuesto["datos"][0]["presupuesto"]." ".$datosPresupuesto["datos"][0]["letra"]);
		
	
	$altura += 7;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($margen,$altura);
	$pdf->MultiCell(80,5,"Cod_cliente: ".$idCliente,0,'L',false);
	
	
	$altura -= 7;
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(110,$altura);
	$pdf->MultiCell(80,5,utf8_decode($datosPresupuesto["datos"][0]["cliente"])."\n".utf8_decode($datosPresupuesto["datos"][0]["persona"])."\n".utf8_decode($datosPresupuesto["datos"][0]["direccion"])."\n".$datosPresupuesto["datos"][0]["cp"]." ".utf8_decode($datosPresupuesto["datos"][0]["poblacion"]),0,'L',false);
	
	
	$pdf->SetTextColor(13,140,252);
	$pdf->SetFont('Arial','B',14);
		
		
	if ($datosPresupuesto["datos"][0]["campanaObservacion"]=="")
	{
		$altura = $altura + 34; //75
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosPresupuesto["datos"][0]["campana"]),0,1,'C',false);
	}
	else
	{
		$altura = $altura + 28; 
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosPresupuesto["datos"][0]["campanaObservacion"]),0,1,'C',false);
		
		$altura = $altura + 7; 
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosPresupuesto["datos"][0]["campana"]),0,1,'C',false);
		
		//$altura = $altura - 7; 
	}
		
	
	
		
		
	
	
	$altura = $altura + 8;//85
	$pdf->SetXY($margen,$altura);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	
	$cantidad = $datosPresupuesto["datos"][0]["cantidad"];
	
	if ($cantidad=="null" || $cantidad==null || $cantidad==0 )
	{
		$cantidad=0;
	}
	else
	{
		//$pdf->Cell(0,0,"Cantidad: ".number_format($cantidad,2,',','.'),0,0,'C',false);
		$pdf->Cell(0,0,"Cantidad: ".number_format($cantidad,0,',','.'),0,0,'C',false);
	}
		
	
	
	$altura = $altura + 4;//95
	$pdf->Line($margen, $altura, 200, $altura);
	
	
	
	
	
	
	$altura = $altura + 5;//85
	$ancho = $pdf->GetPageWidth();
	$pdf->SetTextColor(0,0,0);
	
	$pdf->SetFont('Arial','B',8);
	
	
	$mostrarPrecio = $datosPresupuesto["datos"][0]["detallada"];
	
	
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
	
	
	foreach ($datosDetalles["datos"] as $row) 
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
		
		$total = round($total,2); 
		
		
		$totalImporte = $totalImporte + $total;
		//$totalImporte =  number_format($total,2,',','.');
		
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
			
			$anchoDescripcion = $pdf->GetStringWidth(mb_convert_encoding($row["descripcion"], 'ISO-8859-1', 'UTF-8'));
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
			
			//$pdf->MultiCell(125-10,4,utf8_decode($row["descripcion"]),0,'L',false);
			$pdf->MultiCell(125-10,4,mb_convert_encoding($row["descripcion"], 'ISO-8859-1', 'UTF-8'),0,'L',false);
			
			$altura = $altura + (6*$numeroDeFilas);
			
		}
		
	}
	
	
		
	$altura = $altura + 5;//95
	$pdf->Line($margen, $altura, 200, $altura);
	$altura = $altura + 1;
	$pdf->SetY($altura);
	
	
	$mostrarTotalPresupuesto = $datosPresupuesto["datos"][0]["idVisualizarTotalPresu"];
	
	
	
	if ($mostrarTotalPresupuesto==1)	
	{
		//$pdf->SetY($altura+20);
		$pdf->SetFont('Arial','B',10);		
		$pdf->Cell(0,5,utf8_decode("Total Presupuesto (IVA no incluido): ").number_format($totalImporte,2,',','.')." ".EURO,0,0,'R',false);		
	}
	
	$mostrarTotalFranqueo = $datosPresupuesto["datos"][0]["idVisualizarTotalFranqueo"];
	if ($mostrarTotalFranqueo>1)	
	{
		$altura = $altura + 5;//95
		$pdf->SetY($altura);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(0,5,utf8_decode($datosPresupuesto["datos"][0]["ivaFranqueo"]).": ". number_format($datosPresupuesto["datos"][0]["importeFranqueo"], 2, ',', '.') . " ".EURO,0,0,'R',false);		
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
	$pdf->Cell(0,5,utf8_decode($datosPresupuesto["datos"][0]["textoFormaPago"]),0,0,'L',false);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,5,utf8_decode("Estos precios se aumentarán con el IVA correspondiente, en este momento el 21%"),0,0,'L',false);
	
	$altura = $altura + 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,5,utf8_decode("Atención Comercial: "),0,0,'L',false);
	
	
	$pdf->SetXY($margen+30,$altura);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(0,5,utf8_decode($datosPresupuesto["datos"][0]["nombreComercial"]." - ".$datosPresupuesto["datos"][0]["telefonoComercial"]),0,0,'L',false);
	
	$pdf->SetXY($margen+120,$altura);
	$pdf->MultiCell(50,5,utf8_decode("Alfonso Rodriguez Del Amo\nDirector Comercial"),0,'C',false);
	
	
	$altura = $altura + 7;
	$pdf->SetXY($margen,$altura);	
	$pdf->SetFont('Arial','',8);	
	$pdf->MultiCell(0,5,utf8_decode("Presupuesto Aceptado por:\n(Imprescindible sello, nombre, cargo y firma)"),0,'L',false);
	
	
	$altura = $altura + 12;
	$pdf->SetXY($margen,$altura);	
	$pdf->MultiCell(0,4,utf8_decode("*Todos los sobrantes que no tengan contratado la devolución, serán retirados en 14 días de nuestras instalaciones, pasado ese tiempo se destruirán, no admitiendose ningún tipo de reclamación.\nSi desean que les sea entregado el material sobrante, deberán comunicarlo antes de la finalización del trabajo. Si este servicio no está previamente cotizado, se le comunicará y se incluirá en el presupuesto.\n*Este presupuesto queda sujeto a la aceptación PREVIA de las Condiciones Generales, disponibles en nuestra página web: www.grupocibeles.es/ccgg.pdf"),0,'L',false);
	

		//$ancho = $pdf->GetPageWidth();
	
	
	
	
	
	//$pdf->Output('I', 'asf.pdf');
	//$pdf->Output('F','/asf.pdf');
	$pdf->SetTitle("Presupuesto ".$nombrePresupuestoCompleto." - ".date("dmy")." .pdf",true);
	$pdf->SetSubject("Presupuesto ".$nombrePresupuestoCompleto." - ".date("dmy")." .pdf",true);
	
		//$pdf->Output("I","Presupuesto.pdf",true);
	
	$pdf->Output("I","Presupuesto ".$nombrePresupuestoCompleto." - ".date("dmy")." .pdf","UTF-8");
	
	
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

	
	
	
	
	
		


