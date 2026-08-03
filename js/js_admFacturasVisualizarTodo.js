var peticionUnica1 = null;

var busquedaFiltros = {};
var busquedaFiltrosLike = [];
var busquedaFiltrosOperadores = [];
var busquedaOrder = [];

function buscarFactura()
{
	busquedaFiltros = {};
	busquedaFiltrosLike = [];
	busquedaFiltrosOperadores = [];

	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;

	if ((fechaInicio!="" && fechaInicio!=null && fechaInicio != "null") || (fechaFin!="" && fechaFin!=null && fechaFin != "null"))
	{
		if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
		{
			busquedaFiltrosOperadores.push({campo1: 'fecha', valor: fechaInicio, operador: '>='});
		}
		if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
		{
			busquedaFiltrosOperadores.push({campo1: 'fecha', valor: fechaFin, operador: '<='});
		}
	}
	else
	{
		var anio = document.getElementById("anio").value;
		busquedaFiltrosOperadores.push({campo1: 'fecha', valor: anio+'-01-01', operador: '>='});
		busquedaFiltrosOperadores.push({campo1: 'fecha', valor: anio+'-12-31', operador: '<='});
	}

	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;

	if (textoAbuscar!="")
	{
		busquedaFiltrosLike.push({campo: campoAbuscar, valor: textoAbuscar});
	}

	if (document.getElementById("checkCibeles").checked)
	{
		busquedaFiltros.incluirCibeles = 1;
	}
	if (document.getElementById("checkClayma").checked)
	{
		busquedaFiltros.incluirClayma = 1;
	}
	if (document.getElementById("checkCorreos").checked)
	{
		busquedaFiltros.incluirCorreos = 1;
	}

	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	busquedaOrder = [{campo: orden, dir: desc ? 'DESC' : 'ASC'}];

	cargarListadoFacturasVisualizarTodo();
}

function cargarListadoFacturasVisualizarTodo()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarListadoFacturasVisualizarTodo;
		peticionUnica1.open("POST","ajax/cargarFacturasCibelesClaymaCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaListadoFacturasVisualizarTodo();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturasVisualizarTodo()
{
	var consulta = "accion=cargarFacturasCibelesClaymaCorreos";

	var campos = ['origen','origen2','numeroFacturaCompleto','cliente','fecha','fechaPago','formaPagoReal','precioNeto'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(busquedaFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(busquedaFiltrosOperadores));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(busquedaFiltrosLike));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(busquedaOrder));

	return consulta;
}

function origenTexto(fila)
{
	if (fila["origen2"]=="CORREOS")
	{
		return "Correos";
	}
	else if (fila["origen2"]=="CLAYMA")
	{
		return "Clayma";
	}
	else if (fila["origen2"]=="CIBELES")
	{
		return "Cibeles";
	}
	else
	{
		return "";
	}
}

function tipoFactura(fila)
{
	if (fila["origen2"]=="CORREOS")
	{
		return "facturaCorreos";
	}
	else if (fila["origen2"]=="CLAYMA")
	{
		return fila["origen"]=="ABONO" ? "abonoClayma" : "facturaClayma";
	}
	else
	{
		return fila["origen"]=="ABONO" ? "abono" : "factura";
	}
}

function mostrarListadoFacturasVisualizarTodo()
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
				var res = JSON.parse(peticionUnica1.responseText);
				var datos = res.datos ? res.datos : [];

				var contenido = "";

				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=3 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioApagar"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>';


				contenido += '<tr class="centrarTexto tablaCabeceraColor">';

				contenido += '<th>Cliente</th>';
				contenido += '<th>Factura</th>';
				contenido += '<th>Origen</th>';
				contenido += '<th>Fecha</th>';
				contenido += '<th>Pagado</th>';
				contenido += '<th>Neto</th>';
				contenido += '<th></th>';
				contenido += '</tr>';

				var contador = 0;
				while  (contador<datos.length)
				{
					var tipo = tipoFactura(datos[contador]);

					if (tipo=="facturaCorreos")
					{
						contenido += '<tr  style="background-color:#4a69bd">';
					}
					else if (tipo=="facturaClayma")
					{
						contenido += '<tr  style="background-color:#B87240">';
					}
					else if (tipo=="abono")
					{
						contenido += '<tr  style="background-color:#ff3333">';
					}
					else if (tipo=="abonoClayma")
					{
						contenido += '<tr  style="background-color:#6F1E51">';
					}
					else
					{
						contenido += '<tr>';
					}

					contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_cliente">'+datos[contador]["cliente"]+'</td>';
					contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_numero" style="white-space:nowrap;">'+datos[contador]["numeroFacturaCompleto"]+'</td>';

					contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_tipo" style="white-space:nowrap;">'+origenTexto(datos[contador])+'</td>';

					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);

					contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_fecha" style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';

					if (datos[contador]["fechaPago"]!="" && datos[contador]["fechaPago"]!=null && datos[contador]["fechaPago"]!="null")
					{
						dia = datos[contador]["fechaPago"]["date"].substr(8,2);
						mes = datos[contador]["fechaPago"]["date"].substr(5,2);
						anio = datos[contador]["fechaPago"]["date"].substr(0,4);

						if (dia + "-" + mes+ "-" + anio== '01-01-1900')
						{
							contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_fechaPago" class=""></td>';
						}
						else
						{
							contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_fechaPago" >'+dia + "-" + mes+ "-" + anio+' <br> ' + datos[contador]["formaPagoReal"]+'</td>';
						}
					}
					else
					{
						contenido += '<td id="'+datos[contador]["numeroFacturaCompleto"]+'_fechaPago" class=""></td>';
					}

					contenido += '<td align="right" id="'+datos[contador]["numeroFacturaCompleto"]+'_aPagar"  style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["precioNeto"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';

					if (tipo=="factura")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirFactura(\''+datos[contador]["numeroFacturaCompleto"]+'\')"></td>';
					}
					else if (tipo=="facturaClayma")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirFacturaClayma(\''+datos[contador]["numeroFacturaCompleto"]+'\')"></td>';

					}
					else if (tipo=="abono")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirAbono(\''+datos[contador]["numeroFacturaCompleto"]+'\', '+anio+')"></td>';
					}
					else if (tipo=="abonoClayma")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirAbonoClayma(\''+datos[contador]["numeroFacturaCompleto"]+'\', '+anio+')"></td>';
					}
					else
					{
						contenido += '<td></<td>';
					}

					contenido += '</tr>';

					contador++;
				}

				document.getElementById("listadoFacturasSinEmitir").innerHTML = contenido;
			}
			peticionUnica1=null;
			listadoFacturas_Sumatorio();

		}
	}
}


function listadoFacturas_Sumatorio() //js_facturaSinCobrar
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarListadoFacturas_Sumatorio;
		peticionUnica1.open("POST","ajax/cargarFacturasCibelesClaymaCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaListadoFacturas_Sumatorio();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturas_Sumatorio()
{
	var consulta = "accion=cargarFacturasCibelesClaymaCorreos";

	var campos = ['precioNetoSumatorio'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(busquedaFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(busquedaFiltrosOperadores));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(busquedaFiltrosLike));

	return consulta;
}

function mostrarListadoFacturas_Sumatorio()
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
				var res = JSON.parse(peticionUnica1.responseText);
				var datos = res.datos ? res.datos : [];

				var precioNeto = datos.length>0 ? datos[0]["precioNetoSumatorio"] : 0;

				document.getElementById("sumatorioApagar").innerHTML = 'Precio Neto: '+ Number(precioNeto).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';

			}
			peticionUnica1 = null;


		}
	}
}


function gestionExportarExcelFacturaCibeles()
{
	document.getElementById("exportarExcel_Filtros").value = JSON.stringify(busquedaFiltros);
	document.getElementById("exportarExcel_FiltrosLike").value = JSON.stringify(busquedaFiltrosLike);
	document.getElementById("exportarExcel_FiltrosOperadores").value = JSON.stringify(busquedaFiltrosOperadores);
	document.getElementById("exportarExcel_Order").value = JSON.stringify(busquedaOrder);
	document.getElementById("formExportarAgenteComercialExcel").submit();
}



function irAImprimirAbono(numFactura, anioAbono)
{
	document.getElementById("imprimirNumFacturaAbono").value =numFactura;
	document.getElementById("imprimirAnioSeleccionado").value = anioAbono;
	document.getElementById("formImprimirAbono").submit();
}
function irAImprimirAbonoClayma(numFactura, anioAbono)
{
	document.getElementById("imprimirNumFacturaAbonoClayma").value =numFactura;
	document.getElementById("imprimirAnioSeleccionadoClayma").value = anioAbono;
	document.getElementById("formImprimirAbonoClayma").submit();
}
