
<?php

function conectarSQL($datosBBDD)
{
    $connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID" => $datosBBDD->bbddUser,
        "PWD" => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if ($conn === false) {
        die("<pre>Error conexión SQL:\n" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    return [
        'conn' => $conn,
        'bbdd' => $datosBBDD->bbddBBDD
    ];
}

function mostrarFacturas_Asdfdsa($conn_sis, $bbddSql, $campos, $filtros, $anioSeleccionado, $order)
{
	/*
    $connectionInfo = array(
        "Database" => $datosBBDD->bbddBBDD,
        "UID" => $datosBBDD->bbddUser,
        "PWD" => $datosBBDD->bbddPass,
        "CharacterSet" => "UTF-8"
    );

    $conn_sis = sqlsrv_connect($datosBBDD->dbhost, $connectionInfo);

    if ($conn_sis === false) {
        return array();
    }
*/
    // ---------- CAMPOS ----------
    if (!is_array($campos) || empty($campos)) {
        return array();
    }

    $listaCampos = implode(', ', $campos);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (!empty($filtros['numero'])) {
        $condicion[] = 't1.numero = ?';
        $params[] = $filtros['numero'];
    }

    if (!empty($filtros['serieFactura'])) {
        $condicion[] = 't1.serieFactura = ?';
        $params[] = $filtros['serieFactura'];
    }

    if (!empty($filtros['numeroFacturaCompleto'])) {
        $condicion[] = 't1.numeroFacturaCompleto = ?';
        $params[] = $filtros['numeroFacturaCompleto'];
    }

    if (!empty($filtros['idCodigoCliente'])) {
        $condicion[] = 't1.idCodigoCliente = ?';
        $params[] = $filtros['idCodigoCliente'];
    }

    if (!empty($filtros['cliente'])) {
		$condicion[] = 't1.cliente LIKE ?';    
		$params[] = '%' . $filtros['cliente'] . '%';         
    }

    if (!empty($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    if (!empty($filtros['origenFactura'])) {
        $condicion[] = 't1.origenFactura = ?';
        $params[] = $filtros['origenFactura'];
    }

   	if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
		$condicion[] = 't1.fecha >= ? AND t1.fecha < DATEADD(day, 1, ?)';
		$params[] = $filtros['fecha_inicio'];
		$params[] = $filtros['fecha_fin'];
	}
	elseif (!empty($filtros['fecha_inicio'])) {
		$condicion[] = 't1.fecha >= ?';
		$params[] = $filtros['fecha_inicio'];
	}
	elseif (!empty($filtros['fecha_fin'])) {
		$condicion[] = 't1.fecha < DATEADD(day, 1, ?)';
		$params[] = $filtros['fecha_fin'];
	}

    if (!empty($filtros['detallada'])) {
        $condicion[] = 't1.detallada = ?';
        $params[] = $filtros['detallada'];
    }

    if (!empty($filtros['formaPagoReal'])) {
        $condicion[] = 't1.formaPagoReal = ?';
        $params[] = $filtros['formaPagoReal'];
    }

    if (!empty($filtros['fechaPagoReal'])) {
        $condicion[] = 't1.fechaPagoReal = ?';
        $params[] = $filtros['fechaPagoReal'];
    }

    if (!empty($filtros['fechaPago'])) {
        $condicion[] = 't1.fechaPago = ?';
        $params[] = $filtros['fechaPago'];
    }

    if (!empty($filtros['abono'])) {
        $condicion[] = 't1.abono = ?';
        $params[] = $filtros['abono'];
    }

    if (!empty($filtros['codigo_saldo'])) {
        $condicion[] = 't2.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'numero'     => 't1.numero',
        'fecha'      => 't1.fecha',
        'cliente'    => 't1.cliente',
        'fechaPago'  => 't1.fechaPago'
    );

    $sqlOrder = '';

    if (!empty($order) && is_array($order)) {
        $ordenes = array();

        foreach ($order as $o) {
            if (
                isset($o['campo'], $o['dir']) &&
                array_key_exists($o['campo'], $camposOrdenPermitidos) &&
                in_array(strtoupper($o['dir']), array('ASC', 'DESC'))
            ) {
                $ordenes[] = $camposOrdenPermitidos[$o['campo']] . ' ' . strtoupper($o['dir']);
            }
        }

        if (!empty($ordenes)) {
            $sqlOrder = ' ORDER BY ' . implode(', ', $ordenes);
        }
    }

    // ---------- SQL ----------
    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[facturas$anioSeleccionado] AS t1
        INNER JOIN [".$bbddSql."].[dbo].[clientes] AS t2
            ON t1.cliente = t2.nombre_empresa
        LEFT JOIN [".$bbddSql."].[dbo].[abonosTodosLosAnios] AS t3
            ON t3.factura = t1.numero AND t3.anioFactura = ?
        LEFT JOIN [".$bbddSql."].[dbo].[facRecTodosLosAnios] AS t4
            ON t4.origenFactura = t1.numeroFacturaCompleto
        $sqlWhere
        $sqlOrder
    ";

	//echo $consulta;

    array_unshift($params, $anioSeleccionado);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array();
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    return $result;
}

function insertarFacturaNegativaDesdeSustitucion_Asdfdsa($conn_sis, $bbddSql, $anioSeleccionado, $datos)
{
}

/*
$conn = conectarSQL($datosBBDD);

$campos = [
    't1.numero',
    't1.fecha',
    't1.cliente',
    't1.total'
];

$filtros = [
    'cliente' => 'EMPRESA SL'
];

$order = [
    ['campo' => 'fecha', 'dir' => 'DESC'],
    ['campo' => 'numero', 'dir' => 'ASC']
];

$resultado = mostrarFacturas2($datosBBDD, $campos, $filtros, $anioSeleccionado, $order);

sqlsrv_close($conn);

*/ 


?>