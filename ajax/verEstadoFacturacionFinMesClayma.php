<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="verEstadoFacturacionFinMes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$estado = $_POST["estado"]; //0 poner fecha del ultimo dia del mes anterior, si no existe ninguna factura con fecha del mes actual
								//1 poner fecha actual
	
	
	
	
	//$fechaActual = date("d-m-Y");
	$fechaActual = date("Y-m-d");
	$anioSeleccionado = date('Y');
	$anioNuevo = false;
	
	$ultimaFechaCibeles = mostrarUltimaFechaFacturasCibelesClayma($conexion, $anioSeleccionado);
	
	if ($ultimaFechaCibeles[0]["fecha"] == null || $ultimaFechaCibeles[0]["fecha"] == "null" )
	{
		$anioNuevo = true;
	}
	
	$mesAnterior = strtotime ( '-1 month' , strtotime ($fechaActual));
	
	//$ultimoDia_MesAnterior= date("t-m-Y",$mesAnterior);
	$ultimoDia_MesAnterior= date("Y-m-t",$mesAnterior);
	
	/*
	echo "\nfechaActual: ".$fechaActual;
	echo "\nultimoDia dia  mes Anterior: ".$ultimoDia_MesAnterior;
	echo "\nultimaFecha cibeles: ".$ultimaFechaCibeles[0]["fecha"]->format("Y-m-d")."\n";
	*/
	//echo "Error:".$estado. "\n";
	if ($estado==="0" && $anioNuevo==false)
	{
		if ($ultimoDia_MesAnterior >= $ultimaFechaCibeles[0]["fecha"]->format("Y-m-d")) //no existe ninguna factura de este mes
		{			
			echo cambiarDatosFacturarFechaActualClayma($conexion, 0, $ultimoDia_MesAnterior);
		}
		else
		{
			
			echo "Error: existe facturas con fecha del mes actual".$ultimoDia_MesAnterior;
		}
	}
	else if ($estado==="0")
	{ 				
		echo cambiarDatosFacturarFechaActualClayma($conexion, 0, $ultimoDia_MesAnterior);		
	}
	else if ($estado==="1")
	{
		echo cambiarDatosFacturarFechaActualClayma($conexion, 1,$fechaActual);
	}
	else
	{
		$resultado=verEstadoFacturacionFinMesClayma($conexion);
		echo  $resultado[0]["activado"];


	}
		
}

?>
