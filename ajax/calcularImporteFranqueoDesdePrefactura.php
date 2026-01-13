<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarListadoFacturasPendientesTotal")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	//$idCliente = $_POST["idCliente"];
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	//$clayma = $_POST["clayma"];
	$idClienteCibeles = $_POST["idClienteCibeles"];
	$extension = $_POST["extension"];
	$ot = $_POST["ot"];
	
	
	
	$resultado=verEstadoFacturacionFinMes($conexion);
	
	//$fechaActual = date("d-m-Y");
	$fechaActual = date("Y-m-d");

	$mesAnterior = strtotime ( '-1 month' , strtotime ($fechaActual));	
	//$ultimoDia_MesAnterior= date("t/m/Y",$mesAnterior);
	$ultimoDia_MesAnterior = date("Y-m-t", $mesAnterior);


	//echo "\nfechaActual: ".$fechaActual;
	//$anioSeleccionado = date("Y",$fechaActual);
	$anioSeleccionado = date("Y", strtotime($fechaActual));
	
	if ($resultado[0]["activado"]==0 || $resultado[0]["activado"]=="0")
	{
		//$anioSeleccionado = date("Y",$ultimoDia_MesAnterior);
		$anioSeleccionado = date("Y", strtotime($ultimoDia_MesAnterior));
	}
	
	//echo "\nfechaActual: ".$fechaActual;
	//echo "\nanioSeleccionado: ".$anioSeleccionado;
	
	          
	$importe=verImporteFranqueoPorFechasYcliente($conexion,$idClienteCibeles,$fechaInicio,$fechaFin,$extension,$ot,$anioSeleccionado);
	
	
	if (count($importe)<=0)
	{
		echo json_encode("");
		
	}
	else
	{
		echo json_encode($importe);
		
		
	}
		
}

?>
