var peticionUnica1 = null;
var laCondicion="";
var seguir = true;


var mapaCampoFiltro = {
	"t4.descripcion": "descripcion",
	"t1.pedido": "pedido",
	"t3.proveedor": "proveedor"
};

var mapaCampoOrden = {
	"t1.pedido": "pedido"
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

	losFiltros = {
		sinFacturar: true
	};

	losFiltrosOperadores = [];
	if (campoAbuscar=="t1.pedido" && textoAbuscar!="")
	{
		losFiltrosOperadores = [{campo1: mapaCampoFiltro[campoAbuscar], operador: "=", valor: textoAbuscar}];
	}
	else if (textoAbuscar != "")
	{
		losFiltrosOperadores = [{campo1: mapaCampoFiltro[campoAbuscar], operador: "LIKE", valor: textoAbuscar}];
	}

	elOrder = [{campo: mapaCampoOrden[orden], dir: desc ? "DESC" : "ASC"}];
	
	
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
	var campos = ['pedido','nombreProveedor','descripcion','total'];
	var joins = ['tabla3','tabla4'];

	var consulta = "accion=cargarComprasTerceros";	
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(losFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(losFiltrosOperadores));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(elOrder));
	
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
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error != "")
			{
				alert(res.error);
			}
			else
			{
				
				cargarListadoPedidos();
				
			}
			peticionUnica1=null;			
		}
	}						
}





