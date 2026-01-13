<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarObservacionesClientes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";
	
	
	$idCliente = $_POST["idCliente"];
	
	
	$clayma = $_POST["clayma"];
	
	if ($clayma=="true")
	{
		$clientes=cargarObservacionesClientesClayma($conexion,$idCliente);
	}
	else
	{
		$clientes=cargarObservacionesClientes($conexion,$idCliente);
	}
	
	
	
	
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
