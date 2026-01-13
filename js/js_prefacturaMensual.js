var peticionUnica1 = null;
var anioSeleccionado=null;

function grabarFactura2() //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGrabarFactura2;
		peticionUnica1.open("POST","ajax/anadirFactura2.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGrabarFactura2();
		peticionUnica1.send(query_string);
	}
}

function consultaGrabarFactura2()
{	
	var consulta = "accion=anadirFactura";
	
	//consulta += "&presupuesto=mensual";
	consulta += "&presupuesto=" + "Factura Original: " + document.getElementById("numPresupuesto").innerHTML;
	consulta += "&inicial=0";
	
	
	var selected = document.getElementById("cliente").innerHTML;
	
	
	selected = selected.replaceAll('%','%25');
	selected = selected.replaceAll('&','%26');
	
	
	consulta += "&cliente="+selected; 
	
	//consulta += "&cliente="+document.getElementById("cliente").innerHTML;
	
	
	
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
	consulta += "&total=" + document.getElementById("total").value;	
	consulta += "&provision=" + document.getElementById("provisionTotal").value;	
	consulta += "&aPagar=" + document.getElementById("aPagar").value;
	
	return consulta;	
}

function mostrarGrabarFactura2()
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
				var datos = peticionUnica1.responseText.split("||||");
				
				numFactura = datos[0];
				anioSeleccionado = datos[1];
				
				//verNumFacturaPorPresupuesto(document.getElementById("numPresupuesto").innerHTML);aqui
				document.getElementById("modalNumFactura").innerHTML = numFactura;
				
				grabarFacturaDetalle2(numFactura, anioSeleccionado);
				eliminarDetallesFacturaTemporal("Factura Original: "+document.getElementById("numPresupuesto").innerHTML);
				eliminarFacturaMensualAbono(document.getElementById("numPresupuesto").innerHTML);	
				
				if (document.getElementById("provisionTotal").value!=0)
				{
					modificarProvisionNumFactura();
				}
				
				irAImprimirFactura(numFactura,anioSeleccionado);
				numFactura="";
				anioSeleccionado=null;
				//$("#imprimirFacturaProcesoModal").modal('show');
				location.href='admEmisionFacturasPendientesMensuales.php';
				
			}
			peticionUnica1=null;
		}
	}						
}

function grabarFacturaDetalle2(numFactura, anioSeleccionado) //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGrabarFacturaDetalle2;
		peticionUnica1.open("POST","ajax/anadirFacturaDetalle.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGrabarFacturaDetalle2(numFactura, anioSeleccionado);
		peticionUnica1.send(query_string);
	}
}

function consultaGrabarFacturaDetalle2(numFactura, anioSeleccionado)
{	
	var consulta = "accion=anadirFacturaDetalle";
	
	consulta += "&numFactura="+numFactura;
	consulta += "&numPresupuesto=Factura Original: "+document.getElementById("numPresupuesto").innerHTML;
	//consulta += "&total="+document.getElementById("numPresupuesto").value;
	consulta += "&clayma=false";
	consulta += "&anioSeleccionado=" + anioSeleccionado;
	
	return consulta;	
}

function mostrarGrabarFacturaDetalle2()
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
			peticionUnica1=null;
		}
	}						
}

function eliminarFacturaMensualAbono(numFactura) //js_prefacturalMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarFacturaMensualAbono;
		peticionUnica1.open("POST","ajax/eliminarFacturaMensualAbono.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarFacturaMensualAbono(numFactura);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarFacturaMensualAbono(numFactura)
{	
	var consulta = "accion=eliminarFacturaMensualAbono";	
	consulta += "&numFactura="+numFactura;	
	
	return consulta;	
}

function mostrarEliminarFacturaMensualAbono()
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
			peticionUnica1=null;
		}
	}						
}


function modificarProvisionNumFactura() //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarProvisionNumFactura;
		peticionUnica1.open("POST","ajax/modificarProvisionNumFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarProvisionNumFactura();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarProvisionNumFactura()
{	
	var consulta = "accion=modificarProvisionNumFactura";
	
	consulta += "&idProvision="+document.getElementById("provision").value;
	//consulta += "&numFactura="+document.getElementById("modalNumFactura").value;
	consulta +="&numFactura=" +numFactura;
	
	return consulta;	
}

function mostrarModificarProvisionNumFactura()
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
			peticionUnica1=null;
		}
	}						
}

function anadirDetallePrefactura2() //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePrefactura2;
		peticionUnica1.open("POST","ajax/anadirDetallePrefactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePrefactura2();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePrefactura2()
{	
	var consulta = "accion=anadirDetalle";
	
	consulta += "&numPresupuesto=Factura Original: "+document.getElementById("numPresupuesto").innerHTML;	
	consulta += "&proceso=" + document.getElementById("conceptoNuevoTemp").value;		
	consulta += "&descripcion=" + document.getElementById("descripcionDetalleNuevoTemp").value;	
	consulta += "&nota=" + document.getElementById("notaDetalleNuevoTemp").value;	
	
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
	
	return consulta;	
}

function mostrarAnadirDetallePrefactura2()
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
				//alert(peticion58.responseText);
				cargarDetallesPrefactura("Factura Original: ");
			}
			
			peticionUnica1=null;
		}
	}						
}


function verDatosUnaFactura(numeroFactura,anioSeleccionado) //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosUnaFactura;
		peticionUnica1.open("POST","ajax/verDatosUnaFactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosUnaFactura(numeroFactura,anioSeleccionado);
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosUnaFactura(numeroFactura,anioSeleccionado)
{	
	var consulta = "accion=verDatosUnaFactura";
	consulta += "&numeroFactura=" + numeroFactura;
	consulta += "&clayma=false";
	consulta += "&anioSeleccionado="+anioSeleccionado;
	return consulta;	
}

function mostrarVerDatosUnaFactura()
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
				
				//datos[contador]["id"]
				//datos[contador]["fechaCreacion"]["date"].substring(0,10)
				
				
				document.getElementById("cliente").innerHTML = datos[0]["cliente"];
				//document.getElementById("fechaFactura").value = datos[0]["fecha"]["date"].substring(0,10);
				//document.getElementById("pedidoCliente").value = datos[0]["pedcli"];
				//document.getElementById("cantidad").value = datos[0]["cantidad"];
				//document.getElementById("formaPago").value = datos[0]["idFormaPago"];				
				document.getElementById("campana").value = datos[0]["descripcion"];
				
				document.getElementById("numCuenta").value = datos[0]["numCuentaBanco"];
				
				if (datos[0]["detallada"]=="1")
				{
					document.getElementById("detallada").checked = true;
				}
				else
				{
					document.getElementById("detallada").checked = false;
				}				
				
			}
			peticionUnica1=null;	
		}
	}						
}

function copiarFacturaAFacturaDetalleTemporal(numeroFactura) //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCopiarFacturaAFacturaDetalleTemporal;
		peticionUnica1.open("POST","ajax/copiarFacturaMensualAFacturaDetalleTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCopiarFacturaAFacturaDetalleTemporal(numeroFactura);
		peticionUnica1.send(query_string);
	}
}

function consultaCopiarFacturaAFacturaDetalleTemporal(numeroFactura)
{	
	var consulta = "accion=copiarFacturaAFacturaDetalleTemporal";
	consulta += "&numeroFactura=" + numeroFactura;
	consulta += "&anioSeleccionado=" + document.getElementById("numeroAnioSeleccionado").innerHTML;
	return consulta;	
}


function mostrarCopiarFacturaAFacturaDetalleTemporal()
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
				cargarDetallesPrefactura("Factura Original: ");
			}
			peticionUnica1=null;	
		}
	}						
}



function verDatosCliente() //js_prefacturaMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosCliente;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosCliente();
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosCliente()
{	
	var consulta = "accion=cargarClientes";
	consulta += "&condicion= where nombre_empresa='" + document.getElementById("cliente").innerHTML + "'";

	return consulta;	
}

function mostrarVerDatosCliente()
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
				
				
				
				document.getElementById("formaPago").value = datos[0]["idFormaPago"];
				document.getElementById("pedidoCliente").value  = datos[0]["pedidoCliente"];
							
				
			}
			peticionUnica1=null;	
		}
	}						
}




