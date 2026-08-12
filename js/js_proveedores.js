var peticionUnica1 = null;

var permisosSoloLectura = false;







function cargarUnProveedor()
{		
	//document.getElementById("buscarCampo").value = "codigo";
	let params = new URLSearchParams(window.location.search);
	var idProveedor= params.get("id");
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoProveedores;
		peticionUnica1.open("POST","ajax/cargarProveedor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoProveedores(idProveedor);
		peticionUnica1.send(query_string);				
	}
	
}

function consultaCargarListadoProveedores(idProveedor)
{	
	var campos = ['id','proveedor','nif','servicio','direccion','localidad','provincia','cp','precioComparado','fechaAlta','homologado','deshomologado'];
	var filtros = {id: idProveedor};

	var consulta = "accion=cargarProveedores";
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
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
				
				if (datos.length>0)
				{
					
					document.getElementById("proveedor_id").value = datos[0]["id"];
					document.getElementById("proveedor_nombre").value = datos[0]["proveedor"];
					document.getElementById("proveedor_nif").value = datos[0]["nif"];
					document.getElementById("proveedor_servicio").value = datos[0]["servicio"];
					document.getElementById("proveedor_direccion").value = datos[0]["direccion"];
					document.getElementById("proveedor_localidad").value = datos[0]["localidad"];	
					document.getElementById("proveedor_provincia").value = datos[0]["provincia"];
					document.getElementById("proveedor_cp").value = datos[0]["cp"];
					document.getElementById("proveedor_precioComparado").value = datos[0]["precioComparado"];
					document.getElementById("proveedor_fechaAlta").value = datos[0]["fechaAlta"]["date"].substring(0,10);
					document.getElementById("proveedor_homologado").checked = datos[0]["homologado"];
					document.getElementById("proveedor_deshomologado").value = datos[0]["deshomologado"] 

					cargarProveedorContactos();	
					
				}
				else
				{
					alert("No se ha encontrado ningun resultado");
				}
			}
			peticionUnica1=null;
		}
	}						
}

function cargarProveedorContactos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarProveedorContactos;
		peticionUnica1.open("POST","ajax/cargarProveedorContactos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarProveedorContactos();
		peticionUnica1.send(query_string);						
	}
	
}

function consultaCargarProveedorContactos()
{	
	let params = new URLSearchParams(window.location.search);
	var idProveedor= params.get("id");

	var campos = ['id','idSexo','nombre','apellidos','departamento','cargo','telefono','movil','email','comentario','sexo'];
	var filtros = {idCliente: idProveedor};

	var consulta = "accion=cargarProveedorContactos";	
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;
}

function mostrarCargarProveedorContactos()
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
				
				var contenido="<tr><td>&nbsp&nbsp</td></tr><tr><td colspan='6'><center><h2>CONTACTOS</h2></center></td></tr>";
				var contador = 0;
				
				while  (contador<datos.length)
				{
					
					contenido +='<tr>';
					//contenido += '<td align="right">Sexo</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoSexo" name="'+datos[contador]["id"]+'_contactoSexo" style="width: 100%;" value="'+datos[contador]["sexo"]+'"></input></td>';
					
					if (datos[contador]["idSexo"]==1)
					{
						contenido += '<td align="right">Sexo</td><td><select id="'+datos[contador]["id"]+'_contactoSexo" name="'+datos[contador]["id"]+'_contactoSexo"><option value="1" selected>Hombre</option><option value="2">Mujer</option><option value="3"></option></select></td>';
					}
					else if (datos[contador]["idSexo"]==2)
					{
						contenido += '<td align="right">Sexo</td><td><select id="'+datos[contador]["id"]+'_contactoSexo" name="'+datos[contador]["id"]+'_contactoSexo"><option value="1">Hombre</option><option value="2" selected>Mujer</option><option value="3"></option></select></td>';
					}
					else if (datos[contador]["idSexo"]==3)
					{
						contenido += '<td align="right">Sexo</td><td><select id="'+datos[contador]["id"]+'_contactoSexo" name="'+datos[contador]["id"]+'_contactoSexo"><option value="1">Hombre</option><option value="2">Mujer</option><option value="3" selected></option></select></td>';
					}
					
					
					
					contenido += '<td align="right">Nombre</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoNombre" name="'+datos[contador]["id"]+'_contactoNombre" style="width: 100%;" value="'+datos[contador]["nombre"]+'"></input></td>';
					
					contenido += '<td align="right">Apellidos</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoApellidos" name="'+datos[contador]["id"]+'_contactoApellidos" style="width: 100%;" value="'+datos[contador]["apellidos"]+'"></input></td>';
					
					if (!permisosSoloLectura)
					{
						contenido += '<td rowspan="2"><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarContactoProveedor('+datos[contador]["id"]+')"></td>';
					}
					
					
					contenido +='</tr><tr>';
					
					contenido += '<td align="right">Departamento</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoDepartamento" name="'+datos[contador]["id"]+'_contactoDepartamento" style="width: 100%;" value="'+datos[contador]["departamento"]+'"></input></td>';
					
					contenido += '<td align="right">cargo</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoCargo" name="'+datos[contador]["id"]+'_contactoCargo" style="width: 100%;" value="'+datos[contador]["cargo"]+'"></input></td>';
					
					contenido += '<td align="right">Telefono</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoTelefono" name="'+datos[contador]["id"]+'_contactoTelefono" style="width: 100%;" value="'+datos[contador]["telefono"]+'"></input></td></tr>';
					
					
					contenido +='</tr><tr>';
					
					contenido += '<td align="right">Movil</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoMovil" name="'+datos[contador]["id"]+'_contactoMovil" style="width: 100%;" value="'+datos[contador]["movil"]+'"></input></td>';
					
					contenido += '<td align="right">Email</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoEmail" name="'+datos[contador]["id"]+'_contactoEmail" style="width: 100%;" value="'+datos[contador]["email"]+'"></input></td>';
					
					
					contenido +='<td>Comentario</td><td><textarea style="width: 100%;" id="'+datos[contador]["id"]+'_contactoComentario">'+datos[contador]["comentario"]+'</textarea></td>';
					
					
					if (!permisosSoloLectura)
					{
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarContactoProveedor('+datos[contador]["id"]+')"></td>';
					}
					
					contenido +='</tr>';
			
					contenido +='<tr><td colspan="6"><hr></td></tr>';
					
					contador++;

				}
				
				
				if (!permisosSoloLectura)
				{
					contenido +='<tr>';
					contenido += '<td align="right">Sexo</td><td><select id="contSexo" name="contSexo"><option value="1">Hombre</option><option value="2">Mujer</option><option value="3" selected></option></select></td>';

					contenido += '<td align="right">Nombre</td><td><input type="text" id="contNombre" name="contNombre" style="width: 100%;" value=""></input></td>';

					contenido += '<td align="right">Apellidos</td><td><input type="text" id="contApellidos" name="contApellidos" style="width: 100%;" value=""></input></td></tr>';

					contenido +='</tr><tr>';

					contenido += '<td align="right">Departamento</td><td><input type="text" id="contDepartamento" name="contDepartamento" style="width: 100%;" value=""></input></td>';

					contenido += '<td align="right">cargo</td><td><input type="text" id="contCargo" name="contCargo" style="width: 100%;" value=""></input></td>';

					contenido += '<td align="right">Telefono</td><td><input type="text" id="contTelefono" name="contTelefono" style="width: 100%;" value=""></input></td></tr>';


					contenido +='</tr><tr>';

					contenido += '<td align="right">Movil</td><td><input type="text" id="contMovil" name="contMovil" style="width: 100%;" value=""></input></td>';

					contenido += '<td align="right">Email</td><td><input type="text" id="contEmail" name="contEmail" style="width: 100%;" value=""></input></td>';


					contenido +='<td align="right">Comentario</td><td colspan="1"><textarea style="width: 100%;" id="contComentario" name="contComentario"></textarea></td></tr>';




					contenido += '<tr><td colspan="6" align="center"><button type="button" class="btn btn-info" onClick="insertarContactoProveedor()" id="anadirLosContactos">añadir contacto</button></td></tr>';
				}
	
				
				
				document.getElementById("verProveedorContactos").innerHTML = contenido; 
			}
			peticionUnica1=null;
		}
	}						
}




function modificarContactoProveedor(idContacto)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarContactoProveedor;
		peticionUnica1.open("POST","ajax/modificarContactoProveedor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = constulaModificarContactoProveedor(idContacto);
		peticionUnica1.send(query_string);						
	}
}

function constulaModificarContactoProveedor(idContacto)
{	
	var datos = {
		idSexo: document.getElementById(idContacto + "_contactoSexo").value,
		nombre: document.getElementById(idContacto + "_contactoNombre").value,
		apellidos: document.getElementById(idContacto + "_contactoApellidos").value,
		departamento: document.getElementById(idContacto + "_contactoDepartamento").value,
		cargo: document.getElementById(idContacto + "_contactoCargo").value,
		telefono: document.getElementById(idContacto + "_contactoTelefono").value,
		movil: document.getElementById(idContacto + "_contactoMovil").value,
		email: document.getElementById(idContacto + "_contactoEmail").value,
		comentario: document.getElementById(idContacto + "_contactoComentario").value
	};
	var filtros = {id: idContacto};

	var consulta = "accion=modificarContactoProveedor";
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarModificarContactoProveedor()
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
				
				cargarProveedorContactos();
				
			}
			peticionUnica1=null;
				
		}
	}						
}


function modificarProveedor()			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarProveedor;
		peticionUnica1.open("POST","ajax/modificarProveedor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarProveedor();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarProveedor()
{	
	var datos = {
		proveedor: reemplazarSimbolos2(document.getElementById("proveedor_nombre").value),
		nif: document.getElementById("proveedor_nif").value,
		servicio: document.getElementById("proveedor_servicio").value,
		direccion: document.getElementById("proveedor_direccion").value,
		localidad: document.getElementById("proveedor_localidad").value,
		provincia: document.getElementById("proveedor_provincia").value,
		cp: document.getElementById("proveedor_cp").value,
		precioComparado: document.getElementById("proveedor_precioComparado").value,
		homologado: document.getElementById("proveedor_homologado").checked ? 1 : 0,
		deshomologado: document.getElementById("proveedor_deshomologado").value
	};
	var filtros = {id: document.getElementById("proveedor_id").value};

	var consulta = "accion=modificarProveedor";	
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;
}


function mostrarModificarProveedor()
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
				alert("Proveedor Modificado");
				//cargarListadoPFpendientes();				
			}
				
		}
	}						
}

function insertarContactoProveedor()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarContactoProveedor;
		peticionUnica1.open("POST","ajax/insertarContactoProveedor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarContactoProveedor();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarContactoProveedor()
{	
	var datos = {
		idCliente: document.getElementById("proveedor_id").value,
		idSexo: document.getElementById("contSexo").value,
		nombre: document.getElementById("contNombre").value,
		apellidos: document.getElementById("contApellidos").value,
		departamento: document.getElementById("contDepartamento").value,
		cargo: document.getElementById("contCargo").value,
		telefono: document.getElementById("contTelefono").value,
		movil: document.getElementById("contMovil").value,
		email: document.getElementById("contEmail").value,
		comentario: document.getElementById("contComentario").value
	};

	var consulta = "accion=insertarContactoProveedor";	
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarInsertarContactoProveedor()
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
				cargarProveedorContactos();
				
				document.getElementById("contSexo").value= "";
				document.getElementById("contNombre").value= "";
				document.getElementById("contApellidos").value= "";
				document.getElementById("contDepartamento").value= "";
				document.getElementById("contCargo").value= "";
				document.getElementById("contTelefono").value= "";
				document.getElementById("contMovil").value= "";
				document.getElementById("contEmail").value= "";
				document.getElementById("contComentario").value= "";				
			}
			peticionUnica1=null;
		}
	}						
}


function eliminarContactoProveedor(idContacto)
{
	if (confirm('¿Borrar Contacto?')) 
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarContactoProveedor;
			peticionUnica1.open("POST","ajax/eliminarContactoProveedor.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarContactoProveedor(idContacto);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaEliminarContactoProveedor(idContacto)
{	
	var filtros = {id: idContacto};

	var consulta = "accion=eliminarContactoProveedor";	
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;
}


function mostrarEliminarContactoProveedor()
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
				cargarProveedorContactos();				
			}
				
		}
	}						
}

function gestionHomolodgado()
{
	if (document.getElementById("proveedor_homologado").checked == true)
	{
		document.getElementById("proveedor_deshomologado").disabled=true;
	}
	else
	{
		document.getElementById("proveedor_deshomologado").disabled=false;
	}
}















