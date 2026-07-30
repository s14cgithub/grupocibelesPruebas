

<?php session_start(); 


if(isset($_POST["accion"]) && $_POST["accion"]=="subir")
{
	//echo 'entra';
	$ruta = $_POST["ruta"];
	
	//$idCliente = $_POST["idCliente"];
	
	$idEmpleado = $_SESSION["idEmpleado"];	
	
	$rutaCarpeta = $ruta."archivosDescargas/importacionFacturasCorreos";
	$rutaCarpetaSession = "archivosDescargas/importacionFacturasCorreos";
	
	
	
	//if(isset($_FILES['archivo']))	
	{
		$return = Array('ok'=>TRUE);	  
	  
		$nombre_archivo = $_FILES['archivo']['name'];

		$tipo_archivo = $_FILES['archivo']['type'];

		$tamano_archivo = $_FILES['archivo']['size'];

		$tmp_archivo = $_FILES['archivo']['tmp_name'];

		$archivador = $rutaCarpeta . '/'.$_SESSION['usuario']."-" . $nombre_archivo;
		$archivadorSesion = $rutaCarpetaSession . '/'.$_SESSION['usuario']."-" . $nombre_archivo;
		
		
		
		$anioActual = date('Y');
		$mesActual = date('m');
		$diaActual = date('d');

		$horaActual = date ('h');
		$minutosActual = date('i');
		$segundosActual = date('s');
		
		//$archivador = $rutaCarpeta . '/albaran'.'_'. $anioActual.$mesActual.$diaActual."_".$horaActual.$minutosActual.$segundosActual.".xls";
		//$archivadorSesion = $rutaCarpetaSession . '/albaran'.'_'. $anioActual.$mesActual.$diaActual."_".$horaActual.$minutosActual.$segundosActual.".xls";
		
		
		if (file_exists($rutaCarpeta)) 
		{
		//echo "El fichero $nombre_fichero existe";

		} 
		else 
		{
		   // echo "El fichero $nombre_fichero no existe";
			if(!mkdir($rutaCarpeta, 0777, true)) {
				die('Fallo al crear las carpetas...');
			}
			else
			{
				chmod($rutaCarpeta, 0777);
			}
		}
		
		if(is_file($archivador))
			unlink($archivador); 
		
		if (!move_uploaded_file($tmp_archivo, $archivador)) 
		{	  
			$return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.", 'status' => 'error');
			echo ("Error: Ocurrio un error al subir el archivo. No pudo guardarse.");
		}
		else
		{
			chmod($archivador, 0777);
			//echo $archivador."entra";
			//chmod("archivos/14aaa/14-1",0777);
			//echo json_encode($nombre_archivo);
			
			
			$ruta = '../';	
			require($ruta."Archivos Comunes/constantes.php");
			require($ruta."Archivos Comunes/codigoInclude.php");
			require_once ($ruta.'PHPExcel/Classes/PHPExcel.php');
			
			//$fp = fopen($archivador, "w");
			
			$fechaActual = date('d-m-Y'); 
			$anioActual = date('Y'); 
			
			
			
			$inputFileType = PHPExcel_IOFactory::identify($archivador);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($archivador);
			//$sheet = $objPHPExcel->getSheet(0); 
			
			$conn1 = conectarSQL($conexion);
			$conn = $conn1['conn'];
			$bbddSql = $conn1['bbdd'];

			$error="";
			$resultadoGestion = gestionarDatos($objPHPExcel,$conn,$bbddSql,$error);

			$rutaTxtSesion = "";

			if (!empty($resultadoGestion['duplicados']) || !empty($resultadoGestion['noEncontrados']))
			{
				$nombreArchivoTxt = "codigosSidi_errores.txt";
				$archivadorTxt = $rutaCarpeta.'/'.$nombreArchivoTxt;
				$rutaTxtSesion = $rutaCarpetaSession.'/'.$nombreArchivoTxt;

				$fp = fopen($archivadorTxt, "w");

				foreach ($resultadoGestion['duplicados'] as $codigo)
				{
					fwrite($fp, "Duplicado: ".$codigo);
					fwrite($fp, PHP_EOL);
				}

				foreach ($resultadoGestion['noEncontrados'] as $codigo)
				{
					fwrite($fp, "No encontrado: ".$codigo);
					fwrite($fp, PHP_EOL);
				}

				fclose($fp);
			}
			
			
			if ($error=="")
			{
				echo "Importacion Finalizada";
			}
			else 
				echo $error;

			if ($rutaTxtSesion != "")
			{
				echo "\nNombre:".$rutaTxtSesion;
			}
			
			/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($archivador);
			
			echo "Nombre:".$archivadorSesion;*/

		}
		
	}	
}


function gestionarDatos($objPHPExcel,$conn,$bbddSql,&$error)
{
	$codigoSidi_Duplicados = array();
	$codigoSidi_NoEncontrados = array();

	$sheet = $objPHPExcel->getSheetByName('Bills');
	//$highestRow = $sheet->getHighestRow(); 
	//$highestColumn = $sheet->getHighestColumn();
	
	$objPHPExcel->setActiveSheetIndex(0);
	for ($row = 2, $seguir="true"; $seguir=="true"; $row++)
	{
		
		$numeroOficial =  $sheet->getCell("A".$row)->getValue();
		


		if (trim($numeroOficial==""))
		{
			$seguir=false;
		}
		/*else if ($pos !== false) 
		{

		}*/
		else
		{  //echo "\nentraa\n";
			$tipoFactura =  $sheet->getCell("B".$row)->getValue();
			$otSidi =  $sheet->getCell("C".$row)->getValue();
			$otCibeles = $sheet->getCell("D".$row)->getValue();
			$fechaFactura = $sheet->getCell("E".$row)->getValue();
			$agente = $sheet->getCell("F".$row)->getValue();
			$nombreClienteSidi = $sheet->getCell("G".$row)->getValue();
			$codigoClienteSidi = $sheet->getCell("H".$row)->getValue();
			$importeNeto = $sheet->getCell("K".$row)->getValue();
			$impuestos = $sheet->getCell("L".$row)->getValue();
			$total = $sheet->getCell("M".$row)->getValue();
			$estado = $sheet->getCell("N".$row)->getValue();
			$notaAbono = $sheet->getCell("O".$row)->getValue();

			


			
			


			if (strlen(trim($codigoClienteSidi))==10)
			{
				$resultado1 = cargarClientes($conn, $bbddSql, ['codigo_saldo'], ['codigoSidi' => $codigoClienteSidi], [], []);

				if (count($resultado1['datos'])==1)
				{					
					$idCliente = $resultado1['datos'][0]["codigo_saldo"];	
					$campana = $otCibeles;

					$anticipo = "0";
					$aPagar = $total;

					$resultado3 = mostrarFacturacionCorreos($conn, $bbddSql, ['numeroOficial'], [], ['numeroOficial' => $numeroOficial], [], []);
					if (count($resultado3['datos'])<=0) //Si no existe se guarda el registro de la factura
					{									
						$resultadoFinal =  insertarFacturacionCorreos($conn, $bbddSql, ['numeroOficial' => $numeroOficial, 'fecha' => $fechaFactura, 'codigoCliente' => $idCliente, 'campana' => $campana, 'neto' => $importeNeto, 'iva' => $impuestos, 'importe' => $total, 'anticipo' => $anticipo, 'aPagar' => $aPagar]);
					}
					
					if(strlen($notaAbono)>5) //se guarda el abono
					{						
						$resultado4 = mostrarFacturacionCorreos($conn, $bbddSql, ['numeroOficial'], [], ['numeroOficial' => $notaAbono], [], []);
						if (count($resultado4['datos'])<=0)
						{
							$numeroOficial = $notaAbono;

							$campana = "";				
			
							$importeNeto =  $importeNeto * -1;
							$impuestos = $impuestos * -1;
							$total =$total * -1;
							$anticipo = $anticipo * -1;
							$aPagar = $aPagar * -1;
			
			
							$resultadoFinal =  insertarFacturacionCorreos($conn, $bbddSql, ['numeroOficial' => $numeroOficial, 'fecha' => $fechaFactura, 'codigoCliente' => $idCliente, 'campana' => $campana, 'neto' => $importeNeto, 'iva' => $impuestos, 'importe' => $total, 'anticipo' => $anticipo, 'aPagar' => $aPagar]);
						}
					}
				}
				else if (count($resultado1['datos'])>1)				
				{
					$codigoSidi_Duplicados[] = $codigoClienteSidi;
				}
				else
				{
					$codigoSidi_NoEncontrados[] = $codigoClienteSidi;
				}
			}
			else
			{
				$codigoSidi_NoEncontrados[] = $codigoClienteSidi;
			}	
		
		}
	}

	return array('duplicados' => $codigoSidi_Duplicados, 'noEncontrados' => $codigoSidi_NoEncontrados);
}






?>
