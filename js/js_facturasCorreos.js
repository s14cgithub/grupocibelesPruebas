var peticionUnica1 = null;

function cargarListadoFacturasCorreos() //js_facturasCorreos
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoFacturasCorreos;
		peticionUnica1.open("POST","ajax/mostrarFacturasCorreosTodos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoFacturasCorreos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoFacturasCorreos()
{	
	var consulta = "accion=mostrarFacturaCorreos";	
	
	consulta += "&idCliente=" + document.getElementById("buscarCliente").value;
	consulta += "&fechaInicio=" + document.getElementById("buscarFechaInicio").value;
	consulta += "&fechaFin=" + document.getElementById("buscarFechaFin").value;
	consulta += "&orden=" + document.getElementById("orden").value;
	consulta += "&desc=" + document.getElementById("ordenDesc").checked;
	
	//consulta += "&anioSeleccionado=" + document.getElementById("anioSeleccionado").value;
	
	return consulta;	
}

function mostrarCargarListadoFacturasCorreos()
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
				datos = JSON.parse(peticionUnica1.responseText);				
				
				var contenido = "";
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Número Oficial</th>';						
				contenido += '<th>Cliente</th>';
				contenido += '<th>Nombre</th>';
				contenido += '<th>Fecha</th>';
				contenido += '<th>Importe</th>';
				contenido += '<th>A Pagar</th>';
				contenido += '<th>Anticipio</th>';
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
					
					contenido += '<td>'+dia + "-" + mes+ "-" + anio+'</td>';
					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';					
					
					
					var valor=0;
					
					/*var valor = datos[contador]["aPagar"];
					if (datos[contador]["aPagar"].startsWith('.'))
					{
						valor = "0"+datos[contador]["aPagar"];
					}
					
					contenido += '<td align="right">'+valor.toLocaleString('es')+' €</td>';*/
					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';	
					
					/*valor = datos[contador]["anticipo"];
					if (datos[contador]["anticipo"].startsWith('.'))
					{
						valor = "0"+datos[contador]["anticipo"];
					}					
					
					contenido += '<td align="right">'+valor.toLocaleString('es')+' €</td>';*/
					
					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["anticipo"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
											
					
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

