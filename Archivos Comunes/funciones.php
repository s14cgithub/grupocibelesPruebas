
<?php

function cargarLogin($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order)
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'usuario' => 't1.usuario',
        'idEmpleado' => 't1.idEmpleado'     
    );

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }


    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['usuario'])) {
        $condicion[] = 't1.usuario = ?';
        $params[] = $filtros['usuario'];
    }
    if (isset($filtros['contrasena'])) {
        $condicion[] = 't1.contrasena = ?';
        $params[] = $filtros['contrasena'];
    }   
    if (isset($filtros['activo'])) {
        $condicion[] = 't1.activo = ?';
        $params[] = $filtros['activo'];
    } 


    $operadoresPermitidos = array();

    $camposComparablesPermitidos = array();

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array();

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
        FROM [".$bbddSql."].[dbo].[login] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
    
}

function cargarPermisos($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order)
{

    $camposPermitidos = array(
        'pdaConductor' => 't1.pdaConductor',
        'pda' => 't1.pda',
        'clientes' => 't1.clientes',
        'pdaGestion' => 't1.pdaGestion',
        'pda_registrosHorasManuales' => 't1.pda_registrosHorasManuales',
        'informesProduccion' => 't1.informesProduccion',
        'pdaAdjunto' => 't1.pdaAdjunto',
        'presupuestos' => 't1.presupuestos',
        'nuevoProcesoPresu' => 't1.nuevoProcesoPresu',
        'cambiarFechaCompromisoPresu' => 't1.cambiarFechaCompromisoPresu',
        'cambiarFechaAceptacionPresu' => 't1.cambiarFechaAceptacionPresu',
        'presuOtBajada' => 't1.presuOtBajada',
        'presuOtAbierta' => 't1.presuOtAbierta',
        'presuOtTerminada' => 't1.presuOtTerminada',
        'otBajadaAutomatico' => 't1.otBajadaAutomatico',
        'ot' => 't1.ot',
        'administracion' => 't1.administracion',
        'admContabilidad' => 't1.admContabilidad',
        'admContabilidad_domiciados' => 't1.admContabilidad_domiciados',
        'admFacturacion' => 't1.admFacturacion',
        'grabarFranqueo' => 't1.grabarFranqueo',
        'franqueoF12' => 't1.franqueoF12',
        'actualizarDatos' => 't1.actualizarDatos',
        'presupuestoMensual' => 't1.presupuestoMensual',
        'rutas' => 't1.rutas',
        'empleados' => 't1.empleados',
        'comprasAterceros' => 't1.comprasAterceros',
        'proveedores' => 't1.proveedores',
        'provisionFondos' => 't1.provisionFondos',
        'facturasManipulacion' => 't1.facturasManipulacion',
        'facturasCorreos' => 't1.facturasCorreos',
        'facturas' => 't1.facturas',
        'certAlbGastAdicional' => 't1.certAlbGastAdicional',
        'soloGrabarRecogidasEntregas' => 't1.soloGrabarRecogidasEntregas',
        'almacen' => 't1.almacen',
        'almacen_nuevo' => 't1.almacen_nuevo',
        'almacen_albaran' => 't1.almacen_albaran',
        'estimacionFranqueo' => 't1.estimacionFranqueo',
        'informeFacturaEstadisticas' => 't1.informeFacturaEstadisticas',
        'soloDireccion' => 't1.soloDireccion',
        'preEntradaGestion' => 't1.preEntradaGestion',
        'almacen_listado' => 't1.almacen_listado',
        'clientesAutorizadosFranqueo' => 't1.clientesAutorizadosFranqueo',
        'tarifas' => 't1.tarifas',
        'materialesPapel' => 't1.materialesPapel',
        'noFacProcesado' => 't1.noFacProcesado',
		'soloNoFacturable' => 't1.soloNoFacturable',
		'admInformes' => 't1.admInformes'          
    );

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }


    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id_usuario'])) {
        $condicion[] = 't1.id_usuario = ?';
        $params[] = $filtros['id_usuario'];
    }   

    $operadoresPermitidos = array();

    $camposComparablesPermitidos = array();

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array();

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
        FROM [".$bbddSql."].[dbo].[permisos] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
    
}

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

function cargarClientes($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'codigo_saldo' => 't1.codigo_saldo',
        'codigo' => 't1.codigo',
        'nombre_empresa' => 't1.nombre_empresa',
        'nombre_franqueo' => 't1.nombre_franqueo',
        'subcliente' => 't1.subcliente',
        'direccion' => 't1.direccion',
        'localidad' => 't1.localidad',
        'provincia' => 't1.provincia',
        'codigo_postal' => 't1.codigo_postal',
        'nif_subcliente' => 't1.nif_subcliente',
        'nif' => 't1.nif',
        'comercial' => 't1.comercial',
        'forma_pago' => 't1.forma_pago',
        'tipo_listado' => 't1.tipo_listado',
        'tipo_factura' => 't1.tipo_factura',
        'fecha_alta' => 't1.fecha_alta',
        'provision_inicial' => 't1.provision_inicial',
        'conductor' => 't1.conductor',
        'pais' => 't1.pais',
        'codigoPais' => 't1.codigoPais',
        'activo' => 't1.activo',
        'dias_de_pago' => 't1.dias_de_pago',
        'envio_att' => 't1.envio_att',
        'envio_nombre' => 't1.envio_nombre',
        'envio_domicilio' => 't1.envio_domicilio',
        'envio_cp' => 't1.envio_cp',
        'envio_poblacion' => 't1.envio_poblacion',
        'envio_provincia' => 't1.envio_provincia',
        'envio_pais' => 't1.envio_pais',
        'retener' => 't1.retener',
        'domiciliada' => 't1.domiciliada',
        'inactiva_permanente' => 't1.inactiva_permanente',
        'inactiva_problemas' => 't1.inactiva_problemas',
        'inactiva_inactividad' => 't1.inactiva_inactividad',
        'importePF' => 't1.importePF',
        'fechaCobroPF' => 't1.fechaCobroPF',
        'fechaCuadrePF' => 't1.fechaCuadrePF',
        'imformacionCuadrePF' => 't1.imformacionCuadrePF',
        'idComercial' => 't1.idComercial',
        'idFormaPago' => 't1.idFormaPago',
        'idFormaPagoFranqueo' => 't1.idFormaPagoFranqueo',
        'email' => 't1.email',
        'fac_cuotaRecogida_2024' => 't1.fac_cuotaRecogida_2024',
        'fac_cuotaRecogida' => 't1.fac_cuotaRecogida',
        'fac_idPeriodo' => 't1.fac_idPeriodo',
        'fac_porCientoNoBonificable' => 't1.fac_porCientoNoBonificable',
        'fac_otrosConceptosFijos' => 't1.fac_otrosConceptosFijos',
        'fac_importeFijoOtrosConcepto' => 't1.fac_importeFijoOtrosConcepto',
        'fac_idProvisionFondos' => 't1.fac_idProvisionFondos',
        'fac_cobroUnitarioEnvio' => 't1.fac_cobroUnitarioEnvio',
        'fac_pfFijaImporte' => 't1.fac_pfFijaImporte',
        'idDiasDePago' => 't1.idDiasDePago',
        'numCuentaBanco' => 't1.numCuentaBanco',
        'correoDiario' => 't1.correoDiario',
        'nuestraCuenta' => 't1.nuestraCuenta',
        'sinIva' => 't1.sinIva',
        'retencion' => 't1.retencion',
        'pedidoCliente' => 't1.pedidoCliente',
        'vencimiento' => 't1.vencimiento',
        'codigoSidi' => 't1.codigoSidi',
        'codigoSidiPre' => 't1.codigoSidiPre',
        'prefactura' => 't1.prefactura',
        'idAutorizacionFranqueo' => 't1.idAutorizacionFranqueo',
        'noAplicarPF' => 't1.noAplicarPF',
        'plazoVencimiento' => 't1.plazoVencimiento',
        'saldoTotal' => 'sum(t1.importePF) as saldoTotal',
        'importeFijoTotal' => 'sum(t1.fac_pfFijaImporte) as importeFijoTotal',
        'fechaObservacion' => 't3.fecha',
        'idObservacion' => 't3.id',
        'observacion' => 't3.observacion'
       
       
    );

    //t2: franqueoTipos
    //t3: clientesObservaciones

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[franqueoTipos] as t2 on t1.codigo = t2.idCliente",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[clientesObservaciones] as t3 on t1.codigo = t3.idCliente"
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['activo'])) {
        $condicion[] = 't1.activo = ?';
        $params[] = $filtros['activo'];
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 't1.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }
    if (isset($filtros['codigo'])) {
        $condicion[] = 't1.codigo = ?';
        $params[] = $filtros['codigo'];
    }
    if (isset($filtros['subcliente'])) {
        $condicion[] = 't1.subcliente = ?';
        $params[] = $filtros['subcliente'];
    }
    if (isset($filtros['autorizadoFranqueo']) && $filtros['autorizadoFranqueo'] == 1) {
        $condicion[] = ' (t1.idAutorizacionFranqueo = 2 or t1.idAutorizacionFranqueo = 3) ';
    }
    if (isset($filtros['referencia'])) {
        $condicion[] = 't2.referencia = ?';
        $params[] = $filtros['referencia'];
    }
    if (isset($filtros['retener'])) {
        $condicion[] = 't1.retener = ?';
        $params[] = $filtros['retener'];
    }
    if (isset($filtros['correoDiario'])) {
        $condicion[] = 't1.correoDiario = ?';
        $params[] = $filtros['correoDiario'];
    }
    if (isset($filtros['fac_idPeriodo'])) {
        $condicion[] = 't1.fac_idPeriodo = ?';
        $params[] = $filtros['fac_idPeriodo'];
    }
    if (isset($filtros['fac_idProvisionFondos'])) {
        $condicion[] = 't1.fac_idProvisionFondos = ?';
        $params[] = $filtros['fac_idProvisionFondos'];
    }
    if (isset($filtros['nombre_empresa'])) {
        $condicion[] = 't1.nombre_empresa = ?';
        $params[] = $filtros['nombre_empresa'];
    }
    if (isset($filtros['nombre_franqueo'])) {
        $condicion[] = 't1.nombre_franqueo = ?';
        $params[] = $filtros['nombre_franqueo'];
    }
    if (isset($filtros['asunto'])) {
        $condicion[] = 't3.asunto = ?';
        $params[] = $filtros['asunto'];
    }
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'IN');

    $camposComparablesPermitidos = array(
    'codigo_saldo' => 't1.codigo_saldo',
    'codigo' => 't1.codigo',
    'idObservacion' => 't3.id'
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

        // ---------- IN CON SUBCONSULTA  solo para max(id) de observaciones----------
            if (
                    isset($f['campo1'], $f['operador'], $f['tipoSubconsulta']) &&
                    isset($camposComparablesPermitidos[$f['campo1']]) &&
                    strtoupper($f['operador']) == 'IN' && 
                    $f['tipoSubconsulta'] == 'ultimaObservacionEnvioFacturas'
            ) {
                     $condicion[] =
                        $camposComparablesPermitidos[$f['campo1']] . "
                        IN (
                            SELECT MAX(id)
                            FROM [".$bbddSql."].[dbo].[clientesObservaciones]
                            WHERE asunto = 'Envio Facturas'
                            GROUP BY idCliente
                        )";
            }
            // campo vs campo
            else if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'codigo' => 't1.codigo',
        'nombre_empresa' => 't1.nombre_empresa',
        'nombre_franqueo' => 't1.nombre_franqueo',
        'direccion' => 't1.direccion',
        'codigoSidi' => 't1.codigoSidi',
        'localidad' => 't1.localidad',
        'nif' => 't1.nif',
        'subcliente' => 't1.subcliente',
        'codigo_postal' => 't1.codigo_postal',
        'fecha' => 't3.fecha',
        'observacion' => 't3.observacion'
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre_empresa' => 't1.nombre_empresa',
        'subcliente' => 't1.subcliente',
        'nombre_franqueo' => 't1.nombre_franqueo',
        'codigo' => 't1.codigo',
        'codigo_saldo' => 't1.codigo_saldo',
        'codigo_postal' => 't1.codigo_postal',
        'direccion' => 't1.direccion',
        'localidad' => 't1.localidad',
        'observacion' => 't3.observacion'
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
        FROM [".$bbddSql."].[dbo].[clientes] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
    
}

function cargarClientesObservaciones($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'fecha' => 't1.fecha',
        'nombreCompleto' => "t2.nombre + ' ' + t2.apellidos as nombreCompleto",
        'asunto' => 't1.asunto',
        'observacion' => 't1.observacion'       
    );

    //t2: empleados

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id"    
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'codigo_saldo' => 't1.codigo_saldo'    
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        //'codigo' => 't1.codigo'       
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
       
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
        FROM [".$bbddSql."].[dbo].[clientesObservaciones] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
    
}

function cargarClientesObservacionesClayma($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'fecha' => 't1.fecha',
        'nombreCompleto' => "t2.nombre + ' ' + t2.apellidos as nombreCompleto",
        'asunto' => 't1.asunto',
        'observacion' => 't1.observacion'       
    );

    //t2: empleados

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id"    
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'codigo_saldo' => 't1.codigo_saldo'    
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        //'codigo' => 't1.codigo'       
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
       
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
        FROM [".$bbddSql."].[dbo].[clientesObservacionesClayma] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
    
}

function cargarClientesContactos($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'idSexo' => 't1.idSexo',
        'nombre' => "t1.nombre",
        'apellidos' => 't1.apellidos',
        'departamento' => 't1.departamento',
        'cargo' => 't1.cargo',
        'telefono' => 't1.telefono',
        'movil' => 't1.movil',
        'email' => 't1.email',
        'comentario' => 't1.comentario',
        'sexo' => 't2.sexo',
       
    );

    //t2: sexo

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        'tabla2' => " inner join [".$bbddSql."].[dbo].[sexo] as t2  on t2.id = t1.idSexo"    
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'codigo_saldo' => 't1.codigo_saldo'    
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        //'codigo' => 't1.codigo'       
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
       
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
        FROM [".$bbddSql."].[dbo].[clientesContactos] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
       // die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");

        return array(
            'error' => '"<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',       
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
    
}

function cargarClientesContactosClayma($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'idSexo' => 't1.idSexo',
        'nombre' => "t1.nombre",
        'apellidos' => 't1.apellidos',
        'departamento' => 't1.departamento',
        'cargo' => 't1.cargo',
        'telefono' => 't1.telefono',
        'movil' => 't1.movil',
        'email' => 't1.email',
        'comentario' => 't1.comentario',
        'sexo' => 't2.sexo',
        'movil' => 't1.movil'
    );

    //t2: sexo

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        'tabla2' => " inner join [".$bbddSql."].[dbo].[sexo] as t2  on t2.id = t1.idSexo"    
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'codigo_saldo' => 't1.codigo_saldo'    
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        //'codigo' => 't1.codigo'       
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
       
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
        FROM [".$bbddSql."].[dbo].[clientesContactosClayma] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
       // die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");

        return array(
            'error' => '"<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',       
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
    
}

function cargarClientesDirecRutas($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'att' => 't1.att',
        'nombre' => 't1.nombre',       
        'direccion' => "t1.direccion",
        'cp' => 't1.cp',
        'poblacion' => 't1.poblacion',
        'provincia' => 't1.provincia',
        'pais' => 't1.pais',
        'activo' => 't1.activo',
        'idCliente' => 't1.idCliente'
    );

    //t2:

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        //'tabla2' => "inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id"    
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'codigo_saldo' => 't1.codigo_saldo'    
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        //'codigo' => 't1.codigo'       
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
       
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
        FROM [".$bbddSql."].[dbo].[clientesDirecRutas] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
       // die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");

        return array(
            'error' => '"<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',       
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
    
}

function cargarClientesDirecRutasClayma($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins= array(), $filtrosLike= array())
{

    $camposPermitidos = array(
        'id' => 't1.id',
        'att' => 't1.att',
        'nombre' => 't1.nombre',
        'direccion' => "t1.direccion",
        'cp' => 't1.cp',
        'poblacion' => 't1.poblacion',
        'provincia' => 't1.provincia',
        'pais' => 't1.pais',
        'activo' => 't1.activo',
        'idCliente' => 't1.idCliente'
    );

    //t2:

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

  
  

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    //JOINS

    $joinsPermitidos = [
        //'tabla2' => "inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id"    
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'codigo_saldo' => 't1.codigo_saldo'    
    );    

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        //'codigo' => 't1.codigo'       
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
       
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
        FROM [".$bbddSql."].[dbo].[clientesDirecRutasClayma] AS t1  
        $sqlJoins      
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
       // die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");

        return array(
            'error' => '"<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',       
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
    
}

function cargarClientesClayma($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order,$filtrosLike = array(), $joins= array())
{


    $camposPermitidos = array(
        'codigo_saldo' => 't1.codigo_saldo',
        'codigo' => 't1.codigo',
        'nombre_empresa' => 't1.nombre_empresa',
        'nombre_franqueo' => 't1.nombre_franqueo',
        'subcliente' => 't1.subcliente',
        'direccion' => 't1.direccion',
        'localidad' => 't1.localidad',
        'provincia' => 't1.provincia',
        'codigo_postal' => 't1.codigo_postal',
        'nif_subcliente' => 't1.nif_subcliente',
        'nif' => 't1.nif',
        'comercial' => 't1.comercial',
        'forma_pago' => 't1.forma_pago',
        'tipo_listado' => 't1.tipo_listado',
        'tipo_factura' => 't1.tipo_factura',
        'fecha_alta' => 't1.fecha_alta',
        'provision_inicial' => 't1.provision_inicial',
        'conductor' => 't1.conductor',
        'pais' => 't1.pais',
        'codigoPais' => 't1.codigoPais',
        'activo' => 't1.activo',
        'dias_de_pago' => 't1.dias_de_pago',
        'envio_att' => 't1.envio_att',
        'envio_nombre' => 't1.envio_nombre',
        'envio_domicilio' => 't1.envio_domicilio',
        'envio_cp' => 't1.envio_cp',
        'envio_poblacion' => 't1.envio_poblacion',
        'envio_provincia' => 't1.envio_provincia',
        'envio_pais' => 't1.envio_pais',
        'retener' => 't1.retener',
        'domiciliada' => 't1.domiciliada',
        'inactiva_permanente' => 't1.inactiva_permanente',
        'inactiva_problemas' => 't1.inactiva_problemas',
        'inactiva_inactividad' => 't1.inactiva_inactividad',
        'importePF' => 't1.importePF',
        'fechaCobroPF' => 't1.fechaCobroPF',
        'fechaCuadrePF' => 't1.fechaCuadrePF',
        'imformacionCuadrePF' => 't1.imformacionCuadrePF',
        'idComercial' => 't1.idComercial',
        'idFormaPago' => 't1.idFormaPago',
        'idFormaPagoFranqueo' => 't1.idFormaPagoFranqueo',
        'email' => 't1.email',       
        'fac_cuotaRecogida' => 't1.fac_cuotaRecogida',
        'fac_idPeriodo' => 't1.fac_idPeriodo',
        'fac_porCientoNoBonificable' => 't1.fac_porCientoNoBonificable',
        'fac_otrosConceptosFijos' => 't1.fac_otrosConceptosFijos',
        'fac_importeFijoOtrosConcepto' => 't1.fac_importeFijoOtrosConcepto',
        'fac_idProvisionFondos' => 't1.fac_idProvisionFondos',
        'fac_cobroUnitarioEnvio' => 't1.fac_cobroUnitarioEnvio',
        'fac_pfFijaImporte' => 't1.fac_pfFijaImporte',
        'idDiasDePago' => 't1.idDiasDePago',
        'numCuentaBanco' => 't1.numCuentaBanco',
        'correoDiario' => 't1.correoDiario',
        'nuestraCuenta' => 't1.nuestraCuenta',
        'sinIva' => 't1.sinIva',
        'retencion' => 't1.retencion',
        'pedidoCliente' => 't1.pedidoCliente',
        'vencimiento' => 't1.vencimiento',        
        'prefactura' => 't1.prefactura',       
        'noAplicarPF' => 't1.noAplicarPF',
        'saldoTotal' => 'sum(t1.importePF) as saldoTotal',
        'importeFijoTotal' => 'sum(t1.fac_pfFijaImporte) as importeFijoTotal',
        'fechaObservacion' => 't3.fecha',
        'idObservacion' => 't3.id',
        'observacion' => 't3.observacion'     
       
    );

    //t3: clientesObservaciones

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        //'tabla2' => "inner join [".$bbddSql."].[dbo].[franqueoTipos] as t2 on t1.codigo = t2.idCliente",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[clientesObservacionesClayma] as t3 on t1.codigo = t3.idCliente"
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['activo'])) {
        $condicion[] = 't1.activo = ?';
        $params[] = $filtros['activo'];
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 't1.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }
    if (isset($filtros['codigo'])) {
        $condicion[] = 't1.codigo = ?';
        $params[] = $filtros['codigo'];
    }
    if (isset($filtros['subcliente'])) {
        $condicion[] = 't1.subcliente = ?';
        $params[] = $filtros['subcliente'];
    }
    if (isset($filtros['retener'])) {
        $condicion[] = 't1.retener = ?';
        $params[] = $filtros['retener'];
    }
    if (isset($filtros['correoDiario'])) {
        $condicion[] = 't1.correoDiario = ?';
        $params[] = $filtros['correoDiario'];
    }
    if (isset($filtros['fac_idPeriodo'])) {
        $condicion[] = 't1.fac_idPeriodo = ?';
        $params[] = $filtros['fac_idPeriodo'];
    }
    if (isset($filtros['fac_idProvisionFondos'])) {
        $condicion[] = 't1.fac_idProvisionFondos = ?';
        $params[] = $filtros['fac_idProvisionFondos'];
    }
    if (isset($filtros['nombre_empresa'])) {
        $condicion[] = 't1.nombre_empresa = ?';
        $params[] = $filtros['nombre_empresa'];
    }
    if (isset($filtros['nombre_franqueo'])) {
        $condicion[] = 't1.nombre_franqueo = ?';
        $params[] = $filtros['nombre_franqueo'];
    }
    if (isset($filtros['asunto'])) {
        $condicion[] = 't3.asunto = ?';
        $params[] = $filtros['asunto'];
    }


    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'IN');

    $camposComparablesPermitidos = array(
    'codigo_saldo' => 't1.codigo_saldo',
    'codigo' => 't1.codigo' ,
    'idObservacion' => 't3.id'   
    );

     if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // ---------- IN CON SUBCONSULTA  solo para max(id) de observaciones----------
            if (
                    isset($f['campo1'], $f['operador'], $f['tipoSubconsulta']) &&
                    isset($camposComparablesPermitidos[$f['campo1']]) &&
                    strtoupper($f['operador']) == 'IN' && 
                    $f['tipoSubconsulta'] == 'ultimaObservacionEnvioFacturas'
            ) {
                     $condicion[] =
                        $camposComparablesPermitidos[$f['campo1']] . "
                        IN (
                            SELECT MAX(id)
                            FROM [".$bbddSql."].[dbo].[clientesObservacionesClayma]
                            WHERE asunto = 'Envio Facturas'
                            GROUP BY idCliente
                        )";
            }
            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'codigo' => 't1.codigo',
        'nombre_empresa' => 't1.nombre_empresa',
        'nombre_franqueo' => 't1.nombre_franqueo',
        'direccion' => 't1.direccion',
        'codigoSidi' => 't1.codigoSidi',
        'localidad' => 't1.localidad',
        'nif' => 't1.nif',
        'subcliente' => 't1.subcliente',
        'codigo_postal' => 't1.codigo_postal',
        'fecha' => 't3.fecha',
        'observacion' => 't3.observacion'
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre_empresa' => 't1.nombre_empresa',
        'subcliente' => 't1.subcliente',
        'nombre_franqueo' => 't1.nombre_franqueo',
        'codigo' => 't1.codigo',
        'codigo_saldo' => 't1.codigo_saldo',
        'codigo_postal' => 't1.codigo_postal',
        'direccion' => 't1.direccion',
        'localidad' => 't1.localidad',
        'observacion' => 't3.observacion'      
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
        FROM [".$bbddSql."].[dbo].[clientesClayma] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarPresupuestos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'presupuesto' => 't1.presupuesto',
        'letra' => 't1.letra',
        'cliente' => 't1.cliente',
        'codigoCliente' => 't1.codigoCliente',
        'persona' => 't1.persona',
        'direccion' => 't1.direccion',
        'poblacion' => 't1.poblacion',
        'cp' => 't1.cp',
        'pago' => 't1.pago',
        'notaCibeles' => 't1.notaCibeles',
        'forma de pago' => 't1.forma de pago',
        'campana' => 't1.campana',
        'campanaObservacion' => 't1.campanaObservacion',
        'cantidad' => 't1.cantidad',
        'fecha' => 't1.fecha',
        'comercial' => 't1.comercial',
        'pedcli' => 't1.pedcli',
        'fechaAceptacion' => 't1.fechaAceptacion',
        'fechaCompromiso' => 't1.fechaCompromiso',
        'fechaTerminado' => 't1.fechaTerminado',
        'factura' => 't1.factura',
        'detallada' => 't1.detallada',
        'idComercial' => 't1.idComercial',
        'idFormaPago' => 't1.idFormaPago',
        'idVisualizarTotalPresu' => 't1.idVisualizarTotalPresu',
        'idVisualizarTotalFranqueo' => 't1.idVisualizarTotalFranqueo',
        'importeFranqueo' => 't1.importeFranqueo',
        'otBajada' => 't1.otBajada',
        'otAbierta' => 't1.otAbierta',
        'fechaInicioReal' => 't1.fechaInicioReal',
        'noRetrasar' => 't1.noRetrasar',
        'campana2' => 't1.campana2',
        'cantidad2' => 't1.cantidad2',
        'pdfGenerado' => 't1.pdfGenerado',
        'clayma' => 't1.clayma',
        'numNoFactura' => 't1.numNoFactura',
        'numNoFacturaFecha' => 't1.numNoFacturaFecha',
        'noSeFacturaObservaciones' => 't1.noSeFacturaObservaciones',
        'observaciones2' => 't1.observaciones2',
        'noFacProcesado' => 't1.noFacProcesado',
        'bbddBorrado' => 't1.bbddBorrado',
        'fechaAceptacionRegistro' => 't1.fechaAceptacionRegistro',
        'otSidi' => 't1.otSidi',
        'trabajoIniciado' => 't1.trabajoIniciado',
        'numeroFacturaCompletoCibeles' => 't5.numeroFacturaCompleto',
        'numeroFacturaCompletoClayma' => 't6.numeroFacturaCompleto as numeroFacturaCompletoClayma',
        'ultimoPresupuesto' => 'max(presupuesto) as ultimoPresupuesto',
        'inicialComercial' => 't2.inicial as inicialComercial',
        'nombreComercial' => 't2.nombre as nombreComercial',
        'telefonoComercial' => 't2.telefono as telefonoComercial',
        'textoFormaPago' => 't3.concepto as textoFormaPago',
        'ivaFranqueo' => 't4.tipoIva as ivaFranqueo',
        'nombre_franqueo' => 't7.nombre_franqueo',
        'nombre_franqueoClayma' => 't8.nombre_franqueo',
        'anios' => 'distinct(CAST(SUBSTRING(t1.presupuesto, 1, 2) AS INT) + 2000) AS anios'
    );

    //t2: presupuestadores
    //t3: formaDePago
    //t4: totalFranqueoTipos
    //t5: facturacion
    //t6: facturacion clayma
    //t7: clientes
    //t8: clientes Clayma

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[presupuestadores] as t2 on t2.id = t1.idComercial",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[formaDePago] as t3 on t3.id = t1.idFormaPago",
        'tabla4' => "inner join [".$bbddSql."].[dbo].[totalFranqueoTipos] as t4  on t4.id = t1.idVisualizarTotalFranqueo",
        'tabla5' => "left join [".$bbddSql."].[dbo].[facturacion] as t5 on t5.presupuesto = t1.presupuesto",
        'tabla6' => "left join [".$bbddSql."].[dbo].[facturacionClayma] as t6 on t6.presupuesto = t1.presupuesto",
        'tabla7' => "inner join [".$bbddSql."].[dbo].[clientes] as t7 on t7.codigo = t1.codigoCliente",
        'tabla8' => "inner join [".$bbddSql."].[dbo].[clientesClayma] as t8 on t8.codigo = t1.codigoCliente"
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)',
        'anios' => '(CAST(SUBSTRING(t1.presupuesto, 1, 2) AS INT) + 2000)'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'anios' => 'CAST(SUBSTRING(t1.presupuesto, 1, 2) AS INT) + 2000'
        //'subcliente'     => 't1.subcliente'          
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
        FROM [".$bbddSql."].[dbo].[presupuestos] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarPresupuestosConNumFacturas($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $filtrosLike, $order)
{
    $camposPermitidos = array(
        'cliente' => 'tabla.cliente',
        'campana' => 'tabla.campana',
        'numeroFacturaCompleto' => 'tabla.numeroFacturaCompleto',
        'numNoFactura' =>'tabla.numNoFactura',
        'clayma' => 'tabla.clayma',
        'inicialComercial' => 'tabla.inicialComercial',
        'presupuesto' => 'tabla.presupuesto',
        'fecha' => 'tabla.fecha',
        'otBajada' => 'tabla.otBajada',
        'otAbierta' => 'tabla.otAbierta',
        'fechaAceptacion' => 'tabla.fechaAceptacion',
        'fechaCompromiso' => 'tabla.fechaCompromiso',
        'fechaTerminado' => 'tabla.fechaTerminado',
        'activo' => 'tabla.activo',
        'nombreComercial' => 'tabla.nombreComercial',
        'telefonoComercial' => 'tabla.telefonoComercial',
        'presupuesto' => 'tabla.presupuesto',
        'letra' => 'tabla.letra',
		'notaCibeles' => 'tabla.notaCibeles',
		'campana2' => 'tabla.campana2',
		'fechaInicioReal' => 'tabla.fechaInicioReal',
		'cantidad' => 'tabla.cantidad',
		'cantidad2' => 'tabla.cantidad2',
		'noSeFacturaObservaciones' => 'tabla.noSeFacturaObservaciones',
        'observaciones2' => 'observaciones2'
    );
   

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [

    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 'tabla.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['otBajada'])) {
        $condicion[] = 'tabla.otBajada = ?';
        $params[] = $filtros['otBajada'];
    }
    if (isset($filtros['otAbierta'])) {
        $condicion[] = 'tabla.otAbierta = ?';
        $params[] = $filtros['otAbierta'];
    }
    
    if (isset($filtros['sinFecha']) && $filtros['sinFecha'] == 1) {
        $condicion[] = '(tabla.fechaInicioReal IS NULL OR tabla.fechaTerminado IS NULL)';
    }
    
    //FILTROS CON OPERADORES
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    'fecha' => 'tabla.fecha',
    'fechaInicioReal' => 'tabla.fechaInicioReal'   
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'presupuesto' => 'tabla.presupuesto',
        'cliente' => 'tabla.cliente',
        'campana' => 'tabla.campana'
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'presupuesto' => 'tabla.presupuesto',
        'cliente' => 'tabla.cliente',
        'campana' => 'tabla.campana',
        'fecha' => 'tabla.fecha',
        'fechaAceptacion' => 'tabla.fechaAceptacion',
        'nombreComercial' => 'tabla.nombreComercial',
        'origen' => 'tabla.origen',
        'fechaInicioReal' => 'tabla.fechaInicioReal'       
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
    FROM (
        SELECT
            t1.*,
            t2.nombre AS nombreComercial,
            t2.telefono AS telefonoComercial,
            t2.inicial AS inicialComercial,
            t3.concepto AS textoFormaPago,
            t4.tipoIva AS ivaFranqueo,
            ISNULL(t6.numeroFacturaCompleto,null) as numeroFacturaCompleto,           
            t5.nuestraCuenta,
            t5.codigo_saldo,
            t5.activo,
            t5.codigo

        FROM [".$bbddSql."].[dbo].[presupuestos] AS t1

        INNER JOIN [".$bbddSql."].[dbo].[presupuestadores] AS t2
        ON t2.id = t1.idComercial

        INNER JOIN [".$bbddSql."].[dbo].[formaDePago] AS t3
        ON t3.id = t1.idFormaPago

        INNER JOIN [".$bbddSql."].[dbo].[totalFranqueoTipos] AS t4
        ON t4.id = t1.idVisualizarTotalFranqueo

        LEFT JOIN [".$bbddSql."].[dbo].[clientes] AS t5
        ON t5.subcliente = t1.cliente

       LEFT JOIN [".$bbddSql."].[dbo].[facturacion] as t6 
        on t6.presupuesto = t1.presupuesto

        WHERE t1.clayma = 0

        UNION

        SELECT
            t1.*,
            t2.nombre AS nombreComercial,
            t2.telefono AS telefonoComercial,
            t2.inicial AS inicialComercial,
            t3.concepto AS textoFormaPago,
            t4.tipoIva AS ivaFranqueo,
            ISNULL(t6.numeroFacturaCompleto,null) as numeroFacturaCompleto,
            t5.nuestraCuenta,
            t5.codigo_saldo,
            t5.activo,
            t5.codigo

        FROM [".$bbddSql."].[dbo].[presupuestos] AS t1

        INNER JOIN [".$bbddSql."].[dbo].[presupuestadores] AS t2
        ON t2.id = t1.idComercial

        INNER JOIN [".$bbddSql."].[dbo].[formaDePago] AS t3
        ON t3.id = t1.idFormaPago

        INNER JOIN [".$bbddSql."].[dbo].[totalFranqueoTipos] AS t4
        ON t4.id = t1.idVisualizarTotalFranqueo

        LEFT JOIN [".$bbddSql."].[dbo].[clientesClayma] AS t5
        ON t5.subcliente = t1.cliente

        LEFT JOIN [".$bbddSql."].[dbo].[facturacionClayma] as t6
  on t6.presupuesto = t1.presupuesto 

        WHERE t1.clayma = 1

    ) AS tabla
     
    $sqlJoins
    $sqlWhere
    $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarDetallesPresupuesto($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'presupuesto' => 't1.presupuesto',
        'concepto' => 't1.concepto',
        'grupo' => 't1.grupo',
        'unidades' => 't1.unidades',
        'unidades2' => 't1.unidades2',
        'precio' => 't1.precio',
        'descripcion' => 't1.descripcion',
        'notaCibeles' => 't1.notaCibeles',
        'orden' => 't1.orden',
        'idConcepto' => 't1.idConcepto',
        'idTipo' => 't1.idTipo',
        'idDepartamento' => 't1.idDepartamento',
        'notaAdmonProd' => 't1.notaAdmonProd',
        'exentoIVA' => 't1.exentoIVA',
        'idMaterialPapel' => 't1.idMaterialPapel',
        'idTipoImpresora' => 't1.idTipoImpresora',
        'impresionNumeroCaras' => 't1.impresionNumeroCaras',
        'idPapelTamanioFinal' => 't1.idPapelTamanioFinal',
        'pesoGramos' => 't1.pesoGramos',
        'idGFConcepto' => 't1.idGFConcepto',
        'idGFMetrosCuadrados' => 't1.idGFMetrosCuadrados',
        'noVisible' => 't1.noVisible',
        'proceso' => 't3.proceso',
        'tipoProceso' => 't2.tipoProceso',        
        'departamento' => 't4.departamento',
        'departamentoDistinct' => 'distinct(t4.departamento)',
        'tamanoFinal' => 't11.tamano as tamanoFinal',
        'tipo' => 't7.tipo',
        'tamano' => 't6.tamano',        
        'gramaje' => 't9.gramaje',
        'precioMaterialPapel' => 't5.precio as precioMaterialPapel',
        'tipoImpresora' => 't10.tipoImpresora',
        'gfTipoProceso' => 't14.[nombreConcepto] as gfTipoProceso',
        'gfMaterial' => 't13.[nombreSubconcepto] as gfMaterial',
        'gfConcepto' => 't12.[nombreSubconcepto2] as gfConcepto',
        'gfCoste' => 't12.[coste] as gfCoste'
     
    );

    //t2: procesosTipos
    //t3: procesos
    //t4: procesosDepartamento
    //t5: tarifas_papel
    //t6: L_papelTamanio
    //t7: L_papelTipo
    //t8: L_papelAcabado
    //t9: L_papelGramaje
    //t10: L_impresorasTipo
    //t11: L_papelTamanio
    //t12: L_gf_subconcepto2
    //t13: L_gf_subconcepto1
    //t14: L_gf_concepto

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [        
        'tabla2' => "inner join [".$bbddSql."].[dbo].[procesosTipos] as t2 on t1.idTipo = t2.id", 
        'tabla3' => "inner join  [".$bbddSql."].[dbo].[procesos] as t3 on t1.idConcepto = t3.id",
        //'tabla4' => "inner join  [".$bbddSql."].[dbo].[procesosDepartamento] as t4 on t4.id = t1.idDepartamento",
        'tabla5' => "left join [".$bbddSql."].[dbo].tarifas_papel as t5 on t1.idMaterialPapel = t5.id",
		'tabla6' => "left join [".$bbddSql."].[dbo].[L_papelTamanio] as t6 on t6.id = t5.idTamanio",
		'tabla7' => "left join [".$bbddSql."].[dbo].[L_papelTipo] as t7 on t7.id = t5.idTipo",
		'tabla8' => "left join [".$bbddSql."].[dbo].[L_papelAcabado] as t8 on t8.id = t5.idAcabado",
		'tabla9' => "left join [".$bbddSql."].[dbo].[L_papelGramaje] as t9 on t9.id = t5.idGramaje",
		'tabla10' => "left join [".$bbddSql."].[dbo].[L_impresorasTipo] as t10 on t10.id = t1.idTipoImpresora",
		'tabla11' => "left join [".$bbddSql."].[dbo].[L_papelTamanio] as t11 on t11.id = t1.idPapelTamanioFinal",
		'tabla12' => "left join [".$bbddSql."].[dbo].[L_gf_subconcepto2] as t12 on t12.id = t1.idGFConcepto",
		'tabla13' => "left join [".$bbddSql."].[dbo].[L_gf_subconcepto1] as t13 on t12.idSubconcepto1 = t13.id",
		'tabla14' => "left join [".$bbddSql."].[dbo].[L_gf_concepto] as t14 on t13.idConcepto = t14.id"	
        ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['tamano_id'])) {
        $condicion[] = 't6.id = ?';
        $params[] = $filtros['tamano_id'];
    }
    if (isset($filtros['tipo_id'])) {
        $condicion[] = 't7.id = ?';
        $params[] = $filtros['tipo_id'];
    }
    if (isset($filtros['acabado_id'])) {
        $condicion[] = 't8.id = ?';
        $params[] = $filtros['acabado_id'];
    }
     if (isset($filtros['gramaje_id'])) {
        $condicion[] = 't9.id = ?';
        $params[] = $filtros['gramaje_id'];
    }


    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    //'codigo_saldo' => 't1.codigo_saldo',
    //'codigo' => 't1.codigo'    
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'ordenTipoProceso' => 't2.orden',
        'idTipo' => 't1.idTipo',
        'orden' => ' t1.orden',
        'departamento' => 't4.departamento'        
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
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] AS t1
        inner join [".$bbddSql."].[dbo].procesosTipos as t2
        on t1.idTipo = t2.id
        inner join [".$bbddSql."].[dbo].procesos as t3
        on t1.idConcepto = t3.id
        inner join [".$bbddSql."].[dbo].procesosDepartamento as t4
        on t1.idDepartamento = t4.id
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   


    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarProvisionDeFondos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'presupuesto' => 't1.presupuesto',
        'contador' => 't1.contador',
        'contadorMax' => 'isnull(max(t1.contador),0) as contadorMax',
        'idCliente' => 't1.idCliente',
        'importe' => 't1.importe',
        'fechaCreacion' => 't1.fechaCreacion',
        'tipo' => 't1.tipo',
        'cobrada' => 't1.cobrada',
        'fechaCobro' => 't1.fechaCobro',
        'formaPago' => 't1.formaPago',
        'facCompletaAplicada' => 't1.facCompletaAplicada',
        'numFacturaAplicada' => 't1.numFacturaAplicada',
        'clayma' => 't1.clayma',
        'borradaComercial' => 't1.borradaComercial',
        'numFacturaAplicadaAnio' => 't1.numFacturaAplicadaAnio',
        'concepto' => 't1.concepto',
        'tipoTexto' => 't2.tipo as tipoTexto',
        'cobradaNombre' => 't3.cobrada as cobradaNombre',
        //'ultimoPresupuesto' => 'max(t1.presupuesto) as ultimoPresupuesto',
        'proximoPresupuestoManual' => 'max(t1.presupuesto)+1  as proximoPresupuestoManual',
        'nombre_empresa' => 't5.nombre_empresa',
        'direccion' => 't5.direccion',
        'codigo_postal' => 't5.codigo_postal',
        'localidad' => 't5.localidad',
        'provincia' => 't5.provincia',
        'nif' => 't5.nif',
        'campana' => 't4.campana',
        'conceptoCampana' => 't1.concepto as campana',
        'nombre_empresaClayma' => 't6.nombre_empresa',
        'direccionClayma' => 't6.direccion',
        'codigo_postalClayma' => 't6.codigo_postal',
        'localidadClayma' => 't6.localidad',
        'provinciaClayma' => 't6.provincia',
        'nifClayma' => 't6.nif'
    );

    //t2: provisionesDeFondo_tipos
    //t3: provisionesDeFondo_tipoCobrada
    //t4: presupuestos
    //t5: clientes
    //t6: clientesClayma

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        //'tabla5' => "left join [".$bbddSql."].[dbo].[facturacion] as t5 on t5.presupuesto = t1.presupuesto",
        //'tabla6' => "left join [".$bbddSql."].[dbo].[facturacionClayma] as t6 on t6.presupuesto = t1.presupuesto"
        'tabla4' => "inner join [".$bbddSql."].[dbo].[presupuestos] as t4 on t1.presupuesto=t4.presupuesto",
        'tabla5' => "inner join [".$bbddSql."].[dbo].[clientes] as t5 on t1.idCliente = t5.codigo_saldo",
        'tabla6' => "inner join [".$bbddSql."].[dbo].[clientesClayma] as t6 on t1.idCliente = t6.codigo_saldo"
        ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['cobrada'])) {
        $condicion[] = 't1.cobrada = ?';
        $params[] = $filtros['cobrada'];
    }
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'LIKE');

    $camposComparablesPermitidos = array(
        'presupuesto' => 't1.presupuesto',
        'codigo_saldo' => 't5.codigo_saldo',
        'codigo' => 't5.codigo',
        'codigo_saldoClayma' => 't6.codigo_saldo',
        'codigoClayma' => 't6.codigo',
        'fechaCreacion' => 't1.fechaCreacion'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    //-----------GROUP BY-------------
    $camposGroupPermitidos = array(
        'presupuesto' => 't1.presupuesto'
       
    );

    $sqlGroup = '';

    if (!empty($group) && is_array($group)) {

        $groups = array();

        foreach ($group as $g) {

            if (isset($camposGroupPermitidos[$g])) {
                $groups[] = $camposGroupPermitidos[$g];
            }
        }

        if (!empty($groups)) {
            $sqlGroup = ' GROUP BY ' . implode(', ', $groups);
        }
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        //'nombre_empresa'     => 't1.nombre_empresa',
        //'subcliente'     => 't1.subcliente'          
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
        FROM [".$bbddSql."].[dbo].[provisionesDeFondo] AS t1
        inner join [".$bbddSql."].[dbo].[provisionesDeFondo_tipos] as t2
        on t1.tipo = t2.id
        inner join [".$bbddSql."].[dbo].[provisionesDeFondo_tipoCobrada] as t3
        on t1.cobrada = t3.id
        $sqlJoins        
        $sqlWhere       
        $sqlGroup
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
       die(
            "<pre>" .
            print_r(sqlsrv_errors(), true) .
            "\nConsulta:\n" . $consulta .
            "\nParametros:\n" . print_r($params, true) .
            "</pre>"
        );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
    'params' => $params
    );
}

function cargarProvisionDeFondos_Todo($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $filtrosLike, $order)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(
        'fechaCreacion' => 'tabla.fechaCreacion',
        'presupuesto' => 'tabla.presupuesto',
        'codigo' => 'tabla.codigo',
        'subcliente' => 'tabla.subcliente',
        'nombre_empresa' => 'tabla.nombre_empresa',
        'campana' => 'tabla.campana',

        'importe' => 'tabla.importe',
        'cobradaNombre' => 'tabla.cobradaNombre',
        'tipoNombre' => 'tabla.tipoNombre',
        'fechaCobro' => 'tabla.fechaCobro',
        'formaPago' => 'tabla.formaPago',
        'cobrada' => 'tabla.cobrada',
        'id' => 'tabla.id',
        'importeTotal' => 'sum(importe) as importeTotal' ,
        'noAplicarPF' => 'tabla.noAplicarPF',
        'contador' => 'tabla.contador',
        'clayma' => 'tabla.clayma',
        'nombre_franqueo' => 'tabla.nombre_franqueo',        
        'tipo' => 'tabla.tipo'

    );

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => 'cargarProvisionesDeFondo: campos vacios',
            'datos' => array()
        );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'cargarProvisionesDeFondo: campos SQL vacios',
            'datos' => array()
        );
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();
   

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 'tabla.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['codigo'])) {
        $condicion[] = 'tabla.codigo = ?';
        $params[] = $filtros['codigo'];
    }

    if (isset($filtros['nombre_empresa'])) {
        $condicion[] = 'tabla.nombre_empresa = ?';
        $params[] = $filtros['nombre_empresa'];
    }    

    if (isset($filtros['campana'])) {
        $condicion[] = 'tabla.campana = ?';
        $params[] = $filtros['campana'];
    }

    if (isset($filtros['fechaCreacion'])) {
        $condicion[] = 'tabla.fechaCreacion = ?';
        $params[] = $filtros['fechaCreacion'];
    }

    if (isset($filtros['importe'])) {
        $condicion[] = 'tabla.importe = ?';
        $params[] = $filtros['importe'];
    }

    if (isset($filtros['fechaCobro'])) {
        $condicion[] = 'tabla.fechaCobro = ?';
        $params[] = $filtros['fechaCobro'];
    }

    if (isset($filtros['formaPago'])) {
        $condicion[] = 'tabla.formaPago = ?';
        $params[] = $filtros['formaPago'];
    }

    if (isset($filtros['cobrada'])) {
        $condicion[] = 'tabla.cobrada = ?';
        $params[] = $filtros['cobrada'];
    }

    if (isset($filtros['tipo'])) {
        $condicion[] = 'tabla.tipo = ?';
        $params[] = $filtros['tipo'];
    }    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array(
        '=',
        '>',
        '<',
        '>=',
        '<=',
        '!='
    );

    $camposComparablesPermitidos = array(
        'fechaCobro' => 'tabla.fechaCobro',
        'fechaCreacion' => 'tabla.fechaCreacion'     
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // Campo contra campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // Campo contra valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'presupuesto' => 'tabla.presupuesto',
        'codigo' => 'tabla.codigo',
        'nombre_empresa' => 'tabla.nombre_empresa',
        'campana' => 'tabla.campana',
        'fechaCreacion' => 'tabla.fechaCreacion',
        'importe' => 'tabla.importe',
        'fechaCobro' => 'tabla.fechaCobro',
        'formaPago' => 'tabla.formaPago',
        'tipoNombre' => 'tabla.tipoNombre'
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] =
                    $camposLikePermitidos[$f['campo']] . ' LIKE ?';

                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }

    $sqlWhere = '';

    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 'tabla.id',
        'presupuesto' => 'tabla.presupuesto',
        'codigo' => 'tabla.codigo',
        'codigo_saldo' => 'tabla.codigo_saldo',
        'nombre_franqueo' => 'tabla.nombre_franqueo',
        'nombre_empresa' => 'tabla.nombre_empresa',
        'subcliente' => 'tabla.subcliente',
        'campana' => 'tabla.campana',
        'cobradaNombre' => 'tabla.cobradaNombre',
        'tipoNombre' => 'tabla.tipoNombre',
        'importe' => 'tabla.importe',
        'fechaCobro' => 'tabla.fechaCobro',
        'formaPago' => 'tabla.formaPago'
    );

    $sqlOrder = '';

    if (is_array($order) && !empty($order)) {
        $ordenes = array();

        foreach ($order as $o) {
            if (
                isset($o['campo'], $o['dir']) &&
                isset($camposOrdenPermitidos[$o['campo']]) &&
                in_array(strtoupper($o['dir']), array('ASC', 'DESC'))
            ) {
                $ordenes[] =
                    $camposOrdenPermitidos[$o['campo']] . ' ' .
                    strtoupper($o['dir']);
            }
        }

        if (!empty($ordenes)) {
            $sqlOrder = ' ORDER BY ' . implode(', ', $ordenes);
        }
    }

    // ---------- CONSULTA ----------
    $consulta = "
        SELECT $listaCampos
        FROM
        (
            SELECT
                t1.*,
                t3.nombre_franqueo,
                t2.campana,
                t3.codigo,
                t4.cobrada AS cobradaNombre,
                t5.tipo AS tipoNombre,
                t3.subcliente,
                t3.nombre_empresa,
                t3.codigo_saldo,
                t3.noAplicarPF
            FROM [".$bbddSql."].[dbo].[provisionesDeFondo] AS t1

            INNER JOIN [".$bbddSql."].[dbo].[presupuestos] AS t2
                ON t1.presupuesto = t2.presupuesto

            INNER JOIN [".$bbddSql."].[dbo].[clientes] AS t3
                ON t2.cliente = t3.nombre_empresa

            INNER JOIN [".$bbddSql."].[dbo].[provisionesDeFondo_tipoCobrada] AS t4
                ON t1.cobrada = t4.id

            INNER JOIN [".$bbddSql."].[dbo].[provisionesDeFondo_tipos] AS t5
                ON t5.id = t1.tipo

            WHERE t2.clayma = 0

            UNION

            SELECT
                t1.*,
                t3.nombre_franqueo,
                t2.campana,
                t3.codigo,
                t4.cobrada AS cobradaNombre,
                t5.tipo AS tipoNombre,
                t3.subcliente,
                t3.nombre_empresa,
                t3.codigo_saldo,
                t3.noAplicarPF
            FROM [".$bbddSql."].[dbo].[provisionesDeFondo] AS t1

            INNER JOIN [".$bbddSql."].[dbo].[presupuestos] AS t2
                ON t1.presupuesto = t2.presupuesto

            INNER JOIN [".$bbddSql."].[dbo].[clientesClayma] AS t3
                ON t2.cliente = t3.nombre_empresa

            INNER JOIN [".$bbddSql."].[dbo].[provisionesDeFondo_tipoCobrada] AS t4
                ON t1.cobrada = t4.id

            INNER JOIN [".$bbddSql."].[dbo].[provisionesDeFondo_tipos] AS t5
                ON t5.id = t1.tipo

            WHERE t2.clayma = 1

            UNION

            SELECT
                t1.*,
                t3.nombre_franqueo,
                t1.concepto AS campana,
                t3.codigo,
                t4.cobrada AS cobradaNombre,
                t5.tipo AS tipoNombre,
                t3.subcliente,
                t3.nombre_empresa,
                t3.codigo_saldo,
                t3.noAplicarPF
            FROM [".$bbddSql."].[dbo].[provisionesDeFondo] AS t1

            INNER JOIN [".$bbddSql."].[dbo].[clientes] AS t3
                ON t1.idCliente = t3.codigo

            INNER JOIN [".$bbddSql."].[dbo].[provisionesDeFondo_tipoCobrada] AS t4
                ON t1.cobrada = t4.id

            INNER JOIN [".$bbddSql."].[dbo].[provisionesDeFondo_tipos] AS t5
                ON t5.id = t1.tipo

            WHERE t1.presupuesto LIKE '9%'
        ) AS tabla

        $sqlWhere
        $sqlOrder
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'datos' => array(),
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();

    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
}

function verIdPapel($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'presupuesto' => 't1.presupuesto',
        'contador' => 't1.contador',
        'idCliente' => 't1.idCliente',
        'importe' => 't1.importe',
        'fechaCreacion' => 't1.fechaCreacion',
        'tipo' => 't1.tipo',
        'cobrada' => 't1.cobrada',
        'fechaCobro' => 't1.fechaCobro',
        'formaPago' => 't1.formaPago',
        'facCompletaAplicada' => 't1.facCompletaAplicada',
        'numFacturaAplicada' => 't1.numFacturaAplicada',
        'clayma' => 't1.clayma',
        'borradaComercial' => 't1.borradaComercial',
        'numFacturaAplicadaAnio' => 't1.numFacturaAplicadaAnio',
        'concepto' => 't1.concepto',
        'tipoTexto' => 't2.tipo as tipoTexto',
        'cobradaNombre' => 't3.cobrada as cobradaNombre'
    );

    //t2: provisionesDeFondo_tipos
    //t3: provisionesDeFondo_tipoCobrada

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        //'tabla5' => "left join [".$bbddSql."].[dbo].[facturacion] as t5 on t5.presupuesto = t1.presupuesto",
        //'tabla6' => "left join [".$bbddSql."].[dbo].[facturacionClayma] as t6 on t6.presupuesto = t1.presupuesto"
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idTamanio'])) {
        $condicion[] = 't1.idTamanio = ?';
        $params[] = $filtros['idTamanio'];
    }
    if (isset($filtros['idTipo'])) {
        $condicion[] = 't1.idTipo = ?';
        $params[] = $filtros['idTipo'];
    }


    if (isset($filtros['idAcabado'])) {
        $condicion[] = 't1.idAcabado = ?';
        $params[] = $filtros['idAcabado'];
    }
    if (isset($filtros['idGramaje'])) {
        $condicion[] = 't1.idGramaje = ?';
        $params[] = $filtros['idGramaje'];
    }
    if (isset($filtros['precio'])) {
        $condicion[] = 't1.precio = ?';
        $params[] = $filtros['precio'];
    }
    if (isset($filtros['fecha'])) {
        $condicion[] = 't1.fecha = ?';
        $params[] = $filtros['fecha'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'codigo_saldo' => 't1.codigo_saldo',
    //'codigo' => 't1.codigo'    
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        //'nombre_empresa'     => 't1.nombre_empresa',
        //'subcliente'     => 't1.subcliente'          
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
        FROM [".$bbddSql."].[dbo].[tarifas_papel] AS t1        
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarTarifasFranqueo($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order,$anioSeleccionado,$cantidad=0)
{
    $cantidad = floatval($cantidad);
    
    $camposPermitidos = array(
        'gramos' => 't1.gramos',
        'tipos' => 't1.tipos',
        'importe_cantidadIndicada' => '(t1.precioNeto + t1.iva) * '.$cantidad.' as importe',
        'importeSinIva_cantidadIndicada' => 't1.precioNeto * '.$cantidad.' as importeSinIva',
        'titulo' => 't2.titulo',
        'precioNeto' => 't1.precioNeto',
        'iva' => 't1.iva'
    );

    //t2: tarifasProductos
    

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[tarifasProductos] as t2 on t2.id = t1.idTarifasProducto",
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['idProductoPadre'])) {
        $condicion[] = 't2.idProductoPadre = ?';
        $params[] = $filtros['idProductoPadre'];
    }
    if (isset($filtros['tipos'])) {
        $condicion[] = 't1.tipos = ?';
        $params[] = $filtros['tipos'];
    }
    if (isset($filtros['tipos_acuseRecibo'])) {
        $condicion[] = 't1.id= (SELECT idAcuseRecibo FROM ['.$bbddSql.'].[dbo].[tarifas'.$anioSeleccionado.'] where tipos = ? )';
        $params[] = $filtros['tipos_acuseRecibo'];
    }
    if (isset($filtros['tipos_PEE'])) {
        $condicion[] = 't1.id= (SELECT idPEE FROM ['.$bbddSql.'].[dbo].[tarifas'.$anioSeleccionado.'] where tipos = ? )';
        $params[] = $filtros['tipos_PEE'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idTarifasProducto'])) {
        $condicion[] = 't1.idTarifasProducto = ?';
        $params[] = $filtros['idTarifasProducto'];
    }
    if (isset($filtros['gramos'])) {
        $condicion[] = 't1.gramos = ?';
        $params[] = $filtros['gramos'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    //'codigo' => 't1.codigo'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'gramos'     => 't1.gramos',
        'orden_Producto'     => 't2.orden'          
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
        FROM [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarFranqueo($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $group=array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'referencia' => 't1.referencia',
        'idCliente' => 't1.idCliente',        
        'ot' => 't1.ot',
        'otSidi' => 't1.otSidi',
        'importe' => 't1.importe',
        'envios' => 't1.envios',
        'detalle' => 't1.detalle',
        'anadidos' => 't1.anadidos',
        'fecha' => 't1.fecha',
        'comprobado' => 't1.comprobado',
        'nombre_franqueo' => 't2.nombre_franqueo',
        'codigo_saldo' => 't2.codigo_saldo',
        'codigoSidi' => 't2.codigoSidi',
        'nombreProducto' => 't3.producto as nombreProducto',        
        'numAlbaranes1' => '1 as numAlbaranes1',
        'idProducto' => 't1.producto as idProducto',
        'importeTotal' => 'sum(t1.importe) as importeTotal',
        'contarNumAlbaranes' => 'count(t1.id) as contarNumAlbaranes',
        'enviosTotal' => 'sum(t1.envios) as enviosTotal',
        'totalProductoFecha' => '(SELECT SUM(importe)
                         FROM ['.$bbddSql.'].[dbo].[franqueo]
                         WHERE producto = ?
                         AND fecha = ?) AS total'
    );

    //t2: clientes
    //t3: tarifasProductoPadre   
    

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();
    $paramsCampos = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];

            if ($campo == 'totalProductoFecha') {
                $paramsCampos[] = isset($filtros['producto']) ? $filtros['producto'] : '';
                $paramsCampos[] = isset($filtros['fecha']) ? $filtros['fecha'] : '';
            }
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCliente = t2.codigo",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t3 on t1.producto = t3.id"    
        ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = $paramsCampos;   
    
    if (isset($filtros['fecha'])) {
        $condicion[] = 't1.fecha = ?';
        $params[] = $filtros['fecha'];
    }
    if (isset($filtros['producto'])) {
        $condicion[] = 't1.producto = ?';
        $params[] = $filtros['producto'];
    }
    if (isset($filtros['referencia'])) {
        $condicion[] = 't1.referencia = ?';
        $params[] = $filtros['referencia'];
    }
    if (isset($filtros['comprobado'])) {
        $condicion[] = 't1.comprobado = ?';
        $params[] = $filtros['comprobado'];
    }
    if (isset($filtros['idEmpleado_Por_referencia'])) {
        $condicion[] = 'SUBSTRING(t1.referencia,11, 2) = ?';
        $params[] = $filtros['idEmpleado_Por_referencia'];
    }
    if (isset($filtros['verUltimaReferenciaPorUsuario'])) {
        $condicion[] = 'id=(select max(id) FROM ['.$bbddSql.'].[dbo].[franqueo] where SUBSTRING(referencia,11, 2) = ?)';
        $params[] = $filtros['verUltimaReferenciaPorUsuario'];
    }




    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
        'comprobado' => 't1.comprobado'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- GROUP BY ----------
    $camposGroupPermitidos = array(
        'nombre_franqueo' => 't2.nombre_franqueo',
        'nombreProducto' => 't3.producto'
    );

    $sqlGroup = '';

    if (is_array($group) && !empty($group)) {
        $groups = array();

        foreach ($group as $g) {
            if (isset($camposGroupPermitidos[$g])) {
                $groups[] = $camposGroupPermitidos[$g];
            }
        }

        if (!empty($groups)) {
            $sqlGroup = ' GROUP BY ' . implode(', ', $groups);
        }
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
       'id' => 't1.id',
       'fecha' => 't1.fecha',
       'nombre_franqueo' => 't2.nombre_franqueo',
       'idCliente' => 't1.idCliente'
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
        FROM [".$bbddSql."].[dbo].[franqueo] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
            return array(
            'error' => '<pre>' . print_r(sqlsrv_errors(), true) . '</pre>',
            'sql' => $consulta,
            'params' => $params
            );   
            //die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarFranqueoTipos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $group, $order, $anioTarifas = '',$filtrosLike=array())
{
    $camposPermitidos = array(
        'idCliente' => 't1.idCliente',
        'importeTotal' => 'sum(t1.importe) as importeTotal',
        'unidadesTotal' => 'sum(t1.unidades) as unidadesTotal',
        'codigo_saldo' => 't2.codigo_saldo',
        'id' => 't1.id',
		'unidades' => 't1.unidades',
		'importe' => 't1.importe',
		'tarifa' => 't3.precioNeto + t3.iva as tarifa',
		'idTarifa' => 't3.id as idTarifa',
        'idCliente' => 't1.idCliente',
		'fecha' => 't1.fecha',
		'ot' => 't1.ot',
		'otSidi' => 't1.otSidi',
        'comprobado' => 't1.comprobado',
        'descripcionTarifaLeft' => 't4.descripcion',
        'tituloTarifasProducto' => 't5.titulo',
        'gramosTarifaLeft' => 't4.gramos',
        'anadidos2' => 't8.anadidos',
        'idSIDI' => 't7.idSIDI',
        'codigoSidi' => 'substring(t2.codigoSidi,3,8) as codigoSidi',
        'importeSidi' => 'cast(t1.importe*100 as int) as importeSidi',
        'numSeguimiento' => 't1.numSeguimiento',
        'nombre' => 't1.nombre',
        'direccion' => 't1.direccion',
        'poblacion' => 't1.poblacion',
        'cp' => 't1.cp',
        'gramos_SIDI' => 't3.gramos_SIDI',        
        'tipo' => 't1.tipo',
        'referencia' => 't1.referencia',        
        'txt' => 't1.txt',
        'nombre_empresa' => 't2.nombre_empresa',
        'direccionCliente' => 't2.direccion as direccionCliente',
        'localidadCliente' => 't2.localidad as localidadCliente',
        'provinciaCliente' => 't2.provincia as provinciaCliente',
        'cpCliente' => 't2.codigo_postal as cpCliente',
        'gramos' => 't3.gramos',
        'tituloTarifasProducto_inner' => 't6.titulo',
        'producto_Padre' => 't7.producto as producto',
        'gramosTipo' => 't1.gramos',
        'aniosDistintos' => 'DISTINCT YEAR(t1.fecha) AS aniosDistintos',
        'producto_Padre_left' => 't10.producto',
        'idProductoPadre_left' => 't11.idProductoPadre',
        'tituloTarifasProducto2' => 't11.titulo',
        'nombre_franqueo' => 't2.nombre_franqueo'
       
      
    );

    //t2: clientes
    //t3: tarifas. $anio: en $filtros debe estar la referencia
    //t4: tarifas (left): en $filtros debe estar la referencia
    //t5: tarifasProductos(left)
    //t6: tarifasProductos
    //t7: tarifasProductoPadre
    //t8: franqueo
    //t9: presupuestos
    //t10: tarifasProductoPadreLeft
    

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    if ($anioTarifas !== '') {
        $anio = $anioTarifas; 
    }
    else
    {
        $anio = date('Y');
    }
    
   

    if (is_array($joins) && in_array('tabla3', $joins) && isset($filtros['referencia']) && strlen($filtros['referencia']) >= 10) {
        $anio = substr($filtros['referencia'], 6, 4);
    }

    $anio = intval($anio);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCliente = t2.codigo",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[tarifas".$anio."] as t3  on t1.tipo = t3.tipos", //para utilizar tabla3 en filtros tiene que estar la referencia
        'tabla4' => "left join [".$bbddSql."].[dbo].[tarifas".$anio."] as t4 on t1.tipo = t4.tipos ", //para utilizar tabla4 en filtros tiene que estar la referencia
        'tabla5' => "left join [".$bbddSql."].[dbo].[tarifasProductos] as t5 on t4.idTarifasProducto = t5.id",
        'tabla6' => "inner join [".$bbddSql."].[dbo].[tarifasProductos] as t6 on t3.idTarifasProducto = t6.id",
        'tabla7' => "inner join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t7 on t7.id = t6.idProductoPadre",
        'tabla8' => "left join [".$bbddSql."].[dbo].[franqueo] as t8 on t8.referencia = t1.referencia",
        'tabla9' => "left join [".$bbddSql."].[dbo].[presupuestos] as t9 on t1.ot  like '%'+t9.presupuesto+'%' and t1.ot!=''",
        'tabla10' => "left join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t10 on t10.id = t11.idProductoPadre",
        'tabla11' => "left join [".$bbddSql."].[dbo].[tarifasProductos] as t11 on t3.idTarifasProducto = t11.id",
        ];


    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();   
    
    if (isset($filtros['referencia'])) {
        $condicion[] = 't1.referencia = ?';
        $params[] = $filtros['referencia'];
    } 
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    } 
    if (isset($filtros['fecha'])) {
        $condicion[] = 't1.fecha = ?';
        $params[] = $filtros['fecha'];
    }
    if (isset($filtros['comprobado'])) {
        $condicion[] = 't1.comprobado = ?';
        $params[] = $filtros['comprobado'];
    }
    if (isset($filtros['importado'])) {
        $condicion[] = 't1.importado = ?';
        $params[] = $filtros['importado'];
    }
    if (isset($filtros['idProductoPadre'])) {
        $condicion[] = 't6.idProductoPadre = ?';
        $params[] = $filtros['idProductoPadre'];
    }
    if (isset($filtros['tipo'])) {
        $condicion[] = 't1.tipo = ?';
        $params[] = $filtros['tipo'];
    }
    if (isset($filtros['anio'])) {
        $condicion[] = 'YEAR(t1.fecha) = ?';
        $params[] = $filtros['anio'];
    }


    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    'comprobado' => 't1.comprobado'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'idCliente' => 't1.idCliente',
        'nombre_franqueo' => 't2.nombre_franqueo',
        'ot' => 't1.ot',
        'referencia' => 't1.referencia'

    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    //-----------GROUP BY-------------
    $camposGroupPermitidos = array(
        'idCliente' => 't1.idCliente',
        'codigo_saldo' => 't2.codigo_saldo',
        'producto' => 't7.producto',
        'tipos' => 't1.tipo',
        'orden' => 't6.orden',
        'gramos' => 't3.gramos',
        'titulo' => 't6.titulo'                   
    );

    $sqlGroup = '';

    if (!empty($group) && is_array($group)) {

        $groups = array();

        foreach ($group as $g) {

            if (isset($camposGroupPermitidos[$g])) {
                $groups[] = $camposGroupPermitidos[$g];
            }
        }

        if (!empty($groups)) {
            $sqlGroup = ' GROUP BY ' . implode(', ', $groups);
        }
    }


    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
       'idCliente' => 't1.idCliente',
       'ordenTarifaProducto' => 't5.orden',
       'gramosTarifasLeft' => 't4.gramos',
       'producto_Padre' => 't7.producto',
       'ordenTarifaProducto_inner' => 't6.orden',
       'nombre_franqueo' => 't2.nombre_franqueo',
       'fecha' => 't1.fecha',
       'ot' => 't1.ot',
       'referencia' => 't1.referencia',
       'aniosDistintos' => 'YEAR(t1.fecha)'
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
        FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
            return array(
            'error' => '"Cargar franqueo Tipo:<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',
            'sql' => $consulta,
            'params' => $params
            );   
            //die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarTiposFranqueoPorProducto($conn_sis, $bbddSql, $campos,$filtros, $order)
{
    $camposPermitidos = array(
        'id' => 'tabla.id',
        'destino' => 'tabla.destino',
        'gramos' => 'tabla.gramos',
        'tipos' => 'tabla.tipos',
        'orden' => 'tabla.orden',
        'titulo' => 'tabla.titulo'
    );
   

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['idProductoPadre'])) {
        $condicion[] = 't3.id = ?';
        $params[] = $filtros['idProductoPadre'];
    }
    
    

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
         'orden' => 'tabla.orden',
        'titulo' => 'tabla.titulo' 
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

    $anioActual = date('Y');
    // ---------- SQL ----------
   $consulta = "
        SELECT $listaCampos
        FROM (
            SELECT
                t1.id,
                t2.destino,
                t1.gramos,
                t1.tipos,
                t2.orden,
                t2.titulo
            FROM [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t1
            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t2
                ON t1.idTarifasProducto = t2.id
            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductoPadre] AS t3
                ON t2.idproductoPadre = t3.id
            $sqlWhere

            UNION

            SELECT
                id,
                '' AS destino,
                '' AS gramos,
                tipos,
                9 AS orden,
                '' AS titulo
            FROM [".$bbddSql."].[dbo].[tarifas".$anioActual."]
            WHERE idTarifasProducto IS NULL
        ) AS tabla
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'datos' => array(),
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function mostrarDatosParaExportarAlbaranesCorreosFranqueo($conn_sis, $bbddSql, $campos,$filtros)
{
   
    $camposPermitidos = array(
        'producto' => 'tabla.producto',
        'ambito_SIDI' => 'tabla.ambito_SIDI',
        'anadidos' => 'tabla.anadidos',
        'referencia' => 'tabla.referencia',
        'id' => 'tabla.id',
        'gramos_SIDI' => 'tabla.gramos_SIDI',
        'normalizado' => 'tabla.normalizado',
        'unidades' => 'tabla.unidades',
        'codigoSidi' => 'tabla.codigoSidi',
        'idAnexo_SIDI' => 'tabla.idAnexo_SIDI',
        'idSIDI' => 'tabla.idSIDI'
    );
   

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    // ---------- FILTROS ----------
    $fecha = date('Y-m-d');
    $idProductoPadre = isset($filtros['idProducto']) ? $filtros['idProducto'] : 0;

    $params = array(
        $fecha,   
        $idProductoPadre,
        $fecha,
        $fecha,        
        $idProductoPadre
    );

     $sqlOrder = ' ORDER BY tabla.referencia ASC, tabla.id ASC';
    
    

    $anioActual = date('Y');
    // ---------- SQL ----------
    $consulta = "
        SELECT $listaCampos
        FROM
        (
            SELECT
                t1.id, t1.idCliente, t1.ot, t1.otSidi, t1.fecha, t1.tipo, t1.unidades, t1.importe, t1.referencia, t1.comprobado, t1.txt, t1.importado,
                t3.idProductoPadre,
                t4.producto,
                t4.idAnexo,
                t4.idProducto,
                t2.gramos,
                t2.normalizado,
                t2.ambito,
                t2.anadidos,
                t2.ambito_SIDI,
                t2.gramos_SIDI,
                t4.idAnexo_SIDI,
                t4.idSIDI,
                t5.codigoSidi,
                t5.codigoSidiPre,
                CAST(t1.importe * 100 AS int) AS importeSidi,
                t6.anadidos AS anadidos2,
                t5.nombre_empresa,
                t5.direccion,
                t5.localidad,
                t5.provincia,
                t5.codigo_postal 

            FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1

            INNER JOIN [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t2
            ON t1.tipo = t2.tipos

            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t3
            ON t3.id = t2.idTarifasProducto

            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductoPadre] AS t4
            ON t4.id = t3.idProductoPadre

            INNER JOIN [".$bbddSql."].[dbo].[clientes] AS t5
            ON t5.codigo = t1.idCliente

            LEFT JOIN [".$bbddSql."].[dbo].[franqueo] AS t6
            ON t6.referencia = t1.referencia

            WHERE t1.fecha = ?
            AND t1.comprobado = 0
            AND t1.txt = 0
            AND t3.idProductoPadre = ?

            UNION

            SELECT
                t1.id, t1.idCliente, t1.ot, t1.otSidi, t1.fecha, t1.tipo, t1.unidades, t1.importe, t1.referencia, t1.comprobado, t1.txt, t1.importado,
                t3.idProductoPadre,
                t4.producto,
                t4.idAnexo,
                t4.idProducto,
                t2.gramos,
                t2.normalizado,
                t2.ambito,
                t2.anadidos,
                t2.ambito_SIDI,
                t2.gramos_SIDI,
                t4.idAnexo_SIDI,
                t4.idSIDI,
                t5.codigoSidi,
                t5.codigoSidiPre,
                CAST(t1.importe * 100 AS int) AS importeSidi,
                t6.anadidos AS anadidos2,
                t5.nombre_empresa,
                t5.direccion,
                t5.localidad,
                t5.provincia,
                t5.codigo_postal

            FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1

            LEFT JOIN [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t2
            ON t1.tipo = t2.tipos

            LEFT JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t3
            ON t3.id = t2.idTarifasProducto

            LEFT JOIN [".$bbddSql."].[dbo].[tarifasProductoPadre] AS t4
            ON t4.id = t3.idProductoPadre

            LEFT JOIN [".$bbddSql."].[dbo].[clientes] AS t5
            ON t5.codigo = t1.idCliente

            LEFT JOIN [".$bbddSql."].[dbo].[franqueo] AS t6
            ON t6.referencia = t1.referencia

            WHERE t1.fecha = ?
            AND t1.comprobado = 0
            AND t3.idProductoPadre IS NULL
            AND t1.referencia IN
            (
                SELECT t1.referencia
                FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1

                INNER JOIN [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t2
                ON t1.tipo = t2.tipos

                INNER JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t3
                ON t3.id = t2.idTarifasProducto

                WHERE t1.fecha = ?
                AND t1.comprobado = 0
                AND t1.txt = 0
                AND t3.idProductoPadre = ?
            )
        ) AS tabla
        $sqlOrder
    ";







    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'datos' => array(),
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();

    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
}

function mostrarDatosParaExportarAlbaranesCorreosFranqueoPostF12($conn_sis, $bbddSql, $campos,$filtros)
{
   
    $camposPermitidos = array(
        'producto' => 'tabla.producto',
        'ambito_SIDI' => 'tabla.ambito_SIDI',
        'anadidos' => 'tabla.anadidos',
        'referencia' => 'tabla.referencia',
        'id' => 'tabla.id',
        'gramos_SIDI' => 'tabla.gramos_SIDI',
        'normalizado' => 'tabla.normalizado',
        'unidades' => 'tabla.unidades',
        'codigoSidi' => 'tabla.codigoSidi',
        'idAnexo_SIDI' => 'tabla.idAnexo_SIDI',
        'idSIDI' => 'tabla.idSIDI'
    );
   

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);


    // ---------- FILTROS ----------
    $fecha = date('Y-m-d');
    $idProductoPadre = isset($filtros['idProducto']) ? $filtros['idProducto'] : 0;

    $params = array(
        $fecha,   
        $idProductoPadre,
        $fecha,
        $fecha,        
        $idProductoPadre
    );

     $sqlOrder = ' ORDER BY tabla.referencia ASC, tabla.id ASC';
    
    

    $anioActual = date('Y');
    // ---------- SQL ----------
    $consulta = "
        SELECT $listaCampos
        FROM
        (
            SELECT
                t1.id, t1.idCliente, t1.ot, t1.otSidi, t1.fecha, t1.tipo, t1.unidades, t1.importe, t1.referencia, t1.comprobado, t1.txt, t1.importado,
                t3.idProductoPadre,
                t4.producto,
                t4.idAnexo,
                t4.idProducto,
                t2.gramos,
                t2.normalizado,
                t2.ambito,
                t2.anadidos,
                t2.ambito_SIDI,
                t2.gramos_SIDI,
                t4.idAnexo_SIDI,
                t4.idSIDI,
                t5.codigoSidi,
                t5.codigoSidiPre,
                CAST(t1.importe * 100 AS int) AS importeSidi,
                t6.anadidos AS anadidos2,
                t5.nombre_empresa,
                t5.direccion,
                t5.localidad,
                t5.provincia,
                t5.codigo_postal 

            FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1

            INNER JOIN [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t2
            ON t1.tipo = t2.tipos

            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t3
            ON t3.id = t2.idTarifasProducto

            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductoPadre] AS t4
            ON t4.id = t3.idProductoPadre

            INNER JOIN [".$bbddSql."].[dbo].[clientes] AS t5
            ON t5.codigo = t1.idCliente

            LEFT JOIN [".$bbddSql."].[dbo].[franqueo] AS t6
            ON t6.referencia = t1.referencia

            WHERE t1.fecha = ?
            AND t1.comprobado = 1          
            AND t3.idProductoPadre = ?

            UNION

            SELECT
                t1.id, t1.idCliente, t1.ot, t1.otSidi, t1.fecha, t1.tipo, t1.unidades, t1.importe, t1.referencia, t1.comprobado, t1.txt, t1.importado,
                t3.idProductoPadre,
                t4.producto,
                t4.idAnexo,
                t4.idProducto,
                t2.gramos,
                t2.normalizado,
                t2.ambito,
                t2.anadidos,
                t2.ambito_SIDI,
                t2.gramos_SIDI,
                t4.idAnexo_SIDI,
                t4.idSIDI,
                t5.codigoSidi,
                t5.codigoSidiPre,
                CAST(t1.importe * 100 AS int) AS importeSidi,
                t6.anadidos AS anadidos2,
                t5.nombre_empresa,
                t5.direccion,
                t5.localidad,
                t5.provincia,
                t5.codigo_postal

            FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1

            LEFT JOIN [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t2
            ON t1.tipo = t2.tipos

            LEFT JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t3
            ON t3.id = t2.idTarifasProducto

            LEFT JOIN [".$bbddSql."].[dbo].[tarifasProductoPadre] AS t4
            ON t4.id = t3.idProductoPadre

            LEFT JOIN [".$bbddSql."].[dbo].[clientes] AS t5
            ON t5.codigo = t1.idCliente

            LEFT JOIN [".$bbddSql."].[dbo].[franqueo] AS t6
            ON t6.referencia = t1.referencia

            WHERE t1.fecha = ?
            AND t1.comprobado = 1
            AND t3.idProductoPadre IS NULL
            AND t1.referencia IN
            (
                SELECT t1.referencia
                FROM [".$bbddSql."].[dbo].[franqueoTipos] AS t1

                INNER JOIN [".$bbddSql."].[dbo].[tarifas".$anioActual."] AS t2
                ON t1.tipo = t2.tipos

                INNER JOIN [".$bbddSql."].[dbo].[tarifasProductos] AS t3
                ON t3.id = t2.idTarifasProducto

                WHERE t1.fecha = ?
                AND t1.comprobado = 1               
                AND t3.idProductoPadre = ?
            )
        ) AS tabla
        $sqlOrder
    ";







    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'datos' => array(),
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();

    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'datos' => $result,
        'sql' => $consulta,
        'params' => $params
    );
}

function mostrarFranqueoExportarCorreos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'referencia' => 't1.referencia',
        'ot' => 't2.ot',
        'otSidi' => 't2.otSidi',
        'uno' => 't1.uno',
        'dos' => 't1.dos',
        'tres' => 't1.tres',
        'cuatro' => 't1.cuatro',
        'cinco' => 't1.cinco',
        'seis' => 't1.seis',
        'siete' => 't1.siete'
    );

    //tabla2: franqueo (left)

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "left join [".$bbddSql."].[dbo].[franqueo] as t2 on SUBSTRING(t1.referencia,1,20) = t2.referencia ",
        //'tabla6' => "left join [".$bbddSql."].[dbo].[facturacionClayma] as t6 on t6.presupuesto = t1.presupuesto"
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    /*
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    */

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'codigo_saldo' => 't1.codigo_saldo',
    //'codigo' => 't1.codigo'    
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'referencia' => 't1.referencia',
        'Idunico' => 't1.Idunico',
        'orden' => 't1.orden'      
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
        FROM [".$bbddSql."].[dbo].[franqueoExportarCorreos] AS t1        
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarFranqueoPagado($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $group)
{
    $camposPermitidos = array(
        'id' => 't1.id',        
        'idCliente' => 't1.idCliente',
        'nombre_franqueo' => 't2.nombre_franqueo', 
        'unidades' => 't1.unidades',
        'tipoCert_Not' => 't1.tipoCert_Not',
        'fecha' => 't1.fecha',
        'ot' => 't1.ot'        
    );

    //t2: clientes
    //t3: empleados
    //t4: tarifasProductoPadre   
    

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();
    $paramsCampos = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];

            if ($campo == 'totalProductoFecha') {
                $paramsCampos[] = isset($filtros['producto']) ? $filtros['producto'] : '';
                $paramsCampos[] = isset($filtros['fecha']) ? $filtros['fecha'] : '';
            }
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCliente = t2.codigo",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[empleados] as t3 on t1.idEmpleado = t3.id",
        'tabla4' => "inner join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t4 on t1.idProductoPadre = t4.id",    
        ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = $paramsCampos;   
    
    
    if (isset($filtros['idProductoPadre'])) {
        $condicion[] = 't1.idProductoPadre = ?';
        $params[] = $filtros['idProductoPadre'];
    }
    if (isset($filtros['fecha'])) {
        $condicion[] = 't1.fecha = ?';
        $params[] = $filtros['fecha'];
    }
   




    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
        'comprobado' => 't1.comprobado'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- GROUP BY ----------
    $camposGroupPermitidos = array(
        //'nombre_franqueo' => 't2.nombre_franqueo',
       // 'nombreProducto' => 't3.producto'
    );

    $sqlGroup = '';

    if (is_array($group) && !empty($group)) {
        $groups = array();

        foreach ($group as $g) {
            if (isset($camposGroupPermitidos[$g])) {
                $groups[] = $camposGroupPermitidos[$g];
            }
        }

        if (!empty($groups)) {
            $sqlGroup = ' GROUP BY ' . implode(', ', $groups);
        }
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
      'id' => 't1.id',
      
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
        FROM [".$bbddSql."].[dbo].[franqueoPagado] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
            return array(
            'error' => '<pre>' . print_r(sqlsrv_errors(), true) . '</pre>',
            'sql' => $consulta,
            'params' => $params
            );   
            //die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function mostrarFacturacion($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'numeroFacturaCompleto' => 't1.numeroFacturaCompleto',
        'idCodigoCliente' => 't1.idCodigoCliente',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'aPagar' => 't1.aPagar',
        'precioNeto' => 't1.precioNeto',
        'tipoIva' => 't1.tipoIva',
        'iva' => 't1.iva',
        'precioTotal' => 't1.precioTotal',
        'origenFactura' => 't1.origenFactura',
        'nif' => 't2.nif',
        'numero' => 't1.numero'
        
    );

    //t2: clientes
    //t3: 

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[clientes] as t2 on t2.codigo = t1.idCodigoCliente",
       
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['domiciliada'])) {
        $condicion[] = 't2.domiciliada = ?';
        $params[] = $filtros['domiciliada'];
    }
    if (isset($filtros['sinFormaPago'] ) && $filtros['sinFormaPago'] == 1) {
        $condicion[] = "(t1.formaPagoReal IS NULL OR t1.formaPagoReal = '')";
    }
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha',
        //'codigo_saldo' => 't1.codigo_saldo',        
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'numeroFacturaCompleto' => 't1.numeroFacturaCompleto',
        'numero' => 't1.numero'        
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
        FROM [".$bbddSql."].[dbo].[facturacion] AS t1        
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function mostrarFacturacionClayma($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'numeroFacturaCompleto' => 't1.numeroFacturaCompleto',
        'idCodigoCliente' => 't1.idCodigoCliente',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'aPagar' => 't1.aPagar',
        'precioNeto' => 't1.precioNeto',
        'tipoIva' => 't1.tipoIva',
        'iva' => 't1.iva',
        'precioTotal' => 't1.precioTotal',
        'origenFactura' => 't1.origenFactura',
        'nif' => 't2.nif',
        'numero' => 't1.numero'
        
    );

    //t2: clientes
    //t3: 

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[clientesClayma] as t2 on t2.codigo = t1.idCodigoCliente",
       
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    /*
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    */

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha',
        //'codigo_saldo' => 't1.codigo_saldo',        
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'numeroFacturaCompleto' => 't1.numeroFacturaCompleto',
        'numero' => 't1.numero'        
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
        FROM [".$bbddSql."].[dbo].[facturacionClayma] AS t1        
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function mostrarFacturacionCibelesYCorreos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $filtrosLike, $order)
{
     $camposPermitidos = array(
        'factura' => 'tabla.factura',
        'codigo_saldo' => 'tabla.codigo_saldo',
        'nombre_empresa' => 'tabla.nombre_empresa',
        'nombreFranqueoReal' => "CASE WHEN tabla.franqueoCliente = '' THEN tabla.nombre_empresa ELSE tabla.franqueoCliente END AS nombreFranqueoReal",
        'nombreFranqueoReal2' => "CASE WHEN tabla.franqueoCliente = '' THEN tabla.nombre_franqueo ELSE tabla.franqueoCliente END AS nombreFranqueoReal2",
        'concepto' => 'tabla.concepto',
        'neto' => 'tabla.neto',
        'iva' => 'tabla.iva',
        'importe' => 'tabla.importe',
        'anticipo' => 'tabla.anticipo',
        'aPagar' => 'tabla.aPagar',
        'codigoSubcliente' => 'tabla.codigoSubcliente',
        'franqueoCliente' => 'tabla.franqueoCliente',
        'vencimiento' => 'tabla.vencimiento',
        'fecha' => 'tabla.fecha',
        'presupuesto' => 'tabla.presupuesto',
        'domiciliada' => 'tabla.domiciliada', //siempre debe estar
        'formaPagoReal' => 'tabla.formaPagoReal', //siempre debe estar
        'importePF' => 'tabla.importePF'
        

    );  


    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
       // 'tabla2' => "left join [".$bbddSql."].[dbo].[clientes] as tabla2 on tabla1.clayma=0 t2.id = SUBSTRING(t1.codigoBarras,1,len(t1.codigoBarras)-8)",
       // 'tabla3' => "left join [".$bbddSql."].[dbo].[clientesClayma] as tabla3 on t3.id = t1.codigoSubcliente"
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['domiciliada'])) {
        $condicion[] = 'tabla.domiciliada = ?';
        $params[] = $filtros['domiciliada'];
    }
    if (isset($filtros['sinFormaPago'] ) && $filtros['sinFormaPago'] == 1) {
        $condicion[] = "(tabla.formaPagoReal IS NULL OR tabla.formaPagoReal = '')";
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 'tabla.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    } 
    if (isset($filtros['conCompensacion']) && $filtros['conCompensacion'] == 1) {
        $condicion[] = '(tabla.idFormaPago = 6 or tabla.idFormaPago=13)';
       
    }    
    
    
    
    //FILTROS CON OPERADORES
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    'fecha' => 'tabla.fecha'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
       // 'presupuesto' => 'tabla.presupuesto',
       //'cliente' => 'tabla.cliente'   
    );

    if (is_array($filtrosLike) && !empty($filtrosLike)) {
        foreach ($filtrosLike as $f) {
            if (
                isset($f['campo'], $f['valor']) &&
                isset($camposLikePermitidos[$f['campo']]) &&
                trim($f['valor']) !== ''
            ) {
                $condicion[] = $camposLikePermitidos[$f['campo']] . ' LIKE ?';
                $params[] = '%' . $f['valor'] . '%';
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre_empresa' => 'tabla.nombre_empresa',
        'fecha' => 'tabla.fecha',
        'factura' => 'tabla.factura',
        'nombreFranqueoReal2' => "CASE WHEN tabla.franqueoCliente = '' THEN tabla.nombre_franqueo ELSE tabla.franqueoCliente END",
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
    FROM (

    SELECT numeroFacturaCompleto  as 'factura', t2.codigo_saldo,t2.importePf,t2.nombre_franqueo, t2.nombre_empresa, t3.concepto, t1.precioNeto as 'neto', t1.iva, t1.precioTotal as 'importe', t1.provision as 'anticipo'
	, t1.aPagar, '' as codigoSubcliente	, '' as franqueoCliente, t2.vencimiento, t1.fecha, t1.origenFactura as presupuesto
	, t2.domiciliada, t1.formaPagoReal, t2.idFormaPago
	FROM [".$bbddSql."].[dbo].[facturacion] as t1 
	inner join [".$bbddSql."].[dbo].[clientes] as t2 
	on t1.idCodigoCliente = t2.codigo 
	inner join [".$bbddSql."].[dbo].[formaDePago] as t3 
	on t2.idFormaPago = t3.id 

	UNION

  SELECT t1.numeroOficial as 'factura', t2.codigo_saldo,t2.importePf,t2.nombre_franqueo, t2.nombre_empresa, t3.concepto, t1.neto, t1.iva, t1.importe,t1.anticipo
  , t1.aPagar, t1.codigoCliente, t4.nombre_franqueo as franqueoCliente, t2.vencimiento, t1.fecha, '' as presupuesto
  , t2.domiciliada, t1.formaPago as formaPagoReal, t2.idFormaPago
  FROM [".$bbddSql."].[dbo].[facturasCorreos] as t1
  inner join [".$bbddSql."].[dbo].[clientes] as t2
  on t1.codigoCliente = t2.codigo
  inner join [".$bbddSql."].[dbo].[formaDePago] as t3
  on t2.idFormaPago = t3.id
  inner join [".$bbddSql."].[dbo].[clientes] as t4
  on t2.codigo_saldo = t4.codigo_saldo and t4.codigo_saldo = t4.codigo


    ) AS tabla
     
    $sqlJoins
    $sqlWhere
    $sqlOrder
    ";
   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function mostrarFacturacionCorreos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'codigo_saldo' => 't2.codigo_saldo',
        'importe' => 't1.importe',
        'fecha' => 't1.fecha',
        'numeroOficial' => 't1.numeroOficial'       
    );

    //t2: clientes   

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[clientes] as t2 on t2.codigo = t1.codigoCliente"       
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['domiciliada'])) {
        $condicion[] = 't2.domiciliada = ?';
        $params[] = $filtros['domiciliada'];
    }
    if (isset($filtros['sinFormaPago'] ) && $filtros['sinFormaPago'] == 1) {
        $condicion[] = "(t1.formaPago IS NULL OR t1.formaPago = '')";
    }
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha',
        //'codigo_saldo' => 't1.codigo_saldo',        
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'codigo_saldo' => 't2.codigo_saldo'        
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
        FROM [".$bbddSql."].[dbo].[facturasCorreos] AS t1        
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

//LISTADOS
function cargarPresupuestadores($conn_sis, $bbddSql, $campos, $filtros, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'nombre' => 't1.nombre',
        'telefono' => 't1.telefono',
        'inicial' => 't1.inicial'
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
    );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['nombre'])) {
        $condicion[] = 't1.nombre = ?';
        $params[] = $filtros['nombre'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre'     => 't1.nombre'        
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
        FROM [".$bbddSql."].[dbo].[presupuestadores] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return $result;
    /*return array(
    'datos' => $result,
    'sql' => $consulta
    );*/

}

function cargarComerciales($conn_sis, $bbddSql, $campos, $filtros, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'nombre' => 't1.nombre'      
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
    );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    /*
    if (isset($filtros['nombre'])) {
        $condicion[] = 't1.nombre = ?';
        $params[] = $filtros['nombre'];
    }
    */

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre'     => 't1.nombre'        
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
        FROM [".$bbddSql."].[dbo].[comerciales] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
        'error' => '',
        'datos' => $result,
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );

}

function cargarPaises($conn_sis, $bbddSql, $campos, $filtros, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'nombreComun' => 't1.nombreComun',
        'codigo' => 't1.codigo'    
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
    );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombreComun'     => 't1.nombreComun'        
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
        FROM [".$bbddSql."].[dbo].[paises] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
        'error' => '',
        'datos' => $result,
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );

}

function cargarPeriodosFacturacion($conn_sis, $bbddSql, $campos, $filtros, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'periodo' => 't1.periodo'      
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
    );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    /*
    if (isset($filtros['nombre'])) {
        $condicion[] = 't1.nombre = ?';
        $params[] = $filtros['nombre'];
    }
    */

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'periodo'     => 't1.periodo'        
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
        FROM [".$bbddSql."].[dbo].[clientesFacPeriodos] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
        'error' => '',
        'datos' => $result,
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );

}

function cargarFacturasProvisionFondo($conn_sis, $bbddSql, $campos, $filtros, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipoProvision' => 't1.tipoProvision'      
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
    );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    /*
    if (isset($filtros['nombre'])) {
        $condicion[] = 't1.nombre = ?';
        $params[] = $filtros['nombre'];
    }
    */

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tipoProvision'     => 't1.tipoProvision'        
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
        FROM [".$bbddSql."].[dbo].[clientesFacPF] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
        'error' => '',
        'datos' => $result,
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );

}

function cargarTotalFranqueoIVA($conn_sis, $bbddSql, $campos, $filtros, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipoIva' => 't1.tipoIva'        
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
        );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['tipoIva'])) {
        $condicion[] = 't1.tipoIva = ?';
        $params[] = $filtros['tipoIva'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tipoIva'     => 't1.tipoIva'        
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
        FROM [".$bbddSql."].[dbo].[totalFranqueoTipos] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return $result;
    /*return array(
    'datos' => $result,
    'sql' => $consulta
    );*/

}

function cargarFormasDePago($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'concepto' => 't1.concepto'        
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
        );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['concepto'])) {
        $condicion[] = 't1.concepto = ?';
        $params[] = $filtros['concepto'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'concepto'     => 't1.concepto'        
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
        FROM [".$bbddSql."].[dbo].[formaDePago] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return $result;
    /*return array(
    'datos' => $result,
    'sql' => $consulta
    );*/	
}

function cargarDepartamentoProcesoBBDD($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'departamento' => 't1.departamento'        
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
        );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['departamento'])) {
        $condicion[] = 't1.departamento = ?';
        $params[] = $filtros['departamento'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'departamento'     => 't1.departamento'        
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
        FROM [".$bbddSql."].[dbo].[procesosDepartamento] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return $result;
    /*return array(
    'datos' => $result,
    'sql' => $consulta
    );*/	
}

function cargarProcesoBBDD($conn_sis, $bbddSql , $campos, $joins, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'proceso' => 't1.proceso',
        'descripcion' => 't1.descripcion'      
    );


    if (!is_array($campos) || empty($campos)) {
         return array(
            'error' => "campos vacios");       
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idTipoProceso'])) {
        $condicion[] = 't1.idTipoProceso = ?';
        $params[] = $filtros['idTipoProceso'];
    }
    if (isset($filtros['idDepartamento'])) {
        $condicion[] = 't1.idDepartamento = ?';
        $params[] = $filtros['idDepartamento'];
    }
    if (isset($filtros['proceso'])) {
        $condicion[] = 't1.proceso = ?';
        $params[] = $filtros['proceso'];
    }
    if (isset($filtros['descripcion'])) {
        $condicion[] = 't1.descripcion = ?';
        $params[] = $filtros['descripcion'];
    }
    if (isset($filtros['mostrarEnInforme'])) {
        $condicion[] = 't1.mostrarEnInforme = ?';
        $params[] = $filtros['mostrarEnInforme'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'proceso'     => 't1.proceso'        
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
        FROM [".$bbddSql."].[dbo].[procesos] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta
    );	
}

function cargarTiposProvisionesFondos($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipo' => 't1.tipo'        
    );


    if (!is_array($campos) || empty($campos)) {
        //return array();
         return array(
        'datos' => '',
        'sql' => 'campos vacios'
    );
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       //return array();
         return array(
        'datos' => '',
        'sql' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['tipo'])) {
        $condicion[] = 't1.tipo = ?';
        $params[] = $filtros['tipo'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id'     => 't1.id'        
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
        FROM [".$bbddSql."].[dbo].[provisionesDeFondo_tipos] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return $result;
    /*
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
    */

}

function cargarTipoDeProceso($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipoProceso' => 't1.tipoProceso',
        'orden' => 't1.orden'
    );

    if (!is_array($campos) || empty($campos)) {
        return array();
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array();
    }

    $listaCampos = implode(', ', $camposSQL);

    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['tipoProceso'])) {
        $condicion[] = 't1.tipoProceso = ?';
        $params[] = $filtros['tipoProceso'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tipoProceso'     => 't1.tipoProceso'
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
        FROM [".$bbddSql."].[dbo].[procesosTipos] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return $result;
}

function cargarTamaniosPapel($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{

    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'tamano' => 't1.tamano'        
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');
    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['tamano'])) {
        $condicion[] = 't1.tamano = ?';
        $params[] = $filtros['tamano'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tamano'     => 't1.tamano'        
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
        FROM [".$bbddSql."].[dbo].[L_papelTamanio] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarTiposPapel($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipo' => 't1.tipo'        
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');
    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['tipo'])) {
        $condicion[] = 't1.tipo = ?';
        $params[] = $filtros['tipo'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tipo'     => 't1.tipo'        
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
        FROM [".$bbddSql."].[dbo].[L_papelTipo] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarAcabadoPapel($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'acabado' => 't1.acabado'        
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');
    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['acabado'])) {
        $condicion[] = 't1.acabado = ?';
        $params[] = $filtros['acabado'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'acabado'     => 't1.acabado'        
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
        FROM [".$bbddSql."].[dbo].[L_papelAcabado] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarGramajePapel($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'gramaje' => 't1.gramaje'        
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['gramaje'])) {
        $condicion[] = 't1.gramaje = ?';
        $params[] = $filtros['gramaje'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'gramaje'     => 't1.gramaje'        
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
        FROM [".$bbddSql."].[dbo].[L_papelGramaje] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function mostrarTarifasTipoImpresora($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipoImpresora' => 't1.tipoImpresora',
        'precioClick' => 't1.precioClick'
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['tipoImpresora'])) {
        $condicion[] = 't1.tipoImpresora = ?';
        $params[] = $filtros['tipoImpresora'];
    }
    if (isset($filtros['precioClick'])) {
        $condicion[] = 't1.precioClick = ?';
        $params[] = $filtros['precioClick'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tipoImpresora' => 't1.tipoImpresora',
        'precioClick' => 't1.precioClick'        
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
        FROM [".$bbddSql."].[dbo].[L_impresorasTipo] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarConceptosGF($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'nombreConcepto' => 't1.nombreConcepto'       
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['nombreConcepto'])) {
        $condicion[] = 't1.nombreConcepto = ?';
        $params[] = $filtros['nombreConcepto'];
    }
   

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombreConcepto' => 't1.nombreConcepto'         
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
        FROM [".$bbddSql."].[dbo].[L_gf_concepto] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarSubConceptos1GF($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'idConcepto' => 't1.idConcepto',
        'nombreSubconcepto' => 't1.nombreSubconcepto'    
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idConcepto'])) {
        $condicion[] = 't1.idConcepto = ?';
        $params[] = $filtros['idConcepto'];
    }
    if (isset($filtros['nombreSubconcepto'])) {
        $condicion[] = 't1.nombreSubconcepto = ?';
        $params[] = $filtros['nombreSubconcepto'];
    }
   

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
         'idConcepto' => 't1.idConcepto',
        'nombreSubconcepto' => 't1.nombreSubconcepto'
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
        FROM [".$bbddSql."].[dbo].[L_gf_subconcepto1] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarSubConceptos2GF($conn_sis, $bbddSql, $campos, $filtros,$filtrosOperadores, $order)
{
    // ---------- CAMPOS ----------
    $camposPermitidos = array(
        'id' => 't1.id',
        'idSubconcepto1' => 't1.idSubconcepto1',
        'nombreSubconcepto2' => 't1.nombreSubconcepto2',
        'coste' => 't1.coste'  
    );


    if (!is_array($campos) || empty($campos)) {
       return array(
       'error' => 'campos vacios');    
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
       return array(
       'error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idSubconcepto1'])) {
        $condicion[] = 't1.idSubconcepto1 = ?';
        $params[] = $filtros['idSubconcepto1'];
    }
    if (isset($filtros['nombreSubconcepto2'])) {
        $condicion[] = 't1.nombreSubconcepto2 = ?';
        $params[] = $filtros['nombreSubconcepto2'];
    }
    if (isset($filtros['coste'])) {
        $condicion[] = 't1.coste = ?';
        $params[] = $filtros['coste'];
    }
   

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
   // 'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }


    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }



    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(       
        'nombreSubconcepto2' => 't1.nombreSubconcepto2'
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
        FROM [".$bbddSql."].[dbo].[L_gf_subconcepto2] AS t1        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    //return $result;
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}


function cargarRegistrosHoraInformatica($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id'           
    );

    //t2: presupuestadores
    

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        //'tabla2' => "left join [".$bbddSql."].[dbo].[presupuestos detalle] as t2 on t2.id = SUBSTRING(t1.codigoBarras,1,len(t1.codigoBarras)-8)",
        //'tabla3' => "left join [".$bbddSql."].[dbo].[procesosTipos] as t3 on t3.id = t2.idTipo",
        //'tabla4' => "left join [".$bbddSql."].[dbo].[procesos] as t4 on t4.id = t2.idConcepto",
        //'tabla5' => "left join [".$bbddSql."].[dbo].[procesos] as t5 on t5.id = t1.sinProceso_idConcepto",
        //'tabla6' => "left join [".$bbddSql."].[dbo].[L_gf_subconcepto2] as t6 on t6.id = t1.idGFSubconjunto2",
        //'tabla7' => "left join [".$bbddSql."].[dbo].[L_gf_subconcepto1] as t7 on t6.idSubconcepto1 = t7.id",
        //'tabla8' => "left join [".$bbddSql."].[dbo].[L_gf_concepto] as t8 on t7.idConcepto = t8.id",
        //'tabla9' => "left join [".$bbddSql."].[dbo].[L_impresoras] as t9 on t1.idImpresoras = t9.id",
        //'tabla10' => "left join [".$bbddSql."].[dbo].[L_papelTamanio] as t10 on t1.idPapelTamano = t10.id",
        //'tabla11' => "left join [".$bbddSql."].[dbo].[L_papelTipo] as t11 on t1.idPapelTipo = t11.id",
        //'tabla12' => "left join [".$bbddSql."].[dbo].[L_papelAcabado] as t12 on t1.idPapelAcabado = t12.id",
        //'tabla13' => "left join [".$bbddSql."].[dbo].[L_papelGramaje] as t13 on t1.idPapelGramaje = t13.id",
        //'tabla14' => "left join [".$bbddSql."].[dbo].[L_papelOrigen] as t14 on t1.idPapelOrigen = t14.id",

    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['idPapelTamano'])) {
        $condicion[] = 't1.idPapelTamano = ?';
        $params[] = $filtros['idPapelTamano'];
    }
    if (isset($filtros['idPapelTipo'])) {
        $condicion[] = 't1.idPapelTipo = ?';
        $params[] = $filtros['idPapelTipo'];
    }
    if (isset($filtros['idPapelAcabado'])) {
        $condicion[] = 't1.idPapelAcabado = ?';
        $params[] = $filtros['idPapelAcabado'];
    }
    if (isset($filtros['idPapelGramaje'])) {
        $condicion[] = 't1.idPapelGramaje = ?';
        $params[] = $filtros['idPapelGramaje'];
    }

    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    //'codigo' => 't1.codigo'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        //'nombre_empresa'     => 't1.nombre_empresa',
        //'subcliente'     => 't1.subcliente'          
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
        FROM [".$bbddSql."].[dbo].[registroHoras] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarTamaniosConversorPapel($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idTamanioInicio' => 't1.idTamanioInicio',
        'idTamanioFinal' => 't1.idTamanioFinal',
        'valor' => 't1.valor'
    );

    //t2: L_papelTamanio
    //t3: L_papelTamanio
    

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t2 on t1.idTamanioInicio = t2.id",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t3 on t1.idTamanioFinal = t3.id"
        
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    if (isset($filtros['idPapelTamano'])) {
        $condicion[] = 't1.idPapelTamano = ?';
        $params[] = $filtros['idPapelTamano'];
    }

    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    //'codigo' => 't1.codigo'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'tamanioInicio' => 't2.tamano',
        'tamanioFinal' => 't3.tamano'
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
        FROM [".$bbddSql."].[dbo].[L_papelTamanioConversor] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarListadoTarifasProductosPadre($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'producto' => 't1.producto'      
    );

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
       // 'tabla2' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t2 on t1.idTamanioInicio = t2.id",
        //'tabla3' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t3 on t1.idTamanioFinal = t3.id"
        
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    /*
    if (isset($filtros['idPapelTamano'])) {
        $condicion[] = 't1.idPapelTamano = ?';
        $params[] = $filtros['idPapelTamano'];
    }
    */

    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    //'codigo' => 't1.codigo'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'producto' => 't1.producto'       
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
        FROM [".$bbddSql."].[dbo].[tarifasProductoPadre] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarListadoTarifasProductos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'titulo' => 't1.titulo',
        'orden' => 't1.orden'   
    );

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
       // 'tabla2' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t2 on t1.idTamanioInicio = t2.id",
        //'tabla3' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t3 on t1.idTamanioFinal = t3.id"
        
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['idProductoPadre'])) {
        $condicion[] = 't1.idProductoPadre = ?';
        $params[] = $filtros['idProductoPadre'];
    }
    if (isset($filtros['titulo'])) {
        $condicion[] = 't1.titulo = ?';
        $params[] = $filtros['titulo'];
    }
   
    

    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    //'codigo' => 't1.codigo'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'producto' => 't1.producto'       
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
        FROM [".$bbddSql."].[dbo].[tarifasProductos] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {        
        return array(
            'error' => "<pre>" . print_r(sqlsrv_errors(), true) . "</pre>",            
            'sql' => $consulta,
            'params' => $params
            );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

function cargarProvisionesDeFondo_tipoCobrada($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'cobrada' => 't1.cobrada'       
    );

    if (!is_array($campos) || empty($campos)) {
        return array(
            'error' => "campos vacios");
    }

    $camposSQL = array();

    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //JOINS

    $joinsPermitidos = [
       // 'tabla2' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t2 on t1.idTamanioInicio = t2.id",
        //'tabla3' => "inner join [".$bbddSql."].[dbo].[L_papelTamanio] as t3 on t1.idTamanioFinal = t3.id"
        
    ];

    $sqlJoins = '';

    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }
    
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();    

    
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    

    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    //'codigo' => 't1.codigo'    
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id'       
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
        FROM [".$bbddSql."].[dbo].[provisionesDeFondo_tipoCobrada] AS t1
        $sqlJoins        
        $sqlWhere
        $sqlOrder
    ";

    //echo $consulta;   

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {        
        return array(
            'error' => "<pre>" . print_r(sqlsrv_errors(), true) . "</pre>",            
            'sql' => $consulta,
            'params' => $params
            );
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);
    
    return array(
    'error' => '',
    'datos' => $result,
    'sql' => $consulta,
     'params' => $params
    );
}

//INSERT
function insertarPresupuesto($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'letra' => 'letra',
        'cliente' => 'cliente',
        'codigoCliente' => 'codigoCliente',
        'persona' => 'persona',
        'direccion' => 'direccion',
        'poblacion' => 'poblacion',
        'cp' => 'cp',
        'pago' => 'pago',
        'notaCibeles' => 'notaCibeles',
        'forma de pago' => 'forma de pago',
        'campana' => 'campana',
        'campanaObservacion' => 'campanaObservacion',
        'cantidad' => 'cantidad',
        'fecha' => 'fecha',
        'comercial' => 'comercial',
        'pedcli' => 'pedcli',
        'fechaAceptacion' => 'fechaAceptacion',
        'fechaCompromiso' => 'fechaCompromiso',
        'fechaTerminado' => 'fechaTerminado',
        'factura' => 'factura',
        'detallada' => 'detallada',
        'idComercial' => 'idComercial',
        'idFormaPago' => 'idFormaPago',
        'idVisualizarTotalPresu' => 'idVisualizarTotalPresu',
        'idVisualizarTotalFranqueo' => 'idVisualizarTotalFranqueo',
        'importeFranqueo' => 'importeFranqueo',
        'otBajada' => 'otBajada',
        'otAbierta' => 'otAbierta',
        'fechaInicioReal' => 'fechaInicioReal',
        'noRetrasar' => 'noRetrasar',
        'campana2' => 'campana2',
        'cantidad2' => 'cantidad2',
        'pdfGenerado' => 'pdfGenerado',
        'clayma' => 'clayma',
        'numNoFactura' => 'numNoFactura',
        'numNoFacturaFecha' => 'numNoFacturaFecha',
        'noSeFacturaObservaciones' => 'noSeFacturaObservaciones',
        'observaciones2' => 'observaciones2',
        'noFacProcesado' => 'noFacProcesado',
        'bbddBorrado' => 'bbddBorrado',
        'fechaAceptacionRegistro' => 'fechaAceptacionRegistro',
        'otSidi' => 'otSidi',
        'trabajoIniciado' => 'trabajoIniciado'        
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[presupuestos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarRegistro($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'usuario' => 'usuario',
        'tabla' => 'tabla',
        'idRegistro' => 'idRegistro',
        'presupuesto' => 'presupuesto',
        'columna' => 'columna',
        'descripcion' => 'descripcion',
        'datosAntiguos' => 'datosAntiguos',
        'datosNuevos' => 'datosNuevos',
        'clayma' => 'clayma'
    ); 
    
    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'insertarRegistro: datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'insertarRegistro: camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[log]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarDetallePresupuesto($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'concepto' => 'concepto',
        'grupo' => 'grupo',
        'unidades' => 'unidades',
        'unidades2' => 'unidades2',
        'precio' => 'precio',
        'descripcion' => 'descripcion',
        'notaCibeles' => 'notaCibeles',
        'orden' => 'orden',
        'idConcepto' => 'idConcepto',
        'idTipo' => 'idTipo',
        'idDepartamento' => 'idDepartamento',
        'notaAdmonProd' => 'notaAdmonProd',
        'exentoIVA' => 'exentoIVA',
        'idMaterialPapel' => 'idMaterialPapel',
        'idTipoImpresora' => 'idTipoImpresora',
        'impresionNumeroCaras' => 'impresionNumeroCaras',
        'idPapelTamanioFinal' => 'idPapelTamanioFinal',
        'pesoGramos' => 'pesoGramos',
        'idGFConcepto' => 'idGFConcepto',
        'idGFMetrosCuadrados' => 'idGFMetrosCuadrados',
        'noVisible' => 'noVisible'       
    ); 
    
    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'insertarDetallePresupuesto: datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'insertarDetallePresupuesto: camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[presupuestos detalle]
        (".implode(', ', $camposSQL).")
         OUTPUT INSERTED.id
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $idInsertado = null;

    if (sqlsrv_fetch($resultado) !== false) {
        $idInsertado = sqlsrv_get_field($resultado, 0);    }

   
    
   sqlsrv_free_stmt($resultado);   
    
    return array(
        'error' => '',
        'ok' => true,
        'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarDetallePresupuesto_Select($conn_sis, $bbddSql, $viejoPresupuesto, $nuevoPresupuesto)
{
    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[presupuestos detalle]
        (presupuesto, 
        unidades, 
        precio, 
        descripcion, 
        notaCibeles, 
        orden, 
        idConcepto, 
        idTipo, 
        idDepartamento, 
        notaAdmonProd, 
        exentoIVA,
        idMaterialPapel,
        idTipoImpresora,
        impresionNumeroCaras,
        idPapelTamanioFinal,
        pesoGramos,
        idGFConcepto,
        idGFMetrosCuadrados,
        noVisible)
        SELECT 
            ?, 
            unidades, 
            precio, 
            descripcion, 
            notaCibeles, 
            orden, 
            idConcepto, 
            idTipo,
            idDepartamento, 
            notaAdmonProd, 
            exentoIVA,
            idMaterialPapel,
            idTipoImpresora,
            impresionNumeroCaras,
            idPapelTamanioFinal,
            pesoGramos,
            idGFConcepto,
            idGFMetrosCuadrados,
            noVisible
        FROM [".$bbddSql."].[dbo].[presupuestos detalle]
        WHERE presupuesto = ?
    ";

    $params = array(
        $nuevoPresupuesto,
        $viejoPresupuesto
    );

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);
   
    if ($resultado === false) {
        $error = print_r(sqlsrv_errors(), true);        

        return array(
            'error' => $error,
            'ok' => false,
            'sql' => $consulta,
            'params' => $params            
        );
    }

    return array(
        'error' => '',
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );
    
}

function insertarPresupuestoMensualCopia ($conn_sis, $bbddSql, $contadorViejo, $contadorNuevo, $fechaInicio, $fechaAceptacion, $fechaFin, $fechaCompromiso)
{

	$consulta = "
            INSERT INTO [".$bbddSql."].[dbo].[presupuestos]
           ([presupuesto]
           ,[letra]
           ,[cliente]
           ,[codigoCliente]
           ,[persona]
           ,[direccion]
           ,[poblacion]
           ,[cp]
           ,[pago]
           ,[notaCibeles]
           ,[forma de pago]
           ,[campana]
           ,[cantidad]           
           ,[comercial]
           ,[pedcli]
           ,[fechaAceptacion]
           ,[fechaCompromiso]
           ,[fechaTerminado]
           ,[factura]
           ,[detallada]
           ,[idComercial]
           ,[idFormaPago]
           ,[idVisualizarTotalPresu]
           ,[idVisualizarTotalFranqueo]
           ,[importeFranqueo]
           ,[otBajada]
           ,[otAbierta]
           ,[fechaInicioReal]
           ,[noRetrasar]
           ,[campana2]
           ,[cantidad2]
           ,[pdfGenerado]
		   ,[observaciones2]
		   ,[clayma])
     
	 SELECT ?
      ,''
      ,[cliente]
      ,[codigoCliente]
      ,[persona]
      ,[direccion]
      ,[poblacion]
      ,[cp]
      ,[pago]
      ,[notaCibeles]
      ,[forma de pago]
      ,[campana]
      ,[cantidad]     
      ,[comercial]
      ,[pedcli]
      ,?
      ,?
      ,?
      ,[factura]
      ,[detallada]
      ,[idComercial]
      ,[idFormaPago]
      ,[idVisualizarTotalPresu]
      ,[idVisualizarTotalFranqueo]
      ,[importeFranqueo]
      ,[otBajada]
      ,[otAbierta]
      ,?
      ,[noRetrasar]
      ,[campana2]
      ,[cantidad2]
      ,0
	  ,[observaciones2]
	  ,[clayma]
  FROM [".$bbddSql."].[dbo].[presupuestos]
 where presupuesto = ?";


    $params = array(
        $contadorNuevo,
        $fechaAceptacion,
        $fechaCompromiso,
        $fechaFin,
        $fechaInicio,
        $contadorViejo
    );

	$resultado = sqlsrv_query($conn_sis, $consulta, $params);
   
    if ($resultado === false) {
        $error = print_r(sqlsrv_errors(), true);        

        return array(
            'error' => $error,
            'ok' => false,
            'sql' => $consulta,
            'params' => $params            
        );
    }

    return array(
        'error' => '',
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );
}

function crearNuevoProcesoPresupuesto($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'id' => 'id',
        'idTipoProceso' => 'idTipoProceso',
        'idDepartamento' => 'idDepartamento',
        'proceso' => 'proceso',
        'descripcion' => 'descripcion',
        'mostrarEnInforme' => 'mostrarEnInforme'
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'crearNuevoProcesoPresupuesto: datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'crearNuevoProcesoPresupuesto: camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[procesos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
  
    return array(
        'error' => '',
        'ok' => true,   
        //'id' => $idInsertado,   
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarProvisionFondo($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'idCliente' => 'idCliente',
        'importe' => 'importe',
        'tipo' => 'tipo',
        'contador' => 'contador',
        'clayma' => 'clayma',
        'concepto' => 'concepto'         
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[provisionesDeFondo]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarTamanioPapel($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'tamano' => 'tamano'
          
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[L_papelTamanio]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarTamanioConversor($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idTamanioInicio' => 'idTamanioInicio',
        'idTamanioFinal' => 'idTamanioFinal',
        'valor' => 'valor'
          
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[L_papelTamanioConversor]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarTipoPapel($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'tipo' => 'tipo'          
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[L_papelTipo]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarAcabadoPapel($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'acabado' => 'acabado'          
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[L_papelAcabado]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarGramajePapel($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'gramaje' => 'gramaje'          
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[L_papelGramaje]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarGrabacionFranqueo($conn_sis, $bbddSql, $datos) //el campo idEmpleado es obligatorio siempre
{
    // ---------- VALIDAR DATOS ----------
    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }   

    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(
        'fecha' => 'fecha',
        'idCliente' => 'idCliente',
        'ot' => 'ot',
        'otSidi' => 'otSidi',
        'importe' => 'importe',
        'envios' => 'envios',
        'producto' => 'producto',
        'detalle' => 'detalle',
        'anadidos' => 'anadidos',
        'comprobado' => 'comprobado'
    );

    // ---------- FECHAS ----------
    $fecha1 = date("d-m-Y", strtotime($datos['fecha']));
    $fecha2 = date("d/m/Y", strtotime($datos['fecha']));

    // ---------- REFERENCIA AUTOMÁTICA ----------
    $camposSQL = array(
        'referencia'
    );

    $selectSQL = array(
        "
        CONCAT(
            ?,
            ?,
            RIGHT(
                '00000000' + CAST(ISNULL(MAX(id), 0) + 1 AS varchar(8)),
                8
            )
        )
        "
    );

    $params = array(
        $fecha2,
        $datos['idEmpleado']
    );

    // ---------- RESTO DE CAMPOS ----------
    foreach ($datos as $campo => $valor) {

        if ($campo == 'idEmpleado') {
            continue;
        }

        if (isset($camposPermitidos[$campo])) {

            $camposSQL[] = $camposPermitidos[$campo];

            if ($campo == 'fecha') {
                $selectSQL[] = '?';
                $params[] = $fecha1;
            } else {
                $selectSQL[] = '?';
                $params[] = $valor;
            }
        }
    }

    if (count($camposSQL) <= 1) {
        return array(
            'error' => 'no hay campos validos para insertar',
            'ok' => false
        );
    }

    // ---------- TABLA ----------
    $tabla = "[".$bbddSql."].[dbo].[franqueo]";
    // ---------- SQL ----------
    $consulta = "
        INSERT INTO $tabla
        (".implode(', ', $camposSQL).")
        SELECT
            ".implode(', ', $selectSQL)."
        FROM $tabla
    ";

    // ---------- EJECUTAR ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'sql' => $consulta,
        'params' => $params
    );


}

function insertarGrabacionFranqueoTipos($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(       
        'idCliente' => 'idCliente',
        'ot' => 'ot',
        'otSidi' => 'otSidi',
        'fecha' => 'fecha',
        'tipo' => 'tipo',
        'unidades' => 'unidades',
        'importe' => 'importe',
        'referencia' => 'referencia',
        'importeSinIva' => 'importeSinIva',
        'numSeguimiento' => 'numSeguimiento',
        'importado' => 'importado',
        'txt' => 'txt',
        'comprobado' => 'comprobado',
        'nombre' => 'nombre',
        'direccion' => 'direccion',
        'poblacion' => 'poblacion',
        'cp' => 'cp',
        'gramos' => 'gramos'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[franqueoTipos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarProvisionDeFondo_movimientos($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(       
        'codigoCliente' => 'codigoCliente',
        'fecha' => 'fecha',
        'formaPago' => 'formaPago',
        'importe' => 'importe',
        'presupuesto' => 'presupuesto',
        'fechaCuadre' => 'fechaCuadre',
        'informacionCuadre' => 'informacionCuadre',
        'saldoPostPF' => 'saldoPostPF',
        'clayma' => 'clayma'        
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[provisionDeFondo_movimientos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarDatosFranqueoExportar($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(       
        'uno' => 'uno',
        'dos' => 'dos',
        'tres' => 'tres',
        'cuatro' => 'cuatro',
        'cinco' => 'cinco',
        'seis' => 'seis',
        'siete' => 'siete',
        'referencia' => 'referencia',
        'idUnico' => 'idUnico',
        'orden' => 'orden'     
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[franqueoExportarCorreos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarFranqueoPagado($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'idProductoPadre' => 'idProductoPadre',
        'fecha' => 'fecha',
        'unidades' => 'unidades',
        'ot' => 'ot',
        'tipoCert_Not' => 'tipoCert_Not',
        'idEmpleado' => 'idEmpleado'      
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[franqueoPagado]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarFormaPago($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'concepto' => 'concepto'          
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[formaDePago]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesDirecRutas($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'att' => 'att',
        'nombre' => 'nombre',
        'direccion' => 'direccion',
        'cp' => 'cp',
        'poblacion' => 'poblacion',
        'provincia' => 'provincia',
        'pais' => 'pais',
        'activo' => 'activo',
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[clientesDirecRutas]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesDirecRutasClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'att' => 'att',
        'nombre' => 'nombre',
        'direccion' => 'direccion',
        'cp' => 'cp',
        'poblacion' => 'poblacion',
        'provincia' => 'provincia',
        'pais' => 'pais',
        'activo' => 'activo',
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[clientesDirecRutasClayma]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesObservaciones($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'asunto' => 'asunto',
        'observacion' => 'observacion',
        'fecha' => 'fecha',
        'idEmpleado' => 'idEmpleado'       
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[clientesObservaciones]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesObservacionesClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'asunto' => 'asunto',
        'observacion' => 'observacion',
        'fecha' => 'fecha',
        'idEmpleado' => 'idEmpleado'       
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[clientesObservacionesClayma]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesContactos($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'idSexo' => 'idSexo',
        'nombre' => 'nombre',
        'apellidos' => 'apellidos',
        'departamento' => 'departamento',
        'cargo' => 'cargo',
        'telefono' => 'telefono',
        'movil' => 'movil',
        'email' => 'email',
        'comentario' => 'comentario'
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[clientesContactos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesContactosClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'idSexo' => 'idSexo',
        'nombre' => 'nombre',
        'apellidos' => 'apellidos',
        'departamento' => 'departamento',
        'cargo' => 'cargo',
        'telefono' => 'telefono',
        'movil' => 'movil',
        'email' => 'email',
        'comentario' => 'comentario'
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[clientesContactosClayma]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);
    /*
    $consultaId = "SELECT SCOPE_IDENTITY() AS id";
    $stmtId = sqlsrv_query($conn_sis, $consultaId);

    $idInsertado = null;

    if ($stmtId !== false) {
        $filaId = sqlsrv_fetch_array($stmtId, SQLSRV_FETCH_ASSOC);
        if ($filaId && isset($filaId['id'])) {
            $idInsertado = $filaId['id'];
        }
        sqlsrv_free_stmt($stmtId);
    }
    */
    return array(
        'error' => '',
        'ok' => true,
        //'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientes($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'codigo' => 'codigo',
        'codigo_saldo' => 'codigo_saldo',
        'nombre_empresa' => 'nombre_empresa',
        'nombre_franqueo' => 'nombre_franqueo',
        'subcliente' => 'subcliente',
        'nif' => 'nif',
        'nif_subcliente' => 'nif_subcliente',
        'direccion' => 'direccion',
        'localidad' => 'localidad',
        'provincia' => 'provincia',
        'codigo_postal' => 'codigo_postal',
        'idComercial' => 'idComercial',
        'idDiasDePago' => 'idDiasDePago',
        'idFormaPago' => 'idFormaPago',
        'email' => 'email',
        'fac_cuotaRecogida' => 'fac_cuotaRecogida',
        'fac_idPeriodo' => 'fac_idPeriodo',
        'fac_porCientoNoBonificable' => 'fac_porCientoNoBonificable',
        'fac_otrosConceptosFijos' => 'fac_otrosConceptosFijos',
        'fac_importeFijoOtrosConcepto' => 'fac_importeFijoOtrosConcepto',
        'fac_idProvisionFondos' => 'fac_idProvisionFondos',
        'fac_cobroUnitarioEnvio' => 'fac_cobroUnitarioEnvio',
        'envio_att' => 'envio_att',
        'envio_nombre' => 'envio_nombre',
        'envio_domicilio' => 'envio_domicilio',
        'envio_cp' => 'envio_cp',
        'envio_poblacion' => 'envio_poblacion',
        'envio_provincia' => 'envio_provincia',
        'envio_pais' => 'envio_pais',
        'numCuentaBanco' => 'numCuentaBanco',
        'correoDiario' => 'correoDiario',
        'activo' => 'activo',
        'fac_pfFijaImporte' => 'fac_pfFijaImporte',
        'domiciliada' => 'domiciliada',
        'nuestraCuenta' => 'nuestraCuenta',
        'sinIva' => 'sinIva',
        'retener' => 'retener',
        'pedidoCliente' => 'pedidoCliente',
        'vencimiento' => 'vencimiento',
        'prefactura' => 'prefactura',
        'noAplicarPF' => 'noAplicarPF',
        'retencion' => 'retencion',
        'pais' => 'pais',
        'codigoPais' => 'codigoPais',
        'codigoSidi' => 'codigoSidi',
        'importePF' => 'importePF'
        
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];

            if ($campo == 'codigo')
            {
                $placeholders[] = '@NuevoCodigo';
            }
            else if ($campo == 'codigo_saldo')
            {
                if ((int)$valor == 0)
                {
                    $placeholders[] = '@NuevoCodigo';
                }
                else
                {
                    $placeholders[] = '?';
                    $params[] = $valor;
                }
            }
            else
            {
                $placeholders[] = '?';
                $params[] = $valor;
            }
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        SET XACT_ABORT ON;

        BEGIN TRANSACTION;

        DECLARE @NuevoCodigo INT;

        SELECT @NuevoCodigo = ISNULL(MAX(codigo), 0) + 1
        FROM [".$bbddSql."].[dbo].[clientes] WITH (UPDLOCK, HOLDLOCK);

        INSERT INTO [".$bbddSql."].[dbo].[clientes]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).");

        COMMIT TRANSACTION;

        SELECT @NuevoCodigo AS codigo;
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    /*
     * La consulta ejecuta primero el INSERT y después:
     *
     * SELECT @NuevoCodigo AS codigo
     *
     * Hay que avanzar hasta encontrar ese conjunto de resultados.
     */
    $codigoInsertado = null;

    do {
        if (sqlsrv_num_fields($resultado) > 0) {
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

            if ($fila !== null && isset($fila['codigo'])) {
                $codigoInsertado = $fila['codigo'];
                break;
            }
        }
    } while (sqlsrv_next_result($resultado));

    if ($codigoInsertado === null) {
        $errores = sqlsrv_errors();

        sqlsrv_free_stmt($resultado);

        return array(
            'error' => 'Cliente insertado, pero no se pudo recuperar el codigo nuevo'
                . ($errores ? ': ' . print_r($errores, true) : ''),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'codigo' => $codigoInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function insertarClientesClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'codigo' => 'codigo',
        'codigo_saldo' => 'codigo_saldo',
        'nombre_empresa' => 'nombre_empresa',
        'nombre_franqueo' => 'nombre_franqueo',
        'subcliente' => 'subcliente',
        'nif' => 'nif',
        'nif_subcliente' => 'nif_subcliente',
        'direccion' => 'direccion',
        'localidad' => 'localidad',
        'provincia' => 'provincia',
        'codigo_postal' => 'codigo_postal',
        'idComercial' => 'idComercial',
        'idDiasDePago' => 'idDiasDePago',
        'idFormaPago' => 'idFormaPago',
        'email' => 'email',
        'fac_cuotaRecogida' => 'fac_cuotaRecogida',
        'fac_idPeriodo' => 'fac_idPeriodo',
        'fac_porCientoNoBonificable' => 'fac_porCientoNoBonificable',
        'fac_otrosConceptosFijos' => 'fac_otrosConceptosFijos',
        'fac_importeFijoOtrosConcepto' => 'fac_importeFijoOtrosConcepto',
        'fac_idProvisionFondos' => 'fac_idProvisionFondos',
        'fac_cobroUnitarioEnvio' => 'fac_cobroUnitarioEnvio',
        'envio_att' => 'envio_att',
        'envio_nombre' => 'envio_nombre',
        'envio_domicilio' => 'envio_domicilio',
        'envio_cp' => 'envio_cp',
        'envio_poblacion' => 'envio_poblacion',
        'envio_provincia' => 'envio_provincia',
        'envio_pais' => 'envio_pais',
        'numCuentaBanco' => 'numCuentaBanco',
        'correoDiario' => 'correoDiario',
        'activo' => 'activo',
        'fac_pfFijaImporte' => 'fac_pfFijaImporte',
        'domiciliada' => 'domiciliada',
        'nuestraCuenta' => 'nuestraCuenta',
        'sinIva' => 'sinIva',
        'retener' => 'retener',
        'pedidoCliente' => 'pedidoCliente',
        'vencimiento' => 'vencimiento',
        'prefactura' => 'prefactura',
        'noAplicarPF' => 'noAplicarPF',
        'retencion' => 'retencion',
        'pais' => 'pais',
        'codigoPais' => 'codigoPais',
        'codigoSidi' => 'codigoSidi',
        'importePF' => 'importePF'
        
    );    

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'datos vacios',
            'ok' => false
        );
    }

    $camposSQL = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];

            if ($campo == 'codigo')
            {
                $placeholders[] = '@NuevoCodigo';
            }
            else if ($campo == 'codigo_saldo')
            {
                if ((int)$valor == 0)
                {
                    $placeholders[] = '@NuevoCodigo';
                }
                else
                {
                    $placeholders[] = '?';
                    $params[] = $valor;
                }
            }
            else
            {
                $placeholders[] = '?';
                $params[] = $valor;
            }
        }
    }

    if (empty($camposSQL)) {
        return array(
            'error' => 'camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        SET XACT_ABORT ON;

        BEGIN TRANSACTION;

        DECLARE @NuevoCodigo INT;

        SELECT @NuevoCodigo = ISNULL(MAX(codigo), 0) + 1
        FROM [".$bbddSql."].[dbo].[clientesClayma] WITH (UPDLOCK, HOLDLOCK);

        INSERT INTO [".$bbddSql."].[dbo].[clientesClayma]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).");

        COMMIT TRANSACTION;

        SELECT @NuevoCodigo AS codigo;
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    /*
     * La consulta ejecuta primero el INSERT y después:
     *
     * SELECT @NuevoCodigo AS codigo
     *
     * Hay que avanzar hasta encontrar ese conjunto de resultados.
     */
    $codigoInsertado = null;

    do {
        if (sqlsrv_num_fields($resultado) > 0) {
            $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);

            if ($fila !== null && isset($fila['codigo'])) {
                $codigoInsertado = $fila['codigo'];
                break;
            }
        }
    } while (sqlsrv_next_result($resultado));

    if ($codigoInsertado === null) {
        $errores = sqlsrv_errors();

        sqlsrv_free_stmt($resultado);

        return array(
            'error' => 'Cliente insertado, pero no se pudo recuperar el codigo nuevo'
                . ($errores ? ': ' . print_r($errores, true) : ''),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'codigo' => $codigoInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

//UPDATE
function modificarDetallePresupuesto($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        //'presupuesto' => 't1.presupuesto',
        'concepto' => 't1.concepto',
        'grupo' => 't1.grupo',
        'unidades' => 't1.unidades',
        'unidades2' => 't1.unidades2',
        'precio' => 't1.precio',
        'descripcion' => 't1.descripcion',
        'notaCibeles' => 't1.notaCibeles',
        'orden' => 't1.orden',
        'idConcepto' => 't1.idConcepto',
        'idTipo' => 't1.idTipo',
        'idDepartamento' => 't1.idDepartamento',
        'notaAdmonProd' => 't1.notaAdmonProd',
        'exentoIVA' => 't1.exentoIVA',
        'idMaterialPapel' => 't1.idMaterialPapel',
        'idTipoImpresora' => 't1.idTipoImpresora',
        'impresionNumeroCaras' => 't1.impresionNumeroCaras',
        'idPapelTamanioFinal' => 't1.idPapelTamanioFinal',
        'pesoGramos' => 't1.pesoGramos',
        'idGFConcepto' => 't1.idGFConcepto',
        'idGFMetrosCuadrados' => 't1.idGFMetrosCuadrados',
        'noVisible' => 't1.noVisible'     
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarDetallePresupuesto: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarDetallePresupuesto: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarDetallePresupuesto: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarPresupuesto($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        //'presupuesto' => 't1.presupuesto',
        'letra' => 't1.letra',
        'cliente' => 't1.cliente',
        'codigoCliente' => 't1.codigoCliente',
        'persona' => 't1.persona',
        'direccion' => 't1.direccion',
        'poblacion' => 't1.poblacion',
        'cp' => 't1.cp',
        'pago' => 't1.pago',
        'notaCibeles' => 't1.notaCibeles',
        'forma de pago' => 't1.forma de pago',
        'campana' => 't1.campana',
        'campanaObservacion' => 't1.campanaObservacion',
        'cantidad' => 't1.cantidad',
        'fecha' => 't1.fecha',
        'comercial' => 't1.comercial',
        'pedcli' => 't1.pedcli',
        'fechaAceptacion' => 't1.fechaAceptacion',
        'fechaCompromiso' => 't1.fechaCompromiso',
        'fechaTerminado' => 't1.fechaTerminado',
        'factura' => 't1.factura',
        'detallada' => 't1.detallada',
        'idComercial' => 't1.idComercial',
        'idFormaPago' => 't1.idFormaPago',
        'idVisualizarTotalPresu' => 't1.idVisualizarTotalPresu',
        'idVisualizarTotalFranqueo' => 't1.idVisualizarTotalFranqueo',
        'importeFranqueo' => 't1.importeFranqueo',
        'otBajada' => 't1.otBajada',
        'otAbierta' => 't1.otAbierta',
        'fechaInicioReal' => 't1.fechaInicioReal',
        'noRetrasar' => 't1.noRetrasar',
        'campana2' => 't1.campana2',
        'cantidad2' => 't1.cantidad2',
        'pdfGenerado' => 't1.pdfGenerado',
        'clayma' => 't1.clayma',
        'numNoFactura' => 't1.numNoFactura',
        'numNoFacturaFecha' => 't1.numNoFacturaFecha',
        'noSeFacturaObservaciones' => 't1.noSeFacturaObservaciones',
        'observaciones2' => 't1.observaciones2',
        'noFacProcesado' => 't1.noFacProcesado',
        'bbddBorrado' => 't1.bbddBorrado',
        'fechaAceptacionRegistro' => 't1.fechaAceptacionRegistro',
        'otSidi' => 't1.otSidi',
        'trabajoIniciado' => 't1.trabajoIniciado'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarPresupuesto: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarPresupuesto: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarPresupuesto: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[presupuestos] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarProvisionFondo($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
       
        'cobrada' => 't1.cobrada',
        'borradaComercial' => 't1.borradaComercial',
        'fechaCobro' => 't1.fechaCobro',
        'formaPago' => 't1.formaPago',
        'importe' => 't1.importe'     
        
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarProvisionFondos: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarProvisionFondos: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarProvisionFondos: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[provisionesDeFondo] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarTamanioPapel($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'tamano' => 't1.tamano'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarProvisionFondos: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarTamanioPapel: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarTamanioPapel: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[L_papelTamanio] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarTamanioConversor($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'idTamanioInicio' => 't1.idTamanioInicio',
        'idTamanioFinal' => 'idTamanioFinal',
        'valor' => 'valor'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarTamanioConversor: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarTamanioPapel: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarTamanioConversor: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[L_papelTamanioConversor] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarTipoPapel($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'tipo' => 't1.tipo'        
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarTipo: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarTipo: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarTipo: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[L_papelTipo] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarAcabadoPapel($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'acabado' => 't1.acabado'        
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarAcabado: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarAcabado: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarTipo: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[L_papelAcabado] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarGramajePapel($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'gramaje' => 't1.gramaje'        
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarGramaje: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarGramaje: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarTipo: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[L_papelGramaje] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarFranqueo($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'comprobado' => 't1.comprobado',
        'referencia' => 't1.referencia',
        'fecha' => 't1.fecha',
        'idCliente' => 't1.idCliente',
        'ot' => 't1.ot',
        'otSidi' => 't1.otSidi',
        'importe' => 't1.importe',
        'envios' => 't1.envios',
        'detalle' => 't1.detalle',
        'anadidos' => 't1.anadidos'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarFranqueo: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarFranqueo: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['comprobado'])) {
        $condicion[] = 't1.comprobado = ?';
        $params[] = $filtros['comprobado'];
    }
    if (isset($filtros['referencia'])) {
        $condicion[] = 't1.referencia = ?';
        $params[] = $filtros['referencia'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarFranqueo: update sin WHERE bloqueado por seguridad',
            'ok' => false,           
            'params' => $params,
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[franqueo] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarFranqueoTipos($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
       'comprobado' => 't1.comprobado',
       'tipo' => 't1.tipo',
       'unidades' => 't1.unidades',
       'importe' => 't1.importe',
       'ot' => 't1.ot',
       'otSidi' => 't1.otSidi',
       'fecha' => 't1.fecha',
       'idCliente' => 't1.idCliente',
       'importeSinIva' => 't1.importeSinIva'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarFranqueoTipos: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarFranqueoTipos: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    
    if (isset($filtros['comprobado'])) {
        $condicion[] = 't1.comprobado = ?';
        $params[] = $filtros['comprobado'];
    }
    if (isset($filtros['referencia'])) {
        $condicion[] = 't1.referencia = ?';
        $params[] = $filtros['referencia'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idAutorizacionFranqueo'])) {
        $condicion[] = 't1.idAutorizacionFranqueo = ?';
        $params[] = $filtros['idAutorizacionFranqueo'];
    }
    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarFranqueoTipos: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[franqueoTipos] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarClientes($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores, $datosIncremento)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
       'importePF' => 't1.importePF',
       'fechaCobroPF' => 't1.fechaCobroPF',
       'idAutorizacionFranqueo' => 't1.idAutorizacionFranqueo',
       'direccion' => 't1.direccion',
       'localidad' => 't1.localidad',
       'provincia' => 't1.provincia',
       'codigo_postal' => 't1.codigo_postal',
       'pais' => 't1.pais',
       'codigoPais' => 't1.codigoPais',
       'idComercial' => 't1.idComercial',
       'codigoSidi' => 't1.codigoSidi',
       'idDiasDePago' => 't1.idDiasDePago',
       'idFormaPago' => 't1.idFormaPago',
       'email' => 't1.email',
       'numCuentaBanco' => 't1.numCuentaBanco',
       'nuestraCuenta' => 't1.nuestraCuenta',
       'correoDiario' => 't1.correoDiario',
       'activo' => 't1.activo',
       'domiciliada' => 't1.domiciliada',
       'sinIva' => 't1.sinIva',
       'retener' => 't1.retener',
       'prefactura' => 't1.prefactura',
       'noAplicarPF' => 't1.noAplicarPF',
       'retencion' => 't1.retencion',
       'fac_cuotaRecogida' => 't1.fac_cuotaRecogida',
       'fac_idPeriodo' => 't1.fac_idPeriodo',
       'fac_porCientoNoBonificable' => 't1.fac_porCientoNoBonificable',
       'fac_otrosConceptosFijos' => 't1.fac_otrosConceptosFijos',
       'fac_importeFijoOtrosConcepto' => 't1.fac_importeFijoOtrosConcepto',
       'fac_idProvisionFondos' => 't1.fac_idProvisionFondos',
       'fac_cobroUnitarioEnvio' => 't1.fac_cobroUnitarioEnvio',
       'fac_pfFijaImporte' => 't1.fac_pfFijaImporte',
       'envio_att' => 't1.envio_att',
       'envio_nombre' => 't1.envio_nombre',
       'envio_domicilio' => 't1.envio_domicilio',
       'envio_cp' => 't1.envio_cp',
       'envio_poblacion' => 't1.envio_poblacion',
       'envio_provincia' => 't1.envio_provincia',
       'envio_pais' => 't1.envio_pais',
       'pedidoCliente' => 't1.pedidoCliente',
       'vencimiento' => 't1.vencimiento'
     
    );

    if ((!is_array($datos) || empty($datos)) && (!is_array($datosIncremento) || empty($datosIncremento)))
    {
        return array(
            'error' => 'modificarClientes: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    foreach ($datosIncremento as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ISNULL(' . $camposPermitidos[$campo] . ', 0) + ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarClientes: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();
    
    if (isset($filtros['codigo'])) {
        $condicion[] = 't1.codigo = ?';
        $params[] = $filtros['codigo'];
    }
    if (isset($filtros['idAutorizacionFranqueo'])) {
        $condicion[] = 't1.idAutorizacionFranqueo = ?';
        $params[] = $filtros['idAutorizacionFranqueo'];
    }
    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarClientes: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[clientes] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarClientesClayma($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores, $datosIncremento)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
       'importePF' => 't1.importePF',
       'fechaCobroPF' => 't1.fechaCobroPF',
       'idAutorizacionFranqueo' => 't1.idAutorizacionFranqueo',
       'direccion' => 't1.direccion',
       'localidad' => 't1.localidad',
       'provincia' => 't1.provincia',
       'codigo_postal' => 't1.codigo_postal',
       'pais' => 't1.pais',
       'codigoPais' => 't1.codigoPais',
       'idComercial' => 't1.idComercial',
       'codigoSidi' => 't1.codigoSidi',
       'idDiasDePago' => 't1.idDiasDePago',
       'idFormaPago' => 't1.idFormaPago',
       'email' => 't1.email',
       'numCuentaBanco' => 't1.numCuentaBanco',
       'nuestraCuenta' => 't1.nuestraCuenta',
       'correoDiario' => 't1.correoDiario',
       'activo' => 't1.activo',
       'domiciliada' => 't1.domiciliada',
       'sinIva' => 't1.sinIva',
       'retener' => 't1.retener',
       'prefactura' => 't1.prefactura',
       'noAplicarPF' => 't1.noAplicarPF',
       'retencion' => 't1.retencion',
       'fac_cuotaRecogida' => 't1.fac_cuotaRecogida',
       'fac_idPeriodo' => 't1.fac_idPeriodo',
       'fac_porCientoNoBonificable' => 't1.fac_porCientoNoBonificable',
       'fac_otrosConceptosFijos' => 't1.fac_otrosConceptosFijos',
       'fac_importeFijoOtrosConcepto' => 't1.fac_importeFijoOtrosConcepto',
       'fac_idProvisionFondos' => 't1.fac_idProvisionFondos',
       'fac_cobroUnitarioEnvio' => 't1.fac_cobroUnitarioEnvio',
       'fac_pfFijaImporte' => 't1.fac_pfFijaImporte',
       'envio_att' => 't1.envio_att',
       'envio_nombre' => 't1.envio_nombre',
       'envio_domicilio' => 't1.envio_domicilio',
       'envio_cp' => 't1.envio_cp',
       'envio_poblacion' => 't1.envio_poblacion',
       'envio_provincia' => 't1.envio_provincia',
       'envio_pais' => 't1.envio_pais',
       'pedidoCliente' => 't1.pedidoCliente',
       'vencimiento' => 't1.vencimiento'
    );

    if ((!is_array($datos) || empty($datos)) && (!is_array($datosIncremento) || empty($datosIncremento)))
    {
        return array(
            'error' => 'modificarClientes: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    foreach ($datosIncremento as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ISNULL(' . $camposPermitidos[$campo] . ', 0) + ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarClientes: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();
    
    if (isset($filtros['codigo'])) {
        $condicion[] = 't1.codigo = ?';
        $params[] = $filtros['codigo'];
    }
    if (isset($filtros['idAutorizacionFranqueo'])) {
        $condicion[] = 't1.idAutorizacionFranqueo = ?';
        $params[] = $filtros['idAutorizacionFranqueo'];
    }
    

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarClientes: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[clientesClayma] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarFranqueoPagado($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'unidades' => 't1.unidades',
        'ot' => 't1.ot',
        'tipoCert_Not' => 't1.tipoCert_Not'      
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificar Franqueo Pagado: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificar Franqueo Pagado: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificar Franqueo Pagado: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[franqueoPagado] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarFacturacion($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'formaPagoReal' => 't1.formaPagoReal',
        'fechaPago' => 't1.fechaPago'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modifica Facturacion: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarFacturacion: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['numeroFacturaCompleto'])) {
        $condicion[] = 't1.numeroFacturaCompleto = ?';
        $params[] = $filtros['numeroFacturaCompleto'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarFacturacion: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[facturacion] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarFacturacionCorreos($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'formaPago' => 't1.formaPago',
        'fechaPago' => 't1.fechaPago'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modifica Facturacion: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarFacturacion: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['numeroOficial'])) {
        $condicion[] = 't1.numeroOficial = ?';
        $params[] = $filtros['numeroOficial'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarFacturacion: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[facturasCorreos] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarClientesDirecRutas($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'activo' => 't1.activo',
        'att' => 't1.att',
        'nombre' => 't1.nombre',
        'direccion' => 't1.direccion',
        'cp' => 't1.cp',
        'poblacion' => 't1.poblacion',
        'provincia' => 't1.provincia',
        'pais' => 't1.pais'       
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modifica clientesDirecRutas: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarclientesDirecRutas: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('!=');

    $camposComparablesPermitidos = array(
        'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarclientesDirecRutas: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[clientesDirecRutas] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarClientesDirecRutasClayma($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'activo' => 't1.activo',
        'att' => 't1.att',
        'nombre' => 't1.nombre',
        'direccion' => 't1.direccion',
        'cp' => 't1.cp',
        'poblacion' => 't1.poblacion',
        'provincia' => 't1.provincia',
        'pais' => 't1.pais'       
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modifica clientesDirecRutas: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificarclientesDirecRutas: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('!=');

    $camposComparablesPermitidos = array(
        'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificarclientesDirecRutas: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[clientesDirecRutasClayma] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarClientesContactos($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'idSexo' => 't1.idSexo',
        'nombre' => 't1.nombre',
        'apellidos' => 't1.apellidos',
        'departamento' => 't1.departamento',
        'cargo' => 't1.cargo',
        'telefono' => 't1.telefono',
        'movil' => 't1.movil',
        'email' => 't1.email',
        'comentario' => 't1.comentario'       
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modifica clientesContactos: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificar clientesContactos: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();
    
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('!=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificar clientesContactos: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[clientesContactos] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function modificarClientesContactosClayma($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'idSexo' => 't1.idSexo',
        'nombre' => 't1.nombre',
        'apellidos' => 't1.apellidos',
        'departamento' => 't1.departamento',
        'cargo' => 't1.cargo',
        'telefono' => 't1.telefono',
        'movil' => 't1.movil',
        'email' => 't1.email',
        'comentario' => 't1.comentario'       
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modifica clientesContactos: datos vacios',
            'ok' => false
        );
    }

    // ---------- SET ----------
    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array(
            'error' => 'modificar clientesContactos: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();
    
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }


    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('!=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id',
       // 'activo' => 't1.activo'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // campo vs campo
            if (
                isset($f['campo1'], $f['campo2'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                isset($camposComparablesPermitidos[$f['campo2']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ' .
                    $camposComparablesPermitidos[$f['campo2']];
            }

            // campo vs valor
            else if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                     $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    if (empty($condicion)) {
        return array(
            'error' => 'modificar clientesContactos: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[clientesContactosClayma] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    // filas afectadas
    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

//DELETE
function eliminarDetallePresupuesto($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminarDetallePresupuesto: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarTamaniosPapel($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Papel Tamanio: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[L_papelTamanio] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarTamanioConversor($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Papel Tamanio Conversor: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[L_papelTamanioConversor] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarTiposPapel($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Papel Tipo: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[L_papelTipo] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarAcabadosPapel($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Acabado Papel: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[L_papelAcabado] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarGramajesPapel($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Gramaje Papel: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[L_papelGramaje] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarFranqueo($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['referencia'])) {
        $condicion[] = 't1.referencia = ?';
        $params[] = $filtros['referencia'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Franqueo: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[franqueo] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarFranqueoTipos($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['referencia'])) {
        $condicion[] = 't1.referencia = ?';
        $params[] = $filtros['referencia'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Franqueo Tipos: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[franqueoTipos] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarFranqueoExportarCorreos($conn_sis, $bbddSql)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();
   

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[franqueoExportarCorreos] t1      
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarFranqueoPagado($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar Franqueo Pagado: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[franqueoPagado] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarClientesDirecRutas($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar clientesDirecRutas: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[clientesDirecRutas] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarClientesDirecRutasClayma($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar clientesDirecRutas: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[clientesDirecRutasClayma] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarClientesContactos($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar clientesContactos: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[clientesContactos] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarClientesContactosClayma($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar clientesContactos: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[clientesContactosClayma] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}

function eliminarProvisionFondos($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=');

    $camposComparablesPermitidos = array(
        //'id' => 't1.id'       
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    $f['operador'] . ' ?';

                $params[] = $f['valor'];
            }
        }
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminar ProvisionFondos: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[provisionesDeFondo] t1
        $sqlWhere
    ";

    // ---------- EJECUCIÓN ----------
    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
            'ok' => false,
            'sql' => $consulta,
            'params' => $params
        );
    }

    $filas = sqlsrv_rows_affected($resultado);

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'filas_afectadas' => $filas,
        'sql' => $consulta,
        'params' => $params
    );
}


?>