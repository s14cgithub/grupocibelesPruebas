var peticionUnica1 = null;
var laCondicion="";
var seguir = true;



var mapaCampoFiltro = {
	"t2.cliente": "cliente",
	"t3.proveedor": "proveedor",
	"t1.pedido": "pedido",
	"t1.presupuesto": "presupuesto",
	"t4.descripcion": "descripcion"
};

var mapaCampoOrden = {
	"t1.fechaFacturaCompra": "fechaFacturaCompra",
	"t2.cliente": "nombreCliente",
	"importe": "importe",
	"t3.proveedor": "nombreProveedor",
	"t1.pedido": "pedido",
	"t1.presupuesto": "presupuesto",
	"t4.descripcion": "descripcion"
};

var losFiltros = {};
var losFiltrosOperadores = [];
var elOrder = [];

function buscarFactura()
{
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;	
	var exluirInterno = document.getElementById("exluirInternos").checked;
	
	var activos = document.getElementById("activos").checked;
	var anuales = document.getElementById("anuales").checked;
	
	if (campoAbuscar!="t4.descripcion" && orden=="t4.descripcion" )
	{
		orden="t1.pedido";
	}

	losFiltros = {
		excluirProveedorTest: true,
		excluirInternos: exluirInterno,
		soloActivos: activos,
		soloAnuales: anuales
	};

	losFiltrosOperadores = [];
	if (textoAbuscar != "")
	{
		losFiltrosOperadores = [{campo1: mapaCampoFiltro[campoAbuscar], operador: "LIKE", valor: textoAbuscar}];
	}

	elOrder = [{campo: mapaCampoOrden[orden], dir: desc ? "DESC" : "ASC"}];
	
	
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
		
		peticionUnica1.open("POST","ajax/cargarComprasTerceros.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPedidos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPedidos()
{	
	var campos = ['pedido','presupuesto','nombreProveedor','nombreCliente','fechaFacturaCompra','importe','numeroFactura','anioFactura','clayma','numeroFacturaClayma','anioFacturaClayma'];
	var joins = ['tabla2','tabla3','tabla4','tabla5','tabla8'];
	var group = ['pedido','presupuesto','nombreProveedor','nombreCliente','fechaFacturaCompra','numeroFactura','anioFactura','clayma','numeroFacturaClayma','anioFacturaClayma'];

	var consulta = "accion=cargarComprasTerceros";	
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(losFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(losFiltrosOperadores));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(elOrder));
	consulta += "&group=" + encodeURIComponent(JSON.stringify(group));
	
	return consulta;	
}

function mostrarCargarListadoPedidos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;
				
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
					

					var dia = '';
					var mes = '';
					var anio = '';			

					if (datos[contador]["fechaFacturaCompra"]==null)
					{
						contenido += '<td align="center"  style="overflow:hidden; white-space: nowrap;"></td>';
					}
					else
					{
						dia = datos[contador]["fechaFacturaCompra"]["date"].substr(8,2);
						mes = datos[contador]["fechaFacturaCompra"]["date"].substr(5,2);
						anio = datos[contador]["fechaFacturaCompra"]["date"].substr(0,4);		
						contenido += '<td align="center"  style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes + "-" + anio+'</td>';
					}
								
					
					
					
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
	var campos = ['pedido','presupuesto','nombreProveedor','descripcion','fecha','total','numeroFactura','anioFactura','clayma','numeroFacturaClayma','anioFacturaClayma'];
	var joins = ['tabla2','tabla3','tabla4','tabla5','tabla8'];

	var consulta = "accion=cargarComprasTerceros";	
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(losFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(losFiltrosOperadores));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(elOrder));
	
	return consulta;	
}

function mostrarCargarListadoPedidosProductos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;
				
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
	else if (document.getElementById("copiarPedidoTxt_modal").value.trim().length===0 && document.getElementById("copiarPedidoCk_modal").checked == true )
	{
		alert("Introducir el numero de pedido a Copiar");
		document.getElementById("copiarPedidoTxt_modal").focus();
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
		
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaComprobarPresupuesto()
{	
	var presupuesto = document.getElementById("pedidoNuevo_presupuesto").value;

	var campos = ['presupuesto'];
	var filtros = {presupuesto: presupuesto};
	var filtrosOperadores = [{campo1: 'fechaAceptacion', operador: '!=', valor: null}];

	var consulta = "accion=cargarPresupuestos";		
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));
	
	return consulta;	
}

function mostrarComprobarPresupuesto()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}
			else
			{
				
				var datos = res.datos;		
				
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
	var datos = {
		idComercial: document.getElementById("comercial").value,
		presupuesto: document.getElementById("pedidoNuevo_presupuesto").value,
		idProveedor: document.getElementById("proveedores").value,
		contactoProveedor: document.getElementById("proveedorNuevo_contactorP").value,
		idFormapago: document.getElementById("pedido_formaPago").value,
		anual: document.getElementById("predidoNuevo_anual").checked ? 1 : 0
	};

	var consulta = "accion=insertarCompraTercero";		
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarCrearComprar()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}
			else
			{
				let numeroCompraNuevo = res.datos.idCompra;



				if (copiarPedidoCk_modal.checked==true)
				{
					let numeroCompraCopiar = document.getElementById("copiarPedidoTxt_modal").value;
					duplicarDetallesCompra(numeroCompraNuevo,numeroCompraCopiar);
				}


				irAVerCompra(numeroCompraNuevo);
			}
			
			peticionUnica1=null;
		}
	}						
}

function gestionCopiarPedido()
{
	var elementoCh = document.getElementById("copiarPedidoCk_modal");
	var elementoTxt = document.getElementById("copiarPedidoTxt_modal");
	var elementoBtn = document.getElementById("copiarPedidoBtn_modal");
	if (elementoCh.checked==true)
	{
		elementoTxt.disabled = false;
		elementoBtn.disabled = false;
	}
	else if (elementoCh.checked==false)
	{
		elementoTxt.disabled = true;
		elementoBtn.disabled = true;
	}
}

function copiarPedido()
{
	let pedidoCopiar  = document.getElementById("copiarPedidoTxt_modal");
	if (pedidoCopiar.value.trim().length===0)
	{
		pedidoCopiar.focus();
		alert("Introducir el numero de pedido que quieres duplicar");
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCopiarPedido;		
			peticionUnica1.open("POST","ajax/cargarComprasTerceros.php",false);			
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCopiarPedido();
			peticionUnica1.send(query_string);
		}
	}

	
}




function consultaCopiarPedido()
{	
	var campos = ['idProveedor','anual','idComercial','idFormaPago','contactoProveedor'];
	var filtros = {pedido: document.getElementById("copiarPedidoTxt_modal").value};

	let consulta = "accion=cargarComprasTerceros";	
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarCopiarPedido()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;				

				if (datos.length>0)
				{
					if (datos[0]["idProveedor"]==633)
					{
						document.getElementById("copiarPedidoCk_modal").checked = false;
						document.getElementById("copiarPedidoTxt_modal").value = "";
						document.getElementById("copiarPedidoTxt_modal").disabled = true;
					}
					else
					{
						if (datos[0]["anual"]==1)
							document.getElementById("predidoNuevo_anual").checked = true;
						else
						document.getElementById("predidoNuevo_anual").checked = false;

						document.getElementById("comercial").value = datos[0]["idComercial"];
						document.getElementById("proveedores").value = datos[0]["idProveedor"];
						document.getElementById("pedido_formaPago").value = datos[0]["idFormaPago"];
						document.getElementById("proveedorNuevo_contactorP").value = datos[0]["contactoProveedor"];
					}
					

				}
				else
				{
					alert("No hay datos para mostrar");
				}				
			}
		}
	}
}	


function duplicarDetallesCompra(numeroCompraNuevo,numeroCompraCopiar)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarDuplicarDetallesCompra;		
		peticionUnica1.open("POST","ajax/copiarCompraDetalles.php",false);			
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaDetallesCompra(numeroCompraNuevo,numeroCompraCopiar);
		peticionUnica1.send(query_string);
	}
}

function consultaDetallesCompra(numeroCompraNuevo,numeroCompraCopiar)
{
	var datos = {
		numeroPedidoNuevo: numeroCompraNuevo,
		numeroPedidoCopiar: numeroCompraCopiar
	};

	let consulta="accion=duplicarDetalleCompra";
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarDuplicarDetallesCompra()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}			
		}
	}
}

function gestionAbrirPedidoNuevo()
{
	document.getElementById("copiarPedidoCk_modal").checked = false;
	document.getElementById("copiarPedidoTxt_modal").disabled = true;
	document.getElementById("copiarPedidoBtn_modal").disabled = true;
	$("#nuevoProveedorModal").modal('show');
}