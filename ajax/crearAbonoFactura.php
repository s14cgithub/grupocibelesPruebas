<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="crearAbonoFactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$numeroFactura = $_POST["numeroFactura"];	
	$clayma = $_POST["clayma"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	$anioSeleccionado2="";
		
	
	if ($clayma=="true")
	{
		
		$resultado=verEstadoFacturacionFinMesClayma($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("d/m/Y");
		}
		else
		{
			$fecha = date('d/m/Y');
		}
		
		$anioSeleccionado2 = substr($fecha, -4); //fecha del abono
		
		
		$datos =  duplicarFactura_AbonoClayma($conexion,$numeroFactura,$fecha,$anioSeleccionado,$anioSeleccionado2);
	
	
		echo duplicarFacturaDetalles_AbonoClayma($conexion, $numeroFactura, $datos[0]["numeroAbono"],$anioSeleccionado,$anioSeleccionado2);


		echo cambiarValorAbonoEnFacturaClayma($conexion, $numeroFactura,$anioSeleccionado);

		echo cambiarValorOrigenFacturaClayma($conexion, $numeroFactura,$anioSeleccionado);
		echo borrarNumPresupuestoEnFacturaClayma($conexion, $numeroFactura,$anioSeleccionado);
		
		echo facturaAfacturaMensualPendienteClayma($conexion,$numeroFactura,$anioSeleccionado);
		
		echo modificarValorPrefacturaClayma($conexion, $numeroFactura,0,$anioSeleccionado);
		
	}
	else
	{
		
		$resultado=verEstadoFacturacionFinMes($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("d/m/Y");
		}
		else
		{
			$fecha = date('d/m/Y');
		}
		
		
		$anioSeleccionado2 = substr($fecha, -4); //fecha del abono
		
		$datos =  duplicarFactura_Abono($conexion,$numeroFactura,$fecha,$anioSeleccionado,$anioSeleccionado2);
	
	
		echo duplicarFacturaDetalles_Abono($conexion, $numeroFactura, $datos[0]["numeroAbono"],$anioSeleccionado,$anioSeleccionado2);


		echo cambiarValorAbonoEnFactura($conexion, $numeroFactura,$anioSeleccionado);

		
		echo cambiarValorOrigenFactura($conexion, $numeroFactura,$anioSeleccionado);
		echo borrarNumPresupuestoEnFactura($conexion, $numeroFactura,$anioSeleccionado);
		
		echo facturaAfacturaMensualPendiente($conexion,$numeroFactura,$anioSeleccionado);
		
		echo modificarValorPrefactura($conexion, $numeroFactura,0,$anioSeleccionado);
		
	}
	
	
	

		
		
	echo json_encode($datos);
	
}

?>