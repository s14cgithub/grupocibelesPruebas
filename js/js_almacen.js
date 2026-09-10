var peticionUnica1 = null;

var laCondicion="";
var arrayAlbaran = [];

function cargarSubClientes() //js_almacen (antes cargarSubClientes de js_global, comentada); destino fijo: clienteModal
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

			document.getElementById("clienteModal").innerHTML = contenido;

			peticionUnica1=null;
		}
	}
}

function buscarFactura()
{
	cargarListadoMovimientos();
}

function cargarListadoMovimientos() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoMovimientos;
		peticionUnica1.open("POST","ajax/mostrarAlmacenMovimientos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoMovimientos();
		peticionUnica1.send(query_string);
	}
}

function construirFiltrosOperadoresMovimientos() // filtros del buscador de movimientos; usado por el listado y por el export a Excel
{
	var filtrosOperadores = [];

	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	if (textoAbuscar != "")
	{
		filtrosOperadores.push({ campo1: campoAbuscar, valor: '%' + textoAbuscar + '%', operador: 'LIKE' });
	}

	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;

	if (fechaInicio != "" && fechaInicio != null && fechaInicio != "null")
	{
		filtrosOperadores.push({ campo1: 'fecha', valor: fechaInicio, operador: '>=' });
	}

	if (fechaFin != "" && fechaFin != null && fechaFin != "null")
	{
		filtrosOperadores.push({ campo1: 'fecha', valor: fechaFin, operador: '<=' });
	}

	return filtrosOperadores;
}

function consultaCargarListadoMovimientos()
{	
	var consulta = "accion=mostrarAlmacenMovimientos";

	var campos = ['id','fecha','subcliente','codigo','nombreProducto','modalidad','hueco','cantidad','cantidadTotal','disponible','ot','observaciones','albaran','idAlbaran'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = ['tabla2','tabla3','tabla4','tabla5','tabla6'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtrosOperadores = construirFiltrosOperadoresMovimientos();
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

function mostrarCargarListadoMovimientos()
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
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Fecha</th>';				
				contenido += '<th>Cliente</th>';
				contenido += '<th>Codigo</th>';	
				contenido += '<th>Producto</th>';
				contenido += '<th>Modalidad</th>';
				contenido += '<th>Hueco</th>';
				contenido += '<th>Cantidad</th>';
				contenido += '<th>Cantidad Hueco</th>';
				contenido += '<th>Disponible</th>';

				contenido += '<th>Ot</th>';
				contenido += '<th>Obs.</th>';
				contenido += '<th></th>';

				contenido += '</tr>';
				
				var contador = 0;	
				var contraste = "";
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
					
					
					contenido += '<tr>';
					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="'+datos[contador]["id"]+'_fecha" style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	
					
					
					contenido += '<td align="left" id="'+datos[contador]["id"]+'_cliente">'+datos[contador]["subcliente"]+'</td>';
					contenido += '<td align="left" id="'+datos[contador]["id"]+'_codigo">'+datos[contador]["codigo"]+'</td>';
					contenido += '<td align="left" id="'+datos[contador]["id"]+'_producto">'+datos[contador]["nombreProducto"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_modalidad">'+datos[contador]["modalidad"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_hueco" style="overflow:hidden; white-space: nowrap;">'+datos[contador]["hueco"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_cantidad">'+datos[contador]["cantidad"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_cantidadTotal">'+datos[contador]["cantidadTotal"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_disponible">'+datos[contador]["disponible"]+'</td>';
					
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_ot">'+datos[contador]["ot"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_observaciones">'+datos[contador]["observaciones"]+'</td>';
					
					
					if (datos[contador]["albaran"]==1 || datos[contador]["albaran"]=="1")
					{
						//contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["id"]+'_Albaran" style="accent-color: red;" onclick="return false;" checked></input></td>';
						contenido += '<td align="center"><input type="image" src="imagenes/ojo.png" style="width:15px;"  onclick="visualizarAlbaranAlmacen('+datos[contador]["idAlbaran"]+')"></td>';
					}
					else
					{
						contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["id"]+'_Albaran" onclick="gestionAlbaran('+datos[contador]["id"]+');"></input></td>';
					}
					
					
					
					contenido += '</tr>';
					
					contador++;	
				}		
				
				document.getElementById("listadoMovimientosAlmacen").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}



function cargarListadoProductos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoProductos;
		peticionUnica1.open("POST","ajax/mostrarAlmacenProductos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoProductos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoProductos()
{	
	var consulta = "accion=mostrarAlmacenProductos";
	var campos = ['id','codigo','nombre'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	var joins = [];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
	var filtros = { idSubCliente: document.getElementById("clienteModal").value };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	var order = [ { campo: 'codigo', dir: 'ASC' } ];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoProductos()
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
			
				while  (contador<datos.length)
				{ 
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["codigo"]+' - '+datos[contador]["nombre"]+'</option>';
					contador++;	
				}		
				
				document.getElementById("productoModal").innerHTML = contenido;				
			}
			peticionUnica1=null;
			gestionHuecos();
		}
	}						
}

function cargarListadoModalidadAlmacen()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoModalidadAlmacen;
		peticionUnica1.open("POST","ajax/mostrarAlmacenModalidad.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoModalidadAlmacen();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoModalidadAlmacen()
{	
	var consulta = "accion=mostrarAlmacenModalidad";
	var campos = ['id','modalidad'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	consulta += "&order=" + encodeURIComponent(JSON.stringify([]));
	
	return consulta;	
}

function mostrarCargarListadoModalidadAlmacen()
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
			
				while  (contador<datos.length)
				{ 
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["modalidad"]+'</option>';
					contador++;	
				}		
				
				document.getElementById("modalidadModal").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}


function cargarListadoAlmacenesAlmacen()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoAlmacenesAlmacen;
		peticionUnica1.open("POST","ajax/mostrarAlmacenAlmacenes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoAlmacenesAlmacen();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoAlmacenesAlmacen()
{	
	var consulta = "accion=mostrarAlmacenAlmacenes";
	var campos = ['id','almacen'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	consulta += "&order=" + encodeURIComponent(JSON.stringify([]));
	
	return consulta;	
}

function mostrarCargarListadoAlmacenesAlmacen()
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
			
				while  (contador<datos.length)
				{ 
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["almacen"]+'</option>';
					contador++;	
				}		
				
				document.getElementById("almacenModal").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}



function cargarListadoHuecosAlmacen()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoHuecosAlmacen;
		peticionUnica1.open("POST","ajax/mostrarAlmacenHuecos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoHuecosAlmacen();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoHuecosAlmacen()
{	
	var consulta = "accion=mostrarAlmacenHuecos";
	var campos = ['id','hueco'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	var order = [ { campo: 'hueco', dir: 'ASC' } ];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoHuecosAlmacen()
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
			
				while  (contador<datos.length)
				{ 
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["hueco"]+'</option>';
					contador++;	
				}		
				
				document.getElementById("huecoModal").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}


function crearMovimientoAlmacen()
{
	if (document.getElementById("fechaModal").value=="")
	{
		alert("Elegir una fecha");
		document.getElementById("fechaModal").focus();
	}
	else if (document.getElementById("clienteModal").value=="")
	{
		alert("Elegir un cliente");
		document.getElementById("clienteModal").focus();
	}
	else if (document.getElementById("productoModal").value=="")
	{
		alert("Elegir un producto");
		document.getElementById("productoModal").focus();
	}
	else if (document.getElementById("huecoModal").value=="")
	{
		alert("Elegir un hueco");
		document.getElementById("huecoModal").focus();
	}
	else if (document.getElementById("cantidadModal").value=="")
	{
		alert("Elegir una cantidad");
		document.getElementById("cantidadModal").focus();
	}
	else if (document.getElementById("cantidadModal").value<0)
	{
		alert("La cantidad no puede tener valor negativo");
		document.getElementById("cantidadModal").focus();
	}
	else if (document.getElementById("cantidadModal").value.indexOf(",")>= 0 || document.getElementById("cantidadModal").value.indexOf(".")>= 0)
	{
		alert("La cantidad no puede tener decimales");
		document.getElementById("cantidadModal").focus();
	}
	else
	{  
		if (confirm('¿Crear Movimiento?')) 
		{		 
			peticionUnica1=crearComunicacion(peticionUnica1);

			if(peticionUnica1)
			{							
				peticionUnica1.onreadystatechange = mostrarCrearMovimientoAlmacen;
				peticionUnica1.open("POST","ajax/insertarMovimientoAlmacen.php",false);
				peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
				var query_string = consultaCrearMovimientoAlmacen();
				peticionUnica1.send(query_string);
			}
		}
	}
}




function consultaCrearMovimientoAlmacen()
{	
	var consulta = "accion=crearMovimientoAlmacen";
	
	consulta +="&almacen=" + document.getElementById("almacenModal").value;
	consulta +="&fecha=" + document.getElementById("fechaModal").value;
	consulta +="&cliente=" + document.getElementById("clienteModal").value;
	consulta +="&producto=" + document.getElementById("productoModal").value;
	consulta +="&modalidad=" + document.getElementById("modalidadModal").value;
	consulta +="&hueco=" + document.getElementById("huecoModal").value;
	consulta +="&cantidad=" + document.getElementById("cantidadModal").value;
	consulta +="&observacion=" + document.getElementById("observacionModal").value;
	consulta +="&ot=" + document.getElementById("otModal").value;
	
	return consulta;	
}

function mostrarCrearMovimientoAlmacen()
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
			buscarFactura();
			
		}
	}						
}






function gestionHuecos() 
{
	if (document.getElementById("productoModal").value!="")
	{
		if (document.getElementById("modalidadModal").value=="1")
		{
			peticionUnica1=crearComunicacion(peticionUnica1);

			if(peticionUnica1)
			{							
				peticionUnica1.onreadystatechange = mostrarGestionHuecos;
				peticionUnica1.open("POST","ajax/mostrarAlmacenMovimientos.php",false);
				peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
				var query_string = consultaGestionHuecos();
				peticionUnica1.send(query_string);
			}
		}
		else
		{
			cargarListadoHuecosAlmacen();
		}
	}
	
}

function consultaGestionHuecos()
{	
	// Reutiliza mostrarAlmacenMovimientos.php: se piden todos los movimientos del producto/subcliente,
	// del mas reciente al mas antiguo, y en mostrarGestionHuecos() nos quedamos con el ultimo
	// movimiento (stock actual) de cada hueco.
	var consulta = "accion=mostrarAlmacenMovimientos";
	var campos = ['idHueco','hueco','cantidadTotal'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	var joins = ['tabla5'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
	var filtros = { idSubCliente: document.getElementById("clienteModal").value, idProducto: document.getElementById("productoModal").value };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	var order = [ { campo: 'id', dir: 'DESC' } ];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarGestionHuecos()
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
				var movimientos = res.datos;
				
				// Nos quedamos con el ultimo movimiento (el primero, ya que vienen ordenados
				// por id DESC) de cada hueco, y de esos solo los que tienen stock (cantidadTotal>0).
				var huecosVistos = {};
				var datos = [];
				var i = 0;
				while (i < movimientos.length)
				{
					var idHueco = movimientos[i]["idHueco"];
					if (!huecosVistos[idHueco])
					{
						huecosVistos[idHueco] = true;
						if (movimientos[i]["cantidadTotal"] > 0)
						{
							datos.push({ id: idHueco, hueco: movimientos[i]["hueco"] });
						}
					}
					i++;
				}
				datos.sort(function(a,b){ return a.hueco.localeCompare(b.hueco); });
				
				var contenido = "";
					
				
				var contador = 0;	
			
				while  (contador<datos.length)
				{ 
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["hueco"]+'</option>';
					contador++;	
				}		
				
				document.getElementById("huecoModal").innerHTML = contenido;	
			}
			peticionUnica1=null;
			verCantidadHueco();
			
		}
	}						
}

function generarExcel()
{
	tipoDeExel="todo";
	if (document.getElementById("radioExcelUbicaciones").checked == true)
	{
		tipoDeExel = "Ubicaciones";
	}
	else if(document.getElementById("radioExcelArticulos").checked == true)
	{
		tipoDeExel = "Articulos";
	}
	

	document.getElementById("formExcelTipo").value = tipoDeExel;
	var filtrosOperadores = construirFiltrosOperadoresMovimientos();
	document.getElementById("formExcelFiltrosOperadores").value = JSON.stringify(filtrosOperadores);
	document.getElementById("formImprimirExcelAlmacen").submit();	
}



function gestionAlbaran(id)
{
	//
	
	if (document.getElementById(id+'_Albaran').checked==true)
	{
		arrayAlbaran.push(id);
		
	}
	else
	{
		var seguir = true;
		var contador=0;
		
		while (seguir==true && contador<arrayAlbaran.length)
		{
			if (arrayAlbaran[contador]==id)
			{
				arrayAlbaran.splice(contador, 1); //borra el id del array
				seguir = false;
				
			}
			contador++;			
		}
	}
}

function gestionAlbaran2()
{
	if (arrayAlbaran.length<=0)
	{
		alert("No hay ningun movimiento seleccionado");
	}
	else
	{
		var seguir = true;
		var contador=0;
		var primerCliente = document.getElementById(arrayAlbaran[0]+"_cliente").innerHTML;
		
		while (seguir==true && contador<arrayAlbaran.length)
		{
			if (document.getElementById(arrayAlbaran[contador]+"_cliente").innerHTML!=primerCliente)
			{
				seguir = false;			
			}
			
			contador++;			
		}
		
		if (seguir == false)
		{
			var contenido = "";
			contador=0;
			while (contador<arrayAlbaran.length)
			{
				contenido += document.getElementById(arrayAlbaran[contador]+"_cliente").innerHTML + "\n";

				contador++;			
			}
			alert("Se ha seleccionado más de un Cliente:\n\n"+contenido);
		}
		else
		{
			seguir = true;
			contador=0;
			primeraModalidad = document.getElementById(arrayAlbaran[0]+"_modalidad").innerHTML;

			while (seguir==true && contador<arrayAlbaran.length)
			{
				if (document.getElementById(arrayAlbaran[contador]+"_modalidad").innerHTML!=primeraModalidad)
				{
					seguir = false;			
				}

				contador++;			
			}

			if (seguir == false)
			{
				contenido = "";
				contador=0;
				while (contador<arrayAlbaran.length)
				{
					contenido += document.getElementById(arrayAlbaran[contador]+"_modalidad").innerHTML + "\n";

					contador++;			
				}
				alert("Se ha seleccionado más de una Modalidad:\n\n"+contenido);
			}
			else
			{				
				$("#crearNuevoAlbaranModal").modal('show');
			}
		}
	}
}

function gestionAlbaran3()
{
	
	
	
	
}

function grabarAlbaranAlmacen() 
{
	if ((document.getElementById("empresaModal").value=="" && document.getElementById("direccionModal").value=="" && document.getElementById("cpModal").value=="" && document.getElementById("localidadModal").value=="" && document.getElementById("provinciaModal").value=="") || (document.getElementById("empresaModal").value!="" && document.getElementById("direccionModal").value!="" && document.getElementById("cpModal").value!="" && document.getElementById("localidadModal").value!="" && document.getElementById("provinciaModal").value!="") )
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarAlbaranAlmacen;
			peticionUnica1.open("POST","ajax/insertarAlbaranAlmacen.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGrabarAlbaranAlmacen();
			peticionUnica1.send(query_string);
		}
	}
	else
	{
		alert("Todos los campos de 'Direccion de Envío' deben estar rellenos o vacios");
	}
	
	
	
	
}

function consultaGrabarAlbaranAlmacen()
{	
	var consulta = "accion=grabarAlbaranAlmacen";
	
	
	var contenido="";
	
	var contador=0;
	while (contador<arrayAlbaran.length)
	{
		contenido += arrayAlbaran[contador] + "|||";

		contador++;			
	}
	arrayAlbaran = [];	
	
	consulta += "&idMovimientos=" + contenido;
	consulta += "&observaciones=" + document.getElementById("observacionAlbaranModal").value;
	
	consulta += "&empresa=" + document.getElementById("empresaModal").value;
	consulta += "&direccion=" + document.getElementById("direccionModal").value;
	consulta += "&cp=" + document.getElementById("cpModal").value;
	consulta += "&localidad=" + document.getElementById("localidadModal").value;
	consulta += "&provincia=" + document.getElementById("provinciaModal").value;
	
	return consulta;	
}

function mostrarGrabarAlbaranAlmacen()
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
				document.getElementById("formNumAlbaran").value = res.numeroAlbaran;	
				document.getElementById("formImprimirAlbaran").submit();
				
				peticionUnica1=null;
				buscarFactura();

				
			}
			peticionUnica1=null;
			
		}
	}						
}

function visualizarAlbaranAlmacen(idAlbaran)
{	
	document.getElementById("formNumAlbaran").value = idAlbaran;	
	document.getElementById("formImprimirAlbaran").submit();
}

function verCantidadHueco() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerCantidadHueco;
		peticionUnica1.open("POST","ajax/mostrarAlmacenMovimientos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerCantidadHueco();
		peticionUnica1.send(query_string);
	}
}

function consultaVerCantidadHueco()
{	
	var consulta = "accion=mostrarAlmacenMovimientos";
	var campos = ['cantidadTotal'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	var joins = [];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
	var filtros = { idSubCliente: document.getElementById("clienteModal").value, idProducto: document.getElementById("productoModal").value, idHueco: document.getElementById("huecoModal").value };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	var order = [ { campo: 'id', dir: 'DESC' } ];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarVerCantidadHueco()
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
				
				if (datos.length>0)
				{
					document.getElementById("cantidadModal").value = datos[0]["cantidadTotal"];
				}
				else
				{
					document.getElementById("cantidadModal").value = 0;
				}

				
			}
			peticionUnica1=null;
			
		}
	}						
}








