var peticionUnica1 = null;


var numeroFactura = "";
var clayma="";
var anioSeleccionado="";
var idCliente = 0;







function verDatosFactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosFactura;
		peticionUnica1.open("POST","ajax/verDatosUnaFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosFactura();
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosFactura()
{	
	
	numeroFactura=document.getElementById("numFactura").innerHTML;	
	clayma=document.getElementById("clienteOrigen").checked;	
	anioSeleccionado=20+document.getElementById("anio").innerHTML;	
	
	
	
	var consulta = "accion=verDatosUnaFactura";	
	consulta += "&numeroFactura="+numeroFactura;	
	consulta += "&clayma="+clayma;	
	consulta += "&anioSeleccionado="+anioSeleccionado;	
	
	
	return consulta;	
}

function mostrarVerDatosFactura()
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
						document.getElementById("clientes").innerHTML = datos[0]["cliente"];
						document.getElementById("pedidoCliente").value = datos[0]["pedido"];
						
						
						var dia = datos[0]["fecha"]["date"].substr(8,2);
						var mes = datos[0]["fecha"]["date"].substr(5,2);
						var anio = datos[0]["fecha"]["date"].substr(0,4);
						
						
						document.getElementById("fechaFactura").innerHTML = dia+"/"+mes+"/"+anio;
						document.getElementById("cantidad").value = datos[0]["cantidad"];
						document.getElementById("campana").value = datos[0]["descripcion"];
						
						if (datos[0]["detallada"]==true)
						{
							document.getElementById("detallada").checked = true;
						}
						else
						{
							document.getElementById("detallada").checked = false;
						}
						document.getElementById("Neto").value = datos[0]["precioNeto"];
						document.getElementById("iva").value = datos[0]["iva"];
						document.getElementById("irpf").value = datos[0]["irpf"];
						document.getElementById("total").value = datos[0]["precioTotal"];
						
						
						var provision = datos[0]["provision"];
						
						if (provision == ".00")
						{
							provision="0.00";
						}
						
						
						
						document.getElementById("provisionTotal").value = provision;
						document.getElementById("aPagar").value = datos[0]["precioTotal"];



						

						var textToFind = datos[0]["formaPago"];

						var dd = document.getElementById('formaPago');
						for (var i = 0; i < dd.options.length; i++) {
							if (dd.options[i].text === textToFind) {
								dd.selectedIndex = i;
								break;
							}
						}

						//document.getElementById("formaPago") = datos[0]["formaPago"];
									
					}
				}
				//document.getElementById("provision").innerHTML = contenido;
				//document.getElementById("provisionTotal").value = importeTotal.toFixed(2);
			}
			peticionUnica1=null;
			obtenerIdCliente();
			cargarDetallesFactura();
		}
	}						
}




function cargarDetallesFactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDetallesFactura;
		peticionUnica1.open("POST","ajax/verFacturaDetalle.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesFactura();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarDetallesFactura()
{
	var consulta = "accion=verDetalles";
	consulta += "&numFactura="+numeroFactura;	
	consulta += "&clayma="+clayma;	
	consulta += "&anioSeleccionado="+anioSeleccionado;
	
	
	return consulta;	
}

function mostrarCargarDetallesFactura()
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
				unArray = [];
				var contador=0;
				var contenido = "";
				
				while  (contador<datos.length) 
				{	
					contenido += '<tr><td>OT:</td><td colspan="5"> <label id="'+datos[contador]["id"]+'_otTemp"  style="width:100%">'+datos[contador]["presupuesto"]+'</label></td>';									
					contenido += '<tr><td>Concepto:</td><td colspan="5"> <input type="text" id="'+datos[contador]["id"]+'_procesoTemp" value="'+datos[contador]["concepto"]+'" style="width:100%"></input></td>';					
					
					contenido += '<td ROWSPAN="2" align="center"><input type="image" id="'+datos[contador]["id"]+'_modificarDetalleTemp" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarDetallePreFactura('+datos[contador]["id"]+')" ></td></tr>';
					
					contenido += '<tr><td>Descripcion:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_descripcionDetalleTemp" value="'+datos[contador]["descripcion"]+'" style="width:100%"></input></td></tr>';
						
					contenido += '<tr><td>Nota Cibeles:</td><td colspan="4"><input type="text" id="'+datos[contador]["id"]+'_notaDetalleTemp" value="'+datos[contador]["notaCibeles"]+'" style="width:100%"></input></td>';
					

					if (datos[contador]["exentoIVA"]==true)
						contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_exentoIVADetalleTemp" checked></input></td>';
					else
						contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_exentoIVADetalleTemp"></input></td>';
				
						

					contenido += '<td ROWSPAN="2"><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalleTemp" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarDetallePreFactura('+datos[contador]["id"]+')" ></td></tr>';

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
					
					
					contenido += '<td>Total:</td><td><input type="number" id="'+datos[contador]["id"]+'_totalDetalleTemp" value="'+valor+'" readonly></input></td>';
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
			
			peticionUnica1=null;
		}
	}						
}

function anadirDetallePrefactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePrefactura;
		peticionUnica1.open("POST","ajax/anadirFacturaDetalle2.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePrefactura()
{	
	var consulta = "accion=anadirFacturaDetalle";
	
	consulta += "&numFactura="+numeroFactura;
	consulta += "&anioSeleccionado="+anioSeleccionado;
	consulta += "&clayma="+clayma;
	
	
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
				cargarDetallesFactura();
				
				document.getElementById("conceptoNuevoTemp").value="";
				document.getElementById("descripcionDetalleNuevoTemp").value="";
				document.getElementById("notaDetalleNuevoTemp").value="";
				
				
			}
			peticionUnica1=null;
		}
	}						
}


function obtenerIdCliente()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarObtenerIdCliente;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaObtenerIdCliente();
		peticionUnica1.send(query_string);
	}
}
function consultaObtenerIdCliente()
{	
	var consulta = "accion=cargarClientes";	
	consulta+="&condicion=where nombre_empresa = '"+ document.getElementById("clientes").innerHTML +"' and activo = 1 and codigo_saldo=codigo";
	
	return consulta;	
}

function mostrarObtenerIdCliente()
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
					idCliente = datos[0]["codigo"];
					
				}				
			}
			peticionUnica1=null;
			comprobarIvaCliente();
		}
	}						
}



function comprobarIvaCliente() //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarIvaCliente;
		
		peticionUnica1.open("POST","ajax/verIvaCliente.php",false);	
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarIvaCliente();
		peticionUnica1.send(query_string);
	}
}
function consultaComprobarIvaCliente()
{	
	var consulta = "accion=verSiTieneFirma";	
	consulta+="&idCliente="+idCliente;
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



function modificarDetallePreFactura(id) //esto esta sin hacer
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDetallePreFactura;
		peticionUnica1.open("POST","ajax/modificarDetalleFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDetallePreFactura(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarDetallePreFactura(id)
{	
	var consulta = "accion=modificar4-1Detalle!32Factura";
	
	consulta+="&idDetalle="+id;		
	consulta+="&numFactura="+numeroFactura;
	consulta+="&anioSeleccionado="+anioSeleccionado;
	consulta+="&clayma="+clayma;	
	
	
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
	
	
	return consulta;	
}

function mostrarModificarDetallePreFactura()
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
				peticionUnica1=null;
				calcularTotalTodoPreFactura();
				modificarDatosGenericos();
			}
			peticionUnica1=null;
		}
	}						
}


function eliminarDetallePreFactura(id) //js_prefactura
{	
	if (confirm('¿Borrar Detalle?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarDetallePreFactura;
			peticionUnica1.open("POST","ajax/eliminarDetalleFactura.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarDetallePreFactura(id);
			peticionUnica1.send(query_string);
		}		
	}
}

function consultaEliminarDetallePreFactura(id)
{	
	var consulta = "accion=eliminar!_3281Detalle";	
	
	consulta+="&idDetalle="+id;		
	consulta+="&numFactura="+numeroFactura;
	consulta+="&anioSeleccionado="+anioSeleccionado;
	consulta+="&clayma="+clayma;	
	
	
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
	
	
	return consulta;	
}

function mostrarEliminarDetallePreFactura()
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
				peticionUnica1=null;
				cargarDetallesFactura();
				modificarDatosGenericos();
			}
			
			peticionUnica1=null;
			
		}
	}						
}




function modificarDatosGenericos() //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDatosGenericos;
		peticionUnica1.open("POST","ajax/modificarFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDatosGenericos();
		peticionUnica1.send(query_string);
	}		

}
function consultaModificarDatosGenericos()
{	
	var consulta = "accion=modificar348!Fac";	
	
	
	consulta+="&numFactura="+numeroFactura;
	consulta+="&anioSeleccionado="+anioSeleccionado;
	consulta+="&clayma="+clayma;
	
	consulta += "&pedido=" + document.getElementById("pedidoCliente").value;
	consulta += "&cantidad=" + document.getElementById("cantidad").value;
	
	var combo = document.getElementById("formaPago");
	var selected = combo.options[combo.selectedIndex].text;
	consulta += "&formaPago=" + selected;
	
	consulta += "&campana=" + document.getElementById("campana").value;
	consulta += "&detallada=" + document.getElementById("detallada").checked;
	consulta += "&neto=" + document.getElementById("Neto").value;
	consulta += "&iva=" + document.getElementById("iva").value;
	consulta += "&irpf=" + document.getElementById("irpf").value;
	consulta += "&total=" + document.getElementById("total").value;
	//consulta += "&provision=" + document.getElementById("provisionTotal").value;
	consulta += "&aPagar=" + document.getElementById("aPagar").value;
	
	
	return consulta;	
}

function mostrarModificarDatosGenericos()
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
				peticionUnica1=null;
						
			}
			
			peticionUnica1=null;
			
		}
	}						
}

function getionVerPreFactura()
{
	if (document.getElementById("clienteOrigen").checked==true || document.getElementById("clienteOrigen").checked=="true")
	{
		irAImprimirFacturaClayma(numeroFactura, anioSeleccionado);
	}
	else
	{
		irAImprimirFactura(numeroFactura, anioSeleccionado);
	}
	
}


function convertirAFactura()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarConvertirAFactura;
		peticionUnica1.open("POST","ajax/convertirPreFacturaAFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaConvertirAFactura();
		peticionUnica1.send(query_string);
	}		

}
function consultaConvertirAFactura()
{	
	var consulta = "accion=convertirPreAFactura";	
	
	
	consulta+="&numFactura="+numeroFactura;
	consulta+="&anioSeleccionado="+anioSeleccionado;
	consulta+="&clayma="+clayma;
	
	return consulta;	
}

function mostrarConvertirAFactura()
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
				peticionUnica1=null;
				getionVerPreFactura();
				history.back(-1);		
			}
			
			peticionUnica1=null;
			
		}
	}						
}









