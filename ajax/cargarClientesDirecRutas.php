<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cargarClientesDireccionRutas")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$idCliente = $_POST["idCliente"];
	$clayma = $_POST["clayma"];

	$condicion=" where idCliente = ".$idCliente;



	$orden = " order by id ";
	$condicion .= $orden;


	
	if ($clayma=="true")
	{
		$contactos=cargarClientesDireccionRutasClayma($conexion,$idCliente,$condicion);
	}
	else
	{
		$contactos=cargarClientesDireccionRutas($conexion,$idCliente,$condicion);
	}
	
	
	
	if (count($contactos)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($contactos);
	}
		
}

?>
