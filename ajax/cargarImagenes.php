<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarImagenes")
{	
	$ot = $_POST["ot"];
	
	$ruta = "../../imagenesAdjuntas/".$ot;
	$ruta2 = "../imagenesAdjuntas/".$ot;
	
	if (file_exists($ruta)) 
	{	
		$carpetas = scandir($ruta,1);			

		$contador =0;
		$limite = count($carpetas);

		//while ($carpetas[$contador]!='.')
		while ($contador<$limite)
		{
			$tipo = "";
			if ($carpetas[$contador] == "arranque")
			{
				$tipo = $carpetas[$contador];
				$imagenes = scandir($ruta."/".$carpetas[$contador],1);
			}
			else  if ($carpetas[$contador] == "calidad")
			{
				$tipo = $carpetas[$contador];
				$imagenes = scandir($ruta."/".$carpetas[$contador],1);
			}
			else if ($carpetas[$contador] == "incidencia")
			{
				$tipo = $carpetas[$contador];
				$imagenes = scandir($ruta."/".$carpetas[$contador],1);
			}

			if ($tipo!="")
			{
				$contador2=0;
				$limiteImagen = count($imagenes);
				while ($contador2<$limiteImagen)
				{
					$pos = strpos($imagenes[$contador2], '.');

					if ($pos > 0)
					{
						echo '<div class="mb-3 pics animation all '.$tipo.'"><a href="'.$ruta2."/".$carpetas[$contador]."/".$imagenes[$contador2].'"  target="_blank"><img class="img-fluid" src="'.$ruta2."/".$carpetas[$contador]."/".$imagenes[$contador2].'"></a> </div>';

					}
					$contador2++;
				}
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