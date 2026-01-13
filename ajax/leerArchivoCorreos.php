


<?php 


if(isset($_POST["accion"])&$_POST["accion"]=="leer")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	if(isset($_FILES['archivo']))
	{
	
	  $return = Array('ok'=>TRUE);	  
	  
	  $nombre_archivo = $_FILES['archivo']['name'];
	  
	  $tipo_archivo = $_FILES['archivo']['type'];
	  
	  $tamano_archivo = $_FILES['archivo']['size'];
	  
	  $tmp_archivo = $_FILES['archivo']['tmp_name'];
		
		//$procesarDuplicado=$_POST["duplicados"];
		$procesarDuplicado="reemplazar";
		
		
		
	 /* echo "\nNombre:".$nombre_archivo."\n";
		echo "\nTipo:".$tipo_archivo."\n";
		echo "\ntamaño:".$tamano_archivo."\n";
	  	echo "\ntmp:".$tmp_archivo."\n";*/
	  
		
		$fp = fopen($tmp_archivo, "r");		
		
		$pos1 = strpos($nombre_archivo, "fac");		
		
		if ($pos1 !== false)//factura
		{	//echo "es una factura";
			$tituloTXT_numeroOficial="Código";
			$tituloTXT_campana="Concepto";
			
			$tituloTXT_formaPago="Forma de Pago (2)";			
			$tituloTXT_anticipo="Anticipo";
			
			/*$tituloTXT_estado="Estado";
			$posicionTXT_estado=null;*/
		}
		else // es abono
		{
			//echo "es un abono";
			$tituloTXT_numeroOficial="Nota Abono";
			$tituloTXT_campana="Código Factura";
			
			$tituloTXT_formaPago="";
			$tituloTXT_anticipo="";
		}
		
		$tituloTXT_fecha="Fecha";		
		//$tituloTXT_cif="CIF";		
		$tituloTXT_codigoCliente="Codigo Cliente";
		$tituloTXT_iva="Impuestos";
		$tituloTXT_neto="Importe";	//iva incluido
		
		
		
		
		$posicionTXT_numeroOficial=null;
		$posicionTXT_fecha=null;
		$posicionTXT_cif=null;
		$posicionTXT_codigoCliente=null;
		$posicionTXT_campana=null;
		
		$posicionTXT_formaPago=null;
		
		$posicionTXT_neto=null;
		$posicionTXT_iva=null;
		$posicionTXT_importe=null;
		$posicionTXT_anticipo=null;
		
	
		
		$fila=1;
		
		$primeraVez=true;
		
		$resultadoFinal="";
		
		 while (($datos = fgetcsv($fp, 0, "\t")) !== FALSE) 	//lee un registros del archivo	
		 {
			$datos = array_map("utf8_encode", $datos); 

			$numero = count($datos); //indica el numero de campos de la fila
			//echo "\n".$numero;
			$fila++;
			
			for ($cont=0; $cont < $numero && $primeraVez; $cont++) 			
			{
				 
				if ($datos[$cont]==$tituloTXT_numeroOficial)
				{
					$posicionTXT_numeroOficial=$cont;					
				}
				else if ($datos[$cont]==$tituloTXT_fecha)
				{
					$posicionTXT_fecha=$cont;					
				}
				/*else if ($datos[$cont]==$tituloTXT_cif)
				{
					$posicionTXT_cif=$cont;					
				}*/
				else if ($datos[$cont]==$tituloTXT_codigoCliente)
				{
					$posicionTXT_codigoCliente=$cont;						
				}
				else if ($datos[$cont]==$tituloTXT_campana)
				{
					$posicionTXT_campana=$cont;					
				}
				else if ($datos[$cont]==$tituloTXT_neto)
				{
					$posicionTXT_neto=$cont;					
				}
				else if ($datos[$cont]==$tituloTXT_iva)
				{
					$posicionTXT_iva=$cont;					
				}
				/*else if ($datos[$cont]==$tituloTXT_importe)
				{
					$posicionTXT_importe=$cont;					
				}*/
				else if ($datos[$cont]==$tituloTXT_anticipo)
				{
					$posicionTXT_anticipo=$cont;					
				}	
				else if ($datos[$cont]==$tituloTXT_formaPago)
				{
					$posicionTXT_formaPago=$cont;					
				}	
				
				
				/*else if ($pos1 !== false &&$datos[$cont]==$tituloTXT_estado )
				{
					$posicionTXT_estado=$cont;	
				}*/
				
				
			}
			
			$seguirGrabacion=true;			 
			 
			if (!$primeraVez)
			{
				
				$resultado = verSiExisteNumeroOficialFacturaCorreos($conexion, $datos[$posicionTXT_numeroOficial]);
					
				
				/*if ($procesarDuplicado=="ignorar" && count($resultado)>0)				
				{					
					$seguirGrabacion=false;					
				}
				else if (count($resultado)>0)
				{
					//echo ("\nentra2\n");
					
					//////////////////////////////////
					$descripcion = log_eliminacion;
					$tabla = facturasCorreos_tabla;
					$columna = "todas";					
					$idRegistro = 0;

					$usuario = $_SESSION['usuario'];
					
					$datosAntiguos = '';
					$datosNuevos = '';			

					insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$datos[$posicionTXT_numeroOficial]);	
					//////////////////////////////////
					
					
					
					$resultado = borrarNumeroOficialFacturaCorreos($conexion, $datos[$posicionTXT_numeroOficial]);
				}	*/	
				
				
				if (count($resultado)>0) //SI EXISTE NO SE HACE NADA
				{					
					$seguirGrabacion=false;
					//echo ("\nentra\n");
				}
			}	
			
			
			
			
			 
			
			 
			 
			if ($seguirGrabacion)
			{
				
				if ($pos1 !== false && !$primeraVez)//factura
				{
					$aPagar =0.00;				

					$neto = str_replace('.','',$datos[$posicionTXT_neto]);
					$neto = str_replace(',','.',$neto);
					$iva =  str_replace('.','',$datos[$posicionTXT_iva]);
					$iva =  str_replace(',','.',$iva);
					
					
					$Importe = $neto;
					$neto = $neto - $iva;
					
					//$Importe = $neto - $iva;

					$anticipo= trim($datos[$posicionTXT_anticipo]);

					$formaPago = $datos[$posicionTXT_formaPago];

					

					
					//if($anticipo!="--") 
					{
						if ($anticipo!="--")
						{

							$seguir =true;
							while ($seguir)//esto es para quitar el simbolo del euro
							{
								$ultimoCaracter = substr($anticipo, -1);
								if (!is_numeric ($ultimoCaracter ))
								{
									$anticipo =substr($anticipo,0, -1);
								}
								else
								{
									$seguir=false;
								}
							}


							$anticipo = substr($anticipo,21);
							$anticipo =  str_replace(',','.',$anticipo);
						}
						else
						{
							$anticipo=0;
						}

						$aPagar = $Importe -$anticipo;

						
						//////////////////////////////////
						$descripcion = log_creacion;
						$tabla = facturasCorreos_tabla;
						$columna = "todas";					
						$idRegistro = 0;

						$usuario = $_SESSION['usuario'];

						$datosAntiguos = '';
						$datosNuevos = "Cliente: ".$datos[$posicionTXT_codigoCliente]."|Neto: ".$neto."|IVA: ".$iva."|Importe: ".$Importe."|Anticipo: ".$anticipo."|aPagar: ".$aPagar;			

						insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$datos[$posicionTXT_numeroOficial]);	
						//////////////////////////////////
						
						
						
						$resultadoFinal =  grabarFacturaCorreos($conexion, $datos[$posicionTXT_numeroOficial], $datos[$posicionTXT_fecha], $datos[$posicionTXT_codigoCliente], $datos[$posicionTXT_campana], $neto, $iva, $Importe, $anticipo,$aPagar);
					}

				}
				else if (!$primeraVez) // es abono
				{
					//echo "\nEntra";
					$aPagar =0.00;				
					$anticipo=0;

					$neto = str_replace('.','',$datos[$posicionTXT_neto]);
					$neto = str_replace(',','.',$neto);
					$iva =  str_replace('.','',$datos[$posicionTXT_iva]);
					$iva =  str_replace(',','.',$iva);
					
					$Importe = $neto;
					$neto = $neto - $iva;
					
					
					
					//$Importe = $neto - $iva;
					$aPagar = $Importe -$anticipo;
					
					$Importe = $Importe * (-1);
					
					$aPagar = $aPagar  * (-1);
					
					$iva = $iva * (-1);
					$neto = $neto * (-1);
					
					

					
					//////////////////////////////////
					$descripcion = log_creacion;
					$tabla = facturasCorreos_tabla;
					$columna = "todas";					
					$idRegistro = 0;

					$usuario = $_SESSION['usuario'];

					$datosAntiguos = '';
					$datosNuevos = "Cliente: ".$datos[$posicionTXT_codigoCliente]."|Neto: ".$neto."|IVA: ".$iva."|Importe: ".$Importe."|Anticipo: ".$anticipo."|aPagar: ".$aPagar;			

					insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$datos[$posicionTXT_numeroOficial]);	
					//////////////////////////////////
					
					
					$resultadoFinal =  grabarFacturaCorreos($conexion, $datos[$posicionTXT_numeroOficial], $datos[$posicionTXT_fecha], $datos[$posicionTXT_codigoCliente], $datos[$posicionTXT_campana], $neto, $iva, $Importe, $anticipo,$aPagar);



				}


				
			}
			
			$primeraVez= false; 
			 
			
			$pos3 = strpos($resultadoFinal, "Error");		
		
			if ($pos3 !== false)
			{
				die("Se ha producido un error.\nRevisar el numeroOficial: ".$datos[$posicionTXT_numeroOficial]."\nProcesar de nuevo el archivo completo");
			}
		 }
		
		
		
		fclose($fp);
		
		echo ("Facturas Guardadas");
	
	}
	
}


?>
