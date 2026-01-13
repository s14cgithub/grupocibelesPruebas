<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarObservacionesClientes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$condicion=isset($_POST["condicion"])?$_POST["condicion"]:"";	
	
	$clayma = $_POST["clayma"];
	
	
	/*if ($condicion=="A")
	{
		$condicion=" where t1.activo=1 order by t1.nombre_empresa, t2.fecha";
	}*/
	
	//echo $condicion;
	
	$clientes=cargarObservacionesClientesCompleto($conexion,$condicion,$clayma);
	
	
	
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
