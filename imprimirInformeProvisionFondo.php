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
	require($ruta."Archivos Comunes/pagegroup.php");
	
	
	//$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$campos = [
		'presupuesto',
		'codigo',
		'nombre_empresa',
		'fechaCreacion',
		'tipoNombre',
		'importe',
		'cobradaNombre',
		'fechaCobro',
		'formaPago',
		'campana',
		'noAplicarPF',
		'cobrada'
		

	];
	$joins = isset($_POST["joins"]) ? json_decode($_POST["joins"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();
	$filtrosLike = isset($_POST["filtrosLike"]) ? json_decode($_POST["filtrosLike"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$datosProvision = cargarProvisionDeFondos_Todo($conn, $bbddSql, $campos, $filtros, $filtrosOperadores, $filtrosLike, $order);
	
	//echo json_encode($datosProvision);
	sqlsrv_close($conn);
	
	
	$pdf = new PDF_PageGroup('P','mm','A4');
	
	$pdf->SetLeftMargin(20);
	$pdf->SetRightMargin(20);

	$pdf->StartPageGroup();

	$margenInicial=20;
	
	$contador=0;
	
	while ($contador<count($datosProvision["datos"]))
	{
		nuevaPagina($pdf);
		
		
		$pdf->SetTextColor(255,0,0);
		$pdf->SetFont('Arial','B',16);	
		$altura=18;
		$margen=85;
		$pdf->Text($margen,$altura,"PROVISION DE FONDO");
		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);
		$altura=50;
		$margen=$margenInicial;
		$pdf->Text($margen,$altura,"Presupuesto:");
		$pdf->SetFont('Arial','B',12);
		$pdf->Text($margen+30,$altura,$datosProvision["datos"][$contador]["presupuesto"]);
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,"Codigo del Cliente:");
		$pdf->SetFont('Arial','B',12);
		$pdf->Text($margen + 40,$altura,$datosProvision["datos"][$contador]["codigo"]);		
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,"Cliente:");
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen+20,$altura-4);
		$pdf->MultiCell(0,5,utf8_decode($datosProvision["datos"][$contador]["nombre_empresa"]),0,'L',false);	
		$altura+=15;
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,utf8_decode("Campaña:"));
		$pdf->SetFont('Arial','B',12);		
		$pdf->SetXY($margen+25,$altura-4);
		$pdf->MultiCell(0,5,utf8_decode($datosProvision["datos"][$contador]["campana"]),0,'L',false);
		$altura+=15;
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,utf8_decode("Fecha de Creación:"));
		$pdf->SetFont('Arial','B',12);
		$pdf->Text($margen + 40,$altura,$datosProvision["datos"][$contador]["fechaCreacion"]->format('d/m/Y'));		
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,utf8_decode("Tipo:"));
		$pdf->SetFont('Arial','B',12);
		$pdf->Text($margen + 13,$altura,$datosProvision["datos"][$contador]["tipoNombre"]);		
		$altura+=10;	
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,utf8_decode("Importe:"));
		$pdf->SetFont('Arial','B',12);
		$pdf->Text($margen + 20,$altura,number_format($datosProvision["datos"][$contador]["importe"],2,',','.'). " ". EURO);		
		$altura+=10;
		
		$pdf->SetFont('Arial','',12);
		$pdf->Text($margen,$altura,utf8_decode("Cobrada:"));
		$pdf->SetFont('Arial','B',12);

		$cobradaSinAplicarPF="";
		if ($datosProvision["datos"][$contador]["noAplicarPF"]==1)
		{
			$cobradaSinAplicarPF = " (No Aplicar)";
		}

		$pdf->Text($margen + 20,$altura,$datosProvision["datos"][$contador]["cobradaNombre"].$cobradaSinAplicarPF);		
		$altura+=10;
		
		if ($datosProvision["datos"][$contador]["cobrada"]=="2")
		{
			$pdf->SetFont('Arial','',12);
			$pdf->Text($margen,$altura,utf8_decode("Fecha de Cobro:"));
			$pdf->SetFont('Arial','B',12);
			$pdf->Text($margen + 35,$altura,$datosProvision["datos"][$contador]["fechaCobro"]->format('d/m/Y'));	
			$altura+=10;

			$pdf->SetFont('Arial','',12);
			$pdf->Text($margen,$altura,utf8_decode("Forma de Pago:"));
			$pdf->SetFont('Arial','B',12);
			$pdf->Text($margen + 35,$altura,$datosProvision["datos"][$contador]["formaPago"]);	
			$altura+=10;
		}
		
		
		$contador++;
	}

	
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

function nuevaPagina(&$pdf)
{
	$pdf->AddPage();
$pdf->StartPageGroup();
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
	
	
	
		
	
	//$pdf->Cell(0,5,utf8_decode('Página ').$pdf->GroupPageNo().'/'.$pdf->PageGroupAlias(),0,0,'C');
	
		
	

}

?>
	
	
	
	
	
	
		


