var peticionUnica1 = null;
var permisosSoloLectura = null;

function cargarRutasPlantilla() //js_rutas
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRutasPlantilla;
		peticionUnica1.open("POST","ajax/cargarRutasPlantillas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRutasPlantilla();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRutasPlantilla()
{	
	var consulta = "accion=cargarRutasPlantilla";	
	
	var condicion = "";
	
	if (document.getElementById("clienteRutaBuscar").value != 0 && document.getElementById("clienteRutaBuscar").value !='todos')
	{
		condicion = "where idCliente='"+ document.getElementById("clienteRutaBuscar").value + "'";
	}

	if (document.getElementById("rutaRutaBuscar").value!="")
	{
		if (condicion!="")
		{
			condicion += " and (lunesRuta = "+document.getElementById("rutaRutaBuscar").value+" or martesRuta = "+document.getElementById("rutaRutaBuscar").value+" or miercolesRuta = "+document.getElementById("rutaRutaBuscar").value+" or juevesRuta = "+document.getElementById("rutaRutaBuscar").value+" or viernesRuta = "+document.getElementById("rutaRutaBuscar").value+")";
		}
		else 
		{
			condicion += " where (lunesRuta = "+document.getElementById("rutaRutaBuscar").value+" or martesRuta = "+document.getElementById("rutaRutaBuscar").value+" or miercolesRuta = "+document.getElementById("rutaRutaBuscar").value+" or juevesRuta = "+document.getElementById("rutaRutaBuscar").value+" or viernesRuta = "+document.getElementById("rutaRutaBuscar").value+")";
		}
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
	//consulta +="&orden=" + orden;
	
	return consulta;	
}

function mostrarCargarRutasPlantilla()
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
				contenido +='<th align="center"></th>';
				
				contenido +='<th></th>';		
				contenido +='<th colspan="2" class="borderIzquierdoRutas">LUNES</th>';
				contenido +='<th colspan="2" class="borderIzquierdoRutas">MARTES</th>';
				contenido +='<th colspan="2" class="borderIzquierdoRutas">MIERCOLES</th>';
				contenido +='<th colspan="2" class="borderIzquierdoRutas">JUEVES</th>';
				contenido +='<th colspan="2" class="borderIzquierdoRutas">VIERNES</th>';
				contenido +='<th  class="borderIzquierdoRutas"></th>';
				contenido +='<th></th>';
				contenido +='</tr>';
				
				contenido += '<tr class="centrarTexto">';
				contenido +='<th align="center">id</th>';
				contenido +='<th>Cliente</th>';				
				contenido +='<th class="borderIzquierdoRutas">RUTA</th>';
				contenido +='<th>HORA</th>';
				contenido +='<th class="borderIzquierdoRutas">RUTA</th>';
				contenido +='<th>HORA</th>';
				contenido +='<th class="borderIzquierdoRutas">RUTA</th>';
				contenido +='<th>HORA</th>';
				contenido +='<th class="borderIzquierdoRutas">RUTA</th>';
				contenido +='<th>HORA</th>';
				contenido +='<th class="borderIzquierdoRutas">RUTA</th>';
				contenido +='<th>HORA</th>';
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
					
					contenido +='<td align="center"><select id="'+datos[contador]["id"]+'_cliente"  style="max-width:500px;"></select></td>';
					//contenido +='<td align="left">'+datos[contador]["subcliente"]+'</td>';
					
										
					var valorRuta="";
					var valorHora="";
					
					if (datos[contador]["lunesRuta"]==null)
					{
						valorRuta="";
					}
					else
					{
						valorRuta = datos[contador]["lunesRuta"];
					}
					if (datos[contador]["lunesHora"]==null)
					{
						valorHora="";
					}
					else
					{
						valorHora=datos[contador]["lunesHora"]["date"].substring(11,19);
					}
					
					contenido +='<td align="center" class="borderIzquierdoRutas"><input class="tamanio2" type="text" id="'+datos[contador]["id"]+'_LRuta" value="'+valorRuta+'"></input></td>';		
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_LHora" value="'+valorHora+'"></input></td>';					
					if (datos[contador]["martesRuta"]==null || datos[contador]["martesRuta"]=="0")
					{
						valorRuta="";
					}
					else
					{
						valorRuta = datos[contador]["martesRuta"];
					}
					if (datos[contador]["martesHora"]==null)
					{
						valorHora="";
					}
					else
					{
						valorHora=datos[contador]["martesHora"]["date"].substring(11,19);
					}
					contenido +='<td align="center" class="borderIzquierdoRutas"><input class="tamanio2" type="text" id="'+datos[contador]["id"]+'_MRuta" value="'+valorRuta+'"></input></td>';
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_MHora" value="'+valorHora+'"></input></td>';
					
					if (datos[contador]["miercolesRuta"]==null || datos[contador]["miercolesRuta"]=="0")
					{
						valorRuta="";
					}
					else
					{
						valorRuta = datos[contador]["miercolesRuta"];
					}
					if (datos[contador]["miercolesHora"]==null)
					{
						valorHora="";
					}
					else
					{
						valorHora=datos[contador]["miercolesHora"]["date"].substring(11,19);
					}
					contenido +='<td align="center" class="borderIzquierdoRutas"><input class="tamanio2" type="text" id="'+datos[contador]["id"]+'_XRuta" value="'+valorRuta+'"></input></td>';
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_XHora" value="'+valorHora+'"></input></td>';
					
					if (datos[contador]["juevesRuta"]==null)
					{
						valorRuta="";
					}
					else
					{
						valorRuta = datos[contador]["juevesRuta"];
					}
					if (datos[contador]["juevesHora"]==null)
					{
						valorHora="";
					}
					else
					{
						valorHora=datos[contador]["juevesHora"]["date"].substring(11,19);
					}
					contenido +='<td align="center" class="borderIzquierdoRutas"><input class="tamanio2" type="text" id="'+datos[contador]["id"]+'_JRuta" value="'+valorRuta+'"></input></td>';
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_JHora" value="'+valorHora+'"></input></td>';
					
					if (datos[contador]["viernesRuta"]==null)
					{
						valorRuta="";
					}
					else
					{
						valorRuta = datos[contador]["viernesRuta"];
					}
					if (datos[contador]["viernesHora"]==null)
					{
						valorHora="";
					}
					else
					{
						valorHora=datos[contador]["viernesHora"]["date"].substring(11,19);
					}
					contenido +='<td align="center" class="borderIzquierdoRutas"><input class="tamanio2" type="text" id="'+datos[contador]["id"]+'_VRuta" value="'+valorRuta+'"></input></td>';
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_VHora" value="'+valorHora+'"></input></td>';
				
					contenido +='<td align="center" class="borderIzquierdoRutas"><input type="text"   style="width: 100%;" id="'+datos[contador]["id"]+'_contacto" value="'+datos[contador]["contacto"]+'"></input></td>';	
					contenido +='<td align="center"><input type="text"   style="width: 100%;" id="'+datos[contador]["id"]+'_incidencia" value="'+datos[contador]["incidencia"]+'"></input></td>';
					
					if (!permisosSoloLectura)
					{
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarRutaPlantilla" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRutaPlantilla('+datos[contador]["id"]+')"></td>';		
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarRutaPlantilla" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="borrarRutaPlantilla('+datos[contador]["id"]+')"></td>';
						
					}
					
					contenido +='</tr>';

					contador++;					
				}
				
				
				document.getElementById("plantillaRutas").innerHTML = contenido;
				
				contador = 0;
				
				while  (contador<datos.length)
				{ 					
					//idInputListado = datos[contador]["id"]+'_cliente';
					//cargarListadoNombreFranqueo();
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

function insertarRutaPlantilla() //js_rutas
{
	if (document.getElementById("clienteRuta").value ==0)
	{
		alert("Elegir un Cliente");
	}
	else
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarRutaPlantilla;
			peticionUnica1.open("POST","ajax/insertarRutasPlantilla.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarRutaPlantilla();
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaInsertarRutaPlantilla()
{	
	var consulta = "accion=insertarRegistro";	
	consulta += "&idCliente=" + document.getElementById("clienteRuta").value;	
	consulta += "&LR=" + document.getElementById("LR").value;
	consulta += "&LH=" + document.getElementById("LH").value;	
	consulta += "&MR=" + document.getElementById("MR").value;
	consulta += "&MH=" + document.getElementById("MH").value;
	consulta += "&XR=" + document.getElementById("XR").value;
	consulta += "&XH=" + document.getElementById("XH").value;
	consulta += "&JR=" + document.getElementById("JR").value;
	consulta += "&JH=" + document.getElementById("JH").value;
	consulta += "&VR=" + document.getElementById("VR").value;
	consulta += "&VH=" + document.getElementById("VH").value;
	
	consulta += "&contacto=" + document.getElementById("contacto").value;
	consulta += "&incidencia=" + document.getElementById("incidencia").value;

	
	return consulta;	
}

function mostrarInsertarRutaPlantilla()
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
				cargarRutasPlantilla();
				//alert("registro eliminado");
				
			}
		}
		peticionUnica1=null;		
	}						
}


function modificarRutaPlantilla(id) //js_rutas
{
	if (document.getElementById(id+"_cliente").value ==0)
	{
		alert("Elegir un cliente");
		document.getElementById(id+"_cliente").focus();	
	}
	else
	{		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarRutaPlantilla;
			peticionUnica1.open("POST","ajax/modificarRutasPlantilla.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarRutaPlantilla(id);
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaModificarRutaPlantilla(id)
{	
	var consulta = "accion=modificarRegistro";	
	consulta += "&id=" + id;
	consulta += "&idCliente=" + document.getElementById(id+"_cliente").value;
	consulta += "&Lruta=" + document.getElementById(id+"_LRuta").value;
	consulta += "&Lhora=" + document.getElementById(id+"_LHora").value;	
	consulta += "&Mruta=" + document.getElementById(id+"_MRuta").value;
	consulta += "&Mhora=" + document.getElementById(id+"_MHora").value;
	consulta += "&Xruta=" + document.getElementById(id+"_XRuta").value;
	consulta += "&Xhora=" + document.getElementById(id+"_XHora").value;
	consulta += "&Jruta=" + document.getElementById(id+"_JRuta").value;
	consulta += "&Jhora=" + document.getElementById(id+"_JHora").value;
	consulta += "&Vruta=" + document.getElementById(id+"_VRuta").value;
	consulta += "&Vhora=" + document.getElementById(id+"_VHora").value;
	
	consulta += "&contacto=" + document.getElementById(id+"_contacto").value;
	consulta += "&incidencia=" + document.getElementById(id+"_incidencia").value;
		
	//alert(consulta);
	return consulta;	
}

function mostrarModificarRutaPlantilla()
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
				cargarRutasPlantilla();			
				
			}
		}
		peticionUnica1=null;		
	}						
}

function borrarRutaPlantilla(id) //js_rutas
{
	if (confirm("¿Eliminar el registro: "+id+"?")) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarBorrarRutaPlantilla;
			peticionUnica1.open("POST","ajax/eliminarRutasPlantilla.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaBorrarRutaPlantilla(id);
			peticionUnica1.send(query_string);						
		}
	} 
	else 
	{
	  //no hace nada
	}
	
}

function consultaBorrarRutaPlantilla(id)
{	
	var consulta = "accion=eliminarRegistro";	
	consulta += "&id=" + id;		
	
	return consulta;	
}

function mostrarBorrarRutaPlantilla()
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
				cargarRutasPlantilla();
				alert("registro eliminado");
				
			}
		}
		peticionUnica1=null;		
	}						
}





