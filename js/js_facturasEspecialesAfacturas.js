var peticionUnica1 = null;

function crearFacturasMensuales() //js_facturasEspecialesAfacturas				
{	
	if (confirm("¿Crear Facturas?")) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCrearFacturasMensuales;
			peticionUnica1.open("POST","ajax/insertarFacturasMensuales.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCrearFacturasMensuales();
			peticionUnica1.send(query_string);						
		}
	} 
}

function consultaCrearFacturasMensuales()
{	
	var consulta = "accion=crearFacturasMensuales";
	consulta += "&fechaInicio=" + document.getElementById("fechaInicio").innerHTML;
	consulta += "&fechaFin=" + document.getElementById("fechaFin").innerHTML;
	consulta +="&fechaFac=" + document.getElementById("fechaFacturacion").innerHTML;
	
	return consulta;	
}


function mostrarCrearFacturasMensuales()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			//let respuesta = peticionUnica1.responseText.trim();
			//console.log(respuesta);
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				var mensaje = "No se han podido crear todas las facturas:\n";
				for (var i = 0; i < res.errores.length; i++)
				{
					mensaje += "- " + res.errores[i].nombreCliente + ": " + res.errores[i].error + "\n";
				}
				alert(mensaje);
			}
			else
			{
				alert("Se han creado " + res.facturasCreadas.length + " facturas.");
			}

			limpiarFacturasEspeciales2();
			peticionUnica1=null;
		}
	}						
}

function limpiarFacturasEspeciales2() //js_facturasEspecialesAfacturas (movida de js_global.js)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarLimpiarFacturasEspeciales2;
		peticionUnica1.open("POST","ajax/eliminarFacturasEspecialesTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaLimpiarFacturasEspeciales2();
		peticionUnica1.send(query_string);
	}
}

function consultaLimpiarFacturasEspeciales2()
{
	var consulta = "accion=eliminarFacturasEspecialesTemporal";
	return consulta;
}

function mostrarLimpiarFacturasEspeciales2()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido limpiar la tabla temporal");
			}
			else
			{
				cargarFacturasEspecialesTemporal();
			}

			peticionUnica1=null;
		}
	}
}

function cargarFacturasEspecialesTemporal()		//js_facturasEspecialesAfacturas (movida de js_global.js)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarFacturasEspecialesTemporal;
		peticionUnica1.open("POST","ajax/cargarFacturasEspecialesTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarFacturasEspecialesTemporal();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarFacturasEspecialesTemporal()
{
	var consulta = "accion=cargarFacturasEspeciales";

	var campos = ['id','idCliente','nombre_franqueo','ordenTrabajo','fechaFacturacion','concepto','unidades','precioUnitario'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{ campo: 'nombre_empresa', dir: 'ASC' }, { campo: 'id', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}


function mostrarCargarFacturasEspecialesTemporal()
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

					contenido += '<tr class="centrarTexto tablaCabeceraColor">';

					contenido+='<th align="center"><b>Id</b></th>';
					contenido+='<th align="center"><b>Cliente</b></th>';
					contenido+='<th align="center"><b>Orden Trabajo</b></th>';
					contenido+='<th align="center"><b>Fecha</b></th>';
					contenido+='<th align="center"><b>Concepto</b></th>';
					contenido+='<th align="center">Unidades</th>';
					contenido+='<th align="center">Precio Unitario</th>';

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

						contenido+='<td id="'+datos[contador]["id"]+'_concepto"  value="" style="text-align: center">'+datos[contador]["concepto"]+'</td>';
						contenido+='<td id="'+datos[contador]["id"]+'_unidades" value="" style="text-align: center">'+datos[contador]["unidades"]+'</td>';
						contenido+='<td id="'+datos[contador]["id"]+'_precio"  value="" style="text-align: center">'+datos[contador]["precioUnitario"]+'</td>';


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

function copiarCertRut() //js_facturasEspecialesAfacturas (movida de js_global.js)
{
	limpiarFacturasEspeciales2();
	copiarCertRut2();
}
function copiarCertRut2() //js_facturasEspecialesAfacturas (movida de js_global.js)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCopiarCertRut2;
		peticionUnica1.open("POST","ajax/insertarCertYrutasEnTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCopiarCertRut2();
		peticionUnica1.send(query_string);
	}
}

function consultaCopiarCertRut2()
{
	var consulta = "accion=insertarCertYrutasEnTemporal";
	consulta += "&mes=" + document.getElementById("mesModal").value;
	consulta += "&anio=" + document.getElementById("anioModal").value;

	return consulta;
}

function mostrarCopiarCertRut2()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (!res.ok)
			{
				alert(res.error!="" ? res.error : "No se ha podido copiar los datos");
			}
			else
			{
				cargarFacturasEspecialesTemporal();
				$("#juntarCertRutModal").modal('hide');

				var date = new Date(document.getElementById('anioModal').value+"-"+document.getElementById('mesModal').value+"-5");
				var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
				var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);

				document.getElementById("fechaInicio").innerHTML = primerDia.getDate() + "-" + (primerDia.getMonth()+1)+ "-" + primerDia.getFullYear();
				document.getElementById("fechaFin").innerHTML = ultimoDia.getDate() + "-" + (ultimoDia.getMonth()+1)+ "-" + ultimoDia.getFullYear();

				document.getElementById("fechaFacturacion").innerHTML = res.fechaFac;
			}

			peticionUnica1=null;
		}
	}
}

function previsualizarFacturasMensuales()
{
	document.getElementById("previsualizar_fechaInicio").value = document.getElementById("fechaInicio").innerHTML;
	document.getElementById("previsualizar_fechaFin").value = document.getElementById("fechaFin").innerHTML;
	document.getElementById("previsualizar_fechaFac").value = document.getElementById("fechaFacturacion").innerHTML;

	document.getElementById("formPrevisualizarFacturasMensuales").submit();
}

