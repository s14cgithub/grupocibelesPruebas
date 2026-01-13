<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarImagenes")
{	
	//$ot = $_POST["ot"];
	$ot = "almacen-preEntrega";
	/*$ruta = "./../imagenesAdjuntas/".$ot;
	$ruta2 = "./imagenesAdjuntas/".$ot;*/

	//la carpeta "imagenesAdjuntas" esta fuera de  la carpeta GestionGrupocibeles.
	$ruta = "./../../imagenesAdjuntas/".$ot;
	$ruta2 = "./../imagenesAdjuntas/".$ot;
	
	if (file_exists($ruta)) 
	{	
		
		$imagenes = scandir($ruta,1);
		
		
		//$carpetas = scandir($ruta,1);			
		$tipo = "";
		$contador =0;
		$limiteImagen = count($imagenes);
		
		while ($contador<$limiteImagen)
		{			
			$pos = strpos($imagenes[$contador], '.');

			if ($pos > 0 && $imagenes[$contador]!="Thumbs.db")
			{				
				//echo '<div class="mb-3 pics animation all '.$tipo.'"><a href="'.$ruta2."/".$imagenes[$contador].'"  target="_blank"><img class="img-fluid" src="'.$ruta2."/".$imagenes[$contador].'"></a> </div>';
				
				echo '<div class="mb-3 pics animation all '.$tipo.'"><a href="'.$ruta2."/".$imagenes[$contador].'"  target="_blank"><img class="img-fluid" src="'.$ruta2."/".$imagenes[$contador].'"></a> </div>';
			}
			$contador++;
		}
		

		
	}
	else
	{
		echo "No hay imagenes";	
	}
}

?>