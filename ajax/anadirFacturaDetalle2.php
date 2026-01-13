<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="anadirFacturaDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$numFactura = $_POST["numFactura"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	$clayma = $_POST["clayma"];
	$concepto = $_POST["proceso"];
	$descripcion = $_POST["descripcion"];
	$nota = $_POST["nota"];
	$unidades = $_POST["unidad"];
	$precioUnitario = $_POST["precio"];

	if ($_POST["exentoIVA"]=="true")
		$exentoIVA = 1;
	else
		$exentoIVA = 0;
	
	
	
	
	
	if ($clayma=="true")
	{
		$resultado = copiarTemporalPreFacturaAFacturaDetalleClayma($conexion,$numFactura,$numPresupuesto,$idEmpleado,$anioSeleccionado);
	}
	else
	{
		$resultado = insertarFacturasDetalles($conexion, $numFactura, $concepto, $unidades, $precioUnitario, $descripcion, $nota, 1100, 1100,$anioSeleccionado,$exentoIVA);
	}
	
			
	
	
	if (count($resultado)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No existe ninguna factura con ese numero de presupuesto: ");
	}
	else
	{
		echo json_encode($resultado);
	}
	
	
}

?>