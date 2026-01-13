<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="copiarFacturaMensual")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	$clayma = $_POST["clayma"];
	$numeroFacturaAcopiar = $_POST["numeroFactura"];
	$anioSeleccionadoAcopiar = $_POST["anioSeleccionado"];


	$fecha=null;
	$anioSeleccionado=null;


	if ($clayma=="true")
	{	
		
		$resultado=verEstadoFacturacionFinMesClayma($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("d-m-Y");
		}
		else
		{
			$fecha = date('d-m-Y');
		}
	}
	else
	{
		$resultado=verEstadoFacturacionFinMes($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("d-m-Y");
		}
		else
		{
			$fecha = date('d-m-Y');
		}
		 
	}

	$anioSeleccionado = substr($fecha, -4);
	
	
	
	$primerDia=  date("1/m/Y", strtotime($fecha));
	$ultimoDia=  date("t/m/Y", strtotime($fecha));


	$numeroNuevaFactura = insertarFacturaMensualPorCopia($conexion, $numeroFacturaAcopiar, $anioSeleccionadoAcopiar, $fecha, $primerDia, $ultimoDia, $anioSeleccionado);
	
	echo insertarFacturaMensualDetallesPorCopia($conexion, $numeroFacturaAcopiar, $numeroNuevaFactura,$anioSeleccionado, $anioSeleccionadoAcopiar);

	//$numeroNuevaFactura = $resultado[0]["numeroFactura"];

	$descripcion = log_creacion;
	$tabla = facturas_tabla;
	$columna = "todas";	
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];	
	$datosAntiguos = "";
	$datosNuevos ="Copia desde la factura: ".$numeroFacturaAcopiar."/".$anioSeleccionadoAcopiar.". La nueva factura es: ".$numeroNuevaFactura."/".$anioSeleccionado;
	$presupuesto = "";

	
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,0);	

	echo $numeroNuevaFactura."||||".$anioSeleccionado;
	
	
}


?>