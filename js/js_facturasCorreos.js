var peticionUnica1 = null;

function cargarClientesFacturasCorreos() //cargarClientes de js_global.js esta obsoleta (no encaja con ajax/cargarClientes.php ya migrado); version local
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientesFacturasCorreos;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesFacturasCorreos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarClientesFacturasCorreos()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['codigo_saldo','nombre_empresa'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtrosOperadores = [{ campo1: 'codigo_saldo', campo2: 'codigo', operador: '=' }];
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{ campo: 'nombre_empresa', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarClientesFacturasCorreos()
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
				var contenido = '<option value="0">Todos</option>';
				var contador = 0;

				while (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+' - '+datos[contador]["codigo_saldo"]+'</option>';
					contador++;
				}

				document.getElementById("buscarCliente").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}						
}

function cargarListadoFacturasCorreos() //js_facturasCorreos
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoFacturasCorreos;
		peticionUnica1.open("POST","ajax/cargarFacturacionCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoFacturasCorreos();
		peticionUnica1.send(query_string);
	}
}

function construirFiltrosFacturasCorreos()
{
	var filtros = {};
	var filtrosOperadores = [];

	if (document.getElementById("buscarCliente").value != "0" && document.getElementById("buscarCliente").value != "")
	{
		filtros.codigo_saldo = document.getElementById("buscarCliente").value;
	}

	if (document.getElementById("buscarFechaInicio").value != "")
	{
		filtrosOperadores.push({campo1: 'fecha', valor: document.getElementById("buscarFechaInicio").value, operador: '>='});
	}

	if (document.getElementById("buscarFechaFin").value != "")
	{
		filtrosOperadores.push({campo1: 'fecha', valor: document.getElementById("buscarFechaFin").value, operador: '<='});
	}

	return {filtros: filtros, filtrosOperadores: filtrosOperadores};
}

function consultaCargarListadoFacturasCorreos()
{	
	var consulta = "accion=cargarFacturacionCorreos";	

	var campos = ['id','numeroOficial','codigoCliente','nombre_empresa','fecha','neto','iva','anticipo','importe','aPagar','formaPago','fechaPago'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(['tabla2']));

	var datosFiltros = construirFiltrosFacturasCorreos();
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(datosFiltros.filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(datosFiltros.filtrosOperadores));

	var order = [{campo: document.getElementById("orden").value, dir: document.getElementById("ordenDesc").checked ? 'DESC' : 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoFacturasCorreos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText.trim());

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;				
				
				var contenido = "<tr style='border:0;' id='filaSumatorios'></tr>";
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Número Oficial</th>';						
				contenido += '<th>Cliente</th>';
				contenido += '<th>Nombre</th>';
				contenido += '<th>Fecha</th>';
				contenido += '<th>Neto</th>';
				contenido += '<th>IVA</th>';
				contenido += '<th>Anticipio</th>';
				contenido += '<th>Importe</th>';
				contenido += '<th>A Pagar</th>';			
				
				contenido += '<th>Forma de Pago</th>';
				contenido += '<th></th>';

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
					contenido += '<td align="center">'+datos[contador]["numeroOficial"]+'</td>';
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					contenido += '<td>'+datos[contador]["codigoCliente"]+'</td>';
					contenido += '<td>'+datos[contador]["nombre_empresa"]+'</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';
					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["neto"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';					
					
					
					var valor=0;
					
					/*var valor = datos[contador]["aPagar"];
					if (datos[contador]["aPagar"].startsWith('.'))
					{
						valor = "0"+datos[contador]["aPagar"];
					}
					
					contenido += '<td align="right">'+valor.toLocaleString('es')+' €</td>';*/
					
					
					
					/*valor = datos[contador]["anticipo"];
					if (datos[contador]["anticipo"].startsWith('.'))
					{
						valor = "0"+datos[contador]["anticipo"];
					}					
					
					contenido += '<td align="right">'+valor.toLocaleString('es')+' €</td>';*/
					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["iva"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["anticipo"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';	
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';	
					
											
					
					if (datos[contador]["formaPago"]=="" || datos[contador]["formaPago"]==null)
					{
						contenido += '<td></td>';
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarFacturaCorreos('+datos[contador]["id"]+')"></td>';
					}
					else
					{
						if (datos[contador]["fechaPago"] != null)
						{
							dia = datos[contador]["fechaPago"]["date"].substr(8,2);
							mes = datos[contador]["fechaPago"]["date"].substr(5,2);
							anio = datos[contador]["fechaPago"]["date"].substr(0,4);
						}
						else
						{
							dia = '';
							mes = '';
							anio = '';
						}
						
						
						contenido += '<td>'+datos[contador]["formaPago"]+ '  ' +dia + "-" + mes+ "-" + anio+'</td>';
						contenido += '<td></td>';
					}					
					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listadoPF").innerHTML = contenido;				
			}
			peticionUnica1=null;
			mostrarCargarFacturasCorreosTotales();			
		}
	}
	
}


function mostrarCargarFacturasCorreosTotales() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarMostrarCargarFacturasCorreosTotales;
		peticionUnica1.open("POST","ajax/cargarFacturacionCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaMostrarCargarFacturasCorreosTotales();
		peticionUnica1.send(query_string);
	}
}

function consultaMostrarCargarFacturasCorreosTotales()
{	
	var consulta = "accion=cargarFacturacionCorreos";	

	var campos = ['netoSumatorio','ivaSumatorio','anticipoSumatorio','importeSumatorio','aPagarSumatorio'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(['tabla2']));

	var datosFiltros = construirFiltrosFacturasCorreos();
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(datosFiltros.filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(datosFiltros.filtrosOperadores));
	
	return consulta;	
}

function mostrarMostrarCargarFacturasCorreosTotales()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText.trim());

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;				
				
				document.getElementById("filaSumatorios").innerHTML = "<td style='border:0;'></td><td style='border:0;'></td><td style='border:0;'></td><td style='border:0;'></td><td style='border:0;overflow:hidden; white-space: nowrap;'>"+Number(datos[0]["netoSumatorio"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+" €</td><td style='border:0;overflow:hidden; white-space: nowrap;'>"+Number(datos[0]["ivaSumatorio"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+" €</td><td align='right' style='border:0;overflow:hidden; white-space: nowrap;'>"+Number(datos[0]["anticipoSumatorio"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+" €</td><td style='border:0;overflow:hidden; white-space: nowrap;'>"+Number(datos[0]["importeSumatorio"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+" €</td><td style='border:0;overflow:hidden; white-space: nowrap;'>"+Number(datos[0]["aPagarSumatorio"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+" €</td>";			
			}
			peticionUnica1=null;					
		}
	}
	
}	

function gestionExportarExcelFacturaCorreos() //js_facturasCorreos
{	
	document.getElementById("exportarCliente").value = document.getElementById("buscarCliente").options[document.getElementById("buscarCliente").selectedIndex].text;
	document.getElementById("exportarFechaInicio").value = document.getElementById("buscarFechaInicio").value;
	document.getElementById("exportarFechaFin").value = document.getElementById("buscarFechaFin").value;
	document.getElementById("exportarOrdenarPor").value = document.getElementById("orden").value;
	document.getElementById("exportarDesc").value = document.getElementById("ordenDesc").checked;
		
	document.getElementById("formExportarExcel").submit();
}


function eliminarFacturaCorreos(id) //js_facturasCorreos
{
	if (confirm("¿Eliminar el registro: "+id+"?")) 
	{
	  	eliminarFacturaCorreos2(id);
	} 
	else 
	{
	  //no hace nada
	}
}

function eliminarFacturaCorreos2(id) //js_facturasCorreos
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarFacturaCorreos2;
		peticionUnica1.open("POST","ajax/eliminarFacturaCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarFacturaCorreos2(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarFacturaCorreos2(id)
{	
	var consulta = "accion=eliminarFacturaCorreo";
	consulta +="&id=" + id;	
	return consulta;	
}

function mostrarEliminarFacturaCorreos2()
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
				cargarListadoFacturasCorreos();	
			}
			
		}
	}						
}

