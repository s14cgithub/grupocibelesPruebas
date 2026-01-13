var peticionUnica1 = null;

var permisosSoloLectura = false;

var modificar = true;





function cargarUnaCompra(idPedido)
{		
	//document.getElementById("buscarCampo").value = "codigo";
	/*let params = new URLSearchParams(window.location.search);
	var idPedido= params.get("id");*/
	
	var condicion = " where t1.pedido = " + idPedido;	
		
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarUnaCompra;		
		peticionUnica1.open("POST","ajax/cargarComprasTerceros.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultarCargarUnaCompra(condicion);
		peticionUnica1.send(query_string);
	}
	
}

function consultarCargarUnaCompra(condicion)
{	
	var consulta = "accion=cargarComprasTerceros";
	
	consulta += "&condicion="+(condicion);
	
	return consulta;	
}

function mostrarCargarUnaCompra()
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
					/*if (datos[0]["pdfGenerado"]==1)
					{
						window.location.href = "comprasTercerosListado.php";
					}*/
					
					document.getElementById("pedido_id").value = datos[0]["pedido"];
					
					
					
					document.getElementById("pedido_fechaAlta").value = datos[0]["fecha"]["date"].substring(0,10);
					//document.getElementById("pedido_fechaAlta").innerHTML = dia + "-" + mes + "-" + anio;
					document.getElementById("proveedores").value = datos[0]["idProveedor"];
					document.getElementById("pedido_contactoP").value = datos[0]["contactoProveedor"];
					document.getElementById("pedido_cliente").value = datos[0]["nombreCliente"];
					document.getElementById("pedido_contactoC").value = datos[0]["contactoCliente"];	
					document.getElementById("comercial").value = datos[0]["idComercial"];
					document.getElementById("pedido_presupuesto").value = datos[0]["presupuesto"];
					document.getElementById("pedido_fechaPresu").value = datos[0]["fechaPresupuesto"]["date"].substring(0,10);
					
					
					
					if (datos[0]["numeroFactura"]!=null && datos[0]["clayma"]==0)
					{
						document.getElementById("pedido_numFacVenta").value = datos[0]["numeroFactura"];
					}
					else if (datos[0]["numeroFacturaClayma"]!=null && datos[0]["clayma"]==1)
					{
						document.getElementById("pedido_numFacVenta").value = datos[0]["numeroFacturaClayma"];
					}
					else
					{
						document.getElementById("pedido_numFacVenta").value = '';
					}
					
					
					
					//document.getElementById("pedido_numFacVenta").value = datos[0]["numeroFactura"];
					
					if (datos[0]["fechaFactura"]!=null && datos[0]["clayma"]==0)
					{
						document.getElementById("pedido_fechaFacVenta").value = datos[0]["fechaFactura"]["date"].substring(0,10);
					}
					else if (datos[0]["fechaFacturaClayma"]!=null && datos[0]["clayma"]==1)
					{
						document.getElementById("pedido_fechaFacVenta").value = datos[0]["fechaFacturaClayma"]["date"].substring(0,10);
					}
					else
					{
						document.getElementById("pedido_fechaFacVenta").value = '';
					}
					
					
					if(datos[0]["fechaEntrega"]=="" || datos[0]["fechaEntrega"]== null)
					{
						document.getElementById("pedido_fechaEntrega").value ='';
					}
					else
					{
						document.getElementById("pedido_fechaEntrega").value = datos[0]["fechaEntrega"]["date"].substring(0,10);
					}
					
					
					document.getElementById("pedido_formaPago").value = datos[0]["idFormaPago"];
					document.getElementById("pedido_numFacCompra").value = datos[0]["numeroFacturaCompra"];
					
					
					if (datos[0]["fechaFacturaCompra"]==null)
					{
						document.getElementById("pedido_fechaFacCompra").value = '';
					}
					else
					{
						document.getElementById("pedido_fechaFacCompra").value = datos[0]["fechaFacturaCompra"]["date"].substring(0,10);
					}
					
					
					document.getElementById("pedido_observacionInterna").value = datos[0]["observacionesInternas"];
					cargarDetallesComprarTerceros();

				
					
				}
				else
				{
					//alert("No se ha encontrado ningun resultado");
				}
			}
			peticionUnica1=null;
		}
	}						
}


function modificarComprarTerceros()
{
	if (document.getElementById("pedido_formaPago").value=="")
	{
		document.getElementById("pedido_formaPago").focus();
		alert("Elegir una forma de pago");
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarComprarTerceros;		
			peticionUnica1.open("POST","ajax/modificarComprasTerceros.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarComprarTerceros();
			peticionUnica1.send(query_string);
		}
	}
	
	
	
	
}

function consultaModificarComprarTerceros()
{	
	var consulta = "accion=modificarComprarTerceros";
	
	consulta += "&id=" + document.getElementById("pedido_id").value;
	consulta += "&nombreProveedor=" + document.getElementById("proveedores").value; 
	consulta += "&contactoP=" + document.getElementById("pedido_contactoP").value; 
	consulta += "&contactoC=" + document.getElementById("pedido_contactoC").value; 
	consulta += "&comercial=" + document.getElementById("comercial").value; 
	consulta += "&presupuesto=" + document.getElementById("pedido_presupuesto").value; 
	consulta += "&fechaEntrega=" + document.getElementById("pedido_fechaEntrega").value; 
	consulta += "&formaPago=" + document.getElementById("pedido_formaPago").value; 
	consulta += "&numFacCompra=" + document.getElementById("pedido_numFacCompra").value; 
	consulta += "&fechaFacCompra=" + document.getElementById("pedido_fechaFacCompra").value; 
	consulta += "&observacionInterna=" + document.getElementById("pedido_observacionInterna").value;
	
	return consulta;	
}

function mostrarModificarComprarTerceros()
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
				
			}
			peticionUnica1=null;
		}
	}						
}



function calcularPrecioYmargen(id)
{
	var total=0.00;
	var margen=0.00;
	var cantidad=0;
	var unitario=0;
	var venta=0;
	
	if (id==-1)
	{
		cantidad = document.getElementById("cantidadConceptoNuevoTemp").value;
		unitario = document.getElementById("precioUnitarioConceptoNuevoTemp").value;
		total = (cantidad*unitario).toFixed(2);
		document.getElementById("precioTotalConceptoNuevoTemp").value=total;
		
		venta = document.getElementById("precioVentaConceptoNuevoTemp").value;
		
		margen = (((venta/unitario)-1)*100).toFixed(2);
		document.getElementById("margenConceptoNuevoTemp").value= margen;
	}
	else
	{
		
		
		cantidad = document.getElementById("cantidadConcepto_"+id).value;
		unitario = document.getElementById("precioUnitarioConcepto_"+id).value;
		total = (cantidad*unitario).toFixed(2);
		document.getElementById("precioTotalConcepto_"+id).value=total;
		
		venta = document.getElementById("precioVentaConcepto_"+id).value;
		
		margen = (((venta/unitario)-1)*100).toFixed(2);
		document.getElementById("margenConcepto_"+id).value= margen;
		
	}
}


function anadirDetalleCompraTercero() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetalleCompraTercero;
		peticionUnica1.open("POST","ajax/anadirDetalleCompraTercero.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetalleCompraTercero();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetalleCompraTercero()
{	
	var consulta = "accion=anadirDetalle";
	
	consulta += "&idPedido="+document.getElementById("pedido_id").value;
	consulta += "&numPresupuesto="+document.getElementById("pedido_presupuesto").value;
	
	consulta +="&descripcion=" + reemplazarSimbolos(document.getElementById("descripcionConceptoNuevoTemp").value);
	
	
	
	
	var cantidad = document.getElementById("cantidadConceptoNuevoTemp").value;	
	cantidad = cantidad.replace(',','.');
	if (cantidad == "")
	{
		cantidad =0;
	}
	consulta += "&cantidad=" + cantidad;
	
	var precioUnitario = document.getElementById("precioUnitarioConceptoNuevoTemp").value;	
	precioUnitario = precioUnitario.replace(',','.');
	if (precioUnitario == "")
	{
		precioUnitario =0;
	}
	
	consulta += "&precioUnitario=" + precioUnitario;
	
	
	var precioVenta = document.getElementById("precioVentaConceptoNuevoTemp").value;	
	precioVenta = precioVenta.replace(',','.');
	if (precioVenta == "")
	{
		precioVenta =0;
	}
	
	consulta += "&precioVenta=" + precioVenta;
	
	consulta +="&precioTotal=" + reemplazarSimbolos(document.getElementById("precioTotalConceptoNuevoTemp").value);
	
	consulta +="&margen=" + reemplazarSimbolos2(document.getElementById("margenConceptoNuevoTemp").value);
	
	return consulta;
}

function mostrarAnadirDetalleCompraTercero()
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
				cargarDetallesComprarTerceros();
				
				
				document.getElementById("descripcionConceptoNuevoTemp").value="";
				document.getElementById("cantidadConceptoNuevoTemp").value="";
				document.getElementById("precioUnitarioConceptoNuevoTemp").value="";
				document.getElementById("precioVentaConceptoNuevoTemp").value="";
				
				document.getElementById("margenConceptoNuevoTemp").value="";
				document.getElementById("precioTotalConceptoNuevoTemp").value="";
				
				
				
				
			}
			peticionUnica1=null;
		}
	}						
}


function cargarDetallesComprarTerceros()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDetallesComprarTerceros;
		peticionUnica1.open("POST","ajax/mostrarComprasTercerosDetalles.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesComprarTerceros();
		peticionUnica1.send(query_string);
	}
	
}

function consultaCargarDetallesComprarTerceros()
{	
	var consulta = "accion=cargarDetalles";
	consulta += "&idPedido="+document.getElementById("pedido_id").value;
	return consulta;
}

function mostrarCargarDetallesComprarTerceros()
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
					
					if (datos.length<=0)
					{						
					}
					else
					{
						var contenido="<tr><td>&nbsp&nbsp</td></tr><tr><td colspan='6'><center><h2>CONCEPTOS</h2></center></td></tr>";

						var contador = 0;

						while  (contador<datos.length)
						{
							var id=datos[contador]["id"];
							
							contenido += '<tr>';
							contenido += '<td align="right">Descripcion:</td>';
							contenido += '<td colspan="5">';
							contenido += '<input type="text" id="descripcionConcepto_'+id+'" style="width:100%"  placeholder="Introducir Descripcion" value="'+datos[contador]["descripcion"]+'">';
							contenido += '</input></td>';
							
							if (!permisosSoloLectura)
							{
								contenido += '<td rowspan="2" align="center"><input type="image" src="imagenes/modificar.png" style="width:15px"onclick="modificarDetalleCompraTercero('+id+')"></td>';
							}
							
														
							contenido += '</tr>';
							contenido += '<tr>';
							contenido += '<td align="right">Cantidad:</td>';
							contenido += '<td colspan="1"><input type="number" id="cantidadConcepto_'+id+'" value="'+datos[contador]["cantidad"]+'" style="width:100%" placeholder="" onKeyUp="calcularPrecioYmargen('+id+');"></input></td>';
							contenido += '<td align="right">Precio Unitario:</td>';
							contenido += '<td><input type="number" id="precioUnitarioConcepto_'+id+'"   value="'+datos[contador]["precioUnidad"]+'"   onKeyUp="calcularPrecioYmargen('+id+');"></input></td>';
							contenido += '<td align="right">Precio Total:</td>';
							contenido += '<td><input type="number"  class="soloLectura" id="precioTotalConcepto_'+id+'"  value="'+datos[contador]["total"]+'"  readonly></input></td>';
							contenido += '</tr>';
							contenido += '<tr>';
							contenido += '<td colspan="2"></td>';
							contenido += '<td align="right">Precio Venta:</td>';
							contenido += '<td><input type="number" id="precioVentaConcepto_'+id+'"   value="'+datos[contador]["precioVenta"]+'"   onKeyUp="calcularPrecioYmargen('+id+');"></input></td>';
							contenido += '<td align="right">Margen:</td>';
							contenido += '<td><input type="text" id="margenConcepto_'+id+'"   value="'+datos[contador]["margen"]+'"     class="soloLectura"  readonly></input></td>';
							
							if (!permisosSoloLectura)
							{
								contenido += '<td rowspan="1" align="center"><input type="image" src="imagenes/eliminar.png" style="width:20px" onclick="eliminarDetalleCompraTercero('+id+')"></td>';
							}
							
							contenido += '</tr>';
							
							
							contenido += '<tr><td colspan="6"><hr></td></tr>';
							
							contador++;
						}
					
						document.getElementById("detallesPedido").innerHTML = contenido;
					}
				}
			}
			peticionUnica1=null;
		}
	}						
}





function modificarDetalleCompraTercero(idDetalle)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDetalleCompraTercero;
		peticionUnica1.open("POST","ajax/modificarComprasTercerosDetalles.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDetalleCompraTercero(idDetalle);
		peticionUnica1.send(query_string);
	}
	
}

function consultaModificarDetalleCompraTercero(idDetalle)
{	
	var consulta = "accion=modificarDetalle";
	consulta += "&idDetalle="+idDetalle;
	consulta += "&descripcion="+document.getElementById("descripcionConcepto_"+idDetalle).value;
	consulta += "&cantidad="+document.getElementById("cantidadConcepto_"+idDetalle).value;
	consulta += "&precioUnitario="+document.getElementById("precioUnitarioConcepto_"+idDetalle).value;
	consulta += "&precioTotal="+document.getElementById("precioTotalConcepto_"+idDetalle).value;
	consulta += "&precioVenta="+document.getElementById("precioVentaConcepto_"+idDetalle).value;
	consulta += "&margen="+document.getElementById("margenConcepto_"+idDetalle).value;
	consulta += "&presupuesto="+document.getElementById("pedido_presupuesto").value;
	return consulta;
}

function mostrarModificarDetalleCompraTercero()
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
				alert("Detalle Modificado");
			}
			peticionUnica1=null;
		}
	}						
}

function eliminarDetalleCompraTercero(idDetalle)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarDetalleCompraTercero;
		peticionUnica1.open("POST","ajax/eliminarDetalleCompraTercero.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarDetalleCompraTercero(idDetalle);
		peticionUnica1.send(query_string);
	}
	
}

function consultaEliminarDetalleCompraTercero(idDetalle)
{	
	var consulta = "accion=eliminarDetalle";
	consulta += "&idDetalle="+idDetalle;
	return consulta;
}

function mostrarEliminarDetalleCompraTercero()
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
				cargarDetallesComprarTerceros();
			}
			peticionUnica1=null;
		}
	}						
}

function imprimirCompra(previsualizacion)
{
	
	
	
	document.getElementById("imprimirNumeroCompra").value = document.getElementById("pedido_id").value;
	document.getElementById("imprimirPrevisualizacion").value = previsualizacion;
	document.getElementById("formImprimirCompraTercero").submit();
	
	if (previsualizacion==0)
	{
		cambiarPdfGenerado(document.getElementById("pedido_id").value);
	}
}

function cambiarPdfGenerado(numPedido)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCambiarPdfGenerado;
		peticionUnica1.open("POST","ajax/modificarCompraTerceroPdfGenerado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarPdfGenerado(numPedido);
		peticionUnica1.send(query_string);
	}
	
}

function consultaCambiarPdfGenerado(numPedido)
{	
	var consulta = "accion=modificarPdfImpreso";
	consulta += "&numPedido="+numPedido;
	
	return consulta;
}

function mostrarCambiarPdfGenerado()
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
				window.location.href = "comprasTercerosListado.php";
			}
			peticionUnica1=null;
		}
	}						
} 

function buscarDatosPresupuesto()
{
	if (document.getElementById("pedido_presupuesto").value.length == 7)
	{
		comprobarPresupuesto();
	}
}

function comprobarPresupuesto()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarPresupuesto;		
		
		peticionUnica1.open("POST","ajax/mostrarSoloPresupuestos.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaComprobarPresupuesto()
{	
	var consulta = "accion=mostrarPresupuesto";		
	
	var presupuesto = document.getElementById("pedido_presupuesto").value;
	consulta += "&condicion="+presupuesto;
	
	return consulta;	
}

function mostrarComprobarPresupuesto()
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
				
				if (datos.length>0)
				{
					document.getElementById("botonModificarCompra").style.visibility = "visible";
					
					
					verDatosPresupuesto();
				}
				else
				{
					document.getElementById("botonModificarCompra").style.visibility = "hidden";
					document.getElementById("pedido_presupuesto").focus();
					//document.getElementById("pedido_presupuesto").select();
					alert("El presupuesto introducido no esta disponible");
				}
				
			}
			peticionUnica1=null;			
		}
	}						
}


function verDatosPresupuesto()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosPresupuesto;		
		
		peticionUnica1.open("POST","ajax/mostrarDatosPresupuesto.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosPresupuesto()
{	
	var consulta = "accion=mostrarDatosPresupuesto";		
	
	var presupuesto = document.getElementById("pedido_presupuesto").value;
	consulta += "&numPresupuesto="+presupuesto;
	
	return consulta;	
}

function mostrarVerDatosPresupuesto()
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
				
				if (datos.length>0)
				{
					document.getElementById("pedido_cliente").value = datos[0]["cliente"];
					document.getElementById("pedido_fechaPresu").value = datos[0]["fecha"]["date"].substring(0,10);
					document.getElementById("pedido_numFacVenta").value = datos[0]["numFactura"];
					
					if (datos[0]["fechaFac"]!=null)
					{
						document.getElementById("pedido_fechaFacVenta").value = datos[0]["fechaFac"]["date"].substring(0,10);
					}
					else
					{
						document.getElementById("pedido_fechaFacVenta").value = '';
					}
					
					
					
					
					
				}
				
				
			}
			peticionUnica1=null;			
		}
	}						
}











