<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verDatosUnaFactura")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$numeroFactura = $_POST["numeroFactura"];	
	$clayma = $_POST["clayma"]; 
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	
	if ($clayma== "true")
	{
		$resultado = mostrarFacturaPorNumeroClayma($conexion,$numeroFactura,$anioSeleccionado);
	}
	else
	{
		$resultado = mostrarFacturaPorNumero($conexion,$numeroFactura,$anioSeleccionado);
	}
	
		
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("Error: no existe esa factura");
	}	
	else
	{
		echo json_encode($resultado);
	}
	
}


?>