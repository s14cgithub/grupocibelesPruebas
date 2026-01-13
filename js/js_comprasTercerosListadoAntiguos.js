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
	//var exluirInterno = document.getElementById("exluirInternos").checked;
	
	if (campoAbuscar!="t4.descripcion" && orden=="t4.descripcion" )
	{
		orden="t1.pedido";
	}
	
	
	if (campoAbuscar =="id" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = " + textoAbuscar;
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";
	}
	
	
	condicion += " and t1.pedido!=32638";
	
	/*if (exluirInterno==true)
	{
		condicion +=" and t1.presupuesto>='100'";
	}*/
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	
	
	laCondicion = condicion;
	
	
	if (campoAbuscar=="t4.descripcion")
	{
		cargarListadoPedidosProductos();
	}
	else
	{
		cargarListadoPedidos();
	}
	
	
	
}



function cargarListadoPedidos()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPedidos;		
		
		peticionUnica1.open("POST","ajax/cargarComprasTercerosAntiguos.php",false);
		
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
					contenido += '<th>Presupuesto</th>';
					contenido += '<th>Proveedor</th>';
					contenido += '<th>Cliente</th>';
					contenido += '<th>Fecha</th>';
					contenido += '<th>Importe</th>';
				
					contenido += '<th></th>';

					contenido += '</tr>';
				
				var contador = 0;	
				var contraste='';
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
					
					if (datos[contador]["numeroFactura"]!="" && datos[contador]["numeroFactura"]!=null && datos[contador]["clayma"]==0)
					{	
						var anioCompra = datos[contador]["anioFactura"] - 2000;
						
						contenido += '<td align="right" style="background:green;" title="'+datos[contador]["numeroFactura"]+'/' + anioCompra+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["pedido"]+'</span></td>';						
					}
					else if (datos[contador]["numeroFacturaClayma"]!="" && datos[contador]["numeroFacturaClayma"]!=null && datos[contador]["clayma"]==1)
					{	
						var anioCompra = datos[contador]["anioFacturaClayma"] - 2000;
						
						contenido += '<td align="right" style="background: #B87240;" title="'+datos[contador]["numeroFacturaClayma"]+'/' + anioCompra+'-Clayma"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["pedido"]+'</span></td>';						
					}
					else
					{
						contenido += '<td align="right">'+datos[contador]["pedido"]+'</td>';
					}
					
					//contenido += '<td align="right">'+datos[contador]["pedido"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["presupuesto"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombreProveedor"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombreCliente"]+'</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);					
					
					
					contenido += '<td align="center"  style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes + "-" + anio+'</td>';
					contenido += '<td align="right">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					
					
					
					
					contenido += '<td><input type="image" id="'+datos[contador]["pedido"]+'_cliente" value="" src="imagenes/ojo.png" style="width:15px;" onclick="irAVerCompra('+datos[contador]["pedido"]+')"></td>';
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoProveedores").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}

function irAVerCompra(idPedido)//js_presupuestoListado
{	
	window.location.href = "comprasTerceros.php?id="+idPedido;
}


function cargarListadoPedidosProductos()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPedidosProductos;		
		
		peticionUnica1.open("POST","ajax/cargarComprasTercerosConceptos.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPedidosProductos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPedidosProductos()
{	
	var consulta = "accion=cargarComprasTerceros";	
	
	consulta += "&condicion=" + reemplazarSimbolosBusqueda(laCondicion);
	
	return consulta;	
}

function mostrarCargarListadoPedidosProductos()
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
					contenido += '<th>Presupuesto</th>';
					contenido += '<th>Proveedor</th>';
					contenido += '<th>Producto</th>';
					contenido += '<th>Fecha</th>';
					contenido += '<th>Importe</th>';
				
					contenido += '<th></th>';

					contenido += '</tr>';
				
				var contador = 0;	
				var contraste='';
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
					
					if (datos[contador]["numeroFactura"]!="" && datos[contador]["numeroFactura"]!=null && datos[contador]["clayma"]==0)
					{	
						var anioCompra = datos[contador]["anioFactura"] - 2000;
						
						contenido += '<td align="right" style="background:green;" title="'+datos[contador]["numeroFactura"]+'/' + anioCompra+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["pedido"]+'</span></td>';						
					}
					else if (datos[contador]["numeroFacturaClayma"]!="" && datos[contador]["numeroFacturaClayma"]!=null && datos[contador]["clayma"]==1)
					{	
						var anioCompra = datos[contador]["anioFacturaClayma"] - 2000;
						
						contenido += '<td align="right" style="background: #B87240;" title="'+datos[contador]["numeroFacturaClayma"]+'/' + anioCompra+'-Clayma"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["pedido"]+'</span></td>';						
					}
					else
					{
						contenido += '<td align="right">'+datos[contador]["pedido"]+'</td>';
					}
					
					//contenido += '<td align="right">'+datos[contador]["pedido"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["presupuesto"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombreProveedor"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["descripcion"]+'</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);					
					
										
					contenido += '<td align="center"  style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes + "-" + anio+'</td>';
					contenido += '<td align="right">'+Number(datos[contador]["total"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					
					
					
					
					contenido += '<td><input type="image" id="'+datos[contador]["pedido"]+'_cliente" value="" src="imagenes/ojo.png" style="width:15px;" onclick="irAVerCompra('+datos[contador]["pedido"]+')"></td>';
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoProveedores").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}



function crearCompra()
{
	if (document.getElementById("pedidoNuevo_presupuesto").value.trim=="")
	{
		alert("Introducir el Presupuesto");
		document.getElementById("pedidoNuevo_presupuesto").focus();
	}
	else
	{
		seguir = false;
		comprobarPresupuesto();
		
		if (seguir==true)
		{
			crearComprar2();
		}
		else
		{
			alert("El presupuesto introducido no existe o no tiene fecha de Aceptacion");
			document.getElementById("pedidoNuevo_presupuesto").focus();
		}		
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
	
	var presupuesto = document.getElementById("pedidoNuevo_presupuesto").value;
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
					seguir = true;
				}
				else
				{
					seguir = false;
				}
				
			}
			peticionUnica1=null;			
		}
	}						
}

function crearComprar2()
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearComprar;		
		
		peticionUnica1.open("POST","ajax/insertarCompraTercero.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaMostrarCrearComprar();
		peticionUnica1.send(query_string);
	}
}

function consultaMostrarCrearComprar()
{	
	var consulta = "accion=insertarCompraTercero";		
	
	
	consulta += "&idComercial="+document.getElementById("comercial").value;
	consulta += "&presupuesto="+document.getElementById("pedidoNuevo_presupuesto").value;
	consulta += "&idProveedor="+document.getElementById("proveedores").value;
	consulta += "&contactoProveedor="+document.getElementById("proveedorNuevo_contactorP").value;
	consulta += "&formaPago="+document.getElementById("pedido_formaPago").value;
	
	
	
	
	return consulta;	
}

function mostrarCrearComprar()
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
				irAVerCompra(peticionUnica1.responseText);
			}
			
			peticionUnica1=null;
		}
	}						
}







