<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarFacturasDetallesTemporal")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$datos = isset($_POST["datos"]) ? json_decode($_POST["datos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	//LOG
	$campos2 = array_keys($datos);

	$idDetalle = $filtros['id'];

	$actual = mostrarFacturasDetallesTemporal($conn, $bbddSql, $campos2, ['id' => $idDetalle], [], []);

	$i = 0;
	while ($i < count($campos2)) {
		$columna = $campos2[$i];

		if ($datos[$columna] != $actual['datos'][0][$columna]) {
			$datos2 = array(
				'usuario' => $_SESSION['usuario'],
				'descripcion' => log_modificacion,
				'tabla' => preFactura_tabla,
				'datosAntiguos' => $actual['datos'][0][$columna],
				'datosNuevos' => $datos[$columna],
				'columna' => $columna,
				'idRegistro' => $idDetalle
			);
			insertarRegistro($conn, $bbddSql, $datos2);
		}

		$i++;
	}

	$res = modificarFacturasDetallesTemporal($conn, $bbddSql, $datos, $filtros, $filtrosOperadores);

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
