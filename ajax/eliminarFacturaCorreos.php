<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarFacturaCorreo")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idRegistro = $_POST["id"];

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$usuario = $_SESSION['usuario'];
	$tabla = facturasCorreos_tabla;
	$descripcion = "eliminacion";
	$columna = "todas";
	$datosNuevos = "";

	$resultado = mostrarFacturacionCorreos($conn, $bbddSql, ['numeroOficial','fecha','codigoCliente','campana','neto','iva','importe','anticipo','aPagar','formaPago'], [], ['id' => $idRegistro], [], []);

	$fila = $resultado['datos'][0];

	$numeroOficial = $fila['numeroOficial'];


	if ($fila['fecha'] == "" or $fila['fecha'] == null )
	{
		$fecha = null;
	}
	else
	{
		$fecha = $fila['fecha']->format('Y-m-d H:i:s');
	}


	$codigoCliente = $fila['codigoCliente'];
	$campana = $fila['campana'];

	$neto = $fila['neto'];
	$iva = $fila['iva'];
	$importe = $fila['importe'];
	$anticipo = $fila['anticipo'];
	$aPagar = $fila['aPagar'];
	$formaPago = $fila['formaPago'];



	$datosAntiguos = "numeroOficial: ".$numeroOficial."||fecha: ".$fecha."||codigoCliente: ".$codigoCliente."||campana: ".$campana."||neto: ".$neto."||iva: ".$iva."||importe: ".$importe."||anticipo: ".$anticipo."||aPagar: ".$aPagar."||formaPago: ".$formaPago;

	insertarRegistro($conn, $bbddSql, ['usuario' => $usuario, 'descripcion' => $descripcion, 'datosAntiguos' => $datosAntiguos, 'datosNuevos' => $datosNuevos, 'tabla' => $tabla, 'columna' => $columna, 'idRegistro' => $idRegistro]);

	eliminarFacturacionCorreos($conn, $bbddSql, ['id' => $idRegistro]);

}


?>
