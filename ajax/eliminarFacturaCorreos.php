<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="eliminarFacturaCorreo")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
			
	$idRegistro = $_POST["id"];
	
	$usuario = $_SESSION['usuario'];
	$tabla = facturasCorreos_tabla;
	$descripcion = "eliminacion";
	$columna = "todas";
	$datosNuevos = "";
	
	$resultado = mostrarFacturaCorreosId($conexion,$idRegistro);
	
	
	$numeroOficial = $resultado[0][facturasCorreos_numeroOficial];
	
	
	if ($resultado[0][facturasCorreos_fecha] == "" or $resultado[0][facturasCorreos_fecha] == null )
	{
		$fecha = null;
	}
	else
	{
		$fecha = $resultado[0][facturasCorreos_fecha]->format('Y-m-d H:i:s');
	}
	
	
	$codigoCliente = $resultado[0][facturasCorreos_codigoCliente]; 
	$campana = $resultado[0][facturasCorreos_campana]; 
	
	$neto = $resultado[0][facturasCorreos_neto]; 
	$iva = $resultado[0][facturasCorreos_iva]; 
	$importe = $resultado[0][facturasCorreos_importe]; 
	$anticipo = $resultado[0][facturasCorreos_anticipo]; 
	$aPagar = $resultado[0][facturasCorreos_aPagar]; 
	$formaPago = $resultado[0][facturasCorreos_formaPago]; 
	
	
	
	
	
	
	
	$datosAntiguos = "numeroOficial: ".$numeroOficial."||fecha: ".$fecha."||codigoCliente: ".$codigoCliente."||campana: ".$campana."||neto: ".$neto."||iva: ".$iva."||importe: ".$importe."||anticipo: ".$anticipo."||aPagar: ".$aPagar."||formaPago: ".$formaPago;
	
	//echo("\n".$datosAntiguos."\n");
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');
		
	eliminarFacturaCorreos($conexion,$idRegistro);
	
	
	
	
	
	
	
	
			
	
}


?>
