var peticionUnica1 = null;

var laCondicion="";

function buscarFactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;	
		
	condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;

	
	cargarProveedores();
	
}



function cargarProveedores()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarProveedores;
		peticionUnica1.open("POST","ajax/mostrarAlmacenProveedores.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarProveedores();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarProveedores()
{	
	var consulta = "accion=cargarProveedores";	
	consulta += "&condicion=" + reemplazarSimbolosBusqueda(laCondicion);
	
	return consulta;	
}

function mostrarCargarProveedores()
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
				var contador = 0;	
				contenido += '<th style="text-align:center;">Proveedor</th>';
				while  (contador<datos.length)
				{ 
					contenido += '<tr>';
					contenido += '<td> <input type="text" id="'+datos[contador]["id"]+'_proveedor" value="'+datos[contador]["nombre"]+'"></input></td>';	
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarProveedor('+datos[contador]["id"]+')"></td>';
					
					contenido += '</tr>';
					contador++;	
				}
				
				document.getElementById("historicoProveedor").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}

function guardarProveedor()
{
	if (document.getElementById("nombreProveedor").value=="")
	{
		alert("Introducir un Proveedor");
		document.getElementById("nombreProveedor").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGuardarProveedor;
			peticionUnica1.open("POST","ajax/insertarAlmacenProveedor.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGuardarProveedor();
			peticionUnica1.send(query_string);
		}
	}
	
	
}

function consultaGuardarProveedor()
{	
	var consulta = "accion=insertarProveedor";	
	consulta += "&proveedor="+document.getElementById("nombreProveedor").value;

	
	return consulta;	
}

function mostrarGuardarProveedor()
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
				
				document.getElementById("nombreProveedor").value = "";			
			}
			peticionUnica1=null;
			cargarProveedores();
			
		}
	}						
}


function modificarProveedor(id)
{
	if (document.getElementById(id+'_proveedor').value=="")
	{
		alert("Introducir un Proveedor");
		document.getElementById(id+'_proveedor').focus();
	}	
	else if (confirm('¿Modificar Proveedor?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarProveedor;
			peticionUnica1.open("POST","ajax/modificarAlmacenProveedor.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarProveedor(id);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarProveedor(id)
{	
	var consulta = "accion=modificarProveedor";	
	consulta += "&id="+id;
	consulta += "&proveedor=" + document.getElementById(id+'_proveedor').value;

	
	return consulta;	
}

function mostrarModificarProveedor()
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










