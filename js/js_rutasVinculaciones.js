var peticionUnica1 = null;
var permisosSoloLectura = null;


function insertarRutasConductoresVinculaciones()//para insertar en la tabla de las vinculaciones entre ruta y conductor //js_rutasVinculaciones
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarRutasConductoresVinculaciones;
		peticionUnica1.open("POST","ajax/insertarViculacionRutaConductor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarRutasConductoresVinculaciones();
		peticionUnica1.send(query_string);						
	}
		
}

function consultaInsertarRutasConductoresVinculaciones()
{	
	var consulta = "accion=insertarRutasConductoresVinculaciones";	
	consulta +="&idEmpleado="  + document.getElementById("listadoEmpleado").value;
	consulta +="&ruta="  + document.getElementById("rutaVinculacion").value;
	
	return consulta;	
}

function mostrarInsertarRutasConductoresVinculaciones()
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
				
				//alert(peticionUnica.responseText);
				
			}
			peticionUnica1=null;	
			cargarRutasConductoresVinculaciones();
		}
		
	}						
}

function cargarRutasConductoresVinculaciones()//para mostrar la tabla de las vinculaciones entre ruta y conductor //js_rutasVinculaciones
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRutasConductoresVinculaciones;
		peticionUnica1.open("POST","ajax/cargarRutasVinculadas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRutasConductoresVinculaciones();
		peticionUnica1.send(query_string);						
	}
		
}

function consultaCargarRutasConductoresVinculaciones()
{	
	var consulta = "accion=cargarRutasConductoresVinculaciones";	
	
	return consulta;	
}

function mostrarCargarRutasConductoresVinculaciones()
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
				document.getElementById("vinculacionesConductoresRutas").innerHTML="";	
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
				if (datos != "")
				{
					
					if (datos.length<=0)
					{		
							
					}
					else
					{													
						var contenido = "";					

						contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
							
						contenido += '<td><b>CONDUCTOR</b></td><td><b>RUTA</b></td>';
						
						if (!permisosSoloLectura)
						{
							contenido +='<td></td>';		
						}					
							
						contenido += '</tr>';

						var contador = 0;
						var contraste="";
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
							

							contenido += '<td align="center">'+ datos[contador]["nombreCompleto"]+'</td><td align="center">'+datos[contador]["ruta"]+'</td>';
							if (!permisosSoloLectura)
							{
								contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalle" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarVinculacionRutaConductor('+datos[contador]["id"]+')" ></td>';
							}
							
							
							
							contenido += '</tr>';

							contador++;
						}


						document.getElementById("vinculacionesConductoresRutas").innerHTML = contenido;
						
					}
				}
				
			}
			peticionUnica1=null;	
		}
			
	}						
}


///////
function eliminarVinculacionRutaConductor(id)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarVinculacionRutaConductor;
		peticionUnica1.open("POST","ajax/eliminarViculacionRutaConductor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarVinculacionRutaConductor(id);
		peticionUnica1.send(query_string);						
	}
		
}

function consultaEliminarVinculacionRutaConductor(id)
{	
	var consulta = "accion=eliminarVinculacionRutaConductor";	
	consulta +="&id="  + id;
	
	return consulta;	
}

function mostrarEliminarVinculacionRutaConductor()
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
				
				cargarRutasConductoresVinculaciones();
				
			}
		}
		peticionUnica1=null;	
		
		
	}						
}

