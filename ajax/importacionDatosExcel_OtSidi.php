<?php session_start(); 


if(isset($_POST["accion"])&&$_POST["accion"]=="subir")
{
	//echo 'entra';
	$ruta = $_POST["ruta"];
	
	//$idCliente = $_POST["idCliente"];
	
	$idEmpleado = $_SESSION["idEmpleado"];	
	
	$rutaCarpeta = $ruta."archivosDescargas/importacionOtSidi";
	$rutaCarpetaSession = "archivosDescargas/importacionOtSidi";
	



    $files = glob($rutaCarpeta.'/*'); //se borrar los archivos de hay dentro de la carpeta
    foreach($files as $file){
        if(is_file($file))
            unlink($file); //elimino el fichero
    }



	
	
	//if(isset($_FILES['archivo']))	
	{
		$return = Array('ok'=>TRUE);	  
	  
		$nombre_archivo = $_FILES['archivo']['name'];

		$tipo_archivo = $_FILES['archivo']['type'];

		$tamano_archivo = $_FILES['archivo']['size'];

		$tmp_archivo = $_FILES['archivo']['tmp_name'];

		
		
		
		
		
		$anioActual = date('Y');
		$mesActual = date('m');
		$diaActual = date('d');

		$horaActual = date ('h');
		$minutosActual = date('i');
		$segundosActual = date('s');
		
		$archivador = $rutaCarpeta . '/archivoExcelOtSidi.xls';
		
		
		
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
			gestionarExcel($objPHPExcel,$conexion,$error);
			
			
			if ($error=="")
			{
				echo "Importacion Finalizada";
			}
			else 
				echo $error;
			
			/*$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save($archivador);
			
			*/

		}
		
	}	
}

function extraerNumero($texto) {
    if (preg_match('/\d{7}/', $texto, $coincidencias)) {
        return $coincidencias[0];
    } else {
        return null; // No se encontró un número de 7 dígitos
    }
}

function gestionarExcel($objPHPExcel,$conexion,&$error)
{
	
	$sheet = $objPHPExcel->getSheetByName('Órdenes de Trabajo'); 
	//$highestRow = $sheet->getHighestRow(); 
	//$highestColumn = $sheet->getHighestColumn();
	
	$objPHPExcel->setActiveSheetIndex(0);
	for ($row = 2, $seguir="true"; $seguir=="true"; $row++)
	{

		if (empty(trim($sheet->getCell("E".$row)->getValue()))) 
		{
			$seguir = false;
		}
		else
		{
			$referencia =  $sheet->getCell("E".$row)->getValue();
			$ot = extraerNumero($referencia);
			if ($ot == null)
			{
				//$error = $error."\n".$referencia;
				die ("Error referencia: ".$referencia.". Ot: ".$ot);
			}
			else
			{
				$otSidi = $sheet->getCell("C".$row)->getValue();		
				echo guardarOtSidiDesdeExcel($conexion, $ot, $otSidi);
			}
		}
		
		


		

	}
			
}






?>