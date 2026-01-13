<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarListadoDireccionesRutasClientes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$idCliente=$_POST["idCliente"];

	$condicion = "  where idCliente = ".$idCliente." and activo !=0 order by activo desc, orden desc, nombre "; //NO CAMBIAR EL ORDEN DE ESTA CONSULTA
		
	
	$direcciones=cargarListadoDireccionesRutasCliente($conexion,$condicion);
	
	if (count($direcciones)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($direcciones);
	}
		
}

?>
