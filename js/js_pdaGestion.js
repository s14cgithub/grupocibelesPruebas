var peticionUnica1 = null;
var permisosSoloLectura = null;

function cargarAutomaticosPda()//js_pdaGestion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarAutomaticosPda;
		peticionUnica1.open("POST","ajax/cargarAutomaticosPda.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAutomaticosPda();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarAutomaticosPda()
{	
	var consulta = "accion=cargarAutomaticosPda";	
	return consulta;	
}

function mostrarCargarAutomaticosPda()
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
				
				
				if (datos=="")
				{
					cargarRegistrosHoras();
				}
				else
				{
					var contenido = "";
				
				
					var contador = 0;
					var contraste="";
					
					contenido += '<tr><td colspan="10" style="border:none;text-align: center;"><h2>CIERRES AUTOMATICOS</h2></td></tr>';			
					contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
					contenido += '<th align="center">Id</th>';
					contenido += '<th>Empleado</th>';
					contenido += '<th>Codigo de Barras</th>';
					contenido += '<th>Fecha Inicio</th>';
					contenido += '<th>Hora Inicio</th>';
					contenido += '<th>Modo</th>';
					contenido += '<th>Fecha Fin</th>';
					contenido += '<th>Hora Fin</th>';
					contenido += '<th></th>';
					contenido += '<th></th>';
					contenido += '</tr>';
					
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
						contenido += '<td align="center"><label id="'+datos[contador]["id"]+'">'+datos[contador]["id"]+'</label></td>';

						contenido += '<td align="center"><label id="'+datos[contador]["id"]+'_empleado">'+datos[contador]["nombreEmpleado"]+'</label></td>';

						var datosProceso = datos[contador]["codigoBarras"];
						if (datosProceso == '0-9999999')
						{
							datosProceso="";
						}

						contenido += '<td align="center"><input type="text" id="'+datos[contador]["id"]+'_codigoBarras" value="'+datosProceso+'"></td>';

						contenido += '<td align="center"><input type="date"  id="'+datos[contador]["id"]+'_fechaInicio" value="'+datos[contador]["horaInicio"]["date"].substring(0,10)+'"></td>'; 

						contenido += '<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horaInicio" value="'+datos[contador]["horaInicio"]["date"].substring(11,19)+'"></td>';

						contenido += '<td align="center"><label id="'+datos[contador]["id"]+'_modo">'+datos[contador]["modo"]+'</label></td>';

						contenido += '<td align="center"><input type="date" id="'+datos[contador]["id"]+'_fechaFin" value="'+datos[contador]["horaFin"]["date"].substring(0,10)+'"></td>';

						contenido += '<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horaFin" value="'+datos[contador]["horaFin"]["date"].substring(11,19)+'"></td>';

						if (!permisosSoloLectura)
						{
							contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
							contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
						}
						else 
						{
							contenido += '<td></td>';
							contenido += '<td></td>';
						}
						
						contenido += '</tr>';

						contador++;
					}

					document.getElementById("registrosHoras").innerHTML = contenido;
				}//while
				
				
						
			}//else
			peticionUnica1=null;			
		}//if
	}//if						
}//function

function cargarRegistrosHoras() //js_pdaGestion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRegistrosHoras;
		peticionUnica1.open("POST","ajax/cargarRegistrosHora.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosHoras();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosHoras()
{	
	var consulta = "accion=cargarRegistrosHoras";	
	
	consulta +="&ot="+document.getElementById("buscarOtValor").value;
	consulta +="&empleado="+document.getElementById("buscarEmpleado").value;
	consulta += "&fechaInicio="+document.getElementById("buscarFechaInicio").value;
	consulta += "&fechaFin="+document.getElementById("buscarFechaFin").value;
	consulta += "&orden="+document.getElementById("orden").value;
	consulta += "&desc="+document.getElementById("ordenDesc").checked;
	consulta += "&meses=" + document.getElementById("buscarNumMeses").value;
	consulta += "&masde10horas=" + document.getElementById("masde10horas").checked;
	
	return consulta;	
}

function mostrarCargarRegistrosHoras()
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
				
				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
				contenido +='<th align="center">Id</th>';
				contenido +='<th>Empleado</th>';
				contenido +='<th>Codigo de Barras</th>';
				contenido +='<th>Estado</th>';
				contenido +='<th>Fecha Inicio</th>';
				contenido +='<th>Hora Inicio</th>';
				contenido +='<th>Fecha Fin</th>';
				contenido +='<th>Hora Fin</th>';
				contenido +='<th>Cantidad</th>';
				contenido +='<th>Observaciones</th>';
				contenido +='<th></th>';
				contenido +='<th></th>';
				contenido +='</tr>';
				
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

					var datosProceso = datos[contador]["codigoBarras"];
					if (datosProceso == '0-9999999')
					{
						datosProceso="";
						contenido += '<tr style="background-color:DarkOrange;">';
					}
					else
					{
						contenido += '<tr '+contraste+'>';
					}


					
					
					contenido +='<td align="center"><label id="'+datos[contador]["id"]+'">'+datos[contador]["id"]+'</label></td>';
					contenido +='<td align="center"><label class="textoNegrita" id="'+datos[contador]["id"]+'_empleado">'+datos[contador]["nombreEmpleado"]+'</label></td>';
					
					
					
					
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_codigoBarras" value="'+datosProceso+'" class="tamanio14"></td>';
					
					if (datos[contador]["estado"] == "abierto")
					{
						contenido +='<td align="center" class="procesoAbierto textoNegrita"><label id="'+datos[contador]["id"]+'_estado">'+datos[contador]["estado"]+'</label></td>';
					}
					else
					{
						contenido +='<td align="center" class="procesoCerrado textoNegrita"><label id="'+datos[contador]["id"]+'_estado">'+datos[contador]["estado"]+'</label></td>';
					}
					
					contenido +='<td align="center"><input type="date" class="tamanioFecha"  id="'+datos[contador]["id"]+'_fechaInicio" value="'+datos[contador]["horaInicio"]["date"].substring(0,10)+'"></td>';
					
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horaInicio" value="'+datos[contador]["horaInicio"]["date"].substring(11,19)+'"></td>';
					
					contenido +='<td align="center"><input type="date"  class="tamanioFecha" id="'+datos[contador]["id"]+'_fechaFin" value="';
					
					if (datos[contador]["horaFin"] != "" && datos[contador]["horaFin"] != null)
					{
						contenido += datos[contador]["horaFin"]["date"].substring(0,10);
					}					
										
					contenido +='"></td>';					
					
					contenido +='<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horaFin" value="';
					
					
					if (datos[contador]["horaFin"] != "" && datos[contador]["horaFin"] != null)
					{
						contenido += datos[contador]["horaFin"]["date"].substring(11,19);
					}					
					
					contenido +='"></td>';
					contenido +='<td align="center"><input type="text" class="tamanio7" id="'+datos[contador]["id"]+'_cantidad" value="'+datos[contador]["cantidad"]+'"></td>';
					contenido +='<td align="center"><input class="observaciones1" type="text" id="'+datos[contador]["id"]+'_observaciones" value="'+datos[contador]["observaciones"]+'"></td>';
					


					

					if (datosProceso == '' || permisosSoloLectura ) 
					{
						contenido +='<td></td>';
						contenido +='<td></td>';
						
					}
					else
					{
						contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
						contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
						
					}

					contenido +='</tr>';					
					
					contador++;
					
				}
				
				document.getElementById("registrosHoras").innerHTML = contenido;
						
			}
			peticionUnica1=null;			
		}
	}						
}


function comprobarInsertarRegistroHoraManual() //js_pdaGestion
{
	//verUltimoRegistroTrabajo(document.getElementById("RN_empleado").value);
	
	/*if (unArray.length>0 && unArray[0]["estado"]!="cerrado")
	{
		alert("El empleado seleccionado tiene un proceso abierto");
	}
	else*/
	{
		comprobarCodigoProceso(document.getElementById("RN_proceso").value);
	
		if (booleano)
		{
			if(document.getElementById("RN_fechaInicio").value=="")
			{
				alert("Introducir una fecha de inicio");
				document.getElementById("RN_fechaInicio").focus();
			}		
			else if (document.getElementById("RN_fechaFin").value=="")
			{				
				alert("Introducir una fecha FIN");
				document.getElementById("RN_fechaFin").focus();			
			}
			else
			{
				if (document.getElementById("RN_cantidad").value=="")
				{
					document.getElementById("RN_cantidad").value=0;
				}
				//insertarRegistroHoraCompleto();
				insertarRegistroHoraManual();
				cargarRegistrosHoras();
			}


				
			
		}
		else
		{
			alert("El proceso no existe");
		}
		booleano=false;
	}
	unArray = [];
}



function verUltimoRegistroTrabajo(idEmpleado) //js_pdaGestion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerUltimoRegistroTrabajo;
		peticionUnica1.open("POST","ajax/verUltimoRegistroTrabajo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerUltimoRegistroTrabajo(idEmpleado);
		peticionUnica1.send(query_string);
	}
}

function consultaVerUltimoRegistroTrabajo(idEmpleado)
{	
	var consulta = "accion=comprobarCodigoProceso";	
	consulta += "&idEmpleado="+idEmpleado;
	return consulta;	
}

function mostrarVerUltimoRegistroTrabajo()
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
				
				unArray = datos;				
				
			}//else
			peticionUnica1=null;			
		}//if
	}//if						
}//function


function comprobarCodigoProceso(codigo) //js_pdaGestion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarCodigoProceso;
		peticionUnica1.open("POST","ajax/comprobarCodigoProceso.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarCodigoProceso(codigo);
		peticionUnica1.send(query_string);
	}
}

function consultaComprobarCodigoProceso(codigo)
{	
	var consulta = "accion=comprobarCodigoProceso";	
	consulta += "&codigo="+codigo;
	return consulta;	
}

function mostrarComprobarCodigoProceso()
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
				booleano = peticionUnica1.responseText;
			}//else
			peticionUnica1=null;			
		}//if
	}//if						
}//function


function insertarRegistroHoraManual() //js_pdaGestion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarRegistroHoraManual;
		peticionUnica1.open("POST","ajax/insertarRegistroHoraManual.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarRegistroHoraManual();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarRegistroHoraManual()
{	
	var consulta = "accion=insertarRegistroHoraManual";	
	consulta += "&idEmpleado=" + document.getElementById("RN_empleado").value;
	consulta += "&proceso=" + document.getElementById("RN_proceso").value;
	consulta += "&fechaInicio=" + document.getElementById("RN_fechaInicio").value;
	consulta += "&fechaFin=" + document.getElementById("RN_fechaFin").value;
	consulta += "&cantidad=" + document.getElementById("RN_cantidad").value;
	consulta += "&observaciones=" + document.getElementById("RN_observaciones").value;
	
	
	return consulta;	
}

function mostrarInsertarRegistroHoraManual()
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
				alert("Registro Insertado");
			}//else
			peticionUnica1=null;			
		}//if
	}//if						
}//function


function cargarEmpleadosPDA(idInput) //js_pdaGestion
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarEmpleadosPDA;
		peticionUnica1.open("POST","ajax/cargarEmpleadosPDA.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarEmpleadosPDA();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarEmpleadosPDA()
{	
	var consulta = "accion=cargarEmpleadosPDA";	
	return consulta;	
}

function mostrarCargarEmpleadosPDA()
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
				
				if (idInputListado=="buscarEmpleado")
				{
					contenido += '<option value="">Todos</option>';
				}
				
				
				
				
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["idEmpleado"]+'">'+datos[contador]["nombre"]+' '+datos[contador]["apellidos"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}

function modificarRegistroTrabajo(id) //js_pdaGestion
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarRegistroTrabajo;
		peticionUnica1.open("POST","ajax/modificarRegistroHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarRegistroTrabajo(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarRegistroTrabajo(id)
{	
	var consulta = "accion=modificarRegistro";	
	consulta += "&id=" + id;
	consulta += "&codigoBarras=" + document.getElementById(id+"_codigoBarras").value;
	consulta += "&fechaInicio=" + document.getElementById(id+"_fechaInicio").value;
	consulta += "&horaInicio=" + document.getElementById(id+"_horaInicio").value;	
	consulta += "&fechaFin=" + document.getElementById(id+"_fechaFin").value;
	consulta += "&horaFin=" + document.getElementById(id+"_horaFin").value;
	
	try 
	{
		consulta += "&cantidad=" + document.getElementById(id+"_cantidad").value;
		consulta += "&observaciones=" + document.getElementById(id+"_observaciones").value;
		consulta +="&modificarModo=false";
	}
	catch (error) 
	{
		
	}
	
	return consulta;	
}

function mostrarModificarRegistroTrabajo()
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
				alert("Registro Modificado");			
				cargarRegistrosHoras();
				
			}
			peticionUnica1=null;
		}
	}						
}


function eliminarRegistroTrabajo(id) //js_pdaGestion
{
	if (confirm("¿Eliminar el registro: "+id+"?")) 
	{
	  eliminarRegistroTrabajo2(id);
	} 
	else 
	{
	  //no hace nada
	}
}


function eliminarRegistroTrabajo2(id)//js_pdaGestion
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroTrabajo3;
		peticionUnica1.open("POST","ajax/eliminarRegistroHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarRegistroTrabajo3(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaEliminarRegistroTrabajo3(id)
{	
	var consulta = "accion=eliminarRegistro";	
	consulta += "&id=" + id;
	
	return consulta;	
}

function mostrarEliminarRegistroTrabajo3()
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
				cargarRegistrosHoras();				
			}
			peticionUnica1=null;
		}
	}						
}


