<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarFacturaSinCobrarPendiente")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$origen2 = $_POST["origen2"];
	$numeroFacturaCompleto = $_POST["numeroFacturaCompleto"];
	$formaPago = $_POST["formaPago"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$fechaPago = date('Y-m-d H:i:s');
	$usuario = $_SESSION['usuario'];

	if ($origen2=="CLAYMA")
	{
		$res = modificarFacturacionClayma($conn, $bbddSql, ['formaPagoReal' => $formaPago, 'fechaPago' => $fechaPago], ['numeroFacturaCompleto' => $numeroFacturaCompleto], []);

		insertarRegistro($conn, $bbddSql, ['usuario' => $usuario, 'descripcion' => 'modificacion', 'datosNuevos' => 'formaPagoReal: '.$formaPago, 'datosAntiguos' => '', 'tabla' => 'facturacionClayma', 'columna' => 'formaPagoReal', 'idRegistro' => $numeroFacturaCompleto, 'clayma' => 1]);
	}
	else if ($origen2=="CORREOS")
	{
		$res = modificarFacturacionCorreos($conn, $bbddSql, ['formaPago' => $formaPago, 'fechaPago' => $fechaPago], ['numeroOficial' => $numeroFacturaCompleto], []);

		insertarRegistro($conn, $bbddSql, ['usuario' => $usuario, 'descripcion' => 'modificacion', 'datosNuevos' => 'formaPago: '.$formaPago, 'datosAntiguos' => '', 'tabla' => facturasCorreos_tabla, 'columna' => 'formaPago', 'idRegistro' => $numeroFacturaCompleto]);
	}
	else if ($origen2=="CIBELES")
	{
		$res = modificarFacturacion($conn, $bbddSql, ['formaPagoReal' => $formaPago, 'fechaPago' => $fechaPago], ['numeroFacturaCompleto' => $numeroFacturaCompleto], []);

		insertarRegistro($conn, $bbddSql, ['usuario' => $usuario, 'descripcion' => 'modificacion', 'datosNuevos' => 'formaPagoReal: '.$formaPago, 'datosAntiguos' => '', 'tabla' => 'facturacion', 'columna' => 'formaPagoReal', 'idRegistro' => $numeroFacturaCompleto, 'clayma' => 0]);
	}
	else
	{
		$res = array('error' => 'modificarFacturaSinCobrarPendiente: origen2 no valido', 'ok' => false);
	}

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
