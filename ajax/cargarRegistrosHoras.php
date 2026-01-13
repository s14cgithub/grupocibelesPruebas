<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarRegistros")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$condicion=$_POST["condicion"];	
	
	
	$registros = cargarRegistrosHoras($conexion,$condicion);
	
	if (count($registros)<=0)
	{
		echo json_encode("");
	}
	else
	{
		echo json_encode($registros);
	}
		
}

?>