var peticionUnica1 = null;
var claymaG1=false;
var laCondicion="";
var guardarBusqueda = "";
var arrayAgrupamiento = [];
var refrescar = true;

function buscarFactura()
{
	arrayAgrupamiento = [];
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	guardarBusqueda = "buscarCampo=" + campoAbuscar + "|buscarTexto=" + textoAbuscar + "|ordenBuscar=" + orden + "|ordenDesc=" + desc + "|fechaInicio=" + fechaInicio + "|fechaFin=" + fechaFin + "|origen=" + document.getElementById("buscarPorOrigen").value + "|domiciliada=" + document.getElementById("domiciliada").checked;

	//var clayma = document.getElementById("clienteOrigen").checked;
	
	
	if (campoAbuscar == "t1.codigo_saldo" && textoAbuscar != "")
	{
		condicion = " where  "+campoAbuscar+" = '"+textoAbuscar+"'";	
	}
	else
	{
		//condicion = " where idCliente = codigo_saldo and  "+campoAbuscar+" like '%" + textoAbuscar + "%'";	
		condicion = " where  "+campoAbuscar+" like '%" + textoAbuscar + "%'";	
	}
		
	
	
	
	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		var laFecha = fechaInicio.replace("/","-");
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];	
	
		var laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and t1.fecha >= '"+laFecha1+"'";
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
	
	
	if (document.getElementById("domiciliada").checked==true)
	{
		condicion += " and t1.domiciliada = 1";
	}
	
	if (document.getElementById("buscarPorOrigen").value!="todos")
	{
		condicion += " and t1.origen2 = '"+document.getElementById("buscarPorOrigen").value+"'";
	}
	
	if (orden =="t1.factura")
	{
		if (desc==true)
		{
			condicion += " order by len(" + orden+") desc,"+orden + " desc";
		}
		else
		{
			condicion += " order by len(" + orden+"),"+orden;
		}
		
	}
	else
	{
		condicion += " order by " + orden;
		if (desc==true)
		{
			condicion += " desc";
		}
		
		
	}
	

	
	
	
	
	laCondicion = condicion;
	
	
	//cargarListadoFacturasSinEmitir();
	
	listadoFacturasPendientesTotal();
	
}

function listadoFacturasPendientesTotal() //js_facturaSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturasPendientesTotal;
		peticionUnica1.open("POST","ajax/mostrarListadoFacturasPendientesTotal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturasPendientesTotal();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturasPendientesTotal()
{	
	var consulta = "accion=mostrarListadoFacturasPendientesTotal";
	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	consulta += "&guardarBusqueda=" + guardarBusqueda;
	guardarBusqueda = "";
	
	
	return consulta;	
}

function mostrarListadoFacturasPendientesTotal()
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
				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=3 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioApagar">aaa</td><td style="border:none !important;"></td><td style="border:none !important; text-align:right;">Dar de baja varias a la vez:</td><td style="border:none !important;"><input type="image" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendienteMasivo2()"></td></tr>';
				
				
				
				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
					contenido += '<th align="center">Origen</th>';	
					contenido += '<th align="center">Factura</th>';	
					contenido += '<th align="center">Codigo Saldo</th>';	
					contenido += '<th>Cliente</th>';					
					contenido += '<th>Total</th>';
					contenido += '<th>Total a Pagar</th>';
					contenido += '<th>Fecha</th>';					
					contenido += '<th>Forma de Pago</th>';
					contenido += '<th></th>';

					contenido += '</tr>';
				
				var contador = 0;	
				var contraste = "";
				var mandarFoco="";
				while  (contador<datos.length)
				{  //Number(n).toLocaleString('es');
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					
					
					if (contador%2==0)
					{
						contraste = "";
					}
					else
					{						
						contraste = ' class="tablaContenidoColor" ';
					}
					
					contenido += '<tr ' + contraste + '>';
					
					//Origen: Abono, Cibles, Clayma, Correos
					if (datos[contador]["origen"]=="ABONO")
					{
						contenido += '<td align="center"  style="background: #FF0000" title="ABONO">'+datos[contador]["origen"]+'</td>';
					}
					else if (datos[contador]["origen2"]=="CIBELES")
					{
						contenido += '<td align="center"  style="background:green" title="Cibeles">'+datos[contador]["origen"]+'</td>';
					}
					else if (datos[contador]["origen2"]=="CLAYMA")
					{
						contenido += '<td align="center"  style="background: #B87240" title="Clayma">'+datos[contador]["origen"]+'</td>';
					}					
					else
					{
						contenido += '<td align="center">'+datos[contador]["origen"]+'</td>';
						
					}
					

					if (datos[contador]["origen"]=="ABONO" || datos[contador]["origen2"]=="CIBELES" || datos[contador]["origen2"]=="CLAYMA")
					{
						let fechaRegistro = new Date(datos[contador]["fecha"]["date"]);
						let fechaLimite   = new Date(fechaCambioVerifactu); 

						if (fechaRegistro>fechaLimite)
						{
							contenido += '<td align="right" id="'+datos[contador]["numeroFacturaCompleto"]+'_factura">'+datos[contador]["numeroFacturaCompleto"]+'</td>';
						}
						else
						{
							contenido += '<td align="right" id="'+datos[contador]["factura"]+'_factura">'+datos[contador]["factura"]+ '/'+anio.substr(2,2)+'</td>';
						}						
					}
					else
					{
						contenido += '<td align="center" id="'+datos[contador]["factura"]+'_factura">'+datos[contador]["factura"]+'</td>';
					}
					
					
					//contenido += '<td align="center" id="'+datos[contador]["factura"]+'_factura">'+datos[contador]["factura"]+'</td>';			
					
					contenido += '<td id="'+datos[contador]["factura"]+'_idCliente" style="visibility: hidden;display: none;">'+datos[contador]["idCliente"]+'</td>';
					
					
					contenido += '<td  align="center" id="'+datos[contador]["factura"]+'_codSaldo">'+datos[contador]["codigo_saldo"]+'</td>';
					
					contenido += '<td  id="'+datos[contador]["factura"]+'_Cliente">'+datos[contador]["cliente"]+'</td>';
					
					contenido += '<td align="right"><span style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</span></td>';
					contenido += '<td align="right"><span style="overflow:hidden; white-space: nowrap;" id="'+datos[contador]["factura"]+'_aPagar">'+Number(datos[contador]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</span></td>';
					
					
					
					contenido += '<td><span style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</span></td>';		
					
					//forma de pago
					if (datos[contador]["origen"]=='MANIPULADOS' ||datos[contador]["origen"]=='ABONO' || datos[contador]["origen"]=='REC DIFERENCIAS' || datos[contador]["origen"]=='REC SUSTITUCION')
					{
						contenido += '<td><input type="text" id="'+datos[contador]["factura"]+'_formaPago_'+datos[contador]["origen"]+'_'+datos[contador]["origen2"]+'_'+anio+'" value="" onblur="modificarFacturaPendienteMasivo(\''+datos[contador]["factura"]+'_formaPago_'+datos[contador]["origen"]+'_'+datos[contador]["origen2"]+'_'+anio+'\')"></input></td>';
						
					}
					else if (datos[contador]["origen"]=='CORREOS')
					{						
						contenido += '<td><input type="text" id="'+datos[contador]["factura"]+'_formaPago_'+datos[contador]["origen"]+'_'+datos[contador]["origen2"]+'" value="" onblur="modificarFacturaPendienteMasivo(\''+datos[contador]["factura"]+'_formaPago_CORREOS_CORREOS'+'\')"></input></td>';
					}
					
					//contenido += '<td><input type="text" id="'+datos[contador]["factura"]+'_formaPago_'+datos[contador]["origen"]+'_'+datos[contador]["origen2"]+'_'+anio+'" value=""></input></td>';
					
					if (contador==0)
					{
						mandarFoco = datos[contador]["factura"]+'_formaPago_'+datos[contador]["origen"]+'_'+datos[contador]["origen2"]+'_'+anio;
					}
					

					//modificar
					if (datos[contador]["origen"]=='MANIPULADOS')
					{
						/*contenido += '<td><input type="image" id="'+datos[contador]["factura"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendiente('+datos[contador]["factura"]+',\''+datos[contador]["origen"]+',\''+datos[contador]["origen2"]+'\')"></td>';	*/
						
						contenido += '<td><input type="image" id="'+datos[contador]["factura"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendiente('+datos[contador]["factura"]+',\''+datos[contador]["origen"]+'\',\''+datos[contador]["origen2"]+'\', \''+anio+'\')"></td>';
						
					}
					else if (datos[contador]["origen"]=='CORREOS')
					{
						contenido += '<td><input type="image" id="'+datos[contador]["factura"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaCorreospendienteDesdeTotal(\''+datos[contador]["factura"]+'\')"></td>';
					}
					else if (datos[contador]["origen"]=='ABONO')
					{
						contenido += '<td><input type="image" id="'+datos[contador]["factura"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarAbonoPendiente('+datos[contador]["factura"]+',\''+datos[contador]["origen"]+'\',\''+datos[contador]["origen2"]+'\', \''+anio+'\')"></td>';
					}
					else if (datos[contador]["origen"]=='REC DIFERENCIAS')
					{
						contenido += '<td><input type="image" id="'+datos[contador]["factura"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarRecDiferenciasPendiente('+datos[contador]["factura"]+',\''+datos[contador]["origen"]+'\',\''+datos[contador]["origen2"]+'\', \''+anio+'\')"></td>';
					}
					else if (datos[contador]["origen"]=='REC SUSTITUCION')
					{
						contenido += '<td><input type="image" id="'+datos[contador]["factura"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarRecSustitucionPendiente('+datos[contador]["factura"]+',\''+datos[contador]["origen"]+'\',\''+datos[contador]["origen2"]+'\', \''+anio+'\')"></td>';
					}

					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listadoFacturasPendientesTotal").innerHTML = contenido;				
				
				if (mandarFoco!="")
				{
					document.getElementById(mandarFoco).focus();
				}				
			}
			peticionUnica1 = null;
			listadoFacturasPendientesTotal_Sumatorio();
			
		}
	}						
}

function modificarFacturaPendienteMasivo(la_factura)
{
	

	if (document.getElementById(la_factura).value.length>3)
	{
		document.getElementById(la_factura).style.background = "green";
		
		var seguir = true;
		var contador=0;
		while (seguir==true && contador<arrayAgrupamiento.length)
		{
			if (arrayAgrupamiento[contador]==la_factura)
			{				
				seguir = false;
			}
			contador++;
		}
		if (seguir==true)
		{
			arrayAgrupamiento.push(la_factura);
		}
		
	}
	else
	{
		document.getElementById(la_factura).style.background = "white";

		var seguir = true;
		var contador=0;
		while (seguir==true && contador<arrayAgrupamiento.length)
		{
			if (arrayAgrupamiento[contador]==la_factura)
			{
				arrayAgrupamiento.splice(contador, 1); //borra la factura/abono del array
				seguir = false;
				
			}
			contador++;			
		}		
	}

}

function modificarFacturaPendienteMasivo2()
{
	if (arrayAgrupamiento.length>0)
	{
		if (confirm('¿Dar de bajar los registros cuyo campo "forma de pago" este relleno?')) 
		{
			refrescar = false;

			var contador=0;
			while (contador<arrayAgrupamiento.length)		
			{
				var datosArray = arrayAgrupamiento[contador].split('_');
				
				
				if (datosArray[2]=='CORREOS')
				{
					modificarFacturaCorreospendienteDesdeTotal(datosArray[0]);
				}
				else if (datosArray[2]=='MANIPULADOS')
				{
					modificarFacturaPendiente(datosArray[0],datosArray[2],datosArray[3],datosArray[4]);
				}
				else if (datosArray[2]=='ABONO')
				{
					modificarAbonoPendiente(datosArray[0],datosArray[2],datosArray[3],datosArray[4]);
				}
				else if (datosArray[2]=='REC DIFERENCIAS')
				{
					modificarRecDiferenciasPendiente(datosArray[0],datosArray[2],datosArray[3],datosArray[4]);
				}
				else if (datosArray[2]=='REC SUSTITUCION')
				{
					modificarRecSustitucionPendiente(datosArray[0],datosArray[2],datosArray[3],datosArray[4]);
				}
				else
				{
					alert("Error");
				}

				contador++;
			}

			
			refrescar = true;
			arrayAgrupamiento = [];
			buscarFactura();
			alert("Finalizado");
		}

		
	}
	else
	{
		alert('No hay nada para dar de baja');
	}
}


function listadoFacturasPendientesTotal_Sumatorio() //js_facturaSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturasPendientesTotal_Sumatorio;
		peticionUnica1.open("POST","ajax/mostrarListadoFacturasPendientesTotalSumatorio.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturasPendientesTotal_Sumatorio();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturasPendientesTotal_Sumatorio()
{	
	var consulta = "accion=mostrarListadoFacturasPendientesTotal";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	
	return consulta;	
}

function mostrarListadoFacturasPendientesTotal_Sumatorio()
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
				
				
				document.getElementById("sumatorioApagar").innerHTML = "Total Pendiente: " + Number(datos[0]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';				
								
			}
			peticionUnica1 = null;
			
			
		}
	}						
}



function modificarFacturaPendiente(numero, origen="", origen2="", anio) 
{
	if (document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value.trim()=="")
	{
		alert("Introducir una Forma de pago");
		document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarFacturaPendiente;
			peticionUnica1.open("POST","ajax/modificarFacturasPendientes.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarFacturaPendiente(numero, origen, origen2, anio);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarFacturaPendiente(numero, origen, origen2, anio)
{	
	var consulta = "accion=modificarFacturasPendientes";
	consulta += "&factura="+numero;
	consulta += "&formaPago=" + document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value;
	
	
	
	/*if (origen2=="") 
	{
		consulta +="&clayma=" + document.getElementById("clienteOrigen").checked;
	}
	else */
	if (origen2=="CIBELES")
	{
		claymaG1 = false;
		consulta +="&clayma=false";
	}
	else if (origen2=="CLAYMA")
	{
		claymaG1 = true;
		consulta +="&clayma=true";
	}
	
	consulta += "&anioSeleccionado="+anio;
	
	numeroFactura = numero;
	return consulta;	
}

function mostrarModificarFacturaPendiente()
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
				/*if (document.getElementById("clienteOrigen").checked==true)
				{
					
				}
				else
				{
					insertarMovivimientoFacturaCibeles(numeroFactura);
				}*/
				if (claymaG1==false)
				{
					//insertarMovivimientoFacturaCibeles(numeroFactura);
				}
				
				
				
				numeroFactura="";
				
				/*var ruta = window.location.pathname.split('/');
				var nombre = ruta[ruta.length-1];
				
				if (nombre == 'admFacturasSinCobrarTotal.php')
				{
					listadoFacturasPendientesTotal();
					//buscarFactura();
				}
				else*/
				if (refrescar == true)
				{
					//listadoFacturasPendientes();
					buscarFactura();
				}				
			}
			peticionUnica1=null;			
		}
	}						
}





function gestionInformeFacSinCobrar() //js_facturasSinCobrarTotal
{

	document.getElementById("imprimirIdCliente").value  =  document.getElementById("buscarCliente").value;
	document.getElementById("imprimirFechaInicio").value  =  document.getElementById("buscarFechaInicio").value;
	document.getElementById("imprimirFechaFin").value  =  document.getElementById("buscarFechaFin").value;
	document.getElementById("imprimirOrden").value  =  document.getElementById("orden").value;
	document.getElementById("imprimirDesc").value  =  document.getElementById("ordenDesc").checked;
	document.getElementById("imprimirDomiciliada").value  =  document.getElementById("domiciliada").checked;
	
	document.getElementById("formImprimirInforme").submit();
}

function modificarFacturaCorreospendienteDesdeTotal(factura) //js_facturasSinCobrarTotal
{
	
	if (document.getElementById(factura+"_formaPago_CORREOS_CORREOS").value.trim()==""||document.getElementById(factura+"_formaPago_CORREOS_CORREOS").value==null)
	{
		alert("Rellenar la Forma de Pago");
		document.getElementById(factura+"_formaPago_CORREOS_CORREOS").focus();		
	}
	else
	{
	
		//var elImporte = 
		
		var codigoCliente = document.getElementById(factura+"_idCliente").innerHTML;	
		var aPagar = document.getElementById(factura+"_aPagar").innerHTML.replace(' €','').replace('.','').replace(',','.');
		var formaPago = document.getElementById(factura+"_formaPago_CORREOS_CORREOS").value;
		var numeroOficial = document.getElementById(factura+"_factura").innerHTML;
		
		var fecha="";
		
		insertarMovimientoPF(codigoCliente,fecha,formaPago,aPagar,numeroOficial);	
		
		modificarDatosPFenCliente(codigoCliente, fecha, aPagar);
		
		modificarFacturaCorreosPendiente2(factura);
		
	}
}




function modificarAbonoPendiente(numero, origen="", origen2="", anio) 
{
	if (document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value.trim()=="")
	{
		alert("Introducir una Forma de pago");
		document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarAbonoPendiente;
			peticionUnica1.open("POST","ajax/modificarAbonoPendientes.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarAbonoPendiente(numero,origen, origen2, anio);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarAbonoPendiente(numero, origen, origen2, anio)
{	
	var consulta = "accion=modificarAbonosPendientes";
	consulta += "&abono="+numero;
	consulta += "&formaPago=" + document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value;	
	
	if (origen2=="CIBELES")
	{
		claymaG1 = false;
		consulta +="&clayma=false";
	}
	else if (origen2=="CLAYMA")
	{
		claymaG1 = true;
		consulta +="&clayma=true";
	}
	
	consulta += "&anioSeleccionado=" + anio;
	
	numeroFactura = numero;
	return consulta;	
}

function mostrarModificarAbonoPendiente()
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
				numeroFactura="";				
				
				if (refrescar==true)
				{
					buscarFactura();
				}					
				
			}
			peticionUnica1=null;			
		}
	}						
}


function modificarRecDiferenciasPendiente(numero, origen="", origen2="", anio) 
{
	if (document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value.trim()=="")
	{
		alert("Introducir una Forma de pago");
		document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarRecDiferenciasPendiente;
			peticionUnica1.open("POST","ajax/modificarRecDiferenciasPendientes.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarRecDiferenciasPendiente(numero,origen, origen2, anio);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarRecDiferenciasPendiente(numero, origen, origen2, anio)
{	
	var consulta = "accion=modificarRecDiferenciasPendientes";
	consulta += "&factura="+numero;
	consulta += "&formaPago=" + document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value;	
	
	if (origen2=="CIBELES")
	{
		claymaG1 = false;
		consulta +="&clayma=false";
	}
	else if (origen2=="CLAYMA")
	{
		claymaG1 = true;
		consulta +="&clayma=true";
	}
	
	consulta += "&anioSeleccionado=" + anio;
	
	numeroFactura = numero;
	return consulta;	
}

function mostrarModificarRecDiferenciasPendiente()
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
				numeroFactura="";				
				
				if (refrescar==true)
				{
					buscarFactura();
				}					
				
			}
			peticionUnica1=null;			
		}
	}						
}


function modificarRecSustitucionPendiente(numero, origen="", origen2="", anio) 
{
	if (document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value.trim()=="")
	{
		alert("Introducir una Forma de pago");
		document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarRecSustitucionPendiente;
			peticionUnica1.open("POST","ajax/modificarRecSustitucionPendientes.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarRecSustitucionPendiente(numero,origen, origen2, anio);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarRecSustitucionPendiente(numero, origen, origen2, anio)
{	
	var consulta = "accion=modificarRecSustitucionPendientes";
	consulta += "&factura="+numero;
	consulta += "&formaPago=" + document.getElementById(numero+"_formaPago_"+origen+"_"+origen2+"_"+anio).value;	
	
	if (origen2=="CIBELES")
	{
		claymaG1 = false;
		consulta +="&clayma=false";
	}
	else if (origen2=="CLAYMA")
	{
		claymaG1 = true;
		consulta +="&clayma=true";
	}
	
	consulta += "&anioSeleccionado=" + anio;
	
	numeroFactura = numero;
	return consulta;	
}

function mostrarModificarRecSustitucionPendiente()
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
				numeroFactura="";				
				
				if (refrescar==true)
				{
					buscarFactura();
				}					
				
			}
			peticionUnica1=null;			
		}
	}						
}

function insertarMovimientoAbonoCibeles(numAbono) //js_facturasSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarMovimientoAbonoCibeles;
		peticionUnica1.open("POST","ajax/insertarMovimientoAbono.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarMovimientoAbonoCibeles(numAbono);
		peticionUnica1.send(query_string);
	}
	
}

function consultaInsertarMovimientoAbonoCibeles(numAbono)
{	
	var consulta = "accion=insertarMovimientoAbono";
	consulta += "&abono="+numAbono;
	//consulta +="&clayma=" + document.getElementById("clienteOrigen").checked;
	
	if (claymaG1==false)
	{		
		consulta +="&clayma=false";
	}
	else if (claymaG1==true)
	{		
		consulta +="&clayma=true";
	}
		
	return consulta;	
}

function mostrarInsertarMovimientoAbonoCibeles()
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
				//alert(peticionUnica1.responseText);
			}
		}
	}						
}

function modificarFacturaCorreosPendiente2(factura)	//js_facturasCorreosPendientes			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarFacturaCorreosPendiente2;
		peticionUnica1.open("POST","ajax/modificarFacturasCorreosPendientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarFacturaCorreosPendiente2(factura);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarFacturaCorreosPendiente2(factura)
{	
	var consulta = "accion=modificarFacturaCorreosPendientes";
	
	consulta +="&factura=" + factura; 
	
	consulta +="&formaPago=" + document.getElementById(factura+"_formaPago_CORREOS_CORREOS").value;
	
	return consulta;	
}


function mostrarModificarFacturaCorreosPendiente2()
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
				/*var ruta = window.location.pathname.split('/');
				var nombre = ruta[ruta.length-1];
				
				if (nombre == 'admFacturasSinCobrarTotal.php')
				{
					listadoFacturasPendientesTotal();
					//buscarFactura();
				}
				else*/
				if (refrescar == true)				
				{
					//cargarListadoFacturasCorreosPendientes();
					buscarFactura();
				}
			}
			peticionUnica1=null;
				
		}
	}						
}

function gestionImprimir()
{
	document.getElementById("imprimirCondicion").value = laCondicion;
	document.getElementById("imprimirDomiciliado").value = document.getElementById("domiciliada").checked;
	document.getElementById("imprimirFechaInicio").value = document.getElementById("buscarFechaInicio").value;
	document.getElementById("imprimirFechaFin").value = document.getElementById("buscarFechaFin").value;
	
	document.getElementById("formImprimirInforme").submit();
}

function gestionExportarExcelFacturaSinCobrar()
{	
	document.getElementById("exportarExcel_Condicion").value = laCondicion;
	document.getElementById("exportarExcel_Domiciliado").value = document.getElementById("domiciliada").checked;
	document.getElementById("exportarExcel_FechaInicio").value = document.getElementById("buscarFechaInicio").value;
	document.getElementById("exportarExcel_FechaFin").value = document.getElementById("buscarFechaFin").value;
	
	document.getElementById("formExcelFacturasSinCobrar").submit();
}