<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="verNumFactura")
{
	session_start(); 
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
		
	$numPresupuesto = $_POST["numPresupuesto"];
	$clayma = $_POST["clayma"];
	
	
	if ($clayma=="true")
	{	//echo "entra1";
		$numFactura=mostrarNumFacturaPorPresupuestoClayma($conexion,$numPresupuesto);
	
		///////////////////////////////////temporal//////////////////////////////////	

		/*$resultado = modificarTemporalTraspasoFactura($conexion, $numFactura[0]["numeroTemporal"]);

		if (substr($resultado,0,5)==="Error" )
		{
			echo "Error1".$resultado;
		}
		else
		{
			exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/insertarFacturaClaymaEnAccess.bat');

			$resultado = verNumeroFacturaTemporal($conexion);


		}*/	

		/////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		//echo "entra2";
		$numFactura=mostrarNumFacturaPorPresupuesto($conexion,$numPresupuesto);
	
		///////////////////////////////////temporal//////////////////////////////////	

		/*$resultado = modificarTemporalTraspasoFactura($conexion, $numFactura[0]["numeroTemporal"]);

		if (substr($resultado,0,5)==="Error" )
		{
			echo "Error1".$resultado;
		}
		else
		{
			exec('C:/xampp/htdocs/gestionGrupocibelesPreproduccion/consultas/insertarFacturaEnAccess.bat');

			$resultado = verNumeroFacturaTemporal($conexion);


		}	*/

		/////////////////////////////////////////////////////////////////////////////
	}
	
	
	
	
	if (count($numFactura)<=0)
	{
		echo json_encode("");
		echo ("Error4: No existe ninguna factura con ese numero de presupuesto: ");
	}
	else
	{
		echo json_encode($numFactura);
	}
		
}

?>
