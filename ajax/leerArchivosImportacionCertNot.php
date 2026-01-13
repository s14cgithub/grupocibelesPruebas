


<?php 


if(isset($_POST["accion"])&$_POST["accion"]=="leer")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	require_once ($ruta.'PHPExcel/Classes/PHPExcel.php');
	
	
	if(isset($_FILES['archivo']))
	{
		
		$return = Array('ok'=>TRUE);
	  
	  
	  
	  $nombre_archivo = $_FILES['archivo']['name'];
	  
	  $tipo_archivo = $_FILES['archivo']['type'];
	  
	  $tamano_archivo = $_FILES['archivo']['size'];
	  
	  $tmp_archivo = $_FILES['archivo']['tmp_name'];
	  
		
		
	  $archivo = "C:/xampp/htdocs/gestionGrupocibelesPreproduccion/archivosDescargas/importacionCertificados/".$_SESSION['usuario']."-Excel.xlsx";
		/*if(is_file($archivo))
			unlink($archivo); */
		
		
	  //$archivador = $rutaCarpeta . '/'.$_SESSION['usuario']."-" . $nombre_archivo;
	  
	  if (!move_uploaded_file($tmp_archivo, $archivo)) 
	  {	  
	  	$return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.", 'status' => 'error');
	  	echo ("Error: Ocurrio un error al subir el archivo. No pudo guardarse.");	  
	  }
	  else
	  {
		  chmod($archivo, 0777);		 
	  }
		
		///////////////////////////////////////////////////////////////////////////////////
	
	  
		/*$return = Array('ok'=>TRUE);	  
	  
	  	$nombre_archivo = $_FILES['archivo']['name'];
	  
	  	$tipo_archivo = $_FILES['archivo']['type'];
	  
	  	$tamano_archivo = $_FILES['archivo']['size'];
	  
	  	$tmp_archivo = $_FILES['archivo']['tmp_name'];*/
		
		$producto=$_POST["producto"];
		$fechaDeposito=$_POST["fechaDeposito"];
		
		//$anioSeleccionado = substr($fechaDeposito, 0,4); 
		
		$fechaSeleccionada = date("d-m-Y", strtotime($fechaDeposito));
		$anioSeleccionado = substr($fechaSeleccionada, -4); 
		
		$anioActual = date('Y');
		//die ($anioSeleccionado);
		if ($anioActual!= $anioSeleccionado)
		{
			die("Error: Solo se puede grabar si la fecha esta dentro de este año");
		}
		
		
		$idProductoPadre=0;
		if ($producto=="certificado")
		{
			$idProductoPadre = 3;
		}
		else if($producto=="notificacion")
		{
			$idProductoPadre = 5;
		}
		
		
	 	/*echo "\nNombre:".$nombre_archivo."\n";
		echo "\nTipo:".$tipo_archivo."\n";
		echo "\ntamaño:".$tamano_archivo."\n";
	  	echo "\ntmp:".$tmp_archivo."\n";*/
		
		$archivo = "C:/xampp/htdocs/gestionGrupocibelesPreproduccion/archivosDescargas/importacionCertificados/".$nombre_archivo;
		
		
		$inputFileType = PHPExcel_IOFactory::identify($archivo);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($archivo);
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		
		
		
		//////////////////////
		
		$rutaCarpeta="../archivosDescargas/importacionCertificados/";
		$rutaCarpetaSession = "archivosDescargas/importacionCertificados/";
		$idEmpleado = $_SESSION["idEmpleado"];
		$nombreArchivoErrores = "errores_".$idEmpleado.".txt";		
		$file = $rutaCarpeta.$nombreArchivoErrores;

		if(is_file($file))
			unlink($file); 
		
		
		
		
		
		//borrarDatosFranqueoImportacionCertificados($conexion,$idEmpleado);
		
		$fp = fopen($file, "w");
		
		
		///////////////////////////////////
		$contadorErrores=0;
		
		for ($row = 2; $row <= $highestRow; $row++)
		{ 
			
				 
			$codigoCliente = $sheet->getCell("A".$row)->getValue();
			$codigoBarras = $sheet->getCell("B".$row)->getValue();
			$nombreCompleto = $sheet->getCell("C".$row)->getValue();
			$direccion = $sheet->getCell("D".$row)->getValue();
			$poblacion = $sheet->getCell("E".$row)->getValue();
			$codigoPostal = $sheet->getCell("F".$row)->getValue();
			$peso = $sheet->getCell("G".$row)->getValue();
			$extranjero = $sheet->getCell("H".$row)->getValue();
			$referencia = $sheet->getCell("I".$row)->getValue();
			$anadidos = $sheet->getCell("J".$row)->getValue();
			$ot = $sheet->getCell("K".$row)->getValue();
			$tipo="";
			$importe="";
			
			$error="";
			
			if (strtoupper(trim($extranjero)) == "SÍ" || strtoupper(trim($extranjero)) == "SI")
			{
				$error.= "Fila: ".$row.". Es extranjero";
			}			 
			if (strlen(trim($codigoCliente)) > 4 || strlen(trim($codigoCliente)) <= 0)
			{
				$error.= "Fila: ".$row.". Error en el codigo de cliente";			
			}
			if (strlen(trim($codigoBarras)) != 23)
			{
				$error.= "Fila: ".$row.". Error en el codigo de Barras";
			}
			if (strlen(trim($codigoPostal)) < 4 || strlen(trim($codigoPostal)) > 5)
			{
				$error.= "Fila: ".$row.". Error en el codigo Postal";
			}
			if (strlen(trim($peso))<=0)
			{
				$error .= "Fila: ".$row.". Error en el peso";
			}
			
			
			if ($error!="")
			{
				fwrite($fp,$error);					
				fwrite($fp,PHP_EOL);
				$contadorErrores++;
			}
			else
			{
				$idTarifaProducto = 0;
				
				if (substr($codigoPostal,0,3)==="280") //local
				{
					if($idProductoPadre==3)//certificado
					{
						$idTarifaProducto = 7;
					}
					else if($idProductoPadre==5)//notificacion
					{
						$idTarifaProducto = 82;
					}					
				}
				else
				{
					$resultado = verSiDestinoEsD1($conexion, $codigoPostal); //d1
					if (count($resultado)>0)
					{						
						if($idProductoPadre==3)//certificado
						{
							$idTarifaProducto = 8;
						}
						else if($idProductoPadre==5)//notificacion
						{
							$idTarifaProducto = 83;
						}
					}
					else //d2
					{						
						if($idProductoPadre==3)//certificado
						{
							$idTarifaProducto = 10;
						}
						else if($idProductoPadre==5)//notificacion
						{
							$idTarifaProducto = 84;
						}						
					}						
				}
				
				$resultado2 = verDatosTarifa ($conexion,$idTarifaProducto, $peso, $fechaSeleccionada);
				
				if (count($resultado2)>0)
				{
					//$codigoCliente
					//$OT
					//$fechaDeposito
					$tipo = $resultado2[0]["tipos"];
					$cantidad = 1;
					$importe = $resultado2[0]["precioNeto"] + $resultado2[0]["iva"];
					$importeSinIva = $resultado2[0]["precioNeto"];
					$referencia = verUltimaReferenciaPorUsuario($conexion,$idEmpleado,$anioSeleccionado);
					//$codigoBarras
					
					
					//idProductoPadre
					$detalle = $resultado2[0]["titulo"]."(".$peso.")";
					
					$importeTotal=$importe;
					
					/////////////////////////////////////////
					if (strtoupper(trim($anadidos))=="ACUSE DE RECIBO")
					{
						$datosFranqueo2 = verImporteProductoFranqueoAcuseRecibo($conexion, $tipo, $cantidad,$anioSeleccionado);
						$seguir = true;
					}
					else if(strtoupper(trim($anadidos))=="PEE")
					{
						$datosFranqueo2 = verImporteProductoFranqueoPEE($conexion, $tipo, $cantidad,$anioSeleccionado);
						$seguir = true;
					}
					if ($seguir==true)
					{
						$importeTotal += $datosFranqueo2[0]["importe"];
					}
					
					
					
					insertarGrabacionFranqueo($conexion,$fechaSeleccionada,$codigoCliente,$ot,'',$importeTotal,$cantidad,$idProductoPadre,$detalle,$idEmpleado,$anadidos,$anioSeleccionado);
					
					$referencia = verUltimaReferenciaPorUsuario($conexion,$idEmpleado,$anioSeleccionado);
					
					
					//////////////////////////////////////////
					$importado = 1;
					
					insertarGrabacionFranqueoTipos($conexion,$codigoCliente, $ot,'', $fechaSeleccionada, $tipo, $cantidad, $importe,$referencia[0]["referencia"], $importeSinIva, $anioSeleccionado, $codigoBarras,$importado, $nombreCompleto, $direccion, $poblacion, $codigoPostal);
					

					if ($seguir==true)
					{
						//echo("hola: ".$datosFranqueo2[0]["tipos"]);
						insertarGrabacionFranqueoTipos($conexion,$codigoCliente, $ot,'', $fechaSeleccionada, $datosFranqueo2[0]["tipos"], $cantidad, $datosFranqueo2[0]["importe"],$referencia[0]["referencia"],$datosFranqueo2[0]["importeSinIva"],$anioSeleccionado, $codigoBarras, $importado, $nombreCompleto, $direccion, $poblacion, $codigoPostal);
					}
					
					
					
					
					/////////////////////////////////////////
					
					
					
					
					
				
					
				}
				else
				{
					$error= "Fila: ".$row.". Problemas con el peso";
					fwrite($fp,$error);					
					fwrite($fp,PHP_EOL);
					$contadorErrores++;
				}
			}
			
			
			
			
		}
		
		
		
		
		if ($contadorErrores>0)
		{
			echo ($rutaCarpetaSession.$nombreArchivoErrores);
		}
		
		echo ("Registros Guardados");
		
		fclose($fp);
		
		
	
	}
	
}


?>
