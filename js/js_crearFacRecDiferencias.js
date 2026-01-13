var peticionUnica1 = null;

var clienteInicial = null;
var anioSeleccionado="";
var busquedaPantallaAnterior="";
var unError = "";



function cargarDetallesRegistrosFacRec() 
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarDetallesRegistrosFacRec;
		peticionUnica0.open("POST","ajax/cargarDetalleRecTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesRegistrosFacRec();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarDetallesRegistrosFacRec()
{
	var consulta = "accion=cargarDetallesRegistrosFacRec";
	consulta += "&facturaOriginal=" + document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&clayma=" + document.getElementById("clayma").innerHTML;
	return consulta;	
}

function mostrarCargarDetallesRegistrosFacRec()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			if (peticionUnica0.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica0.responseText);
			}
			else
			{				 
				var datos = new Array;				
				datos = JSON.parse(peticionUnica0.responseText);
				unArray = [];
				var contador=0;
				var contenido = "";
				
				while  (contador<datos.length) 
				{					
					contenido += '<tr><td>Concepto:</td><td colspan="5"> <input type="text" id="'+datos[contador]["id"]+'_procesoTemp" value="'+datos[contador]["concepto"]+'" style="width:100%"></input></td>';					
					
					contenido += '<td ROWSPAN="2" align="center"><input type="image" id="'+datos[contador]["id"]+'_modificarDetalleTemp" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarDetalleRecTemporal('+datos[contador]["id"]+')" ></td></tr>';
					
					contenido += '<tr><td>Descripcion:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_descripcionDetalleTemp" value="'+datos[contador]["descripcion"]+'" style="width:100%"></input></td></tr>';
						
					contenido += '<tr><td>Nota Cibeles:</td><td colspan="4"><input type="text" id="'+datos[contador]["id"]+'_notaDetalleTemp" value="'+datos[contador]["notaCibeles"]+'" style="width:100%"></input></td>';
					
					if (datos[contador]["exentoIVA"]==true)
						contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_exentoIVADetalleTemp" checked></input></td>';
					else
						contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_exentoIVADetalleTemp"></input></td>';
				
									


					contenido += '<td ROWSPAN="2"><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalleTemp" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarDetalleRecTemporal('+datos[contador]["id"]+')" ></td></tr>';

					contenido += '<tr>'; 
					
					var valor = datos[contador]["unidades"];
					if (datos[contador]["unidades"].startsWith('.'))
					{
						valor = "0" + datos[contador]["unidades"];
					}
					
					contenido += '<td>Unidad:</td><td><input type="number" id="'+datos[contador]["id"]+'_unidadesDetalleTemp" value="'+valor+'" onkeyup="calcularTotal('+datos[contador]["id"]+')"></input></td>';
										
					valor = datos[contador]["precio"];
					if (datos[contador]["precio"].startsWith('.'))
					{
						valor = "0" + datos[contador]["precio"];
					}					
					
					contenido += '<td>Precio:</td><td><input type="number" id="'+datos[contador]["id"]+'_precioDetalleTemp" value="'+valor+'" onkeyup="calcularTotal('+datos[contador]["id"]+')"></input></td>';
					
					valor = datos[contador]["total"];
					if (datos[contador]["total"].startsWith('.'))
					{
						valor = "0" + datos[contador]["total"];
					}					
					
					
					contenido += '<td>Total:</td><td><input type="number" id="'+datos[contador]["id"]+'_totalDetalleTemp" value="'+valor+'"></input></td>';
					//contenido += '<td>Total:</td><td><input type="number" id="'+datos[contador]["id"]+'_totalDetalleTemp" value="'+valor+'"></input>&nbsp<input type="checkbox" id="'+datos[contador]["id"]+'_IVATemp" onChange="calcularTotal('+datos[contador]["id"]+')">IVA</input></td>';
					//document.getElementById(datos[contador]["id"]+'_IVATemp').checked = document.getElementsByTagName("ivaIncluido");
					
					contenido += '<tr><td colspan="7" style="border:0px;"><hr></td></tr>';

					contenido += '</tr>';
					
					unArray.push(datos[contador]["id"]);
					
					contador++;
				}				
				
				document.getElementById("detallesPrefactura").innerHTML=contenido;				
				
				calcularTotalTodoPreFactura();				
			}
			
			peticionUnica0=null;
		}
	}						
}

function anadirDetallePrefactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePrefactura;
		peticionUnica1.open("POST","ajax/anadirDetalleRecTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePrefactura()
{	
	var consulta = "accion=anadirDetalle";
	
	
	consulta += "&facturaOriginal=" + document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&clayma=" + document.getElementById("clayma").innerHTML;
	
	consulta += "&proceso=" + document.getElementById("conceptoNuevoTemp").value;	
	
	
	
	
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
				cargarDetallesRegistrosFacRec();
				calcularTotalTodoPreFactura();
				
				document.getElementById("conceptoNuevoTemp").value="";
				document.getElementById("descripcionDetalleNuevoTemp").value="";
				document.getElementById("notaDetalleNuevoTemp").value="";
				document.getElementById("exentoIVA").checked=false;
				
			}
			peticionUnica1=null;
		}
	}						
}


function modificarDetalleRecTemporal(id) //js_prefactura
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarModificarDetalleRecTemporal;
		peticionUnica0.open("POST","ajax/modificarDetalleRecTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDetalleRecTemporal(id);
		peticionUnica0.send(query_string);
	}
}

function consultaModificarDetalleRecTemporal(id)
{	
	var consulta = "accion=modificarDetallePrefactura";
	
	consulta+="&idDetalle="+id;		
	consulta += "&concepto=" + document.getElementById(id+"_procesoTemp").value;	
	
	//consulta += "&descripcion=" + document.getElementById(id+"_descripcionDetalleTemp").value;	
	consulta +="&descripcion=" + reemplazarSimbolos(document.getElementById(id+"_descripcionDetalleTemp").value);
	
	//consulta += "&nota=" + document.getElementById(id+"_notaDetalleTemp").value;	
	consulta +="&nota=" + reemplazarSimbolos(document.getElementById(id+"_notaDetalleTemp").value);
	
	
	var unidad = document.getElementById(id+"_unidadesDetalleTemp").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}
	consulta += "&unidad=" + unidad;
	
	var precio = document.getElementById(id+"_precioDetalleTemp").value;
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}
	
	consulta += "&precio=" + precio;
	
	var total = document.getElementById(id+"_totalDetalleTemp").value;
	total = total.replace(',','.');
	if (total == "")
	{
		total =0;
	}
	
	consulta += "&total=" + total;
	
	consulta += "&exentoIVA="+document.getElementById(id+"_exentoIVADetalleTemp").checked;
	
	return consulta;	
}

function mostrarModificarDetalleRecTemporal()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			if (peticionUnica0.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica0.responseText);
			}
			else
			{
				alert(peticionUnica0.responseText.trim());
				peticionUnica0=null;
				cargarDetallesRegistrosFacRec();
				calcularTotalTodoPreFactura();
			}
			peticionUnica0=null;
		}
	}						
}
function eliminarDetalleRecTemporal(id)  
{	
	if (confirm('¿Borrar Detalle?')) 
	{
		peticionUnica0=crearComunicacion(peticionUnica0);

		if(peticionUnica0)
		{							
			peticionUnica0.onreadystatechange = mostrarEliminarDetalleRecTemporal;
			peticionUnica0.open("POST","ajax/eliminarDetalleRecTemporal.php",false);
			peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarDetalleRecTemporal(id);
			peticionUnica0.send(query_string);
		}		
	}
}

function consultaEliminarDetalleRecTemporal(id)
{	
	var consulta = "accion=eliminarDetalle";	
	consulta+="&idDetalle="+id;
	
	return consulta;	
}

function mostrarEliminarDetalleRecTemporal()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			if (peticionUnica0.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica0.responseText);
			}
			else
			{
				cargarDetallesRegistrosFacRec();	
				calcularTotalTodoPreFactura();			
			}
		}
	}						
}


function comprobarIvaCliente(id,clayma) //js_prefactura
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
	consulta+="&clayma="+clayma;
	
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


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function previsualizarFactura() //js_prefactura
{
	if (document.getElementById("clienteOrigen").checked)
	{		
		//document.getElementById("previsualizarClayma_presupuesto").value = document.getElementById("numPresupuesto").innerHTML;		
		
		document.getElementById("previsualizarClayma_facRecMotivo").value = document.getElementById("motivo").value;
		document.getElementById("previsualizarClayma_idCliente").value = document.getElementById("idCliente").innerHTML;		
		document.getElementById("previsualizarClayma_facRecfacOriginal").value = document.getElementById("numeroFacturaCompleto").innerHTML;	


		document.getElementById("previsualizarClayma_fecha").value = document.getElementById("fechaFactura").value;
		document.getElementById("previsualizarClayma_pedido").value = (document.getElementById("pedidoCliente").innerHTML);
		
		
		
		
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
		document.getElementById("previsualizarClayma_formaPago").value = document.getElementById("formaPago").innerHTML;
		
		document.getElementById("previsualizarClayma_nuestraCuenta").value = document.getElementById("numCuenta").value;
		
		document.getElementById("previsualizarClayma_campana").value = (document.getElementById("campana").innerHTML);
		
		valor = 0;
		if (document.getElementById("detallada").innerHTML =="true")
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
	
		document.getElementById("previsualizar_facRecMotivo").value = document.getElementById("motivo").value;
		document.getElementById("previsualizar_idCliente").value = document.getElementById("idCliente").innerHTML;	
		document.getElementById("previsualizar_facRecfacOriginal").value = document.getElementById("numeroFacturaCompleto").innerHTML;	
		
		
		document.getElementById("previsualizar_fecha").value = document.getElementById("fechaFactura").value;
		document.getElementById("previsualizar_pedido").value = (document.getElementById("pedidoCliente").innerHTML);
		
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
		document.getElementById("previsualizar_formaPago").value = document.getElementById("formaPago").innerHTML;
		
		document.getElementById("previsualizar_nuestraCuenta").value = document.getElementById("numCuenta").innerHTML;
		
		document.getElementById("previsualizar_campana").value = (document.getElementById("campana").innerHTML);
		
		valor = 0;
		if (document.getElementById("detallada").innerHTML =="true")
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

function grabarFacturaRec() 
{	
	
	if (document.getElementById("motivo").value.trim()=="")
	{
		alert("Indicar un Motivo");
		document.getElementById("motivo").focus();
	}
	else
	{
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarFacturaRec;
			peticionUnica1.open("POST","ajax/anadirFacturaRec.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGrabarFacturaRec();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGrabarFacturaRec()
{	
	var consulta = "accion=anadirFactura";
	
	consulta += "&motivo="+document.getElementById("motivo").value;
	consulta += "&facturaOriginal="+document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&clayma="+document.getElementById("clayma").innerHTML;
	consulta += "&idCliente="+document.getElementById("idCliente").innerHTML;
	consulta += "&detallada="+document.getElementById("detallada").innerHTML;
	consulta += "&nombreCliente="+document.getElementById("nombreCliente").innerHTML.trim();
	consulta += "&pedidoCliente="+document.getElementById("pedidoCliente").innerHTML.trim();
	
	
	if (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null)
	{
		consulta += "&cantidad=0";
	}
	else
	{
		consulta += "&cantidad=" + document.getElementById("cantidad").value;
	}	

	consulta += "&formaPago="+document.getElementById("formaPago").innerHTML.trim();
	consulta += "&numCuenta=" + document.getElementById("numCuenta").innerHTML.trim();
	consulta += "&campana=" + document.getElementById("campana").innerHTML.trim();
	consulta += "&detallada=" + document.getElementById("detallada").innerHTML.trim();


	consulta += "&neto=" + document.getElementById("Neto").value;	
	consulta += "&iva=" + document.getElementById("iva").value;	
	consulta += "&irpf=" + document.getElementById("irpf").value;	
	consulta += "&total=" + document.getElementById("total").value;	
	consulta += "&provision=" + document.getElementById("provisionTotal").value;	
	consulta += "&aPagar=" + document.getElementById("aPagar").value;
	
	
	
	
	
	return consulta;	
}

function mostrarGrabarFacturaRec()
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
				var numeroDeAnio = datos['data'][0]['anio'];
				var numeroFacturaRec = datos['data'][0]['numeroFacturaCompleto'];
				grabarFacturaDetalleRec(numeroDeAnio,numeroFacturaRec);


				if (unError == "Error En Verifactu")
				{
					unError="";
					alert("ERROR EN VERIFACTU\nSi se ha generado la factura, NO ENVIARSELO AL CLIENTE\nRevisar lo que ha pasado");
				}
				else
				{
					irAImprimirFacRec(numeroFacturaRec);
				}

				location.href='admFacturacion.php';
				
				
				
				
				
				
				
				/*
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
				*/	
			}
			peticionUnica1=null;
		}
	}						
}

function irAImprimirFacRec(numeroFacRecCompleto) 
{	
	if (document.getElementById("clienteOrigen").checked)
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


function grabarFacturaDetalleRec(numeroDeAnio,numeroFacturaRec) 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGrabarFacturaDetalleRec;
		peticionUnica1.open("POST","ajax/anadirFacturaDetalleRec.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGrabarFacturaDetalleRec(numeroDeAnio,numeroFacturaRec);
		peticionUnica1.send(query_string);
	}
}

function consultaGrabarFacturaDetalleRec(numeroDeAnio,numeroFacturaRec)
{	
	var consulta = "accion=anadirFacturaDetalle";
	
	consulta += "&facturaOriginal="+document.getElementById("numeroFacturaCompleto").innerHTML;
	consulta += "&clayma="+document.getElementById("clayma").innerHTML;
	consulta += "&anio="+numeroDeAnio;
	consulta += "&numeroFacturaRec="+numeroFacturaRec;
	

	
	
	return consulta;	
}

function mostrarGrabarFacturaDetalleRec()
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

