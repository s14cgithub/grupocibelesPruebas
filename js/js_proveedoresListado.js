var peticionUnica1 = null;
var laCondicion="";



var losFiltros = {};
var losFiltrosOperadores = [];
var losFiltrosLike = [];
var elOrder = [];

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

	// filtros estructurados (nuevo formato) para ajax/cargarProveedor.php
	losFiltros = {};
	losFiltrosLike = [];

	if (campoAbuscar =="id" && textoAbuscar!="")
	{
		losFiltros.id = textoAbuscar;
	}
	else if (textoAbuscar != "")
	{
		losFiltrosLike = [{campo: campoAbuscar, valor: textoAbuscar}];
	}

	if (soloHomologado==true)
	{
		losFiltros.homologado = 1;
	}

	elOrder = [{campo: orden, dir: desc ? "DESC" : "ASC"}];
	
	
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
	var campos = ['id','proveedor','servicio','direccion','cp','localidad'];

	var consulta = "accion=cargarProveedores";	
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(losFiltros));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(losFiltrosLike));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(elOrder));
	
	return consulta;	
}

function mostrarCargarListadoProveedores()
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
	var datos = {
		proveedor: document.getElementById("proveedorNuevo_nombre").value,
		nif: document.getElementById("proveedorNuevo_nif").value,
		servicio: document.getElementById("proveedorNuevo_servicio").value,
		direccion: document.getElementById("proveedorNuevo_direccion").value,
		localidad: document.getElementById("proveedorNuevo_localidad").value,
		provincia: document.getElementById("proveedorNuevo_provincia").value,
		cp: document.getElementById("proveedorNuevo_cp").value,
		precioComparado: document.getElementById("proveedorNuevo_precioComparado").value,
		telefono: document.getElementById("proveedorNuevo_Telefono").value
	};

	var consulta = "accion=insertarProveedor";	
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarCrearProveedor()
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
				alert("El numero de pedido del nuevo proveeedor es: "+res.datos.idProveedor);
				cargarListadoProveedores();				
			}
			peticionUnica1=null;			
		}
	}						
}










