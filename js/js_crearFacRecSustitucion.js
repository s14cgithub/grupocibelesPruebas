var peticionUnica1 = null;

var clienteInicial = null;
var anioSeleccionado="";
var busquedaPantallaAnterior="";
var unError = "";
var facCompleto = "";

function cargarDetallesPrefactura() //alias: mostrarModificarDetallePreFactura/mostrarEliminarDetallePreFactura (js_global.js) llaman a este nombre
{
	cargarDetallesRegistrosFacRec();
}

function cargarClientes() //cargarClientes/cargarClientesClayma de js_global.js estan comentadas; version local
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientes;
		if (document.getElementById("clayma").innerHTML=="true")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarClientes()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['codigo_saldo','nombre_empresa'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { activo: 1 };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var filtrosOperadores = [{ campo1: 'codigo_saldo', campo2: 'codigo', operador: '=' }];
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{ campo: 'nombre_empresa', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarClientes()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var datos = res.datos;
				var contenido = "";
				var contador = 0;

				while (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+' - '+datos[contador]["codigo_saldo"]+'</option>';
					contador++;
				}

				document.getElementById("clientes").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}						
}

function borrarTemporalDetallesRec()
{
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarBorrarTemporalRec;
		peticionUnica0.open("POST","ajax/eliminarFacturasDetallesTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBorrarTemporalRec();
		peticionUnica0.send(query_string);
	}
}

function consultaBorrarTemporalRec()
{
	var consulta = "accion=eliminarFacturasDetallesTemporal";

	var filtros = { idUsuario: true };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarBorrarTemporalRec()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			
			peticionUnica0=null;
		}
	}						
}



function copiarDetallesATemporalDetallesRec()
{
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCopiarDetallesATemporalDetallesRec;
		peticionUnica0.open("POST","ajax/copiarFacturacionDetallesATemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string =consultaCopiarDetallesATemporalDetallesRec();
		peticionUnica0.send(query_string);
	}
}

function consultaCopiarDetallesATemporalDetallesRec()
{
	var consulta = "accion=copiarFacturacionDetallesATemporal";
	consulta += "&facturaOriginal=" + document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&clayma=" + document.getElementById("clayma").innerHTML;
	return consulta;	
}

function mostrarCopiarDetallesATemporalDetallesRec()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

			var huboError = res.some(function(fila) { return !fila.ok; });

			if (huboError)
			{
				alert("Error al copiar el detalle de la factura original");
			}
			
			peticionUnica0=null;
		}
	}						
}

function cargarDetallesRegistrosFacRec() 
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarDetallesRegistrosFacRec;
		peticionUnica0.open("POST","ajax/mostrarFacturasDetallesTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesRegistrosFacRec();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarDetallesRegistrosFacRec()
{
	var consulta = "accion=mostrarFacturasDetallesTemporal";

	var campos = ['id','concepto','descripcion','notaCibeles','tipoIva','unidades','precio','total'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { facturaOriginal: document.getElementById("numeroFacturaCompleto").innerHTML, idUsuario: true };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarCargarDetallesRegistrosFacRec()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{				 
				var datos = res.datos;
				unArray = [];
				var contador=0;
				var contenido = "";
				
				while  (contador<datos.length) 
				{					
					contenido += '<tr><td>Concepto:</td><td colspan="5"> <input type="text" id="'+datos[contador]["id"]+'_procesoTemp" value="'+datos[contador]["concepto"]+'" style="width:100%"></input></td>';					
					
					contenido += '<td ROWSPAN="2" align="center"><input type="image" id="'+datos[contador]["id"]+'_modificarDetalleTemp" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarDetallePreFactura('+datos[contador]["id"]+')" ></td></tr>';
					
					contenido += '<tr><td>Descripcion:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_descripcionDetalleTemp" value="'+datos[contador]["descripcion"]+'" style="width:100%"></input></td></tr>';
						
					contenido += '<tr><td>Nota Cibeles:</td><td colspan="4"><input type="text" id="'+datos[contador]["id"]+'_notaDetalleTemp" value="'+ (datos[contador]["notaCibeles"]==null ? '': datos[contador]["notaCibeles"]) +'" style="width:100%"></input></td>';
					
					contenido += '<td style="text-align:center;">Tipo IVA: <select id="'+datos[contador]["id"]+'_tipoIvaDetalleTemp">'+construirOpcionesTipoIva(datos[contador]["tipoIva"])+'></td>';

					contenido += '<td ROWSPAN="2" align="center"><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalleTemp" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarDetallePreFactura('+datos[contador]["id"]+')" ></td></tr>';

					contenido += '<tr>'; 
					
					var valor = datos[contador]["unidades"];
					if (datos[contador]["unidades"].startsWith('.'))
					{
						valor = "0" + datos[contador]["unidades"];
					}
					
					contenido += '<td>Unidad:</td><td><input type="number" id="'+datos[contador]["id"]+'_unidadesDetalleTemp" value="'+valor+'" onkeyup="calcularTotal('+datos[contador]["id"]+')"></input></td>';
										
					valor = datos[contador]["precio"];
					if (datos[contador]["precio"].startsWith('.'))
					{
						valor = "0" + datos[contador]["precio"];
					}					
					
					contenido += '<td>Precio:</td><td><input type="number" id="'+datos[contador]["id"]+'_precioDetalleTemp" value="'+valor+'" onkeyup="calcularTotal('+datos[contador]["id"]+')"></input></td>';
					
					valor = datos[contador]["total"];
					if (datos[contador]["total"].startsWith('.'))
					{
						valor = "0" + datos[contador]["total"];
					}					
					
					
					contenido += '<td>Total:</td><td><input type="number" id="'+datos[contador]["id"]+'_totalDetalleTemp" value="'+valor+'" readonly></input></td>';
					
					contenido += '<tr><td colspan="7" style="border:0px;"><hr></td></tr>';

					contenido += '</tr>';
					
					unArray.push(datos[contador]["id"]);
					
					contador++;
				}				
				
				document.getElementById("detallesPrefactura").innerHTML=contenido;				
				
				calcularTotalTodoPreFactura();				
			}
			
			peticionUnica0=null;
		}
	}						
}

function anadirDetallePrefactura() //js_crearFacRecSustitucion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePrefactura;
		peticionUnica1.open("POST","ajax/insertarFacturasDetallesTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePrefactura()
{	
	var consulta = "accion=insertarFacturasDetallesTemporal";

	var unidad = document.getElementById("unidadesDetalleNuevoTemp").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}

	var precio = document.getElementById("precioDetalleNuevoTemp").value;	
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}

	var datos = {
		facturaOriginal: document.getElementById("numeroFacturaCompleto").innerHTML,
		presupuesto: "",
		concepto: document.getElementById("conceptoNuevoTemp").value,
		descripcion: reemplazarSimbolos(document.getElementById("descripcionDetalleNuevoTemp").value),
		notaCibeles: reemplazarSimbolos(document.getElementById("notaDetalleNuevoTemp").value),
		unidades: unidad,
		precio: precio,
		total: Math.round(parseFloat(precio)*parseFloat(unidad)*100)/100,
		ordenTipo: 1000,
		orden: 1000,
		tipoIva: document.getElementById("tipoIvaNuevoTemp").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarAnadirDetallePrefactura()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				cargarDetallesRegistrosFacRec();
				
				document.getElementById("conceptoNuevoTemp").value="";
				document.getElementById("descripcionDetalleNuevoTemp").value="";
				document.getElementById("notaDetalleNuevoTemp").value="";
				document.getElementById("tipoIvaNuevoTemp").value=21;
				
			}
			peticionUnica1=null;
		}
	}						
}


function comprobarIvaCliente(id,clayma) //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarIvaCliente;
		peticionUnica1.open("POST","ajax/verIvaCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarIvaCliente(id,clayma);
		peticionUnica1.send(query_string);
	}
}
function consultaComprobarIvaCliente(id,clayma)
{	
	var consulta = "accion=verSiTieneFirma";	
	consulta+="&idCliente="+id;
	consulta+="&clayma="+clayma;
	
	return consulta;	
}

function mostrarComprobarIvaCliente()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else
			{	
				var datos = new Array;
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
				if (datos != "")
				{
					if (datos[0]["sinIva"]==1)
					{
						document.getElementById("sinIvaCliente").value="sinIva";
					}
					else
					{
						document.getElementById("sinIvaCliente").value="conIva";
					}

					if (datos[0]["retencion"]==1)
					{
						document.getElementById("conIRPFCliente").value="conIRPF";
					}
					else
					{
						document.getElementById("conIRPFCliente").value="sinIRPF";
					}
				}				
			}
			peticionUnica1=null;
			calcularTotalTodoPreFactura();
		}
	}						
}

function gestionClientes()
{
	cargarClientes();

	cargarPresupuestoYprovision()
}

function cargarPresupuestoYprovision()
{
	cargarPresupuestos();
	verProvisionTotalPresupuesto();

	comprobarIvaCliente(document.getElementById("clientes").value,document.getElementById("clayma").innerHTML);
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function previsualizarFactura() //js_crearFacRecSustitucion
{
	if (document.getElementById("motivo").value.trim()=="")
	{
		alert("Indicar un Motivo");
		document.getElementById("motivo").focus();
		return;
	}
	if (unArray.length==0)
	{
		alert("Añadir al menos un Concepto");
		return;
	}

	document.getElementById("previsualizar_facRecMotivo").value = document.getElementById("motivo").value;
	document.getElementById("previsualizar_idCliente").value = document.getElementById("clientes").value;	
	document.getElementById("previsualizar_facRecfacOriginal").value = document.getElementById("numeroFacturaCompleto").innerHTML;	
	
	
	document.getElementById("previsualizar_fecha").value = document.getElementById("fechaFactura").value;
	document.getElementById("previsualizar_pedido").value = document.getElementById("pedidoCliente").value;
	
	var valor = 0;
	if (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null)
	{
		valor = 0;
	}
	else
	{
		valor = document.getElementById("cantidad").value;
	}
	
	document.getElementById("previsualizar_cantidad").value = valor;

	let select = document.getElementById("formaPago");
	let textoSeleccionado = select.options[select.selectedIndex].text;

	document.getElementById("previsualizar_formaPago").value = textoSeleccionado;
	
	document.getElementById("previsualizar_nuestraCuenta").value = document.getElementById("numCuenta").innerHTML;
	
	document.getElementById("previsualizar_campana").value = document.getElementById("campana").value;
	
	valor = 0;
	if (document.getElementById("detallada").checked)
	{
		valor = 1;
	}
	
	let valorPrespuesto = "";
	if (document.getElementById("presupuestoCampo").value!=0)
		valorPrespuesto=document.getElementById("presupuestoCampo").value;
	document.getElementById("previsualizar_presupuesto").value = valorPrespuesto;
	document.getElementById("previsualizar_detallada").value = valor;
	document.getElementById("previsualizar_neto").value = document.getElementById("Neto").value;
	document.getElementById("previsualizar_iva").value = document.getElementById("iva").value;
	document.getElementById("previsualizar_irpf").value = document.getElementById("irpf").value;
	document.getElementById("previsualizar_total").value = document.getElementById("total").value;
	document.getElementById("previsualizar_provision").value = document.getElementById("provisionTotal").value;
	document.getElementById("previsualizar_aPagar").value = document.getElementById("aPagar").value;
	document.getElementById("previsualizar_clayma").value = document.getElementById("clayma").innerHTML=="true" ? 1 : 0;
				
	document.getElementById("formPrevisualizarFactura").submit();		
}

function grabarFacturaRecSustitucion() 
{	
	
	if (document.getElementById("motivo").value.trim()=="")
	{
		alert("Indicar un Motivo");
		document.getElementById("motivo").focus();
	}
	else if (unArray.length==0)
	{
		alert("Añadir al menos un Concepto");
	}
	else if (!seguirSiNoEsPrimeraFacturaDelMesSinConfirmar(document.getElementById("clayma").innerHTML=="true" ? 1 : 0))
	{
		//el usuario ha cancelado tras el aviso de primera factura del mes
	}
	else
	{
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarFacturaRec;
			peticionUnica1.open("POST","ajax/anadirFactura.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var query_string = consultaGrabarFacturaRec();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGrabarFacturaRec()
{	
	var consulta = "accion=anadirFactura";

	let select = document.getElementById("clientes");
  	let textoSeleccionado = select.options[select.selectedIndex].text;

	let ultimaPos = textoSeleccionado.lastIndexOf("-");
	let soloCliente = textoSeleccionado.substring(0, ultimaPos).trim();

	let selectFormaPago = document.getElementById("formaPago");
	let formaPagoTexto = selectFormaPago.options[selectFormaPago.selectedIndex].text;

	let valorPrespuesto = "";
	if (document.getElementById("presupuestoCampo").value!=0)
		valorPrespuesto=document.getElementById("presupuestoCampo").value;

	var datos = {
		presupuesto: valorPrespuesto,
		descripcion: document.getElementById("campana").value,
		cliente: soloCliente,
		idCodigoCliente: document.getElementById("clientes").value,
		clayma: document.getElementById("clayma").innerHTML=="true" ? 1 : 0,
		pedido: document.getElementById("pedidoCliente").value,
		cantidad: (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null) ? 0 : document.getElementById("cantidad").value,
		formaPago: formaPagoTexto,
		numCuentaBanco: document.getElementById("numCuenta").innerHTML.trim(),
		detallada: document.getElementById("detallada").checked ? 1 : 0,
		precioNeto: document.getElementById("Neto").value,
		iva: document.getElementById("iva").value,
		irpf: document.getElementById("irpf").value,
		precioTotal: document.getElementById("total").value,
		provision: document.getElementById("provisionTotal").value,
		aPagar: document.getElementById("aPagar").value,
		prefactura: 0,
		serieFactura: 'SUST',
		motivo: document.getElementById("motivo").value,
		origenFactura: document.getElementById("numeroFacturaCompleto").innerHTML
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarGrabarFacturaRec()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{	
				var numeroFacturaRec = res.numeroFacturaCompleto;
				facCompleto = numeroFacturaRec;
				grabarFacturaDetalleRec(numeroFacturaRec);


				if (document.getElementById("provisionTotal").value!=0)
				{				
					modificarProvisionNumFacturaPorPresupuesto();
				}

				anularProvisionFondoAplicadaAFactura();
				duplicarFacturaANegativa();

				if (unError == "Error En Verifactu")
				{
					unError="";
					alert("ERROR EN VERIFACTU\nSi se ha generado la factura, NO ENVIARSELO AL CLIENTE\nRevisar lo que ha pasado");
				}
				else
				{
					if (document.getElementById("clayma").innerHTML=="true")
					{
						irAImprimirFacturaClayma(numeroFacturaRec);
					}
					else
					{
						irAImprimirFactura(numeroFacturaRec);
					}
				}

				history.back(-1);
			}
			peticionUnica1=null;
		}
	}						
}

function grabarFacturaDetalleRec(numeroFacturaRec) 
{	
	var clayma = document.getElementById("clayma").innerHTML=="true";
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGrabarFacturaDetalleRec;
		peticionUnica1.open("POST", clayma ? "ajax/insertarFacturacionDetalleClaymaDesdeTemporal.php" : "ajax/insertarFacturacionDetalleDesdeTemporal.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGrabarFacturaDetalleRec(numeroFacturaRec);
		peticionUnica1.send(query_string);
	}
}

function consultaGrabarFacturaDetalleRec(numeroFacturaRec)
{	
	var consulta = "accion=insertarFacturacionDetalles";

	consulta += "&facturaOriginal="+document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&numeroFacturaCompleto="+encodeURIComponent(numeroFacturaRec);
	consulta += "&campana="+encodeURIComponent(document.getElementById("campana").value.trim());

	return consulta;	
}

function mostrarGrabarFacturaDetalleRec()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			var huboError = res.some(function(fila) { return !fila.ok; });

			if (huboError)
			{
				alert("Error al grabar el detalle de la factura");
			}

			peticionUnica1=null;
		}
	}						
}


function verProvisionTotalPresupuesto() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerProvisionTotalPresupuesto;
		peticionUnica1.open("POST","ajax/cargarProvisionDeFondos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerProvisionTotalPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaVerProvisionTotalPresupuesto()
{	
	var consulta = "accion=cargarProvisionDeFondos";

	var campos = ['importeTotal'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
		presupuesto: document.getElementById("presupuestoCampo").value,
		cobrada: 2,
		tipo: 3,
		facCompletaAplicada: 1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarVerProvisionTotalPresupuesto()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}			
			else
			{
				var datos = res.datos;
				var importeTotal = parseFloat(datos[0]["importeTotal"]);

				document.getElementById("provisionTotal").value = importeTotal.toFixed(2);
			}

			calcularTotalTodoPreFactura();
			peticionUnica1=null;
		}
	}						
}


function cargarPresupuestos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarPresupuestos;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPresupuestos();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarPresupuestos()
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['presupuesto'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
		codigoCliente: document.getElementById("clientes").value,
		clayma: document.getElementById("clayma").innerHTML=="true" ? 1 : 0
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var order = [{ campo: 'presupuesto', dir: 'DESC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}


function mostrarCargarPresupuestos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;
				var contenido = "";

				contenido += '<option value="0">Ninguno</option>';
				var contador = 0;
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["presupuesto"]+'">'+datos[contador]["presupuesto"]+'</option>';
					contador++;
				}							
				document.getElementById("presupuestoCampo").innerHTML = contenido;	
				document.getElementById("presupuestoCampo").value = document.getElementById("presupuesto").innerHTML;	
				if (document.getElementById("presupuestoCampo").value=="")
					document.getElementById("presupuestoCampo").value=0;	
			}
			peticionUnica1=null;
		}
	}						
}

function modificarProvisionNumFacturaPorPresupuesto() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarProvisionNumFacturaPorPresupuesto;
		peticionUnica1.open("POST","ajax/modificarProvisionFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarProvisionNumFacturaPorPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarProvisionNumFacturaPorPresupuesto()
{	
	var consulta = "accion=modificarProvisionFondo";
	consulta += "&pantallaOrigen=prefactura";

	var datos = {
		facCompletaAplicada: facCompleto
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
		presupuesto: document.getElementById("presupuestoCampo").value,
		cobrada: 2,
		tipo: 3,
		sinFacturaAplicada: 1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarModificarProvisionNumFacturaPorPresupuesto()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}

			peticionUnica1 = null;
		}
	}						
}

function anularProvisionFondoAplicadaAFactura()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnularProvisionFondoAplicadaAFactura;
		peticionUnica1.open("POST","ajax/modificarProvisionFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnularProvisionFondoAplicadaAFactura();
		peticionUnica1.send(query_string);
	}
}

function consultaAnularProvisionFondoAplicadaAFactura()
{	
	var consulta = "accion=modificarProvisionFondo";
	consulta += "&pantallaOrigen=prefactura";

	var datos = {
		facCompletaAplicada: null
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
		facCompletaAplicada: document.getElementById("numeroFacturaCompleto").innerHTML
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarAnularProvisionFondoAplicadaAFactura()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}

			peticionUnica1 = null;
		}
	}						
}

function duplicarFacturaANegativa()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarDuplicarFacturaANegativa;
		peticionUnica1.open("POST","ajax/duplicarFacturaANegativa.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaDuplicarFacturaANegativa();
		peticionUnica1.send(query_string);
	}
}

function consultaDuplicarFacturaANegativa()
{	
	var consulta = "accion=duplicarFacturaANegativa";
	consulta += "&facturaOriginal=" + document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&clayma=" + document.getElementById("clayma").innerHTML;

	return consulta;	
}

function mostrarDuplicarFacturaANegativa()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}

			peticionUnica1 = null;
		}
	}						
}

