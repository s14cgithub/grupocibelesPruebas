<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarRegistro")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	
	$idCliente= $_POST["idCliente"];
	/*$LR= $_POST["LR"];
	$LH= $_POST["LH"];
	$MR= $_POST["MR"];
	$MH= $_POST["MH"];
	$XR= $_POST["XR"];
	$XH= $_POST["XH"];
	$JR= $_POST["JR"];
	$JH= $_POST["JH"];
	$VR= $_POST["VR"];
	$VH= $_POST["VH"];*/
	$contacto= $_POST["contacto"];
	$incidencia= $_POST["incidencia"];	
	$fecha =  $_POST["fecha"];
	$hora= $_POST["hora"];
	$ruta= $_POST["ruta"];
	
	
	/*if ($LR=="")
	{
		$LR="null";
	}
	if ($MR=="")
	{
		$MR="null";
	}
	if ($XR=="")
	{
		$XR="null";
	}
	if ($JR=="")
	{
		$JR="null";
	}
	if ($VR=="")
	{
		$VR="null";
	}*/
	
	/*if ($LH=="")
	{
		$LH="null";
	}
	if ($MH=="")
	{
		$MH="null";
	}
	if ($XH=="")
	{
		$XH="null";
	}
	if ($JH=="")
	{
		$JH="null";
	}
	if ($VH=="")
	{
		$VH="null";
	}*/
	
	
	
	
	
	
	//echo insertarRutasAdicionales($conexion,$idCliente,$LR,$LH,$MR,$MH,$XR,$XH,$JR,$JH,$VR,$VH,$contacto,$incidencia,$fecha);
	echo insertarRutasAdicionales($conexion,$idCliente,$hora, $ruta,$contacto,$incidencia,$fecha);
	
	
}


?>
