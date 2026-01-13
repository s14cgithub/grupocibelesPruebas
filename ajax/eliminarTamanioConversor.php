<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarTamanioConversor")
{	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$id = $_POST["id"];
	
	
	echo eliminarTamanioConversor($conexion, $id);
			
				
	
	
}


?>