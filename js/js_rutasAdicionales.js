var peticionUnica1 = null;
var permisosSoloLectura = null;



function cargarRutasAdicionales() //js_rutasAdicionales
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRutasAdicionales;
		peticionUnica1.open("POST","ajax/cargarRutasAdicionales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRutasAdicionales();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRutasAdicionales()
{	
	var consulta = "accion=cargarRutasAdicionales";	
	
	var condicion = "";
	
	if (document.getElementById("clienteRutaBuscar").value != 0)
	{
		condicion = "where idCliente='"+ document.getElementById("clienteRutaBuscar").value + "'";
	}
	
	var orden = " order by " + document.getElementById("orden").value;
	
	if (document.getElementById("ordenDesc").checked==true)
	{
		orden += " desc";
	}
	else
	{
		orden += " asc";
	}
	
	condicion = condicion  + orden;
	
	consulta +="&condicion=" + condicion;
	
	return consulta;	
}

function mostrarCargarRutasAdicionales()
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
				
				var contenido = "";				
				
				contenido += '<tr class="centrarTexto">';
				contenido +='<th align="center">id</th>';
				contenido +='<th>Cliente</th>';				
				contenido +='<th class="borderIzquierdoRutas">FECHA</th>';
				contenido +='<th>HORA</th>';
				contenido +='<th class="borderIzquierdoRutas">RUTA</th>';				
				contenido +='<th class="borderIzquierdoRutas">contacto</th>';
				contenido +='<th>observaciones</th>';
				
				if (!permisosSoloLectura)
				{
					contenido +='<th></th>';
					contenido +='<th></th>';
				}
				
				contenido +='</tr>';
				
				var contador = 0;
				var contraste='';
				while  (contador<datos.length)
				{ 
			
					if (contador%2==0)
					{
						//contraste = ' class="contraste" ';
					}

					contenido += '<tr '+contraste+'>';
					
					contenido +='<td align="center"><label id="'+datos[contador]["id"]+'">'+datos[contador]["id"]+'</label></td>';
					contenido +='<td align="center"><select id="'+datos[contador]["id"]+'_cliente" style="max-width:500px;">'+datos[contador]["idCliente"]+'</select></td>';
					contenido +='<td align="center"><input type="date"   style="width: 100%;" id="'+datos[contador]["id"]+'_fecha" value="'+datos[contador]["fecha"]["date"].substring(0,10)+'"></input></td>';
										
					var valorRuta="";
					var valorHora="";
					
					if (datos[contador]["ruta"]==null)
					{
						valorRuta="";
					}
					else
					{
						valorRuta = datos[contador]["ruta"];
					}
					if (datos[contador]["hora"]==null)
					{
						valorHora="";
					}
					else
					{
						valorHora=datos[contador]["hora"]["date"].substring(11,19);
					}
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_hora" value="'+valorHora+'"></input></td>';
					contenido +='<td align="center" class="borderIzquierdoRutas"><input class="tamanio2" type="text" id="'+datos[contador]["id"]+'_ruta" value="'+valorRuta+'"></input></td>';
				
					contenido +='<td align="center" class="borderIzquierdoRutas"><input type="text"   class="tamanio7" id="'+datos[contador]["id"]+'_contacto" value="'+datos[contador]["contacto"]+'"></input></td>';	
					contenido +='<td align="center"><input type="text"  class="tamanio7" id="'+datos[contador]["id"]+'_incidencia" value="'+datos[contador]["incidencia"]+'"></input></td>';
					

					if (!permisosSoloLectura)
					{
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarRutaAdicional" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRutaAdicional('+datos[contador]["id"]+')"></td>';		
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarRutaAdicional" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="borrarRutaAdicional('+datos[contador]["id"]+')"></td>';					
					}
					
					contenido +='</tr>';
					
					contador++;					
				}
				
				
				document.getElementById("RutasAdicionales").innerHTML = contenido;
				
				contador = 0;
				
				while  (contador<datos.length)
				{ 					
					/*idInputListado = datos[contador]["id"]+'_cliente';
					cargarListadoNombreFranqueo();*/
					cargarSubClientes2(' codigo, subcliente ', 'A',datos[contador]["id"]+'_cliente');


					document.getElementById(datos[contador]["id"]+'_cliente').value = datos[contador]["idCliente"];					

					idInputListado = "";
				
					contador++;	
				}
						
			}
			peticionUnica1=null;			
		}
	}						
}

function insertarRutaAdicional() //js_rutasAdicionales
{
	if (document.getElementById("fecha").value=="")
	{
		alert("Elegir una fecha");
		document.getElementById("fecha").focus();
	}	
	else if (document.getElementById("clienteRuta").value ==0)
	{
		alert("Elegir un Cliente");
		document.getElementById("clienteRuta").focus();
	}
	else if (document.getElementById("hora").value ==0)
	{
		alert("Elegir una hora");
		document.getElementById("hora").focus();
	}
	else
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarRutaAdicional;
			peticionUnica1.open("POST","ajax/insertarRutasAdicionales.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarRutaAdicional();
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaInsertarRutaAdicional()
{	
	var consulta = "accion=insertarRegistro";	
	consulta += "&idCliente=" + document.getElementById("clienteRuta").value;	
	
	consulta += "&hora=" + document.getElementById("hora").value;
	consulta += "&ruta=" + document.getElementById("ruta").value;
	
	consulta += "&contacto=" + document.getElementById("contacto").value;
	consulta += "&incidencia=" + document.getElementById("incidencia").value;
	consulta += "&fecha=" + document.getElementById("fecha").value;
	
	return consulta;	
}

function mostrarInsertarRutaAdicional()
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
				cargarRutasAdicionales();
				//alert("registro eliminado");
				
			}
		}
		peticionUnica1=null;		
	}						
}




function modificarRutaAdicional(id) //js_rutasAdicionales
{
	if (document.getElementById(id+"_cliente").value ==0)
	{
		alert("Elegir un cliente");
		document.getElementById(id+"_cliente").focus();
	
	}
	else if (document.getElementById(id+"_fecha").value=="")
	{
		alert("Elegir una fecha");
		document.getElementById(id+"_fecha").focus();
	}
	else if (document.getElementById(id+"_hora").value=="")
	{
		alert("Elegir una hora");
		document.getElementById(id+"_hora").focus();
	}
	else if (document.getElementById(id+"_ruta").value=="")
	{
		alert("Elegir una ruta");
		document.getElementById(id+"_ruta").focus();
	}
	else
	{		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarRutaAdicional;
			peticionUnica1.open("POST","ajax/modificarRutasAdicional.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarRutaAdicional(id);
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaModificarRutaAdicional(id)
{	
	var consulta = "accion=modificarRegistro";	
	consulta += "&id=" + id;
	consulta += "&idCliente=" + document.getElementById(id+"_cliente").value;	
	consulta += "&hora=" + document.getElementById(id+"_hora").value;
	consulta += "&ruta=" + document.getElementById(id+"_ruta").value;
	
	consulta += "&contacto=" + document.getElementById(id+"_contacto").value;
	consulta += "&incidencia=" + document.getElementById(id+"_incidencia").value;
	consulta += "&fecha=" + document.getElementById(id+"_fecha").value; 
		
	
	return consulta;	
}

function mostrarModificarRutaAdicional()
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
				alert("Ruta Modificada");				
				cargarRutasAdicionales();				
			}
		}
		peticionUnica1=null;		
	}						
}

function borrarRutaAdicional(id) //js_rutasAdicionales
{
	if (confirm("¿Eliminar el registro: "+id+"?")) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarBorrarRutaAdicional;
			peticionUnica1.open("POST","ajax/eliminarRutasAdicional.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaBorrarRutaAdicional(id);
			peticionUnica1.send(query_string);						
		}
	} 
	else 
	{
	  //no hace nada
	}
	
}

function consultaBorrarRutaAdicional(id)
{	
	var consulta = "accion=eliminarRegistro";	
	consulta += "&id=" + id;		
	
	return consulta;	
}

function mostrarBorrarRutaAdicional()
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
				cargarRutasAdicionales();
				alert("registro eliminado");				
			}
		}
		peticionUnica1=null;		
	}						
}


