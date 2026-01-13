<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="versiHayConversor")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$presupuesto = $_POST["presupuesto"];	
	
	
	
	$resultado = mostrarValorConversorTamanio($conexion,$presupuesto);
	
	
		
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo ("no existe el presupuesto");
	}	
	else
	{
		echo  json_encode($resultado);
	}
	
}


?>