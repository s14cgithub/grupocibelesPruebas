<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarListadoFacturasPendientesTotal")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	//guardar busqueda
	$guardarBusqueda = $_POST["guardarBusqueda"];
	$datosBusqueda = explode('|',$guardarBusqueda);
	
	foreach ($datosBusqueda as $valor)
	{
		$datos1 = explode('=',$valor);

		if ($datos1[0] == "buscarCampo")
		{
			$_SESSION["facturasSinCobrar_queBusca"] = $datos1[1];
		}
		else if ($datos1[0] == "buscarTexto")
		{
			$_SESSION["facturasSinCobrar_texto"] = $datos1[1];
		}
		else if ($datos1[0] == "ordenBuscar")
		{
			$_SESSION["facturasSinCobrar_orden"] = $datos1[1];
		}
		else if ($datos1[0] == "ordenDesc")
		{
			$_SESSION["facturasSinCobrar_ordenDesc"] = $datos1[1];
		}
		else if ($datos1[0] == "fechaInicio")
		{
			$_SESSION["facturasSinCobrar_fechaInicio"] = $datos1[1];
		}
		else if ($datos1[0] == "fechaFin")
		{
			$_SESSION["facturasSinCobrar_fechaFin"] = $datos1[1];
		}
		else if ($datos1[0] == "origen")
		{
			$_SESSION["facturasSinCobrar_origen"] = $datos1[1];
		}
		else if ($datos1[0] == "domiciliada")
		{
			$_SESSION["facturasSinCobrar_domiciliada"] = $datos1[1];
		}
	}


	

	
	
	$condicion = $_POST["condicion"];
	
	$resultado = mostrarListadoFacturasPendientesTotal($conexion, $condicion);
	
	
	
	if (count($resultado)<=0)
	{
		echo  json_encode("");
	}	
	else
	{
		echo  json_encode($resultado);
	}
}


?>