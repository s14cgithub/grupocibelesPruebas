var peticionUnica1 = null;

function cargarSubClientes() //js_almacenProducto (antes cargarSubClientes de js_global, comentada); destino fijo: nombreCliente
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarSubClientes;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarSubClientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarSubClientes()
{
	var consulta = "accion=cargarClientes";

	var campos = ['codigo','subcliente'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {activo: 1};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var order = [{campo: 'subcliente', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;
}

function mostrarCargarSubClientes()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			var contenido = '';

			if (res.error=="" && res.datos)
			{
				for (var i=0; i<res.datos.length; i++)
				{
					contenido += '<option value="'+res.datos[i]["codigo"]+'">'+res.datos[i]["subcliente"]+' - '+res.datos[i]["codigo"]+'</option>';
				}
			}

			document.getElementById("nombreCliente").innerHTML = contenido;

			peticionUnica1=null;
		}
	}
}

function buscarFactura()
{
	cargarProductos();	
}



function cargarProductos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarProductos;
		peticionUnica1.open("POST","ajax/mostrarAlmacenProductos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarProductos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarProductos()
{	
	var consulta = "accion=mostrarAlmacenProductos";

	var campos = ['id','subcliente','nombre','codigo'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = ['tabla2'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtrosOperadores = [];
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	if (textoAbuscar != "")
	{
		filtrosOperadores.push({ campo1: campoAbuscar, valor: '%' + textoAbuscar + '%', operador: 'LIKE' });
	}
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify({}));

	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	var order = [
		{ campo: orden, dir: desc ? 'DESC' : 'ASC' }
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarProductos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{				
				var datos = res.datos;
				
				var contenido = "";
				var contador = 0;	
				contenido += '<th style="text-align:center;">Cliente</th>';
				contenido += '<th style="text-align:center;">Producto</th>';
				contenido += '<th style="text-align:center;">Codigo</th>';
				
				while  (contador<datos.length)
				{ 
					contenido += '<tr>';
					contenido += '<td> <label id="'+datos[contador]["id"]+'_cliente">'+datos[contador]["subcliente"]+'</label></td>';	
					//contenido += '<td> <select id="'+datos[contador]["id"]+'_cliente"></select></td>';
					
					contenido += '<td> <input type="text" id="'+datos[contador]["id"]+'_producto" value="'+datos[contador]["nombre"]+'"></input></td>';
					
					contenido += '<td> <input type="text" id="'+datos[contador]["id"]+'_codigo" value="'+datos[contador]["codigo"]+'"></input></td>';
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarProducto('+datos[contador]["id"]+')"></td>';
					
					contenido += '</tr>';
					contador++;	
				}
				
				document.getElementById("historicoProductos").innerHTML = contenido;
				
				/*contador=0;
				
				while  (contador<datos.length)
				{ 
					
					//idInputListado=datos[contador]["id"]+'_cliente';
					//cargarSubClientes("A",idInputListado);
					
					document.getElementById(datos[contador]["id"]+'_cliente').innerHTML = "<option value='"+datos[contador]["idSubCliente"]+"'> "+datos[contador]["subcliente"]+"</option>";
					
					document.getElementById(datos[contador]["id"]+'_cliente').value = datos[contador]["idSubCliente"];
					contador++;	
				}*/
			}
			peticionUnica1=null;
			
		}
	}						
}





function guardarProducto()
{
	if (document.getElementById("nombreProducto").value=="")
	{
		alert("Introducir un Producto");
		document.getElementById("nombreProducto").focus();
	}
	else if (document.getElementById("codigoProducto").value=="")
	{
		alert("Introducir un Codigo");
		document.getElementById("codigoProducto").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGuardarProducto;
			peticionUnica1.open("POST","ajax/insertarAlmacenProducto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGuardarProducto();
			peticionUnica1.send(query_string);
		}
	}
	
	
}

function consultaGuardarProducto()
{	
	var consulta = "accion=insertarProducto";	
	consulta += "&producto="+document.getElementById("nombreProducto").value;
	consulta += "&idCliente="+document.getElementById("nombreCliente").value;
	consulta += "&codigo="+document.getElementById("codigoProducto").value;

	
	return consulta;	
}

function mostrarGuardarProducto()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				
				document.getElementById("nombreProducto").value = "";
				document.getElementById("codigoProducto").value = "";	
				alert(res.mensaje);
			}
			peticionUnica1=null;
			cargarProductos();
			
		}
	}						
}


function modificarProducto(id)
{
	if (document.getElementById(id+'_producto').value=="")
	{
		alert("Introducir un Producto");
		document.getElementById(id+'_producto').focus();
	}
	else if (document.getElementById(id+'_codigo').value=="")
	{
		alert("Introducir un Codigo");
		document.getElementById(id+'_codigo').focus();
	}
	else if (confirm('¿Modificar Producto?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarProducto;
			peticionUnica1.open("POST","ajax/modificarAlmacenProducto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarProducto(id);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarProducto(id)
{	
	var consulta = "accion=modificarProducto";	
	consulta += "&id="+id;	
	consulta += "&producto=" + document.getElementById(id+'_producto').value;
	consulta += "&codigo=" + document.getElementById(id+'_codigo').value;

	
	return consulta;	
}

function mostrarModificarProducto()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{				
				alert(res.mensaje);
			}
			peticionUnica1=null;	
			
		}
	}						
}










