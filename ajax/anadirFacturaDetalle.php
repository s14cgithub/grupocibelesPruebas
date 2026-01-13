<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirFacturaDetalle")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numFactura = $_POST["numFactura"]; //no es numero de factura completo
	$numPresupuesto = $_POST["numPresupuesto"];
	$clayma = $_POST["clayma"];
	$anioSeleccionado = $_POST["anioSeleccionado"];

	$idEmpleado = $_SESSION["idEmpleado"];
	//echo "\nNumero de factura: ".$numFactura;
	if ($clayma=="true")
	{
		//echo "\n<br>entra en clayma<br>\n";
		//$resultado = copiarTemporalPreFacturaAFacturaDetalleClayma($conexion,$numFactura,$numPresupuesto,$idEmpleado,$anioSeleccionado,$numeroCompleto);
		$resultado = copiarTemporalPreFacturaAFacturaDetalleClayma($conexion,$numFactura,$numPresupuesto,$idEmpleado,$anioSeleccionado);
		echo lanzarFacturaClayma($conexion,$numFactura,$anioSeleccionado,$urlClayma,$apiKeyClayma);
	}
	else
	{   
		//echo "\n<br>entra en cibeles<br>\n";                                                                   
		//$resultado = copiarTemporalPreFacturaAFacturaDetalle($conexion,$numFactura,$numPresupuesto,$idEmpleado,$anioSeleccionado,$numeroCompleto);
		$resultado = copiarTemporalPreFacturaAFacturaDetalle($conexion,$numFactura,$numPresupuesto,$idEmpleado,$anioSeleccionado);

		//require_once($ruta."Verifactu/wortice/lanzarFactura.php");
		echo lanzarFacturaCibeles($conexion,$numFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);

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