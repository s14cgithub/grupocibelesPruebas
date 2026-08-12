<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="eliminarRegistroHoras")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$idRegistro = isset($filtros['id']) ? $filtros['id'] : '';
	$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

	$res = array('error' => '', 'datos' => array());

	if ($idRegistro == '')
	{
		$res['error'] = "Error: falta id";
		echo json_encode($res);
		return;
	}

	//se guarda en el log el estado del registro antes de borrarlo
	$resActual = cargarRegistrosHoras($conn, $bbddSql, ['idEmpleado','nombreEmpleado','codigoBarras','horaInicio','horaFin','cantidad','observaciones','estado','modo'], array(), array('id' => $idRegistro), array(), array());

	if (isset($resActual['datos'][0]))
	{
		$r = $resActual['datos'][0];
		$horaInicio = ($r['horaInicio'] instanceof DateTime) ? $r['horaInicio']->format('Y-m-d H:i:s') : $r['horaInicio'];
		$horaFin = ($r['horaFin'] instanceof DateTime) ? $r['horaFin']->format('Y-m-d H:i:s') : $r['horaFin'];

		$datosAntiguos = "idEmpleado: ".$r['idEmpleado']."||empleado: ".$r['nombreEmpleado']."||codigoBarras: ".$r['codigoBarras']."||horaInicio: ".$horaInicio."||horaFin: ".$horaFin."||cantidad: ".$r['cantidad']."||nota: ".$r['observaciones']."||estado: ".$r['estado']."||modo: ".$r['modo'];

		insertarRegistro($conn, $bbddSql, array(
			'usuario'       => $usuario,
			'descripcion'   => "eliminacion",
			'datosAntiguos' => $datosAntiguos,
			'datosNuevos'   => "",
			'tabla'         => tabla_registroHora,
			'columna'       => "todas",
			'idRegistro'    => $idRegistro
		));
	}

	$res = eliminarRegistroHoras($conn, $bbddSql, array('id' => $idRegistro));

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
