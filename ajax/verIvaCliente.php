<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="verSiTieneFirma")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idCliente = $_POST["idCliente"];
	$clayma = $_POST["clayma"];
	
	$condicion=" where codigo_saldo=".$idCliente;
	
	if ($clayma=="true")
	{
		$resultado=cargarClientesClayma($conexion,$condicion);
	}
	else
	{
		$resultado=cargarClientes($conexion,$condicion);
	}
	
	
	
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