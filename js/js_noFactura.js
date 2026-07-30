var peticionUnica1 = null;

var mostrarModificarProcesado=false;
var mostrarVisualizarProcesado=false;

function cargarAniosPresupuestos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarAniosPresupuestos;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAniosPresupuestos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarAniosPresupuestos()
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['anios'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [
		{ campo: 'anios', dir: 'DESC' }
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarAniosPresupuestos()
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

				while  (contador<datos.length)
				{	
					contenido += '<option value="'+datos[contador]["anios"]+'">'+datos[contador]["anios"]+'</option>';
					contador++;	
				}

				document.getElementById("anio").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}

function cargarListadoPresupuestoNoFacturable()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPresupuestoNoFacturable;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPresupuestoNoFacturable();
		peticionUnica1.send(query_string);
	}
}

function construirFiltrosNoFacturable()
{
	var filtrosOperadores = [
		{ campo1: 'numNoFactura', operador: 'IS NOT NULL' }
	];

	var anio = document.getElementById("anio").value;
	if (anio!="" && anio!=null)
	{
		var anio2digitos = anio.toString().substr(-2);
		filtrosOperadores.push({ campo1: 'presupuesto', valor: anio2digitos + '%', operador: 'LIKE' });
	}

	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;

	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		filtrosOperadores.push({ campo1: 'fecha', valor: fechaInicio, operador: '>=' });
	}

	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		var aux = new Date(fechaFin);
		aux.setDate(aux.getDate() + 1);
		var fechaFinExclusiva = aux.getFullYear() + "-" + (aux.getMonth()+1) + "-" + aux.getDate();
		filtrosOperadores.push({ campo1: 'fecha', valor: fechaFinExclusiva, operador: '<' });
	}

	if (document.getElementById("excluirInstituto").checked==true)
	{
		filtrosOperadores.push({ campo1: 'cliente', valor: '%instituto%', operador: 'NOT LIKE' });
	}

	if (document.getElementById("conImporte").checked==true)
	{
		filtrosOperadores.push({ campo1: 'importePresupuesto', valor: 0, operador: '>' });
	}

	if (document.getElementById("crisMagia").checked==true)
	{
		filtrosOperadores.push({ campo1: 'noSeFacturaObservaciones', valor: '%MAGIA%', operador: 'NOT LIKE' });
	}

	var filtros = {};

	if (document.getElementById("ordenProcesadosSolo").checked==true)
	{
		filtros.noFacProcesado = 1;
	}
	else if (document.getElementById("ordenProcesadosSin").checked==true)
	{
		filtros.sinProcesar = 1;
	}

	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;

	var filtrosLike = [];
	if (textoAbuscar != "")
	{
		filtrosLike.push({ campo: campoAbuscar, valor: textoAbuscar });
	}

	return { filtros: filtros, filtrosOperadores: filtrosOperadores, filtrosLike: filtrosLike };
}

function consultaCargarListadoPresupuestoNoFacturable()
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['numNoFactura','presupuesto','cliente','campana','importePresupuesto','numNoFacturaFecha','noFacProcesado'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(['tabla9']));

	var datosFiltros = construirFiltrosNoFacturable();
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(datosFiltros.filtrosOperadores));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(datosFiltros.filtros));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(datosFiltros.filtrosLike));

	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	var order = [
		{ campo: orden, dir: desc ? 'DESC' : 'ASC' }
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarListadoPresupuestoNoFacturable()
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
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					
					contenido += '<th>Numero</th>';
					contenido += '<th>Presupuesto</th>';
					contenido += '<th>Cliente</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Importe</th>';
					contenido += '<th>Fecha</th>';
					contenido += '<th>Obs.</th>';
					
				if (!(mostrarVisualizarProcesado==true && mostrarModificarProcesado==false))
				{
						
					contenido += '<th></th>';	
				}
					
				
				if (mostrarVisualizarProcesado==true)
				{
					contenido += '<th></th>';	
				}
				if (mostrarModificarProcesado==true)
				{
					contenido += '<th></th>';	
				}
				
				
				
				

					contenido += '</tr>';
				
				var contador = 0;	
				var contraste='';
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
					
					contenido += '<tr '+contraste+'>';
					
					
					contenido += '<td align="center"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["numNoFactura"]+'</span></td>';
					contenido += '<td align="center"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</span></td>';
					contenido += '<td align="left"><span>'+datos[contador]["cliente"]+'</span></td>';
					contenido += '<td align="left"><span">'+datos[contador]["campana"]+'</span></td>';
					
					var importe = 0;
					
					//var noTruncarDecimales = {maximumFractionDigits: 2};
					importe = Number(datos[contador]["importePresupuesto"]).toLocaleString('de-DE',{minimumFractionDigits: 2});
					//importe = Number(datos[contador]["importePresupuesto"]).toLocaleString(undefined);
					
					
					
					/*if (importe == ".00")
					{
						importe = "0";
					}*/
					
					contenido += '<td align="right"  style="overflow:hidden; white-space: nowrap;">'+importe+' €</td>';
					
								
					
					var dia = datos[contador]["numNoFacturaFecha"]["date"].substr(8,2);
					var mes = datos[contador]["numNoFacturaFecha"]["date"].substr(5,2);
					var anio = datos[contador]["numNoFacturaFecha"]["date"].substr(0,4);
					
					contenido += '<td><span style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</span></td>';
					
					contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="verObservacion(\''+datos[contador]["numNoFactura"]+'\')"></td>';
					
					if (!(mostrarVisualizarProcesado==true && mostrarModificarProcesado==false))
					{

						if (datos[contador]["noFacProcesado"]==1)
						{
							contenido += '<td align="center"></td>';
						}
						else
						{
							contenido += '<td align="center"><input type="image" value="" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarNumNoFacturable1(\''+datos[contador]["numNoFactura"]+'\')"></td>';
						}
					}
					else
					{
						document.getElementById("modalModificarObservacion").style.visibility = "hidden";
						document.getElementById("modalModificarObservacion").style.display = "none";
					}
					
					var soloLectura=' onclick="return false;"';
					if (mostrarModificarProcesado==true)
					{
						soloLectura=' onclick=""';
					}
					
					
					if (mostrarVisualizarProcesado==true)
					{
						if (datos[contador]["noFacProcesado"]==1)
						{
							contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_Procesado" '+soloLectura+' checked></input></td>';
						}
						else
						{
							contenido += '<td align="center"><input type="checkbox"  id="'+datos[contador]["presupuesto"]+'_Procesado" '+soloLectura+'></input></td>';
						}
					
					}
					if (mostrarModificarProcesado==true)
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="cambiarProcesado(\''+datos[contador]["presupuesto"]+'\')"></td>';
					}
					
					
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listado").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}

function cambiarProcesado(presupuesto)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCambiarProcesado;
		peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarProcesado(presupuesto);
		peticionUnica1.send(query_string);
	}
}
function consultaCambiarProcesado(presupuesto)
{	
	var consulta = "accion=modificarRegistro";

	var datos = {
		noFacProcesado: document.getElementById(presupuesto+'_Procesado').checked ? 1 : 0
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { presupuesto: presupuesto };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarCambiarProcesado()
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
				cargarListadoPresupuestoNoFacturable();
			}
			peticionUnica1=null;			
		}
	}						
}




function eliminarNumNoFacturable1(idNoFactura)
{
	document.getElementById("eliminarNumeroModalLabel").innerHTML="ELIMINAR NUMERO NO FACTURABLE: "+idNoFactura;
	//document.getElementById("eliminarNumeroModalLabel").style.color= "#FF0000";
	document.getElementById("numFacturaModal").innerHTML=idNoFactura;
	$("#eliminarFacturaModal").modal('show');
	
}
function eliminarNumNoFacturable2()
{
	if (confirm('¿Eliminar?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarNumNoFacturable2;
			peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarNumNoFacturable2();
			peticionUnica1.send(query_string);
		}
	}
}


function consultaEliminarNumNoFacturable2()
{	
	var consulta = "accion=modificarRegistro";

	var datos = {
		numNoFactura: null,
		noSeFacturaObservaciones: document.getElementById("motivoEliminacionModal").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { numNoFactura: document.getElementById("numFacturaModal").innerHTML };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	document.getElementById("numFacturaModal").innerHTM="";
	document.getElementById("motivoEliminacionModal").value="";
	
	return consulta;	
}

function mostrarEliminarNumNoFacturable2()
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
				$("#eliminarFacturaModal").modal('hide');
				cargarListadoPresupuestoNoFacturable();
			}
			peticionUnica1=null;			
		}
	}						
}

function verObservacion(numero) //js_facturas
{
	document.getElementById("numFacturaModal").innerHTML = numero;
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerObservacion;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerObservacion(numero);
		peticionUnica1.send(query_string);
	}
}

function consultaVerObservacion(numero)
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['noSeFacturaObservaciones'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { numNoFactura: numero };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarVerObservacion()
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
					document.getElementById("observacionInternaModal").value = datos[0]["noSeFacturaObservaciones"];
				}
				$("#observacionFacturaModal").modal('show');
			}
			peticionUnica1=null;
		}
	}						
}



function modificarObservacion() //js_facturas
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarObservacion;
		peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarObservacion();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarObservacion()
{	
	var consulta = "accion=modificarRegistro";

	var datos = {
		noSeFacturaObservaciones: document.getElementById("observacionInternaModal").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { numNoFactura: document.getElementById("numFacturaModal").innerHTML };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarModificarObservacion()
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
				alert("Observacion Realizada");
				
			}
			peticionUnica1=null;
		}
	}						
}

function gestionExportarExcel()
{
	var datosFiltros = construirFiltrosNoFacturable();

	document.getElementById("exportarFiltros").value = JSON.stringify(datosFiltros.filtros);
	document.getElementById("exportarFiltrosOperadores").value = JSON.stringify(datosFiltros.filtrosOperadores);
	document.getElementById("exportarFiltrosLike").value = JSON.stringify(datosFiltros.filtrosLike);

	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	document.getElementById("exportarOrden").value = orden;
	document.getElementById("exportarDesc").value = desc;

	document.getElementById("formExportarExcel").submit();
}


