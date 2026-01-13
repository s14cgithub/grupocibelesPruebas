<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="cobroMasivoDomiciliado")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	$formaPago = $_POST["formaPago"];
	//$fechaPago = $_POST["fechaPago"];
	
	
	
	
	$resultado = verFacturasDomiciliadasEntreFechas($conexion,$fechaInicio,$fechaFin);
	

	$idRegistro = "";	
	$usuario = $_SESSION['usuario'];	
	$descripcion = log_modificacion;		
	$datosAntiguos="";
	$datosNuevos = $formaPago;		
		
	$tabla = facturas_tabla;
	$columna = facturas_formaDepago;

	for ($contador=0;$contador<count($resultado);$contador++)
	{		
		modificarFacturasPendientes($conexion, $resultado[$contador]["numero"], $formaPago, $resultado[$contador]["anioFactura"]);
		$presupuesto = $resultado[$contador]["numero"]."/".$resultado[$contador]["anioFactura"];
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);
		//insertarMovimientoFacturaCibeles($conexion, $resultado[$contador]["numero"]);		
	}
	
	$resultado3 = verAbonosDomiciliadasEntreFechas($conexion,$fechaInicio,$fechaFin);
	
	$tabla = abono_tabla;
	$columna = abono_formaDepago;

	for ($contador=0;$contador<count($resultado3);$contador++)
	{
		modificarAbonosPendientes($conexion, $resultado3[$contador]["numero"], $formaPago, $resultado3[$contador]["anioAbono"]);
		$presupuesto = $resultado3[$contador]["numero"]."/".$resultado3[$contador]["anioAbono"];
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);
		//insertarMovimientoFacturaCibeles($conexion, $resultado3[$contador]["numero"]);		
	}
	
	$fecha = date('Y-m-d');
	
	$resultado2 = verFacturasCorreosDomiciliadasEntreFechas($conexion,$fechaInicio,$fechaFin);
	
	$tabla = facturasCorreos_tabla;
	$columna = facturasCorreos_formaPago;

	for ($contador=0;$contador<count($resultado2);$contador++)
	{
		
		modificarFacturasCorreosPendientes($conexion,$resultado2[$contador]["numeroOficial"],$formaPago);	

		$presupuesto = $resultado2[$contador]["numeroOficial"];
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);

		
		$codigoSaldo=$resultado2[$contador]["codigo_saldo"];
		$saldo=null;
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$codigoSaldo);
		
		$importe = $resultado2[$contador]["importe"];
		
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;
		
		insertarMovimientoPF($conexion, $codigoSaldo, $fecha, $formaPago, $importe, $resultado2[$contador]["numeroOficial"], '','',$nuevoSaldo,0);	
		
		modificarDatosPFenCliente($conexion,$codigoSaldo,$fecha,$importe);
		
	}
	
	
	
}


?>