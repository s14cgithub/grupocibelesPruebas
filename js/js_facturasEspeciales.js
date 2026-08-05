var peticionUnica1 = null;

function guardarFacturaEspecial() //js_facturasEspeciales
{
	if (document.getElementById("nombreCliente").value==0)
	{
		alert("Introduce un cliente");
		document.getElementById("nombreCliente").focus();
	}
	else if (document.getElementById("ordenTrabajo").value=="")
	{
		alert("Introduce un valor en la orden de trabajo");
		document.getElementById("ordenTrabajo").focus();
	}
	else if (document.getElementById("fecha").value=="")
	{
		alert("Introduce una fecha");
		document.getElementById("fecha").focus();
	}
	else if (document.getElementById("concepto").value=="")
	{
		alert("Introduce un concepto");
		document.getElementById("concepto").focus();
	}
	else if (document.getElementById("unidades").value=="")
	{
		alert("Introduce una  unidad");
		document.getElementById("unidades").focus();
	}
	else if (document.getElementById("importe").value=="")
	{
		alert("Introduce un importe");
		document.getElementById("importe").focus();
	}
	else if (document.getElementById("ordenTrabajo").value.toUpperCase()!="CD" && document.getElementById("ordenTrabajo").value.toUpperCase()!="BUROFAX" && document.getElementById("ordenTrabajo").value.toUpperCase()!="OTROS CONCEPTOS" && document.getElementById("ordenTrabajo").value.toUpperCase()!="FACTURAS" && document.getElementById("ordenTrabajo").value.toUpperCase()!="CROTALES" && document.getElementById("ordenTrabajo").value.toUpperCase()!="RECOGIDAS")
	{
		alert("En Orden de Trabajo se debe poner alguna de las siguientes opciones:\n\n- CD\n- BUROFAX\n- CROTALES\n- RECOGIDAS\n- OTROS CONCEPTOS");
	}
	else
	{
		guardarFacturaEspecial2();
	}
}

function guardarFacturaEspecial2()	//js_facturasEspeciales
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarGuardarFacturaEspecial2;
		peticionUnica1.open("POST","ajax/guardarFacturaEspecial.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaGuardarFacturaEspecial2();
		peticionUnica1.send(query_string);
	}
}

function consultaGuardarFacturaEspecial2()
{
	var consulta = "accion=guardarFacturaEspecial2";

	var datos = {
		idCliente: document.getElementById("nombreCliente").value,
		ordenTrabajo: document.getElementById("ordenTrabajo").value,
		fecha: document.getElementById("fecha").value,
		concepto: document.getElementById("concepto").value,
		unidades: document.getElementById("unidades").value,
		importe: document.getElementById("importe").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;
}


function mostrarGuardarFacturaEspecial2()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido guardar la factura especial");
			}
			else
			{
				cargarFacturasEspeciales();
			}
			peticionUnica1=null;
		}
	}
}

function cargarFacturasEspeciales()	//js_facturasEspeciales
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarFacturasEspeciales;
		peticionUnica1.open("POST","ajax/cargarFacturasEspeciales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarFacturasEspeciales();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarFacturasEspeciales()
{
	var consulta = "accion=cargarFacturasEspeciales";

	var campos = ['id','idCliente','nombre_franqueo','ordenTrabajo','fechaFacturacion','concepto','unidades','precioUnitario'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{ campo: 'id', dir: 'DESC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}


function mostrarCargarFacturasEspeciales()
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

					contenido+='<th align="center"><b>Id</b></th>';
					contenido+='<th align="center"><b>Cliente</b></th>';
					contenido+='<th align="center"><b>Orden Trabajo</b></th>';
					contenido+='<th align="center"><b>Fecha</b></th>';
					contenido+='<th align="center"><b>Concepto</b></th>';
					contenido+='<th align="center">Unidades</th>';
					contenido+='<th align="center">Precio Unitario</th>';
					contenido+='<th align="center"></th>';
					contenido+='<th align="center"></th>';

					contenido+='</tr>';

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

						contenido+='<td id="'+datos[contador]["id"]+'_certificado" value="" style="text-align: center">'+datos[contador]["id"]+'</td>';

						contenido+='<td id="'+datos[contador]["id"]+'_certCliente" value="" style="text-align: center">'+datos[contador]["nombre_franqueo"]+'</td>';

						contenido+='<td value="" style="text-align: center">'+datos[contador]["ordenTrabajo"]+'</td>';

						var fecha1 = datos[contador]["fechaFacturacion"]["date"].substring(0,19).replace(" ","T");
						var dia = fecha1.substr(8,2);
						var mes = fecha1.substr(5,2);
						var anio = fecha1.substr(0,4);


						contenido+='<td value="" style="text-align: center">'+dia  + "-" + mes + "-" + anio+'</td>';

						contenido+='<td><input type="text" id="'+datos[contador]["id"]+'_concepto"  value="'+datos[contador]["concepto"]+'" style="width: 100%;text-align: center"></td>';
						contenido+='<td><input type="number" id="'+datos[contador]["id"]+'_unidades" value="'+datos[contador]["unidades"]+'" style="width: 100%;text-align: center"></td>';
						contenido+='<td><input type="number" id="'+datos[contador]["id"]+'_precio"  value="'+datos[contador]["precioUnitario"]+'" style="width: 100%;text-align: center"></td>';

						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarEspecial" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRegistroEspecial('+datos[contador]["id"]+')"></td>';

						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarEspecial value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroEspecial('+datos[contador]["id"]+')"></td>';


						contenido+='</tr>';

						contador++;

					}

					document.getElementById("historico1").innerHTML = contenido;


				}
				else
				{
					document.getElementById("historico1").innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}
}


function modificarRegistroEspecial(id) //js_facturasEspeciales
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarModificarRegistroEspecial;
		peticionUnica1.open("POST","ajax/modificarFacturasEspeciales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaModificarRegistroEspecial(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarRegistroEspecial(id)
{
	var consulta = "accion=modificarFacturasEspeciales";

	var datos = {
		concepto: document.getElementById(id+"_concepto").value,
		unidades: document.getElementById(id+"_unidades").value,
		precioUnitario: document.getElementById(id+"_precio").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { id: id };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;
}

function mostrarModificarRegistroEspecial()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido modificar el registro");
			}
			else
			{
				alert("Registro Modificado");
				cargarFacturasEspeciales();
			}

			peticionUnica1=null;
		}
	}
}

function eliminarRegistroEspecial(id) //js_facturasEspeciales
{
	if (confirm("¿Eliminar registro: "+id+"?"))
	{
		eliminarRegistroEspecial2(id);
	}
}


function eliminarRegistroEspecial2(id) //js_facturasEspeciales
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroEspecial2;
		peticionUnica1.open("POST","ajax/eliminarFacturasEspeciales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaEliminarRegistroEspecial2(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarRegistroEspecial2(id)
{
	var consulta = "accion=eliminarFacturasEspeciales";
	var filtros = { id: id };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	return consulta;
}

function mostrarEliminarRegistroEspecial2()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido eliminar el registro");
			}
			else
			{
				cargarFacturasEspeciales();
			}

			peticionUnica1=null;
		}
	}
}


function informeGastosAdionales()
{
	if (document.getElementById("fechaInicioModal").value == "")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioModal").focus();
	}
	else if (document.getElementById("fechaFinModal").value == "")
	{
		alert("Introducir una fecha de Fin");
		document.getElementById("fechaFinModal").focus();
	}
	else
	{
		var filtros = { idCliente: document.getElementById("clienteModal").value };
		document.getElementById("imprimirFiltros").value = JSON.stringify(filtros);

		var filtrosOperadores = [
			{campo1: 'fechaFacturacion', valor: document.getElementById("fechaInicioModal").value, operador: '>='},
			{campo1: 'fechaFacturacion', valor: document.getElementById("fechaFinModal").value, operador: '<='}
		];
		document.getElementById("imprimirFiltrosOperadores").value = JSON.stringify(filtrosOperadores);

		var order = [
			{ campo: 'idCliente', dir: 'ASC' },
			{ campo: 'fechaFacturacion', dir: 'ASC' },
			{ campo: 'concepto', dir: 'ASC' }
		];
		document.getElementById("imprimirOrder").value = JSON.stringify(order);

		document.getElementById("formImprimirGastosAdicionales").submit();
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
				var contenido = '<option value=""></option>';

				var contador = 0;
				while (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo"]+'">'+datos[contador]["nombre_franqueo"]+'</option>';
					contador++;
				}

				document.getElementById("nombreCliente").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}
}


function cargarClienteModal() //cargarClientes de js_global.js esta comentada; version local
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarClienteModal;
		peticionUnica1.open("POST", "ajax/cargarClientes.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarClienteModal();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarClienteModal()
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

function mostrarCargarClienteModal()
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
				var contenido = '<option value=""></option>';

				var contador = 0;
				while (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo"]+'">'+datos[contador]["nombre_franqueo"]+'</option>';
					contador++;
				}

				document.getElementById("clienteModal").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}
}
