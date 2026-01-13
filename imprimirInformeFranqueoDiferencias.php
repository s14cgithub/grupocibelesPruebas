<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirFranqueoDirerencias")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	require($ruta."FPDF/fpdf.php");	
	
	
	
	
	
	
	$fechaInicio = $_POST["imprimirInformeFranqueoDirencias_fechaInicio"];	
	$fechaFin = $_POST["imprimirInformeFranqueoDirencias_fechaFin"];
	//$archivos = $_POST["imprimirInformeFranqueoDirencias_fichero"];
	//$archivos = $_FILES["elArchivoComparar"];
	
	
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$datosCorreos = verDatosFacturasCorreosPorFechaYCliente2($conexion,$fechaInicio, $fechaFin); 
	$datosFranqueo = verDatosFranqueoPorFechaYCliente2($conexion,$fechaInicio, $fechaFin); 
	$resultado = array();
	$resultado2 = array();

	//CORREOS
	for ($contador=0;$contador<count($datosCorreos);$contador++)
	{
		array_push($resultado, $datosCorreos[$contador]["codigoCliente"]);	
		array_push($resultado2, $datosCorreos[$contador]["subcliente"]."|".$datosCorreos[$contador]["importe"]);	
	}
	
	//FRANQUEO
	for ($contador=0;$contador<count($datosFranqueo);$contador++)
	{
		if(array_search($datosFranqueo[$contador]["idCliente"],$resultado) !== false)//SI LO ENCUENTA ENTRA
		{
			$seguir = true;
			for ($contador2=0;$contador2<count($resultado) && $seguir;$contador2++)
			{
				if ($resultado[$contador2]==$datosFranqueo[$contador]["idCliente"])
				{
					$resultado[$contador2] = $resultado[$contador2] ."|".$datosFranqueo[$contador]["idCliente"];
					$resultado2[$contador2] = $resultado2[$contador2] ."|".$datosFranqueo[$contador]["subcliente"]."|".$datosFranqueo[$contador]["importe"];
					$seguir = false;
					//echo "<br>".$resultado2[$contador2];
				}
			}
		}
		else //existe idCliente en franqueo pero no en correos
		{
			array_push($resultado, "|".$datosFranqueo[$contador]["idCliente"]);	
			array_push($resultado2, "||".$datosFranqueo[$contador]["subcliente"]."|".$datosFranqueo[$contador]["importe"]);
			//echo "<br>".$resultado2[$contador];
		}		
	}
	
	
	


	
	{
		require($ruta."Archivos Comunes/pagegroup.php");
		require($ruta."Archivos Comunes/rotate.php");
		require($ruta."Archivos Comunes/cabeceraPieFactura.php");
		
		$pdf = new cabeceraFactura('P','mm','A4');

		$pdf->SetLeftMargin(20);
		$pdf->SetRightMargin(20);

		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);	
		$pdf->SetFont('Arial','B',10);



		$margenInicial=20;
		$margen=$margenInicial;


		//$pdf->SetDrawColor(colorAzulR,colorAzulG,colorAzulB);
		//$pdf->SetLineWidth(0.8);

		$altura=15;
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, diaDeLaSemanaActual().date(", d")." de ".mesActual()." del ". date("Y"),0,1,'R',false);
		$altura += 5;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,0, "Informe del ".date("d-m-Y", strtotime($fechaInicio)). " al ".date("d-m-Y", strtotime($fechaFin)),0,1,'R',false);

		
		insertarTitulos($pdf,$altura,$margenInicial);
		
		//sort($resultado);
		for ($contador=0;$contador<count($resultado2);$contador++)
		{
			
			
			if ($altura>260)
			{
				$pdf->AddPage();
				insertarTitulos($pdf,$altura,$margenInicial);
			}
			
			$datosInicial = explode("|",$resultado2[$contador]);
			
			$datos0 = $datosInicial[0];
			$datos1 = $datosInicial[1];
			$datos2="";
			$datos3=0;
			
			if (count($datosInicial)>=4)
			{
				$datos2=$datosInicial[2];
				$datos3=$datosInicial[3];
			}
			
			
			
			
			
			if ( $datos1!=$datos3)
			{
				$altura += 7;
				$margen=$margenInicial;

				$pdf->SetXY($margen,$altura);
				//0->cliente
				//1->importeCibeles
				//2->importeCorreos


				//$pdf->MultiCell(84,5,utf8_decode($datos2[0]),0,'L',false);
				$auxNombre = $datos0;
				if ($auxNombre=="")
				{
					$auxNombre=$datos2;
				}
				$pdf->Cell(84,0,substr(utf8_decode($auxNombre),0,50),0,1,'L',false);
				//$altura += 5;

				$margen = 114;
				$pdf->SetXY($margen,$altura);
				//$pdf->Cell(20,0,number_format(($datos3),2,',','.')." ".EURO,0,1,'R',false);
				$pdf->Cell(20,0,$datos3." ".EURO,0,1,'R',false);


				$margen = 141;
				$pdf->SetXY($margen,$altura);
				//$pdf->Cell(20,0,number_format($datos1,2,',','.')." ".EURO,0,1,'R',false);
				$pdf->Cell(20,0,$datos1." ".EURO,0,1,'R',false);


				$margen = $pdf->GetPageWidth()-20;
				$pdf->SetXY($margen,$altura);
				
				/*if ($datos1<0)
				{	//echo "entra";
					$pdf->Cell(0,0,number_format($datos1+$datos3,2,',','.')." ".EURO,0,1,'R',false);
				}
				else*/
				{
					$pdf->Cell(0,0,number_format($datos1-$datos3,2,',','.')." ".EURO,0,1,'R',false);
				}
				



				/*$anchoDescripcion = $pdf->GetStringWidth(utf8_decode($datos2[0]));
				$numeroDeFilas = ceil ($anchoDescripcion / 84);
				if ($numeroDeFilas<1)
				{
					$numeroDeFilas = 1;
				}	
				$altura = $altura + (5*$numeroDeFilas);*/
			}			
		}		
	}
	
	
	$pdf->Output("I",$ruta."Albaran - ".date("dmy")." .pdf","UTF-8");
	
	
}
else
{
	
}


function insertarTitulos(&$pdf,&$altura,$margenInicial)
{
	//$pdf->AddPage();
	
	
	$margen = $margenInicial;
	
	
	$altura=10;
	$altura += 5;
	$altura += 8;
	$altura += 15;

	$pdf->SetTextColor(colorAzulR,colorAzulG,colorAzulB);
	$pdf->SetFont('Arial','BI',16);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,"FRANQUEO - DIFERENCIAS ENTRE CIBELES Y CORREOS",0,1,'C',false);


	$altura += 10;
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("EMPRESA"),0,1,'L',false);

	$margen = 120;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("CIBELES"),0,1,'L',false);

	$margen = 145;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("CORREOS"),0,1,'L',false);

	$margen = $pdf->GetPageWidth()-20;
	$pdf->SetXY($margen,$altura);
	$pdf->Cell(0,0,utf8_decode("DIREFENCIA"),0,1,'R',false);


	$pdf->SetFont('Arial','',8);
	
		
	

}



?>
	
	
	
	
	
		


