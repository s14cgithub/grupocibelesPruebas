<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarTipoProvisionesFondos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$condicion = $_POST["condicion"];
	
	$condicion1 = "";
	if ($condicion == 'limitacionA')
	{
		$condicion1 = " where id!=4 ";
	}
		
	
	$departamento=cargarTiposProvisionesFondos($conexion, $condicion1);
	
	
	if (count($departamento)<=0)
	{
		echo json_encode("");
		echo ("Error2: No hay tipo de provisiones de fondo para mostrar: ");
	}
	else
	{
		echo json_encode($departamento);
	}
		
}

?>
