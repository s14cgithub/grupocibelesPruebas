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
	$archivos = $_FILES["elArchivoComparar"];
	
	$datosCorreos=array();
	
	
	
	$return = Array('ok'=>TRUE);	  
	  
	$nombre_archivo = $archivos['name'];

	$tipo_archivo = $archivos['type'];

	$tamano_archivo =$archivos['size'];

	$tmp_archivo = $archivos['tmp_name'];
	
	
	//echo ("nombre: ".$nombre_archivo);
	
	$fp = fopen($tmp_archivo, "r");		
		
	$tituloTXT_codigoCliente="Codigo Cliente";
	$tituloTXT_iva="Impuestos";
	$tituloTXT_neto="Importe";	


	$idClienteUtilizados = array(); 
	

	$primeraVez=true;	
	
	 while (($datos = fgetcsv($fp, 0, "\t")) !== FALSE) 		
	 {
		
		$datos = array_map("utf8_encode", $datos); 

		$numero = count($datos); //indica el numero de campos de la fila
		//echo "\n".$numero;
		

		for ($cont=0; $cont < $numero && $primeraVez; $cont++) 			
		{

			if ($datos[$cont]==$tituloTXT_codigoCliente)
			{
				$posicionTXT_codigoCliente=$cont;						
			}				
			else if ($datos[$cont]==$tituloTXT_neto)
			{
				$posicionTXT_neto=$cont;					
			}
			else if ($datos[$cont]==$tituloTXT_iva)
			{
				$posicionTXT_iva=$cont;					
			}

		}

		if (!$primeraVez)
		{

			$neto = str_replace('.','',$datos[$posicionTXT_neto]);
			$neto = str_replace(',','.',$neto);
			$iva =  str_replace('.','',$datos[$posicionTXT_iva]);
			$iva =  str_replace(',','.',$iva);

			$idCliente = $datos[$posicionTXT_codigoCliente];

			$importeFacturasCorreosEmitidas = verImporteFacturasCorreosPorFechaYcliente($conexion,$fechaInicio, $fechaFin,$idCliente);
			
			
			if(array_search($idCliente, array_column($datosCorreos, 'idCliente')) !== false )
			{
				$seguir = true;
				for ($contador=0;$contador<count($datosCorreos)&&$seguir;$contador++)
				{
					if ($datosCorreos[$contador]["idCliente"]==$idCliente)
					{							
						//$datosCorreos[$contador]["neto"]= $datosCorreos[$contador]["neto"] + $neto  + $importeFacturasCorreosEmitidas[0]["importe"] ;
						$datosCorreos[$contador]["neto"]= $datosCorreos[$contador]["neto"] + $neto ;
						$seguir = false;
						
						if ($idCliente === 1270){
							echo ("<br>Cliente: ".$idCliente." importeNuevo: ".$neto. " total: ".$datosCorreos[$contador]["neto"]);}
						
					}
				}
			}
			else 
			{
				$datosCorreos2=array();
				$datosCorreos2['idCliente'] = $idCliente;
				$datosCorreos2['neto'] = $neto + $importeFacturasCorreosEmitidas[0]["importe"];
				//echo "\n<br>cliente: ".$idCliente." neto: ".$neto." importe: ".$importeFacturasCorreosEmitidas[0]["importe"];
				$datosCorreos[] = $datosCorreos2;
				
				if ($idCliente === 1270)
						echo ("<br>Cliente: ".$idCliente."PrimeraVez; Neto: ".$neto." Correos ".$importeFacturasCorreosEmitidas[0]["importe"]);
				
			}
		}			

		$primeraVez= false; 

	}		
	
	
	/*$contador=-1;
	while ($contador <count($idClienteUtilizados))
	{
		echo "<br>".$idClienteUtilizados[$contador];
		$contador++;
	}*/
	
	
	fclose($fp);

	$resultado = array();

	for ($contador=0;$contador<count($datosCorreos);$contador++)
	{
		$datos = verSumatorioPrecioFranqueoCibeles($conexion, $datosCorreos[$contador]["idCliente"],$fechaInicio, $fechaFin);


		//echo "entra1";
		if (count($datos)>0)
		{
			//echo $datos[0]["importe"];
			if($datos[0]["importe"] != $datosCorreos[$contador]["neto"])
			{ //echo "entra3";
				array_push($resultado, $datos[0]["subcliente"]."|".$datos[0]["importe"]."|".$datosCorreos[$contador]["neto"]);					
			}
		}
	}


	if (count($resultado)<=0)
	{
		echo "Error: No hay nada que mostrar";
	}
	else
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
		
		sort($resultado);
		for ($contador=0;$contador<count($resultado);$contador++)
		{
			if ($altura>260)
			{
				$pdf->AddPage();
				insertarTitulos($pdf,$altura,$margenInicial);
			}
			
			$datos2 = explode("|",$resultado[$contador]);
			
			if ($datos2[1]-$datos2[2]!=0)
			{
				$altura += 7;
				$margen=$margenInicial;

				$pdf->SetXY($margen,$altura);
				//0->cliente
				//1->importeCibeles
				//2->importeCorreos


				//$pdf->MultiCell(84,5,utf8_decode($datos2[0]),0,'L',false);
				$pdf->Cell(84,0,substr(utf8_decode($datos2[0]),0,50),0,1,'L',false);
				//$altura += 5;

				$margen = 114;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(20,0,number_format($datos2[1],2,',','.')." ".EURO,0,1,'R',false);


				$margen = 141;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(20,0,number_format($datos2[2],2,',','.')." ".EURO,0,1,'R',false);


				$margen = $pdf->GetPageWidth()-20;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,0,number_format($datos2[1]-$datos2[2],2,',','.')." ".EURO,0,1,'R',false);



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
	
	
	
	
	
		


