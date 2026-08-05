var peticionUnica1 = null;

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

				document.getElementById("listadoNombreFranqueo").innerHTML = contenido;
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

function guardarCertificado() //js_certGrabar
{
	if(document.getElementById("fechaFranqueo").value == "")
	{
		alert("Insertar una fecha");
		document.getElementById('fechaFranqueo').focus();
	}
	else if (document.getElementById("listadoNombreFranqueo").value == "")
	{
		alert("Insertar un nombre de franqueo");
		document.getElementById('listadoNombreFranqueo').focus();
	}
	else if (document.getElementById("unidades").value == "")
	{
		alert("Insertar las unidades");
		document.getElementById('unidades').focus();
	}
	else if (document.getElementById("listadoProducto").value == "")
	{
		alert("Insertar un producto");
		document.getElementById('listadoProducto').focus();
	}
	else
	{
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{
			peticionUnica1.onreadystatechange = mostrarGuardarCertificado;
			peticionUnica1.open("POST","ajax/guardarCertificado.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var query_string = consultaGuardarCertificado();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGuardarCertificado()
{
	var consulta = "accion=guardarCertificado";
	var datos = {
		idCliente: document.getElementById("listadoNombreFranqueo").value,
		unidades: document.getElementById("unidades").value,
		idProducto: document.getElementById("listadoProducto").value,
		fecha: document.getElementById("fechaFranqueo").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;
}

function mostrarGuardarCertificado()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido guardar el certificado");
			}
			else
			{
				cargarCertificados();

				document.getElementById("listadoNombreFranqueo").value = "";
				document.getElementById("unidades").value = "";
				document.getElementById("listadoProducto").value = "";
				document.getElementById("listadoNombreFranqueo").focus();
			}
			peticionUnica1=null;
		}
	}
}

function cargarCertificados() //js_certGrabar
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarCertificados;
		peticionUnica1.open("POST","ajax/cargarCertificados.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarCertificados();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarCertificados()
{
	var consulta = "accion=cargarCertificados";

	var campos = ['id','idCliente','nombre_franqueo','unidades','producto','fecha'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var fechaDesde = new Date();
	fechaDesde.setMonth(fechaDesde.getMonth()-2);
	var fechaDesdeTexto = fechaDesde.getFullYear()+"-"+String(fechaDesde.getMonth()+1).padStart(2,'0')+"-01";

	var filtrosOperadores = [{campo1: 'fecha', valor: fechaDesdeTexto, operador: '>='}];
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{ campo: 'id', dir: 'DESC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}


function mostrarCargarCertificados()
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

				if (datos.length>0)
				{
					var contenido = "";

					contenido+='<tr class="centrarTexto tablaCabeceraColor">';

					contenido+='<th align="center"><b>Cliente</b></th>';
					contenido+='<th align="center"><b>Nombre Franqueo</b></th>';
					contenido+='<th align="center"><b>Unidades</b></th>';
					contenido+='<th align="center"><b>Descripcion</b></th>';
					contenido+='<th align="center"><b>Fecha</b></th>';
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

					contenido+='<td id="'+datos[contador]["id"]+'_certificado" style="text-align: center">'+datos[contador]["idCliente"]+'</td>';
						contenido+='<td><input id="'+datos[contador]["id"]+'_nomFranqueo" value="'+datos[contador]["nombre_franqueo"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["unidades"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["producto"]+'" style="width: 100%;text-align: center" readonly></td>';

					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);

						contenido+='<td><input value="'+dia + "-" + mes+ "-" + anio+'" style="width: 100%;text-align: center" readonly></td>';

						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarCertificado" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroCertificado('+datos[contador]["id"]+')"></td>';
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


function informeCertificados() //js_certGrabar
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
			{campo1: 'fecha', valor: document.getElementById("fechaInicioModal").value, operador: '>='},
			{campo1: 'fecha', valor: document.getElementById("fechaFinModal").value, operador: '<='}
		];
		document.getElementById("imprimirFiltrosOperadores").value = JSON.stringify(filtrosOperadores);

		var order = [
			{ campo: 'idCliente', dir: 'ASC' },
			{ campo: 'fecha', dir: 'ASC' },
			{ campo: 'producto', dir: 'ASC' }
		];
		document.getElementById("imprimirOrder").value = JSON.stringify(order);

		document.getElementById("formImprimirCertificados").submit();
	}
}

function cargarCertificadoProductos()	//js_certGrabar
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarCertificadoProductos;
		peticionUnica1.open("POST","ajax/cargarCertificadoProductos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarCertificadoProductos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarCertificadoProductos()
{
	var consulta = "accion=cargarCertificadoProductos";

	var campos = ['id','producto'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{ campo: 'producto', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}

function mostrarCargarCertificadoProductos()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["producto"]+'</option>';

					contador++;
				}
				document.getElementById("listadoProducto").innerHTML = contenido;
			}
			peticionUnica1 = null;
		}
	}
}

function eliminarRegistroCertificado(id) //js_certGrabar
{
	if (confirm("¿Eliminar certificado: "+id+"?"))
	{
	  eliminarRegistroCertificado2(id);
	}
}


function eliminarRegistroCertificado2(id) //js_certGrabar
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroTrabajo2;
		peticionUnica1.open("POST","ajax/eliminarCertificadosGrabados.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaEliminarRegistroTrabajo2(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarRegistroTrabajo2(id)
{
	var consulta = "accion=eliminarCertificadosGrabados";
	var filtros = { id: id };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	return consulta;
}

function mostrarEliminarRegistroTrabajo2()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido eliminar el certificado");
			}
			else
			{
				cargarCertificados();
			}

			peticionUnica1=null;
		}
	}
}
