var peticionUnica1 = null;
var laCondicion="";
var seguir = true;


function buscarFactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;	
	
	
	if (campoAbuscar =="t1.pedido" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = " + textoAbuscar;
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";
	}
	
	
	
	condicion += " and (numeroFacturaCompra = '' or numeroFacturaCompra is null)";
	
	
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	
	
	laCondicion = condicion;
	
	
	
	cargarListadoPedidos();
	
	
}



function cargarListadoPedidos()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPedidos;		
		
		peticionUnica1.open("POST","ajax/cargarComprasTercerosConceptos.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPedidos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPedidos()
{	
	var consulta = "accion=cargarComprasTerceros";	
	
	consulta += "&condicion=" + reemplazarSimbolosBusqueda(laCondicion);
	
	return consulta;	
}

function mostrarCargarListadoPedidos()
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
				
					contenido += '<th>Pedido</th>';
					contenido += '<th>Proveedor</th>';
					contenido += '<th>Concepto</th>';
					contenido += '<th>Importe</th>';
					contenido += '<th>Nº Factura</th>';
					contenido += '<th>Fecha Factura</th>';
				
					contenido += '<th></th>';
					

					contenido += '</tr>';
				
				var contador = 0;	
				var contraste='';
				var pedidoAnterior = "asdfjka28349283429";
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
					
					
					
					contenido += '<td align="right">'+datos[contador]["pedido"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombreProveedor"]+'</td>';
				
					contenido += '<td align="left">'+datos[contador]["descripcion"]+'</td>';
					contenido += '<td align="right">'+Number(datos[contador]["total"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					
					
					if (pedidoAnterior!=datos[contador]["pedido"])
					{
						contenido += '<td><input type="text"id="'+datos[contador]["pedido"]+'_numeroFactura" value=""></td>';
						contenido += '<td><input type="date"id="'+datos[contador]["pedido"]+'_fechaFactura" value=""></td>';
						
						pedidoAnterior = datos[contador]["pedido"] ;
						
						contenido += '<td><input type="image" id="'+datos[contador]["pedido"]+'_cliente" value="" src="imagenes/modificar.png" style="width:15px;" onclick="insertarFacturaCompra('+datos[contador]["pedido"]+')"></td>';
					}
					else
					{
						contenido +='<td></td><td></td><td></td>'
					}
					
													
					
					
					
					
					
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoProveedores").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}

function insertarFacturaCompra(idPedido)//js_presupuestoListado
{	
	if (document.getElementById(idPedido + '_numeroFactura').value == "")
	{
		alert("Introducir un numero de factura");
		document.getElementById(idPedido + '_numeroFactura').focus();
	}
	else if (document.getElementById(idPedido + '_fechaFactura').value == "")
	{
		alert("Introducir la fecha de factura");
		document.getElementById(idPedido + '_fechaFactura').focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarFacturaCompra;		

			peticionUnica1.open("POST","ajax/insertarFacturaCompra_Terceros.php",false);
			
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarFacturaCompra(idPedido);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaInsertarFacturaCompra(idPedido)
{	
	var consulta = "accion=insertarNumeroFactura";		
	
	
	consulta += "&idPedido="+idPedido;
	consulta += "&numeroFactura="+document.getElementById(idPedido+"_numeroFactura").value;
	consulta += "&fechaFactura="+document.getElementById(idPedido+"_fechaFactura").value;
	
	return consulta;	
}

function mostrarInsertarFacturaCompra()
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
				
				cargarListadoPedidos();
				
			}
			peticionUnica1=null;			
		}
	}						
}





