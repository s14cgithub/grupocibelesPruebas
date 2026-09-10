<?php

function reemplazarSimbolos($texto)
{
	
	$resultado = $texto;

	$resultado = str_replace('€',EURO,$resultado);
	$resultado = str_replace('ñ',ene,$resultado);
	$resultado = str_replace('Ñ',ene_may,$resultado);
	$resultado = str_replace('á',a_acento,$resultado);
	$resultado = str_replace('é',e_acento,$resultado);
	$resultado = str_replace('í',i_acento,$resultado);
	$resultado = str_replace('ó',o_acento,$resultado);
	$resultado = str_replace('ú',u_acento,$resultado);
	$resultado = str_replace('Á',a_acento_may,$resultado);
	$resultado = str_replace('É',e_acento_may,$resultado);
	$resultado = str_replace('Í',i_acento_may,$resultado);
	$resultado = str_replace('Ó',o_acento_may,$resultado);
	$resultado = str_replace('Ú',u_acento_may,$resultado);

	$resultado = str_replace('º',signo_grado,$resultado);
	$resultado = str_replace('ª',signo_ordinal,$resultado);
	$resultado = str_replace('%',signo_tantoPorciento,$resultado);
	$resultado = str_replace('…',signo_tresPuntos,$resultado);
	
	$resultado = str_replace('|',lineaVertical,$resultado);
	$resultado = str_replace('·',puntoMedio,$resultado);
	$resultado = str_replace('¬',sinSigno,$resultado);
	$resultado = str_replace('¡',exclamacionAbierta,$resultado);
	$resultado = str_replace('¿',interrogacionAbierta,$resultado);
	
	$resultado = str_replace('Ç',CcedillaMayuscula,$resultado);
	$resultado = str_replace('ç',CcedillaMinuscula,$resultado);
	$resultado = str_replace('¨',CcedillaMinuscula,$resultado);
	
	$resultado = str_replace('´',acento,$resultado);
	$resultado = str_replace('`',acentoGrave,$resultado);
	$resultado = str_replace('²',superindice2,$resultado);

	$resultado = str_replace('–','-',$resultado);

	$resultado = str_replace('“','"',$resultado);
	$resultado = str_replace('”','"',$resultado);
	
	
	return $resultado;
}

function diaDeLaSemanaActual() 
{
	$diaTexto="";
	if (date("N")=="1")
	{
		$diaTexto = "Lunes";
	}
	else if (date("N")=="2")
	{
		$diaTexto = "Martes";
	}
	else if (date("N")=="3")
	{
		$diaTexto = "Miercoles";
	}
	else if (date("N")=="4")
	{
		$diaTexto = "Jueves";
	}
	else if (date("N")=="5")
	{
		$diaTexto = "Viernes";
	}
	else if (date("N")=="6")
	{
		$diaTexto = "Sabado";
	}
	
	else if (date("N")=="7")
	{
		$diaTexto = "Domingo";
	}
	return $diaTexto;
}

function mesActual()
{
	$mesTexto="";
	if (date("n")=="1")
	{		
		$mesTexto = "Enero";
	}
	else if (date("n")=="2")
	{		
		$mesTexto = "Febrero";
	}
	else if (date("n")=="3")
	{		
		$mesTexto = "Marzo";
	}
	else if (date("n")=="4")
	{		
		$mesTexto = "Abril";
	}
	else if (date("n")=="5")
	{		
		$mesTexto = "Mayo";
	}
	else if (date("n")=="6")
	{		
		$mesTexto = "Junio";
	}
	else if (date("n")=="7")
	{		
		$mesTexto = "Julio";
	}
	else if (date("n")=="8")
	{		
		$mesTexto = "Agosto";
	}
	else if (date("n")=="9")
	{		
		$mesTexto = "Septiembre";
	}
	else if (date("n")=="10")
	{		
		$mesTexto = "Octubre";
	}
	else if (date("n")=="11")
	{		
		$mesTexto = "Noviembre";
	}
	else if (date("n")=="12")
	{		
		$mesTexto = "Diciembre";
	}
	
	return $mesTexto;
}

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
        'pda_registrosHorasManuales_Comprobacion' => 't1.pda_registrosHorasManuales_Comprobacion',
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
        'observacion' => 't3.observacion',
        'nombrePais' => 't4.nombreComun as nombrePais'
       
       
    );

    //t2: franqueoTipos
    //t3: clientesObservaciones
    //t4: paises

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
        'tabla3' => "inner join [".$bbddSql."].[dbo].[clientesObservaciones] as t3 on t1.codigo = t3.idCliente",
        'tabla4' => "left join [".$bbddSql."].[dbo].[paises] as t4 on t4.id = t1.pais"
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
    if (isset($filtros['codigoSidi'])) {
        $condicion[] = 't1.codigoSidi = ?';
        $params[] = $filtros['codigoSidi'];
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
        'observacion' => 't3.observacion',
        'nombrePais' => 't4.nombreComun as nombrePais'
       
    );

    //t3: clientesObservaciones
    //t4: paises

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
        'tabla3' => "inner join [".$bbddSql."].[dbo].[clientesObservacionesClayma] as t3 on t1.codigo = t3.idCliente",
        'tabla4' => "left join [".$bbddSql."].[dbo].[paises] as t4 on t4.id = t1.pais"
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

function cargarPresupuestos($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $filtrosLike = array())
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
        'numNoFacturaMax' => 'isnull(max(numNoFactura),10000) as numNoFacturaMax',
        'inicialComercial' => 't2.inicial as inicialComercial',
        'nombreComercial' => 't2.nombre as nombreComercial',
        'telefonoComercial' => 't2.telefono as telefonoComercial',
        'textoFormaPago' => 't3.concepto as textoFormaPago',
        'ivaFranqueo' => 't4.tipoIva as ivaFranqueo',
        'nombre_franqueo' => 't7.nombre_franqueo',
        'nombre_franqueoClayma' => 't8.nombre_franqueo',
        'codigo_saldo' => 't7.codigo_saldo',
        'codigo_saldoClayma' => 't8.codigo_saldo as codigo_saldo',
        'idFormaPagoCliente' => 't7.idFormaPago as idFormaPagoCliente',
        'idFormaPagoClienteClayma' => 't8.idFormaPago as idFormaPagoCliente',
        'nuestraCuenta' => 't7.nuestraCuenta',
        'nuestraCuentaClayma' => 't8.nuestraCuenta as nuestraCuenta',
        'anios' => 'distinct(CAST(SUBSTRING(t1.presupuesto, 1, 2) AS INT) + 2000) AS anios',
        'importePresupuesto' => 't9.importePresupuesto',
        'numFactura' => 't10.numero as numFactura',
        'fechaFac' => 't10.fecha as fechaFac'
    );

    //t2: presupuestadores
    //t3: formaDePago
    //t4: totalFranqueoTipos
    //t5: facturacion
    //t6: facturacion clayma
    //t7: clientes
    //t8: clientes Clayma
    //t9: suma de presupuestos detalle (importePresupuesto)
    //t10: facturasTodosLosAnios (numFactura/fechaFac)

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
        'tabla8' => "inner join [".$bbddSql."].[dbo].[clientesClayma] as t8 on t8.codigo = t1.codigoCliente",
        'tabla9' => "left join (SELECT ISNULL(sum(ROUND(precio*unidades,2)),0) as importePresupuesto, presupuesto FROM [".$bbddSql."].[dbo].[presupuestos detalle] group by presupuesto) as t9 on t9.presupuesto = t1.presupuesto",
        'tabla10' => "left join [".$bbddSql."].[dbo].[facturasTodosLosAnios] as t10 on t10.presupuesto = t1.presupuesto"
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

    if (isset($filtros['clayma'])) {
        $condicion[] = 't1.clayma = ?';
        $params[] = $filtros['clayma'];
    }

    if (isset($filtros['codigoCliente'])) {
        $condicion[] = 't1.codigoCliente = ?';
        $params[] = $filtros['codigoCliente'];
    }

    if (array_key_exists('numNoFactura', $filtros)) {
        if ($filtros['numNoFactura'] === null) {
            $condicion[] = 't1.numNoFactura IS NULL';
        } else {
            $condicion[] = 't1.numNoFactura = ?';
            $params[] = $filtros['numNoFactura'];
        }
    }

    if (isset($filtros['noFacProcesado'])) {
        $condicion[] = 't1.noFacProcesado = ?';
        $params[] = $filtros['noFacProcesado'];
    }

    if (isset($filtros['sinProcesar']) && $filtros['sinProcesar'] == 1) {
        $condicion[] = "(t1.noFacProcesado = 0 OR t1.noFacProcesado IS NULL)";
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'LIKE', 'NOT LIKE','IS NOT NULL');
    
    $camposComparablesPermitidos = array(
        'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)',
        'anios' => '(CAST(SUBSTRING(t1.presupuesto, 1, 2) AS INT) + 2000)',
        'presupuesto' => 't1.presupuesto',
        'fechaTerminado' => 't1.fechaTerminado',
        'numNoFactura' => 't1.numNoFactura',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'noSeFacturaObservaciones' => 't1.noSeFacturaObservaciones',
        'importePresupuesto' => 't9.importePresupuesto',
        'fechaAceptacion' => 't1.fechaAceptacion'
    );
         

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {

            // Un presupuesto puede estar dentro de una factura combinada ("Comb: 1234 - 1235"),
            // asi que se excluyen tanto los presupuestos facturados directamente como los que
            // aparecen dentro de una combinacion. El LIKE solo se aplica sobre las filas que
            // empiezan por "Comb" (un subconjunto pequeno); el resto se compara por igualdad
            // (indexable), evitando el escaneo completo de facturacion/facturacionClayma que
            // hacia el NOT EXISTS + LIKE correlacionado sobre toda la tabla.
            if (
                isset($f['campo1'], $f['operador'], $f['tipoSubconsulta']) &&
                $f['campo1'] == 'presupuesto' &&
                strtoupper($f['operador']) == 'NOT LIKE' &&
                in_array($f['tipoSubconsulta'], array('presupuestosEnfacturacionClayma', 'presupuestosEnfacturacion'))
            ) {
                $tablaExclusion = ($f['tipoSubconsulta'] == 'presupuestosEnfacturacionClayma') ? 'facturacionClayma' : 'facturacion';

                $condicion[] =
                    "NOT EXISTS (
                        SELECT 1
                        FROM [".$bbddSql."].[dbo].[".$tablaExclusion."] fc
                        WHERE fc.presupuesto = t1.presupuesto
                    )
                    AND NOT EXISTS (
                        SELECT 1
                        FROM [".$bbddSql."].[dbo].[".$tablaExclusion."] fc
                        WHERE fc.presupuesto LIKE 'Comb%'
                        AND fc.presupuesto LIKE '%' + t1.presupuesto + '%'
                    )";
            }

            // campo IS NULL / IS NOT NULL (operador literal, sin campo2 ni valor)
            else if (
                isset($f['campo1'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array(strtoupper($f['operador']), array('IS NULL', 'IS NOT NULL'))
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' . strtoupper($f['operador']);
            }

            // campo vs valor nulo -> IS NULL / IS NOT NULL
            else if (
                isset($f['campo1'], $f['operador']) &&
                array_key_exists('valor', $f) &&
                $f['valor'] === null &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], array('=', '!='))
            ) {
                $condicion[] =
                    $camposComparablesPermitidos[$f['campo1']] . ' ' .
                    ($f['operador'] == '!=' ? 'IS NOT NULL' : 'IS NULL');
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
        'presupuesto' => 't1.presupuesto',
        'cliente' => 't1.cliente',
        'campana' => 't1.campana',
        'numNoFactura' => 't1.numNoFactura',
        'importePresupuesto' => 't9.importePresupuesto'
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
        'anios' => 'CAST(SUBSTRING(t1.presupuesto, 1, 2) AS INT) + 2000',
        'presupuesto' => 't1.presupuesto',
        'cliente' => 't1.cliente',
        'campana' => 't1.campana',
        'fechaTerminado' => 't1.fechaTerminado',
        'fecha' => 't1.fecha',
        'numNoFactura' => 't1.numNoFactura',
        'importePresupuesto' => 't9.importePresupuesto'
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
        'ordenTipo' => 't2.orden as ordenTipo',
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
        'gfCoste' => 't12.[coste] as gfCoste',
        'cliente' => 't15.cliente',
        'campana' => 't15.campana',
        'cantidadTrabajo' => "t15.cantidad as 'cantidad trabajo'",
        'cantidadProceso' => "t1.unidades as 'cantidad proceso'",
        'presupuestador' => 't16.nombre as presupuestador',
        'fechaCompromiso' => 't15.fechaCompromiso'
     
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
    //t15: presupuestos (padre)
    //t16: presupuestadores

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
		'tabla14' => "left join [".$bbddSql."].[dbo].[L_gf_concepto] as t14 on t13.idConcepto = t14.id",
		'tabla15' => "inner join [".$bbddSql."].[dbo].[presupuestos] as t15 on t15.presupuesto = t1.presupuesto",
		'tabla16' => "left join [".$bbddSql."].[dbo].[presupuestadores] as t16 on t16.id = t15.idComercial"
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
    if (isset($filtros['idDepartamento'])) {
        $condicion[] = 't1.idDepartamento = ?';
        $params[] = $filtros['idDepartamento'];
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
        'importeTotal' => 'isnull(sum(t1.importe),0) as importeTotal',
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
    if (isset($filtros['tipo'])) {
        $condicion[] = 't1.tipo = ?';
        $params[] = $filtros['tipo'];
    }
    if (isset($filtros['facCompletaAplicada'])) {
        $condicion[] = "(t1.facCompletaAplicada IS NULL OR t1.facCompletaAplicada = '')";
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

function mostrarFacturarFechaActual($conn_sis, $bbddSql, $campos)
{
    $camposPermitidos = array(
        'activado' => 't1.activado',
        'fechaImprimir' => 't1.fechaImprimir'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[facturarFechaActual] AS t1";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta);
}

function mostrarFacturarFechaActualClayma($conn_sis, $bbddSql, $campos)
{
    $camposPermitidos = array(
        'activado' => 't1.activado',
        'fechaImprimir' => 't1.fechaImprimir'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[facturarFechaActualClayma] AS t1";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta);
}

function modificarFacturarFechaActual($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'activado' => 'activado',
        'fechaImprimir' => 'fechaImprimir'
    );

    if (!is_array($datos) || empty($datos)) {
        return array('error' => 'modificarFacturarFechaActual: datos vacios', 'ok' => false);
    }

    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array('error' => 'modificarFacturarFechaActual: no hay campos validos para actualizar', 'ok' => false);
    }

    $consulta = "UPDATE [".$bbddSql."].[dbo].[facturarFechaActual] SET " . implode(', ', $set);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function modificarFacturarFechaActualClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'activado' => 'activado',
        'fechaImprimir' => 'fechaImprimir'
    );

    if (!is_array($datos) || empty($datos)) {
        return array('error' => 'modificarFacturarFechaActualClayma: datos vacios', 'ok' => false);
    }

    $set = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $set[] = $camposPermitidos[$campo] . ' = ?';
            $params[] = $valor;
        }
    }

    if (empty($set)) {
        return array('error' => 'modificarFacturarFechaActualClayma: no hay campos validos para actualizar', 'ok' => false);
    }

    $consulta = "UPDATE [".$bbddSql."].[dbo].[facturarFechaActualClayma] SET " . implode(', ', $set);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function insertarFacturacion($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'cliente' => 'cliente',
        'idCodigoCliente' => 'idCodigoCliente',
        'descripcion' => 'descripcion',
        'fecha' => 'fecha',
        'inicialComercial' => 'inicialComercial',
        'precioNeto' => 'precioNeto',
        'tipoIva' => 'tipoIva',
        'precioNetoExentoIva' => 'precioNetoExentoIva',
        'iva' => 'iva',
        'irpf' => 'irpf',
        'precioTotal' => 'precioTotal',
        'provision' => 'provision',
        'aPagar' => 'aPagar',
        'cantidad' => 'cantidad',
        'pedido' => 'pedido',
        'formaPago' => 'formaPago',
        'detallada' => 'detallada',
        'numCuentaBanco' => 'numCuentaBanco',
        'combinadoSumatorio' => 'combinadoSumatorio',
        'prefactura' => 'prefactura',
        'cd' => 'cd',
        'fechaInicio' => 'fechaInicio',
        'fechaFin' => 'fechaFin',
        'importeFranqueo' => 'importeFranqueo',
        'abono' => 'abono',
        'observaciones' => 'observaciones',
        'observacionesInternas' => 'observacionesInternas',
        'liquidado' => 'liquidado',
        'comprobacionError' => 'comprobacionError',
        'dirPost_nombreEmpresa' => 'dirPost_nombreEmpresa',
        'dirPost_direccion' => 'dirPost_direccion',
        'dirPost_cp' => 'dirPost_cp',
        'dirPost_poblacion' => 'dirPost_poblacion',
        'dirPost_provincia' => 'dirPost_provincia',
        'dirPost_pais' => 'dirPost_pais',
        'dirPost_codigoPais' => 'dirPost_codigoPais',
        'dirEnv_nombreEmpresa' => 'dirEnv_nombreEmpresa',
        'dirEnv_direccion' => 'dirEnv_direccion',
        'dirEnv_cp' => 'dirEnv_cp',
        'dirEnv_poblacion' => 'dirEnv_poblacion',
        'dirEnv_provincia' => 'dirEnv_provincia',
        'dirEnv_pais' => 'dirEnv_pais',
        'retener' => 'retener',
        'serieFactura' => 'serieFactura',
        'dirPost_Nif' => 'dirPost_Nif',
        'dirPost_nombrePais' => 'dirPost_nombrePais',
        'dirEnv_att' => 'dirEnv_att',
        'motivo' => 'motivo',
        'origenFactura' => 'origenFactura',
        'fechaRealizacion' => 'fechaRealizacion'
    );

    if (!is_array($datos) || empty($datos) || !isset($datos['fecha'])) {
        return array('error' => 'insertarFacturacion: datos vacios o falta fecha', 'ok' => false);
    }

    if (!isset($datos['serieFactura']) || $datos['serieFactura'] === '') {
        return array('error' => 'insertarFacturacion: falta serieFactura', 'ok' => false);
    }

    $anio = substr($datos['fecha'], -4);
    $anioDosDigitos = $anio - 2000;

    $serieFactura = $datos['serieFactura'];

    if (!isset($datos['numCuentaBanco']) || trim($datos['numCuentaBanco'])==='') {
        $datos['numCuentaBanco'] = "ES21 2100 1945 2402 0000 7147 CAIXESBBXXX\nES48 0049 1839 4621 1043 1601 BSCHESMMXXX";
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
        return array('error' => 'insertarFacturacion: camposSQL vacios', 'ok' => false);
    }

    //numeracion por serie Y año (YEAR(fecha)); UPDLOCK/HOLDLOCK serializa inserciones concurrentes de la misma serie/año
    $subNumero = "(SELECT ISNULL(MAX(numero),0) + 1 FROM [".$bbddSql."].[dbo].[facturacion] WITH (UPDLOCK, HOLDLOCK) WHERE serieFactura = ? AND YEAR(fecha) = ?)";

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturacion]
        (numero, numeroFacturaCompleto, ".implode(', ', $camposSQL).")
         OUTPUT INSERTED.numero, INSERTED.numeroFacturaCompleto
        VALUES ($subNumero, CONCAT(?, ' ', $subNumero, '/".$anioDosDigitos."'), ".implode(', ', $placeholders).")
    ";

    $finalParams = array_merge(array($serieFactura, (int)$anio, $serieFactura, $serieFactura, (int)$anio), $params);

    $resultado = sqlsrv_query($conn_sis, $consulta, $finalParams);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $finalParams);
    }

    $numero = null;
    $numeroFacturaCompleto = null;

    if (sqlsrv_fetch($resultado) !== false) {
        $numero = sqlsrv_get_field($resultado, 0);
        $numeroFacturaCompleto = sqlsrv_get_field($resultado, 1);
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'numero' => $numero,
        'numeroFacturaCompleto' => $numeroFacturaCompleto,
        'sql' => $consulta,
        'params' => $finalParams
    );
}

function insertarFacturacionClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'cliente' => 'cliente',
        'idCodigoCliente' => 'idCodigoCliente',
        'descripcion' => 'descripcion',
        'fecha' => 'fecha',
        'inicialComercial' => 'inicialComercial',
        'precioNeto' => 'precioNeto',
        'tipoIva' => 'tipoIva',
        'precioNetoExentoIva' => 'precioNetoExentoIva',
        'iva' => 'iva',
        'irpf' => 'irpf',
        'precioTotal' => 'precioTotal',
        'provision' => 'provision',
        'aPagar' => 'aPagar',
        'cantidad' => 'cantidad',
        'pedido' => 'pedido',
        'formaPago' => 'formaPago',
        'detallada' => 'detallada',
        'numCuentaBanco' => 'numCuentaBanco',
        'combinadoSumatorio' => 'combinadoSumatorio',
        'prefactura' => 'prefactura',
        'cd' => 'cd',
        'fechaInicio' => 'fechaInicio',
        'fechaFin' => 'fechaFin',
        'importeFranqueo' => 'importeFranqueo',
        'abono' => 'abono',
        'observaciones' => 'observaciones',
        'observacionesInternas' => 'observacionesInternas',
        'liquidado' => 'liquidado',
        'comprobacionError' => 'comprobacionError',
        'dirPost_nombreEmpresa' => 'dirPost_nombreEmpresa',
        'dirPost_direccion' => 'dirPost_direccion',
        'dirPost_cp' => 'dirPost_cp',
        'dirPost_poblacion' => 'dirPost_poblacion',
        'dirPost_provincia' => 'dirPost_provincia',
        'dirPost_pais' => 'dirPost_pais',
        'dirPost_codigoPais' => 'dirPost_codigoPais',
        'dirEnv_nombreEmpresa' => 'dirEnv_nombreEmpresa',
        'dirEnv_direccion' => 'dirEnv_direccion',
        'dirEnv_cp' => 'dirEnv_cp',
        'dirEnv_poblacion' => 'dirEnv_poblacion',
        'dirEnv_provincia' => 'dirEnv_provincia',
        'dirEnv_pais' => 'dirEnv_pais',
        'retener' => 'retener',
        'serieFactura' => 'serieFactura',
        'dirPost_Nif' => 'dirPost_Nif',
        'dirPost_nombrePais' => 'dirPost_nombrePais',
        'dirEnv_att' => 'dirEnv_att',
        'motivo' => 'motivo',
        'origenFactura' => 'origenFactura',
        'fechaRealizacion' => 'fechaRealizacion'
    );

    if (!is_array($datos) || empty($datos) || !isset($datos['fecha'])) {
        return array('error' => 'insertarFacturacionClayma: datos vacios o falta fecha', 'ok' => false);
    }

    if (!isset($datos['serieFactura']) || $datos['serieFactura'] === '') {
        return array('error' => 'insertarFacturacionClayma: falta serieFactura', 'ok' => false);
    }

    $anio = substr($datos['fecha'], -4);
    $anioDosDigitos = $anio - 2000;

    $serieFactura = $datos['serieFactura'];

    if (!isset($datos['numCuentaBanco']) || trim($datos['numCuentaBanco'])==='') {
        $datos['numCuentaBanco'] = "ES42 2100 1945 2202 0006 7880 CAIXESBBXXX";
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
        return array('error' => 'insertarFacturacionClayma: camposSQL vacios', 'ok' => false);
    }

    //numeracion por serie Y año (YEAR(fecha)); UPDLOCK/HOLDLOCK serializa inserciones concurrentes de la misma serie/año
    $subNumero = "(SELECT ISNULL(MAX(numero),0) + 1 FROM [".$bbddSql."].[dbo].[facturacionClayma] WITH (UPDLOCK, HOLDLOCK) WHERE serieFactura = ? AND YEAR(fecha) = ?)";

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturacionClayma]
        (numero, numeroFacturaCompleto, ".implode(', ', $camposSQL).")
         OUTPUT INSERTED.numero, INSERTED.numeroFacturaCompleto
        VALUES ($subNumero, CONCAT(?, ' ', $subNumero, '/".$anioDosDigitos."'), ".implode(', ', $placeholders).")
    ";

    $finalParams = array_merge(array($serieFactura, (int)$anio, $serieFactura, $serieFactura, (int)$anio), $params);

    $resultado = sqlsrv_query($conn_sis, $consulta, $finalParams);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $finalParams);
    }

    $numero = null;
    $numeroFacturaCompleto = null;

    if (sqlsrv_fetch($resultado) !== false) {
        $numero = sqlsrv_get_field($resultado, 0);
        $numeroFacturaCompleto = sqlsrv_get_field($resultado, 1);
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'numero' => $numero,
        'numeroFacturaCompleto' => $numeroFacturaCompleto,
        'sql' => $consulta,
        'params' => $finalParams
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
		'importeSinIva' => 't1.importeSinIva',
		'tarifa' => 't3.precioNeto + t3.iva as tarifa',
		'tarifaSinIva' => 't3.precioNeto as tarifaSinIva',
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
        'nombre_franqueo' => 't2.nombre_franqueo',
        'nombreEmpresa' => 't2.nombre_empresa as nombreEmpresa',
        'subcliente' => 't2.subcliente',
        'campana2' => 't12.campana2',
        'descripcion' => 't3.descripcion',
        'unidadesSuma' => 'sum(t1.unidades) as unidades',
        'unitario' => 'sum(t1.importe)/sum(t1.unidades) as unitario',
        'importeTotalSinIva' => 'sum(t1.importeSinIva) as importeTotalSinIva',
        'unitarioSinIva' => 'sum(t1.importeSinIva)/sum(t1.unidades) as unitarioSinIva',
        'diasFranqueados' => 'count(distinct(t1.fecha)) as diasFranqueados',
        'codigo' => 't2.codigo',
        'precioNeto' => 't3.precioNeto',
        'descuentoPorCiento' => 't13.descuentoPorCiento',
        'importeBonificacion' => 'sum(round(t3.precioNeto,2)*t1.unidades) as importe',
        'bonificacion' => 'sum(round(t3.precioNeto,2)*t1.unidades) * t13.descuentoPorCiento/100 as bonificacion'
       
      
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
        'tabla12' => "left join [".$bbddSql."].[dbo].[presupuestos] as t12 on t12.presupuesto = substring(t1.ot,3,7)",
        'tabla13' => "inner join [".$bbddSql."].[dbo].[descuentosFranqueo".$anio."] as t13 on t13.descripcion = t3.descripcion",
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
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
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
    if (isset($filtros['ot_excluirOT']) && $filtros['ot_excluirOT'] == 1) {
        $condicion[] = "t1.ot NOT LIKE 'OT%'";
    }
    if (isset($filtros['claves']) && is_array($filtros['claves']) && !empty($filtros['claves'])) {
        $placeholdersClaves = implode(',', array_fill(0, count($filtros['claves']), '?'));
        $condicion[] = "t6.clave IN ($placeholdersClaves)";
        foreach ($filtros['claves'] as $clave) {
            $params[] = $clave;
        }
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 't2.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }


    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
    //'presupuestoNoMensual' => 'SUBSTRING(t1.presupuesto, LEN(t1.presupuesto) - 2, 3)'
    'comprobado' => 't1.comprobado',
    'fecha' => 't1.fecha'
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
        'titulo' => 't6.titulo',
        'nombre_empresa' => 't2.nombre_empresa',
        'ot' => 't1.ot',
        'descripcion' => 't3.descripcion',
        'fecha' => 't1.fecha',
        'subcliente' => 't2.subcliente',
        'campana2' => 't12.campana2',
        'codigo' => 't2.codigo',
        'precioNeto' => 't3.precioNeto',
        'descuentoPorCiento' => 't13.descuentoPorCiento'
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
       'aniosDistintos' => 'YEAR(t1.fecha)',
       'codigo_saldo' => 't2.codigo_saldo',
       'nombre_empresa' => 't2.nombre_empresa',
       'descripcion' => 't3.descripcion',
       'gramos' => 't3.gramos',
       'subcliente' => 't2.subcliente',
       'codigo' => 't2.codigo'
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
            'error' => "Cargar franqueo Tipo:<pre>" . print_r(sqlsrv_errors(), true) . "</pre>",
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

function cargarConsumoPorProductosFranqueo($conn_sis, $bbddSql, $fechaInicio, $fechaFin)
{
    $fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
    $fechaFin1 = date("Y-m-d", strtotime($fechaFin));
    $anioSeleccionado = date("Y", strtotime($fechaInicio));

    $consulta = "
    select * from (
    SELECT t4.producto, sum(t1.unidades) as unidades, sum(t1.importe) as importe, sum(t1.unidades*t2.precioNeto) as sinIva, sum(t1.unidades*(t2.precioNeto+t2.iva)) as conIva, t4.retribucionCorreos
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    inner join [".$bbddSql."].[dbo].[tarifasProductos] as t3 on t2.idTarifasProducto = t3.id
    inner join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t4 on t3.idProductoPadre = t4.id
    where t1.fecha >= ? and t1.fecha <= ? and t1.comprobado = 1
    group by t4.producto, t4.retribucionCorreos

    union

    SELECT ' Acuses', sum(t1.unidades) as unidades, sum(t1.importe) as importe, sum(t1.unidades*t2.precioNeto) as sinIva, sum(t1.unidades*(t2.precioNeto+t2.iva)) as conIva, 'CARTA' as retribucionCorreos
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos != '1'  and t1.comprobado = 1

    union

    SELECT ' Envios Especiales', sum(t1.unidades) as unidades, sum(t1.importe) as importe, sum(t1.importe/1.21) as sinIva, sum(t1.importe) as conIva, 'ENVIOS ESPECIALES' as retribucionCorreos
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos = '1'  and t1.comprobado = 1
    ) as tabla order by retribucionCorreos, producto
    ";

    

    $params = array($fechaInicio1, $fechaFin1, $fechaInicio1, $fechaFin1, $fechaInicio1, $fechaFin1);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }
    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarConsumoPorProductosFranqueo2($conn_sis, $bbddSql, $fechaInicio, $fechaFin)
{
    $fechaInicio1 = date("Y-m-d", strtotime($fechaInicio));
    $fechaFin1 = date("Y-m-d", strtotime($fechaFin));
    $anioSeleccionado = date("Y", strtotime($fechaInicio));

    $consulta = "
    select
    t4.producto2, sum(t1.unidades) as unidades,
    sum(((t8.precioNetoReal*t1.unidades))-(isnull(t5.descuentoTantoPorCiento,0)*t8.precioNetoReal*t1.unidades/100)) as sinIva
    , sum( (((t8.precioIvaReal)*t1.unidades))-(isnull(t5.descuentoTantoPorCiento,0)*(t1.unidades*(t8.precioIvaReal))/100)) as conIva
    ,t4.retribucionCorreos
    , t7.sumaUnidades, t3.ordenInforme as orden

    from [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    inner join [".$bbddSql."].[dbo].[tarifasProductos] as t3 on t2.idTarifasProducto = t3.id
    inner join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t4 on t3.idProductoPadre = t4.id

    inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
    else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal
    FROM [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."]) as t8
    on t8.tipos = t2.tipos
    left join [".$bbddSql."].[dbo].[franqueoDescuentoCorreos] as t5
    on t5.idTarifasProducto = t3.id and t5.idTarifasProductoPadre = t4.id and t1.idCliente = t5.idCliente

    left join (
    select t4.producto2, sum(unidades) as sumaUnidades from [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    inner join [".$bbddSql."].[dbo].[tarifasProductos] as t3 on t2.idTarifasProducto = t3.id
    inner join [".$bbddSql."].[dbo].[tarifasProductoPadre] as t4 on t3.idProductoPadre = t4.id
    where t1.fecha >= ? and t1.fecha <= ? and t1.comprobado = 1
    group by t4.producto2
    ) as t7
    on t7.producto2 = t4.producto2

    where t1.fecha >= ? and t1.fecha <= ? and t1.comprobado = 1

    group by t4.retribucionCorreos, t4.producto2, t7.sumaUnidades, t3.ordenInforme

    union

    SELECT ' Acuses' as producto, sum(t1.unidades) as unidades, sum(t1.unidades*t4.precioNetoReal) as sinIva, sum(t1.unidades*(t4.precioIvaReal)) as conIva, 'CARTA' as retribucionCorreos
    ,t3.unidades as sumatorioUnidades, t2.destinoAcusesOrden as orden
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
    else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal
    FROM [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."]) as t4
    on t4.tipos = t2.tipos

    left join (
    SELECT 'acuses' as tipo, sum(t1.unidades) as unidades
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos != '1' and t2.descripcion like '%acuse%' and t1.comprobado = 1
    ) as t3
    on t3.tipo = 'acuses'

    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos != '1' and t2.descripcion like '%acuse%' and t1.comprobado = 1
    group by t2.destinoAcuses, t3.unidades, t2.destinoAcusesOrden

    union

    SELECT ' PEE' as producto, sum(t1.unidades) as unidades, sum(t1.unidades*t4.precioNetoReal) as sinIva, sum(t1.unidades*(t4.precioIvaReal)) as conIva, 'CARTA' as retribucionCorreos
    ,t3.unidades as sumatorioUnidades, t2.destinoAcusesOrden as orden
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    inner join (SELECT tipos, case when iva>0 then (precioNeto+iva)/1.21
    else precioNeto end as precioNetoReal, precioNeto + iva as precioIvaReal
    FROM [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."]) as t4
    on t4.tipos = t2.tipos

    left join (
    SELECT 'PEE' as tipo, sum(t1.unidades) as unidades
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos
    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos != '1' and t2.descripcion like '%PEE%' and t1.comprobado = 1
    ) as t3
    on t3.tipo = 'PEE'

    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos != '1' and t2.descripcion like '%PEE%' and t1.comprobado = 1
    group by t2.destinoAcuses, t3.unidades, t2.destinoAcusesOrden

    union

    SELECT ' Envios Especiales', sum(t1.unidades) as unidades, sum(t1.importe/1.21) as sinIva, sum(t1.importe) as conIva, 'ENVIOS ESPECIALES' as retribucionCorreos, sum(t1.unidades) as sumaUnidades
    , 1 as orden
    FROM [".$bbddSql."].[dbo].[franqueoTipos] as t1
    inner join [".$bbddSql."].[dbo].[tarifas".$anioSeleccionado."] as t2 on t1.tipo = t2.tipos

    where t1.fecha >= ? and t1.fecha <= ? and t2.idTarifasProducto is null and t2.tipos = '1' and t1.comprobado = 1

    order by retribucionCorreos, producto2, orden
    ";

    $params = array(
        $fechaInicio1, $fechaFin1,
        $fechaInicio1, $fechaFin1,
        $fechaInicio1, $fechaFin1,
        $fechaInicio1, $fechaFin1,
        $fechaInicio1, $fechaFin1,
        $fechaInicio1, $fechaFin1,
        $fechaInicio1, $fechaFin1
    );

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }
    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDatosGenericosFranqueo($conn_sis, $bbddSql, $campos, $filtros)
{
    $camposPermitidos = array(
        'objetivoFranqueo' => 't1.objetivoFranqueo',
        'tantoPorcientoObjetivo' => 't1.tantoPorcientoObjetivo',
        'diasHabiles' => 't1.diasHabiles',
        'anio' => 't1.anio'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $condicion = array();
    $params = array();

    if (isset($filtros['anio'])) {
        $condicion[] = 't1.anio = ?';
        $params[] = $filtros['anio'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[datosGenericosFranqueo] AS t1" . $sqlWhere;

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }
    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDatosFacturasMensuales($conn_sis, $bbddSql, $campos, $fechaInicio, $fechaFin, $anioTarifas, $order)
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
        'formaPago' => 't5.concepto as formaPago',
        'importe' => 'ISNULL(t2.importe,0) as importe',
        'envios' => 'ISNULL(t3.envios,0) as envios',
        'conceptos' => 't4.conceptos'
    );

    //t2: subconsulta importe de franqueo (franqueoTipos + tarifas{anio} + tarifasProductos, claves B/G/D/H/NOTP)
    //t3: subconsulta numero de envios de franqueo (franqueoTipos, sin filtro de clave)
    //t4: subconsulta existencia de conceptos (facturasEspecialesTemporal)
    //t5: formaDePago

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

    $anio = intval($anioTarifas);

    $params = array();

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'nombre_empresa' => 't1.nombre_empresa',
        'codigo' => 't1.codigo',
        'codigo_saldo' => 't1.codigo_saldo'
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
    // params: t2 (fechaInicio, fechaFin), t3 (fechaInicio, fechaFin), t4 (fechaInicio, fechaFin) - en ese orden, antes del WHERE
    $params[] = $fechaInicio;
    $params[] = $fechaFin;
    $params[] = $fechaInicio;
    $params[] = $fechaFin;
    $params[] = $fechaInicio;
    $params[] = $fechaFin;

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[clientes] AS t1

        LEFT JOIN (
            SELECT tf.idCliente, SUM(tf.importe) as importe
            FROM [".$bbddSql."].[dbo].[franqueoTipos] as tf
            INNER JOIN [".$bbddSql."].[dbo].[tarifas".$anio."] as tar ON tf.tipo = tar.tipos
            INNER JOIN [".$bbddSql."].[dbo].[tarifasProductos] as tp ON tar.idTarifasProducto = tp.id
            WHERE (tp.clave='B' or tp.clave='G' or tp.clave='D' or tp.clave='H' or tp.clave='NOTP')
              AND tf.fecha >= ? AND tf.fecha < ? AND tf.ot NOT LIKE 'OT%' AND tf.comprobado = 1
            GROUP BY tf.idCliente
        ) as t2 ON t1.codigo = t2.idCliente

        LEFT JOIN (
            SELECT tf2.idCliente, SUM(tf2.unidades) as envios
            FROM [".$bbddSql."].[dbo].[franqueoTipos] as tf2
            WHERE tf2.fecha >= ? AND tf2.fecha < ? AND tf2.ot NOT LIKE 'OT%' AND tf2.comprobado = 1
            GROUP BY tf2.idCliente
        ) as t3 ON t1.codigo = t3.idCliente

        LEFT JOIN (
            SELECT fet.idCliente, 'tieneConceptos' as conceptos
            FROM [".$bbddSql."].[dbo].[facturasEspecialesTemporal] as fet
            WHERE (UPPER(fet.ordenTrabajo)='CD' or UPPER(fet.ordenTrabajo)='BUROFAX' or UPPER(fet.ordenTrabajo)='RECOGIDAS' or UPPER(fet.ordenTrabajo)='CROTALES' or UPPER(fet.ordenTrabajo)='FACTURAS' or UPPER(fet.ordenTrabajo)='OTROS CONCEPTOS')
              AND fet.fechaFacturacion >= ? AND fet.fechaFacturacion < ?
            GROUP BY fet.idCliente
        ) as t4 ON t1.codigo = t4.idCliente

        LEFT JOIN [".$bbddSql."].[dbo].[formaDePago] as t5 ON t5.id = t1.idFormaPago

        WHERE t1.fac_idPeriodo = 1 AND t1.correoDiario = 1 AND t1.activo = 1
          AND (t2.importe > 0 OR t3.envios > 0 OR t4.conceptos IS NOT NULL OR t1.fac_cuotaRecogida > 0)
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => '"Cargar Datos Facturas Mensuales:<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',
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

function cargarDetallesFacturasMensuales($conn_sis, $bbddSql, $codigoCliente)
{
    $params = array($codigoCliente, $codigoCliente);

    $consulta = "
        SELECT idCliente, concepto, 1 as unidades, SUM(unidades*precioUnitario) as precioUnitario, SUM(unidades*precioUnitario) as importe
        FROM [".$bbddSql."].[dbo].[facturasEspecialesTemporal]
        WHERE (UPPER(ordenTrabajo)='CD' or UPPER(ordenTrabajo)='BUROFAX') AND idCliente = ?
        GROUP BY idCliente, fechaFacturacion, concepto
        UNION
        SELECT idCliente, concepto, sum(unidades), precioUnitario , sum(unidades*precioUnitario) as importe
        FROM [".$bbddSql."].[dbo].[facturasEspecialesTemporal]
        WHERE (UPPER(ordenTrabajo)='CROTALES' or UPPER(ordenTrabajo)='FACTURAS' or UPPER(ordenTrabajo)='RECOGIDAS' or UPPER(ordenTrabajo)='OTROS CONCEPTOS') AND idCliente = ?
        group by idCliente, concepto, precioUnitario
        ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => '"Cargar Detalles Facturas Mensuales:<pre>" . print_r(sqlsrv_errors(), true) . "</pre>"',
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

function calcularDatosFacturaMensualCliente($conn, $bbddSql, $valor, $fechaInicio, $fechaFin, $fechaFac, $fechaFinCampana)
{
    $codigoCliente = $valor["codigo"];
    $codigoSaldoCliente = $valor["codigo_saldo"];

    $nombreCliente = $valor["nombre_empresa"];
    $descripcion = "Manipulación y envío de su Correspondencia diaria del ".$fechaInicio." al ".$fechaFinCampana;//nombre de campaña
    $presupuesto = "mensual";
    $fecha = $fechaFac;
    $inicialComercial = 0;//preguntar
    $prefactura = $valor["prefactura"];
    if ($prefactura!=1)
    {
        $prefactura=0;
    }

    $datosDetalles = array();
    if ($valor["conceptos"]!="" && $valor["conceptos"]!="null" && $valor["conceptos"]!=null)//para calcular el importe total
    {
        $resultadoDetalles = cargarDetallesFacturasMensuales($conn, $bbddSql, $codigoCliente);
        $datosDetalles = $resultadoDetalles['datos'];
    }

    $precioNetoTotal = 0;
    $lineasDetalle = array();

    foreach ($datosDetalles as $valor2)
    {
        $total1 = round($valor2["unidades"] * $valor2["precioUnitario"], 2);
        $precioNetoTotal = $precioNetoTotal + $total1;

        $lineasDetalle[] = array(
            'presupuesto' => $presupuesto,
            'concepto' => $valor2["concepto"],
            'unidades' => $valor2["unidades"],
            'precio' => $valor2["precioUnitario"],
            'total' => $total1,
            'ordenTipo' => 1000,
            'orden' => 1000
        );
    }

    $fijoMensual = round($valor["fac_cuotaRecogida"], 2);

    $precioFranqueo = $valor["importe"]=="null"||$valor["importe"]==null ? 0 : $valor["importe"];
    $manipuladoNoBonificable = round($precioFranqueo * $valor["fac_porCientoNoBonificable"] /100, 2);
    $otroConceptosCliente = round($valor["fac_importeFijoOtrosConcepto"], 2);

    $cantidadFranqueo = $valor["envios"]=="null"||$valor["envios"]==null ? 0 : $valor["envios"];
    $manipulacionPostal = round($cantidadFranqueo * $valor["fac_cobroUnitarioEnvio"], 2);

    $precioNetoTotal = $precioNetoTotal + $fijoMensual + $manipulacionPostal + $manipuladoNoBonificable + $otroConceptosCliente;
    $precioNetoTotal = round($precioNetoTotal, 2);

    if ($valor["sinIva"]==1 || $valor["sinIva"]=="1")
    {
        $iva = 0.00;
        $precioTotal = $precioNetoTotal;
        $provision = 0;//??????????????
        $aPagar = $precioTotal + 0;
        $tipoIvaDetalle = 0;
    }
    else
    {
        $iva = round($precioNetoTotal * 0.21, 2);
        $precioTotal = $precioNetoTotal + $iva;
        $provision = 0;//??????????????
        $aPagar = $precioTotal + 0;
        $tipoIvaDetalle = 21;
    }

    foreach ($lineasDetalle as &$linea)
    {
        $linea['tipoIva'] = $tipoIvaDetalle;
    }
    unset($linea);

    $pedido = $valor["pedidoCliente"];
    $formaPago = $valor["formaPago"];
    $detallada = 0;
    $cd = 1;

    if ($cantidadFranqueo=="")
    {
        $cantidadFranqueo=0;
    }

    $datosFacturaCabecera = array(
        'serieFactura' => 'FAC',
        'cliente' => $nombreCliente,
        'idCodigoCliente' => $codigoSaldoCliente,
        'descripcion' => $descripcion,
        'presupuesto' => $presupuesto,
        'fecha' => $fecha,
        'fechaRealizacion' => $fecha,
        'inicialComercial' => $inicialComercial,
        'precioNeto' => $precioNetoTotal,
        'iva' => $iva,
        'irpf' => 0,
        'precioTotal' => $precioTotal,
        'provision' => $provision,
        'aPagar' => $aPagar,
        'cantidad' => $cantidadFranqueo,
        'pedido' => $pedido,
        'formaPago' => $formaPago,
        'detallada' => $detallada,
        'cd' => $cd,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
        'importeFranqueo' => $precioFranqueo,
        'numCuentaBanco' => $valor["nuestraCuenta"],
        'prefactura' => $prefactura
    );

    $camposClienteFactura = ['nombre_empresa','direccion','codigo_postal','localidad','provincia','pais','codigoPais','nif','nombrePais','envio_nombre','envio_att','envio_domicilio','envio_cp','envio_poblacion','envio_provincia','envio_pais','retener'];
    $resClienteFactura = cargarClientes($conn, $bbddSql, $camposClienteFactura, ['codigo' => $codigoCliente], [], [], ['tabla4']);

    if (!empty($resClienteFactura['datos'])) {
        $clienteFactura = $resClienteFactura['datos'][0];

        $datosFacturaCabecera['dirPost_nombreEmpresa'] = $clienteFactura['nombre_empresa'];
        $datosFacturaCabecera['dirPost_direccion'] = $clienteFactura['direccion'];
        $datosFacturaCabecera['dirPost_cp'] = $clienteFactura['codigo_postal'];
        $datosFacturaCabecera['dirPost_poblacion'] = $clienteFactura['localidad'];
        $datosFacturaCabecera['dirPost_provincia'] = $clienteFactura['provincia'];
        $datosFacturaCabecera['dirPost_pais'] = $clienteFactura['nombrePais'];
        $datosFacturaCabecera['dirPost_codigoPais'] = $clienteFactura['codigoPais'];
        $datosFacturaCabecera['dirPost_Nif'] = $clienteFactura['nif'];

        if ($clienteFactura['envio_domicilio']=="" && $clienteFactura['envio_cp']=="" && $clienteFactura['envio_poblacion']=="" && $clienteFactura['envio_provincia']=="") {
            $datosFacturaCabecera['dirEnv_nombreEmpresa'] = $clienteFactura['nombre_empresa'];
            $datosFacturaCabecera['dirEnv_direccion'] = $clienteFactura['direccion'];
            $datosFacturaCabecera['dirEnv_cp'] = $clienteFactura['codigo_postal'];
            $datosFacturaCabecera['dirEnv_poblacion'] = $clienteFactura['localidad'];
            $datosFacturaCabecera['dirEnv_provincia'] = $clienteFactura['provincia'];
            $datosFacturaCabecera['dirEnv_pais'] = $clienteFactura['nombrePais'];
            $datosFacturaCabecera['dirEnv_att'] = '';
        } else {
            $datosFacturaCabecera['dirEnv_nombreEmpresa'] = $clienteFactura['envio_nombre'];
            $datosFacturaCabecera['dirEnv_direccion'] = $clienteFactura['envio_domicilio'];
            $datosFacturaCabecera['dirEnv_cp'] = $clienteFactura['envio_cp'];
            $datosFacturaCabecera['dirEnv_poblacion'] = $clienteFactura['envio_poblacion'];
            $datosFacturaCabecera['dirEnv_provincia'] = $clienteFactura['envio_provincia'];
            $datosFacturaCabecera['dirEnv_pais'] = $clienteFactura['envio_pais'];
            $datosFacturaCabecera['dirEnv_att'] = $clienteFactura['envio_att'];
        }

        $datosFacturaCabecera['retener'] = $clienteFactura['retener'];
    }

    //FijoMensual
    $lineasDetalle[] = array(
        'presupuesto' => $presupuesto,
        'concepto' => 'Fijo Mensual',
        'unidades' => 1,
        'precio' => $fijoMensual,
        'total' => $fijoMensual,
        'ordenTipo' => 1000,
        'orden' => 1,
        'tipoIva' => $tipoIvaDetalle
    );

    //Manipulacion Postal
    $lineasDetalle[] = array(
        'presupuesto' => $presupuesto,
        'concepto' => 'Manipulación Postal',
        'unidades' => $cantidadFranqueo,
        'precio' => $valor["fac_cobroUnitarioEnvio"],
        'total' => $manipulacionPostal,
        'ordenTipo' => 1000,
        'orden' => 2,
        'tipoIva' => $tipoIvaDetalle
    );

    //Manipulado de Productos No Bonificables
    $lineasDetalle[] = array(
        'presupuesto' => $presupuesto,
        'concepto' => 'Manipulado de Productos No Bonificables',
        'unidades' => $valor["fac_porCientoNoBonificable"],
        'precio' => $precioFranqueo,
        'total' => $manipuladoNoBonificable,
        'ordenTipo' => 1000,
        'orden' => 3,
        'tipoIva' => $tipoIvaDetalle
    );

    //Otros Conceptos
    $lineasDetalle[] = array(
        'presupuesto' => $presupuesto,
        'concepto' => $valor["fac_otrosConceptosFijos"],
        'unidades' => 1,
        'precio' => $otroConceptosCliente,
        'total' => $otroConceptosCliente,
        'ordenTipo' => 1000,
        'orden' => 3,
        'tipoIva' => $tipoIvaDetalle
    );

    return array(
        'codigoCliente' => $codigoCliente,
        'nombreCliente' => $nombreCliente,
        'datosFacturaCabecera' => $datosFacturaCabecera,
        'lineasDetalle' => $lineasDetalle
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

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

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
        'codigo_saldo' => 't2.codigo_saldo',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'aPagar' => 't1.aPagar',
        'precioNeto' => 't1.precioNeto',
        'tipoIva' => 't1.tipoIva',
        'iva' => 't1.iva',
        'precioTotal' => 't1.precioTotal',
        'origenFactura' => 't1.origenFactura',
        'motivo' => 't1.motivo',
        'nombreComercial' => 't3.nombre as nombreComercial',
        'nif' => 't2.nif',
        'numero' => 't1.numero',
        'presupuesto' => 't1.presupuesto',
        'descripcion' => 't1.descripcion',
        'inicialComercial' => 't1.inicialComercial',
        'irpf' => 't1.irpf',
        'precioTotalSinIrpf' => 'CASE WHEN t1.irpf < 0 OR t1.irpf > 0 THEN t1.precioNeto + t1.iva ELSE t1.precioTotal END as precioTotalSinIrpf',
        'provision' => 't1.provision',
        'cantidad' => 't1.cantidad',
        'pedido' => 't1.pedido',
        'formaPago' => 't1.formaPago',
        'detallada' => 't1.detallada',
        'cuentaDelBanco' => 't1.numCuentaBanco as cuentaDelBanco',
        'combinadoSumatorio' => 't1.combinadoSumatorio',
        'laprefactura' => 't1.prefactura as laprefactura',
        'prefactura' => 't1.prefactura',
        'serieFactura' => 't1.serieFactura',
        'nombre_empresa' => 't1.dirPost_nombreEmpresa as nombre_empresa',
        'direccion' => 't1.dirPost_direccion as direccion',
        'codigo_postal' => 't1.dirPost_cp as codigo_postal',
        'localidad' => 't1.dirPost_poblacion as localidad',
        'provincia' => 't1.dirPost_provincia as provincia',
        'nif' => 't1.dirPost_Nif as nif',
        'nombrePais' => 't1.dirPost_pais as nombrePais',
        'dirPost_pais' => 't1.dirPost_pais',
        'dirPost_codigoPais' => 't1.dirPost_codigoPais',
        'envio_nombre' => 't1.dirEnv_nombreEmpresa as envio_nombre',
        'envio_domicilio' => 't1.dirEnv_direccion as envio_domicilio',
        'envio_cp' => 't1.dirEnv_cp as envio_cp',
        'envio_poblacion' => 't1.dirEnv_poblacion as envio_poblacion',
        'envio_provincia' => 't1.dirEnv_provincia as envio_provincia',
        'envio_pais' => 't1.dirEnv_pais as envio_pais',
        'envio_att' => 't1.dirEnv_att as envio_att',
        'retener' => 't1.retener',
        'observaciones' => 't1.observaciones',
        'observacionesInternas' => 't1.observacionesInternas',
        'precioNetoExentoIva' => 't1.precioNetoExentoIva',
        'fechaRealizacion' => 't1.fechaRealizacion',
        'verifactu_qrcode' => 't1.verifactu_qrcode',
        'verifactu_message' => 't1.verifactu_message',
        'verifactu_idSolicitud' => 't1.verifactu_idSolicitud',
        'verifactu_nifExpedidor' => 't1.verifactu_nifExpedidor',
        'verifactu_fechaExpedicion' => 't1.verifactu_fechaExpedicion',
        'verifactu_numFactura' => 't1.verifactu_numFactura',
        'verifactu_hast' => 't1.verifactu_hast',
        'verifactu_url' => 't1.verifactu_url',
        'verifactu_queueId' => 't1.verifactu_queueId',
        'aniosUtilizados' => 'DISTINCT YEAR(t1.fecha) as aniosUtilizados',
        'fechaPago' => 't1.fechaPago',
        'formaPagoReal' => 't1.formaPagoReal',
        'cd' => 't1.cd',
        'fechaInicio' => 't1.fechaInicio',
        'fechaFin' => 't1.fechaFin',
        'importeFranqueo' => 't1.importeFranqueo',
        'abono' => 't1.abono',
        'liquidado' => 't1.liquidado',
        'comprobacionError' => 't1.comprobacionError',
        'facRecDiferencia' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'RECT') THEN 1 ELSE 0 END) as facRecDiferencia",
        'facRecSustitucion' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'SUST') THEN 1 ELSE 0 END) as facRecSustitucion",
        'numFacRec' => "(SELECT TOP 1 fr.numeroFacturaCompleto FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura IN ('RECT','SUST')) as numFacRec",
        'facNeg' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'NEG') THEN 1 ELSE 0 END) as facNeg",
        'numFacNeg' => "(SELECT TOP 1 fr.numeroFacturaCompleto FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'NEG') as numFacNeg",
        'facAbono' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'AB') THEN 1 ELSE 0 END) as facAbono",
        'numFacAbono' => "(SELECT TOP 1 fr.numeroFacturaCompleto FROM [".$bbddSql."].[dbo].[facturacion] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'AB') as numFacAbono",
        'aPagarSumatorio' => 'SUM(t1.aPagar) as aPagarSumatorio',
        'precioNetoSumatorio' => 'SUM(t1.precioNeto) as precioNetoSumatorio',
        'fechaMax' => 'MAX(t1.fecha) as fechaMax'
        
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
        'tabla3' => "inner join [".$bbddSql."].[dbo].[comerciales] as t3 on t3.id = t2.idComercial",
       
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

    if (isset($filtros['numeroFacturaCompleto'])) {
        $condicion[] = 't1.numeroFacturaCompleto = ?';
        $params[] = $filtros['numeroFacturaCompleto'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['domiciliada'])) {
        $condicion[] = 't2.domiciliada = ?';
        $params[] = $filtros['domiciliada'];
    }
    if (isset($filtros['sinFormaPago'] ) && $filtros['sinFormaPago'] == 1) {
        $condicion[] = "(t1.formaPagoReal IS NULL OR t1.formaPagoReal = '')";
    }
    if (isset($filtros['serieFactura']) && $filtros['serieFactura'] != '') {
        $condicion[] = 't1.serieFactura = ?';
        $params[] = $filtros['serieFactura'];
    }
    if (isset($filtros['soloPagadas']) && $filtros['soloPagadas'] == 1) {
        $condicion[] = 't1.fechaPago IS NOT NULL';
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 't2.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }
    

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'LIKE');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha',
        'numero' => 't1.numero',
        'descripcion' => 't1.descripcion',
        'cliente' => 't1.cliente',
        'presupuesto' => 't1.presupuesto',
        'precioTotal' => 't1.precioTotal',
        'aPagar' => 't1.aPagar',
        'fechaPago' => 't1.fechaPago',
        'nombreComercial' => 't3.nombre',
        'formaPagoReal' => 't1.formaPagoReal',
        'liquidado' => 't1.liquidado',
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
        'numero' => 't1.numero',
        'aniosUtilizados' => 'aniosUtilizados',
        'descripcion' => 't1.descripcion',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'fechaPago' => 't1.fechaPago',
        'presupuesto' => 't1.presupuesto',
        'precioTotal' => 't1.precioTotal',
        'aPagar' => 't1.aPagar',
        'nombreComercial' => 't3.nombre',
        'serieFactura' => 't1.serieFactura',
        'formaPagoReal' => 't1.formaPagoReal',
        'liquidado' => 't1.liquidado',
        'id' => 't1.id',
        'ordenAgentesComerciales_ComercialCliente' => 't3.nombre, t1.cliente, t1.serieFactura desc, t1.fecha'
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
        'codigo_saldo' => 't2.codigo_saldo',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'aPagar' => 't1.aPagar',
        'precioNeto' => 't1.precioNeto',
        'tipoIva' => 't1.tipoIva',
        'iva' => 't1.iva',
        'precioTotal' => 't1.precioTotal',
        'origenFactura' => 't1.origenFactura',
        'motivo' => 't1.motivo',
        'nombreComercial' => 't3.nombre as nombreComercial',
        'numero' => 't1.numero',
        'presupuesto' => 't1.presupuesto',
        'descripcion' => 't1.descripcion',
        'inicialComercial' => 't1.inicialComercial',
        'irpf' => 't1.irpf',
        'precioTotalSinIrpf' => 'CASE WHEN t1.irpf < 0 OR t1.irpf > 0 THEN t1.precioNeto + t1.iva ELSE t1.precioTotal END as precioTotalSinIrpf',
        'provision' => 't1.provision',
        'cantidad' => 't1.cantidad',
        'pedido' => 't1.pedido',
        'formaPago' => 't1.formaPago',
        'detallada' => 't1.detallada',
        'cuentaDelBanco' => 't1.numCuentaBanco as cuentaDelBanco',
        'combinadoSumatorio' => 't1.combinadoSumatorio',
        'laprefactura' => 't1.prefactura as laprefactura',
        'prefactura' => 't1.prefactura',
        'serieFactura' => 't1.serieFactura',
        'nombre_empresa' => 't1.dirPost_nombreEmpresa as nombre_empresa',
        'direccion' => 't1.dirPost_direccion as direccion',
        'codigo_postal' => 't1.dirPost_cp as codigo_postal',
        'localidad' => 't1.dirPost_poblacion as localidad',
        'provincia' => 't1.dirPost_provincia as provincia',
        'nif' => 't1.dirPost_Nif as nif',
        'nombrePais' => 't1.dirPost_pais as nombrePais',
        'dirPost_pais' => 't1.dirPost_pais',
        'dirPost_codigoPais' => 't1.dirPost_codigoPais',
        'envio_nombre' => 't1.dirEnv_nombreEmpresa as envio_nombre',
        'envio_domicilio' => 't1.dirEnv_direccion as envio_domicilio',
        'envio_cp' => 't1.dirEnv_cp as envio_cp',
        'envio_poblacion' => 't1.dirEnv_poblacion as envio_poblacion',
        'envio_provincia' => 't1.dirEnv_provincia as envio_provincia',
        'envio_pais' => 't1.dirEnv_pais as envio_pais',
        'envio_att' => 't1.dirEnv_att as envio_att',
        'retener' => 't1.retener',
        'observaciones' => 't1.observaciones',
        'observacionesInternas' => 't1.observacionesInternas',
        'precioNetoExentoIva' => 't1.precioNetoExentoIva',
        'fechaRealizacion' => 't1.fechaRealizacion',
        'verifactu_qrcode' => 't1.verifactu_qrcode',
        'verifactu_message' => 't1.verifactu_message',
        'verifactu_idSolicitud' => 't1.verifactu_idSolicitud',
        'verifactu_nifExpedidor' => 't1.verifactu_nifExpedidor',
        'verifactu_fechaExpedicion' => 't1.verifactu_fechaExpedicion',
        'verifactu_numFactura' => 't1.verifactu_numFactura',
        'verifactu_hast' => 't1.verifactu_hast',
        'verifactu_url' => 't1.verifactu_url',
        'verifactu_queueId' => 't1.verifactu_queueId',
        'aniosUtilizados' => 'DISTINCT YEAR(t1.fecha) as aniosUtilizados',
        'fechaPago' => 't1.fechaPago',
        'formaPagoReal' => 't1.formaPagoReal',
        'cd' => 't1.cd',
        'fechaInicio' => 't1.fechaInicio',
        'fechaFin' => 't1.fechaFin',
        'importeFranqueo' => 't1.importeFranqueo',
        'abono' => 't1.abono',
        'liquidado' => 't1.liquidado',
        'comprobacionError' => 't1.comprobacionError',
        'facRecDiferencia' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'RECT') THEN 1 ELSE 0 END) as facRecDiferencia",
        'facRecSustitucion' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'SUST') THEN 1 ELSE 0 END) as facRecSustitucion",
        'numFacRec' => "(SELECT TOP 1 fr.numeroFacturaCompleto FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura IN ('RECT','SUST')) as numFacRec",
        'facNeg' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'NEG') THEN 1 ELSE 0 END) as facNeg",
        'numFacNeg' => "(SELECT TOP 1 fr.numeroFacturaCompleto FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'NEG') as numFacNeg",
        'facAbono' => "(CASE WHEN EXISTS (SELECT 1 FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'AB') THEN 1 ELSE 0 END) as facAbono",
        'numFacAbono' => "(SELECT TOP 1 fr.numeroFacturaCompleto FROM [".$bbddSql."].[dbo].[facturacionClayma] fr WHERE fr.origenFactura = t1.numeroFacturaCompleto AND fr.serieFactura = 'AB') as numFacAbono",
        'aPagarSumatorio' => 'SUM(t1.aPagar) as aPagarSumatorio',
        'precioNetoSumatorio' => 'SUM(t1.precioNeto) as precioNetoSumatorio',
        'fechaMax' => 'MAX(t1.fecha) as fechaMax'
        
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
        'tabla3' => "inner join [".$bbddSql."].[dbo].[comerciales] as t3 on t3.id = t2.idComercial",
       
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

    if (isset($filtros['numeroFacturaCompleto'])) {
        $condicion[] = 't1.numeroFacturaCompleto = ?';
        $params[] = $filtros['numeroFacturaCompleto'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['serieFactura']) && $filtros['serieFactura'] != '') {
        $condicion[] = 't1.serieFactura = ?';
        $params[] = $filtros['serieFactura'];
    }
    if (isset($filtros['soloPagadas']) && $filtros['soloPagadas'] == 1) {
        $condicion[] = 't1.fechaPago IS NOT NULL';
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 't2.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }

    /*
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    */

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'LIKE');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha',
        'numero' => 't1.numero',
        'descripcion' => 't1.descripcion',
        'cliente' => 't1.cliente',
        'presupuesto' => 't1.presupuesto',
        'precioTotal' => 't1.precioTotal',
        'aPagar' => 't1.aPagar',
        'fechaPago' => 't1.fechaPago',
        'nombreComercial' => 't3.nombre',
        'formaPagoReal' => 't1.formaPagoReal',
        'liquidado' => 't1.liquidado',
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
        'numero' => 't1.numero',
        'aniosUtilizados' => 'aniosUtilizados',
        'descripcion' => 't1.descripcion',
        'cliente' => 't1.cliente',
        'fecha' => 't1.fecha',
        'fechaPago' => 't1.fechaPago',
        'presupuesto' => 't1.presupuesto',
        'precioTotal' => 't1.precioTotal',
        'aPagar' => 't1.aPagar',
        'nombreComercial' => 't3.nombre',
        'serieFactura' => 't1.serieFactura',
        'formaPagoReal' => 't1.formaPagoReal',
        'liquidado' => 't1.liquidado',
        'id' => 't1.id',
        'ordenAgentesComerciales_ComercialCliente' => 't3.nombre, t1.cliente, t1.serieFactura desc, t1.fecha'
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

function mostrarFacturasCibelesClaymaCorreos($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $filtrosLike, $order)
{
    $camposPermitidos = array(
        'origen' => 'tabla.origen',
        'origen2' => 'tabla.origen2',
        'numeroFacturaCompleto' => 'tabla.numeroFacturaCompleto',
        'idCliente' => 'tabla.idCliente',
        'codigo_saldo' => 'tabla.codigo_saldo',
        'cliente' => 'tabla.cliente',
        'importe' => 'tabla.importe',
        'aPagar' => 'tabla.aPagar',
        'fecha' => 'tabla.fecha',
        'domiciliada' => 'tabla.domiciliada',
        'formaPago' => 'tabla.formaPago',
        'descripcion' => 'tabla.descripcion',
        'saldo' => 'tabla.saldo',
        'precioNeto' => 'tabla.precioNeto',
        'formaPagoReal' => 'tabla.formaPagoReal',
        'fechaPago' => 'tabla.fechaPago',
        'aPagarSumatorio' => 'SUM(tabla.aPagar) as aPagarSumatorio',
        'precioNetoSumatorio' => 'SUM(tabla.precioNeto) as precioNetoSumatorio'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['domiciliada'])) {
        $condicion[] = 'tabla.domiciliada = ?';
        $params[] = $filtros['domiciliada'];
    }
    if (isset($filtros['origen2']) && $filtros['origen2'] != '') {
        $condicion[] = 'tabla.origen2 = ?';
        $params[] = $filtros['origen2'];
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 'tabla.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }
    if (isset($filtros['sinFormaPago']) && $filtros['sinFormaPago'] == 1) {
        $condicion[] = "(tabla.formaPagoReal IS NULL OR tabla.formaPagoReal = '') AND (tabla.fechaPago IS NULL OR tabla.fechaPago ='') ";
    }

    $origenesIncluidos = array();
    if (isset($filtros['incluirCibeles']) && $filtros['incluirCibeles'] == 1) {
        $origenesIncluidos[] = 'CIBELES';
    }
    if (isset($filtros['incluirClayma']) && $filtros['incluirClayma'] == 1) {
        $origenesIncluidos[] = 'CLAYMA';
    }
    if (isset($filtros['incluirCorreos']) && $filtros['incluirCorreos'] == 1) {
        $origenesIncluidos[] = 'CORREOS';
    }
    if (!empty($origenesIncluidos)) {
        $placeholders = implode(',', array_fill(0, count($origenesIncluidos), '?'));
        $condicion[] = "tabla.origen2 IN ($placeholders)";
        foreach ($origenesIncluidos as $o) {
            $params[] = $o;
        }
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 'tabla.fecha'
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

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'cliente' => 'tabla.cliente',
        'numeroFacturaCompleto' => 'tabla.numeroFacturaCompleto',
        'importe' => 'tabla.importe',
        'aPagar' => 'tabla.aPagar',
        'precioNeto' => 'tabla.precioNeto',
        'origen' => 'tabla.origen'
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
        'cliente' => 'tabla.cliente',
        'clienteIdCliente' => 'tabla.cliente, tabla.idCliente',
        'codigo_saldo' => 'tabla.codigo_saldo',
        'numeroFacturaCompleto' => 'tabla.numeroFacturaCompleto',
        'fecha' => 'tabla.fecha',
        'importe' => 'tabla.importe',
        'aPagar' => 'tabla.aPagar',
        'precioNeto' => 'tabla.precioNeto',
        'origen2' => 'tabla.origen2',
        'totalCliente' => 'SUM(tabla.aPagar) OVER (PARTITION BY tabla.codigo_saldo)'
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
            CASE t1.serieFactura
                WHEN 'RECT' THEN 'REC DIFERENCIAS'
                WHEN 'SUST' THEN 'REC SUSTITUCION'
                WHEN 'AB' THEN 'ABONO'
                ELSE 'MANIPULADOS'
            END as origen,
            t1.numeroFacturaCompleto as numeroFacturaCompleto,
            t1.cliente as cliente,
            t1.idCodigoCliente as idCliente,
            t1.idCodigoCliente as codigo_saldo,
            t1.fecha,
            t1.precioTotal as importe,
            t1.aPagar,
            t2.domiciliada,
            t3.concepto as formaPago,
            t1.descripcion as descripcion,
            t2.importePF as saldo,
            t1.precioNeto,
            t1.formaPagoReal,
            t1.fechaPago,
            'CIBELES' as origen2
        FROM [".$bbddSql."].[dbo].[facturacion] as t1
        inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCodigoCliente = t2.codigo AND t2.codigo = t2.codigo_saldo
        inner join [".$bbddSql."].[dbo].[formaDePago] as t3 on t3.id = t2.idFormaPago

        UNION

        SELECT
            CASE t1.serieFactura
                WHEN 'RECT' THEN 'REC DIFERENCIAS'
                WHEN 'SUST' THEN 'REC SUSTITUCION'
                WHEN 'AB' THEN 'ABONO'
                ELSE 'MANIPULADOS'
            END as origen,
            t1.numeroFacturaCompleto as numeroFacturaCompleto,
            t1.cliente as cliente,
            t1.idCodigoCliente as idCliente,
            t1.idCodigoCliente as codigo_saldo,
            t1.fecha,
            t1.precioTotal as importe,
            t1.aPagar,
            t2.domiciliada,
            t3.concepto as formaPago,
            t1.descripcion as descripcion,
            t2.importePF as saldo,
            t1.precioNeto,
            t1.formaPagoReal,
            t1.fechaPago,
            'CLAYMA' as origen2
        FROM [".$bbddSql."].[dbo].[facturacionClayma] as t1
        inner join [".$bbddSql."].[dbo].[clientesClayma] as t2 on t1.idCodigoCliente = t2.codigo AND t2.codigo = t2.codigo_saldo
        inner join [".$bbddSql."].[dbo].[formaDePago] as t3 on t3.id = t2.idFormaPago

        UNION

        SELECT
            'CORREOS' as origen,
            t1.numeroOficial as numeroFacturaCompleto,
            t2.subcliente as cliente,
            t2.codigo as idCliente,
            t2.codigo_saldo,
            t1.fecha,
            t1.importe,
            t1.aPagar,
            t2.domiciliada,
            t3.concepto as formaPago,
            t1.campana as descripcion,
            t2.importePF as saldo,
            t1.neto as precioNeto,
            t1.formaPago as formaPagoReal,
            t1.fechaPago,
            'CORREOS' as origen2
        FROM [".$bbddSql."].[dbo].[facturasCorreos] as t1
        inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.codigoCliente = t2.codigo
        inner join [".$bbddSql."].[dbo].[formaDePago] as t3 on t3.id = t2.idFormaPago

        ) AS tabla
        $sqlWhere
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
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
        'id' => 't1.id',
        'codigo_saldo' => 't2.codigo_saldo',
        'codigoCliente' => 't1.codigoCliente',
        'nombre_empresa' => 't2.nombre_empresa',
        'campana' => 't1.campana',
        'importe' => 't1.importe',
        'fecha' => 't1.fecha',
        'numeroOficial' => 't1.numeroOficial',
        'neto' => 't1.neto',
        'iva' => 't1.iva',
        'anticipo' => 't1.anticipo',
        'aPagar' => 't1.aPagar',
        'formaPago' => 't1.formaPago',
        'fechaPago' => 't1.fechaPago',
        'netoSumatorio' => 'SUM(t1.neto) as netoSumatorio',
        'ivaSumatorio' => 'SUM(t1.iva) as ivaSumatorio',
        'anticipoSumatorio' => 'SUM(t1.anticipo) as anticipoSumatorio',
        'importeSumatorio' => 'SUM(t1.importe) as importeSumatorio',
        'aPagarSumatorio' => 'SUM(t1.aPagar) as aPagarSumatorio'
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
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['codigo_saldo'])) {
        $condicion[] = 't2.codigo_saldo = ?';
        $params[] = $filtros['codigo_saldo'];
    }
    if (isset($filtros['nombre_empresa'])) {
        $condicion[] = 't2.nombre_empresa = ?';
        $params[] = $filtros['nombre_empresa'];
    }
    if (isset($filtros['numeroOficial'])) {
        $condicion[] = 't1.numeroOficial = ?';
        $params[] = $filtros['numeroOficial'];
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
        'codigo_saldo' => 't2.codigo_saldo',
        'aPagar' => 't1.aPagar',
        'nombre_empresa' => 't2.nombre_empresa',
        'numeroOficial' => 't1.numeroOficial',
        'importe' => 't1.importe',
        'fecha' => 't1.fecha'
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

function insertarFacturacionCorreos($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'numeroOficial' => 'numeroOficial',
        'fecha' => 'fecha',
        'codigoCliente' => 'codigoCliente',
        'campana' => 'campana',
        'neto' => 'neto',
        'iva' => 'iva',
        'importe' => 'importe',
        'anticipo' => 'anticipo',
        'aPagar' => 'aPagar'
    );

    if (!is_array($datos) || empty($datos)) {
        return array('error' => 'insertarFacturacionCorreos: datos vacios', 'ok' => false);
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
        return array('error' => 'insertarFacturacionCorreos: camposSQL vacios', 'ok' => false);
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturasCorreos]
        (".implode(', ', $camposSQL).")
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function eliminarFacturacionCorreos($conn_sis, $bbddSql, $filtros)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    //SEGURIDAD: NO permitir DELETE sin WHERE
    if (empty($condicion)) {
        return array(
            'error' => 'eliminarFacturacionCorreos: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
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

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
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
    if (isset($filtros['activo'])) {
        $condicion[] = 't1.activo = ?';
        $params[] = $filtros['activo'];
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

function cargarImpresoras($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'impresoras' => 't1.impresoras'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => 'campos vacios');
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

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

    $camposOrdenPermitidos = array(
        'impresoras' => 't1.impresoras'
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

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[L_impresoras] AS t1 $sqlWhere $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarOrigenPapel($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'origen' => 't1.origen'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => 'campos vacios');
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => 'camposSql vacios');
    }

    $listaCampos = implode(', ', $camposSQL);

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

    $camposOrdenPermitidos = array(
        'origen' => 't1.origen'
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

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[L_papelOrigen] AS t1 $sqlWhere $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
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

function insertarFacturasDetallesTemporal($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idEmpleado' => 'idEmpleado',
        'presupuesto' => 'presupuesto',
        'facturaOriginal' => 'facturaOriginal',
        'concepto' => 'concepto',
        'descripcion' => 'descripcion',
        'notaCibeles' => 'notaCibeles',
        'unidades' => 'unidades',
        'precio' => 'precio',
        'total' => 'total',
        'ordenTipo' => 'ordenTipo',
        'orden' => 'orden',
        'idTipoProceso' => 'idTipoProceso',
        'exentoIVA' => 'exentoIVA',
        'tipoIva' => 'tipoIva'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'insertarFacturasDetallesTemporal: datos vacios',
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
            'error' => 'insertarFacturasDetallesTemporal: camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturasDetallesTemporal]
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
        $idInsertado = sqlsrv_get_field($resultado, 0);
    }

    sqlsrv_free_stmt($resultado);

    return array(
        'error' => '',
        'ok' => true,
        'id' => $idInsertado,
        'sql' => $consulta,
        'params' => $params
    );
}

function mostrarFacturasDetallesTemporal($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $group = array(), $joins = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idEmpleado' => 't1.idEmpleado',
        'presupuesto' => 't1.presupuesto',
        'facturaOriginal' => 't1.facturaOriginal',
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'notaCibeles' => 't1.notaCibeles',
        'unidades' => 't1.unidades',
        'unidadesSumatorio' => 'sum(t1.unidades) as unidades',
        'precio' => 't1.precio',
        'total' => 't1.total',
        'totalSumatorio' => 'sum(t1.total) as total',
        'ordenTipo' => 't1.ordenTipo',
        'orden' => 't1.orden',
        'idTipoProceso' => 't1.idTipoProceso',
        'exentoIVA' => 't1.exentoIVA',
        'tipoIva' => 't1.tipoIva',
        'presupuestoDistinct' => 'distinct(t1.presupuesto) as presupuesto',
        'campana' => 't2.descripcion as campana',
        'clienteDistinct' => 'distinct(t4.codigo_saldo) as clientes',
        'nombreEmpresaCliente' => 't4.nombre_empresa',
        'clienteDistinctClayma' => 'distinct(t5.codigo_saldo) as clientes',
        'nombreEmpresaClienteClayma' => 't5.nombre_empresa'
    );

    //JOINS
    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[facturasTemporal] as t2 on t1.presupuesto = t2.presupuesto",
        'tabla3' => "inner join [".$bbddSql."].[dbo].[presupuestos] as t3 on t1.presupuesto = t3.presupuesto",
        'tabla4' => "inner join [".$bbddSql."].[dbo].[clientes] as t4 on t3.cliente = t4.nombre_empresa",
        'tabla5' => "inner join [".$bbddSql."].[dbo].[clientesClayma] as t5 on t3.cliente = t5.nombre_empresa"
    ];

    $sqlJoins = '';
    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['facturaOriginal'])) {
        $condicion[] = 't1.facturaOriginal = ?';
        $params[] = $filtros['facturaOriginal'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 't1.idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array();

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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'presupuesto' => 't1.presupuesto',
        'ordenTipo' => 't1.ordenTipo',
        'orden' => 't1.orden',
        'concepto' => 't1.concepto'
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

    // ---------- GROUP BY ----------
    $camposGroupPermitidos = array(
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'precio' => 't1.precio',
        'tipoIva' => 't1.tipoIva'
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

    // ---------- SQL ----------
    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[facturasDetallesTemporal] AS t1
        $sqlJoins
        $sqlWhere
        $sqlGroup
        $sqlOrder
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

function insertarFacturasTemporal($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idCliente' => 'idCliente',
        'usuario' => 'usuario',
        'clayma' => 'clayma',
        'pedido' => 'pedido',
        'cantidad' => 'cantidad',
        'formaPago' => 'formaPago',
        'descripcion' => 'descripcion',
        'detallada' => 'detallada',
        'precioNeto' => 'precioNeto',
        'iva' => 'iva',
        'irpf' => 'irpf',
        'precioTotal' => 'precioTotal',
        'provision' => 'provision',
        'aPagar' => 'aPagar',
        'presupuesto' => 'presupuesto'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'insertarFacturasTemporal: datos vacios',
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
            'error' => 'insertarFacturasTemporal: camposSQL vacios',
            'ok' => false
        );
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturasTemporal]
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
        'sql' => $consulta,
        'params' => $params
    );
}

function mostrarFacRecDetallesTemporal($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idUsuario' => 't1.idUsuario',
        'facturaOriginal' => 't1.facturaOriginal',
        'clayma' => 't1.clayma',
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'notaCibeles' => 't1.notaCibeles',
        'unidades' => 't1.unidades',
        'precio' => 't1.precio',
        'total' => 't1.total',
        'ordenTipo' => 't1.ordenTipo',
        'orden' => 't1.orden',
        'exentoIVA' => 't1.exentoIVA'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['facturaOriginal'])) {
        $condicion[] = 't1.facturaOriginal = ?';
        $params[] = $filtros['facturaOriginal'];
    }
    if (isset($filtros['idUsuario'])) {
        $condicion[] = 't1.idUsuario = ?';
        $params[] = $filtros['idUsuario'];
    }
    if (isset($filtros['clayma'])) {
        $condicion[] = 't1.clayma = ?';
        $params[] = $filtros['clayma'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array();

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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'ordenTipo' => 't1.ordenTipo',
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
        FROM [".$bbddSql."].[dbo].[facturasRecDetallesTemporal] AS t1
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

function cargarProvisionDeFondo_movimientos($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'codigoCliente' => 't1.codigoCliente',
        'fecha' => 't1.fecha',
        'formaPago' => 't1.formaPago',
        'importe' => 't1.importe',
        'presupuesto' => 't1.presupuesto',
        'fechaCuadre' => 't1.fechaCuadre',
        'informacionCuadre' => 't1.informacionCuadre',
        'saldoPostPF' => 't1.saldoPostPF',
        'clayma' => 't1.clayma',
        'sumaMovimientosSinFranqueo' => 'sum(t1.importe) as sumaMovimientosSinFranqueo'
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

    if (isset($filtros['codigoCliente'])) {
        $condicion[] = 't1.codigoCliente = ?';
        $params[] = $filtros['codigoCliente'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['presupuesto_no_vacio']) && $filtros['presupuesto_no_vacio'] == 1) {
        $condicion[] = "t1.presupuesto != ''";
    }
    if (isset($filtros['formaPago_no_vacio']) && $filtros['formaPago_no_vacio'] == 1) {
        $condicion[] = "t1.formaPago != ''";
    }
    if (isset($filtros['clayma'])) {
        $condicion[] = 't1.clayma = ?';
        $params[] = $filtros['clayma'];
    }
    if (isset($filtros['informacionCuadre'])) {
        $condicion[] = 't1.informacionCuadre = ?';
        $params[] = $filtros['informacionCuadre'];
    }
    if (isset($filtros['fechaMovimientoMax'], $filtros['codigoClienteMovimientoMax'])) {
        $condicion[] = "t1.id IN (SELECT MAX(id) FROM [".$bbddSql."].[dbo].[provisionDeFondo_movimientos] WHERE fecha <= ? AND codigoCliente = ?)";
        $params[] = $filtros['fechaMovimientoMax'];
        $params[] = $filtros['codigoClienteMovimientoMax'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha'
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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'fecha' => 't1.fecha'
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
        FROM [".$bbddSql."].[dbo].[provisionDeFondo_movimientos] AS t1
        $sqlWhere
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array(
            'error' => print_r(sqlsrv_errors(), true),
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

function modificarFacturacionClayma($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
        'formaPagoReal' => 't1.formaPagoReal',
        'fechaPago' => 't1.fechaPago',
        'liquidado' => 't1.liquidado',
        'verifactu_qrcode' => 't1.verifactu_qrcode',
        'verifactu_message' => 't1.verifactu_message',
        'verifactu_idSolicitud' => 't1.verifactu_idSolicitud',
        'verifactu_nifExpedidor' => 't1.verifactu_nifExpedidor',
        'verifactu_fechaExpedicion' => 't1.verifactu_fechaExpedicion',
        'verifactu_numFactura' => 't1.verifactu_numFactura',
        'verifactu_hast' => 't1.verifactu_hast',
        'verifactu_url' => 't1.verifactu_url',
        'verifactu_queueId' => 't1.verifactu_queueId'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarFacturacionClayma: datos vacios',
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
            'error' => 'modificarFacturacionClayma: no hay campos validos para actualizar',
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

    $camposComparablesPermitidos = array();

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
            'error' => 'modificarFacturacionClayma: update sin WHERE bloqueado por seguridad',
            'ok' => false,            
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[facturacionClayma] t1
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

function modificarFacturasDetallesTemporal($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    $camposPermitidos = array(
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'notaCibeles' => 't1.notaCibeles',
        'unidades' => 't1.unidades',
        'precio' => 't1.precio',
        'total' => 't1.total',
        'exentoIVA' => 't1.exentoIVA',
        'tipoIva' => 't1.tipoIva'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarFacturasDetallesTemporal: datos vacios',
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
            'error' => 'modificarFacturasDetallesTemporal: no hay campos validos para actualizar',
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

    if (empty($condicion)) {
        return array(
            'error' => 'modificarFacturasDetallesTemporal: update sin WHERE bloqueado por seguridad',
            'ok' => false,
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[facturasDetallesTemporal] t1
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

    if (isset($filtros['numNoFactura'])) {
        $condicion[] = 't1.numNoFactura = ?';
        $params[] = $filtros['numNoFactura'];
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

function modificarFacturasTemporal($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(
        'idCliente' => 't1.idCliente',
        'clayma' => 't1.clayma',
        'pedido' => 't1.pedido',
        'cantidad' => 't1.cantidad',
        'formaPago' => 't1.formaPago',
        'descripcion' => 't1.descripcion',
        'detallada' => 't1.detallada',
        'precioNeto' => 't1.precioNeto',
        'iva' => 't1.iva',
        'irpf' => 't1.irpf',
        'precioTotal' => 't1.precioTotal',
        'provision' => 't1.provision',
        'aPagar' => 't1.aPagar'
    );

    if (!is_array($datos) || empty($datos)) {
        return array(
            'error' => 'modificarFacturasTemporal: datos vacios',
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
            'error' => 'modificarFacturasTemporal: no hay campos validos para actualizar',
            'ok' => false
        );
    }

    // ---------- FILTROS ----------
    $condicion = array();

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['usuario'])) {
        $condicion[] = 't1.usuario = ?';
        $params[] = $filtros['usuario'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'presupuesto' => 't1.presupuesto'
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

    if (empty($condicion)) {
        return array(
            'error' => 'modificarFacturasTemporal: update sin WHERE bloqueado por seguridad',
            'ok' => false,
            'params' => $params
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        UPDATE t1
        SET " . implode(', ', $set) . "
        FROM [".$bbddSql."].[dbo].[facturasTemporal] t1
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

function modificarProvisionFondo($conn_sis, $bbddSql, $datos, $filtros, $filtrosOperadores)
{
    // ---------- CAMPOS PERMITIDOS ----------
    $camposPermitidos = array(       
       
        'cobrada' => 't1.cobrada',
        'borradaComercial' => 't1.borradaComercial',
        'fechaCobro' => 't1.fechaCobro',
        'formaPago' => 't1.formaPago',
        'importe' => 't1.importe',
        'idCliente' => 't1.idCliente',
        'clayma' => 't1.clayma',
        'facCompletaAplicada' => 't1.facCompletaAplicada'
        
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
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['cobrada'])) {
        $condicion[] = 't1.cobrada = ?';
        $params[] = $filtros['cobrada'];
    }
    if (isset($filtros['tipo'])) {
        $condicion[] = 't1.tipo = ?';
        $params[] = $filtros['tipo'];
    }
    if (isset($filtros['sinFacturaAplicada']) && $filtros['sinFacturaAplicada'] == 1) {
        $condicion[] = "(t1.facCompletaAplicada IS NULL OR t1.facCompletaAplicada = '')";
    }
    if (isset($filtros['facCompletaAplicada'])) {
        $condicion[] = 't1.facCompletaAplicada = ?';
        $params[] = $filtros['facCompletaAplicada'];
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
        'fechaPago' => 't1.fechaPago',
        'liquidado' => 't1.liquidado',
        'verifactu_qrcode' => 't1.verifactu_qrcode',
        'verifactu_message' => 't1.verifactu_message',
        'verifactu_idSolicitud' => 't1.verifactu_idSolicitud',
        'verifactu_nifExpedidor' => 't1.verifactu_nifExpedidor',
        'verifactu_fechaExpedicion' => 't1.verifactu_fechaExpedicion',
        'verifactu_numFactura' => 't1.verifactu_numFactura',
        'verifactu_hast' => 't1.verifactu_hast',
        'verifactu_url' => 't1.verifactu_url',
        'verifactu_queueId' => 't1.verifactu_queueId'
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

function eliminarFacturasDetallesTemporal($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }
    if (isset($filtros['facturaOriginal'])) {
        $condicion[] = 't1.facturaOriginal = ?';
        $params[] = $filtros['facturaOriginal'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 't1.idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }
    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'presupuesto' => 't1.presupuesto'
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
            'error' => 'eliminarFacturasDetallesTemporal: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[facturasDetallesTemporal] t1
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

function eliminarFacturasTemporal($conn_sis, $bbddSql, $filtros, $filtrosOperadores)
{
    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['usuario'])) {
        $condicion[] = 't1.usuario = ?';
        $params[] = $filtros['usuario'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    // ---------- FILTROS OPERADORES ----------
    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        //'presupuesto' => 't1.presupuesto'
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
            'error' => 'eliminarFacturasTemporal: DELETE sin WHERE bloqueado por seguridad',
            'ok' => false
        );
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);

    // ---------- SQL ----------
    $consulta = "
        DELETE t1
        FROM [".$bbddSql."].[dbo].[facturasTemporal] t1
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

function mostrarFacturasTemporal($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'usuario' => 't1.usuario',
        'clayma' => 't1.clayma',
        'pedido' => 't1.pedido',
        'cantidad' => 't1.cantidad',
        'formaPago' => 't1.formaPago',
        'formaPagoTexto' => 't2.concepto as formaPagoTexto',
        'descripcion' => 't1.descripcion',
        'detallada' => 't1.detallada',
        'precioNeto' => 't1.precioNeto',
        'iva' => 't1.iva',
        'irpf' => 't1.irpf',
        'precioTotal' => 't1.precioTotal',
        'provision' => 't1.provision',
        'aPagar' => 't1.aPagar',
        'presupuesto' => 't1.presupuesto',
        'precioNetoSumatorio' => 'sum(t1.precioNeto) as precioNeto',
        'ivaSumatorio' => 'sum(t1.iva) as iva',
        'precioTotalSumatorio' => 'sum(t1.precioTotal) as precioTotal',
        'provisionSumatorio' => 'sum(t1.provision) as provision',
        'aPagarSumatorio' => 'sum(t1.aPagar) as aPagar',
        'irpfSumatorio' => 'sum(t1.irpf) as irpf'
    );

    //JOINS
    $joinsPermitidos = [
        'tabla2' => "inner join [".$bbddSql."].[dbo].[formaDePago] as t2 on t1.formaPago = t2.id"
    ];

    $sqlJoins = '';
    if (is_array($joins) && !empty($joins)) {
        foreach ($joins as $j) {
            if (isset($joinsPermitidos[$j])) {
                $sqlJoins .= " " . $joinsPermitidos[$j];
            }
        }
    }

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['usuario'])) {
        $condicion[] = 't1.usuario = ?';
        $params[] = $filtros['usuario'];
    }
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array();

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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'presupuesto' => 't1.presupuesto'
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
        FROM [".$bbddSql."].[dbo].[facturasTemporal] AS t1
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

function mostrarSePuedeFacturar($conn_sis, $bbddSql)
{
    $consulta = "SELECT sePuedeFacturar FROM [".$bbddSql."].[dbo].[sePuedeFacturar]";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        die("<pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
    }

    $valor = 0;
    if (sqlsrv_fetch($resultado) !== false) {
        $valor = sqlsrv_get_field($resultado, 0);
    }

    sqlsrv_free_stmt($resultado);

    return $valor;
}

function modificarSePuedeFacturar($conn_sis, $bbddSql, $valor)
{
    $consulta = "UPDATE [".$bbddSql."].[dbo].[sePuedeFacturar] SET sePuedeFacturar = ?";
    $params = array($valor);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function mostrarTipoIva($conn_sis, $bbddSql)
{
    $consulta = "SELECT tipoIva FROM [".$bbddSql."].[dbo].[tipoIva] ORDER BY tipoIva ASC";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array());
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result);
}

function insertarFacturacionDetalles($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'numeroFacturaCompleto' => 'numeroFacturaCompleto',
        'concepto' => 'concepto',
        'descripcion' => 'descripcion',
        'campana' => 'campana',
        'unidades' => 'unidades',
        'precio' => 'precio',
        'total' => 'total',
        'ordenTipo' => 'ordenTipo',
        'orden' => 'orden',
        'exentoIVA' => 'exentoIVA',
        'tipoIva' => 'tipoIva'
    );

    if (!is_array($datos) || empty($datos)) {
        return array('error' => 'insertarFacturacionDetalles: datos vacios', 'ok' => false);
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
        return array('error' => 'insertarFacturacionDetalles: camposSQL vacios', 'ok' => false);
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturacionDetalles]
        (".implode(', ', $camposSQL).")
         OUTPUT INSERTED.id
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    $idInsertado = null;

    if (sqlsrv_fetch($resultado) !== false) {
        $idInsertado = sqlsrv_get_field($resultado, 0);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'ok' => true, 'id' => $idInsertado, 'sql' => $consulta, 'params' => $params);
}

function insertarFacturacionDetallesClayma($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'presupuesto' => 'presupuesto',
        'numeroFacturaCompleto' => 'numeroFacturaCompleto',
        'concepto' => 'concepto',
        'descripcion' => 'descripcion',
        'campana' => 'campana',
        'unidades' => 'unidades',
        'precio' => 'precio',
        'total' => 'total',
        'ordenTipo' => 'ordenTipo',
        'orden' => 'orden',
        'exentoIVA' => 'exentoIVA',
        'tipoIva' => 'tipoIva'
    );

    if (!is_array($datos) || empty($datos)) {
        return array('error' => 'insertarFacturacionDetallesClayma: datos vacios', 'ok' => false);
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
        return array('error' => 'insertarFacturacionDetallesClayma: camposSQL vacios', 'ok' => false);
    }

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[facturacionDetallesClayma]
        (".implode(', ', $camposSQL).")
         OUTPUT INSERTED.id
        VALUES (".implode(', ', $placeholders).")
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    $idInsertado = null;

    if (sqlsrv_fetch($resultado) !== false) {
        $idInsertado = sqlsrv_get_field($resultado, 0);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'ok' => true, 'id' => $idInsertado, 'sql' => $consulta, 'params' => $params);
}

function cargarFacturacionDetalles($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $group, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'unidades' => 't1.unidades',
        'unidadesSumatorio' => 'sum(t1.unidades) as unidades',
        'precio' => 't1.precio',
        'total' => 't1.total',
        'totalSumatorio' => 'sum(t1.total) as total',
        'exentoIVA' => 't1.exentoIVA',
        'tipoIva' => 't1.tipoIva',
        'numeroFacturaCompleto' => 't1.numeroFacturaCompleto',
        'presupuestoDistinct' => 'distinct(t1.presupuesto) as presupuesto',
        'campana' => 't1.campana',
        'ordenTipo' => 't1.ordenTipo',
        'orden' => 't1.orden'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['numeroFacturaCompleto'])) {
        $condicion[] = 't1.numeroFacturaCompleto = ?';
        $params[] = $filtros['numeroFacturaCompleto'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array();

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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- GROUP BY ----------
    $camposGroupPermitidos = array(
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'precio' => 't1.precio',
        'exentoIVA' => 't1.exentoIVA',
        'tipoIva' => 't1.tipoIva'
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
        'concepto' => 't1.concepto',
        'presupuesto' => 't1.presupuesto',
        'ordenTipo' => 't1.ordenTipo',
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
        FROM [".$bbddSql."].[dbo].[facturacionDetalles] AS t1       
        $sqlWhere
        $sqlGroup
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

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarFacturasEspecialesTemporal($conn_sis, $bbddSql, $campos, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'nombre_franqueo' => 't2.nombre_franqueo',
        'ordenTrabajo' => 't1.ordenTrabajo',
        'fechaFacturacion' => 't1.fechaFacturacion',
        'concepto' => 't1.concepto',
        'unidades' => 't1.unidades',
        'precioUnitario' => 't1.precioUnitario',
        'fechaInicio' => 't1.fechaInicio',
        'fechaFin' => 't1.fechaFin'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'nombre_empresa' => 't2.nombre_empresa'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[facturasEspecialesTemporal] AS t1
        inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCliente = t2.codigo
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta);
}

function eliminarFacturasEspecialesTemporal($conn_sis, $bbddSql)
{
    $consulta = "DELETE FROM [".$bbddSql."].[dbo].[facturasEspecialesTemporal]";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta);
}

function insertarCertYrutasEnTemporal($conn_sis, $bbddSql, $datos)
{
    $fechaFac = $datos['fechaFac'];
    $primerDia = $datos['primerDia'];
    $ultimoDia = $datos['ultimoDia'];

    $consulta1 = "
        INSERT INTO [".$bbddSql."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, precioUnitario, fechaInicio, fechaFin)
        SELECT idCliente, 'CD', ?, 'Gestion Certificados y Paquetes', 1, SUM(unidades * importeUnitario), ?, ?
        FROM [".$bbddSql."].[dbo].[CertificadosGrabados]
        WHERE fecha >= ? AND fecha < ?
        GROUP BY idCliente
    ";
    $params1 = array($fechaFac, $primerDia, $ultimoDia, $primerDia, $ultimoDia);

    $resultado1 = sqlsrv_query($conn_sis, $consulta1, $params1);

    if ($resultado1 === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta1, 'params' => $params1);
    }

    $consulta2 = "
        INSERT INTO [".$bbddSql."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, precioUnitario, fechaInicio, fechaFin)
        SELECT idCliente, 'RECOGIDAS', ?, 'Recogidas - entregas especiales', SUM(cantidad), importe, ?, ?
        FROM [".$bbddSql."].[dbo].[albaranes]
        WHERE fecha >= ? AND fecha < ?
        GROUP BY idCliente, descripcion, importe
    ";
    $params2 = array($fechaFac, $primerDia, $ultimoDia, $primerDia, $ultimoDia);

    $resultado2 = sqlsrv_query($conn_sis, $consulta2, $params2);

    if ($resultado2 === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta2, 'params' => $params2);
    }

    $consulta3 = "
        INSERT INTO [".$bbddSql."].[dbo].[facturasEspecialesTemporal] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, precioUnitario, fechaInicio, fechaFin)
        SELECT idCliente, ordenTrabajo, ?, concepto, unidades, precioUnitario, ?, ?
        FROM [".$bbddSql."].[dbo].[facturasEspeciales]
        WHERE fechaFacturacion >= ? AND fechaFacturacion < ?
        GROUP BY idCliente, ordenTrabajo, concepto, unidades, precioUnitario
    ";
    $params3 = array($fechaFac, $primerDia, $ultimoDia, $primerDia, $ultimoDia);

    $resultado3 = sqlsrv_query($conn_sis, $consulta3, $params3);

    if ($resultado3 === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta3, 'params' => $params3);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta3, 'params' => $params3);
}

function insertarFacturasEspeciales($conn_sis, $bbddSql, $datos)
{
    $consulta = "INSERT INTO [".$bbddSql."].[dbo].[facturasEspeciales] (idCliente, ordenTrabajo, fechaFacturacion, concepto, unidades, precioUnitario) VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($datos['idCliente'], $datos['ordenTrabajo'], $datos['fecha'], $datos['concepto'], $datos['unidades'], $datos['importe']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function cargarFacturasEspeciales($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $group, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'nombre_franqueo' => 't2.nombre_franqueo',
        'subcliente' => 't2.subcliente',
        'ordenTrabajo' => 't1.ordenTrabajo',
        'fechaFacturacion' => 't1.fechaFacturacion',
        'concepto' => 't1.concepto',
        'unidades' => 't1.unidades',
        'unidadesSumatorio' => 'SUM(t1.unidades) as unidadesSumatorio',
        'precioUnitario' => 't1.precioUnitario',
        'totalSumatorio' => 'SUM(t1.unidades*t1.precioUnitario) as totalSumatorio'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $condicion = array();
    $params = array();

    if (!empty($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fechaFacturacion' => 't1.fechaFacturacion'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    $camposGroupPermitidos = array(
        'idCliente' => 't1.idCliente',
        'subcliente' => 't2.subcliente',
        'concepto' => 't1.concepto',
        'precioUnitario' => 't1.precioUnitario',
        'fechaFacturacion' => 't1.fechaFacturacion'
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

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'fechaFacturacion' => 't1.fechaFacturacion',
        'concepto' => 't1.concepto'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[facturasEspeciales] AS t1
        inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCliente = t2.codigo
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function modificarFacturasEspeciales($conn_sis, $bbddSql, $datos, $filtros)
{
    if (!isset($filtros['id'])) {
        return array('error' => 'id vacio', 'ok' => false);
    }

    $consulta = "UPDATE [".$bbddSql."].[dbo].[facturasEspeciales] SET concepto = ?, unidades = ?, precioUnitario = ? WHERE id = ?";
    $params = array($datos['concepto'], $datos['unidades'], $datos['precioUnitario'], $filtros['id']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function eliminarFacturasEspeciales($conn_sis, $bbddSql, $filtros)
{
    if (!isset($filtros['id'])) {
        return array('error' => 'id vacio', 'ok' => false);
    }

    $consulta = "DELETE FROM [".$bbddSql."].[dbo].[facturasEspeciales] WHERE id = ?";
    $params = array($filtros['id']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function cargarEmpleados($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $joins = array(), $group = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'nombre' => 't1.nombre',
        'apellidos' => 't1.apellidos',
        'idEmpleado' => 't2.idEmpleado',
        'nombreEmpleado' => "concat(t1.nombre,' ',t1.apellidos) as nombreEmpleado",
        'jornadaHoras' => "max(datepart(HOUR, t1.horasLaborales)) as jornadaHoras",
        'horasRealizadas' => "cast((case when sum(datediff(second, t4.horaInicio, t4.horaFin)) is null then 0 else sum(datediff(second, t4.horaInicio, t4.horaFin)) end / 3600.000) as decimal(6,2)) as horasRealizadas"
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- JOINS ----------
    // el join de rango filtra registroHoras por fecha; las fechas se sanean con date()/strtotime() (formato fijo, no input crudo) y van dentro del subquery
    // horaFin es datetime: para incluir el dia fin completo se compara horaFin < (fin + 1 dia)
    $rangoRegistroHoras = (isset($filtros['rangoRegistroHoras']) && is_array($filtros['rangoRegistroHoras'])) ? $filtros['rangoRegistroHoras'] : array();
    $rangoInicio = isset($rangoRegistroHoras['inicio']) ? date("d-m-Y", strtotime($rangoRegistroHoras['inicio'])) : '';
    $rangoFin = isset($rangoRegistroHoras['fin']) ? date("d-m-Y", strtotime($rangoRegistroHoras['fin']." + 1 days")) : '';

    // t2: login | t3: permisos | t4: registroHoras filtrado por rango de fecha (informe de horas)
    $joinsPermitidos = array(
        'tabla_login' => "inner join [".$bbddSql."].[dbo].[login] as t2 on t1.id = t2.idEmpleado",
        'tabla_permisos' => "inner join [".$bbddSql."].[dbo].[permisos] as t3 on t2.id = t3.id_usuario",
        'tabla_registroHorasRango' => "left join (select * from [".$bbddSql."].[dbo].[registroHoras] where horaInicio >= '".$rangoInicio."' and horaFin < '".$rangoFin."') as t4 on t4.idEmpleado = t1.id"
    );

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
    if (isset($filtros['pda_o_registrosManuales']) && $filtros['pda_o_registrosManuales'] == 1) {
        $condicion[] = "(t3.pda = 1 or t3.pda_registrosHorasManuales = 2)";
    }
    if (isset($filtros['activo'])) {
        $condicion[] = 't2.activo > 0';
    }
    if (isset($filtros['pdaOManuales'])) {
        $condicion[] = "(t3.pda != 0 or t3.[pda_registrosHorasManuales] != 0)";
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array();

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
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
        'id' => 't1.id',
        'nombre' => 't1.nombre',
        'apellidos' => 't1.apellidos'
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
        'nombre' => 't1.nombre',
        'apellidos' => 't1.apellidos'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[empleados] AS t1
        $sqlJoins
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarRegistroHoras_multiusuario($conn_sis, $bbddSql, $campos, $joins, $filtros, $order)
{
    $camposPermitidos = array(
        'idUsuario' => 't1.idUsuario',
        'idEmpleado' => 't1.idEmpleado',
        'idEmpleadoDistinct' => 'DISTINCT t1.idEmpleado as idEmpleado',
        'nombreEmpleado' => "CONCAT(t2.nombre, ' ', t2.apellidos) as nombreEmpleado",
        'empleadoInicio' => "CONCAT(t3.nombre, ' ', t3.apellidos) as empleadoInicio"
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- JOINS ----------
    // t2: empleados (empleado anadido, por idEmpleado) | t3: empleados (empleado que inicia, por idUsuario)
    $joinsPermitidos = array(
        'tabla_empleadoAnadido' => "inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id",
        'tabla_empleadoInicio' => "inner join [".$bbddSql."].[dbo].[empleados] as t3 on t1.idUsuario = t3.id"
    );

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

    if (isset($filtros['idUsuario'])) {
        $condicion[] = 't1.idUsuario = ?';
        $params[] = $filtros['idUsuario'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 't1.idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- ORDER BY ----------
    $camposOrdenPermitidos = array(
        'idUsuario' => 't1.idUsuario',
        'idEmpleado' => 't1.idEmpleado'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[registroHoras_multiusuario] AS t1
        $sqlJoins
        $sqlWhere
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function insertarRegistroHoras_multiusuario($conn_sis, $bbddSql, $datos)
{
    $idUsuario = isset($datos['idUsuario']) ? $datos['idUsuario'] : null;
    $proceso = isset($datos['proceso']) ? $datos['proceso'] : '';
    $idEmpleado = isset($datos['idEmpleado']) ? $datos['idEmpleado'] : null;

    $consulta = "insert into [".$bbddSql."].[dbo].[registroHoras_multiusuario] ([idUsuario], [proceso], [idEmpleado]) VALUES (?, ?, ?)";
    $params = array($idUsuario, $proceso, $idEmpleado);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'sql' => $consulta, 'params' => $params);
}

function eliminarRegistroHoras_multiusuario($conn_sis, $bbddSql, $filtros)
{
    $condicion = array();
    $params = array();

    if (isset($filtros['idUsuario'])) {
        $condicion[] = 'idUsuario = ?';
        $params[] = $filtros['idUsuario'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 'idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }

    if (empty($condicion)) {
        return array('error' => "eliminar sin filtros no permitido");
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    $consulta = "delete from [".$bbddSql."].[dbo].[registroHoras_multiusuario]" . $sqlWhere;

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'sql' => $consulta, 'params' => $params);
}

function eliminarRegistroHoras($conn_sis, $bbddSql, $filtros)
{
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 'id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 'idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }
    if (isset($filtros['codigoBarras'])) {
        $condicion[] = 'codigoBarras = ?';
        $params[] = $filtros['codigoBarras'];
    }

    if (empty($condicion)) {
        return array('error' => "eliminar sin filtros no permitido");
    }

    $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    $consulta = "delete from [".$bbddSql."].[dbo].[registroHoras]" . $sqlWhere;

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'sql' => $consulta, 'params' => $params);
}

function cargarHorasDetallesEmpleado($conn_sis, $bbddSql, $fechaInicio, $fechaFin, $nombreEmpleado)
{
    //informe de detalle de un empleado: sus registros en el rango con analisis de rentabilidad (precio/unidad, unidad/hora, porcentaje)
    $newDate = date("d-m-Y", strtotime($fechaInicio));
    $newDateFin = date("d-m-Y", strtotime($fechaFin." + 1 days"));

    $consulta = "SELECT t2.idConcepto, t1.[id]
        , t1.[nombreEmpleado]
        , t1.[codigoBarras]
        , CONCAT(t6.departamento,'/',t5.tipoProceso,'/',t4.proceso) as concepto
        , t1.[horaInicio]
        , t1.[horaFin]
        , t1.cantidad as cantidad
        , t1.[observaciones]
        , t1.[estado]
        , t1.[modo]
        , (RIGHT('0'+ cast((datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' + RIGHT('0'+ cast(((datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' + RIGHT('0'+ cast((datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as 'horas'
        , cast((case when (datediff(second, t1.horaInicio, t1.horaFin)) is null then '0' else (datediff(second, t1.horaInicio, t1.horaFin)) end / 3600.000) as decimal(6,2)) as 'horasRealizadas1'
        , cast(case when (case when t7.cantidad2 is null then t7.cantidad else t7.cantidad2 end)=0 then 0 else ((t2.precio * case when t2.unidades2 is null then t2.unidades else t2.unidades2 end) / case when t7.cantidad2 is null then t7.cantidad else t7.cantidad2 end) end as decimal(6,3)) as 'precio/unidad'
        , cast((3600.000 * t1.cantidad) / cast(datediff(second, t1.horaInicio, t1.horaFin) as decimal(16,3)) as decimal(16,3)) as 'unidad/hora'
        , t8.[unidad/hora] as 'unidad/horaTotalProceso'
        , case when t8.[unidad/hora]<=0 then 0 else cast((cast((3600.000 * t1.cantidad) / cast(datediff(second, t1.horaInicio, t1.horaFin) as decimal(16,3)) as decimal(16,3))) * 100 / t8.[unidad/hora] as decimal(16,3)) end as porcentaje
        FROM [".$bbddSql."].[dbo].[registroHoras] as t1
        inner join [".$bbddSql."].[dbo].[presupuestos detalle] as t2 on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
        inner join [".$bbddSql."].[dbo].[empleados] as t3 on t1.idEmpleado = t3.id
        inner join [".$bbddSql."].[dbo].[procesos] as t4 on t2.idConcepto = t4.id
        inner join [".$bbddSql."].[dbo].[procesosTipos] as t5 on t4.idTipoProceso = t5.id
        inner join [".$bbddSql."].[dbo].[procesosDepartamento] as t6 on t4.idDepartamento = t6.id
        inner join [".$bbddSql."].[dbo].[presupuestos] as t7 on t7.presupuesto = t2.presupuesto
        left join (
            select idConcepto, ot, sum(cantidad) as cantidad, sum(segundos) as segundos
            , case when sum(segundos)<=0 then 0 else cast(3600.000 * sum(cantidad) / cast(sum(segundos) as decimal(16,3)) as decimal(16,3)) end as 'unidad/hora'
            from (
                select t1.nombreEmpleado, t2.idConcepto, SUBSTRING(t1.codigoBarras, CHARINDEX('-', t1.codigoBarras)+1,7) as ot
                , sum(t1.cantidad) as cantidad
                , cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) as decimal(16,3)) as segundos
                , case when cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) as decimal(16,3))<=0 then 0 else cast(3600.000 * sum(t1.cantidad) / cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) as decimal(16,3)) as decimal(16,3)) end as 'unidad/hora'
                FROM [".$bbddSql."].[dbo].[registroHoras] as t1
                inner join [".$bbddSql."].[dbo].[presupuestos detalle] as t2 on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
                group by t1.nombreEmpleado, t2.idConcepto, SUBSTRING(t1.codigoBarras, CHARINDEX('-', t1.codigoBarras)+1,7)
            ) as tabla group by idConcepto, ot
        ) as t8 on t8.idConcepto = t2.idConcepto and SUBSTRING(t1.codigoBarras, CHARINDEX('-', t1.codigoBarras)+1,7) = t8.ot
        where t1.nombreEmpleado = ? and t1.horaInicio >= ? and t1.horaFin < ?
        order by horaInicio, concepto";

    $params = array($nombreEmpleado, $newDate, $newDateFin);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDatosInformeOtCostes($conn_sis, $bbddSql, $ot)
{
    //informe de costes por OT: horas/coste por departamento/tipoProceso/proceso/empleado/dia
    $consulta = "SELECT t12.cliente, t12.campana, t2.departamento, t3.tipoProceso, t4.proceso
        ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),23) as fechaInicio
        ,t5.nombreEmpleado, sum(t5.cantidad) as cantidad
        ,case when convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) is null then '0' else convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) end as horasTrabajados
        ,sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) as segundosTrabajados
        ,cast(case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 3600.000 as decimal(16,3)) as 'costeHora'
        ,cast(isnull(sum(t5.cantidad) /(sum(datediff(SECOND, t5.horaInicio, t5.horaFin))/60.00),0)*60 as decimal(6,0)) as media
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] as t1
        inner join [".$bbddSql."].[dbo].[presupuestos] as t12 on t12.presupuesto = t1.presupuesto
        inner join [".$bbddSql."].[dbo].[procesosDepartamento] as t2 on t1.idDepartamento = t2.id
        inner join [".$bbddSql."].[dbo].[procesosTipos] as t3 on t1.idTipo = t3.id
        inner join [".$bbddSql."].[dbo].[procesos] as t4 on t1.idConcepto = t4.id
        left join (
            select t1.*, t2.precioHora
            from [".$bbddSql."].[dbo].[registroHoras] as t1
            inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id
        ) as t5 on CONCAT(t1.id,'-',t1.presupuesto) = t5.codigoBarras
        where t1.presupuesto = ? and t4.mostrarEnInforme = 1
        group by t12.cliente, t12.campana, t2.departamento, t3.tipoProceso, t4.proceso, t5.nombreEmpleado
        ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),23), t5.precioHora
        order by t2.departamento, t3.tipoProceso, t4.proceso, convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),23) desc, t5.nombreEmpleado";

    $params = array($ot);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDatosInformeOtCostesTotales($conn_sis, $bbddSql, $ot)
{
    //informe de costes por OT (totales): factura, compras terceros, coste papel/click, transporte, satisfaccion
    $anio = (int)substr($ot, 0, 2) + 2000;

    $consulta = "select ISNULL(sum(tabla.cantidad),0) as cantidad, ISNULL(sum(tabla.segundosTrabajados),0) as segundosTrabajados, sum(tabla.costeHora) as costeHora, tabla.fechaTerminado, tabla.fechaCompromiso, tabla.fechaInicioReal, datediff(day, tabla.fechaCompromiso, tabla.fechaTerminado) as satisfaccion, datediff(day, tabla.fechaInicioReal, tabla.fechaTerminado) as tiempoRealizacion
        , tabla.comprasTerceros, tabla.importeFactura, tabla.importeFacturaClayma, ISNULL(sum(tabla.costePapel),0) as costePapel, ISNULL(sum(tabla.costeClick),0) as costeClick, tabla.cantidadPresupuesto, ISNULL(sum(tabla.precioPapel_Presupuesto),0) as precioPapel_Presupuesto, ISNULL(sum(tabla.precioClick_Presupuesto),0) as precioClick_Presupuesto
        , tabla.tantoPorCientoTransporte, max(tabla.pesoGramos) as pesoGramos
        from (
        SELECT t6.cliente, t6.campana, t2.departamento, t3.tipoProceso, t4.proceso
        ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),105) as fechaInicio
        ,t5.nombreEmpleado, sum(t5.cantidad) as cantidad
        ,case when convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) is null then '0' else convert(varchar,DATEADD(s,sum(datediff(second, horaInicio, horaFin)),0),108) end as horasTrabajados
        ,sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) as segundosTrabajados
        ,cast(case when t5.precioHora is null then 0.000 else t5.precioHora end * case when sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) is null then 0.000 else sum(datediff(SECOND, t5.horaInicio, t5.horaFin)) end / 3600.000 as decimal(16,3)) as 'costeHora'
        , t6.fechaTerminado, t6.fechaCompromiso, t6.fechaInicioReal
        , case when t9.comprasTerceros is null then 0 else t9.comprasTerceros end as comprasTerceros
        , case when t7.precioNeto is null then 0 else t7.precioNeto end as importeFactura
        , case when t8.precioNeto is null then 0 else t8.precioNeto end as importeFacturaClayma
        , sum(t10.papel) as costePapel
        , sum(t11.click) as costeClick
        , case when t6.cantidad2 is null or t6.cantidad2 = '' then t6.cantidad else t6.cantidad2 end as cantidadPresupuesto
        , case when t1.unidades2>0 then sum(t12.precio * t1.unidades2) else sum(t12.precio*t1.unidades) end as precioPapel_Presupuesto
        , case when t1.unidades2>0 then sum(t13.precioClick * t1.impresionNumeroCaras * t1.unidades2) else sum(t13.precioClick * t1.impresionNumeroCaras * t1.unidades) end as precioClick_Presupuesto
        , t14.tantoPorCientoTransporte, max(t1.pesoGramos) as pesoGramos
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] as t1
        inner join [".$bbddSql."].[dbo].[presupuestos] as t6 on t6.presupuesto = t1.presupuesto
        inner join [".$bbddSql."].[dbo].[procesosDepartamento] as t2 on t1.idDepartamento = t2.id
        inner join [".$bbddSql."].[dbo].[procesosTipos] as t3 on t1.idTipo = t3.id
        inner join [".$bbddSql."].[dbo].[procesos] as t4 on t1.idConcepto = t4.id
        left join (
            select t1.*, t2.precioHora
            from [".$bbddSql."].[dbo].[registroHoras] as t1
            inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id
        ) as t5 on CONCAT(t1.id,'-',t1.presupuesto) = t5.codigoBarras
        left join [".$bbddSql."].[dbo].[facturas".$anio."] as t7 on t6.presupuesto = t7.presupuesto
        left join [".$bbddSql."].[dbo].[facturasClayma".$anio."] as t8 on t6.presupuesto = t8.presupuesto
        left join (SELECT sum(t7.total) as comprasTerceros, t8.presupuesto
            FROM [".$bbddSql."].[dbo].[comprasTercerosDetalles] as t7
            inner join [".$bbddSql."].[dbo].[compraTerceros] as t8 on t7.pedido = t8.pedido
            group by t8.presupuesto
        ) as t9 on t9.presupuesto = t6.presupuesto
        left join (
            SELECT t1.id, t2.precio * t1.cantidad as papel
            FROM [".$bbddSql."].[dbo].[registroHoras] as t1
            inner join [".$bbddSql."].[dbo].[tarifas_papel] as t2 on t1.idPapelTamano = t2.idTamanio and t1.idPapelTipo = t2.idTipo and t1.idPapelAcabado = t2.idAcabado and t1.idPapelGramaje = t2.idGramaje
            where t2.precio * t1.cantidad is not null and t1.idPapelOrigen = 1
        ) as t10 on t10.id = t5.id
        left join (
            SELECT t3.id as idRegistroHora, t1.id as idImpresora, t2.precioClick, t3.cantidad, t2.precioClick * t3.cantidad * ISNULL(t3.impresionNumeroCaras,1) as click
            FROM [".$bbddSql."].[dbo].[L_impresoras] as t1
            inner join [".$bbddSql."].[dbo].[L_impresorasTipo] as t2 on t1.tipoImpresora = t2.id
            inner join [".$bbddSql."].[dbo].[registroHoras] as t3 on t3.idImpresoras = t1.id
        ) as t11 on t11.idRegistroHora = t5.id
        left join [".$bbddSql."].[dbo].[tarifas_papel] as t12 on t1.idMaterialPapel = t12.id
        left join [".$bbddSql."].[dbo].[L_impresorasTipo] as t13 on t1.idTipoImpresora = t13.id
        left join [".$bbddSql."].[dbo].[datosGenericosFranqueo] as t14 on t14.anio = ?
        where t1.presupuesto = ?
        group by t6.cliente, t6.campana, t2.departamento, t3.tipoProceso, t4.proceso, t5.nombreEmpleado, t6.fechaTerminado
        ,convert(varchar,DATEADD(dd, 0, DATEDIFF(dd, 0, t5.horaInicio)),105), t5.precioHora, t6.fechaTerminado, t9.comprasTerceros, t7.precioNeto, t8.precioNeto, t6.clayma
        , case when t6.cantidad2 is null or t6.cantidad2 = '' then t6.cantidad else t6.cantidad2 end, t6.fechaCompromiso, t6.fechaInicioReal
        , t1.unidades2, t14.tantoPorCientoTransporte
        ) as tabla
        group by tabla.cliente, tabla.campana, tabla.fechaTerminado
        , tabla.comprasTerceros, tabla.importeFactura, tabla.importeFacturaClayma, tabla.cantidadPresupuesto, tabla.tantoPorCientoTransporte, tabla.fechaCompromiso, tabla.fechaInicioReal";

    $params = array($anio, $ot);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDetallesInformeOtCostesTotales($conn_sis, $bbddSql, $ot)
{
    //detalle real (informatica): coste papel/click por registro de horas de la OT
    $consulta = "SELECT t1.id as idRegistroHoras, t1.nombreEmpleado, t3.impresoras, t9.tipoImpresora, t9.precioClick
        , t4.tamano, t5.tipo, t6.acabado, t7.gramaje, t8.origen, t2.precio as precioMaterial, t1.cantidad as cantidadEmpleado
        , ISNULL(t1.impresionNumeroCaras,1) as numeroCaras, t2.precio * t1.cantidad * ISNULL(t1.impresionNumeroCaras,1) as costePapel
        , t9.precioClick * t1.cantidad * ISNULL(t1.impresionNumeroCaras,1) as costeClick
        FROM [".$bbddSql."].[dbo].[registroHoras] as t1
        left join [".$bbddSql."].[dbo].[tarifas_papel] as t2 on t1.idPapelTamano = t2.idTamanio and t1.idPapelTipo = t2.idTipo and t1.idPapelAcabado = t2.idAcabado and t1.idPapelGramaje = t2.idGramaje
        left join [".$bbddSql."].[dbo].[L_impresoras] as t3 on t1.idImpresoras = t3.id
        left join [".$bbddSql."].[dbo].[L_papelTamanio] as t4 on t1.idPapelTamano = t4.id
        left join [".$bbddSql."].[dbo].[L_papelTipo] as t5 on t1.idPapelTipo = t5.id
        left join [".$bbddSql."].[dbo].[L_papelAcabado] as t6 on t1.idPapelAcabado = t6.id
        left join [".$bbddSql."].[dbo].[L_papelGramaje] as t7 on t1.idPapelGramaje = t7.id
        left join [".$bbddSql."].[dbo].[L_papelOrigen] as t8 on t1.idPapelOrigen = t8.id
        left join [".$bbddSql."].[dbo].[L_impresorasTipo] as t9 on t9.id = t3.tipoImpresora
        where SUBSTRING(t1.codigoBarras, CHARINDEX('-', t1.codigoBarras)+1, 7) = ? and t1.idPapelOrigen = 1";

    $params = array($ot);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDetallesPresupustoInformeOtCostesTotales($conn_sis, $bbddSql, $ot)
{
    //detalle presupuestado: coste papel/click por linea de detalle del presupuesto de la OT
    $consulta = "SELECT t1.id, t3.nombre, t6.departamento, t7.tipoProceso, t8.proceso, t9.tamano, t10.tipo, t11.acabado, t12.gramaje, t5.tipoImpresora, t1.unidades, t1.unidades2
        ,case when t1.unidades2>0 then t1.unidades2 else t1.unidades end as unidadesParaUtilizar
        , t1.impresionNumeroCaras, t4.precio as precioMaterial, t5.precioClick
        ,case when t1.unidades2>0 then t4.precio * t1.unidades2 else t4.precio*t1.unidades end as costePapel
        ,case when t1.unidades2>0 then t5.precioClick * t1.impresionNumeroCaras * t1.unidades2 else t5.precioClick * t1.impresionNumeroCaras * t1.unidades end as costeClick
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] as t1
        inner join [".$bbddSql."].[dbo].[presupuestos] as t2 on t1.presupuesto = t2.presupuesto
        inner join [".$bbddSql."].[dbo].[presupuestadores] as t3 on t2.idComercial = t3.id
        left join [".$bbddSql."].[dbo].[tarifas_papel] as t4 on t1.idMaterialPapel = t4.id
        left join [".$bbddSql."].[dbo].[L_impresorasTipo] as t5 on t1.idTipoImpresora = t5.id
        left join [".$bbddSql."].[dbo].[procesosDepartamento] as t6 on t1.idDepartamento = t6.id
        left join [".$bbddSql."].[dbo].[procesosTipos] as t7 on t1.idTipo = t7.id
        left join [".$bbddSql."].[dbo].[procesos] as t8 on t1.idConcepto = t8.id
        left join [".$bbddSql."].[dbo].[L_papelTamanio] as t9 on t4.idTamanio = t9.id
        left join [".$bbddSql."].[dbo].[L_papelTipo] as t10 on t4.idTipo = t10.id
        left join [".$bbddSql."].[dbo].[L_papelAcabado] as t11 on t4.idAcabado = t11.id
        left join [".$bbddSql."].[dbo].[L_papelGramaje] as t12 on t4.idGramaje = t12.id
        where t2.presupuesto = ? and case when t1.unidades2>0 then t1.unidades2 else t1.unidades end > 0 and t8.mostrarEnInforme = 1
        order by departamento, tipoProceso, proceso, tipoImpresora";

    $params = array($ot);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarDatosInformeOtDetalle($conn_sis, $bbddSql, $codigoBarras)
{
    //detalle por codigoBarras: horas/cantidad por empleado, con totales del codigoBarras
    $consulta = "SELECT t1.nombreEmpleado, t2.concepto, t1.codigoBarras,
        sum(isnull(t1.cantidad,0)) as cantidad,
        (RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':'
        + RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
        + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
        , sum(datediff(second, t1.horaInicio, t1.horaFin)) as segundos,
        (SELECT sum(isnull(t1.cantidad,0))
            FROM [".$bbddSql."].[dbo].[registroHoras] as t1
            inner join [".$bbddSql."].[dbo].[presupuestos detalle] as t2 on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
            where t1.codigoBarras = ?
            group by t1.codigoBarras
        ) as cantidadTotal,
        (SELECT (RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':'
            + RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':'
            + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2)) as horas
            FROM [".$bbddSql."].[dbo].[registroHoras] as t1
            inner join [".$bbddSql."].[dbo].[presupuestos detalle] as t2 on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
            where t1.codigoBarras = ?
            group by t1.codigoBarras
        ) as horasTotal
        FROM [".$bbddSql."].[dbo].[registroHoras] as t1
        inner join [".$bbddSql."].[dbo].[presupuestos detalle] as t2 on t1.[codigoBarras] = cast(t2.id as nvarchar) + '-' + cast(t2.presupuesto as nvarchar)
        where t1.codigoBarras = ?
        group by t1.nombreEmpleado, t2.concepto, t1.codigoBarras";

    $params = array($codigoBarras, $codigoBarras, $codigoBarras);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function mostrarValorConversorTamanio($conn_sis, $bbddSql, $presupuesto)
{
    //valor del conversor de tamano para el presupuesto (informe costes OT)
    $consulta = "select max(t15.valor) as valorConversor
        FROM [".$bbddSql."].[dbo].[presupuestos detalle] as t1
        inner join [".$bbddSql."].[dbo].[L_papelTamanioConversor] as t15 on t15.idTamanioInicio = t1.idTipoImpresora and t15.idTamanioFinal = t1.idPapelTamanioFinal
        where t1.presupuesto = ?";

    $params = array($presupuesto);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarOtPorFechas($conn_sis, $bbddSql, $fechaInicio, $fechaFin)
{
    //informe de OT por fecha: horas/cantidad/% realizado por OT en el rango
    $fechaInicio1 = date("d", strtotime($fechaInicio)) . '-' . date("m", strtotime($fechaInicio)) . '-' . date("y", strtotime($fechaInicio));
    $fechaFin1 = date("d", strtotime($fechaFin)) . '-' . date("m", strtotime($fechaFin)) . '-' . date("Y", strtotime($fechaFin));

    $consulta = "SELECT SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) as presupuesto
        , sum(datediff(SECOND, t1.horaInicio, t1.horaFin))/60 as minutosTrabajados
        , sum(datediff(SECOND, t1.horaInicio, t1.horaFin))/60/60 as horasTrabajados
        , case when t2.finalizado='FINALIZADO' then t2.finalizado else 'EN  PROCESO' end as 'finalizado'
        , t3.cantidad, t3.cantidad2, sum(t1.cantidad) as cantidadRealizada
        , case when t3.cantidad2=0 or sum(t1.cantidad)=0 then 0 else CAST(SUM(t1.cantidad) AS BIGINT)*100/t3.cantidad2 end as tantoPorcientoRealizado
        FROM [".$bbddSql."].[dbo].[registroHoras] as t1
        left join (select tabla.*, 'FINALIZADO' as finalizado from (SELECT t1.*, t2.inicial
            FROM [".$bbddSql."].[dbo].[presupuestos] as t1
            inner join [".$bbddSql."].[dbo].[presupuestadores] as t2 on t1.idComercial = t2.id
            where t1.fechaTerminado <= GETDATE()
            or t1.numNoFactura is not null
            or t1.presupuesto in (select presupuesto from [".$bbddSql."].[dbo].[presupuestosFacturadosTodosLosAnios])
        ) as tabla) as t2 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t2.presupuesto
        left join [".$bbddSql."].[dbo].[presupuestos] as t3 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t3.presupuesto
        where t1.horaInicio >= ? and t1.horaFin < dateadd(day,1,?)
        group by SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7), t2.finalizado, t3.cantidad, t3.cantidad2
        order by finalizado, 1";

    $params = array($fechaInicio1, $fechaFin1);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function cargarProveedores($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $order, $filtrosLike = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'proveedor' => 't1.proveedor',
        'servicio' => 't1.servicio',
        'direccion' => 't1.direccion',
        'localidad' => 't1.localidad',
        'provincia' => 't1.provincia',
        'cp' => 't1.cp',
        'nif' => 't1.nif',
        'telefono' => 't1.telefono',
        'fax' => 't1.fax',
        'fechaAlta' => 't1.fechaAlta',
        'homologado' => 't1.homologado',
        'deshomologado' => 't1.deshomologado',
        'precioComparado' => 't1.precioComparado'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['id'])) {
        $condicion[] = 't1.id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['homologado'])) {
        $condicion[] = 't1.homologado = ?';
        $params[] = $filtros['homologado'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'id' => 't1.id',
        'proveedor' => 't1.proveedor',
        'homologado' => 't1.homologado'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    // ---------- FILTROS LIKE ----------
    $camposLikePermitidos = array(
        'proveedor' => 't1.proveedor',
        'servicio' => 't1.servicio',
        'direccion' => 't1.direccion',
        'cp' => 't1.cp',
        'localidad' => 't1.localidad'
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
        'proveedor' => 't1.proveedor',
        'servicio' => 't1.servicio',
        'direccion' => 't1.direccion',
        'cp' => 't1.cp',
        'localidad' => 't1.localidad'
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

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[proveedores] AS t1 $sqlWhere $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function insertarProveedor($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'proveedor' => 'proveedor',
        'servicio' => 'servicio',
        'direccion' => 'direccion',
        'localidad' => 'localidad',
        'provincia' => 'provincia',
        'cp' => 'cp',
        'nif' => 'nif',
        'telefono' => 'telefono',
        'precioComparado' => 'precioComparado'
    );

    $columnas = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $columnas[] = '[' . $camposPermitidos[$campo] . ']';
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($columnas)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    $columnas[] = '[fax]';
    $placeholders[] = "''";
    $columnas[] = '[fechaAlta]';
    $placeholders[] = 'GETDATE()';
    $columnas[] = '[homologado]';
    $placeholders[] = '1';
    $columnas[] = '[deshomologado]';
    $placeholders[] = "''";

    $consulta = "insert into [".$bbddSql."].[dbo].[proveedores] (" . implode(', ', $columnas) . ") OUTPUT INSERTED.id AS idProveedor values (" . implode(', ', $placeholders) . ")";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $idProveedor = null;
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    if ($fila) {
        $idProveedor = $fila['idProveedor'];
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array('idProveedor' => $idProveedor), 'sql' => $consulta, 'params' => $params);
}

function modificarProveedor($conn_sis, $bbddSql, $datos, $filtros)
{
    $camposPermitidos = array(
        'proveedor' => 'proveedor',
        'nif' => 'nif',
        'servicio' => 'servicio',
        'direccion' => 'direccion',
        'localidad' => 'localidad',
        'provincia' => 'provincia',
        'cp' => 'cp',
        'precioComparado' => 'precioComparado',
        'homologado' => 'homologado',
        'deshomologado' => 'deshomologado'
    );

    $sets = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $sets[] = '[' . $camposPermitidos[$campo] . '] = ?';
            $params[] = $valor;
        }
    }

    if (empty($sets)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    if (!isset($filtros['id'])) {
        return array('error' => 'filtro id obligatorio', 'datos' => array());
    }

    $params[] = $filtros['id'];

    $consulta = "UPDATE [".$bbddSql."].[dbo].[proveedores] SET " . implode(', ', $sets) . " WHERE id = ?";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function cargarProveedorContactos($conn_sis, $bbddSql, $campos, $filtros, $order = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'idSexo' => 't1.idSexo',
        'nombre' => 't1.nombre',
        'apellidos' => 't1.apellidos',
        'departamento' => 't1.departamento',
        'cargo' => 't1.cargo',
        'telefono' => 't1.telefono',
        'movil' => 't1.movil',
        'email' => 't1.email',
        'comentario' => 't1.comentario',
        'sexo' => 't2.sexo'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'nombre' => 't1.nombre'
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

    $consulta = "SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[proveedoresContactos] AS t1
        inner join [".$bbddSql."].[dbo].[sexo] as t2 on t1.idSexo = t2.id
        $sqlWhere $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function insertarContactoProveedor($conn_sis, $bbddSql, $datos)
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

    $columnas = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $columnas[] = '[' . $camposPermitidos[$campo] . ']';
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($columnas)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    $consulta = "insert into [".$bbddSql."].[dbo].[proveedoresContactos] (" . implode(', ', $columnas) . ") values (" . implode(', ', $placeholders) . ")";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function modificarContactoProveedor($conn_sis, $bbddSql, $datos, $filtros)
{
    $camposPermitidos = array(
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

    $sets = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $sets[] = '[' . $camposPermitidos[$campo] . '] = ?';
            $params[] = $valor;
        }
    }

    if (empty($sets)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    if (!isset($filtros['id'])) {
        return array('error' => 'filtro id obligatorio', 'datos' => array());
    }

    $params[] = $filtros['id'];

    $consulta = "UPDATE [".$bbddSql."].[dbo].[proveedoresContactos] SET " . implode(', ', $sets) . " WHERE id = ?";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function eliminarContactoProveedor($conn_sis, $bbddSql, $filtros)
{
    if (!isset($filtros['id'])) {
        return array('error' => 'filtro id obligatorio', 'datos' => array());
    }

    $consulta = "delete from [".$bbddSql."].[dbo].[proveedoresContactos] where id = ?";
    $params = array($filtros['id']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function cargarComprasTerceros($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $group = array())
{
    //listado de compras a terceros: por pedido (agrupado, con importe = sum(comprasTercerosDetalles))
    //o a nivel de linea de producto (sin agrupar) segun los campos/group que se pidan
    $camposPermitidos = array(
        'pedido' => 't1.pedido',
        'idComercial' => 't1.idComercial',
        'presupuesto' => 't1.presupuesto',
        'fecha' => 't1.fecha',
        'idProveedor' => 't1.idProveedor',
        'contactoProveedor' => 't1.contactoProveedor',
        'fechaEntrega' => 't1.fechaEntrega',
        'idFormaPago' => 't1.idFormaPago',
        'idCliente' => 't1.idCliente',
        'contactoCliente' => 't1.contactoCliente',
        'fechaFacturaCompra' => 't1.fechaFacturaCompra',
        'numeroFacturaCompra' => 't1.numeroFacturaCompra',
        'pedidoAntiguo' => 't1.pedidoAntiguo',
        'observacionesInternas' => 't1.observacionesInternas',
        'pdfGenerado' => 't1.pdfGenerado',
        'anual' => 't1.anual',
        'nombreCliente' => 't2.cliente as nombreCliente',
        'nombreProveedor' => 't3.proveedor as nombreProveedor',
        'importe' => 'sum(t4.total) as importe',
        'descripcion' => 't4.descripcion',
        'total' => 't4.total',
        'fechaPresupuesto' => 't2.fecha as fechaPresupuesto',
        'fechaFactura' => 't5.fechaPago as fechaFactura',
        'numeroFactura' => 't5.numero as numeroFactura',
        'fechaFacturaClayma' => 't8.fechaPago as fechaFacturaClayma',
        'numeroFacturaClayma' => 't8.numero as numeroFacturaClayma',
        'comercialNombre' => 't6.nombre as comercialNombre',
        'comercialInicial' => 't6.inicial as comercialInicial',
        'formaPago' => 't7.concepto as formaPago',
        'clayma' => 't2.clayma as clayma',
        'anioFactura' => 't5.anioFactura',
        'anioFacturaClayma' => 't8.anioFactura as anioFacturaClayma'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    //t2: presupuestos
    //t3: proveedores
    //t4: comprasTercerosDetalles (importe/descripcion/total)
    //t5: facturasTodosLosAnios
    //t6: comerciales
    //t7: formaDePagoCompraTerceros
    //t8: facturasClaymaTodosLosAnios

    $joinsPermitidos = array(
        'tabla2' => "inner join [".$bbddSql."].[dbo].presupuestos as t2 on t1.presupuesto = t2.presupuesto",
        'tabla3' => "inner join [".$bbddSql."].[dbo].proveedores as t3 on t1.idProveedor = t3.id",
        'tabla4' => "left join [".$bbddSql."].[dbo].[comprasTercerosDetalles] as t4 on t1.pedido = t4.pedido",
        'tabla5' => "left join [".$bbddSql."].[dbo].[facturasTodosLosAnios] as t5 on t1.presupuesto = t5.presupuesto",
        'tabla6' => "left join [".$bbddSql."].dbo.comerciales as t6 on t1.idComercial = t6.id",
        'tabla7' => "left join [".$bbddSql."].[dbo].[formaDePagoCompraTerceros] as t7 on t1.idFormaPago = t7.id",
        'tabla8' => "left join [".$bbddSql."].[dbo].[facturasClaymaTodosLosAnios] as t8 on t1.presupuesto = t8.presupuesto"
    );

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

    if (isset($filtros['pedido'])) {
        $condicion[] = 't1.pedido = ?';
        $params[] = $filtros['pedido'];
    }
    if (!empty($filtros['excluirProveedorTest'])) {
        $condicion[] = 't1.idProveedor != 633';
    }
    if (!empty($filtros['excluirInternos'])) {
        $condicion[] = "t1.presupuesto >= '100'";
    }
    if (!empty($filtros['soloActivos'])) {
        $condicion[] = "((t1.presupuesto='0000001' and (numeroFacturaCompra is null or numeroFacturaCompra = '')) or (t1.anual=0 and t1.presupuesto>1000 and (t5.numero is null or numeroFacturaCompra is null)))";
    }
    if (!empty($filtros['soloAnuales'])) {
        $condicion[] = 't1.anual = 1';
    }
    if (!empty($filtros['sinFacturar'])) {
        $condicion[] = "(t1.numeroFacturaCompra = '' or t1.numeroFacturaCompra is null)";
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=', 'LIKE');

    $camposComparablesPermitidos = array(
        'pedido' => 't1.pedido',
        'presupuesto' => 't1.presupuesto',
        'idProveedor' => 't1.idProveedor',
        'cliente' => 't2.cliente',
        'proveedor' => 't3.proveedor',
        'descripcion' => 't4.descripcion'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                if (strtoupper($f['operador']) == 'LIKE') {
                    $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' LIKE ?';
                    $params[] = '%' . $f['valor'] . '%';
                } else {
                    $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
                    $params[] = $f['valor'];
                }
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- GROUP BY ----------
    $camposGroupPermitidos = array(
        'pedido' => 't1.pedido',
        'idComercial' => 't1.idComercial',
        'presupuesto' => 't1.presupuesto',
        'fecha' => 't1.fecha',
        'idProveedor' => 't1.idProveedor',
        'contactoProveedor' => 't1.contactoProveedor',
        'fechaEntrega' => 't1.fechaEntrega',
        'idFormaPago' => 't1.idFormaPago',
        'idCliente' => 't1.idCliente',
        'contactoCliente' => 't1.contactoCliente',
        'fechaFacturaCompra' => 't1.fechaFacturaCompra',
        'numeroFacturaCompra' => 't1.numeroFacturaCompra',
        'pedidoAntiguo' => 't1.pedidoAntiguo',
        'observacionesInternas' => 't1.observacionesInternas',
        'pdfGenerado' => 't1.pdfGenerado',
        'anual' => 't1.anual',
        'nombreCliente' => 't2.cliente',
        'nombreProveedor' => 't3.proveedor',
        'fechaPresupuesto' => 't2.fecha',
        'fechaFactura' => 't5.fechaPago',
        'numeroFactura' => 't5.numero',
        'comercialNombre' => 't6.nombre',
        'comercialInicial' => 't6.inicial',
        'formaPago' => 't7.concepto',
        'clayma' => 't2.clayma',
        'fechaFacturaClayma' => 't8.fechaPago',
        'numeroFacturaClayma' => 't8.numero',
        'anioFactura' => 't5.anioFactura',
        'anioFacturaClayma' => 't8.anioFactura'
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
        'fechaFacturaCompra' => 't1.fechaFacturaCompra',
        'nombreCliente' => 't2.cliente',
        'importe' => 'importe',
        'nombreProveedor' => 't3.proveedor',
        'pedido' => 't1.pedido',
        'presupuesto' => 't1.presupuesto',
        'descripcion' => 't4.descripcion'
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

    $consulta = "SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[compraTerceros] as t1
        $sqlJoins
        $sqlWhere
        $sqlGroup
        $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function insertarComprarTercero($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idComercial' => 'idComercial',
        'presupuesto' => 'presupuesto',
        'idProveedor' => 'idProveedor',
        'contactoProveedor' => 'contactoProveedor',
        'idFormapago' => 'idFormapago',
        'anual' => 'anual'
    );

    $columnas = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $columnas[] = '[' . $camposPermitidos[$campo] . ']';
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($columnas)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    $columnas[] = '[fecha]';
    $placeholders[] = 'GETDATE()';

    $consulta = "insert into [".$bbddSql."].[dbo].[compraTerceros] (" . implode(', ', $columnas) . ") OUTPUT INSERTED.pedido AS idCompra values (" . implode(', ', $placeholders) . ")";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $idCompra = null;
    $fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC);
    if ($fila) {
        $idCompra = $fila['idCompra'];
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array('idCompra' => $idCompra), 'sql' => $consulta, 'params' => $params);
}

function copiarDetallesCompras($conn_sis, $bbddSql, $datos)
{
    if (!isset($datos['numeroPedidoNuevo']) || !isset($datos['numeroPedidoCopiar'])) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    $consulta = "insert into [".$bbddSql."].[dbo].[comprasTercerosDetalles] (pedido, descripcion, cantidad, precioUnidad, precioVenta, total, margen)
        select ?, descripcion, cantidad, precioUnidad, precioVenta, total, margen
        FROM [".$bbddSql."].[dbo].[comprasTercerosDetalles] where pedido = ?";

    $params = array($datos['numeroPedidoNuevo'], $datos['numeroPedidoCopiar']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function cargarFormasDePagoComprasATerceros($conn_sis, $bbddSql, $campos, $order = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'concepto' => 't1.concepto'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'concepto' => 't1.concepto'
    );

    $sqlOrder = ' ORDER BY t1.concepto';
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

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[formaDePagoCompraTerceros] AS t1 $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta);
}

function modificarComprasTerceros($conn_sis, $bbddSql, $datos, $filtros)
{
    //fechaEntrega/fechaFacturaCompra: cadena vacia se guarda como NULL (igual que el legacy)
    $camposPermitidos = array(
        'idComercial' => 'idComercial',
        'presupuesto' => 'presupuesto',
        'idProveedor' => 'idProveedor',
        'contactoProveedor' => 'contactoProveedor',
        'observacionesInternas' => 'observacionesInternas',
        'fechaEntrega' => 'fechaEntrega',
        'fechaFacturaCompra' => 'fechaFacturaCompra',
        'idFormaPago' => 'idFormaPago',
        'contactoCliente' => 'contactoCliente',
        'numeroFacturaCompra' => 'numeroFacturaCompra'
    );

    $camposFecha = array('fechaEntrega', 'fechaFacturaCompra');

    $sets = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            if (in_array($campo, $camposFecha) && $valor === '') {
                $valor = null;
            }
            $sets[] = '[' . $camposPermitidos[$campo] . '] = ?';
            $params[] = $valor;
        }
    }

    if (empty($sets)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    if (!isset($filtros['pedido'])) {
        return array('error' => 'filtro pedido obligatorio', 'datos' => array());
    }

    $params[] = $filtros['pedido'];

    $consulta = "UPDATE [".$bbddSql."].[dbo].[compraTerceros] SET " . implode(', ', $sets) . " WHERE pedido = ?";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function insertarDetalleCompraTercero($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'pedido' => 'pedido',
        'descripcion' => 'descripcion',
        'cantidad' => 'cantidad',
        'precioUnidad' => 'precioUnidad',
        'precioVenta' => 'precioVenta',
        'total' => 'total',
        'margen' => 'margen'
    );

    $columnas = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $columnas[] = '[' . $camposPermitidos[$campo] . ']';
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($columnas)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    $consulta = "insert into [".$bbddSql."].[dbo].[comprasTercerosDetalles] (" . implode(', ', $columnas) . ") values (" . implode(', ', $placeholders) . ")";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function cargarComprasTercerosDetalles($conn_sis, $bbddSql, $campos, $filtros, $order = array())
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'pedido' => 't1.pedido',
        'descripcion' => 't1.descripcion',
        'cantidad' => 't1.cantidad',
        'precioUnidad' => 't1.precioUnidad',
        'precioVenta' => 't1.precioVenta',
        'total' => 't1.total',
        'margen' => 't1.margen'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $condicion = array();
    $params = array();

    if (isset($filtros['pedido'])) {
        $condicion[] = 't1.pedido = ?';
        $params[] = $filtros['pedido'];
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    $camposOrdenPermitidos = array(
        'id' => 't1.id'
    );

    $sqlOrder = ' ORDER BY t1.id ASC';
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

    $consulta = "SELECT $listaCampos FROM [".$bbddSql."].[dbo].[comprasTercerosDetalles] AS t1 $sqlWhere $sqlOrder";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function modificarDetalleCompraTercero($conn_sis, $bbddSql, $datos, $filtros)
{
    $camposPermitidos = array(
        'descripcion' => 'descripcion',
        'cantidad' => 'cantidad',
        'precioUnidad' => 'precioUnidad',
        'total' => 'total',
        'precioVenta' => 'precioVenta',
        'margen' => 'margen'
    );

    $sets = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $sets[] = '[' . $camposPermitidos[$campo] . '] = ?';
            $params[] = $valor;
        }
    }

    if (empty($sets)) {
        return array('error' => 'datos vacios', 'datos' => array());
    }

    if (!isset($filtros['id'])) {
        return array('error' => 'filtro id obligatorio', 'datos' => array());
    }

    $params[] = $filtros['id'];

    $consulta = "UPDATE [".$bbddSql."].[dbo].[comprasTercerosDetalles] SET " . implode(', ', $sets) . " WHERE id = ?";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function eliminarDetalleComprarTercero($conn_sis, $bbddSql, $filtros)
{
    if (!isset($filtros['id'])) {
        return array('error' => 'filtro id obligatorio', 'datos' => array());
    }

    $consulta = "delete from [".$bbddSql."].[dbo].[comprasTercerosDetalles] where id = ?";
    $params = array($filtros['id']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function modificarComprasTercerosPdfImpreso($conn_sis, $bbddSql, $filtros)
{
    if (!isset($filtros['pedido'])) {
        return array('error' => 'filtro pedido obligatorio', 'datos' => array());
    }

    $consulta = "UPDATE [".$bbddSql."].[dbo].[compraTerceros] SET [pdfGenerado] = 1 WHERE pedido = ?";
    $params = array($filtros['pedido']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => array(), 'sql' => $consulta, 'params' => $params);
}

function cargarRegistrosHoras($conn_sis, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order, $group = array())
{
    $camposPermitidos = array(
        'todos' => 't1.*',
        'id' => 't1.id',
        'codigoBarras' => 't1.codigoBarras',
        'estado' => 't1.estado',
        'horaInicio' => 't1.horaInicio',
        'horaFin' => 't1.horaFin',
        'idEmpleado' => 't1.idEmpleado',
        'nombreEmpleado' => 't1.nombreEmpleado',
        'cantidad' => 't1.cantidad',
        'observaciones' => 't1.observaciones',
        'modo' => 't1.modo',
        'horarioInicio' => 'te.horarioInicio',
        'horarioFin' => 'te.horarioFin',
        'cliente' => 't2.cliente',
        'campana' => "t2.campana as 'campana'",
        'cantidadTrabajo' => "t2.cantidad as 'cantidad trabajo'",
        'comercialPresupuesto' => 't2.comercial',
        'fechaCompromiso' => 't2.[fechaCompromiso]',
        'concepto' => 't4.proceso as concepto',
        'cantidadProceso' => "t3.unidades as 'cantidad proceso'",
        'descripcion' => 't3.descripcion',
        'notaCibeles' => 't3.notaCibeles',
        'presupuesto' => 't3.presupuesto',
        'presupuestador' => 't5.nombre as presupuestador',
        'comercial' => 't7.nombre as comercial',
        'subcliente' => 't11.subcliente',
        'departamento' => 't10.departamento',
        'idDepartamento' => 't10.id as idDepartamento',
        'tipoProceso' => 't9.tipoProceso',
        'proceso' => 't8.proceso',
        'sinProceso_idConcepto' => 't1.sinProceso_idConcepto',
        'sinProceso_idCliente' => 't1.sinProceso_idCliente',
        'sinProceso_idTipoProceso' => 't15.idTipoProceso as sinProceso_idTipoProceso',
        'idGFSubconjunto2' => 't1.idGFSubconjunto2',
        'idGFSubconcepto1' => 't17.id as idGFSubconcepto1',
        'idGFConcepto' => 't18.id as idGFConcepto',
        'gfTipoProceso' => 't13.tipoProceso as tipoProceso',
        'gfProceso' => 't14.proceso as proceso',
        'gfDescripcion' => 't12.descripcion as descripcion',
        'idImpresoras' => 't1.idImpresoras',
        'idPapelTamano' => 't1.idPapelTamano',
        'idPapelTipo' => 't1.idPapelTipo',
        'idPapelAcabado' => 't1.idPapelAcabado',
        'idPapelGramaje' => 't1.idPapelGramaje',
        'idPapelOrigen' => 't1.idPapelOrigen',
        'impresionNumeroCaras' => 't1.impresionNumeroCaras',
        'impresoras' => 't19.impresoras',
        'tamanoPapel' => 't20.tamano as tamano',
        'tipoPapel' => 't21.tipo as tipo',
        'acabadoPapel' => 't22.acabado as acabado',
        'gramajePapel' => 't23.gramaje as gramaje',
        'origenPapel' => 't24.origen as origen',
        'horas' => "isnull(RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) / 3600 as VARCHAR),2) + ':' + RIGHT('0'+ cast((sum(datediff(second, t1.horaInicio, t1.horaFin)) / 60)%60 as VARCHAR),2)+ ':' + RIGHT('0'+ cast(sum(datediff(second, t1.horaInicio, t1.horaFin)) % 60 as VARCHAR),2),0) as horas",
        'horasTotal' => "RIGHT('0'+ cast(sum(sum(datediff(second, t1.horaInicio, t1.horaFin))) over () / 3600 as VARCHAR),2) + ':' + RIGHT('0'+ cast((sum(sum(datediff(second, t1.horaInicio, t1.horaFin))) over () / 60)%60 as VARCHAR),2)+ ':' + RIGHT('0'+ cast(sum(sum(datediff(second, t1.horaInicio, t1.horaFin))) over () % 60 as VARCHAR),2) as horasTotal"
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- JOINS ----------
    // t2: presupuestos | t3: presupuestos detalle | t4: procesos | t5: presupuestadores | t6: clientes union clientesClayma | t7: comerciales | te: empleados
    // registros manuales (sinProceso_*): t8: procesos | t9: procesosTipos | t10: procesosDepartamento | t11: clientes
    // GF (LEFT, listado gran formato): t12: presupuestos detalle | t13: procesosTipos | t14: procesos | t15: procesos(sinProceso) | t16: L_gf_subconcepto2 | t17: L_gf_subconcepto1 | t18: L_gf_concepto
    // papel (LEFT, listado informatica): t19: L_impresoras | t20: L_papelTamanio | t21: L_papelTipo | t22: L_papelAcabado | t23: L_papelGramaje | t24: L_papelOrigen
    $joinsPermitidos = array(
        'tabla_presupuestos' => "inner join [".$bbddSql."].[dbo].[presupuestos] as t2 on SUBSTRING(t1.codigoBarras,CHARINDEX('-', t1.codigoBarras)+1,7) = t2.presupuesto",
        'tabla_presupuestosDetalle' => "inner join [".$bbddSql."].[dbo].[presupuestos detalle] as t3 on SUBSTRING(t1.codigoBarras,0,CHARINDEX('-', t1.codigoBarras)) = t3.id",
        'tabla_procesos' => "inner join [".$bbddSql."].[dbo].[procesos] as t4 on t4.id = t3.idConcepto",
        'tabla_presupuestadores' => "left join [".$bbddSql."].[dbo].[presupuestadores] as t5 on t5.id = t2.idComercial",
        'tabla_clientesUnion' => "left join (SELECT nombre_empresa, idComercial, 0 as clayma FROM [".$bbddSql."].[dbo].[clientes] union SELECT nombre_empresa, idComercial, 1 as clayma FROM [".$bbddSql."].[dbo].[clientesClayma]) as t6 on t6.nombre_empresa = t2.cliente and t6.clayma = t2.clayma",
        'tabla_comerciales' => "left join [".$bbddSql."].[dbo].[comerciales] as t7 on t7.id = t6.idComercial",
        'tabla_empleados' => "inner join [".$bbddSql."].[dbo].[empleados] as te on te.id = t1.idEmpleado",
        'tabla_procesoManual' => "inner join [".$bbddSql."].[dbo].[procesos] as t8 on t8.id = t1.sinProceso_idConcepto",
        'tabla_procesosTiposManual' => "inner join [".$bbddSql."].[dbo].[procesosTipos] as t9 on t8.idTipoProceso = t9.id",
        'tabla_procesosDepartamentoManual' => "inner join [".$bbddSql."].[dbo].[procesosDepartamento] as t10 on t10.id = t8.idDepartamento",
        'tabla_clienteManual' => "left join [".$bbddSql."].[dbo].[clientes] as t11 on t11.codigo = t1.sinProceso_idCliente",
        'tabla_gfDetalle' => "left join [".$bbddSql."].[dbo].[presupuestos detalle] as t12 on t12.id = SUBSTRING(t1.codigoBarras,1,len(t1.codigoBarras)-8)",
        'tabla_gfProcesosTipos' => "left join [".$bbddSql."].[dbo].[procesosTipos] as t13 on t13.id = t12.idTipo",
        'tabla_gfProcesos' => "left join [".$bbddSql."].[dbo].[procesos] as t14 on t14.id = t12.idConcepto",
        'tabla_gfProcesoSin' => "left join [".$bbddSql."].[dbo].[procesos] as t15 on t15.id = t1.sinProceso_idConcepto",
        'tabla_gfSub2' => "left join [".$bbddSql."].[dbo].[L_gf_subconcepto2] as t16 on t16.id = t1.idGFSubconjunto2",
        'tabla_gfSub1' => "left join [".$bbddSql."].[dbo].[L_gf_subconcepto1] as t17 on t16.idSubconcepto1 = t17.id",
        'tabla_gfConcepto' => "left join [".$bbddSql."].[dbo].[L_gf_concepto] as t18 on t17.idConcepto = t18.id",
        'tabla_impresoras' => "left join [".$bbddSql."].[dbo].[L_impresoras] as t19 on t19.id = t1.idImpresoras",
        'tabla_papelTamano' => "left join [".$bbddSql."].[dbo].[L_papelTamanio] as t20 on t20.id = t1.idPapelTamano",
        'tabla_papelTipo' => "left join [".$bbddSql."].[dbo].[L_papelTipo] as t21 on t21.id = t1.idPapelTipo",
        'tabla_papelAcabado' => "left join [".$bbddSql."].[dbo].[L_papelAcabado] as t22 on t22.id = t1.idPapelAcabado",
        'tabla_papelGramaje' => "left join [".$bbddSql."].[dbo].[L_papelGramaje] as t23 on t23.id = t1.idPapelGramaje",
        'tabla_papelOrigen' => "left join [".$bbddSql."].[dbo].[L_papelOrigen] as t24 on t24.id = t1.idPapelOrigen"
    );

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
    if (isset($filtros['codigoBarras'])) {
        $condicion[] = 't1.codigoBarras = ?';
        $params[] = $filtros['codigoBarras'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 't1.idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }
    if (isset($filtros['modo'])) {
        $condicion[] = 't1.modo = ?';
        $params[] = $filtros['modo'];
    }
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
    if (isset($filtros['maxIdPorEmpleado'])) {
        $condicion[] = 't1.id = (select max(tmax.id) from ['.$bbddSql.'].[dbo].[registroHoras] as tmax where tmax.idEmpleado = ?)';
        $params[] = $filtros['maxIdPorEmpleado'];
    }
    if (isset($filtros['otLike'])) {
        $condicion[] = 't1.codigoBarras like ?';
        $params[] = '%-'.$filtros['otLike'];
    }
    if (isset($filtros['idGFSubconjunto2NotNull'])) {
        $condicion[] = 't1.idGFSubconjunto2 is not null';
    }
    if (isset($filtros['idImpresorasNotNull'])) {
        $condicion[] = 't1.idImpresoras is not null';
    }
    if (isset($filtros['masde10horas'])) {
        $condicion[] = "(t1.estado != 'abierto' and DATEDIFF(HOUR, t1.horaInicio, t1.horaFin) > 10)";
    }
    if (isset($filtros['fechaRangoOManual']) && is_array($filtros['fechaRangoOManual'])) {
        $fr = $filtros['fechaRangoOManual'];
        $cond = array();
        if (isset($fr['inicio'])) {
            $cond[] = 't1.horaInicio >= ?';
            $params[] = $fr['inicio'];
        }
        if (isset($fr['fin'])) {
            $cond[] = 't1.horaInicio < ?';
            $params[] = $fr['fin'];
        }
        if (!empty($cond)) {
            $condicion[] = '((' . implode(' and ', $cond) . ") or t1.codigoBarras = '0-9999999')";
        }
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array(
        'estado' => 't1.estado',
        'horaInicio' => 't1.horaInicio',
        'fechaInicioDia' => 'convert(date, t1.horaInicio)'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
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
        'id' => 't1.id',
        'codigoBarras' => 't1.codigoBarras',
        'nombreEmpleado' => 't1.nombreEmpleado',
        'horaInicio' => 't1.horaInicio',
        'horaFin' => 't1.horaFin',
        'cantidad' => 't1.cantidad',
        'observaciones' => 't1.observaciones',
        'estado' => 't1.estado'
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
        'id' => 't1.id',
        'horaInicio' => 't1.horaInicio',
        'horaFin' => 't1.horaFin',
        'cantidad' => 't1.cantidad',
        'nombreEmpleado' => 't1.nombreEmpleado',
        'codigoBarras' => 't1.codigoBarras',
        'estado' => 't1.estado',
        'subcliente' => 't11.subcliente'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[registroHoras] AS t1
        $sqlJoins
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'datos' => array(), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function insertarRegistroHoras($conn_sis, $bbddSql, $datos)
{
    $camposPermitidos = array(
        'idEmpleado' => 'idEmpleado',
        'codigoBarras' => 'codigoBarras',
        'horaInicio' => 'horaInicio',
        'horaFin' => 'horaFin',
        'estado' => 'estado',
        'cantidad' => 'cantidad',
        'observaciones' => 'observaciones',
        'modo' => 'modo',
        'sinProceso_idConcepto' => 'sinProceso_idConcepto',
        'sinProceso_idCliente' => 'sinProceso_idCliente',
        'idGFSubconjunto2' => 'idGFSubconjunto2',
        'idImpresoras' => 'idImpresoras',
        'idPapelTamano' => 'idPapelTamano',
        'idPapelTipo' => 'idPapelTipo',
        'idPapelAcabado' => 'idPapelAcabado',
        'idPapelGramaje' => 'idPapelGramaje',
        'idPapelOrigen' => 'idPapelOrigen',
        'impresionNumeroCaras' => 'impresionNumeroCaras'
    );

    $columnas = array();
    $placeholders = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $columnas[] = '[' . $camposPermitidos[$campo] . ']';
            $placeholders[] = '?';
            $params[] = $valor;
        }
    }

    if (empty($columnas)) {
        return array('error' => 'datos vacios');
    }

    $consulta = "insert into [".$bbddSql."].[dbo].[registroHoras] (" . implode(', ', $columnas) . ") values (" . implode(', ', $placeholders) . ")";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'sql' => $consulta, 'params' => $params);
}

function modificarRegistroHoras($conn_sis, $bbddSql, $datos, $filtros)
{
    $camposPermitidos = array(
        'horaInicio' => 'horaInicio',
        'horaFin' => 'horaFin',
        'estado' => 'estado',
        'cantidad' => 'cantidad',
        'observaciones' => 'observaciones',
        'modo' => 'modo',
        'codigoBarras' => 'codigoBarras',
        'sinProceso_idConcepto' => 'sinProceso_idConcepto',
        'sinProceso_idCliente' => 'sinProceso_idCliente',
        'nombreEmpleado' => 'nombreEmpleado',
        'idGFSubconjunto2' => 'idGFSubconjunto2',
        'idImpresoras' => 'idImpresoras',
        'idPapelTamano' => 'idPapelTamano',
        'idPapelTipo' => 'idPapelTipo',
        'idPapelAcabado' => 'idPapelAcabado',
        'idPapelGramaje' => 'idPapelGramaje',
        'idPapelOrigen' => 'idPapelOrigen',
        'impresionNumeroCaras' => 'impresionNumeroCaras'
    );

    $sets = array();
    $params = array();

    foreach ($datos as $campo => $valor) {
        if (isset($camposPermitidos[$campo])) {
            $sets[] = '[' . $camposPermitidos[$campo] . '] = ?';
            $params[] = $valor;
        }
    }

    if (empty($sets)) {
        return array('error' => 'datos vacios');
    }

    $condicion = array();
    if (isset($filtros['id'])) {
        $condicion[] = 'id = ?';
        $params[] = $filtros['id'];
    }
    if (isset($filtros['idEmpleado'])) {
        $condicion[] = 'idEmpleado = ?';
        $params[] = $filtros['idEmpleado'];
    }
    if (isset($filtros['codigoBarras'])) {
        $condicion[] = 'codigoBarras = ?';
        $params[] = $filtros['codigoBarras'];
    }

    if (empty($condicion)) {
        return array('error' => 'modificar sin filtros no permitido');
    }

    $consulta = "update [".$bbddSql."].[dbo].[registroHoras] set " . implode(', ', $sets) . " where " . implode(' AND ', $condicion);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'sql' => $consulta, 'params' => $params);
}

function insertarUsuarioRegistroTrabajo($conn_sis, $bbddSql, $id)
{
    $consulta = "update t1 set t1.nombreEmpleado = t2.nombre + ' ' + t2.apellidos
    from [".$bbddSql."].[dbo].[registroHoras] as t1
    inner join [".$bbddSql."].[dbo].[empleados] as t2 on t1.idEmpleado = t2.id
    where t1.id = ?";

    $params = array($id);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'sql' => $consulta, 'params' => $params);
}

function cargarAlbaranTipo($conn_sis, $bbddSql, $campos, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'tipo' => 't1.tipo'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'tipo' => 't1.tipo'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[albaranTipo] AS t1
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta);
}

function insertarAlbaran($conn_sis, $bbddSql, $datos)
{
    $idEmpleado = $datos['idEmpleado'];
    $idTipo = $datos['idTipoAlbaran'];
    $idCliente = $datos['idCliente'];
    $cantidad = $datos['cantidad'];
    $importe = $datos['importe'];
    $descripcion = $datos['descripcion'];

    $fechaHora = str_replace("T", " ", $datos['fecha']);
    $fechaConHora = date("Y-m-d H:i:s", strtotime($fechaHora));
    $fechaInicioDia = date("Y-m-d", strtotime($fechaHora));
    $fechaInicioDiaSiguiente = date("Y-m-d", strtotime($fechaInicioDia." +1 days"));

    $prefijoRecibo = date("md", strtotime($fechaHora));
    $sufijoRecibo = '/'.date("y", strtotime($fechaHora));

    $consulta = "
        INSERT INTO [".$bbddSql."].[dbo].[albaranes] (idEmpleado, idTipo, idCliente, fecha, cantidad, importe, descripcion, numeroReciboUnDia, numeroRecibo)
        SELECT ?, ?, ?, ?, ?, ?, ?, ISNULL(MAX(numeroReciboUnDia),0)+1, CONCAT(?, ISNULL(MAX(numeroReciboUnDia),0)+1, ?)
        FROM [".$bbddSql."].[dbo].[albaranes]
        WHERE fecha >= ? AND fecha < ?
    ";

    $params = array($idEmpleado, $idTipo, $idCliente, $fechaConHora, $cantidad, $importe, $descripcion, $prefijoRecibo, $sufijoRecibo, $fechaInicioDia, $fechaInicioDiaSiguiente);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function cargarAlbaranes($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $filtrosLike, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'numeroRecibo' => 't1.numeroRecibo',
        'fecha' => 't1.fecha',
        'nombre' => "t3.nombre + ' ' + t3.apellidos as nombre",
        'tipo' => 't4.tipo',
        'idCliente' => 't1.idCliente',
        'nombre_franqueo' => 't2.nombre_franqueo',
        'cantidad' => 't1.cantidad',
        'importe' => 't1.importe',
        'descripcion' => 't1.descripcion'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $condicion = array();
    $params = array();

    if (isset($filtros['numeroRecibo'])) {
        $condicion[] = 't1.numeroRecibo = ?';
        $params[] = $filtros['numeroRecibo'];
    }
    if (isset($filtros['idCliente'])) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $camposLikePermitidos = array(
        'descripcion' => 't1.descripcion',
        'importe' => 't1.importe',
        'nombre_franqueo' => 't2.nombre_franqueo'
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

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'numeroRecibo' => 't1.numeroRecibo',
        'descripcion' => 't1.descripcion',
        'idCliente' => 't1.idCliente',
        'importe' => 't1.importe',
        'nombre_franqueo' => 't2.nombre_franqueo'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[albaranes] AS t1
        inner join [".$bbddSql."].[dbo].[clientes] as t2 on t1.idCliente = t2.codigo
        inner join [".$bbddSql."].[dbo].[empleados] as t3 on t3.id = t1.idEmpleado
        inner join [".$bbddSql."].[dbo].[albaranTipo] as t4 on t4.id = t1.idTipo
        $sqlWhere
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function eliminarAlbaran($conn_sis, $bbddSql, $filtros)
{
    if (!isset($filtros['id'])) {
        return array('error' => 'id vacio', 'ok' => false);
    }

    $consulta = "DELETE FROM [".$bbddSql."].[dbo].[albaranes] WHERE id = ?";
    $params = array($filtros['id']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function cargarCertificadoProductos($conn_sis, $bbddSql, $campos, $filtros, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'producto' => 't1.producto',
        'importeUnitario' => 't1.importeUnitario'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

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

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[certificadoProductos] AS t1
        $sqlWhere
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function insertarCertificadosGrabados($conn_sis, $bbddSql, $datos)
{
    $idCliente = $datos['idCliente'];
    $unidades = $datos['unidades'];
    $idProducto = $datos['idProducto'];
    $fecha = date("d-m-Y", strtotime($datos['fecha']));

    $consultaEspecial = "SELECT importeUnitario FROM [".$bbddSql."].[dbo].[certificadoEspeciales] WHERE idCliente = ? AND idProducto = ?";
    $resultadoEspecial = sqlsrv_query($conn_sis, $consultaEspecial, array($idCliente, $idProducto));

    if ($resultadoEspecial === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false);
    }

    $filaEspecial = sqlsrv_fetch_array($resultadoEspecial, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($resultadoEspecial);

    if ($filaEspecial !== null && $filaEspecial !== false) {
        $importeUnitario = $filaEspecial['importeUnitario'];
    } else {
        $consultaProducto = "SELECT importeUnitario FROM [".$bbddSql."].[dbo].[certificadoProductos] WHERE id = ?";
        $resultadoProducto = sqlsrv_query($conn_sis, $consultaProducto, array($idProducto));

        if ($resultadoProducto === false) {
            return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false);
        }

        $filaProducto = sqlsrv_fetch_array($resultadoProducto, SQLSRV_FETCH_ASSOC);
        sqlsrv_free_stmt($resultadoProducto);
        $importeUnitario = $filaProducto['importeUnitario'];
    }

    $consulta = "INSERT INTO [".$bbddSql."].[dbo].[CertificadosGrabados] (idCliente, unidades, idProducto, importeUnitario, fecha) VALUES (?, ?, ?, ?, ?)";
    $params = array($idCliente, $unidades, $idProducto, $importeUnitario, $fecha);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function mostrarCertificadosGrabados($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $group, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'nombre_franqueo' => 't3.nombre_franqueo',
        'subcliente' => 't3.subcliente',
        'unidades' => 't1.unidades',
        'unidadesSumatorio' => 'SUM(t1.unidades) as unidadesSumatorio',
        'producto' => 't2.producto',
        'fecha' => 't1.fecha',
        'importeUnitario' => 't1.importeUnitario',
        'totalSumatorio' => 'SUM(t1.unidades*t1.importeUnitario) as totalSumatorio'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    $condicion = array();
    $params = array();

    if (isset($filtros['idCliente']) && $filtros['idCliente'] != 0) {
        $condicion[] = 't1.idCliente = ?';
        $params[] = $filtros['idCliente'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');

    $camposComparablesPermitidos = array(
        'fecha' => 't1.fecha'
    );

    if (is_array($filtrosOperadores) && !empty($filtrosOperadores)) {
        foreach ($filtrosOperadores as $f) {
            if (
                isset($f['campo1'], $f['valor'], $f['operador']) &&
                isset($camposComparablesPermitidos[$f['campo1']]) &&
                in_array($f['operador'], $operadoresPermitidos)
            ) {
                $condicion[] = $camposComparablesPermitidos[$f['campo1']] . ' ' . $f['operador'] . ' ?';
                $params[] = $f['valor'];
            }
        }
    }

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    $camposGroupPermitidos = array(
        'idCliente' => 't1.idCliente',
        'subcliente' => 't3.subcliente',
        'producto' => 't2.producto',
        'fecha' => 't1.fecha',
        'importeUnitario' => 't1.importeUnitario'
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

    $camposOrdenPermitidos = array(
        'id' => 't1.id',
        'idCliente' => 't1.idCliente',
        'fecha' => 't1.fecha',
        'nombre_franqueo' => 't3.nombre_franqueo',
        'subcliente' => 't3.subcliente',
        'producto' => 't2.producto'
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

    $consulta = "
        SELECT $listaCampos
        FROM [".$bbddSql."].[dbo].[CertificadosGrabados] AS t1
        inner join [".$bbddSql."].[dbo].[certificadoProductos] as t2 on t1.idProducto = t2.id
        inner join [".$bbddSql."].[dbo].[clientes] as t3 on t1.idCliente = t3.codigo
        $sqlWhere
        $sqlGroup
        $sqlOrder
    ";

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'sql' => $consulta, 'params' => $params);
    }

    $result = array();
    while ($fila = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
        $result[] = $fila;
    }

    sqlsrv_free_stmt($resultado);

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}

function eliminarCertificadosGrabados($conn_sis, $bbddSql, $filtros)
{
    if (!isset($filtros['id'])) {
        return array('error' => 'id vacio', 'ok' => false);
    }

    $consulta = "DELETE FROM [".$bbddSql."].[dbo].[CertificadosGrabados] WHERE id = ?";
    $params = array($filtros['id']);

    $resultado = sqlsrv_query($conn_sis, $consulta, $params);

    if ($resultado === false) {
        return array('error' => print_r(sqlsrv_errors(), true), 'ok' => false, 'sql' => $consulta, 'params' => $params);
    }

    return array('error' => '', 'ok' => true, 'sql' => $consulta, 'params' => $params);
}

function cargarFacturacionDetallesClayma($conn_sis, $bbddSql, $campos, $filtros, $filtrosOperadores, $group, $order)
{
    $camposPermitidos = array(
        'id' => 't1.id',
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'unidades' => 't1.unidades',
        'unidadesSumatorio' => 'sum(t1.unidades) as unidades',
        'precio' => 't1.precio',
        'total' => 't1.total',
        'totalSumatorio' => 'sum(t1.total) as total',
        'exentoIVA' => 't1.exentoIVA',
        'tipoIva' => 't1.tipoIva',
        'numeroFacturaCompleto' => 't1.numeroFacturaCompleto',
        'presupuestoDistinct' => 'distinct(t1.presupuesto) as presupuesto',
        'campana' => 't1.campana',
        'ordenTipo' => 't1.ordenTipo',
        'orden' => 't1.orden'
    );

    if (!is_array($campos) || empty($campos)) {
        return array('error' => "campos vacios");
    }

    $camposSQL = array();
    foreach ($campos as $campo) {
        if (isset($camposPermitidos[$campo])) {
            $camposSQL[] = $camposPermitidos[$campo];
        }
    }

    if (empty($camposSQL)) {
        return array('error' => "campos SQL vacios");
    }

    $listaCampos = implode(', ', $camposSQL);

    // ---------- FILTROS ----------
    $condicion = array();
    $params = array();

    if (isset($filtros['numeroFacturaCompleto'])) {
        $condicion[] = 't1.numeroFacturaCompleto = ?';
        $params[] = $filtros['numeroFacturaCompleto'];
    }
    if (isset($filtros['presupuesto'])) {
        $condicion[] = 't1.presupuesto = ?';
        $params[] = $filtros['presupuesto'];
    }

    $operadoresPermitidos = array('=', '>', '<', '>=', '<=', '!=');
    $camposComparablesPermitidos = array();

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

    $sqlWhere = '';
    if (!empty($condicion)) {
        $sqlWhere = ' WHERE ' . implode(' AND ', $condicion);
    }

    // ---------- GROUP BY ----------
    $camposGroupPermitidos = array(
        'concepto' => 't1.concepto',
        'descripcion' => 't1.descripcion',
        'precio' => 't1.precio',
        'exentoIVA' => 't1.exentoIVA',
        'tipoIva' => 't1.tipoIva'
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
        'concepto' => 't1.concepto',
        'presupuesto' => 't1.presupuesto',
        'ordenTipo' => 't1.ordenTipo',
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
        FROM [".$bbddSql."].[dbo].[facturacionDetallesClayma] AS t1       
        $sqlWhere
        $sqlGroup
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

    return array('error' => '', 'datos' => $result, 'sql' => $consulta, 'params' => $params);
}


?>
