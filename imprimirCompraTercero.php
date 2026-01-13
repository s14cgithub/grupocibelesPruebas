<?php 
session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"])&$_POST["imprimirAccion"]=="imprimirCompra")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	
	/*require($ruta."FPDF/fpdf.php");
	
	require($ruta."Archivos Comunes/cabeceraPieInforme.php");*/
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	require($ruta."Archivos Comunes/rotate.php");
	require($ruta."Archivos Comunes/cabeceraPieFacturaGroupPag2.php");
	
	
	
	$numeroPedido = isset($_POST["imprimirNumeroCompra"]) ? $_POST["imprimirNumeroCompra"] : 0;
	$previsualizar = isset($_POST["imprimirPrevisualizacion"]) ? $_POST["imprimirPrevisualizacion"] : 1;
	//$previsualizar=1;
	
	$pdf = new cabeceraFactura('P','mm','A4');	
	
	
	
	$pdf->StartPageGroup();	

	
	$condicion = " where t1.pedido = ".$numeroPedido;
	$datosCompra = cargarComprasTerceros($conexion,$condicion);
	
	
	$datosCompraDetalle = mostrarComprarTerceroDetalles($conexion,$numeroPedido);		
	
	
	
	if (count($datosCompraDetalle)>0)
	{
		
		$pdf->SetLeftMargin(20);
		$pdf->SetRightMargin(20);

		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);
		
		$pdf->AddPage();
		

		$contador=0;	



		$pdf->SetFont('Arial','B',10);
		$altura=35;
		$margen=10;
		$alturaSiguientePagina=40;
		$limiteAlturaDatos=250;

		nuevaPagina($pdf,$altura,$margen, $datosCompra,$previsualizar);
		
		

		
		$contador=0;
		
		$pdf->SetFont('Arial','',8);
		
		$totalSuma = 0.00;
		
		while ($contador < count($datosCompraDetalle))
		{
			$altura = $altura + 5;
			
			
			
			if ($altura>210)
			{
				$pdf->SetFont('Arial','BI',8);
				
				$pdf->SetXY(120,265);			
				$pdf->Cell(0,5,"FR 07.03.3",0,0,'R',false);
				
				$pdf->AddPage();
				$altura=35;
				$margen=10;
				nuevaPagina($pdf,$altura,$margen, $datosCompra,$previsualizar);
				$altura = $altura + 5;
			}

			
			
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(110,5,utf8_decode($datosCompraDetalle[$contador]["descripcion"]),0,'L',false);
			
			$pdf->SetXY(120,$altura);			
			$pdf->Cell(20,5,number_format($datosCompraDetalle[$contador]["cantidad"],3,',','.'),0,0,'R',false);
			//$pdf->Cell(20,5,$datosCompraDetalle[$contador]["cantidad"],0,0,'R',false);
			
			
			$aux=(float)$datosCompraDetalle[$contador]["precioUnidad"];
			$longitud = strlen(substr(strrchr($aux, "."), 1));


			//echo $aux;


			$pdf->SetXY(140,$altura);			
			$pdf->Cell(30,5,number_format($datosCompraDetalle[$contador]["precioUnidad"],$longitud,',','.')." ".EURO,0,0,'R',false);
			
			
			$pdf->SetXY(170,$altura);			
			$pdf->Cell(30,5,number_format($datosCompraDetalle[$contador]["total"],2,',','.')." ".EURO,0,0,'R',false);
			
			
			$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datosCompraDetalle[$contador]["descripcion"]));
			$numeroDeFilas = $anchoDescripcion / ((110-10)-1);
			
			if ($numeroDeFilas<1)
			{
				$numeroDeFilas = 1;
			}

			//$altura -= 5;
			$altura = $altura + (5*$numeroDeFilas);
			
			
			$totalSuma += $datosCompraDetalle[$contador]["total"];
			
			$contador++;
		}
		
		
		$altura += 5;
		$pdf->SetFont('Arial','B',8);
		$pdf->SetXY(170,$altura);			
		$pdf->Cell(30,5,"Total: ".number_format($totalSuma,2,',','.')." ".EURO,0,0,'R',false);
		
		
		

		//$cantidad = $datosPresupuesto[0]["cantidad"];

		/*if ($cantidad=="null" || $cantidad==null || $cantidad==0 )
		{
			$cantidad=0;
		}
		else
		{
			$pdf->Cell(0,0,"Cantidad: ".number_format($cantidad,0,',','.'),0,0,'C',false);
			//$pdf->Cell(0,0,"Cantidad: ".number_format($cantidad,2,',','.'),0,0,'C',false);
		}*/



		




		$altura = $altura + 5;//85
		$ancho = $pdf->GetPageWidth();
		$pdf->SetTextColor(0,0,0);

		$pdf->SetFont('Arial','B',8);




		$altura = $altura + 5;//95
		$pdf->Line($margen, $altura, 200, $altura);
		$altura = $altura + 1;
		$pdf->SetY($altura);


		
		
		
		
		
		
		

		$altura = 220;//220
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','B',10);

		$numDelPedido = $datosCompra[0]["pedido"];
		$numDelPresu = $datosCompra[0]["presupuesto"];
		if ($previsualizar==1)
		{
			$numDelPedido = "XXXXX";
			$numDelPresu = "XXXXX";
		}
		$pdf->Cell(0,5,utf8_decode("Es imprescindible para su pago que se indique en la factura el número de pedido: Nº ".$numDelPedido." / ".$numDelPresu),0,0,'L',false);

		$altura = 225;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,5,utf8_decode("Y enviarlo al sigiuente correo electronico: proveedores@grupocibeles.es"),0,0,'L',false);
		
	

		$altura = $altura + 10;
		$pdf->SetXY($margen,$altura);
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(0,5,utf8_decode("Firma Solicitante"),0,0,'L',false);

		


		$pdf->SetXY($margen+30,$altura);
		$pdf->SetFont('Arial','I',8);
		//$pdf->Cell(0,5,utf8_decode($datosPresupuesto[0]["nombreComercial"]." - ".$datosPresupuesto[0]["telefonoComercial"]),0,0,'L',false);

		$pdf->SetXY($margen+120,$altura);
		$pdf->MultiCell(50,5,utf8_decode("Autorizado por Director:"),0,'C',false);
		
		
		$pdf->SetFont('Arial','',8);
		$altura += 10;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosCompra[0]["comercialNombre"]),0,1,'L',false);


		$pdf->SetFont('Arial','BI',8);
				
		$pdf->SetXY(120,265);			
		$pdf->Cell(0,5,"FR 07.03.3",0,0,'R',false);
		


		//$ancho = $pdf->GetPageWidth();

		/*if ($mensual=="00")
		{
			//$pdf->Output("F",rutaPresupuestos.$nombrePresupuestoCompleto." - Presupuesto - ".$nombrePresupuesto." - ".date("dmy").".pdf",true);
			//aqui
			$pdf->Output("F",rutaPresupuestos.$nombrePresupuestoCompleto." ".$nombrePresupuesto." ".date("dmy").".pdf",true);
		}*/


	}
		
		
		
		

	
	$pdf->SetTitle("PedidoCompra ".$numeroPedido." - ".date("dmy").".pdf",true);
	$pdf->SetSubject("PedidoCompra ".$numeroPedido." - ".date("dmy").".pdf",true);
	$pdf->Output("I","PedidoCompra ".$numeroPedido." - ".date("dmy").".pdf",true);
	/*$pdf->Output("F",rutaPresupuestos." BORRARRRRRRRR222222".$numDelPresu." ".date("dmy").".pdf",true);*/
	
	// presupuesto + numero + letra + campaña + fechaActual(6 digitos)
}
else
{
	
}


function insertarTitulos(&$pdf,&$altura,&$margen)
{
	//$pdf->AddPage();
	//$pdf->SetAutoPageBreak(false);
	$pdf->SetTextColor(0,0,0);

	$pdf->SetFont('Arial','B',8);
			
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(20,0,"DESCRIPCION",0,0,'R',false);

	$pdf->SetXY(120,$altura);
	$pdf->Cell(20,0,"CANTIDAD",0,0,'R',false);

	$pdf->SetXY(150,$altura);
	$pdf->Cell(20,0,"PRECIO UNIDAD",0,0,'R',false);
	$pdf->SetXY(180,$altura);
	$pdf->Cell(20,0,"TOTAL",0,0,'R',false);
		
	$pdf->SetFont('Arial','',8);
	//$altura=35;
	//$margen=10;
	
	
}

function nuevaPagina(&$pdf,&$altura,&$margen, $datosCompra,$previsualizar)
{
	$pdf->SetFont('Arial','B',10);
	$pdf->Text($margen,$altura,$datosCompra[0]["fecha"]->format('d/m/Y'));

	$pdf->SetTextColor(13,140,252);

	$pdf->Text($margen,$altura+5,"PEDIDO DE COMPRA:");
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	
	if ($previsualizar==0)
	{
		$pdf->Text($margen+40,$altura+5,$datosCompra[0]["pedido"]." / ".$datosCompra[0]["presupuesto"]);
	}
	


	$pdf->SetFont('Arial','',10);
	$altura += 10;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("Proveedor: "),0,1,'L',false);

	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY($margen+20,$altura);
	$pdf->Cell(0,0,utf8_decode($datosCompra[0]["nombreProveedor"]),0,1,'L',false);

	$pdf->SetFont('Arial','',10);
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Pedido por: ".utf8_decode($datosCompra[0]["comercialNombre"]),0,1,'L',false);

	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Fecha Pedido: ".utf8_decode($datosCompra[0]["fecha"]->format('d/m/Y')),0,1,'L',false);
	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$fechaEntrega = "";
	if ($datosCompra[0]["fechaEntrega"]!="")
	{
		$fechaEntrega = $datosCompra[0]["fechaEntrega"]->format('d/m/Y');
	}
	$pdf->Cell(0,0,"Fecha Entrega: ".$fechaEntrega,0,1,'L',false);

	$altura += 5;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"Forma Pago: ".utf8_decode($datosCompra[0]["formaPago"]),0,1,'L',false);



	$altura = $altura + 5;
	$pdf->Line($margen, $altura, 200, $altura);



	$altura = $altura + 5;

	$alturaNuevaPagina = $altura;

	insertarTitulos($pdf,$altura,$margen);
	if ($previsualizar==1)
	{
		imprimirPrevisualizacion($pdf);
	}
}

function imprimirPrevisualizacion(&$pdf)
{
	$pdf->SetTextColor(255,192,203);
	$pdf->SetFont('Arial','B',50);
	
	$pdf->SetXY(0,20);
	$pdf->Cell(0,0,utf8_decode("PREVISUALIZACION"),0,1,'C',false);
		
		
		
	$pdf->SetFont('Arial','B',10);
	
	$borradorYinicial=80;
	$borradorContador=0;
	$borradorContador2=80;
	
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
	$pdf->SetTextColor(0,0,0);
		
}

?>