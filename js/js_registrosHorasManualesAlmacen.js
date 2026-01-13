var peticionUnica1 = null;
var idInputListado = null;
var condicion = null;


function comprobarInsertarRegistroHoraManual() //js_pdaGestion
{	
	comprobarCodigoProceso(document.getElementById("RN_proceso").value);


	var seguir1=false;

	if (booleano)
	{
		seguir1 = true;
	}
	else
	{
		document.getElementById("RN_proceso").value = "";

		if (document.getElementById("procesoNombre").value != "")
		{
			seguir1 = true;
		}
		else
		{
			alert("Introducir un proceso");
		}
	}

	if (seguir1==true)
	{
		if(document.getElementById("RN_fechaInicio").value=="")
		{
			alert("Introducir una fecha de inicio");
			document.getElementById("RN_fechaInicio").focus();
		}
		else if(document.getElementById("RN_fechaFin").value=="")
		{
			alert("Introducir una fecha Fin");
			document.getElementById("RN_fechaFin").focus();
		}
		else if (document.getElementById("RN_fechaInicio").value >document.getElementById("RN_fechaFin").value)		
		{
			alert("La fecha/hora fin no puede ser inferior a la fecha de inicio");
		}		
		else
		{
			if (document.getElementById("RN_cantidad").value=="")
			{
				document.getElementById("RN_cantidad").value=0;
			}
			
			insertarRegistroHoraManual();
			
		}
	}
	
	

		

/*
	if (booleano)
	{
		if(document.getElementById("RN_fechaInicio").value=="")
		{
			alert("Introducir una fecha de inicio");
			document.getElementById("RN_fechaInicio").focus();
		}
		else if(document.getElementById("RN_fechaFin").value=="")
		{
			alert("Introducir una fecha Fin");
			document.getElementById("RN_fechaFin").focus();
		}		
		else
		{
			if (document.getElementById("RN_cantidad").value=="")
			{
				document.getElementById("RN_cantidad").value=0;
			}
			
			insertarRegistroHoraManual();
			//cargarRegistrosHoras();
		}
	}
	else if (document.getElementById("procesoNombre").value!=null)
	{

	}
	else
	{
		alert("El proceso no existe");
	}
		*/
	booleano=false;
	
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
		peticionUnica1.open("POST","ajax/insertarRegistroHoraManualInformatica.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarRegistroHoraManual();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarRegistroHoraManual()
{	
	var consulta = "accion=insertarRegistroHoraManual";	
	//consulta += "&idEmpleado=" + document.getElementById("RN_empleado").value;
	consulta += "&proceso=" + document.getElementById("RN_proceso").value;
	consulta += "&fechaInicio=" + document.getElementById("RN_fechaInicio").value;
	consulta += "&fechaFin=" + document.getElementById("RN_fechaFin").value;
	consulta += "&cantidad=" + document.getElementById("RN_cantidad").value;
	consulta += "&observaciones=" + document.getElementById("RN_observaciones").value;


	/*consulta += "&impresoras=";
	consulta += "&tamanio=";
	consulta += "&tipo=";
	consulta += "&acabado=";
	consulta += "&gramaje=";
	consulta += "&origen=" ;*/


	consulta += "&sinProceso_idProceso=" + document.getElementById("procesoNombre").value;
	consulta += "&sinProceso_idCliente=" + document.getElementById("clientes").value;
	//consulta += "&sinProceso_observaciones=" + document.getElementById("observacionesConcepto").value;
	
	
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
				alert(peticionUnica1.responseText);
				cargarRegistrosHoras() ;
			}
			peticionUnica1=null;
						
		}//if
	}//if						
}//function


function cargarRegistrosHoras() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRegistrosHoras;
		peticionUnica1.open("POST","ajax/cargarRegistrosHoraAlmacen.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosHoras();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosHoras()
{	
	var consulta = "accion=cargarRegistrosHoras";	
	
	consulta +="&ot="+document.getElementById("buscarOtValor").value;	
	consulta += "&fechaInicio="+document.getElementById("buscarFechaInicio").value;
	consulta += "&fechaFin="+document.getElementById("buscarFechaFin").value;
	consulta += "&orden="+document.getElementById("orden").value;
	consulta += "&desc="+document.getElementById("ordenDesc").checked;
	//consulta += "&meses=" + document.getElementById("buscarNumMeses").value;
	
	

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
				contenido +='<th>Proceso</th>';
				contenido +='<th>Hora Inicio</th>';				
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

					contenido += '<tr '+contraste+'>';
					
					contenido +='<td align="center"><label id="'+datos[contador]["id"]+'" title="'+datos[contador]["tipoProceso"] + '\n'+datos[contador]["proceso"] + '\n'+datos[contador]["descripcion"]+'">'+datos[contador]["id"]+'</label></td>';

					contenido +='<td style="visibility: hidden; display: none"><input type="text" id="'+datos[contador]["id"]+'_idConceptoManual" value="'+datos[contador]["sinProceso_idConcepto"]+'"></input></td>';
					contenido +='<td style="visibility: hidden; display: none"><input type="text" id="'+datos[contador]["id"]+'_idTipoConceptoManual" value="'+datos[contador]["sinProceso_idTipoProceso"]+'"></input></td>';
					contenido +='<td style="visibility: hidden; display: none"><input type="text" id="'+datos[contador]["id"]+'_idClienteManual" value="'+datos[contador]["sinProceso_idCliente"]+'"></input></td>';
					contenido +='<td style="visibility: hidden; display: none"><input type="text" id="'+datos[contador]["id"]+'_idProcesoManual" value="'+datos[contador]["id"]+'"></input></td>';

					
					
					
					var datosProceso = datos[contador]["codigoBarras"];
					if (datosProceso == '0-9999999')
					{
						datosProceso="";
					}
					
					contenido +='<td align="center"><input type="text" id="'+datos[contador]["id"]+'_codigoBarras" value="'+datosProceso+'" class="tamanio14"></td>';
							
					



					contenido +='<td align="center"><input type="datetime-local" class=""  id="'+datos[contador]["id"]+'_fechaInicio" value="'+datos[contador]["horaInicio"]["date"].replace(' ','T').substring(0,16)+'"></td>';
					contenido +='<td align="center"><input type="datetime-local" class=""  id="'+datos[contador]["id"]+'_fechaFin" value="'+datos[contador]["horaFin"]["date"].replace(' ','T').substring(0,16)+'"></td>';


									
					
					
					contenido +='<td align="center"><input type="text" class="tamanio7" id="'+datos[contador]["id"]+'_cantidad" value="'+datos[contador]["cantidad"]+'"></td>';
					contenido +='<td align="center"><textarea rows="1" style="margin-top:3px !important;" class="" type="text" id="'+datos[contador]["id"]+'_observaciones">'+datos[contador]["observaciones"]+'</textarea></td>';

										
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="comprobarModificaregistroHoraManual('+datos[contador]["id"]+')" ></td>';
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
					contenido +='</tr>';					
					
					contador++;
					
				}
				
				document.getElementById("registrosHoras").innerHTML = contenido;

						
			}
			peticionUnica1=null;			
		}
	}						
}



function comprobarModificaregistroHoraManual(id) //js_pdaGestion
{		
	comprobarCodigoProceso(document.getElementById(id+"_codigoBarras").value);

	if (booleano)
	{
		if(document.getElementById(id+"_fechaInicio").value=="")
		{
			alert("Introducir una fecha de inicio");
			document.getElementById(id+"_fechaInicio").focus();
		}
		else if(document.getElementById(id+"_fechaFin").value=="")
		{
			alert("Introducir una fecha Fin");
			document.getElementById(id+"_fechaFin").focus();
		}
		
		else
		{
			if (document.getElementById(id+"_cantidad").value=="")
			{
				document.getElementById(id+"_cantidad").value=0;
			}
			
			modificarRegistroTrabajo(id); 
			//cargarRegistrosHoras();
		}
	}
	else if (document.getElementById(id+"_idConceptoManual").value!="null")
	{
		//abrir ventana modal con tipo de proceso y proceso y cliente
		//alert("aaaaaae"); aqui

		document.getElementById("tipoProcesoModal").value = document.getElementById(id+"_idTipoConceptoManual").value
		cargarSubprocesos2();
		document.getElementById("procesoModal").value = document.getElementById(id+"_idConceptoManual").value;
		document.getElementById("clienteModal").value = document.getElementById(id+"_idClienteManual").value;

		document.getElementById("registroProcesoModal").innerHTML = document.getElementById(id+"_idProcesoManual").value;

		

		$("#modalRegistroManualSinProceso").modal('show');
	} 
	else
	{
		alert("El proceso no existe");
	}
	booleano=false;
		
	
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
	consulta += "&horaInicio=informatica";	
	consulta += "&fechaFin=" + document.getElementById(id+"_fechaFin").value;
	consulta += "&horaFin=informatica";

	
	
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


function cargarSubprocesos()//js_presupeustosAlta
{
	//alert("entra");
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarSubProcesos;
		peticionUnica1.open("POST","ajax/mostrarSubProcesos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubProcesos();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarSubProcesos()
{	
	var consulta = "accion=cargarSubProcesos";	
	consulta += "&idTipoProceso=" + document.getElementById("tipoProceso").value;
	consulta += "&idDepartamento=6";
	
	return consulta;	
}

function mostrarCargarSubProcesos()
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
					document.getElementById("procesoNombre").innerHTML = "";
				}
				
				if (datos != "")
				{
					
					if (datos.length<=0)
					{
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{							
						var contenido = "";
						var contador = 0;

						while  (contador<datos.length)
						{
								
							if (datos[contador]["descripcion"]==null || datos[contador]["descripcion"]=="" || datos[contador]["descripcion"]== "null")
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+'</option>';
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+ " --- " + datos[contador]["descripcion"]+'</option>';	
							}

							contador++;
						}
							
						document.getElementById("procesoNombre").innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById("procesoNombre").innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}						
}

function cargarSubprocesos2()//js_presupeustosAlta
{
	//alert("entra");
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarSubProcesos2;
		peticionUnica1.open("POST","ajax/mostrarSubProcesos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubProcesos2();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarSubProcesos2()
{	
	var consulta = "accion=cargarSubProcesos";	
	consulta += "&idTipoProceso=" + document.getElementById("tipoProcesoModal").value;
	consulta += "&idDepartamento=6";
	
	return consulta;	
}

function mostrarCargarSubProcesos2()
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
					document.getElementById("procesoModal").innerHTML = "";
				}
				
				if (datos != "")
				{
					
					if (datos.length<=0)
					{
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{							
						var contenido = "";
						var contador = 0;

						while  (contador<datos.length)
						{
								
							if (datos[contador]["descripcion"]==null || datos[contador]["descripcion"]=="" || datos[contador]["descripcion"]== "null")
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+'</option>';
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+ " --- " + datos[contador]["descripcion"]+'</option>';	
							}

							contador++;
						}
							
						document.getElementById("procesoModal").innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById("procesoModal").innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}						
}




function cambiarProcesoSinIndicar() //js_pdaGestion
{
	var id = document.getElementById("registroProcesoModal").innerHTML;
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCambiarProcesoSinIndicar;
		peticionUnica1.open("POST","ajax/modificarRegistroHoras2.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarProcesoSinIndicar(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaCambiarProcesoSinIndicar(id)
{	
	var consulta = "accion=modificarRegistro";	
	consulta += "&idRegistro=" + id;
	consulta += "&idProceso=" + document.getElementById("procesoModal").value;
	consulta += "&idCliente=" + document.getElementById("clienteModal").value;
		
	return consulta;	
}

function mostrarCambiarProcesoSinIndicar()
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
				$("#modalRegistroManualSinProceso").modal('hide');					
				//cargarRegistrosHoras();
				
			}
			peticionUnica1=null;
		}
	}						
}




