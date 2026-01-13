<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarClientesBuscador")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=$_POST["condicion"];
	$condicion=utf8_decode($condicion);
	
	$clayma=$_POST["clayma"];
	
	
	if ($clayma=="true")
	{
		$clientes=cargarClientesClayma($conexion,$condicion);
	}
	else
	{
		$clientes=cargarClientes($conexion,$condicion);
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
