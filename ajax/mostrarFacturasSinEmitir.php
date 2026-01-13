<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarFacturasSinEmitir")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$clayma = $_POST["clayma"];
	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
	}
	
	$condicion = $_POST["condicion"];
	
	

	//guardarBusqueda
	$_SESSION["prefactura_Clayma"] = $clayma;
	$_SESSION["prefactura_queBusca"] = "aaaaaaa";
	$auxiliar = strpos($condicion, 'like');
	if ($auxiliar===false)
	{
		$auxiliar2 = explode(">=",$condicion);
		$_SESSION["prefactura_queBusca"] = trim($auxiliar2[0]);		

		$auxiliar3 = explode('and',$auxiliar2[1]);
		$_SESSION["prefactura_texto"] = trim($auxiliar3[0]);
	}
	else
	{ 
		$auxiliar2 = explode("like",$condicion);
		$_SESSION["prefactura_queBusca"] = trim(substr(trim($auxiliar2[0]), 5));
		
		//echo "entra".$_SESSION["prefactura_queBusca"];

		$auxiliar3 = explode('%',$auxiliar2[1]);
		$_SESSION["prefactura_texto"] = trim($auxiliar3[1]);		
	}

	$auxiliarOrden = explode("order by",$condicion);
	$auxiliar4 = strpos($auxiliarOrden[1], "desc");
	if ($auxiliar4===false)
	{ 
		$_SESSION["prefactura_orden"] = trim($auxiliarOrden[1]);
		$_SESSION["prefactura_Desc"] = "false";		
	}
	else
	{
		$_SESSION["prefactura_orden"] = trim(substr(trim($auxiliarOrden[1]), 0, -4)); 
		$_SESSION["prefactura_Desc"] = "true";
	}
	
	
	
	

	
	
	$resultado = mostrarFacturasSinEmitir($conexion,$clayma1,$condicion);
	
	
	
	if (count($resultado)<=0)
	{
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>