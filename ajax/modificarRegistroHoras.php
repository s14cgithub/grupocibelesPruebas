<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarRegistroHoras")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$idRegistro = isset($filtros['id']) ? $filtros['id'] : '';
	$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

	$res = array('error' => '', 'datos' => array());

	if ($idRegistro == '' || !is_array($datos) || empty($datos))
	{
		$res['error'] = "Error: faltan datos o id";
		echo json_encode($res);
		return;
	}

	//se cargan los valores actuales de las columnas que se van a modificar, para comparar y auditar el cambio
	$campos = array_keys($datos);
	$resActual = cargarRegistrosHoras($conn, $bbddSql, $campos, array(), array('id' => $idRegistro), array(), array());
	$actual = isset($resActual['datos'][0]) ? $resActual['datos'][0] : array();

	foreach ($datos as $campo => $valorNuevo)
	{
		//el codigoBarras solo se cambia si viene informado (los registros manuales lo dejan vacio)
		if ($campo == 'codigoBarras' && $valorNuevo == '')
		{
			continue;
		}

		$valorAntiguo = isset($actual[$campo]) ? $actual[$campo] : null;
		if ($valorAntiguo instanceof DateTime)
		{
			$valorAntiguo = $valorAntiguo->format('d/m/Y H:i:s');
		}

		if ((string)$valorAntiguo !== (string)$valorNuevo)
		{
			insertarRegistro($conn, $bbddSql, array(
				'usuario'       => $usuario,
				'descripcion'   => "modificacion",
				'datosAntiguos' => (string)$valorAntiguo,
				'datosNuevos'   => (string)$valorNuevo,
				'tabla'         => tabla_registroHora,
				'columna'       => $campo,
				'idRegistro'    => $idRegistro
			));

			modificarRegistroHoras($conn, $bbddSql, array($campo => $valorNuevo), array('id' => $idRegistro));
		}
	}

	sqlsrv_close($conn);

	echo json_encode($res);
}

?>
