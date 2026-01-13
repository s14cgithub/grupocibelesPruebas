<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarClientes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$campos = $_POST["campos"];
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";
	
	if ($condicion=="A")
	{
		//$condicion = " where tipo_factura='A'";
		$condicion = " where activo = 1";
	}
	/*else if ($condicion=="B")
	{
		//$condicion = " where tipo_factura='A'";
		$condicion = " where codigo_saldo = codigo ";
	}*/
	
	
	
	
	$clientes=cargarSubClientes2($conexion,$campos,$condicion);
	
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
