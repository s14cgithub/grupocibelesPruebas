var peticionUnica1 = null;
var laCondicion="";



function buscarFactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;	
	var soloHomologado = document.getElementById("proveedorHomologado").checked;
	
	if (campoAbuscar =="id" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = " + textoAbuscar;
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";
	}
	
	if (soloHomologado==true)
	{
		condicion += " and homologado=1";
	}
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	
	
	laCondicion = condicion;
	
	
	
	cargarListadoProveedores();
	
	
}



function cargarListadoProveedores()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoProveedores;		
		
		peticionUnica1.open("POST","ajax/cargarProveedor.php",false);
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoProveedores();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoProveedores()
{	
	var consulta = "accion=cargarProveedores";	
	
	consulta += "&condicion=" + reemplazarSimbolosBusqueda(laCondicion);
	
	return consulta;	
}

function mostrarCargarListadoProveedores()
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
				
					contenido += '<th>Id</th>';
					contenido += '<th>Proveedor</th>';
					contenido += '<th>Servicio</th>';
					contenido += '<th>Direccion</th>';
					contenido += '<th>CP</th>';
					contenido += '<th>Localidad</th>';
				
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
					
					
					contenido += '<td align="right">'+datos[contador]["id"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["proveedor"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["servicio"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["direccion"]+'</td>';
					contenido += '<td align="center">'+datos[contador]["cp"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["localidad"]+'</td>';
					
					contenido += '<td><input type="image" id="'+datos[contador]["codigo"]+'_cliente" value="" src="imagenes/ojo.png" style="width:15px;" onclick="irAVerProveedor('+datos[contador]["id"]+')"></td>';
								
					
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoProveedores").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}

function irAVerProveedor(idProveedor)//js_presupuestoListado
{	
	window.location.href = "proveedor.php?id="+idProveedor;
}

function gestionExportarExcelProveedores() //js_facturas
{
	
	document.getElementById("exportarCondiciones").value = laCondicion;		
	document.getElementById("formExportarExcel").submit();
}

function crearProveedor()
{
	if (document.getElementById("proveedorNuevo_nombre").value=="")
	{
		alert("Rellenar el nombre del proveedor");
		document.getElementById("proveedorNuevo_nombre").focus();
	}
	else if (document.getElementById("proveedorNuevo_nif").value=="")
	{
		alert("Rellenar el nif del proveedor");
		document.getElementById("proveedorNuevo_nif").focus();
	}
	else if (document.getElementById("proveedorNuevo_direccion").value=="")
	{
		alert("Rellenar la direccion del proveedor");
		document.getElementById("proveedorNuevo_direccion").focus();
	}
	else if (document.getElementById("proveedorNuevo_localidad").value=="")
	{
		alert("Rellenar la localidad del proveedor");
		document.getElementById("proveedorNuevo_localidad").focus();
	}
	else if (document.getElementById("proveedorNuevo_provincia").value=="")
	{
		alert("Rellenar la provincia del proveedor");
		document.getElementById("proveedorNuevo_provincia").focus();
	}
	else if (document.getElementById("proveedorNuevo_cp").value=="")
	{
		alert("Rellenar el codigo postal del proveedor");
		document.getElementById("proveedorNuevo_cp").focus();
	}
	else if (document.getElementById("proveedorNuevo_Telefono").value=="")
	{
		alert("Rellenar el telefono del proveedor");
		document.getElementById("proveedorNuevo_Telefono").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCrearProveedor;		

			peticionUnica1.open("POST","ajax/insertarProveedor.php",false);

			//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCrearProveedor();
			peticionUnica1.send(query_string);
		}
	}
}



function consultaCrearProveedor()
{	
	var consulta = "accion=insertarProveedor";	
	
	consulta += "&nombre=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_nombre").value);
	consulta += "&nif=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_nif").value);
	consulta += "&servicio=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_servicio").value);
	consulta += "&direccion=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_direccion").value);
	consulta += "&localidad=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_localidad").value);
	consulta += "&provincia=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_provincia").value);
	consulta += "&cp=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_cp").value);
	consulta += "&precioComparado=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_precioComparado").value);
	consulta += "&telefono=" + reemplazarSimbolosBusqueda(document.getElementById("proveedorNuevo_Telefono").value); 
	
	
	return consulta;	
}

function mostrarCrearProveedor()
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
				alert("El numero de pedido del nuevo proveeedor es: "+peticionUnica1.responseText);
				cargarListadoProveedores();				
			}
			peticionUnica1=null;			
		}
	}						
}










