<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="guardarPrefacturaCabeceraTemporal")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$usuario = $_SESSION['idEmpleado'];
	$idCliente = $_POST["idCliente"];
	$clayma = $_POST["clayma"];
	$pedido = $_POST["pedido"];
	$cantidad = $_POST["cantidad"];
	$formaPago = $_POST["formaPago"];
	$campana = $_POST["campana"];
	$detallada = $_POST["detallada"];
	$Neto = $_POST["Neto"];
	$iva = $_POST["iva"];
	$total = $_POST["total"];
	$provisionTotal = $_POST["provisionTotal"];
	$aPagar = $_POST["aPagar"];
	
	echo borrarPrefacturaCabeceraTemporal($conexion,$usuario);
	
	echo insertarPrefacturaCabeceraTemporal($conexion, $idCliente, $clayma, $usuario, $pedido, $cantidad, $formaPago, $campana, $detallada, $Neto, $iva, $total, $provisionTotal, $aPagar);
	
	///////////////////////////////////////////////////////////////////////
	
	/*$tabla = provisionDeFondoMovimientos_tabla;
	$descripcion = "creacion";
	$columna = "todas";
	$datosAntiguos = "";
	$idRegistro=0;
	
	$datosNuevos = "codigoCliente: ".$codigoCliente."||Clayma: ".$clayma1."||fecha: ".$fecha."||formaPago: ".$formaPago."||importe: ".$importe."||presupuesto: ".$presupuesto."||fechaCuadre: ".$fechaCuadre."||informacionCuadre: ".$informacionCuadre;
	
	//echo("\n".$datosAntiguos."\n");
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,'');*/
	
	
	
			
	
}


?>
