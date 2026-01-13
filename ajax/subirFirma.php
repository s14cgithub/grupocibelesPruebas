


<?php session_start(); 
//echo "a1a";
/*echo ("idPlantilla".$_POST["idPlantilla"]);
echo ("idProducto".$_POST["idProducto"]);
echo ("accion".$_POST["accion"]);*/

if(isset($_POST["accion"])&&$_POST["accion"]=="subir")
{
	
	$url = base64_decode($_POST["url"]);
	$idCliente = $_POST["idCliente3"];
	$horaRuta = $_POST["horaRuta3"];
	//$idCliente = 125;
		
	$rutaCarpeta = "../../imagenesAdjuntas/firmas";
	
	//echo $rutaCarpeta;
	
	if (file_exists($rutaCarpeta)) 
	{
	//echo "El fichero $nombre_fichero existe";
		
	} 
	else 
	{
	   // echo "El fichero $nombre_fichero no existe";
		if(!mkdir($rutaCarpeta, 0777, true)) {
			die('Error: Fallo al crear las carpetas...');
		}
		else
		{
			chmod($rutaCarpeta, 0777);
		}
	}
	
	try
	{
		$tmp_archivo = base64_decode($url);


		//$rutaP=$rutaCarpeta."/".date('Ymd_his')."_".$idCliente.".jpg";
		$rutaP=$rutaCarpeta."/".date('Y-m-d')."_".str_replace(":","-",$horaRuta)."_".$idCliente.".jpg";
		
		$file = fopen($rutaP, 'w');
		fwrite($file, $url);
		echo ("Firma Guardada");
	}
	catch (Exception $e)
	{
		echo ("Error: ".$e);
	}
    fclose($file);
	
	
	
	
	
	
}
?>

