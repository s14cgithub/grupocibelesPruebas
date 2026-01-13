


<?php 


if(isset($_POST["accion"])&$_POST["accion"]=="leer")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	
	$datosCorreos=array();
	
	if(isset($_FILES['archivo']))
	{
	
		$return = Array('ok'=>TRUE);	  
	  
		$nombre_archivo = $_FILES['archivo']['name'];

		$tipo_archivo = $_FILES['archivo']['type'];

		$tamano_archivo = $_FILES['archivo']['size'];

		$tmp_archivo = $_FILES['archivo']['tmp_name'];
		
	 
	  
		
		$fp = fopen($tmp_archivo, "r");		
		
		//$pos1 = strpos($nombre_archivo, "fac");		
		
		
		
		
		$tituloTXT_codigoCliente="Codigo Cliente";
		$tituloTXT_iva="Impuestos";
		$tituloTXT_neto="Importe";	
		
		
		
		$fila=1;
		
		$primeraVez=true;
		
		$resultadoFinal="";
		
		 while (($datos = fgetcsv($fp, 0, "\t")) !== FALSE) 		
		 {
			$datos = array_map("utf8_encode", $datos); 

			$numero = count($datos); //indica el numero de campos de la fila
			//echo "\n".$numero;
			$fila++;
			
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
				
				
				if(array_search($idCliente, array_column($datosCorreos, 'idCliente')) !== false) 
				{
					$seguir = true;
					for ($contador=0;$contador<count($datosCorreos)&&$seguir;$contador++)
					{
						if ($datosCorreos[$contador]["idCliente"]==$idCliente)
						{							
							$datosCorreos[$contador]["neto"]=$datosCorreos[$contador]["neto"] + $neto;
							$seguir = false;
						}
					}
				}
				else 
				{
					$datosCorreos2=array();
					$datosCorreos2['idCliente'] = $idCliente;
					$datosCorreos2['neto'] = $neto;

					$datosCorreos[] = $datosCorreos2;
				}
			}			
			
			$primeraVez= false; 
			 
			
			
		}		
				
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
					array_push($resultado, $datos[0]["cliente"]."|".$datos[0]["importe"]."|".$datosCorreos[$contador]["neto"]);					
				}
			}
		}
		
		
		if (count($resultado)<=0)
		{
			echo "Error: No hay nada que mostrar";
		}
		else
		{
			echo json_encode($resultado);
		}
		
		
		
		
	
	}
	
}


?>
