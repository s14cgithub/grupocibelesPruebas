<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarTipoPapel")
{ 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$id=$_POST["id"];	
	$valor=$_POST["valor"];	


	
	
	echo modificarTipoPapel($conexion,$id,$valor);
}

?>
