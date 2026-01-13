<?php

/*$rutaRaiz_ArchivosComunes="Archivos Comunes";
$rutaConexionSql = $rutaRaiz_ArchivosComunes."/conexion.php";
$rutaCabecera = $rutaRaiz_ArchivosComunes."/cabecera.php";
$rutaFunciones = $rutaRaiz_ArchivosComunes."/funciones.php";*/




$anchoWeb=100;

define('versionCibeles',"2.02");

define('fechaCambioVerifactu',"2025-11-27");
define('urlCodigoQrVerifactu',"https://prewww2.aeat.es/wlpl/TIKE-CONT/ValidarQR?");



define('cifCibeles',"A81339186");
define('cifClayma',"A80499221");

define('estadoAbierto', 'abierto');
define('estadoCerrado', 'cerrado');
define('modoAutomatico', 'automatico');
define('modoManual', 'manual');
define('modoPDA', 'pda');

define('log_creacion','Creacion');
define('log_modificacion','Modificacion');
define('log_eliminacion','Eliminacion');


//RUTAS
define('rutaPresupuestos','//172.26.0.44/d/Comercial/Privado/Presupuestos/General/');
define('rutaProduccionAdjuntas','imagenesAdjuntas');


//SIMBOLOS
define('signo_tantoPorciento',chr(37));
define('lineaVertical',chr(124));
define('EURO',chr(128));


define('puntoVineta',chr(149));

define('ene',chr(241));
define('a_acento',chr(225));
define('e_acento',chr(233));
define('i_acento',chr(237));
define('o_acento',chr(243));
define('u_acento',chr(250));

define('ene_may',chr(209));
define('a_acento_may',chr(193));
define('e_acento_may',chr(201));
define('i_acento_may',chr(205));
define('o_acento_may',chr(211));
define('u_acento_may',chr(218));


define('acentoGrave',chr(96));
define('exclamacionAbierta',chr(161));
define('diaeresis',chr(168));
define('sinSigno',chr(172));
define('signo_grado',chr(176));
define('signo_ordinal',chr(170));
define('acento',chr(180));
define('puntoMedio',chr(183));
define('interrogacionAbierta',chr(191));
define('CcedillaMayuscula',chr(199));
define('CcedillaMinuscula',chr(231));
define('superindice2',chr(178));













define('signo_tresPuntos','...');





//TABLAS


define('tabla_registroHora','registroHoras');
define('registroHora_columnaId','id');
define('registroHora_columnaIdEmpleado','idEmpleado');
define('registroHora_columnaNombreEmpleado','nombreEmpleado');
define('registroHora_columnaCodigoBarras','codigoBarras');
define('registroHora_columnaHoraInicio','horaInicio');
define('registroHora_columnaHoraFin','horaFin');
define('registroHora_columnaCantidad','cantidad');
define('registroHora_columnaObservaciones','observaciones');
define('registroHora_columnaEstado','estado');
define('registroHora_columnaModo','modo');
define('registroHora_columnaIdImpresoras','idImpresoras');
define('registroHora_columnaIdPapelTamano','idPapelTamano');
define('registroHora_columnaIdPapelTipo','idPapelTipo');
define('registroHora_columnaIdPapelAcabado','idPapelAcabado');
define('registroHora_columnaIdPapelGramaje','idPapelGramaje');
define('registroHora_columnaIdPapelOrigen','idPapelOrigen');
define('registroHora_columnasinProceso_idConcepto','sinProceso_idConcepto');
define('registroHora_columnasinProceso_idCliente','sinProceso_idCliente');
define('registroHora_columnaNumerosCaras','impresionNumeroCaras');
define('registroHora_columnaIdGFSubconjunto2','idGFSubconjunto2');

define('registroEmpleado_columnaId','id');
define('registroEmpleado_columnaNombre','nombre');
define('registroEmpleado_columnaApellido','apellidos');



define('presupuesto_tabla','presupuestos');
define('presupuesto_ColumnaIdComercial','idComercial');
define('presupuesto_ColumnaDetallada','detallada');
define('presupuesto_ColumnaCliente','cliente');
define('presupuesto_ColumnaCampana','campana');
define('presupuesto_ColumnaCampana2','campana2');
define('presupuesto_ColumnaCantidad','cantidad');
define('presupuesto_ColumnaCantidad2','cantidad2');
define('presupuesto_ColumnaPedCliente','pedcli');
define('presupuesto_ColumnaDireccion','direccion');
define('presupuesto_ColumnaCP','cp');
define('presupuesto_ColumnaPoblacion','poblacion');
define('presupuesto_ColumnaFormaPago','idFormaPago');
define('presupuesto_ColumnaPersona','persona');
define('presupuesto_ColumnaMostrarTotalPresu','idVisualizarTotalPresu');
define('presupuesto_ColumnaMostrarTotalFranqueo','idVisualizarTotalFranqueo');
define('presupuesto_ColumnaImportelFranqueo','importeFranqueo');
define('presupuesto_formaPago_tabla','formaDePago');
define('presupuesto_otBajada','otBajada');
define('presupuesto_otAbierta','otAbierta');
define('presupuesto_fechaAceptacion','fechaAceptacion');
define('presupuesto_fechaCompromiso','fechaCompromiso');
define('presupuesto_fechaTerminado','fechaTerminado');
define('presupuesto_fechaInicioReal','fechaInicioReal');
define('presupuesto_Presupuesto','presupuesto');
define('presupuesto_Letra','letra');
define('presupuesto_notaCibeles','notaCibeles');
define('presupuesto_pdfGenerado','pdfGenerado');
define('presupuesto_numeroNoFactura','numNoFactura');
define('presupuesto_trabajoIniciado','trabajoIniciado');


define('presupuestoDetalle_tabla','presupuestos detalle');
define('presupuestoDetalle_ColumnaConcepto','idConcepto');
define('presupuestoDetalle_ColumnaDescripcion','descripcion');
define('presupuestoDetalle_NotaCibeles','notaCibeles');
define('presupuestoDetalle_Unidad','unidades');
define('presupuestoDetalle_Unidad2','unidades2');
define('presupuestoDetalle_Precio','precio');
define('presupuestoDetalle_Orden','orden');
define('presupuestoDetalle_Id','id');
define('presupuestoDetalle_Presupuesto','presupuesto');
define('presupuestoDetalle_idTipo','idTipo');
define('presupuestoDetalle_NotaCibelesAdmonProd','notaAdmonProd');
define('presupuestoDetalle_exentoIVA','exentoIVA');
define('presupuestoDetalle_pesoGramos','pesoGramos');

define('provisionDeFondo_tabla','provisionesDeFondo');
define('provisionDeFondo_presupuesto','presupuesto');
define('provisionDeFondo_importe','importe');
define('provisionDeFondo_fechaCreacion','fechaCreacion');
define('provisionDeFondo_tipo','tipo');
define('provisionDeFondo_cobrada','cobrada');
define('provisionDeFondo_fechaCobro','fechaCobro');
define('provisionDeFondo_formaPago','formaPago');

define('provisionDeFondoMovimientos_tabla','provisionDeFondo_movimientos');


define('clientes_tabla','clientes');
define('clientes_codigoSubcliente','codigo');
define('clientes_fechaCobro','fechaCobroPF');
define('clientes_importePF','importePF');


define('clientesContactos_tabla','clientesContactos');
define('clientesDireccionesRuta_tabla','clientesDirecRutas');


define('preFactura_tabla','facturasDetallesTemporal');
define('preFacturaDetalle_Presupuesto','presupuesto');
define('preFacturaDetalle_Concepto','concepto');
define('preFacturaDetalle_ColumnaDescripcion','descripcion');
define('preFacturaDetalle_NotaCibeles','notaCibeles');
define('preFacturaDetalle_Unidad','unidades');
define('preFacturaDetalle_Precio','precio');


define('abono_tabla','abono');
define('abono_formaDepago','formaPagoReal');
define('abono_fechaDepago','fechaPago');

define('facturas_tabla','facturas');
define('facturas_formaDepago','formaPagoReal');
define('facturas_fechaDepago','fechaPago');

define('facturasDetalles_tabla','facturasDetalles');

define('facturaDetalle_factura','factura');
define('facturaDetalle_Concepto','concepto');
define('facturaDetalle_ColumnaDescripcion','descripcion');
define('facturaDetalle_NotaCibeles','notaCibeles');
define('facturaDetalle_Unidad','unidades');
define('facturaDetalle_Precio','precio');


define('facturasRec_tabla','facturasRectificativas');
define('facturasRecSustitutiva_tabla','facturasRectificativas');



define('facturasCorreos_tabla','facturasCorreos');
define('facturasCorreos_numeroOficial','numeroOficial');
define('facturasCorreos_fecha','fecha');
define('facturasCorreos_codigoCliente','codigoCliente');
define('facturasCorreos_campana','campana');
define('facturasCorreos_neto','neto');
define('facturasCorreos_iva','iva');
define('facturasCorreos_importe','importe');
define('facturasCorreos_anticipo','anticipo');
define('facturasCorreos_aPagar','aPagar');
define('facturasCorreos_formaPago','formaPago');


define('rutasPlantilla_id','id');
define('rutasPlantilla_idCliente','idCliente');
define('rutasPlantilla_lunesRuta','lunesRuta');
define('rutasPlantilla_lunesHora','lunesHora');
define('rutasPlantilla_martesRuta','martesRuta');
define('rutasPlantilla_martesHora','martesHora');
define('rutasPlantilla_miercolesRuta','miercolesRuta');
define('rutasPlantilla_miercolesHora','miercolesHora');
define('rutasPlantilla_juevesRuta','juevesRuta');
define('rutasPlantilla_juevesHora','juevesHora');
define('rutasPlantilla_viernesRuta','viernesRuta');
define('rutasPlantilla_viernesHora','viernesHora');
define('rutasPlantilla_incidencia','incidencia');
define('rutasPlantilla_contacto','contacto');

define('empleado_id','id');
define('empleado_nombre','nombre');
define('empleado_apellidos','apellidos');
define('empleado_precioHora','precioHora');
define('empleado_horasLaborales','horasLaborales');

 define('empleado_columnaNombre','nombre');
 define('empleado_columnaPrecioHora','PrecioHora');
 define('empleado_columnaHoraLaboral','horaLaboral');


define('login_idEmpleado','idEmpleado');
define('login_usuario','usuario');

define('comprasATerceros_tabla','compraTerceros');
define('comprasATercerosDetalles_tabla','comprasTercerosDetalles');
define('comprasATercerosDetalles_Descripcion','descripcion');
define('comprasATercerosDetalles_cantidad','cantidad');
define('comprasATercerosDetalles_precioUnidad','precioUnidad');
define('comprasATercerosDetalles_precioVenta','precioVenta');
define('comprasATercerosDetalles_total','total');
define('comprasATercerosDetalles_margen','margen');



//


//COLORES
define('colorAzulR',0);
define('colorAzulG',66);
define('colorAzulB',137);


define('colorNaranjaR',252);
define('colorNaranjaG',255);
define('colorNaranjaB',232);


define('colorBlancoR',255);
define('colorBlancoG',255);
define('colorBlancoB',255);

define('colorNegroR',0);
define('colorNegroG',0);
define('colorNegroB',0);

define('colorRojoR',255);
define('colorRojoG',0);
define('colorRojoB',0);

define('colorVerdeR',0);
define('colorVerdeG',255);
define('colorVerdeB',0);

define('colorContrasteR',230);
define('colorContrasteG',230);
define('colorContrasteB',230);

define('colorPrevisualizacionR',255);
define('colorPrevisualizacionG',192);
define('colorPrevisualizacionB',203);

/*define('colorRojoClaymaR',228);
define('colorRojoClaymaG',32);
define('colorRojoClaymaB',62);*/

define('colorRojoClaymaR',138);
define('colorRojoClaymaG',16);
define('colorRojoClaymaB',4);






?>