<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoParaVerPresupuesto")
{	
	$numPresupuesto = $_POST["numPresupuesto"];
	
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	
	
	$Directory = rutaPresupuestos;
	
	if(is_dir($Directory))
	{
	
	
	
		$Resource = opendir($Directory);
		$encontrados = array();

		$seguir = true;

		while(false !== ($Item = readdir($Resource)))
		{
			if($Item != "." && $Item != "..")
			{
				$pos = strpos($Item, $numPresupuesto);
				if ($pos !== false) 
				{				
					$Found[] = $Directory . $Item;
				}
			}
		}


		echo json_encode($Found); 
	}
	else 
		echo ("no entra");
	
	
}

?>