<?php 

session_start(); 
require("comprobarSesion.php");

if(isset($_POST["imprimirAccion"]) && $_POST["imprimirAccion"]=="imprimirInformeCliente")
//if(isset($_GET["imprimirAccion"])&$_GET["imprimirAccion"]=="imprimirPresu")
{
	$ruta = '/';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	require($ruta."FPDF/fpdf.php");	
	
	$idProducto = $_POST["imprimirInformeClienteProducto"];	
	$fecha = $_POST["imprimirInformeClienteFecha"];
	
	$fecha = date("d-m-Y", strtotime($fecha));

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'idCliente',
		'anadidos',
		'importe',
		'envios',
		'nombreProducto',
		'nombre_franqueo',
		'numAlbaranes1',
		'idProducto',
		'detalle'
	];

	$joins = [
		'tabla3',
		'tabla2'
	];

	$filtros = [
		'producto' => $idProducto,
		'fecha' => $fecha
	];
	$filtrosOperadores = array();

	$order = array(
		'nombre_franqueo' => 'ASC',
		'idCliente' => 'ASC'
	);

	$datosInforme = cargarFranqueo($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);

	//echo json_encode($datosInforme);
	//exit;
	

	$prueba = array();
	$datos=array();
	foreach ($datosInforme["datos"] as $row) 
	{
		$cliente=$row["idCliente"];
		$anadidos=$row["anadidos"];
		$importeTotal=$row["importe"];
		$enviosTotal=$row["envios"];
		$producto=$row["nombreProducto"];
		$nombre=$row["nombre_franqueo"];
		$numAlbaranes = $row["numAlbaranes1"];
		$idProducto = $row["idProducto"];
		
		
		$detalles=explode("|", $row["detalle"]);
		//$datos=array(array());
		
		foreach ($detalles as $valor)  //LOCAL(20): 3|RESTO(20): 3
		{
			//$valor = $valor * 2;
			
			$titulo = substr($valor,0,strrpos($valor, '('));
			$gramos = substr($valor,strrpos($valor, '(')+1,strrpos($valor, ')') - strrpos($valor, '(')-1);
			$envios = substr($valor,strrpos($valor, ' ')+1,strlen($valor) - strrpos($valor, ' ')-1);
			
			//echo '<br>'.$cliente."-".$anadidos."-".$titulo."-".$gramos."-".$envios;
			
			$datos[] =
						array(
							"cliente"=>$cliente,
							"titulo"=>$titulo,
							"gramos"=>$gramos,
							"envios"=>$envios,
							"anadidos"=>$anadidos,
							"importeTotal"=>$importeTotal,
							"enviosTotal"=>$enviosTotal,
							"producto"=>$producto,
							"nombre"=>$nombre,
							"numAlbaranes"=>$numAlbaranes,
							"idProducto"=>$idProducto
						);
			
		
			//echo '<br>';
			
		}
		
		//echo $datos[0]['cliente'];
		//$registros=array();
		/*foreach ($datos as $valor2)  //LOCAL(20): 3|RESTO(20): 3
		{
			echo ('<br>'.$valor2["cliente"]);
		}*/
		
		
		
		
		
		
		
		
	}
	//echo '<br>'.$datos[50]['cliente'];
	//echo '<br>'.count($datos);
	
	
	
	$pdf = new FPDF('P','mm','A4');
	$clienteAntiguo = "asdfjk234234jasdkf";
	$contador=0;
	
	$numEnviosTotal=0;
	$numEnviosAcuseTotal=0;
	$gramajeTotal=0;
	
	$tieneAnadidos=false;
	
	
	$valoresGuardados[]=array();
	$valoresGuardadosAnadidos[]=array();
	//$aaaaaa=array(2,2);
	//$aaaaaa(0,0)=3;
	//echo '<br>'.$aaaaaa(0,0);
	
	
	
	while ($contador<count($datos))
	{
		//$contador++;
		if ($clienteAntiguo!=$datos[$contador]["cliente"])
		{
			$clienteAntiguo = $datos[$contador]["cliente"];
			$titulos=array();
			$titulos2=array();
			$gramos=array();
			
			$tieneAnadidos=false;
			

			$contador2=$contador;
			while ($contador2<count($datos))
			{
				if ($datos[$contador]["cliente"]==$datos[$contador2]["cliente"])
				{
					if (!in_array($datos[$contador2]["titulo"],$titulos) )
					{
						//echo '<br>'.$datos[$contador2]["titulo"];
						
						$campos2 = [
							'orden'
						];
		
						$joins2 = array();
						$filtros2 = array(
							'idProductoPadre' => $datos[$contador2]["idProducto"],
							'titulo' => $datos[$contador2]["titulo"]
						);
						 
						$filtrosOperadores2 = array();
						$order2 = array();

						$ordenTitulo = cargarListadoTarifasProductos($conn, $bbddSql, $campos2, $joins2, $filtros2, $filtrosOperadores2, $order2);
						
						
						$elOrden="";
						if (strlen($ordenTitulo["datos"][0]["orden"])==1)
						{
							$elOrden="0".$ordenTitulo["datos"][0]["orden"];
						}
						else
						{
							$elOrden=$ordenTitulo["datos"][0]["orden"];
						}
						
						array_push($titulos, $datos[$contador2]["titulo"]);
						array_push($titulos2, $elOrden.$datos[$contador2]["titulo"]);
					}


					if (!in_array($datos[$contador2]["gramos"],$gramos))
					{
						array_push($gramos, intval($datos[$contador2]["gramos"]));
					}
					
					

					if ($tieneAnadidos==false and $datos[$contador2]["anadidos"]!="" and $datos[$contador2]["anadidos"]!="null" and $datos[$contador2]["anadidos"]!=null)
					{
						$tieneAnadidos=true;
						//echo "<br>Tiene añadidos";
					}
				
					
					
					
				}

				$contador2++;
			}//while contador2

			sort($gramos);
			sort($titulos2);
			
			//$tieneAnadidos=true;
			//echo $tieneAnadidos;
			
			$contador4=0;
			//echo "<br>".substr($titulos2[0],2);
			while ($contador4<count($titulos2))
			{
				$titulos2[$contador4] = substr($titulos2[$contador4],2);
				$contador4++;
			}
			
			//inicializar el array a ceros
			$contador2=0;//titulos
			$contador3=0;//gramos

			//se ponde los array a cero	
			while($contador3<count($gramos))
			{				
				$contador2=0;
				while ($contador2<count($titulos2))			
				{
					$valoresGuardados[$contador2][$contador3]=0;
					
					if ($tieneAnadidos)
					{
						$valoresGuardadosAnadidos[$contador2][$contador3]=0;
						//echo '<br>'.$valoresGuardadosAnadidos[$contador2][$contador3];
					}
					
					$contador2++;
				}
				$contador3++;
			}
			
			
			
			
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->SetAutoPageBreak(false);
			//$contador=0;	
			$pdf->SetFillColor(22,84,146);
			$pdf->Rect(10,10,$pdf->GetPageWidth()-20,24,"F");


			$pdf->Image('imagenes/Logo Cibeles Mailing Correos Vertical2.jpg',12,10,20);


			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','',10);	
			$altura=280;
			$margen=50;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
			
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('Arial','B',16);	
			$altura=10;
			$margen=32;


			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(0,12,$datos[$contador]["producto"]."\n".$datos[$contador]["nombre"],0,'C',false);

			$altura += 30;
			$margen = 10;
			
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Arial','B',12);
			$pdf->SetXY($margen,$altura);


			$pdf->MultiCell(50,8,"Fecha: ".date("d-m-Y", strtotime($fecha)),0,'L',false);

			$margen = 100;
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(0,8,"Importe Total: ".number_format($datos[$contador]["importeTotal"],2,',','.')." ".EURO,0,'R',false);

			$altura += 5;
			$margen = 10;
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(50,8,utf8_decode("Nº de albaranes: ").$datos[$contador]["numAlbaranes"],0,'L',false);

			$margen = 100;
			$pdf->SetXY($margen,$altura);
			$pdf->MultiCell(0,8,utf8_decode("Nº de envíos: ").$datos[$contador]["enviosTotal"],0,'R',false);

			$margen = 10;
			$altura += 20;
			
			/*$pdf->SetXY($margen,$altura);
			$pdf->Cell(10,5,$altura,0,0,'C',false);*/
			
			$pdf->SetFont('Arial','B',10);
			
			$margen = 10;
			$sumarMargen=18;
			
			
			$pdf->SetTextColor(255,255,255);
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($sumarMargen,5,"",0,0,'C',false);
			$margen += $sumarMargen;
		
			$contador3=0;
			while ($contador3 < count($titulos2))
			{
				$elTitulo= $titulos2[$contador3];
				if (strlen($elTitulo)>7)
				{
					$elTitulo = substr($elTitulo,0,7);
				}
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($sumarMargen,5,$elTitulo,1,0,'C',true);
				$margen += $sumarMargen;
				$contador3++;
			}
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($sumarMargen,5,"Gramos",1,0,'C',true);
			$margen += $sumarMargen;
			
			$contador3=0;
			$margen=10;
			$altura+=5;
			
			while ($contador3 < count($gramos))
			{				
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($sumarMargen,5,$gramos[$contador3],1,0,'C',true);
				$altura+=5;
				$contador3++;
			}
			
			if ($tieneAnadidos)
			{
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($sumarMargen,5,"AVISO",1,0,'C',true);
				$altura+=5;
			}
			
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($sumarMargen,5,"TOTALES",1,0,'C',true);			
			
			$pdf->SetTextColor(0,0,0);
			
		}//if
		
		$pdf->SetTextColor(0,0,0);		
		
		//SE INTRODUCE LOS DATOS			
		
		$margen=10;
		//$margen+=$sumarMargen;
		$altura=65;
		
		
		$seguir=true;
		
		$contador2=0;//titulos
		$contador3=0;//gramos
		
		//SE GUARDA LOS VALORES
		while($contador3<count($gramos) && $seguir)
		{			
			$contador2=0;
			while ($contador2<count($titulos2) && $seguir)			
			{				
				if ($datos[$contador]["titulo"]==$titulos2[$contador2] && $datos[$contador]["gramos"]==$gramos[$contador3])
				{					
					$valoresGuardados[$contador2][$contador3]+=$datos[$contador]["envios"];
					//echo '<br>'.$datos[$contador]["envios"];
					if ($tieneAnadidos && $datos[$contador]["anadidos"]!="")
					{
						//echo '<br>'.$datos[$contador]["envios"];
						$valoresGuardadosAnadidos[$contador2][$contador3]+=$datos[$contador]["envios"];
					}
					
					$seguir=false;
				}
							
				$contador2++;
			}
			
			$contador3++;
		}
		
		
		//SE IMPRIME LOS DATOS		
		
			
				if ($contador+1==count($datos)||$datos[$contador]["cliente"]!=$datos[$contador+1]["cliente"])
				{
					//$numEnviosTotal=0;
					//$numEnviosAcuseTotal=0;

					$gramajeTotalTotal=0;

					$contador2=0;//titulos
					$contador3=0;//gramos
					
					while($contador3<count($gramos))
					{
						$gramajeTotal=0;

						$altura+=5;
						$margen=10;
						$contador2=0;
						$pdf->SetFont('Arial','',10);
						while ($contador2<count($titulos2) )			
						{
							$margen+=$sumarMargen;

							$pdf->SetXY($margen,$altura);
							$pdf->Cell($sumarMargen,5,$valoresGuardados[$contador2][$contador3],1,0,'C',false);							

							$gramajeTotal += ($valoresGuardados[$contador2][$contador3] * $gramos[$contador3]);

							$contador2++;
						}
						$pdf->SetFont('Arial','',10);
						$margen+=$sumarMargen;
						$pdf->SetXY($margen,$altura);
						$pdf->Cell($sumarMargen,5,$gramajeTotal,1,0,'C',false);	
						$gramajeTotalTotal += $gramajeTotal;
						$contador3++;
					}


					$pdf->SetFont('Arial','B',10);
					$altura+=5;
					$margen=10;
					$contador3=0;
					$contador2=0;

					while ($contador2<count($titulos2))				
					{
						$numEnviosTotal=0;
						$numEnviosAcuseTotal=0;

						$contador3=0;
						while($contador3<count($gramos))
						{
							$numEnviosTotal += $valoresGuardados[$contador2][$contador3];
							//echo '<br>'.$numEnviosTotal;
							//echo '<br>'.$datos[$contador]["anadidos"];
							if($tieneAnadidos)
							{								
								$numEnviosAcuseTotal += $valoresGuardadosAnadidos[$contador2][$contador3];
								//echo '<br>'.$numEnviosAcuseTotal;
							}

							$contador3++;
						}
						$margen+=$sumarMargen;
						
						
						if($tieneAnadidos)
						{						
							$pdf->SetXY($margen,$altura);
							$pdf->Cell($sumarMargen,5,$numEnviosAcuseTotal,1,0,'C',false);	
							$altura+=5;
						}

						$pdf->SetXY($margen,$altura);
						$pdf->Cell($sumarMargen,5,$numEnviosTotal,1,0,'C',false);	

						if($tieneAnadidos)
						{
							$altura-=5;
						}

						$contador2++;
					}

					$margen+=$sumarMargen;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell($sumarMargen,5,$gramajeTotalTotal,1,0,'C',false);	






				}
			
		
	
		$contador++;
	
	}
	
	
	/*//se imprimen los datos del ultimo registro
	$numEnviosTotal=0;
	$numEnviosAcuseTotal=0;
	
	$gramajeTotalTotal=0;
	
	$contador2=0;//titulos
	$contador3=0;//gramos
	
	while($contador3<count($gramos))
	{
		$gramajeTotal=0;
		$altura+=5;
		$margen=10;
		$contador2=0;
		while ($contador2<count($titulos2) )			
		{
			$margen+=$sumarMargen;
			$pdf->SetXY($margen,$altura);
			$pdf->Cell($sumarMargen,5,$valoresGuardados[$contador2][$contador3],1,0,'C',false);		
			
			
			//$numEnviosTotal+=$valoresGuardados[$contador2][$contador3];
			$gramajeTotal += ($valoresGuardados[$contador2][$contador3] * $gramos[$contador3]);
			$contador2++;
		}
		
		
		
		$margen+=$sumarMargen;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell($sumarMargen,5,$gramajeTotal,1,0,'C',false);	
		$gramajeTotalTotal += $gramajeTotal;
		$contador3++;
		
		$altura+=5;
				$margen=10;
				$contador3=0;
				$contador2=0;
				
				while ($contador2<count($titulos2))				
				{
					$enviosTotal=0;
					$contador3=0;
					while($contador3<count($gramos))
					{
						$enviosTotal+= $valoresGuardados[$contador2][$contador3];
						$contador3++;
					}
					$margen+=$sumarMargen;
					$pdf->SetXY($margen,$altura);
					$pdf->Cell($sumarMargen,5,$enviosTotal,1,0,'C',false);	
					
					$contador2++;
				}
				
				$margen+=$sumarMargen;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell($sumarMargen,5,$gramajeTotalTotal,1,0,'C',false);	
		
	}*/
	
	
	
	
	
	
	
	$pdf->Output("I",$ruta."Albaran - ".date("dmy")." .pdf","UTF-8");
	sqlsrv_close($conn);
	
	/*if (count($datosInforme)>0)
	{
		$pdf = new FPDF('P','mm','A4');

		$pdf->AddPage();
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(false);
		//$contador=0;	
		$pdf->SetFillColor(22,84,146);
		$pdf->Rect(10,10,$pdf->GetPageWidth()-20,24,"F");


		$pdf->Image('imagenes/Logo Cibeles Mailing Correos Vertical2.jpg',12,10,20);

		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',10);	
		$altura=280;
		$margen=50;
		$pdf->SetXY($margen,$altura);
		$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
		
		

		$pdf->SetTextColor(255,255,255);
		$pdf->SetFont('Arial','B',16);	
		$altura=10;
		$margen=32;
		

		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,12,"RESUMEN DE ALBARANES\n".$datosInforme[0]["producto"],0,'C',false);
		
		$altura += 30;
		$margen = 10;
		
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY($margen,$altura);
		
		
		$pdf->MultiCell(50,12,"Fecha: ".date("d-m-Y", strtotime($fecha)),0,'L',false);
		
		$margen = 100;
		$pdf->SetXY($margen,$altura);
		$pdf->MultiCell(0,12,"Importe Total: ".number_format($datosInforme[0]["total"],2,',','.')." ".EURO,0,'R',false);
		
		
		$margen = 10;
		$altura += 20;
		
		
		$primeraVez=true;
		
		
		
		foreach ($datosInforme as $row) 
		{
			if ($altura>=265)
			{
				$pdf->AddPage();
				$primeraVez=true;
				
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Arial','',10);	
				$altura=280;
				$margen=50;
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(0,10,utf8_decode('Página '.$pdf->PageNo().'/{nb}'),0,0,'R');
				
				$margen = 10;
				$altura=10;
				
			}
			
			
			if ($primeraVez==true)
			{
				$pdf->SetFillColor(189,218,153);
				$pdf->SetXY($margen,$altura);
				$pdf->Cell(60,5,"CLIENTE",0,0,'C',true);		
				$pdf->Cell(40,5,"ALBARANES",0,0,'C',true);		
				$pdf->Cell(40,5,"ENVIOS",0,0,'C',true);		
				$pdf->Cell(50,5,"IMPORTE",0,0,'C',true);

				$pdf->SetFont('Arial','',10);
				$primeraVez = false;
			}
			
			
			$altura +=5;
			$pdf->SetXY($margen,$altura);			
			$pdf->Cell(60,5,$row["cliente"],1,0,'C',false);
			$pdf->Cell(40,5,$row["albaranes"],1,0,'C',false);		
			$pdf->Cell(40,5,$row["envios"],1,0,'C',false);		
			$pdf->Cell(50,5,$row["importe"],1,0,'C',false);
		}
		
		$pdf->Output("I",$ruta."Albaran - ".date("dmy")." .pdf","UTF-8");
	}*/
}
else
{
	
}



?>
	
	
	
	
	
		


