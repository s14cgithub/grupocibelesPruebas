<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarDatosClienteRuta")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	session_start(); 
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idCliente= $_POST["idCliente"];
	$idEmpleado = $_SESSION["idEmpleado"];
	
	
	
	
	
	$clientes=cargarDatosClienteRuta($conexion,$idCliente, $idEmpleado);
	
	if (count($clientes)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($clientes);
	}
		
}

?>
