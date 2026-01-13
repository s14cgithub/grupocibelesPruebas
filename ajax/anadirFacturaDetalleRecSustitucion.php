<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="anadirFacturaDetalleSustitucion")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$facturaOriginal = $_POST["facturaOriginal"];	
	$clayma = $_POST["clayma"];
	$anioSeleccionado = $_POST["anio"];
	$numeroFacturaRec = $_POST["numeroFacturaRec"];
	
	

	$idEmpleado = $_SESSION["idEmpleado"];
	
	if ($clayma=="true")
	{
		
		$resultado = copiarTemporalRecAFacturaDetalleRecSustClayma($conexion,$facturaOriginal,$idEmpleado,$anioSeleccionado,$numeroFacturaRec);

		echo lanzarFacturaRecSustitucionClayma($conexion,$numeroFacturaRec,$anioSeleccionado,$urlClayma,$apiKeyClayma); 
		
	}
	else
	{ 
		$resultado = copiarTemporalRecAFacturaDetalleRecSust($conexion,$facturaOriginal,$idEmpleado,$anioSeleccionado,$numeroFacturaRec);

		
		echo lanzarFacturaRecSustitucionCibeles($conexion,$numeroFacturaRec,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);

		

	}	
	

	borrarDetallesTemporalesFacRec ($conexion,$idEmpleado);
	
	
	
	
	
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