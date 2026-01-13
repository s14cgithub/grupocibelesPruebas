<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarObservacionOT")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numeroPresupuesto=$_POST["numeroPresupuesto"];
	$observacion=$_POST["observacion"];
	
	
	echo modificarObservacionPrespuesto($conexion,$numeroPresupuesto,$observacion);
	
		
}

?>
