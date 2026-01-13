


<?php session_start(); 
//echo "a1a";
/*echo ("idPlantilla".$_POST["idPlantilla"]);
echo ("idProducto".$_POST["idProducto"]);
echo ("accion".$_POST["accion"]);*/

if(isset($_POST["accion"])&&$_POST["accion"]=="subir")
{
	$ruta = $_POST["ruta"];
	$tipo = $_POST["tipo"];
	
	
	$codigoBarras = $_POST["codigoBarras"];
	$aux = explode('-',$codigoBarras);
	$ot = $aux[1];
	$rutaCarpeta = $ruta."imagenesAdjuntas/almacen-preEntrega";
	//echo ("\nPrueba: ".$aux[0]."\n");
	echo $rutaCarpeta;
	
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
	
	/*//$rutaCarpeta = $rutaCarpeta.'/'.$_POST['idProducto'];
	if (file_exists($rutaCarpeta)) 
	{
	//    echo "El fichero $nombre_fichero existe";
		
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
	}*/
	
	
	
	if(isset($_FILES['archivo']))
	{
	
	  $return = Array('ok'=>TRUE);
	  
	  
	  
	  $nombre_archivo = $_FILES['archivo']['name'];
	  
	  $tipo_archivo = $_FILES['archivo']['type'];
	  
	  $tamano_archivo = $_FILES['archivo']['size'];
	  
	  $tmp_archivo = $_FILES['archivo']['tmp_name'];
	  
	  $archivador = $rutaCarpeta . '/'.$nombre_archivo;
	  //////////////////////////////////////////////////////
		/*$equipo = $_FILES['archivo'];

		foreach($equipo as $posicion=>$jugador)
			{
			echo "\ntipo: " . $posicion . " es " . $jugador."\n";
			
			}*/
		
	//////////////////////////////////////////////////////	
		
		
		
	  /*echo "\nNombre:".$nombre_archivo."\n";
		echo "\nTipo:".$tipo_archivo."\n";
		echo "\ntamaño:".$tamano_archivo."\n";
	  echo "\ntmp:".$tmp_archivo."\n";
	  echo "\narchivador".$archivador."\n";*/
	  
	  if (!move_uploaded_file($tmp_archivo, $archivador)) {
	  
	  $return = Array('ok' => FALSE, 'msg' => "Ocurrio un error al subir el archivo. No pudo guardarse.", 'status' => 'error');
	  echo ("Error: Ocurrio un error al subir el archivo. No pudo guardarse.");
	  
	  }
	  else
	  {
		  chmod($archivador, 0777);
		  //chmod("archivos/14aaa/14-1",0777);
		  //echo json_encode($nombre_archivo);
	  }
	
	}
	
}
?>
