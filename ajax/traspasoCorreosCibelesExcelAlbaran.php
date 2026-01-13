

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
			
			$error="";
			gestionarDatos($objPHPExcel,$conexion,$error);
			
			
			if ($error=="")
			{
				echo "Importacion Finalizada";
			}
			else 
				echo $error;
			
			/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($archivador);
			
			echo "Nombre:".$archivadorSesion;*/

		}
		
	}	
}


function gestionarDatos($objPHPExcel,$conexion,&$error)
{
	
	$sheet = $objPHPExcel->getSheetByName('Albaranes');
	//$highestRow = $sheet->getHighestRow(); 
	//$highestColumn = $sheet->getHighestColumn();
	
	$objPHPExcel->setActiveSheetIndex(0);
	for ($row = 2, $seguir="true"; $seguir=="true"; $row++)
	{
		
		$numeroOficial =  $sheet->getCell("B".$row)->getValue(); //CODIGO DE ALBARAN
		


		if (trim($numeroOficial==""))
		{
			$seguir=false;
		}
		/*else if ($pos !== false) 
		{

		}*/
		else
		{

			$estado = $sheet->getCell("O".$row)->getValue();

			if (trim($estado) != "Facturado (SAP)" && trim($estado) != "Pdte. Creacion (SIDI)" &&  trim($estado) != "Borrado (SIDI)")
			{
				$producto =  "A";
			
				$deposito = $sheet->getCell("C".$row)->getValue();
				$otSidi = $sheet->getCell("D".$row)->getValue();
				$otCibeles = $sheet->getCell("E".$row)->getValue();
				$fechaCreacion = $sheet->getCell("F".$row)->getValue();
				$fechaAdmision = $sheet->getCell("G".$row)->getValue();
				$nombreClienteSidi = $sheet->getCell("H".$row)->getValue();
				$subClienteSidi = $sheet->getCell("I".$row)->getValue();
				$total = $sheet->getCell("M".$row)->getValue();
				$numeroEnvios = $sheet->getCell("N".$row)->getValue();
				
	
	
				$idClienteEncontrado=false;
				$idCliente = 0; //0 -> no se ha encontrado ningun cliente; -1 -> se ha encontador mas de un cliente.
	
				if (strlen(trim($otSidi))==15)
				{
					$resultado1 = verIdClientePorOtSidi($conexion, trim($otSidi));
	
					if (count($resultado1)==1)
					{
						$idClienteEncontrado = true;
						$idCliente = $resultado1[0]["idCliente"];
					}
					
				}
				
				if (!$idClienteEncontrado)
				{

					if (trim($nombreClienteSidi) == "HELLERMAN TYTON, S.L.-")
					{
						$idCliente = 911;
					}
					else if (trim($nombreClienteSidi) == "SACYR INDUSTRIAL OPERACION Y MANTENIMIENTO, S.L.-")
					{
						$idCliente = 2694;
					}
					else if (trim($nombreClienteSidi) == "PYC PRYCONSA,S.A.-")
					{
						$idCliente = 3086;
					}
					else if (trim($nombreClienteSidi) == "CIBELES MAILING-")
					{
						$idCliente = 14;
					}
					else if (trim($nombreClienteSidi) == "TOTAL ENERGIES MARKETING ESPAÑA, S.A.U.")
					{
						$idCliente = 2741;
					}
					else if (trim($nombreClienteSidi) == "SACYR CONSERVACION S.A.-")
					{
						$idCliente = 2442;
					}
					else if (trim($nombreClienteSidi) == "AMARA S.A.-")
					{
						$idCliente = 26;
					}
					else if (trim($nombreClienteSidi) == "ZEPELIN TELEVISION, S.A.U. (Nº Prov. 50)-")
					{
						$idCliente = 72;
					}
					else if (trim($nombreClienteSidi) == "SAINT-GOBAIN ESPAÑA. S.L.")
					{
						$idCliente = 2850;
					}
					else if (trim($nombreClienteSidi) == "CLAYMA S.A.")
					{
						$idCliente = 554;
					}
					else if (trim($nombreClienteSidi) == "LOOMIS SPAIN S.A.-")
					{
						$idCliente = 453;
					}
					else  
					{
						

						$condicion = "where nombre_empresa like  '".substr($nombreClienteSidi, 0, -4)."%' and activo = 1";
	
						$resultado2 = cargarClientes($conexion,$condicion);
						if (count($resultado2)==1)
						{
							$idClienteEncontrado = true;
							$idCliente = $resultado2[0]["codigo_saldo"];	
						}
						else if (count($resultado2)>1)
						{
							$idCliente = -1;
						}
					}
					
					
					
				}

				
	
				$campana = "";
				$anticipo = "0";
				$aPagar = $total;
				$importeNeto = "0";
				$impuestos = "0";
	
	
				$resultado3 = verSiExisteNumeroOficialFacturaCorreos($conexion, $numeroOficial);
	
				if (count($resultado3)<=0) //SI EXISTE NO SE HACE NADA
				{					
					$resultadoFinal =  grabarFacturaCorreos($conexion, $numeroOficial, $fechaCreacion, $idCliente, $campana, $importeNeto, $impuestos, $total, $anticipo,$aPagar);
				}
				
	
				
	
	
	
				//$direccion = str_replace('\'','',$direccion);
	
				
	
	
				/*if (!is_numeric($precio))
				{
					$precio = $sheet->getCell("K".$row)->getCalculatedValue();
				}*/
				/*
				if (strpos($fecha,'/'))
				{
					$fecha=str_replace('/','-',$fecha);
				}
				else if (strpos($fecha,'-'))
				{
	
				}
				else
				{
					$fecha = \PHPExcel_Style_NumberFormat::toFormattedString($fecha, 'DD-MM-YYYY');
				}
					*/
	
	
							
				//$resultado = guardarAlbaranSalida($conexion,$numAlbaran,$numAlbaran2,$tipoDocumento,$idCliente,$fecha,$contacto,$direccion,$peso,$linea,$precio,$sinSeguimiento,$retiran,$paletMedio,$paletEuropeo,$paletAmericano,$paletBox,$cajasCedidas,$linea2_9,$linea9,$cambioEmbalaje,$creacionCodigos,$seguroMercancia,$etiquetajePedidos,$destino,$palet);
	
			}





			
		
		}
	}
}






?>
