var peticionUnica1 = null;

var clienteInicial = null;
var anioSeleccionado="";
var busquedaPantallaAnterior="";
var unError = "";

function guardarValorClienteInicial()
{
	clienteInicial = document.getElementById("clientes").value;
}

function verProvisionPrefactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerProvisionPrefactura;
		peticionUnica1.open("POST","ajax/verProvisionPreFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerProvisionPrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaVerProvisionPrefactura()
{	
	var consulta = "accion=verProvision";	
	consulta += "&presupuesto="+document.getElementById("numPresupuesto").innerHTML;	
	
	return consulta;	
}

function mostrarVerProvisionPrefactura()
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
				var contenido = "";
				var datos = new Array;
				var importeTotal = 0;
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
				if (datos != "")
				{	
					if (datos.length<=0)
					{
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{							
						var contador = 0;
						while  (contador<datos.length)
						{
							//contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["importe"]+'</option>';	
							importeTotal = importeTotal + parseFloat(datos[contador]["importe"]);
							contador++;
						}							
						//document.getElementById("provision").innerHTML = contenido;						
					}
				}
				//document.getElementById("provision").innerHTML = contenido;
				document.getElementById("provisionTotal").value = importeTotal.toFixed(2);
			}
			peticionUnica1=null;
		}
	}						
}

function verDatosUnPresupuesto() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosUnPresupuesto;
		peticionUnica1.open("POST","ajax/verDatosUnPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosUnPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosUnPresupuesto()
{	
	var consulta = "accion=verDatosUnPresupuesto";
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;	
	return consulta;	
}

function mostrarVerDatosUnPresupuesto()
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
				
				if (datos[0]["clayma"]=="1")
				{
					document.getElementById("clienteOrigen").checked = true;
				}
				else
				{
					document.getElementById("clienteOrigen").checked = false;
				}
				
				document.getElementById("clientes").value = datos[0]["codigo_saldo"];
				
				valorNumero = datos[0]["codigo_saldo"];
				
				document.getElementById("fechaFactura").value = datos[0]["fecha"]["date"].substring(0,10);
				document.getElementById("pedidoCliente").value = datos[0]["pedcli"];
				document.getElementById("cantidad").value = datos[0]["cantidad"];
				document.getElementById("formaPago").value = datos[0]["idFormaPagoCliente"];				
				document.getElementById("campana").value = datos[0]["campana"];
				
				document.getElementById("numCuenta").value = datos[0]["nuestraCuenta"];
				
				if (datos[0]["detallada"]=="1")
				{
					document.getElementById("detallada").checked = true;
				}
				else
				{
					document.getElementById("detallada").checked = false;
				}
				

				/*
				if (datos[0]["prefactura"]==1)
				{
					document.getElementById("botonModificarPresupuesto").style.display = "none";
					document.getElementById("botonModificarPresupuesto").style.visibility = "hidden";
					
					//document.getElementById("botonEmitirPreFactura").style.display = "table-row";					
					//document.getElementById("botonEmitirPreFactura").style.visibility = "visible";
					
					
				}
				else*/
				{
					document.getElementById("botonModificarPresupuesto").style.display = "table-row";
					document.getElementById("botonModificarPresupuesto").style.visibility = "visible";
					
					//document.getElementById("botonEmitirPreFactura").style.display = "none";					
					//document.getElementById("botonEmitirPreFactura").style.visibility = "hidden";
					
				}
				
			}
			peticionUnica1=null;
		}
	}						
}





function copiarPresupuestoAFacturaDetalleTemporal(opcion) //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCopiarPresupuestoAFacturaDetalleTemporal;
		peticionUnica1.open("POST","ajax/copiarPresupuestoAFacturaDetalleTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCopiarPresupuestoAFacturaDetalleTemporal(opcion);
		peticionUnica1.send(query_string);
	}
}

function consultaCopiarPresupuestoAFacturaDetalleTemporal(opcion)
{	
	var consulta = "accion=copiarPresupuestoAFacturaDetalleTemporal";
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;
	consulta += "&opcion="+opcion;	
	return consulta;	
}

function mostrarCopiarPresupuestoAFacturaDetalleTemporal()
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
				cargarDetallesPrefactura();
			}
			peticionUnica1=null;
		}
	}						
}







function cambiarClienteEnPrefactura()//presupuestoCambioModal //js_prefactura
{	
	
	if (confirm('¿Cambiar Cliente?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCambiarClienteEnPrefactura;
			peticionUnica1.open("POST","ajax/cambiarClienteEnPFClayma.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCambiarClienteEnPrefactura();
			peticionUnica1.send(query_string);
		}
		
		clienteInicial = document.getElementById("clientes").value;
	}
	else
	{
		document.getElementById("clientes").value = clienteInicial;
	}
	
	
	
}

function consultaCambiarClienteEnPrefactura()
{	
	var consulta = "accion=cambiarClienteEnPFClayma";	
	
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;	
	consulta += "&idCliente=" + document.getElementById("clientes").value;		
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	
	return consulta;	
}

function mostrarCambiarClienteEnPrefactura()
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
			comprobarIvaCliente(document.getElementById("clientes").value);
		}
		peticionUnica1 = null;
	}						
}

function comprobarIvaCliente(id) //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarIvaCliente;
		peticionUnica1.open("POST","ajax/verIvaCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarIvaCliente(id);
		peticionUnica1.send(query_string);
	}
}
function consultaComprobarIvaCliente(id)
{	
	var consulta = "accion=verSiTieneFirma";	
	consulta+="&idCliente="+id;
	consulta+="&clayma="+document.getElementById("clienteOrigen").checked;
	
	return consulta;	
}

function mostrarComprobarIvaCliente()
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
				
				if (datos != "")
				{
					if (datos[0]["sinIva"]==1)
					{
						document.getElementById("sinIvaCliente").value="sinIva";
					}
					else
					{
						document.getElementById("sinIvaCliente").value="conIva";
					}

					if (datos[0]["retencion"]==1)
					{
						document.getElementById("conIRPFCliente").value="conIRPF";
					}
					else
					{
						document.getElementById("conIRPFCliente").value="sinIRPF";
					}
				}				
			}
			peticionUnica1=null;
			calcularTotalTodoPreFactura();
		}
	}						
}

function noSeFactura() //js_prefactura
{
	/*if (confirm("Confirmar ¿El presupuesto "+document.getElementById("numPresupuesto").innerHTML+" no se va a facturar?")) 
	{
		noSeFactura2();
	}	*/
	
	$("#noSeFacturaModal").modal('show');
}

function noSeFactura2()//js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarNoSeFactura2;
		peticionUnica1.open("POST","ajax/modificarNumNoFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaNoSeFactura2();
		peticionUnica1.send(query_string);
	}
}
function consultaNoSeFactura2()
{	
	var consulta = "accion=noSeFactura";	
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;
	consulta += "&observacion=" + document.getElementById("observacionNoFacturableModal").value;
	
	return consulta;	
}

function mostrarNoSeFactura2()
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
				alert("El numero de la NO factura es: "+peticionUnica1.responseText);		
				gestionarVolverPrefacturaBorrarTemporal(1);
			}	
			peticionUnica1 = null;
		}
	}						
}

function gestionarVolverPrefacturaBorrarTemporal(valor) //js_prefactura
{
	if (valor==1)
	{	
		eliminarPresupuestoDeFacturaDetalleTemporal(document.getElementById("numPresupuesto").innerHTML);
		eliminarPresupuestoDeFacturaTemporal(document.getElementById("numPresupuesto").innerHTML);
	}
	else if (valor==2)
	{
		modificarFacturaTemporal();
	}
	
	var parametros = busquedaPantallaAnterior.split("|||");


	if (document.getElementById("clienteOrigen").checked)
	{
		location.href = 'admEmisionFacturasPendientes.php?clayma=1&buscarCampo=' + parametros[0] + '&buscarTexto=' + parametros[1] + '&ordenBuscar=' + parametros[2] + '&buscarDesc=' + parametros[3];
	}
	else
	{
		location.href = 'admEmisionFacturasPendientes.php?clayma=0&buscarCampo=' + parametros[0] + '&buscarTexto=' + parametros[1] + '&ordenBuscar=' + parametros[2] + '&buscarDesc=' + parametros[3];
	}	
}

function modificarFacturaTemporal()//presupuestoCambioModal //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarFacturaTemporal;
		peticionUnica1.open("POST","ajax/modificarFacturaTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarFacturaTemporal();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarFacturaTemporal()
{	
	var consulta = "accion=modificarFacturaTemporal";	
	
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;		
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	consulta += "&idCliente=" + document.getElementById("clientes").value;	
	consulta += "&pedido=" + document.getElementById("pedidoCliente").value;
	consulta += "&cantidad=" + document.getElementById("cantidad").value;
	consulta += "&idFormaPago=" + document.getElementById("formaPago").value;
	consulta += "&campana=" + document.getElementById("campana").value;
	consulta += "&detallada=" + document.getElementById("detallada").checked;
	consulta += "&neto=" + document.getElementById("Neto").value;
	consulta += "&iva=" + document.getElementById("iva").value;
	consulta += "&irpf=" + document.getElementById("irpf").value;
	consulta += "&total=" + document.getElementById("total").value;
	consulta += "&provision=" + document.getElementById("provisionTotal").value;
	consulta += "&aPagar=" + document.getElementById("aPagar").value;
	
	return consulta;	
}

function mostrarModificarFacturaTemporal()
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
		peticionUnica1 = null;
	}						
}

function previsualizarFactura() //js_prefactura
{
	if (document.getElementById("clienteOrigen").checked)
	{		
		document.getElementById("previsualizarClayma_presupuesto").value = document.getElementById("numPresupuesto").innerHTML;		
		
		var combo = document.getElementById("clientes");
		var selected = combo.options[combo.selectedIndex].text;			
		
		document.getElementById("previsualizarClayma_cliente").value = selected;
		document.getElementById("previsualizarClayma_idCliente").value = document.getElementById("clientes").value;		
		
		document.getElementById("previsualizarClayma_fecha").value = document.getElementById("fechaFactura").value;
		document.getElementById("previsualizarClayma_pedido").value = (document.getElementById("pedidoCliente").value);
		
		
		
		
		var valor = 0;
		if (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null)
		{
			valor = 0;
		}
		else
		{
			valor = document.getElementById("cantidad").value;
		}
		
		document.getElementById("previsualizarClayma_cantidad").value = valor;
		document.getElementById("previsualizarClayma_formaPago").value = document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;
		
		document.getElementById("previsualizarClayma_nuestraCuenta").value = document.getElementById("numCuenta").value;
		
		document.getElementById("previsualizarClayma_campana").value = (document.getElementById("campana").value);
		
		valor = 0;
		if (document.getElementById("detallada").checked)
		{
			valor = 1;
		}
		
		document.getElementById("previsualizarClayma_detallada").value = valor;
		document.getElementById("previsualizarClayma_neto").value = document.getElementById("Neto").value;
		document.getElementById("previsualizarClayma_iva").value = document.getElementById("iva").value;
		document.getElementById("previsualizarClayma_irpf").value = document.getElementById("irpf").value;
		document.getElementById("previsualizarClayma_total").value = document.getElementById("total").value;
		document.getElementById("previsualizarClayma_provision").value = document.getElementById("provisionTotal").value;
		document.getElementById("previsualizarClayma_aPagar").value = document.getElementById("aPagar").value;
					
		document.getElementById("formPrevisualizarFacturaClayma").submit();
		
	}
	else
	{
		document.getElementById("previsualizar_presupuesto").value = document.getElementById("numPresupuesto").innerHTML;		
		
		var combo = document.getElementById("clientes");
		var selected = combo.options[combo.selectedIndex].text;			
		
		document.getElementById("previsualizar_cliente").value = selected;
		document.getElementById("previsualizar_idCliente").value = document.getElementById("clientes").value;		
		
		document.getElementById("previsualizar_fecha").value = document.getElementById("fechaFactura").value;
		document.getElementById("previsualizar_pedido").value = (document.getElementById("pedidoCliente").value);
		
		var valor = 0;
		if (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null)
		{
			valor = 0;
		}
		else
		{
			valor = document.getElementById("cantidad").value;
		}
		
		document.getElementById("previsualizar_cantidad").value = valor;
		document.getElementById("previsualizar_formaPago").value = document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;
		
		document.getElementById("previsualizar_nuestraCuenta").value = document.getElementById("numCuenta").value;
		
		document.getElementById("previsualizar_campana").value = (document.getElementById("campana").value);
		
		valor = 0;
		if (document.getElementById("detallada").checked)
		{
			valor = 1;
		}
		
		document.getElementById("previsualizar_detallada").value = valor;
		document.getElementById("previsualizar_neto").value = document.getElementById("Neto").value;
		document.getElementById("previsualizar_iva").value = document.getElementById("iva").value;
		document.getElementById("previsualizar_irpf").value = document.getElementById("irpf").value;
		document.getElementById("previsualizar_total").value = document.getElementById("total").value;
		document.getElementById("previsualizar_provision").value = document.getElementById("provisionTotal").value;
		document.getElementById("previsualizar_aPagar").value = document.getElementById("aPagar").value;
					
		document.getElementById("formPrevisualizarFactura").submit();		
	}
}

function grabarFactura(prefactura=0) //
{	
	prefactura = 0; //esta linea se pone al no utilizar la factura-prefactura
	if (document.getElementById("clientes").value<=0)
	{
		alert("Elegir un Cliente");
		document.getElementById("clientes").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarFactura;
			peticionUnica1.open("POST","ajax/anadirFactura.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGrabarFactura(prefactura);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGrabarFactura(prefactura)
{	
	var consulta = "accion=anadirFactura";
	
	consulta += "&presupuesto="+document.getElementById("numPresupuesto").innerHTML;
	consulta += "&inicial="+document.getElementById("inicialComercial").innerHTML;
	//consulta += "&cliente="+document.getElementById("cliente").innerHTML;
	
	var combo = document.getElementById("clientes");
	var selected = combo.options[combo.selectedIndex].text;
	
	
	selected = selected.replaceAll('%','%25');
	selected = selected.replaceAll('&','%26');
	
	
	consulta += "&cliente="+selected; 
	consulta += "&clayma="+document.getElementById("clienteOrigen").checked;
	
	consulta += "&fecha=" + document.getElementById("fechaFactura").value;
	
	consulta += "&pedidoCliente=" + document.getElementById("pedidoCliente").value;	
	
	if (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null)
	{
		consulta += "&cantidad=0";
	}
	else
	{
		consulta += "&cantidad=" + document.getElementById("cantidad").value;
	}	

	consulta += "&formaPago=" + document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;	
	
	consulta += "&numCuenta=" + document.getElementById("numCuenta").value;	
	
	consulta += "&campana=" + document.getElementById("campana").value;	
	
	if (document.getElementById("detallada").checked)
	{
		consulta += "&detallada=1";	
	}
	else
	{
		consulta += "&detallada=0";	
	}
	
	consulta += "&neto=" + document.getElementById("Neto").value;	
	consulta += "&iva=" + document.getElementById("iva").value;	
	consulta += "&irpf=" + document.getElementById("irpf").value;	
	consulta += "&total=" + document.getElementById("total").value;	
	consulta += "&provision=" + document.getElementById("provisionTotal").value;	
	consulta += "&aPagar=" + document.getElementById("aPagar").value;
	
	consulta += "&prefactura="+ prefactura;
	
	return consulta;	
}

function mostrarGrabarFactura()
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
				verNumFacturaPorPresupuesto(document.getElementById("numPresupuesto").innerHTML);
				
				document.getElementById("modalNumFactura").innerHTML = numFactura;
				
				grabarFacturaDetalle();
				eliminarDetallesFacturaTemporal(document.getElementById("numPresupuesto").innerHTML);
				
				if (document.getElementById("provisionTotal").value!=0)
				{				
					modificarProvisionNumFacturaPorPresupuesto();
				}
				if (unError == "Error En Verifactu")
				{
					unError="";
					alert("ERROR EN VERIFACTU\nSi se ha generado la factura, NO ENVIARSELO AL CLIENTE\nRevisar lo que ha pasado");
				}
				else
				{
					if (document.getElementById("clienteOrigen").checked)
					{
						irAImprimirFacturaClayma(numFactura, anioSeleccionado);
					}
					else
					{
						irAImprimirFactura(numFactura, anioSeleccionado);
					}
				}
				
				
				numFactura="";
				anioSeleccionado="";
				location.href='admEmisionFacturasPendientes.php';				
			}
			peticionUnica1=null;
		}
	}						
}

function verNumFacturaPorPresupuesto(numPresupuesto) //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerNumFacturaPorPresupuesto;
		peticionUnica1.open("POST","ajax/mostrarNumFacturaPorPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerNumFacturaPorPresupuesto(numPresupuesto);
		peticionUnica1.send(query_string);
	}
}

function consultaVerNumFacturaPorPresupuesto(numPresupuesto)
{	
	var consulta = "accion=verNumFactura";	
	consulta += "&numPresupuesto="+numPresupuesto;		
	consulta +="&clayma="+document.getElementById("clienteOrigen").checked;
	
	return consulta;	
}

function mostrarVerNumFacturaPorPresupuesto()
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
				
				if (datos != "")
				{
					numFactura = datos[0]["numero"];
					anioSeleccionado=datos[0]["anioFactura"];
					facCompleto = datos[0]["numeroFacturaCompleto"];
				}
				else
				{
					numFactura="";
					anioSeleccionado="";
					facCompleto="";
				}
			}
			peticionUnica1=null;
		}
	}						
}

function grabarFacturaDetalle() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGrabarFacturaDetalle;
		peticionUnica1.open("POST","ajax/anadirFacturaDetalle.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGrabarFacturaDetalle();
		peticionUnica1.send(query_string);
	}
}

function consultaGrabarFacturaDetalle()
{	
	var consulta = "accion=anadirFacturaDetalle";
	
	consulta += "&numFactura="+document.getElementById("modalNumFactura").innerHTML;
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	//consulta += "&total="+document.getElementById("numPresupuesto").value;
	consulta += "&clayma="+document.getElementById("clienteOrigen").checked;
	consulta += "&anioSeleccionado=" + anioSeleccionado;
	
	return consulta;	
}

function mostrarGrabarFacturaDetalle()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else if (peticionUnica1.responseText.includes("ErrorMensaje"))
			{
				unError = "Error En Verifactu";
				alert(unError);
			}
			else
			{
			}
			peticionUnica1=null;
		}
	}						
}




function modificarProvisionNumFacturaPorPresupuesto() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarProvisionNumFacturaPorPresupuesto;
		peticionUnica1.open("POST","ajax/modificarProvisionNumFacturaPorPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarProvisionNumFacturaPorPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarProvisionNumFacturaPorPresupuesto()
{	
	var consulta = "accion=modificarProvisionNumFacturaPorPresupuesto";
	
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;	
	consulta +="&numFactura=" +numFactura;
	consulta +="&anioSeleccionado=" + anioSeleccionado;
	consulta +="&facCompleto=" + facCompleto;
	
	return consulta;	
}

function mostrarModificarProvisionNumFacturaPorPresupuesto()
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
			peticionUnica1 = null;
		}
	}						
}

function calcularImporteFranqueoDesdePrefactura1() //js_prefactura
{	
	if (document.getElementById("clienteOrigen").checked)
	{
		document.getElementById("cliente_importeFranqueoModal").value=0;
	}
	else
	{
		document.getElementById("cliente_importeFranqueoModal").value = document.getElementById("clientes").value;
	}
	
	$("#verImporteFranqueoModal").modal('show');
}

function anadirDetallePrefactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePrefactura;
		peticionUnica1.open("POST","ajax/anadirDetallePrefactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePrefactura()
{	
	var consulta = "accion=anadirDetalle";
	
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	
	consulta += "&proceso=" + document.getElementById("conceptoNuevoTemp").value;	
	
	/*consulta += "&descripcion=" + document.getElementById("descripcionDetalleNuevoTemp").value;	
	consulta += "&nota=" + document.getElementById("notaDetalleNuevoTemp").value;	*/
	
	
	consulta +="&descripcion=" + reemplazarSimbolos(document.getElementById("descripcionDetalleNuevoTemp").value);
	consulta +="&nota=" + reemplazarSimbolos(document.getElementById("notaDetalleNuevoTemp").value);
	
	
	
	
	var unidad = document.getElementById("unidadesDetalleNuevoTemp").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}
	consulta += "&unidad=" + unidad;
	
	var precio = document.getElementById("precioDetalleNuevoTemp").value;	
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}
	
	consulta += "&precio=" + precio;

	consulta += "&exentoIVA=" + document.getElementById("exentoIVA").checked;
	
	return consulta;	
}

function mostrarAnadirDetallePrefactura()
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
				peticionUnica1=null;
				cargarDetallesPrefactura();
				
				document.getElementById("conceptoNuevoTemp").value="";
				document.getElementById("descripcionDetalleNuevoTemp").value="";
				document.getElementById("notaDetalleNuevoTemp").value="";
				document.getElementById("excentoIVA").checked="false";
				
			}
			peticionUnica1=null;
		}
	}						
}

function calcularImporteFranqueoDesdePrefactura() //js_prefactura
{
	if (document.getElementById("fechaInicio_importeFranqueoModal").value=="")
	{
		alert("Introducir una fecha de inicio.");
		document.getElementById("fechaInicio_importeFranqueoModal").focus();
	}
	else if (document.getElementById("fechaFin_importeFranqueoModal").value=="")
	{
		alert("Introducir una fecha fin.");
		document.getElementById("fechaFin_importeFranqueoModal").focus();
	}
	else if (document.getElementById("clientes").value<=0)
	{
		alert("Introducir una fecha fin.");
		document.getElementById("clientes").focus();
	}
	else if (document.getElementById("cliente_importeFranqueoModal").value<=0)
	{
		alert("Seleccionar un cliente");
		document.getElementById("cliente_importeFranqueoModal").focus();
	}
	else
	{
		calcularImporteFranqueoDesdePrefactura2();
	}
}


function calcularImporteFranqueoDesdePrefactura2() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCalcularImporteFranqueoDesdePrefactura2;
		peticionUnica1.open("POST","ajax/calcularImporteFranqueoDesdePrefactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCalcularImporteFranqueoDesdePrefactura2();
		peticionUnica1.send(query_string);
	}
}

function consultaCalcularImporteFranqueoDesdePrefactura2()
{	
	var consulta = "accion=mostrarListadoFacturasPendientesTotal";
	
	//consulta += "&idCliente=" + document.getElementById("clientes").value;
	consulta += "&fechaInicio=" + document.getElementById("fechaInicio_importeFranqueoModal").value;
	consulta += "&fechaFin=" + document.getElementById("fechaFin_importeFranqueoModal").value;
	//consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	consulta += "&idClienteCibeles=" + document.getElementById("cliente_importeFranqueoModal").value;
	consulta += "&extension=" + document.getElementById("extension_importeFranqueoModal").value;
	consulta += "&ot=" + document.getElementById("numPresupuesto").innerHTML;
	
	return consulta;	
}

function mostrarCalcularImporteFranqueoDesdePrefactura2()
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
						
				document.getElementById("precioDetalleNuevoTemp").value=datos[0]["importe"];
				document.getElementById("unidadesDetalleNuevoTemp").value = 1;
				document.getElementById("conceptoNuevoTemp").value = "Franqueo";

				$("#verImporteFranqueoModal").modal('hide');
			}
			
		}
		peticionUnica1 = null;
	}						
}

function gestionarCambioClientePrefactura(destino) //js_prefactura 
{
	
	if (confirm('¿Cambiar Cliente?')) 
	{
	
		gestionDeCargarClientesListado(destino);

		document.getElementById(destino).value=0;
	}
}




function verDatosUnPresupuestoTemporal(numPresupuesto) //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosUnPresupuestoTemporal;
		peticionUnica1.open("POST","ajax/verDatosUnPresupuestoTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosUnPresupuestoTemporal(numPresupuesto);
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosUnPresupuestoTemporal(numPresupuesto)
{	
	var consulta = "accion=verDatosUnPresupuesto";
	consulta += "&numPresupuesto=" + numPresupuesto;
	return consulta;	
}

function mostrarVerDatosUnPresupuestoTemporal()
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
				
				document.getElementById("pedidoCliente").value = datos[0]["pedido"];
				document.getElementById("cantidad").value = datos[0]["cantidad"];
				document.getElementById("formaPago").value = datos[0]["formaPago"];				
				document.getElementById("campana").value = datos[0]["descripcion"];
				
				if (datos[0]["detallada"]=="1")
				{
					document.getElementById("detallada").checked = true;
				}
				else
				{
					document.getElementById("detallada").checked = false;
				}			
				
			}	
			peticionUnica1 = null;
		}
	}						
}

