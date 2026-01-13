<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarCarpetas")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$rutaPadre="//172.26.0.44/d/Comercial/Privado/PRESUPUESTOS";
	echo (listar_s_directorio($rutaPadre));
	
	
	/*if (count($subProcesos)<=0)
	{
		echo json_encode("");
		echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($subProcesos);
	}*/
	
	
	
	
	
		
}


//header('content-type: text/plain; charset:utf-8');
function listar_s_directorio($directorio)
{ 
	$resultado ="";
   // validamos que sea un directorio
   if (is_dir($directorio)) 
   { 
	  
	  // abrimos el directorio para poder leerlo
	  if ($dr = opendir($directorio)) 
	  { 
		 // leemos el directorio
		 $contador=0;
		 while (($file = readdir($dr)) !== false) 
		 { 
			$contador++;
			// validamos que los datos devueltos sean un directorio y no archivo
			//if (is_dir($directorio . $file) && $file!="." && $file!="..")
		  	//if (is_dir($file)&& $file!="..")
			if (is_dir($directorio."/".$file)&&$file!=".." && $file!=".")
			{ 
				
			   //de ser un directorio lo imprimimos en pantalla
			   //echo "<br>: $directorio$file"; 
				$resultado .= '<option value="'.$contador.'">'.$file.'</option>';
			} 
		 } 
		 // al finalizar cerramos el directorio
		 closedir($dr); 
	  } 
	  return  $resultado;
   }
   else
   { 
	 return "<br>No es directorio valida"; 
   }
}

?>
