<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="verEstadoFacturacionFinMes")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$estado = $_POST["estado"]; //0 poner fecha del ultimo dia del mes anterior, si no existe ninguna factura con fecha del mes actual
								//1 poner fecha actual
	
	
	
	
	$fechaActual = date("d-m-Y");
	$anioSeleccionado = date('Y');
	$anioNuevo = false;
	
	$ultimaFechaCibeles = mostrarUltimaFechaFacturasCibeles($conexion, $anioSeleccionado);
	
	if ($ultimaFechaCibeles[0]["fecha"] == null || $ultimaFechaCibeles[0]["fecha"] == "null" )
	{ //echo "entra1";
		$anioNuevo = true;
	}
	
	$mesAnterior = strtotime ( '-1 month' , strtotime ($fechaActual));	
	$ultimoDia_MesAnterior= date("t/m/Y",$mesAnterior);
	
	//echo "Error:".$estado. "\n";
	if ($estado==="0" && $anioNuevo==false)
	{ 
		//echo "entra2";
		
		$numero1 = intval(date("Y",$mesAnterior));
		$numero2 = intval($ultimaFechaCibeles[0]["fecha"]->format("Y"));
		
		if ($ultimoDia_MesAnterior >= $ultimaFechaCibeles[0]["fecha"]->format("d/m/Y") && $numero1>=$numero2 ) //no existe ninguna factura de este mes
		{	//echo "entra3";	
			echo cambiarDatosFacturarFechaActual($conexion, 0, $ultimoDia_MesAnterior);
			//echo "Error: ¿porque?";
		}
		else
		{//echo "entra4";
			echo "Error: existe facturas con fecha del mes actual";
		}
	}
	else if ($estado==="0")
	{ 	//echo "entra5";			
		echo cambiarDatosFacturarFechaActual($conexion, 0, $ultimoDia_MesAnterior);		
	}
	else if ($estado==="1")
	{//echo "entra6";
		echo cambiarDatosFacturarFechaActual($conexion, 1,$fechaActual);
	}
	else
	{//echo "entra7";
		$resultado=verEstadoFacturacionFinMes($conexion);
		echo  $resultado[0]["activado"];
	}
		
}

?>
