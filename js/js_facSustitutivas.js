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
	
	
		
	//condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";	


	if (campoAbuscar =="t1.numero" && textoAbuscar!="")
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
		
		condicion += " and t1.fecha>= '"+laFecha1+"'";
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		laFecha = fechaFin.replace("/","-");
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];	
	
		laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and t1.fecha <= '"+laFecha1+"'";
	}
	
	if (document.getElementById("facSoloPagadas").checked==true)
	{
		condicion += " and t1.fechaPago != '' ";
	}
	
	

	/*
	if (document.getElementById("preFacturaConNum").checked == true)
	{
		condicion += " and t1.prefactura = 1 ";
	}
	*/
	
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}

	/*
	if (document.getElementById("agenteComercial").checked==false)
	{
		if (desc==true)
		{
			condicion += " desc";
		}
	}
		*/
	
	laCondicion = condicion;
	
	
	//cargarListadoFacturasSinEmitir();
	
	/*
	if (document.getElementById("agenteComercial").checked==true)
	{
		cargarListadoAgenteComercial();
	}
	else*/
	{
		cargarListadoFacturasRec();
	}
	
	
}

function cargarListadoFacturasRec() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoFacturasRec;
		peticionUnica1.open("POST","ajax/mostrarFacRectificativaSustitutiva.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoFacturasRec();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoFacturasRec()
{	
	var consulta = "accion=cargarListadoFacturas";
	
	
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	consulta += "&anio=" + document.getElementById("anio").value;
	
	return consulta;	
}

function mostrarCargarListadoFacturasRec()
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
				datos = JSON.parse(peticionUnica1.responseText.trim());				
				
				var contenido = "";
				
				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=3 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioApagar">aaa</td><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>';
				
				
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					contenido += '<th align="center">Factura</th>';	
					//contenido += '<th align="center">Clayma</th>';	
					contenido += '<th>Cliente</th>';
					contenido += '<th>Rectifica a</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Total</th>';
					contenido += '<th>Total a Pagar</th>';
					contenido += '<th>Fecha</th>';					
					contenido += '<th>Pago</th>';
					contenido += '<th ondblclick="comprobarVerifactu(\'RectSustitucion\');">Factura</th>';
					contenido += '<th>Rec. Dife.</th>';
					contenido += '<th>Rec. Sust.</th>';


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
					
					contenido += '<tr '+contraste+'>';
					
					
					
					contenido += '<td  style="white-space: nowrap;" align="center" id="'+datos[contador]["numero"]+'_numero">'+datos[contador]["numeroFacturaCompleto"]+'</td>';
					
					
						
					if (datos[contador]["origen"]==1)
					{
						contenido += '<td align="center" style="visibility: hidden;display: none;"><input type="checkbox" id="'+datos[contador]["numero"]+'_clayma" onclick="return false;" checked  ></input></td>';
					}
					else
					{
						contenido += '<td align="center" style="visibility: hidden;display: none;"><input type="checkbox"  id="'+datos[contador]["numero"]+'_clayma" onclick="return false;"   ></input></td>';
					}	
					
					contenido += '<td id="'+datos[contador]["numero"]+'_nombreCliente">'+datos[contador]["cliente"]+'</td>';
					contenido += '<td id="'+datos[contador]["numero"]+'_presupuesto">'+datos[contador]["origenFactura"]+'</td>';
					contenido += '<td id="'+datos[contador]["numero"]+'_campana">'+datos[contador]["descripcion"]+'</td>';					
					
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_total"  style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["precioTotal"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
										
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_aPagar"  style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="'+datos[contador]["numero"]+'_fecha" style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	
					
									
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
							contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" >'+dia + "-" + mes+ "-" + anio+' <br> ' + datos[contador]["formaPagoReal"]+'</td>';
							mostrarCrearAbono = false;
						}
					}
					else
					{
						contenido += '<td id="'+datos[contador]["numero"]+'_fechaPago" class=""></td>';
					}	
					

					//FACTURA
					let fondo="";
					let fechaRegistro = new Date(datos[contador]["fecha"]["date"]);
					let fechaLimite   = new Date(fechaCambioVerifactu); //poner la ultima fecha de la factura sin utilizar verifactu

					if (fechaRegistro>fechaLimite && (datos[contador]["verifactu_idSolicitud"]==null || datos[contador]["verifactu_idSolicitud"]==''))
					{
						fondo = 'style="animation: fondoAlerta 0.5s infinite;" title="No Procesado con Verifactu"';
					}
					else if (datos[contador]["verifactu_message"] != null)
					{
						var messageError = datos[contador]["verifactu_message"];
						messageError = messageError.replace(/"/g, "&quot;").replace(/'/g, "&#39;");
						fondo = 'style="background-color: black;" title="'+messageError+'"';
					}
					if (datos[contador]["origen"]==1)
					{						
						contenido += '<td align="center"'+fondo+'><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirFacRec('+datos[contador]["numero"]+',\''+datos[contador]["numeroFacturaCompleto"]+'\')"></td>'; 
					}
					else
					{
						contenido += '<td align="center"'+fondo+'><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="irAImprimirFacRec('+datos[contador]["numero"]+',\''+datos[contador]["numeroFacturaCompleto"]+'\')"></td>'; 
					}
						
					
					

					if (datos[contador]["facRecDiferencia"] != true && datos[contador]["facRecSustitucion"] != true)
					{
						contenido += '<td align="center"><input type="image" id="'+datos[contador]["numeroFacturaCompleto"]+'_crearFacDif" value="" src="imagenes/crear.png" style="width:15px;"   onclick="crearFacRecDiferencia(\''+datos[contador]["numeroFacturaCompleto"]+'\')"></td>';
						contenido += '<td align="center"><input type="image" id="'+datos[contador]["numeroFacturaCompleto"]+'_crearFacSust" value="" src="imagenes/crear.png" style="width:15px;"  onclick="crearFacRecSustitucion(\''+datos[contador]["numeroFacturaCompleto"]+'\')"></td>';
					}
					else
					{
						if (datos[contador]["facRecDiferencia"]==true)//FACTURA RECITIFICATIVA POR DIFERENCIA
						{					
							contenido += '<td align="center" onclick="irAImprimirFacRec('+datos[contador]["numero"]+',\''+datos[contador]["facRec"]+'\')">'+datos[contador]["facRec"]+'</td>';					
							contenido += '<td align="center"></td>';
						}
						else if (datos[contador]["facRecSustitucion"]==true)//FACTURA RECITIFICATIVA POR SUSTITUCION
						{												
							contenido += '<td align="center"></td>';
							contenido += '<td align="center" onclick="irAImprimirFacRec('+datos[contador]["numero"]+',\''+datos[contador]["facRec"]+'\')">'+datos[contador]["facRec"]+'</td>';
						}						
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

function comprobarVerifactu(tipoFactura)
{
	if (confirm("COMPROBAR FACTURAS REC. POR SUSTITUCION SIN VERIFACTU "+document.getElementById("anio").value+"\nSolo se comprobará el año seleccionado\n¿Quieres continuar?")) 
	{    
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarComprobarVerifactu;
			peticionUnica1.open("POST","ajax/comprobarFacturasVerifactu.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaComprobarVerifactu(tipoFactura);
			peticionUnica1.send(query_string);
		}
	} 
}

function consultaComprobarVerifactu(tipoFactura)
{	
	var consulta = "accion=comprobarVerifactu";
	consulta += "&anioSeleccionado="  + document.getElementById("anio").value; 
	consulta +="&clayma=" + document.getElementById("clienteOrigen").value;
	consulta +="&tipoFactura=" + tipoFactura;
	return consulta;	
}

function mostrarComprobarVerifactu()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			let respuesta = peticionUnica1.responseText.trim();
			//console.log(respuesta);
			if (respuesta.toLowerCase().includes("error"))
			{
				alert(peticionUnica1.responseText);
			}
			else
			{				
				
							
			}
			peticionUnica1=null;
			
			cargarListadoFacturas();
		}
	}						
}

function listadoFacturas_Sumatorio() //js_facturaSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturas_Sumatorio;
		peticionUnica1.open("POST","ajax/mostrarFacturasVisualizarSumatorio.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturas_Sumatorio();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturas_Sumatorio()
{	
	var consulta = "accion=listadoFacturas_Sumatorio";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
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
				
				
				document.getElementById("sumatorioApagar").innerHTML = "Total a Pagar: " + Number(datos[0]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' € | Precio Neto: '+ Number(datos[0]["precioNeto"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';				
								
			}
			peticionUnica1 = null;
			
			
		}
	}						
}


function gestionExportarExcelFacturaCibeles() //js_facturas
{
	/*document.getElementById("exportarCliente").value = document.getElementById("buscarCliente").options[document.getElementById("buscarCliente").selectedIndex].text;
	document.getElementById("exportarFechaInicio").value = document.getElementById("buscarFechaInicio").value;
	document.getElementById("exportarFechaFin").value = document.getElementById("buscarFechaFin").value;
	document.getElementById("exportarOrdenarPor").value = document.getElementById("orden").value;
	document.getElementById("exportarDesc").value = document.getElementById("ordenDesc").checked;*/
	
	
	if (document.getElementById("agenteComercial").checked==true)
	{
		document.getElementById("exportarACClayma").value = document.getElementById("clienteOrigen").checked;
		document.getElementById("exportarACCondiciones").value = laCondicion;
		document.getElementById("exportarACAnioSeleccionado").value = document.getElementById("anio").value
		//alert(laCondicion);
		document.getElementById("formExportarAgenteComercialExcel").submit();			
	}
	else
	{
		document.getElementById("exportarClayma").value = document.getElementById("clienteOrigen").checked;
		document.getElementById("exportarCondiciones").value = laCondicion;
		document.getElementById("exportarAnioSeleccionado").value = document.getElementById("anio").value
		document.getElementById("formExportarExcel").submit();
	}
	
}

function comprobarDatosAbonoFactura() //js_facturas
{
	var numeroFactura = document.getElementById("facturaModal").innerHTML;
	
	//var fechaFactura
	
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
	consulta += "&clayma=" + document.getElementById(numeroFactura+"_clayma").checked;
	consulta += "&anioSeleccionado=" + document.getElementById("anio").value;
	
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
				irAImprimirAbono(document.getElementById("facturaModal").innerHTML, datos[0]["numeroAbono"] , datos[0]["anioAbono"] );
				cargarListadoFacturas();				
			}
			peticionUnica1=null;			
		}
	}						
}

function irAImprimirAbono(numFactura, numAbono, anioAbono) //js_facturas
{	
	if (document.getElementById(numFactura+"_clayma").checked)
	{
		document.getElementById("imprimirNumFacturaAbonoClayma").value =numAbono;
		document.getElementById("imprimirAnioSeleccionadoClayma").value = anioAbono;
		document.getElementById("formImprimirAbonoClayma").submit();
	}
	else
	{
		document.getElementById("imprimirNumFacturaAbono").value =numAbono;
		document.getElementById("imprimirAnioSeleccionado").value = anioAbono;
		document.getElementById("formImprimirAbono").submit();
	}
}

function irAImprimirFacRec(numFactura, numeroFacRecCompleto ) 
{	
	if (document.getElementById(numFactura+"_clayma").checked)
	{
		document.getElementById("imprimirNumFacturaRecClayma").value = numeroFacRecCompleto;
		
		document.getElementById("formImprimirFacRecClayma").submit();
	}
	else
	{
		document.getElementById("imprimirNumFacturaRec").value = numeroFacRecCompleto;		
		document.getElementById("formImprimirFacRec").submit();
	}
}

function modificarObservacionFactura() //js_facturas
{
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarObservacionFactura;
		peticionUnica1.open("POST","ajax/modificarObservacionFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarObservacionFactura();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarObservacionFactura()
{	
	var consulta = "accion=modificarObservacionFactura";	
	consulta += "&numeroFactura=" + document.getElementById("numFacturaModal").innerHTML;
	consulta += "&observacion=" + document.getElementById("observacionModal").value;	
	consulta += "&clayma=" + document.getElementById("claymaModal").innerHTML;
	consulta += "&anioSeleccionado=" + document.getElementById("anio").value;
	
	return consulta;	
}

function mostrarModificarObservacionFactura()
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

function verObservacionFactura(numFactura) //js_facturas
{
	document.getElementById("numFacturaModal").innerHTML = numFactura;
	document.getElementById("claymaModal").innerHTML = document.getElementById(numFactura+"_clayma").checked;
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerObservacionFactura;
		peticionUnica1.open("POST","ajax/verDatosUnaFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaMostrarVerObservacionFactura(numFactura);
		peticionUnica1.send(query_string);
	}
}

function consultaMostrarVerObservacionFactura(numFactura)
{	
	var consulta = "accion=verDatosUnaFactura";	
	consulta += "&numeroFactura="+numFactura;
	consulta += "&clayma=" + document.getElementById(numFactura+"_clayma").checked;
	consulta += "&anioSeleccionado="+document.getElementById("anio").value;
	
	return consulta;	
}

function mostrarVerObservacionFactura()
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
	document.getElementById("fechaModal").innerHTML = document.getElementById(numeroFactura+"_fecha").innerHTML;
	

	
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
	consulta += "&anioSeleccionado=" + document.getElementById("anio").value; 
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
		document.getElementById("anioSeleccionado2").value =  document.getElementById("anio").value;
		document.getElementById("formImprimirFacturaRangoClayma").submit();
	}
	else
	{
		
		document.getElementById("imprimirPrimeraFactura").value = document.getElementById("primeraFacturaImprmirModal").value;
		document.getElementById("imprimirUltimaFactura").value = document.getElementById("ultimaFacturaImprmirModal").value;
		document.getElementById("anioSeleccionado1").value =  document.getElementById("anio").value;
		document.getElementById("formImprimirFacturaRango").submit();
	}
	
	
	
}

function gestionAgenteComercial()
{
	var contenido;
	if (document.getElementById("agenteComercial").checked==true)
	{
		contenido = '<option value=" t1.cliente">Cliente</option>';
		contenido += '<option value="t1.nombre" selected>Comercial</option>';
		contenido += '<option value="t1.numero">Factura</option>';
		contenido += '<option value="t1.fechaPago">Fecha de Pago</option>';
		contenido += '<option value="t1.formaPagoReal">Forma de Pago</option>';
		contenido += '<option value="t1.aPagar">Importe</option>';
		contenido += '<option value="t1.liquitado">Liquidado</option>';
		
		document.getElementById("buscarCampo").innerHTML=contenido;
		
		
		contenido=null;
		/*contenido = '<option value=" t1.cliente">Cliente</option>';
		contenido += '<option value="t3.nombre">Comercial</option>';*/
		contenido += '<option value="t1.nombre, t1.cliente,t1.tipo desc, t1.fecha" selected>Com y Cli</option>';
		/*contenido += '<option value="t1.numero">Factura</option>';
		contenido += '<option value="t1.fechaPago">Fecha de Pago</option>';
		contenido += '<option value="t1.formaPagoReal">Forma de Pago</option>';
		contenido += '<option value="t1.aPagar">Importe</option>';
		contenido += '<option value="t1.liquitado">Liquidado</option>';	*/
		
		document.getElementById("ordenBuscar").innerHTML=contenido;
		
		
		
								
		buscarFactura();
		
		document.getElementById("btnImprimir").style.visibility = "hidden";
		
			
	}
	else
	{
		contenido += '<option value="t1.descripcion">Campaña</option>';
		contenido += '<option value="t1.cliente">Cliente</option>';
		contenido += '<option value="t1.numero"  selected>Factura</option>';		
		contenido += '<option value="t1.presupuesto">Presupuesto</option>';
		contenido += '<option value="t1.precioTotal">Total</option>';
		contenido += '<option value="t1.aPagar">Total a Pagar</option>';
		
		document.getElementById("buscarCampo").innerHTML=contenido;
		
		
		contenido=null;
		contenido = '<option value="t1.descripcion">Campaña</option>';		
		contenido += '<option value="cliente">Cliente</option>';
		contenido += '<option value="numero" selected>Factura</option>';
		contenido += '<option value="fecha">Fecha Factura</option>';
		contenido += '<option value="t1.fechaPago">Fecha Pago</option>';
		contenido += '<option value="presupuesto">Presupuesto</option>';		
		contenido += '<option value="presupuesto">Presupuesto</option>';
		contenido += '<option value="precioTotal">Total</option>';
		contenido += '<option value="aPagar">Total a Pagar</option>';
		
		document.getElementById("ordenBuscar").innerHTML=contenido;
		
		buscarFactura();
		
		
		
		document.getElementById("btnImprimir").style.visibility = "visible";
		
		
	}
	
}


function cargarListadoAgenteComercial() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoAgenteComercial;
		peticionUnica1.open("POST","ajax/mostrarAgenteVisualizar.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoAgenteComercial();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoAgenteComercial()
{	
	var consulta = "accion=cargarListadoAgenteComercial";
	
	
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	
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
				contenido += '<th>Comercial</th>';
				contenido += '<th>Cliente</th>';
				contenido += '<th>Numero</th>';
				contenido += '<th>Tipo</th>';
				contenido += '<th>Fecha</th>';
				contenido += '<th>Importe</th>';
				contenido += '<th>Pagado</th>';
				contenido += '<th>Liquidado</th>';
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
					
					
					contenido += '<tr>';
					
					
					
					
						
					if (datos[contador]["origen"]==1)
					{
						contenido += '<td align="center" style="visibility: hidden;display: none;"><input type="checkbox" id="'+datos[contador]["numero"]+'_clayma" onclick="return false;" checked  ></input></td>';
					}
					else
					{
						contenido += '<td align="center" style="visibility: hidden;display: none;"><input type="checkbox"  id="'+datos[contador]["numero"]+'_clayma" onclick="return false;"   ></input></td>';
					}	
					
					contenido += '<td id="'+datos[contador]["numero"]+'_nombre">'+datos[contador]["nombre"]+'</td>';
					contenido += '<td id="'+datos[contador]["numero"]+'_cliente">'+datos[contador]["cliente"]+'</td>';
					contenido += '<td id="'+datos[contador]["numero"]+'_numero">'+datos[contador]["numero"]+'</td>';
					
					contenido += '<td id="'+datos[contador]["numero"]+'_tipo">'+datos[contador]["tipo"]+'</td>';
					
					
					
					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="'+datos[contador]["numero"]+'_fecha" style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	
					
						
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_aPagar"  style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["precioNeto"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
						
				
					
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
					
					
					if (datos[contador]["liquidado"]==1)
					{
						contenido += '<td><input type="checkbox" id="'+datos[contador]["numero"]+'_'+datos[contador]["tipo"]+'_liquidado"  value="" onclick="cambiarValorLiquidado(this.id)" style="" checked> </input></td>';	
					}
					else
					{
						contenido += '<td><input type="checkbox" id="'+datos[contador]["numero"]+'_'+datos[contador]["tipo"]+'_liquidado"  value="" onclick="cambiarValorLiquidado(this.id)" style="" > </input></td>';	
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
	
function cambiarValorLiquidado(id)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCambiarValorLiquidado;
		peticionUnica1.open("POST","ajax/cambiarValorLiquidado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarValorLiquidado(id);
		peticionUnica1.send(query_string);
	}
}
	
function consultaCambiarValorLiquidado(id)
{	
	var consulta = "accion=cambiarValorLiquidado";
	/*¿ABONO? hay que poner otra columna (factura o abono)*/
	
	
	var datos = id.split('_');
	
	
	
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	consulta += "&anio=" + document.getElementById("anio").value;
	consulta += "&tipo=" + datos[1];
	consulta += "&numero=" + datos[0];
	consulta += "&liquidado=" + document.getElementById(id).checked;
	
	
	
	
	
	return consulta;	
}

function mostrarCambiarValorLiquidado()
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
			}
			
		}
	}						
}

function irAPreFacturaReal(numero,anio)
{
	
	document.getElementById("preFacturaNumFactura").value = numero;
	document.getElementById("preFacturaNumAnio").value = anio;
	document.getElementById("preFacturaNumClayma").value = document.getElementById("clienteOrigen").checked;
	document.getElementById("formIrAprefacturaConNum").submit();
}



function crearFacRecDiferencia(numeroFactura)
{
	document.getElementById("facRecDiferencias_numeroFactura").value =numeroFactura; 	
	document.getElementById("facRecDiferencias_clayma").value = document.getElementById("clienteOrigen").checked;
	document.getElementById("formIrAfacRecDiferencias").submit();
}

function crearFacRecSustitucion(numeroFactura)
{
	document.getElementById("facRecSustitucion_numeroFactura").value =numeroFactura; 	
	document.getElementById("facRecSustitucion_clayma").value = document.getElementById("clienteOrigen").checked;
	document.getElementById("formIrAfacRecSustitucion").submit();
}






