<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarTamanioConversor")
{ 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$id=$_POST["id"];	
	$idTamanioInicio=$_POST["idTamanioInicio"];	
	$idTamanioFinal=$_POST["idTamanioFinal"];	
	$valorTamanioConversor=$_POST["valorTamanioConversor"];	
	
	
	echo modificarTamanioConversor($conexion,$id,$idTamanioInicio,$idTamanioFinal,$valorTamanioConversor);
}

?>
