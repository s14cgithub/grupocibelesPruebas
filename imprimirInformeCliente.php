<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirCliente")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");		
	
	require($ruta."FPDF/fpdf.php");
	require($ruta."Archivos Comunes/pagegroup.php");
	
	
	$idCodigoSubCliente = $_POST["imprimirCodigoCliente"];	
	$clayma = $_POST["imprimirClaymaCliente"];	
	
	
	$condicion=" where codigo=".$idCodigoSubCliente;
	
	$origen="";

	$campos = [
		'nombre_franqueo',
		'fecha_alta',
		'codigo_saldo',
		'nif',
		'nombre_empresa',
		'codigo',
		'nif_subcliente',
		'subcliente',
		'direccion',
		'localidad',
		'provincia',
		'codigo_postal',
		'activo',	
		'correoDiario',	
		'retener',	
		'domiciliada',	
		'sinIva',	
		'fac_cuotaRecogida',	
		'fac_idPeriodo',	
		'fac_porCientoNoBonificable',	
		'fac_otrosConceptosFijos',	
		'fac_importeFijoOtrosConcepto',	
		'fac_idProvisionFondos',	
		'fac_cobroUnitarioEnvio',	
	];

	$joins = array();

	$filtros = array(
		"codigo" => $idCodigoSubCliente		
	);
	$filtrosOperadores = array();
	$order = array();

	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	
	
	if ($clayma=="1")
	{ 
		$datosInforme = cargarClientesClayma($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order);
		$origen = "CLAYMA";
	}
	else
	{
		$datosInforme = cargarClientes($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $order);
		$origen = "CibelesMailing";
	}
	
	
	//echo json_encode($datosInforme);
	
	//echo $condicion;
	
	$pdf = new PDF_PageGroup('P','mm','A4');
	
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);

	$pdf->StartPageGroup();

	$margenInicial=20;
	
	$contador=0;
	
	while ($contador<count($datosInforme["datos"]))
	{
		$nombreFranqueo = utf8_decode($datosInforme["datos"][$contador]["nombre_franqueo"]);
		
		nuevaPagina($pdf,$altura, $nombreFranqueo);
		
		
		
		
		$altura=35;
		$margen=$margenInicial;
		
		$pdf->SetFont('Arial','B',8);		
		$pdf->SetXY($margen,$altura-5);
		$pdf->Cell(0,0,"Informe: ".date('d/m/Y'),0,1,'R',false);
		
		$altura+=5;
		
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($origen),0,1,'L',false);
		
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(140,0,utf8_decode("Fecha de Alta:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$datosInforme["datos"][$contador]["fecha_alta"]->format('d/m/Y'),0,1,'R',false);
		$altura+=10;
		
		$altura=50;
		
		$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Código del Cliente:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+50,$altura);
		$pdf->Cell(0,0,$datosInforme["datos"][$contador]["codigo_saldo"],0,1,'L',false);
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(140,0,utf8_decode("Nif del Cliente:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$datosInforme["datos"][$contador]["nif"],0,1,'R',false);
		$altura+=10;
		
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Cliente:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen+20,$altura-2.5);
		$pdf->MultiCell(0,5,utf8_decode($datosInforme["datos"][$contador]["nombre_empresa"]),0,'L',false);	
		$altura+=15;
		
		
		
		$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Código del Sub-Cliente:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+50,$altura);
		$pdf->Cell(0,0,$datosInforme["datos"][$contador]["codigo"],0,1,'L',false);
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(140,0,utf8_decode("Nif del Sub-Cliente:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$datosInforme["datos"][$contador]["nif_subcliente"],0,1,'R',false);
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Sub-Cliente:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen+25,$altura-2.5);
		$pdf->MultiCell(0,5,utf8_decode($datosInforme["datos"][$contador]["subcliente"]),0,'L',false);	
		$altura+=15;
		
		
		
		/*$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Nombre de Franqueo:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+50,$altura);
		$pdf->Cell(0,0,utf8_decode($datosInforme["datos"][$contador]["nombre_franqueo"]),0,1,'L',false);
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(140,0,utf8_decode("Fecha de Alta:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$datosInforme["datos"][$contador]["fecha_alta"]->format('d/m/Y'),0,1,'R',false);
		$altura+=10;*/
		
		$pdf->SetLineWidth(0.3);
		$pdf->Line($margen, $altura-5, 190, $altura-5);
		
		$pdf->SetFont('Arial','',12);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Dirección:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen+25,$altura-2.5);
		$pdf->MultiCell(0,5,utf8_decode($datosInforme["datos"][$contador]["direccion"]),0,'L',false);	
		$altura+=15;
		
		
		$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Localidad:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+25,$altura);
		$pdf->Cell(0,0,utf8_decode($datosInforme["datos"][$contador]["localidad"]),0,1,'L',false);
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(130,0,utf8_decode("Provincia:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosInforme["datos"][$contador]["provincia"]),0,1,'R',false);
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Código Postal:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+30,$altura);
		$pdf->Cell(0,0,utf8_decode($datosInforme["datos"][$contador]["codigo_postal"]),0,1,'L',false);
		$altura+=10;
		
		/*$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(140,0,utf8_decode("Comercial:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosInforme["datos"][$contador]["comercial"]),0,1,'R',false);
		$altura+=10;*/
		
		/*$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Saldo:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+30,$altura);
		$pdf->Cell(0,0,number_format($datosInforme["datos"][$contador]["importePF"],2,',','.')." " .EURO,0,1,'L',false);		
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(100,0,utf8_decode("IBAN:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode($datosInforme["datos"][$contador]["numCuentaBanco"]),0,1,'R',false);
		$altura+=10;*/
		
		$pdf->Line($margen, $altura-5, 190, $altura-5);
		
		$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Activo:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+20,$altura);
		$check="No";
		if ($datosInforme["datos"][$contador]["activo"]==1)
		{
			$check = "Sí";
		}
		$pdf->Cell(0,0,utf8_decode($check),0,1,'L',false);		
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(85,0,utf8_decode("Correo Diario:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen+85,$altura);
		$check="No";
		if ($datosInforme["datos"][$contador]["correoDiario"]==1)
		{
			$check = "Sí";
		}
		$pdf->Cell(0,0,utf8_decode($check),0,1,'L',false);
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(160,0,utf8_decode("Retener:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$check="No";
		if ($datosInforme["datos"][$contador]["retener"]==1)
		{
			$check = "Sí";
		}
		$pdf->Cell(0,0,utf8_decode($check),0,1,'R',false);		
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);	
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,utf8_decode("Domicialiado:"),0,1,'L',false);
		$pdf->SetFont('Arial','B',12);	
		$pdf->SetXY($margen+30,$altura);
		$check="No";
		if ($datosInforme["datos"][$contador]["domiciliada"]==1)
		{
			$check = "Sí";
		}
		$pdf->Cell(0,0,utf8_decode($check),0,1,'L',false);		
		
		$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(85,0,utf8_decode("Sin Iva:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen+85,$altura);
		$check="No";
		if ($datosInforme["datos"][$contador]["sinIva"]==1)
		{
			$check = "Sí";
		}
		$pdf->Cell(0,0,utf8_decode($check),0,1,'L',false);
		
		/*$pdf->SetFont('Arial','',12);		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(160,0,utf8_decode("Retener:"),0,1,'R',false);
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen,$altura);
		$check="No";
		if ($datosInforme["datos"][$contador]["retener"]==1)
		{
			$check = "Sí";
		}
		$pdf->Cell(0,0,utf8_decode($check),0,1,'R',false);*/
		
		$altura+=10;
		
		

		$campos2 = [
			'sexo',
			'nombre',
			'apellidos',
			'cargo',
			'departamento',
			'telefono',
			'email',
			'comentario',
			'movil'			
		];

		$joins2 = array('tabla2');

		$filtros2 = array(
			"idCliente" => $datosInforme["datos"][$contador]["codigo"]		
		);
		$filtrosOperadores2 = array();
		$order2 = array();
		
		
		if ($clayma=="1")
		{ 
			
			$contactos = cargarClientesContactosClayma ($conn, $bbddSql, $campos2, $filtros2, $filtrosOperadores2, $order2,$joins2);
		}
		else
		{
			$contactos = cargarClientesContactos ($conn, $bbddSql, $campos2, $filtros2, $filtrosOperadores2, $order2,$joins2);		
		}
		
			//echo json_encode($contactos);
			
			$contador2=0;
			while ($contador2 < count($contactos["datos"]))
			{
				if ($altura>=200)
				{
					nuevaPagina($pdf,$altura,$nombreFranqueo);
				}
				$pdf->Line($margen, $altura-5, 190, $altura-5);
				//$pdf->Cell(0,0,"altura: ".$altura,0,1,'L',false);
				$pdf->SetFont('Arial','',12);	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode("Sexo:"),0,1,'L',false);
				$pdf->SetFont('Arial','B',12);	
				$pdf->SetXY($margen+13,$altura);				
				$pdf->Cell(0,0,utf8_decode($contactos["datos"][$contador2]["sexo"]),0,1,'L',false);		

				$pdf->SetFont('Arial','',12);		
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(60,0,utf8_decode("Nombre:"),0,1,'R',false);
				$pdf->SetFont('Arial','B',12);		
				$pdf->SetXY($margen+60,$altura);
				$pdf->Cell(0,0,utf8_decode($contactos["datos"][$contador2]["nombre"]) ." ".utf8_decode($contactos["datos"][$contador2]["apellidos"]),0,1,'L',false);
				$altura+=10;
				
				$pdf->SetFont('Arial','',12);	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode("Cargo:"),0,1,'L',false);
				$pdf->SetFont('Arial','B',12);	
				$pdf->SetXY($margen+13,$altura);				
				$pdf->Cell(0,0,utf8_decode($contactos["datos"][$contador2]["cargo"]),0,1,'L',false);		
				$altura+=10;
				
				$pdf->SetFont('Arial','',12);	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode("Departamento:"),0,1,'L',false);
				$pdf->SetFont('Arial','B',12);	
				$pdf->SetXY($margen+30,$altura);				
				$pdf->Cell(0,0,utf8_decode($contactos["datos"][$contador2]["departamento"]),0,1,'L',false);		
				$altura+=10;
				
				$pdf->SetFont('Arial','',12);	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode("Telefonos:"),0,1,'L',false);
				$pdf->SetFont('Arial','B',12);	
				$pdf->SetXY($margen+23,$altura);				
				$pdf->Cell(0,0,utf8_decode($contactos["datos"][$contador2]["telefono"]) . " " . utf8_decode($contactos["datos"][$contador2]["movil"]),0,1,'L',false);		
				$altura+=10;
				
				$pdf->SetFont('Arial','',12);	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode("Email:"),0,1,'L',false);
				$pdf->SetFont('Arial','B',12);	
				$pdf->SetXY($margen+23,$altura);				
				$pdf->Cell(0,0,utf8_decode($contactos["datos"][$contador2]["email"]),0,1,'L',false);	
				$altura+=10;
				
				$pdf->SetFont('Arial','',12);	
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,utf8_decode("Comentario:"),0,1,'L',false);
				$pdf->SetFont('Arial','B',12);	
				$pdf->SetXY($margen+25,$altura);				
				$pdf->MultiCell(0,5,utf8_decode($contactos["datos"][$contador2]["comentario"]),0,'L',false);	

				$anchoDescripcion = $pdf->GetStringWidth($contactos["datos"][$contador2]["comentario"]);
				$numeroDeFilas = ceil ($anchoDescripcion / 84);
				if ($numeroDeFilas<1)
				{
					$numeroDeFilas = 1;
				}	
				$altura = $altura + (5*$numeroDeFilas);


				$altura+=10;


				
				$contador2++;
			}
			
		
		
		
		
		
		$altura+=10;
		
		if ($datosInforme["datos"][$contador]["correoDiario"]==1)
		{
			$pdf->Line($margen, $altura-5, 190, $altura-5);
			
			$pdf->SetFont('Arial','',12);	
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode("Cuota Recogida:"),0,1,'L',false);
			$pdf->SetFont('Arial','B',12);	
			$pdf->SetXY($margen+40,$altura);			
			$pdf->Cell(0,0,number_format($datosInforme["datos"][$contador]["fac_cuotaRecogida"],2,',','.')." " .EURO,0,1,'L',false);		

			$pdf->SetFont('Arial','',12);		
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(85,0,utf8_decode("Periodo:"),0,1,'R',false);
			$pdf->SetFont('Arial','B',12);		
			$pdf->SetXY($margen+85,$altura);
			$valor="";
			if ($datosInforme["datos"][$contador]["fac_idPeriodo"]=="1")
			{
				$valor = "Mensual";
			}
			else if ($datosInforme["datos"][$contador]["fac_idPeriodo"]=="2")
			{
				$valor = "Especial";
			}
			$pdf->Cell(0,0,$valor,0,1,'L',false);

			$pdf->SetFont('Arial','',12);		
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(150,0,utf8_decode("% Prod. No Bon:"),0,1,'R',false);
			$pdf->SetFont('Arial','B',12);		
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($datosInforme["datos"][$contador]["fac_porCientoNoBonificable"],2,',','.')." " .EURO,0,1,'R',false);	
			$altura+=10;
			
			$pdf->SetFont('Arial','',12);	
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode("Otros Conceptos:"),0,1,'L',false);
			$pdf->SetFont('Arial','B',10);	
			$pdf->SetXY($margen+35,$altura);			
			$pdf->Cell(0,0,$datosInforme["datos"][$contador]["fac_otrosConceptosFijos"],0,1,'L',false);		

			$pdf->SetFont('Arial','',12);		
			$pdf->SetXY($margen+115,$altura);
			$pdf->Cell(0,0,utf8_decode("Importe Fijo:"),0,1,'L',false);
			$pdf->SetFont('Arial','B',12);		
			$pdf->SetXY($margen,$altura);			
			$pdf->Cell(0,0,number_format($datosInforme["datos"][$contador]["fac_importeFijoOtrosConcepto"],2,',','.')." " .EURO,0,1,'R',false);				
			$altura+=10;
			
			
			$pdf->SetFont('Arial','',12);	
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,utf8_decode("Provision de Fondos:"),0,1,'L',false);
			$pdf->SetFont('Arial','B',12);	
			$pdf->SetXY($margen+45,$altura);
			$valor="";
			if ($datosInforme["datos"][$contador]["fac_idProvisionFondos"]=="1")
			{
				$valor = "Fija";
			}
			else if ($datosInforme["datos"][$contador]["fac_idProvisionFondos"]=="2")
			{
				$valor = "Variable";
			}
			$pdf->Cell(0,0,$valor,0,1,'L',false);

			$pdf->SetFont('Arial','',12);		
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(150,0,utf8_decode("Cobro Unitario Envío:"),0,1,'R',false);
			$pdf->SetFont('Arial','B',12);		
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,0,number_format($datosInforme["datos"][$contador]["fac_cobroUnitarioEnvio"],2,',','.')." " .EURO,0,1,'R',false);	
			$altura+=10;
			
			
			
			
			
		}
		
		
		$contador++;
	}

	sqlsrv_close($conn);
	$pdf->Output("I",$ruta."Provision de Fondo - ".date("dmy")." .pdf","UTF-8");
	
}
else
{
	
}




function redondear_dos_decimal($valor) {
	$float_redondeado=0.00;
   $float_redondeado=round($valor * 100, 0) / 100;
   return $float_redondeado;
}

function nuevaPagina(&$pdf ,&$altura,$nombreFranqueo)
{
	$pdf->AddPage();
	//$pdf->StartPageGroup();
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);
	
	$margenInicial=20;
	$margen=20;
	$altura=10;
	
	$pdf->SetXY($margen,$altura);
	$pdf->Image('imagenes/cabeceraInforme.jpg',-5,-5,220);
	
	$pdf->SetY(-15);
	$pdf->Image('imagenes/pieInforme.jpg',0,269,220);
	// Arial italic 8
	$pdf->SetFont('Arial','I',8);
	// Número de página
	$pdf->SetXY(0,260);
	$pdf->Cell(0,10,utf8_decode('Página ').$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias(),0,0,'R');
	
	$pdf->SetTextColor(255,0,0);
		$pdf->SetFont('Arial','B',16);	
		$altura=15;
		$margen=40;
		
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0,$nombreFranqueo,0,1,'C',false);
		
		$pdf->SetTextColor(0,0,0);
	
	$altura=50;
		
	
	//$pdf->Cell(0,5,utf8_decode('Página ').$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias(),0,0,'C');
	
		
	

}

?>
	
	
	
	
	
	
		


