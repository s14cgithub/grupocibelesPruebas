var peticionUnica1 = null;

var laCondicion="";

function buscarFactura()
{

	var condicion="";


	var fechaInicioAnio="01-01-" + document.getElementById("anio").value;
	var fechaFinAnio="31-12-" + document.getElementById("anio").value;


	

	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;


	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		var laFecha = fechaInicio.replace("/","-");
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];	
	
		var laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " where t1.fecha>= '"+laFecha1+"'";
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		laFecha = fechaFin.replace("/","-");
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];	
	
		laFecha1 = dia+"-"+mes+"-"+anio;

		if (condicion!="")
		{
			condicion += " and t1.fecha <= '"+laFecha1+"'";
		}
		else
		{
			condicion += " where t1.fecha <= '"+laFecha1+"'";
		}
		
		
	}

	if (condicion=="")
	{
		condicion += " where t1.fecha>= '"+fechaInicioAnio+"' and t1.fecha<='"+fechaFinAnio+"'";
	}


	
	
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = "";
	
	if (document.getElementById("ordenDesc").checked)
	{
		desc = " desc";
	}
	
	
	
	

	//if (campoAbuscar =="t1.numero" && textoAbuscar!="")
	if (textoAbuscar!="")
	/*{
		condicion = "  and "+campoAbuscar+" = " + textoAbuscar;
	}
	else*/
	{
		condicion += "  and "+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";	
	}

	if (document.getElementById("checkCibeles").checked || document.getElementById("checkClayma").checked || document.getElementById("checkCorreos").checked)
	{
		condicion += " and (";
	
		if (document.getElementById("checkCibeles").checked)
		{
			condicion += " tipo='factura' or tipo='abono' or";
		}
	
		if (document.getElementById("checkClayma").checked)
		{
			condicion += " tipo='facturaClayma' or tipo='abonoClayma' or";
		}
	
		if (document.getElementById("checkCorreos").checked)
		{
			condicion += " tipo='facturaCorreos' or";
		}
	
		condicion = condicion.slice(0, -3);
	
		condicion += " )";


	
	}
	
	
	
	
	
	condicion += " order by " + orden + desc;
	
	
	
	laCondicion = condicion;
	
	
	

	cargarListadoAgenteComercial();
	
}



function listadoFacturas_Sumatorio() //js_facturaSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturas_Sumatorio;
		peticionUnica1.open("POST","ajax/mostrarFacturasVisualizarSumatorioTodo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturas_Sumatorio();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturas_Sumatorio()
{	
	var consulta = "accion=listadoFacturas_Sumatorio";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');	
	consulta += "&anioSeleccionado=" + document.getElementById("anio").value; 
	
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
				var datos = new Array;				
				datos = JSON.parse(peticionUnica1.responseText);
				
				
				document.getElementById("sumatorioApagar").innerHTML = 'Precio Neto: '+ Number(datos[0]["precioNeto"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';				
								
			}
			peticionUnica1 = null;
			
			
		}
	}						
}


function gestionExportarExcelFacturaCibeles() 
{	
			
		document.getElementById("exportarACCondiciones").value = laCondicion;
		document.getElementById("exportarACAnioSeleccionado").value = document.getElementById("anio").value
		//alert(laCondicion);
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



function cargarListadoAgenteComercial() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoAgenteComercial;
		peticionUnica1.open("POST","ajax/mostrarAgenteVisualizarTodo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoAgenteComercial();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoAgenteComercial()
{	
	var consulta = "accion=cargarListadoAgenteComercial";
	
	
	
	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	consulta += "&anio=" + document.getElementById("anio").value;
	
	return consulta;	
}

function mostrarCargarListadoAgenteComercial()
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
				
				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=3 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioApagar"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>';
				
				
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';					
				
				contenido += '<th>Cliente</th>';
				contenido += '<th>Numero</th>';
				contenido += '<th>Tipo</th>';
				contenido += '<th>Fecha</th>';				
				contenido += '<th>Pagado</th>';	
				contenido += '<th>Neto</th>';			
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
					
					//contenido += '<tr ' + contraste + '>';
					
					
					if (datos[contador]["tipo"]=="facturaCorreos")
					{
						contenido += '<tr  style="background-color:#4a69bd">';
					}
					else if (datos[contador]["tipo"]=="facturaClayma")
					{
						contenido += '<tr  style="background-color:#B87240">';
					}

					else if (datos[contador]["tipo"]=="abono")
					{
						contenido += '<tr  style="background-color:#ff3333">';
					}
					else if (datos[contador]["tipo"]=="abonoClayma")
					{
						contenido += '<tr  style="background-color:#6F1E51">';
					}


					


					
					else
					{
						contenido += '<tr>';
					}
					
										
					
					
					contenido += '<td id="'+datos[contador]["numero"]+'_cliente">'+datos[contador]["cliente"]+'</td>';
					contenido += '<td id="'+datos[contador]["numero"]+'_numero">'+datos[contador]["numero"]+'</td>';
					
					contenido += '<td id="'+datos[contador]["numero"]+'_tipo">'+datos[contador]["tipo"]+'</td>';
					
					
					
					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="'+datos[contador]["numero"]+'_fecha" style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	
					
						
					
						
				
					
					if (datos[contador]["fechaPago"]!="" && datos[contador]["fechaPago"]!=null && datos[contador]["fechaPago"]!="null")
					{
						dia = datos[contador]["fechaPago"]["date"].substr(8,2);
						mes = datos[contador]["fechaPago"]["date"].substr(5,2);
						anio = datos[contador]["fechaPago"]["date"].substr(0,4);
						
						if (dia + "-" + mes+ "-" + anio== '01-01-1900')
						{
							contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" class=""></td>';
						}
						else
						{						
							contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" >'+dia + "-" + mes+ "-" + anio+' <br> ' + datos[contador]["formaPagoReal"]+'</td>';							
						}
					}
					else
					{
						contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" class=""></td>';
					}					
					
					//var anioAbono = datos[contador]["anioAbono"]-2000;
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_aPagar"  style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["precioNeto"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
					
					if (datos[contador]["tipo"]=="factura")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirFactura(\''+datos[contador]["numero"]+'\', '+document.getElementById("anio").value+')"></td>';
					}
					else if (datos[contador]["tipo"]=="facturaClayma")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirFacturaClayma(\''+datos[contador]["numero"]+'\', '+document.getElementById("anio").value+')"></td>';
						
					}
					else if (datos[contador]["tipo"]=="abono")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirAbono(\''+datos[contador]["numero"]+'\', '+document.getElementById("anio").value+')"></td>';
					}
					else if (datos[contador]["tipo"]=="abonoClayma")
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirAbonoClayma(\''+datos[contador]["numero"]+'\', '+document.getElementById("anio").value+')"></td>';
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
	