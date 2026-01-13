
<?php

if(isset($_POST["accion"])&&$_POST["accion"]=="comprobarVerifactu")
{
    session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

   
    $anioSeleccionado = $_POST["anioSeleccionado"];
    //$condicion = " where t1.fecha > '".fechaCambioVerifactu."' and  (t1.verifactu_qrcode is null or t1.verifactu_qrcode='')";
	$condicion = " where t1.fecha > '".fechaCambioVerifactu."' and  (t1.verifactu_idSolicitud is null or t1.verifactu_idSolicitud='')";

	$clayma = $_POST["clayma"];
	$tipoFactura = $_POST["tipoFactura"];

	echo "\nclayma: ".$clayma;
	echo "\ntipoFactura: ".$tipoFactura;
	
	if ($clayma=="true" && $tipoFactura=="Factura") //Hecho
	{	echo "entra1";	
		$datosDeFacturas = mostrarFacturasClayma($conexion,$condicion,$anioSeleccionado);
		foreach ($datosDeFacturas as $valor3)
		{
			$numeroDeFactura = $valor3["numero"];		
			lanzarFacturaClayma($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);    
		}
	}
	else if ($clayma=="true" && $tipoFactura=="RectDiferencias") //hecho
	{echo "entra2";
		$datosDeFacturas = mostrarFactRectificativasClayma($conexion,$condicion,$anioSeleccionado);
		foreach ($datosDeFacturas as $valor3)
		{
			$numeroDeFactura = $valor3["numero"];		
			lanzarFacturaRecDiferenciaClayma($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);    
		}
	}
	else if ($clayma=="true" && $tipoFactura=="RectSustitucion") //hecho
	{echo "entra3";
		$datosDeFacturas = mostrarFactRectificativasSustitutivaClayma($conexion,$condicion,$anioSeleccionado);
		foreach ($datosDeFacturas as $valor3)
		{
			$numeroDeFactura = $valor3["numero"];		
			lanzarFacturaRecSustitucionClayma($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);    
		}
	}
	else if ($clayma=="false" && $tipoFactura=="Factura") //hecho
	{ 	echo "\nentra4";	
		echo "\n".$condicion."\n";	
		$datosDeFacturas = mostrarFacturas($conexion,$condicion,$anioSeleccionado);
		foreach ($datosDeFacturas as $valor3)
		{
			$numeroDeFactura = $valor3["numero"];		
			echo lanzarFacturaCibeles($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);    
		}
	}
	else if ($clayma=="false" && $tipoFactura=="RectDiferencias") //hecho
	{echo "entra5";
		$datosDeFacturas = mostrarFactRectificativas($conexion,$condicion,$anioSeleccionado);
		foreach ($datosDeFacturas as $valor3)
		{
			$numeroDeFactura = $valor3["numero"];		
			lanzarFacturaRecDiferenciaCibeles($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);    
		}
	}
	else if ($clayma=="false" && $tipoFactura=="RectSustitucion") //hecho
	{echo "entra6";
		$datosDeFacturas = mostrarFactRectificativasSustitutiva($conexion,$condicion,$anioSeleccionado);
		foreach ($datosDeFacturas as $valor3)
		{
			$numeroDeFactura = $valor3["numero"];		
			lanzarFacturaRecSustitucionCibeles($conexion,$numeroDeFactura,$anioSeleccionado,$urlCibeles,$apiKeyCibeles);    
		}
	}



	

	
}
?>