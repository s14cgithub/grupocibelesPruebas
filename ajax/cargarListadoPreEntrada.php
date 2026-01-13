<?php 
	

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoPreEntrada")
{	

	$ot = "almacen-preEntrega";
	$ruta = "./../../imagenesAdjuntas/".$ot;
	$ruta2 = "./../imagenesAdjuntas/".$ot;
	
	if (file_exists($ruta)) 
	{
		
		
		echo "<tr class=centrarTexto tablaCabeceraColor>";
		echo "<th>Imagen</th>";
		echo "<th></th>";
		
		
		echo "</tr>";
		
		
		$imagenes = scandir($ruta,1);
		
		
		//$carpetas = scandir($ruta,1);			
		$tipo = "";
		$contador =0;
		$limiteImagen = count($imagenes);
		
		while ($contador<$limiteImagen)
		{
			$pos = strpos($imagenes[$contador], '.');

			if ($pos > 0 && $imagenes[$contador]!='Thumbs.db')
			{				
				echo '<tr><td><img src="'.$ruta2."/".$imagenes[$contador].'" class="img-fluid"></img></td><td><input type="image" id="'.$imagenes[$contador].'_eliminarRegistroF" value="" src="imagenes/eliminar.png" style="width:20px;"  onclick="eliminarFotoAlmacenPreEntrada(\''.$imagenes[$contador].'\')"></td></tr>';
				
				//<img src="'.$ruta2."/".$imagenes[$contador].'"></img>
				//<input type="checkbox" id="'+$imagenes[$contador]+'_imagen  value="" '+$imagenes[$contador]+'></input></td>
			}
			$contador++;
		}
	}
	else
	{
		//echo "No hay imagenes";	
	}
}	


?>
