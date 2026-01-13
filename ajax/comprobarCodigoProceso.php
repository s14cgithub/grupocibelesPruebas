<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="comprobarCodigoProceso")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$codigoBarras = $_POST["codigo"];
	$codigoBarras_id="";
	$codigoBarras_presupuesto="";
	
	$codigoBarras_longitud = strlen($codigoBarras);
	
	
	$posicion = strpos($codigoBarras,'-');
	
	if ($posicion===false)
	{
		echo (false);
	}
	else
	{
		
		$codigoBarras_id = substr($codigoBarras,0,$posicion);
		$codigoBarras_presupuesto = substr($codigoBarras,$posicion+1,$codigoBarras_longitud-$posicion);
		
		$trabajo=comprobarCodigo($conexion,$codigoBarras_id, $codigoBarras_presupuesto);
		//echo $trabajo[0]["cliente"];
		
		if (count($trabajo)<=0)
		{
			echo (false);
		}
		else
		{			
			echo(true);
		}
	}
}

?>
