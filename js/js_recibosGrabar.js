var peticionUnica1 = null;
var permisosSoloLectura = null;

var busquedaFiltros = {};
var busquedaFiltrosLike = [];
var busquedaFiltrosOperadores = [];
var busquedaOrder = [];


function buscarFactura()
{
	busquedaFiltros = {};
	busquedaFiltrosLike = [];
	busquedaFiltrosOperadores = [];

	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;

	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;

	if ((campoAbuscar=="numeroRecibo" || campoAbuscar=="idCliente") && textoAbuscar!="")
	{
		busquedaFiltros[campoAbuscar] = textoAbuscar;
	}
	else if (textoAbuscar!="")
	{
		busquedaFiltrosLike.push({campo: campoAbuscar, valor: textoAbuscar});
	}

	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		busquedaFiltrosOperadores.push({campo1: 'fecha', valor: fechaInicio, operador: '>='});
	}

	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		var aux = new Date(fechaFin);
		aux.setDate(aux.getDate() + 1);
		var fechaFinSiguiente = aux.getFullYear() + "-" + String(aux.getMonth()+1).padStart(2,'0') + "-" + String(aux.getDate()).padStart(2,'0');

		busquedaFiltrosOperadores.push({campo1: 'fecha', valor: fechaFinSiguiente, operador: '<'});
	}

	busquedaOrder = [{campo: orden, dir: desc ? 'DESC' : 'ASC'}];

	cargarAlbaranes();
}

function cargarAlbaranes() //js_recibosGrabar
{
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarAlbaranes;
		peticionUnica1.open("POST","ajax/cargarAlbaranes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string =consultaCargarAlbaranes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarAlbaranes()
{
	var consulta = "accion=cargarAlbaranes";

	var campos = ['id','numeroRecibo','fecha','nombre','tipo','idCliente','nombre_franqueo','cantidad','importe','descripcion'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(busquedaFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(busquedaFiltrosOperadores));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(busquedaFiltrosLike));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(busquedaOrder));

	return consulta;
}

function mostrarCargarAlbaranes()
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

				if (datos.length>0)
				{
					contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					contenido += '<th align="center">Albarán</th>';
					contenido += '<th>Fecha</th>';
					contenido += '<th>Empleado</th>';
					contenido += '<th>Tipo</th>';
					contenido += '<th>Cliente</th>';
					contenido += '<th>Cantidad</th>';
					contenido += '<th>Importe</th>';
					contenido += '<th>Descripcion</th>';

					if (!permisosSoloLectura)
					{
						contenido += '<th></th>'; //imprimir
						contenido += '<th></th>'; //borrar
					}


					contenido += '</tr>';

				var contador = 0;
				var contraste = "";

				while  (contador<datos.length)
				{
					if (contador%2==0)
					{
						contraste = "";
					}
					else
					{
						contraste = ' class="tablaContenidoColor" ';
					}


					contenido += '<tr ' + contraste + '>';

					contenido += '<td align="center" id="'+datos[contador]["id"]+'_albaran"> '+datos[contador]["numeroRecibo"] + '</td>';

					var fecha1 = datos[contador]["fecha"]["date"].substring(0,19).replace(" ","T");
					var dia = fecha1.substr(8,2);
					var mes = fecha1.substr(5,2);
					var anio = fecha1.substr(0,4);
					var fecha = dia  + "-" + mes + "-" + anio + " " +  datos[contador]["fecha"]["date"].substr(11,8);

					contenido += '<td align="center" id="'+datos[contador]["id"]+'_fecha">' + fecha + '</td>';

					contenido += '<td align="center" id = "'+datos[contador]["id"]+'_empleadoAlba">'+datos[contador]["nombre"]+'</td>';
					contenido += '<td align="center" id = "'+datos[contador]["id"]+'_tipoAlba">'+datos[contador]["tipo"]+'</td>';
					contenido += '<td align="left" id = "'+datos[contador]["id"]+'_clienteAlba">'+datos[contador]["idCliente"]+" - "+datos[contador]["nombre_franqueo"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_cantidad">' + datos[contador]["cantidad"] + '</td>';

					contenido += '<td align="right" id="'+datos[contador]["id"]+'_importe">' + Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2}) + ' €</td>';

					contenido += '<td align="center" id="'+datos[contador]["id"]+'_descripcion">' + datos[contador]["descripcion"] + '</td>';


					if (!permisosSoloLectura)
					{
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_imprimir" src="imagenes/imprimir.png" style="width:15px;"  onclick="imprimirAlbaran('+datos[contador]["id"]+')"></td>';
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarAlbaran('+datos[contador]["id"]+')"></td>';
					}

					contenido += '</tr>';

					contador++;
				}

				}
				document.getElementById("historicoAlbaranes").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}
}




function guardarAlbaran()	//js_recibosGrabar
{
	if (document.getElementById("fechaAlbaran").value == "")
	{
		alert("Insertar la fecha del albaran");
		document.getElementById('fechaAlbaran').focus();
	}
	else if (document.getElementById("listadoNombreFranqueo").value==0)
	{
		alert("Insertar un Cliente");
		document.getElementById('listadoNombreFranqueo').focus();
	}
	else if (document.getElementById("cantidadAlbaran").value == "")
	{
		alert("Insertar la cantidad");
		document.getElementById('cantidadAlbaran').focus();
	}
	else if (document.getElementById("importeAlbaran").value == "")
	{
		alert("Insertar el importe");
		document.getElementById('importeAlbaran').focus();
	}
	else if (document.getElementById("descripcionAlbaran").value == "")
	{
		alert("Insertar la descripcion");
		document.getElementById('descripcionAlbaran').focus();
	}
	else
	{
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{
			peticionUnica1.onreadystatechange = mostrarGuardarAlbaran;
			peticionUnica1.open("POST","ajax/guardarAlbaran.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var query_string = consultaGuardarAlbaran();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGuardarAlbaran()
{
	var consulta = "accion=guardarAlbaran";

	var datos = {
		idEmpleado: document.getElementById("listadoEmpleado").value,
		idTipoAlbaran: document.getElementById("listadoTipoAlbaran").value,
		idCliente: document.getElementById("listadoNombreFranqueo").value,
		fecha: document.getElementById("fechaAlbaran").value,
		cantidad: document.getElementById("cantidadAlbaran").value,
		importe: document.getElementById("importeAlbaran").value,
		descripcion: document.getElementById("descripcionAlbaran").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;
}

function mostrarGuardarAlbaran()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido guardar el albarán");
			}
			else
			{
				alert("Albaran Creado");

				cargarAlbaranes();
				nuevoAlbaran();
			}
			peticionUnica1=null;
		}
	}
}



function nuevoAlbaran() // js_recibosGrabar
{
	document.getElementById("numAlbaran").value = "";
	document.getElementById("listadoTipoAlbaran").value = 1;
	document.getElementById("listadoNombreFranqueo").value = 1;
	document.getElementById("fechaAlbaran").value = "";
	document.getElementById("cantidadAlbaran").value = "";
	document.getElementById("importeAlbaran").value = "";
	document.getElementById("descripcionAlbaran").value = "";
}


function cargarListadoEmpleado() //cargarListadoEmpleado de js_global.js esta comentada; version local
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarListadoEmpleado;
		peticionUnica1.open("POST","ajax/cargarListadoEmpleado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoEmpleado();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoEmpleado()
{
	var consulta = "accion=cargarListadoEmpleado";

	var campos = ['id','nombre','apellidos'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{ campo: 'nombre', dir: 'ASC' }, { campo: 'apellidos', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}

function mostrarCargarListadoEmpleado()
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

				var contador=0;
				var contenido="";
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombre"]+ ' ' + datos[contador]["apellidos"] + '</option>';
					contador++;
				}
				document.getElementById("listadoEmpleado").innerHTML = contenido;

			}
			peticionUnica1=null;
		}
	}
}


function cargarListadoNombreFranqueo() //cargarClientes de js_global.js esta comentada; version local
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarListadoNombreFranqueo;
		peticionUnica1.open("POST", "ajax/cargarClientes.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoNombreFranqueo();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoNombreFranqueo()
{
	var consulta = "accion=cargarClientes";
	var campos = ['codigo','nombre_franqueo'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	var filtros = {activo: 1};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	var order = [{ campo: 'nombre_franqueo', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	return consulta;
}

function mostrarCargarListadoNombreFranqueo()
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
					contenido += '<option value="'+datos[contador]["codigo"]+'">'+datos[contador]["nombre_franqueo"]+'</option>';
					contador++;
				}

				document.getElementById("listadoNombreFranqueo").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}
}


function cargarListadoTipoAlabaran()	//js_recibosGrabar
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarListadoTipoAlabaran;
		peticionUnica1.open("POST","ajax/cargarAlbaranTipo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoTipoAlabaran();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoTipoAlabaran()
{
	var consulta = "accion=cargarAlbaranTipo";

	var campos = ['id','tipo'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{ campo: 'tipo', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}

function mostrarCargarListadoTipoAlabaran()
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

				var contador=0;
				var contenido="";
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tipo"]+ '</option>';
					contador++;
				}
				document.getElementById("listadoTipoAlbaran").innerHTML = contenido;

			}
			peticionUnica1=null;
		}
	}
}

function imprimirAlbaran(id) //js_recibosGrabar
{

	document.getElementById("imprimirAlbaran_numero").value = document.getElementById(id+"_albaran").innerHTML;
	document.getElementById("imprimirAlbaran_empleado").value = document.getElementById(id+"_empleadoAlba").innerHTML;
	document.getElementById("imprimirAlbaran_tipo").value = document.getElementById(id+"_tipoAlba").innerHTML;

	var idcliente = document.getElementById(id+"_clienteAlba").innerHTML.split(" - ");

	document.getElementById("imprimirAlbaran_cliente").value = idcliente[0];

	document.getElementById("imprimirAlbaran_fecha").value = document.getElementById(id+"_fecha").innerHTML;
	document.getElementById("imprimirAlbaran_cantidad").value = document.getElementById(id+"_cantidad").innerHTML;
	document.getElementById("imprimirAlbaran_importe").value = document.getElementById(id+"_importe").innerHTML;
	document.getElementById("imprimirAlbaran_descripcion").value = document.getElementById(id+"_descripcion").innerHTML;

	document.getElementById("formImprimirAlbaran").submit();
}


function eliminarAlbaran(id)	// js_recibosGrabar
{
	if (confirm('¿Eliminar el albarán: '+ document.getElementById(id + "_albaran").innerHTML+'?'))
	{
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{
			peticionUnica1.onreadystatechange = mostrarEliminarAlbaran;
			peticionUnica1.open("POST","ajax/eliminarAlbaran.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var query_string =consultaEliminarAlbaran(id);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaEliminarAlbaran(id)
{
	var consulta = "accion=eliminarAlbaran";
	var filtros = { id: id };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	return consulta;
}

function mostrarEliminarAlbaran()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido eliminar el albarán");
			}
			else
			{
				cargarAlbaranes();
			}
			peticionUnica1=null;
		}
	}
}
