<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoNombreFranqueo")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=$_POST["condicion"];
	
	if ($condicion =='')
	{
		$condicion = ' where activo = 1 order by nombre_franqueo';
	}
	
	//isset ($_POST["condicion"]) ? $_POST["condicion"] : ' where activo = 1 order by nombre_franqueo';
	
	
	
	$comerciales=cargarListadoNombreFranqueo($conexion,$condicion);
	
	if (count($comerciales)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($comerciales);
	}
		
}

?>
