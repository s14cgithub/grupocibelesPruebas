


<?php session_start(); 
//echo "a1a";
/*echo ("idPlantilla".$_POST["idPlantilla"]);
echo ("idProducto".$_POST["idProducto"]);
echo ("accion".$_POST["accion"]);*/

if(isset($_POST["accion"]) && $_POST["accion"]=="subir")
{
	$ruta = $_POST["ruta"];
	$tipo = $_POST["tipo"];
	
	$idEmpleado = $_SESSION["idEmpleado"];	

	if (strlen($idEmpleado)==1)
	{
		$idEmpleado = "0".$idEmpleado;
	}
	
	$rutaCarpeta = $ruta."archivosDescargas/";
	$rutaCarpetaSession = "archivosDescargas/importacionIndra";
	
	if ($tipo=="excelIndra")
	{
		$rutaCarpeta = $rutaCarpeta."importacionIndra";
	}
	
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
		
		$archivador = $rutaCarpeta . '/indraFranqueo_'. $anioActual.$mesActual.$diaActual."_".$horaActual.$minutosActual.$segundosActual.".xlsx";
		$archivadorSesion = $rutaCarpetaSession . '/indraFranqueo_'. $anioActual.$mesActual.$diaActual."_".$horaActual.$minutosActual.$segundosActual.".xlsx";
		
		
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
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			
			$objPHPExcel->setActiveSheetIndex(0);
			
			$conn1 = conectarSQL($conexion);
			$conn = $conn1['conn'];
			$bbddSql = $conn1['bbdd'];
			
			for ($row = 5, $seguir2="true"; $seguir2=="true"; $row++)
			{
				//echo "-entra4-";
				$sedeOrigen = $sheet->getCell("A".$row)->getValue();
				$fechaEntrega = $sheet->getCell("B".$row)->getValue();
				$numOrden = $sheet->getCell("C".$row)->getValue();
				$remitente = $sheet->getCell("D".$row)->getValue();
				
				if ($sedeOrigen=="" && $fechaEntrega=="" && $numOrden=="" && $remitente=="")
				{
					//echo "-entra2-";
					$seguir2 = "false";
				}
				else
				{
					//echo "-entra3-";
					
					$tipoServicio = $sheet->getCell("H".$row)->getValue();
					$acuseRecibo = $sheet->getCell("J".$row)->getValue();
					
					//$fechaActual = ;
					$codigoBarras = $sheet->getCell("U".$row)->getValue();
					$destino = $sheet->getCell("V".$row)->getValue();
					$gramos = $sheet->getCell("W".$row)->getValue();
					$codigoCliente = $sheet->getCell("AA".$row)->getCalculatedValue();
					
					
					$error="";
					
					$cantidad = 1;
					
					
					
					
					///////VER TIPO//////
					$idProductoPadre=0;
					if (strtoupper($tipoServicio)=="CERTIFICADO")
					{
						//$error="AAA";
						$idProductoPadre=3;
					}
					else if (strtoupper($tipoServicio)=="ORDINARIO")
					{
						//$error="BBB";
						$idProductoPadre=1;
					}
					else
					{
						//$error="CCC";
						$error="Error en la columna 'Tipo Servicio' en la fila ".$row;
					}
					
					if ($error=="")
					{
						$idTarifaProducto = 0;
					
						if($idProductoPadre==3)//certificado
						{
							if ($destino=="Local")							
								$idTarifaProducto = 7;	
							else if ($destino=="Destino1")							
								$idTarifaProducto = 8;
							else if ($destino=="Destino2")							
								$idTarifaProducto = 10;
							else if ($destino=="Z1")
							{
								$idProductoPadre = 17;
								$idTarifaProducto = 11;
							}
							else if ($destino=="Z2")
							{
								$idProductoPadre = 17;
								$idTarifaProducto = 12;
							}
							else if ($destino=="Z3")
							{
								$idProductoPadre = 17;
								$idTarifaProducto = 13;
							}
							else
							{
								$error="Error al buscar la tarifa. Parte 1.";
							}								
						}
						else if ($idProductoPadre==1)
						{
							if ($destino=="Local")							
								$idTarifaProducto = 2;
							else if ($destino=="Destino1")							
								$idTarifaProducto = 1;
							else if ($destino=="Destino2")							
								$idTarifaProducto = 3;
							else if ($destino=="Z1")
							{
								$idProductoPadre = 16;
								$idTarifaProducto = 4;
							}
							else if ($destino=="Z2")
							{
								$idProductoPadre = 16;
								$idTarifaProducto = 5;
							}
							else if ($destino=="Z3")
							{
								$idProductoPadre = 16;
								$idTarifaProducto = 6;
							}
							else
							{
								$error="Error al buscar la tarifa. Parte 2.";
							}	
							
						}
						else
						{
							$error="Error al gestionar el id-producto-tarifa";
						}
						
						if ($error=="")
						{
							$campos = [
								'tipos',
								'precioNeto',
								'iva',
								'titulo'
							];
						
							$joins = [
								'tabla2'
							];

							$filtros = [
								'idTarifasProducto' => $idTarifaProducto,
								'gramos' => $gramos
							];
							$filtrosOperadores = array();
							$order = array();

							//where t1.idTarifasProducto = ".$idTarifaProducto." and t1.gramos=" .$peso;


							$resultado2 = cargarTarifasFranqueo($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order,$anioActual,$cantidad);
							
							if (count($resultado2["datos"])>0)
							{
								$tipo = $resultado2["datos"][0]["tipos"];
								
								$importe = $resultado2["datos"][0]["precioNeto"] + $resultado2["datos"][0]["iva"];
								$importeSinIva = $resultado2["datos"][0]["precioNeto"];
								//$referencia = verUltimaReferenciaPorUsuario($conexion,$idEmpleado,$anioActual);
								//$codigoBarras


								//idProductoPadre
								$detalle = $resultado2["datos"][0]["titulo"]."(".$gramos."): ".$cantidad;

								$importeTotal = $importe;
								$importeTotalSinIva = $importeSinIva;

								/////////////////////////////////////////
								$seguir=false;

								$datos2 = [
									'importe_cantidadIndicada',
									'tipos',
									'importeSinIva_cantidadIndicada'				
								];
								$joins2 = array();
								
								$filtrosOperadores2 = array();
								$order2 = array();	

								if (strtoupper(trim($acuseRecibo))=="ACUSE DE RECIBO")
								{
									$filtros2 = [
										'tipos_acuseRecibo' => $tipo		
									];
									$seguir = true;
								}
								else if(strtoupper(trim($acuseRecibo))=="PEE")
								{
									$filtros2 = [
										'tipos_PEE' => $tipo		
									];

									$seguir = true;
								}
								if ($seguir==true)
								{
									$datosFranqueo2 = cargarTarifasFranqueo($conn,$bbddSql, $datos2, $joins2, $filtros2, $filtrosOperadores2, $order2,$anioActual,$cantidad);

									//echo json_encode($datosFranqueo2);
									//exit; 

									$importeTotal += $datosFranqueo2["datos"][0]["importe"];
									$importeTotalSinIva += $datosFranqueo2["datos"][0]["importeSinIva"];
								}


								$anadidos = $acuseRecibo;
								$ot="";
								$nombreCompleto="";
								$direccion="";
								$poblacion="";
								$codigoPostal="";
								
								
								$datos3["fecha"] = $fechaActual;
								$datos3["idCliente"] = $codigoCliente;
								$datos3["ot"] = $ot;
								$datos3["otSidi"] = '';
								$datos3["importe"] = $importeTotal;
								$datos3["envios"] = $cantidad;
								$datos3["producto"] = $idProductoPadre;
								$datos3["detalle"] = $detalle;
								$datos3["idEmpleado"] = $idEmpleado;
								$datos3["anadidos"] = $anadidos;


								insertarGrabacionFranqueo($conn, $bbddSql, $datos3);


								$datos4 = [
									'referencia'
								];

								$joins4 = array();

								$filtros4 = [
									'verUltimaReferenciaPorUsuario' => $idEmpleado	
								];

								$filtrosOperadores4 = array();
								$order4 = array();
								$referencia = cargarFranqueo($conn, $bbddSql, $datos4, $joins4, $filtros4, $filtrosOperadores4, $order4);
		


								//////////////////////////////////////////
								$importado = 2;//VALOR UNICO PARA ESTE PROCESO DE INDRA
								
								$datos5 =  [
									'idCliente' => $codigoCliente,
									'ot' =>  $ot,
									'otSidi' => '',
									'fecha' => $fechaActual,
									'tipo' => $tipo,
									'unidades' =>  $cantidad,
									'importe' => $importe,
									'referencia' => $referencia["datos"][0]["referencia"],
									'importeSinIva' => $importeSinIva,
									'numSeguimiento' => $codigoBarras,
									'importado' => $importado,
									'txt' => 0,
									'comprobado' => 0,
									'nombre' => $nombreCompleto,
									'direccion' => $direccion,
									'poblacion' => $poblacion,
									'cp' => $codigoPostal				
								];

								insertarGrabacionFranqueoTipos($conn, $bbddSql, $datos5);
								

								if ($seguir==true)
								{

									$datos6 =  [
										'idCliente' => $codigoCliente,
										'ot' =>  $ot,
										'otSidi' => '',
										'fecha' => $fechaActual,
										'tipo' => $datosFranqueo2["datos"][0]["tipos"],
										'unidades' =>  $cantidad,
										'importe' => $datosFranqueo2["datos"][0]["importe"],
										'referencia' => $referencia["datos"][0]["referencia"],
										'importeSinIva' => $datosFranqueo2["datos"][0]["importeSinIva"],
										'numSeguimiento' => $codigoBarras,
										'importado' => $importado,
										'txt' => 0,
										'comprobado' => 0,
										'nombre' => $nombreCompleto,
										'direccion' => $direccion,
										'poblacion' => $poblacion,
										'cp' => $codigoPostal				
									];

									insertarGrabacionFranqueoTipos($conn, $bbddSql, $datos6);									
									
								}
								
								//echo "entra";
								$objPHPExcel->getActiveSheet()->SetCellValue('X'.$row, $importeTotalSinIva);
								$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$row, $importeTotalSinIva);

							}
							else
							{
								$error = "Error: problemas con el peso";
							}
							
						}
						else
						{
						}
					}
					
				}
				if ($error!="")
				{
					$objPHPExcel->getActiveSheet()->SetCellValue('AB'.$row, $error);
				}
				
			}//fin del for
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($archivador);
			
			echo "Nombre:".$archivadorSesion;

			sqlsrv_close($conn);

		}
		
	}	
}
?>
