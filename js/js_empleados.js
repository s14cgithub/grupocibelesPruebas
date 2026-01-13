var peticionUnica1 = null;


function cargarEmpleados() //js_empleados
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarEmpleados;
		peticionUnica1.open("POST","ajax/cargarEmpleados.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarEmpleados();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarEmpleados()
{	
	var consulta = "accion=cargarEmpleados";	
	
	
	consulta +="&idEmpleado="+document.getElementById("listadoEmpleado1").value;
	consulta += "&precioHora="+document.getElementById("buscarPrecioHora").value;
	consulta += "&horasLaborales="+document.getElementById("buscarHorasLaborales").value;
	consulta += "&orden="+document.getElementById("orden").value;
	consulta += "&desc="+document.getElementById("ordenDesc").checked;
	
	
	return consulta;	
}

function mostrarCargarEmpleados()
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
				contenido +='<th>Nombre</th>';
				contenido +='<th>Apellidos</th>';
				contenido +='<th>Precio Hora</th>';
				contenido +='<th>Horas Laborales</th>';				
				contenido +='<th></th>';
				contenido +='<th></th>';
				
				contenido +='</tr>';				
				
				var contador = 0;
				var contraste='';
				while  (contador<datos.length)
				{	
			
					if (contador%2==0)
					{
						contraste = ' class="contraste" ';
					}

					contenido += '<tr '+contraste+'>';
					
					contenido +='<td align="center"><label id="'+datos[contador]["id"]+'">'+datos[contador]["id"]+'</label></td>';
					
					
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_nombre" value="'+datos[contador]["nombre"]+'"></td>';
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_apellidos" value="'+datos[contador]["apellidos"]+'"></td>';
					
					var precioHora = 0;
					if (datos[contador]["precioHora"]!=0 && datos[contador]["precioHora"]!="null" && datos[contador]["precioHora"]!=null && datos[contador]["precioHora"]!="")
					{
						precioHora =datos[contador]["precioHora"];
					}
					
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_precioHora" value="'+precioHora+'"></td>';
					
					var horas="";
					if (datos[contador]["horasLaborales"]!=null)
					{
						horas = datos[contador]["horasLaborales"]["date"].substr(datos[contador]["horasLaborales"]["date"].lastIndexOf(' ')+1,5);
					}
					
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horasLaborales" value="'+horas+'"></td>';
										
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarRegistroEmpleado('+datos[contador]["id"]+')" ></td>';	
					
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroEmpleado('+datos[contador]["id"]+')" ></td>';
					contenido +='</tr>';
					
					contador++;
					
				}
								
				document.getElementById("empleados").innerHTML = contenido;
						
			}
			peticionUnica1=null;			
		}
	}						
}


function insertarRegistroEmpleado() //js_empleados
{
	
	if (document.getElementById("nombreNuevo").value=="")
	{
		alert("Introducir un Nombre");
		document.getElementById("nombreNuevo").focus();
	}
	else if (document.getElementById("apellidosNuevo").value=="")
	{
		alert("Introducir los Apellidos");
		document.getElementById("apellidosNuevo").focus();
	}
	else if (document.getElementById("horasLaboralNuevo").value=="")
	{
		alert("Introducir las horas Laborales");
		document.getElementById("horasLaboralNuevo").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarRegistroEmpleado;
			peticionUnica1.open("POST","ajax/insertarRegistroEmpleado.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarRegistroEmpleado();
			peticionUnica1.send(query_string);
		}
	}
	
}

function consultaInsertarRegistroEmpleado()
{	
	var consulta = "accion=insertarRegistroEmpleado";	
	
	var precioHora = 0;
	if (document.getElementById("precioHoraNuevo").value==0 || document.getElementById("precioHoraNuevo").value=='' ||document.getElementById("precioHoraNuevo").value==null || document.getElementById("precioHoraNuevo").value=="null")
	{
		precioHora = 0;
	}
	else
	{
		precioHora = document.getElementById("precioHoraNuevo").value;
	}
	
	consulta += "&nombre="+document.getElementById("nombreNuevo").value ;
	consulta += "&apellidos="+document.getElementById("apellidosNuevo").value;
	consulta += "&precioHora="+precioHora;
	consulta += "&horasLaborales="+document.getElementById("horasLaboralNuevo").value;
	
	return consulta;	
}

function mostrarInsertarRegistroEmpleado()
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
				cargarEmpleados();
			}
			peticionUnica1=null;			
		}
	}						
}





function modificarRegistroEmpleado(idEmpleado) //js_empleados
{	
	
	if (document.getElementById(idEmpleado+"_nombre").value=="")
	{
		alert("Introducir un Nombre");
		document.getElementById(idEmpleado+"_nombre").focus();
	}
	else if (document.getElementById(idEmpleado+"_apellidos").value=="")
	{
		alert("Introducir los Apellidos");
		document.getElementById(idEmpleado+"_apellidos").focus();
	}
	else if (document.getElementById(idEmpleado+"_horasLaborales").value=="")
	{
		alert("Introducir las horas Laborales");
		document.getElementById(idEmpleado+"_horasLaborales").focus();
	}
	else
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarRegistroEmpleado;
			peticionUnica1.open("POST","ajax/modificarRegistroEmpleado.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarRegistroEmpleado(idEmpleado);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarRegistroEmpleado(idEmpleado)
{	
	var consulta = "accion=modificarRegistroEmpleado";	
	
	var precioHora = 0;
	if (document.getElementById(idEmpleado+"_precioHora").value==0 || document.getElementById(idEmpleado+"_precioHora").value=='' ||document.getElementById(idEmpleado+"_precioHora").value==null || document.getElementById(idEmpleado+"_precioHora").value=="null")
	{
		precioHora = 0;
	}
	else
	{
		precioHora = document.getElementById(idEmpleado+"_precioHora").value;
	}
	
	consulta +="&idEmpleado="+idEmpleado;
	consulta += "&nombre="+document.getElementById(idEmpleado+"_nombre").value ;
	consulta += "&apellidos="+document.getElementById(idEmpleado+"_apellidos").value;
	consulta += "&precioHora="+precioHora;
	consulta += "&horasLaborales="+document.getElementById(idEmpleado+"_horasLaborales").value;	
	
	return consulta;	
}

function mostrarModificarRegistroEmpleado()
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

function eliminarRegistroEmpleado(idEmpleado) //js_empleados
{	
	
	if (confirm("¿Eliminar el registro: "+idEmpleado+"?")) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarRegistroEmpleado;
			peticionUnica1.open("POST","ajax/eliminarRegistroEmpleado.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarRegistroEmpleado(idEmpleado);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaEliminarRegistroEmpleado(idEmpleado)
{	
	var consulta = "accion=eliminarRegistroEmpleado";	
	
	consulta +="&idEmpleado="+idEmpleado;
	
	return consulta;	
}

function mostrarEliminarRegistroEmpleado()
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
				cargarEmpleados();
			}
			peticionUnica1=null;			
		}
	}						
}


