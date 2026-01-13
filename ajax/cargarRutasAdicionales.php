<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarRutasAdicionales")
{
	
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	
	
	$condicion = $_POST["condicion"];
	//$orden = $_POST["orden"];
	
	
	/*if ($condicion!="")
	{
		$condicion = " where idCliente=".$condicion;
	}
	if ($orden !="")
	{
		$condicion = $condicion + " order by " + $orden; 
	}*/
	
	
	$registros = cargarRutasAdicionales($conexion,$condicion);
	//echo (count($registros1));
	if (count($registros)<=0)
	{
		
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		
		echo json_encode($registros);
	}
		
}


?>
