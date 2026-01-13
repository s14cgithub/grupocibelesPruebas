<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarObservacionAbono")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numeroAbono=$_POST["numeroAbono"];
	$observacion=$_POST["observacion"];
	$observacionInterna=$_POST["observacionInterna"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	
	$clayma = $_POST["clayma"];
	
	if ($clayma == "true")
	{
		echo modificarObservacionAbonoClayma($conexion,$numeroAbono,$observacion,$observacionInterna,$anioSeleccionado);
	}
	else
	{
		echo modificarObservacionAbono($conexion,$numeroAbono,$observacion,$observacionInterna,$anioSeleccionado);
	}
	
	
	
		
}

?>
