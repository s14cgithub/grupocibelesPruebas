var peticionUnica1 = null;

var laCondicion="";

function buscarFactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	//var clayma = document.getElementById("clienteOrigen").checked;
	
	
	if ((campoAbuscar=="numero" && textoAbuscar!="")  ||(campoAbuscar=="factura" && textoAbuscar!=""))
	{
		condicion = " where "+campoAbuscar+" = " + textoAbuscar;	
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";
	}
		
	
	
	
	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		var laFecha = fechaInicio.replace("/","-");
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];	
	
		var laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and fecha>= '"+laFecha1+"'";
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		laFecha = fechaFin.replace("/","-");
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];	
	
		laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and fecha <= '"+laFecha1+"'";
	}
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	
	
	
	cargarListadoAbonos();
	
}

function cargarListadoAbonos() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoAbonos;
		peticionUnica1.open("POST","ajax/mostrarAbonosVisualizar.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoAbonos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoAbonos()
{	
	var consulta = "accion=cargarListadoAbonos";	
	
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	
	consulta += "&anioSeleccionado=" + document.getElementById("anioSeleccionado").value;
	
	return consulta;	
}

function mostrarCargarListadoAbonos()
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
					contenido += '<th align="center">Abono</th>';					
					contenido += '<th>Cliente</th>';
					contenido += '<th>Factura</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Total</th>';
					contenido += '<th>Total a Pagar</th>';
					contenido += '<th>Fecha</th>';					
					contenido += '<th>Pago</th>';					
					contenido += '<th>Abono</th>';
					contenido += '<th>Obs.</th>';
					

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
					
					/*if (datos[contador]["presupuesto"].includes("_V"))
					{
						contenido += '<tr bgcolor="#ff3333">';
					}
					else
					{*/
						contenido += '<tr>';
					//}
					
					contenido += '<td align="center" id="'+datos[contador]["numero"]+'_numero">'+datos[contador]["numero"]+'</td>';
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					
					/*if (datos[contador]["origen"]==1)
					{
						contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["numero"]+'_clayma" onclick="return false;" checked></input></td>';
					}
					else
					{
						contenido += '<td align="center"><input type="checkbox"  id="'+datos[contador]["numero"]+'_clayma" onclick="return false;"></input></td>';
					}	*/	
					//if (datos[contador]["origen"]==1)
					if (document.getElementById("clienteOrigen").checked==true)
					{
						contenido += '<td align="center" style="visibility: hidden;display: none;"><input type="checkbox" id="'+datos[contador]["numero"]+'_clayma" onclick="return false;" checked  ></input></td>';
					}
					else
					{
						contenido += '<td align="center" style="visibility: hidden;display: none;"><input type="checkbox"  id="'+datos[contador]["numero"]+'_clayma" onclick="return false;"   ></input></td>';
					}	
					
					contenido += '<td id="'+datos[contador]["numero"]+'_nombreCliente">'+datos[contador]["cliente"]+'</td>';
					
					
					var anioFactura = datos[contador]["anioFactura"]-2000;
					
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_factura">'+datos[contador]["factura"]+'/'+anioFactura+'</td>';
					contenido += '<td id="'+datos[contador]["numero"]+'_campana">'+datos[contador]["descripcion"]+'</td>';
					
					
					//contenido += '<td align="right" id="'+datos[contador]["numero"]+'_total"  style="visibility: hidden;display: none;">'+Number(datos[contador]["precioTotal"]).toLocaleString('es')+' €</td>'; 
					
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_total"  style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["precioTotal"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
					
					
					
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_aPagar" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="'+datos[contador]["numero"]+'_fecha" style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	
					
					//contenido += '<td id="'+datos[contador]["numero"]+'_fecha">'+datos[contador]["fecha"]["date"].substring(0,10)+'</td>';					
					var mostrarCrearAbono = true;
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
							contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" style="overflow:hidden; white-space: nowrap;" >'+dia + "-" + mes+ "-" + anio+'</td>';
							mostrarCrearAbono = false;
						}
					}
					else
					{
						contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" class=""></td>';
					}
					
					
					contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirAbono(\''+datos[contador]["factura"]+'\',\''+datos[contador]["numero"]+'\', \''+document.getElementById("anioSeleccionado").value+'\')"></td>';
					
					
					contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="verObservacionAbono('+datos[contador]["numero"]+')"></td>';	
					
					
					
					contenido += '</tr>';
					
					contador++;	
				}		
				
				document.getElementById("listadoFacturasSinEmitir").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}


function gestionExportarExcelAbonosCibeles() //js_facturas
{
	
	document.getElementById("exportarClayma").value = document.getElementById("clienteOrigen").checked;
	document.getElementById("exportarCondiciones").value = laCondicion;
	document.getElementById("exportarAnioSeleccionado").value =  document.getElementById("anioSeleccionado").value;
	
		
	document.getElementById("formExportarExcel").submit();
}

function comprobarDatosAbonoFactura() //js_facturas
{
	var numeroFactura = document.getElementById("facturaModal").innerHTML;
	/*var fechaSeleccionado = document.getElementById("fechaModal").value;
	var anioSeleccionado = fechaSeleccionado.substr(0,4);
	var mesSeleccionado = fechaSeleccionado.substr(5,2);
	var diaSeleccionado = fechaSeleccionado.substr(8,2);
	
	var fechaLimite = document.getElementById("fechaModal").getAttribute("min");
	var anioLimite = fechaLimite.substr(0,4);
	var mesLimite = fechaLimite.substr(5,2);
	var diaLimite = fechaLimite.substr(8,2);
	
	
	var fechaIncorrecta = false;
	
	if (anioSeleccionado<anioLimite)
		fechaIncorrecta = true;
	else if (anioSeleccionado<anioLimite && mesSeleccionado<mesLimite)
		fechaIncorrecta = true;
	else if (anioSeleccionado<anioLimite && mesSeleccionado<mesLimite && diaSeleccionado<diaLimite)
		fechaIncorrecta = true;
	
	document.getElementById("fechaMensaje").innerHTML = "La fecha debe ser igual o posterior a: " + diaLimite + "-" + mesLimite + "-" + anioLimite;
	
	if (fechaIncorrecta==true)
	{		
		document.getElementById("fechaModal").focus();
	}
	else*/
	{		
		crearAbonoFactura2(numeroFactura);
	}
	
	
}

function crearAbonoFactura2(numeroFactura) //js_facturas
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearAbonoFactura;
		peticionUnica1.open("POST","ajax/crearAbonoFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearAbonoFactura(numeroFactura);
		peticionUnica1.send(query_string);
	}
}

function consultaCrearAbonoFactura(numeroFactura)
{	
	var consulta = "accion=crearAbonoFactura";
	consulta += "&numeroFactura="+numeroFactura;
	//consulta += "&fecha=" + document.getElementById("fechaModal").value;
	consulta += "&clayma=" + document.getElementById(numeroFactura+"_clayma").checked;
	
	return consulta;	
}

function mostrarCrearAbonoFactura()
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
				alert('Numero del Abono: '+datos[0]["numeroAbono"]);
				$("#crearAbonoModal").modal('hide');
				
				//irAImprimirFactura2(datos[0]["numeroAbono"]);
				irAImprimirAbono(document.getElementById("facturaModal").innerHTML);
				//cargarListadoFacturas();				
				buscarFactura();
			}
			peticionUnica1=null;			
		}
	}						
}







function irAImprimirAbono(numFactura, numeroAbono, anioSeleccionado) //js_facturas
{	
	if (document.getElementById(numeroAbono+"_clayma").checked)
	{
		document.getElementById("imprimirNumFacturaAbonoClayma").value =numeroAbono;
		document.getElementById("imprimirAnioSeleccionadoClayma").value =anioSeleccionado;		
		document.getElementById("formImprimirAbonoClayma").submit();
	}
	else
	{
		document.getElementById("imprimirNumFacturaAbono").value =numeroAbono;
		document.getElementById("imprimirAnioSeleccionado").value =anioSeleccionado;		
		document.getElementById("formImprimirAbono").submit();
	}
}

function modificarObservacionAbono() //js_facturas
{
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarObservacionAbono;
		peticionUnica1.open("POST","ajax/modificarObservacionAbono.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarObservacionAbono();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarObservacionAbono()
{	
	var consulta = "accion=modificarObservacionAbono";	
	consulta += "&numeroAbono=" + document.getElementById("numFacturaModal").innerHTML;
	consulta += "&observacion=" + document.getElementById("observacionModal").value;	
	consulta += "&observacionInterna=" + document.getElementById("observacionInternaModal").value;	
	consulta += "&clayma=" + document.getElementById("claymaModal").innerHTML;
	consulta += "&anioSeleccionado=" + document.getElementById("anioSeleccionado").value;
	
	return consulta;	
}

function mostrarModificarObservacionAbono()
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
				alert(peticionUnica1.responseText);
				$("#observacionFacturaModal").modal('hide');
			}
			peticionUnica1=null;
		}
	}						
}

function verObservacionAbono(numAbono) //js_facturas
{
	document.getElementById("numFacturaModal").innerHTML = numAbono;
	document.getElementById("claymaModal").innerHTML = document.getElementById(numAbono+"_clayma").checked;
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerObservacionAbono;
		peticionUnica1.open("POST","ajax/mostrarAbonosVisualizar.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerObservacionAbono(numAbono);
		peticionUnica1.send(query_string);
	}
}

function consultaVerObservacionAbono(numAbono)
{	
	var consulta = "accion=cargarListadoAbonos";	
	var condicion = " where numero="+numAbono;
	consulta += "&condicion="+condicion;
	consulta += "&anioSeleccionado=" + document.getElementById("anioSeleccionado").value;
	consulta += "&clayma=" + document.getElementById(numAbono+"_clayma").checked;
	
	return consulta;	
}

function mostrarVerObservacionAbono()
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
				
				if (datos.length>0)
				{					
					document.getElementById("observacionModal").value = datos[0]["observaciones"];
					document.getElementById("observacionInternaModal").value = datos[0]["observacionesInternas"];					
				}
				//document.getElementById("").value = peticionUnica.responseText;
				$("#observacionFacturaModal").modal('show');
			}
			peticionUnica1=null;
		}
	}						
}

function crearAbonoFactura(numeroFactura) //js_facturas
{	
	document.getElementById("facturaModal").innerHTML = document.getElementById(numeroFactura+"_numero").innerHTML;
	document.getElementById("clienteModal").innerHTML = document.getElementById(numeroFactura+"_nombreCliente").innerHTML;
	document.getElementById("totalModal").innerHTML = document.getElementById(numeroFactura+"_total").innerHTML;
	document.getElementById("aPagarModal").innerHTML = document.getElementById(numeroFactura+"_aPagar").innerHTML;
	//document.getElementById("formaPagoModal").innerHTML = document.getElementById(numeroFactura+"_formaPago").innerHTML;

	/*verFechaMinimaAbono();
	var fechaMinima = "2021-10-11";
	var fechaMinima = campo1;
	campo1 = "";
	
	document.getElementById("fechaModal").setAttribute("min",fechaMinima);
	
	var anioLimite = fechaMinima.substr(0,4);
	var mesLimite = fechaMinima.substr(5,2);
	var diaLimite = fechaMinima.substr(8,2);
	
	document.getElementById("fechaMensaje").innerHTML = "La fecha debe ser igual o posterior a: " + diaLimite + "-" + mesLimite + "-" + anioLimite;	*/
	
	$("#crearAbonoModal").modal('show');	
}

function verFechaMinimaAbono() //js_facturas
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerFechaMinimaAbono;
		peticionUnica1.open("POST","ajax/mostrarUltimaFechaAbono.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerFechaMinimaAbono();
		peticionUnica1.send(query_string);
	}
}

function consultaVerFechaMinimaAbono()
{	
	var consulta = "accion=mostrarUltimaFechaAbono";
	consulta += "&anioSeleccionado=" + document.getElementById("anioSeleccionado").value;
	return consulta;	
}

function mostrarVerFechaMinimaAbono()
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
				
				if (datos[0]["fecha"] != null)
				{
					campo1 = datos[0]["fecha"]["date"].substr(0,10);
				}
				else
				{
					campo1 = '1000-01-01';
				}
						
			}
			peticionUnica1=null;			
		}
	}						
}

function imprimirFacturaRango()
{
	
	
	if (document.getElementById("clienteOrigen").checked==true)
	{
		
		document.getElementById("imprimirPrimeraFacturaClayma").value = document.getElementById("primeraFacturaImprmirModal").value;
	document.getElementById("imprimirUltimaFacturaClayma").value = document.getElementById("ultimaFacturaImprmirModal").value;
		document.getElementById("formImprimirFacturaRangoClayma").submit();
	}
	else
	{
		
		document.getElementById("imprimirPrimeraFactura").value = document.getElementById("primeraFacturaImprmirModal").value;
	document.getElementById("imprimirUltimaFactura").value = document.getElementById("ultimaFacturaImprmirModal").value;
		document.getElementById("formImprimirFacturaRango").submit();
	}
	
	
	
}

