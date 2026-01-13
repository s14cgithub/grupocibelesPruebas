<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verSiTieneFirma")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idCliente = $_POST["idCliente"];
	$horaRuta = $_POST["horaRuta"];
			
	
	if (file_exists("../../imagenesAdjuntas/firmas/".date('Y-m-d')."_".str_replace(":","-",$horaRuta)."_".$idCliente.".jpg")) 
	{
		echo true;
	} 
	else 
	{
		echo false;
	}
	
	
	
}

?>