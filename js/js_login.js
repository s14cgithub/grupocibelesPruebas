var peticionUnica1 = null;



function cargarLogin() //js_login
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarLogin;
		peticionUnica1.open("POST","ajax/cargarLogin.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarLogin();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarLogin()
{	
	var consulta = "accion=cargarLogin";	
	
	consulta += "&idEmpleado="+document.getElementById("listadoEmpleado1").value;
	consulta += "&usuario="+document.getElementById("buscarUsuario").value;
	
	//consulta += "&orden="+document.getElementById("orden").value;
	//consulta += "&desc="+document.getElementById("ordenDesc").checked;
	
	return consulta;	
}

function mostrarCargarLogin()
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
				contenido +='<th>Empleado</th>';
				contenido +='<th>Usuario</th>';
				contenido +='<th>Contraseña</th>';						
				contenido +='<th></th>';
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
					
					contenido +='<td align="center"><select id="'+datos[contador]["id"]+'_empleado" value=""></td>';
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_usuario" value="'+datos[contador]["usuario"]+'"></td>';
					
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_contrasenia" value=""></td>';
					
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarRegistroUsuario('+datos[contador]["id"]+')" ></td>';	
					
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/ojo.png" style="width:15px;" onclick="mostrarPermisos('+datos[contador]["id"]+')" ></td>';	
					
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroUsuario('+datos[contador]["id"]+')" ></td>';
					contenido +='</tr>';
					
					contador++;					
				}				
				
				document.getElementById("usuarios").innerHTML = contenido;				
				
				contador = 0;
				
				while  (contador<datos.length) 
				{
					idInputListado = datos[contador]["id"]+'_empleado';
					cargarListadoEmpleado();
					document.getElementById(datos[contador]["id"]+'_empleado').value = datos[contador]["idEmpleado"];
					contador++;
				}						
			}
			peticionUnica1=null;			
		}
	}						
}

function insertarRegistroUsuario() //js_login
{
	
	if (document.getElementById("usuarioNuevo").value=="")
	{
		alert("Introducir un usuario");
		document.getElementById("usuarioNuevo").focus();
	}
	else if (document.getElementById("contrasenaNuevo").value=="")
	{
		alert("Introducir una contraseña");
		document.getElementById("contrasenaNuevo").focus();
	}	
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarRegistroUsuario;
			peticionUnica1.open("POST","ajax/insertarRegistroUsuario.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarRegistroUsuario();
			peticionUnica1.send(query_string);
		}
	}
	
}

function consultaInsertarRegistroUsuario()
{	
	var consulta = "accion=insertarRegistroUsuario";	
	
	consulta += "&idEmpleado="+document.getElementById("empleadoNuevo").value ;
	consulta += "&usuarioNuevo="+document.getElementById("usuarioNuevo").value;	
	consulta += "&contrasenaNuevo="+document.getElementById("contrasenaNuevo").value;
	
	return consulta;	
}

function mostrarInsertarRegistroUsuario()
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
				var resultado = peticionUnica1.responseText.split("|||");				
				crearPermisosNuevo(resultado[0]);
				if (campo1="true")
				{
					alert(resultado[1]);
				}
				
				campo1="";				
				cargarLogin();
				document.getElementById("usuarioNuevo").value="";	
				document.getElementById("contrasenaNuevo").value="";	
			}
			peticionUnica1=null;			
		}
	}						
}

function crearPermisosNuevo(idLogin) //js_login
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearPermisosNuevo;
		peticionUnica1.open("POST","ajax/crearNuevoPermiso.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearPermisosNuevo(idLogin);
		peticionUnica1.send(query_string);
	}
}

function consultaCrearPermisosNuevo(idLogin)
{	
	var consulta = "accion=crearPermisosNuevo";
	
	consulta += "&idLogin=" + idLogin;
	
	return consulta;	
}

function mostrarCrearPermisosNuevo()
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
				campo1 = "true";		
			}
			
			peticionUnica1=null;			
		}
	}						
}


function modificarPermisos() //js_login
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarPermisos;
		peticionUnica1.open("POST","ajax/modificarPermisos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarPermisos();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarPermisos()
{	
	var consulta = "accion=modificarPermisos";
	
	consulta += "&idLogin=" + document.getElementById("idLoginModal").innerHTML;
	
	
	consulta += "&pms_pda=" +document.getElementById("pms_pda").checked;
	consulta += "&pms_pda_gestion=" +document.getElementById("pms_pda_gestion").checked;
	consulta += "&pms_pdaAdjunto=" +document.getElementById("pms_pdaAdjunto").checked;
	consulta += "&pms_informesProduccion=" +document.getElementById("pms_informesProduccion").checked;
	consulta += "&pms_presupuestos=" +document.getElementById("pms_presupuestos").checked;
	consulta += "&pms_nuevoProcesoPresu=" +document.getElementById("pms_nuevoProcesoPresu").checked;
	consulta += "&pms_cambiarFechaCompromisoPresu=" +document.getElementById("pms_cambiarFechaCompromisoPresu").checked;
	consulta += "&pms_cambiarFechaAceptacionPresu=" +document.getElementById("pms_cambiarFechaAceptacionPresu").checked;
	consulta += "&pms_otBajada=" +document.getElementById("pms_otBajada").checked;
	consulta += "&pms_otAbierta=" +document.getElementById("pms_otAbierta").checked;
	consulta += "&pms_otTerminada=" +document.getElementById("pms_otTerminada").checked;
	consulta += "&pms_otBajadaAutomatico=" +document.getElementById("pms_otBajadaAutomatico").checked;
	consulta += "&pms_prodOt=" +document.getElementById("pms_prodOt").checked;
	
	consulta += "&pms_administracion=" +document.getElementById("pms_administracion").checked;	
	consulta += "&pms_admFacturacion=" +document.getElementById("pms_admFacturacion").checked;	
	consulta += "&pms_admContabilidad=" +document.getElementById("pms_admContabilidad").checked;	
	
	consulta += "&pms_prodGrabarFranqueo=" +document.getElementById("pms_prodGrabarFranqueo").checked;
	consulta += "&pms_prodFranqueoF12=" +document.getElementById("pms_prodFranqueoF12").checked;
	consulta += "&pms_actualizarDatos=" +document.getElementById("pms_actualizarDatos").checked;
	consulta += "&pms_presupuestoMensual=" +document.getElementById("pms_presupuestoMensual").checked;
	consulta += "&pms_rutas=" +document.getElementById("pms_rutas").checked;
	consulta += "&pms_pdaConductor=" +document.getElementById("pms_pdaConductor").checked;
					
	
	return consulta;	
}

function mostrarModificarPermisos()
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
				verPermisosPorIdLogin(document.getElementById("idLoginModal").innerHTML);		
			}
			
			peticionUnica1=null;			
		}
	}						
}

function verPermisosPorIdLogin(idLogin) //js_login
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerPermisosPorIdLogin;
		peticionUnica1.open("POST","ajax/verPermisosPorIdLogin.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerPermisosPorIdLogin(idLogin);
		peticionUnica1.send(query_string);
	}
}

function consultaVerPermisosPorIdLogin(idLogin)
{	
	var consulta = "accion=verPermisosPorIdLogin";
	
	consulta += "&idLogin="+idLogin;	
	
	return consulta;	
}

function mostrarVerPermisosPorIdLogin()
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
					
					if (datos[0]["administracion"]==2)
					{
						document.getElementById("pms_administracion").checked = true;
					}
					else
					{
						document.getElementById("pms_administracion").checked = false;
					}
					
					if (datos[0]["admContabilidad"]==2)
					{
						document.getElementById("pms_admContabilidad").checked = true;
					}
					else
					{
						document.getElementById("pms_admFacturacion").checked = false;
					}
					
					if (datos[0]["admFacturacion"]==2)
					{
						document.getElementById("pms_admFacturacion").checked = true;
					}
					else
					{
						document.getElementById("pms_admContabilidad").checked = false;
					}
					
					
					document.getElementById("pms_pda").checked = datos[0]["pda"];
					
					
					if (datos[0]["pdaGestion"]==2)
					{
						document.getElementById("pms_pda_gestion").checked = true;
					}
					else
					{
						document.getElementById("pms_pda_gestion").checked = false;
					}
					
					if (datos[0]["pdaAdjunto"]==2)
					{
						document.getElementById("pms_pdaAdjunto").checked = true;
					}
					else
					{
						document.getElementById("pms_pdaAdjunto").checked = false;
					}
					
					if (datos[0]["informesProduccion"]==2)
					{
						document.getElementById("pms_informesProduccion").checked = true;
					}
					else
					{
						document.getElementById("pms_informesProduccion").checked = false;
					}
					
					if (datos[0]["presupuestos"]==2)
					{
						document.getElementById("pms_presupuestos").checked = true;
					}
					else
					{
						document.getElementById("pms_presupuestos").checked = false;
					}
					
					if (datos[0]["nuevoProcesoPresu"]==2)
					{
						document.getElementById("pms_nuevoProcesoPresu").checked = true;
					}
					else
					{
						document.getElementById("pms_nuevoProcesoPresu").checked = false;
					}
					
					if (datos[0]["cambiarFechaCompromisoPresu"]==2)
					{
						document.getElementById("pms_cambiarFechaCompromisoPresu").checked = true;
					}
					else
					{
						document.getElementById("pms_cambiarFechaCompromisoPresu").checked = false;
					}
					
					if (datos[0]["cambiarFechaAceptacionPresu"]==2)
					{
						document.getElementById("pms_cambiarFechaAceptacionPresu").checked = true;
					}
					else
					{
						document.getElementById("pms_cambiarFechaAceptacionPresu").checked = false;
					}
					
					if (datos[0]["presuOtBajada"]==2)
					{
						document.getElementById("pms_otBajada").checked = true;
					}
					else
					{
						document.getElementById("pms_otBajada").checked = false;
					}
					
					if (datos[0]["presuOtAbierta"]==2)
					{
						document.getElementById("pms_otAbierta").checked = true;
					}
					else
					{
						document.getElementById("pms_otAbierta").checked = false;
					}
					
					if (datos[0]["presuOtTerminada"]==2)
					{
						document.getElementById("pms_otTerminada").checked = true;
					}
					else
					{
						document.getElementById("pms_otTerminada").checked = false;
					}
					
					if (datos[0]["otBajadaAutomatico"]==2)
					{
						document.getElementById("pms_otBajadaAutomatico").checked = true;
					}
					else
					{
						document.getElementById("pms_otBajadaAutomatico").checked = false;
					}
					
					if (datos[0]["ot"]==2)
					{
						document.getElementById("pms_prodOt").checked = true;
					}
					else
					{
						document.getElementById("pms_prodOt").checked = false;
					}
					
					
					
					if (datos[0]["grabarFranqueo"]==2)
					{
						document.getElementById("pms_prodGrabarFranqueo").checked = true;
					}
					else
					{
						document.getElementById("pms_prodGrabarFranqueo").checked = false;
					}
					
					if (datos[0]["franqueoF12"]==2)
					{
						document.getElementById("pms_prodFranqueoF12").checked = true;
					}
					else
					{
						document.getElementById("pms_prodFranqueoF12").checked = false;
					}
					
					if (datos[0]["actualizarDatos"]==2)
					{
						document.getElementById("pms_actualizarDatos").checked = true;
					}
					else
					{
						document.getElementById("pms_actualizarDatos").checked = false;
					}
					
					if (datos[0]["presupuestoMensual"]==2)
					{
						document.getElementById("pms_presupuestoMensual").checked = true;
					}
					else
					{
						document.getElementById("pms_presupuestoMensual").checked = false;
					}
					
					if (datos[0]["rutas"]==2)
					{
						document.getElementById("pms_rutas").checked = true;
					}
					else
					{
						document.getElementById("pms_rutas").checked = false;
					}					
					
					if (datos[0]["pdaConductor"]==2)
					{
						document.getElementById("pms_pdaConductor").checked = true;
					}
					else
					{
						document.getElementById("pms_pdaConductor").checked = false;
					}
				}
		
			}
			
			peticionUnica1=null;			
		}
	}						
}

function modificarRegistroUsuario(id) //js_login
{	
	if (document.getElementById(id+"_usuario").value=="")
	{
		alert("Introducir un Usuario");
		document.getElementById(id+"_usuario").focus();
	}	
	else
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarRegistroUsuario;
			peticionUnica1.open("POST","ajax/modificarRegistroUsuario.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarRegistroUsuario(id);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarRegistroUsuario(id)
{	
	var consulta = "accion=modificarRegistroUsuario";	
	
	consulta +="&id="+id;
	consulta +="&idEmpleado="+document.getElementById(id+"_empleado").value;
	consulta += "&usuario="+document.getElementById(id+"_usuario").value;
	consulta += "&contrasena="+document.getElementById(id+"_contrasenia").value;	
	
	return consulta;	
}

function mostrarModificarRegistroUsuario()
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
				cargarLogin();
			}
			peticionUnica1=null;			
		}
	}						
}

function eliminarRegistroUsuario(id) //js_login
{	
	
	if (confirm("¿Eliminar el registro: "+id+"?")) 
	{
		valorNumero = id;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarRegistroUsuario;
			peticionUnica1.open("POST","ajax/eliminarRegistroUsuario.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarRegistroUsuario(id);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaEliminarRegistroUsuario(id)
{	
	var consulta = "accion=eliminarRegistroUsuario";	
	
	consulta +="&id="+id;
	
	return consulta;	
}

function mostrarEliminarRegistroUsuario()
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
				var mensaje = peticionUnica1.responseText;
				
				borrarPermisosUsuario(valorNumero);
				
				
				if (campo1="true")
				{
					alert(mensaje);
				}
				
				campo1="";
				
				cargarLogin();
			}
			peticionUnica1=null;			
		}
	}						
}


function borrarPermisosUsuario(id_usuario) //js_login
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarBorrarPermisosUsuario;
		peticionUnica1.open("POST","ajax/borrarPermisosUsuario.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBorrarPermisosUsuario(id_usuario);
		peticionUnica1.send(query_string);
	}
}

function consultaBorrarPermisosUsuario(id_usuario)
{	
	var consulta = "accion=borrarPermisosUsuario";
	
	consulta += "&id_usuario=" + id_usuario;
	
	return consulta;	
}

function mostrarBorrarPermisosUsuario()
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
				campo1 = "true";		
			}
			
			peticionUnica1=null;			
		}
	}						
}

function mostrarPermisos(idLogin) //js_login
{
	document.getElementById("usuarioModal").innerHTML = document.getElementById(idLogin+'_usuario').value;
	document.getElementById("idLoginModal").innerHTML = idLogin;
	verPermisosPorIdLogin(idLogin);	
	$("#permisos").modal('show');
}

