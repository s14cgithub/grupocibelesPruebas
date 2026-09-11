var peticionUnica1 = null;

function cargarListadoEmpleado() //js_empleados (antes cargarListadoEmpleado de js_global, comentada); destino fijo: listadoEmpleado1
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarListadoEmpleado;
		peticionUnica1.open("POST","ajax/cargarListadoEmpleado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoEmpleado();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoEmpleado()
{	
	var consulta = "accion=cargarListadoEmpleado";

	var campos = ['id','nombre','apellidos'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify({}));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	consulta += "&joins=" + encodeURIComponent(JSON.stringify([]));

	var order = [{campo: 'nombre', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoEmpleado()
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
				
				var contador=0;
				var contenido="<option value=\"\">Todos</option>";
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombre"]+ ' ' + datos[contador]["apellidos"] + '</option>';
					contador++;
				}
				document.getElementById("listadoEmpleado1").innerHTML = contenido;	
							
			}
			peticionUnica1=null;
		}
	}						
}

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

	var campos = ['id','nombre','apellidos','precioHora','horasLaborales','activo'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {};

	var idEmpleado = document.getElementById("listadoEmpleado1").value;
	if (idEmpleado != "")
	{
		filtros.id = idEmpleado;
	}

	var precioHora = document.getElementById("buscarPrecioHora").value;
	if (precioHora != "")
	{
		filtros.precioHora = precioHora;
	}

	var horasLaborales = document.getElementById("buscarHorasLaborales").value;
	if (horasLaborales != "")
	{
		filtros.horasLaborales = horasLaborales;
	}

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify([]));
	consulta += "&joins=" + encodeURIComponent(JSON.stringify([]));

	var orden = document.getElementById("orden").value;
	var desc = document.getElementById("ordenDesc").checked;
	var order = [
		{ campo: orden, dir: desc ? 'DESC' : 'ASC' }
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarEmpleados()
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
				
				contenido += '<tr class="centrarTexto">';
				contenido +='<th align="center">id</th>';
				contenido +='<th>Nombre</th>';
				contenido +='<th>Apellidos</th>';
				contenido +='<th>Precio Hora</th>';
				contenido +='<th>Horas Laborales</th>';
				contenido +='<th>Activo</th>';
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

					var activoChecked = (datos[contador]["activo"]==1 || datos[contador]["activo"]==true) ? ' checked' : '';
					contenido +='<td align="center"><input type="checkbox" id="'+datos[contador]["id"]+'_activo"'+activoChecked+'></td>';
										
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
		precioHora = document.getElementById("precioHoraNuevo").value.replace(',', '.');
	}
	
	var datos = {
		nombre: document.getElementById("nombreNuevo").value,
		apellidos: document.getElementById("apellidosNuevo").value,
		precioHora: precioHora,
		horasLaborales: document.getElementById("horasLaboralNuevo").value,
		activo: document.getElementById("activoNuevo").checked ? 1 : 0
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarInsertarRegistroEmpleado()
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
		precioHora = document.getElementById(idEmpleado+"_precioHora").value.replace(',', '.');
	}
	
	consulta +="&idEmpleado="+idEmpleado;
	var datos = {
		nombre: document.getElementById(idEmpleado+"_nombre").value,
		apellidos: document.getElementById(idEmpleado+"_apellidos").value,
		precioHora: precioHora,
		horasLaborales: document.getElementById(idEmpleado+"_horasLaborales").value,
		activo: document.getElementById(idEmpleado+"_activo").checked ? 1 : 0
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarModificarRegistroEmpleado()
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
	
	var filtros = { id: idEmpleado };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarEliminarRegistroEmpleado()
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
				cargarEmpleados();
			}
			peticionUnica1=null;			
		}
	}						
}


