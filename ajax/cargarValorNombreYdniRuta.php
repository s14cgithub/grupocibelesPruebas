<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarValorNombreYdniRuta")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	session_start(); 
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idCliente= $_POST["idCliente"];
	$horaRuta = $_POST["horaRuta"];
	
	
	$resultado=cargarValorNombreYdniRuta($conexion,$idCliente,$horaRuta);
	
	if (count($resultado)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($resultado);
	}
		
}

?>
