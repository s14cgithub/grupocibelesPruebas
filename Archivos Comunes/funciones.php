
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

function cargarClientes($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order)
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
        'plazoVencimiento' => 't1.plazoVencimiento'
       
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


    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    'codigo_saldo' => 't1.codigo_saldo',
    'codigo' => 't1.codigo'    
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

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre_empresa' => 't1.nombre_empresa',
        'subcliente' => 't1.subcliente'          
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

function cargarClientesClayma($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order)
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
        'noAplicarPF' => 't1.noAplicarPF'
       
       
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


    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    'codigo_saldo' => 't1.codigo_saldo',
    'codigo' => 't1.codigo'    
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

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre_empresa'     => 't1.nombre_empresa',
        'subcliente'     => 't1.subcliente'          
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
        'ivaFranqueo' => 't4.tipoIva as ivaFranqueo'        
    );

    //t2: presupuestadores
    //t3: formaDePago
    //t4: totalFranqueoTipos
    //t5: facturacion
    //t6: facturacion clayma

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
        'tabla6' => "left join [".$bbddSql."].[dbo].[facturacionClayma] as t6 on t6.presupuesto = t1.presupuesto"
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
    'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
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

function cargarPresupuestosConNumFacturas($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    // ---------- CAMPOS ----------
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
		'noSeFacturaObservaciones' => 'tabla.noSeFacturaObservaciones'
    );

    if (!is_array($campos) || empty($campos)) {

        return array(
            'error' => 'campos vacios',
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
            'error' => 'campos SQL vacios',
            'datos' => array()
        );
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    // texto + queBusca
    if (
        !empty($filtros['texto']) &&
        !empty($filtros['queBusca'])
    ) {

        $camposBusquedaPermitidos = array(

            'presupuesto' => 'tabla.presupuesto',
            'cliente' => 'tabla.cliente',
            'campana' => 'tabla.campana'
        );

        if (
            isset($camposBusquedaPermitidos[$filtros['queBusca']])
        ) {

            $condicion[] =
                $camposBusquedaPermitidos[$filtros['queBusca']] .
                ' LIKE ?';

            $params[] = '%' . $filtros['texto'] . '%';
        }
    }

    // bajada + abierta
    if (
        isset($filtros['bajada']) &&
        isset($filtros['abierta'])
    ) {

        if (
            $filtros['bajada'] == 1 ||
            $filtros['abierta'] == 1
        ) {

            $condicion[] = 'tabla.otBajada = ?';

            $params[] =
                $filtros['bajada'] == 1 ? 1 : 0;

            $condicion[] = 'tabla.otAbierta = ?';

            $params[] =
                $filtros['abierta'] == 1 ? 1 : 0;
        }
    }

     // fecha desde meses
    if (!empty($filtros['fecha'])) {

        $condicion[] = $filtros['fecha'];
    }

    // fecha aceptacion
    if (!empty($filtros['fechaAceptacion'])) {

        $fechaSinHora =
            date(
                "d-m-Y",
                strtotime(
                    $filtros['fechaAceptacion']
                )
            );

        $condicion[] =
            'tabla.fechaAceptacionRegistro >= ?';

        $params[] = $fechaSinHora;

        $condicion[] =
            'tabla.otAbierta != ?';

        $params[] = 1;
    }

    $sqlWhere = '';

    if (!empty($condicion)) {

        $sqlWhere =
            ' WHERE ' .
            implode(' AND ', $condicion);
    }

    // ---------- ORDER ----------
    $camposOrdenPermitidos = array(

        'presupuesto' => 'tabla.presupuesto',
        'cliente' => 'tabla.cliente',
        'campana' => 'tabla.campana',
        'fecha' => 'tabla.fecha',
        'fechaAceptacion' => 'tabla.fechaAceptacion',
        'nombreComercial' => 'tabla.nombreComercial',
        'origen' => 'tabla.origen'
    );

    $sqlOrder = '';

    if (!empty($order) && is_array($order)) {

        $ordenes = array();

        foreach ($order as $o) {

            if (
                isset($o['campo'], $o['dir']) &&
                isset($camposOrdenPermitidos[$o['campo']]) &&
                in_array(
                    strtoupper($o['dir']),
                    array('ASC', 'DESC')
                )
            ) {

                $ordenes[] =
                    $camposOrdenPermitidos[$o['campo']] .
                    ' ' .
                    strtoupper($o['dir']);
            }
        }

        if (!empty($ordenes)) {

            $sqlOrder =
                ' ORDER BY ' .
                implode(', ', $ordenes);
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

    $sqlWhere

    $sqlOrder
    ";

    $resultado =
        sqlsrv_query(
            $conn_sis,
            $consulta,
            $params
        );

    if ($resultado === false) {

        return array(

            'error' =>
                print_r(sqlsrv_errors(), true),
            'datos' => array(),
            'sql' => $consulta,
            'params' => $params
        );
    }

    $result = array();

    while (
        $fila =
            sqlsrv_fetch_array(
                $resultado,
                SQLSRV_FETCH_ASSOC
            )
    ) {

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

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'LIKE');

    $camposComparablesPermitidos = array(
        'presupuesto' => 't1.presupuesto',
        'codigo_saldo' => 't5.codigo_saldo',
        'codigo' => 't5.codigo',
        'codigo_saldoClayma' => 't6.codigo_saldo',
        'codigoClayma' => 't6.codigo'
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

    if (!empty($filtros['nombre'])) {
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

    
    if (!empty($filtros['tipoIva'])) {
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

    
    if (!empty($filtros['concepto'])) {
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

    
    if (!empty($filtros['departamento'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['idTipoProceso'])) {
        $condicion[] = 't1.idTipoProceso = ?';
        $params[] = $filtros['idTipoProceso'];
    }
    if (!empty($filtros['idDepartamento'])) {
        $condicion[] = 't1.idDepartamento = ?';
        $params[] = $filtros['idDepartamento'];
    }
    if (!empty($filtros['proceso'])) {
        $condicion[] = 't1.proceso = ?';
        $params[] = $filtros['proceso'];
    }
    if (!empty($filtros['descripcion'])) {
        $condicion[] = 't1.descripcion = ?';
        $params[] = $filtros['descripcion'];
    }
    if (!empty($filtros['mostrarEnInforme'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['tipo'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['tamano'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['tipo'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['acabado'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['gramaje'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['tipoImpresora'])) {
        $condicion[] = 't1.tipoImpresora = ?';
        $params[] = $filtros['tipoImpresora'];
    }
    if (!empty($filtros['precioClick'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['nombreConcepto'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['idConcepto'])) {
        $condicion[] = 't1.idConcepto = ?';
        $params[] = $filtros['idConcepto'];
    }
    if (!empty($filtros['nombreSubconcepto'])) {
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

    if (!empty($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (!empty($filtros['idSubconcepto1'])) {
        $condicion[] = 't1.idSubconcepto1 = ?';
        $params[] = $filtros['idSubconcepto1'];
    }
    if (!empty($filtros['nombreSubconcepto2'])) {
        $condicion[] = 't1.nombreSubconcepto2 = ?';
        $params[] = $filtros['nombreSubconcepto2'];
    }
    if (!empty($filtros['coste'])) {
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

    if (!empty($filtros['id'])) {
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

    if (!empty($filtros['presupuesto'])) {
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
        'borradaComercial' => 't1.borradaComercial'
        
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

    if (!empty($filtros['id'])) {
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









    


?>