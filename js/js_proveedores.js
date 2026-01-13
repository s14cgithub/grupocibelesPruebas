var peticionUnica1 = null;

var permisosSoloLectura = false;







function cargarUnProveedor()
{		
	//document.getElementById("buscarCampo").value = "codigo";
	let params = new URLSearchParams(window.location.search);
	var idProveedor= params.get("id");
	
	var condicion = " where id = " + idProveedor;	
		
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoProveedores;
		peticionUnica1.open("POST","ajax/cargarProveedor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoProveedores(condicion);
		peticionUnica1.send(query_string);				
	}
	
}

function consultaCargarListadoProveedores(condicion)
{	
	var consulta = "accion=cargarProveedores";
	
	consulta += "&condicion="+(condicion);
	
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
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
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
	var consulta = "accion=cargarProveedorContactos";	
	
	let params = new URLSearchParams(window.location.search);
	var idProveedor= params.get("id");
	
	var condicion = " where idCliente = " + idProveedor;
	consulta +="&condicion=" + condicion;
	
	
	return consulta;
}

function mostrarCargarProveedorContactos()
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
	var consulta = "accion=modificarProveedor";
	
	consulta +="&idContacto="+idContacto;
	
	consulta +="&idSexo=" + document.getElementById(idContacto + "_contactoSexo").value;
	consulta +="&nombre=" + document.getElementById(idContacto + "_contactoNombre").value; 
	consulta +="&apellidos=" + document.getElementById(idContacto + "_contactoApellidos").value; 
	consulta +="&departamento=" + document.getElementById(idContacto + "_contactoDepartamento").value; 
	consulta +="&cargo=" + document.getElementById(idContacto + "_contactoCargo").value; 
	consulta +="&telefono=" + document.getElementById(idContacto + "_contactoTelefono").value; 
	consulta +="&movil=" + document.getElementById(idContacto + "_contactoMovil").value; 
	consulta +="&email=" + document.getElementById(idContacto + "_contactoEmail").value; 
	consulta +="&comentario=" + document.getElementById(idContacto + "_contactoComentario").value; 
				
		
	
	return consulta;	
}

function mostrarModificarContactoProveedor()
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
				peticionUnica1=null;
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
	var consulta = "accion=modificarProveedor";	
	
	consulta +="&idProveedor=" + document.getElementById("proveedor_id").value;	
	consulta +="&nombre=" + reemplazarSimbolos2(document.getElementById("proveedor_nombre").value);	
	consulta +="&nif=" + document.getElementById("proveedor_nif").value;	
	consulta +="&servicio=" + document.getElementById("proveedor_servicio").value;
	
	consulta +="&direccion=" + document.getElementById("proveedor_direccion").value;	
	consulta +="&localidad=" + document.getElementById("proveedor_localidad").value;	
	consulta +="&provincia=" + document.getElementById("proveedor_provincia").value;	
	consulta +="&cp=" + document.getElementById("proveedor_cp").value;	
	
	
	
	consulta +="&precioComparado=" + document.getElementById("proveedor_precioComparado").value;	
	consulta +="&fechaAlta=" + document.getElementById("proveedor_fechaAlta").value;
	consulta +="&homologado=" + document.getElementById("proveedor_homologado").checked;
	consulta +="&motivoDeshomologado=" + document.getElementById("proveedor_deshomologado").value;
	
		
	
	
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
	var consulta = "accion=insertarContactoProveedor";	
	consulta += "&idProveedor="+document.getElementById("proveedor_id").value;
	consulta += "&idSexo="+document.getElementById("contSexo").value;
	consulta += "&nombre="+document.getElementById("contNombre").value;
	consulta += "&apellidos="+document.getElementById("contApellidos").value;
	consulta += "&departamento="+document.getElementById("contDepartamento").value;
	consulta += "&cargo="+document.getElementById("contCargo").value;
	consulta += "&telefono="+document.getElementById("contTelefono").value;
	consulta += "&movil="+document.getElementById("contMovil").value;
	consulta += "&email="+document.getElementById("contEmail").value;
	consulta += "&comentario="+document.getElementById("contComentario").value;	
	
	
	return consulta;	
}

function mostrarInsertarContactoProveedor()
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
	var consulta = "accion=eliminarContactoProveedor";	
	
	consulta +="&idContacto=" + idContacto;
	
	return consulta;
}


function mostrarEliminarContactoProveedor()
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
				//alert(peticionUnica1.responseText);				
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















