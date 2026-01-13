var peticionUnica1 = null;
var idInputListado = null;
var condicion = null;


function cargarImpresoras(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarImpresoras;
		peticionUnica1.open("POST","ajax/cargarImpresoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarImpresoras();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarImpresoras()
{	
	var consulta = "accion=cargarImpresoras";	
	return consulta;	
}

function mostrarCargarImpresoras()
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
				
				
				
				
				
				
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["impresoras"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


function cargarOrigen(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarOrigen;
		peticionUnica1.open("POST","ajax/cargarOrigenPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarOrigen();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarOrigen()
{	
	var consulta = "accion=cargarOrigen";	
	return consulta;	
}

function mostrarCargarOrigen()
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
				
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["origen"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}



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
		peticionUnica1.open("POST","ajax/insertarRegistroHoraManualInformatica.php",false); //tambien se utiliza para almacen
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

	consulta += "&impresoras=" + document.getElementById("RN_Impresora").value;
	consulta += "&tamanio=" + document.getElementById("RN_Tamanio").value;
	consulta += "&tipo=" + document.getElementById("RN_Tipo").value;
	consulta += "&acabado=" + document.getElementById("RN_Acabado").value;
	consulta += "&gramaje=" + document.getElementById("RN_Gramaje").value;
	consulta += "&origen=" + document.getElementById("RN_Origen").value;
	consulta += "&numCaras=" + document.getElementById("RN_NumCaras").value;


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
		peticionUnica1.open("POST","ajax/cargarRegistrosHoraInformatica.php",false);
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
				contenido +='<th>Impresora</th>';
				contenido +='<th>Tamaño</th>';
				contenido +='<th>Tipo</th>';
				contenido +='<th>Acabado</th>';
				contenido +='<th>Gramaje</th>';
				contenido +='<th>Origen</th>';
				contenido +='<th>Nº Caras</th>';
				
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

					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_impresora" value="">'+datos[contador]["impresoras"]+'</label></td>';
					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_tamano" value="">'+datos[contador]["tamano"]+'</label></td>';
					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_tipo" value="">'+datos[contador]["tipo"]+'</label></td>';
					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_acabado" value="">'+datos[contador]["acabado"]+'</label></td>';
					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_gramaje" value="">'+datos[contador]["gramaje"]+'</label></td>';
					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_origen" value="">'+datos[contador]["origen"]+'</label></td>';
					contenido +='<td style="border: 1px solid white" align="center"><label id="'+datos[contador]["id"]+'_numCaras" value="">'+datos[contador]["impresionNumeroCaras"]+'</label></td>';
					
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="comprobarModificaregistroHoraManual('+datos[contador]["id"]+')" ></td>';
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
					

					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_impresoraValor">'+datos[contador]["idImpresoras"]+'</label></td>';
					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_tamanoValor">'+datos[contador]["idPapelTamano"]+'</label></td>';
					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_tipoValor">'+datos[contador]["idPapelTipo"]+'</label></td>';
					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_acabadoValor">'+datos[contador]["idPapelAcabado"]+'</label></td>';
					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_gramajeValor">'+datos[contador]["idPapelGramaje"]+'</label></td>';
					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_origenValor">'+datos[contador]["idPapelOrigen"]+'</label></td>';
					contenido +='<td style="visibility: hidden; display: none"><label id="'+datos[contador]["id"]+'_numCarasValor">'+datos[contador]["impresionNumeroCaras"]+'</label></td>';
					
					
					
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

	if (booleano || document.getElementById(id+"_codigoBarras").value == "")
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
			
			//se rellena el modal

			document.getElementById("tipoProcesoModal2").value = document.getElementById(id+"_idTipoConceptoManual").value
			cargarSubprocesos2();
			document.getElementById("procesoModal2").value = document.getElementById(id+"_idConceptoManual").value;
			document.getElementById("clienteModal2").value = document.getElementById(id+"_idClienteManual").value;

			document.getElementById("registroProcesoModal2").innerHTML = document.getElementById(id+"_idProcesoManual").value;



			document.getElementById("impresoraModal2").value = document.getElementById(id+"_impresoraValor").innerHTML;
			document.getElementById("tamanoModal2").value = document.getElementById(id+"_tamanoValor").innerHTML;
			document.getElementById("tipoModal2").value = document.getElementById(id+"_tipoValor").innerHTML;
			document.getElementById("acabadoModal2").value = document.getElementById(id+"_acabadoValor").innerHTML;
			document.getElementById("gramajeModal2").value = document.getElementById(id+"_gramajeValor").innerHTML;
			document.getElementById("origenModal2").value = document.getElementById(id+"_origenValor").innerHTML;
			document.getElementById("numCarasModal2").value = document.getElementById(id+"_numCarasValor").innerHTML;


			$("#modalRegistroManualSinProceso_nuevo").modal('show');
			//modificarRegistroTrabajo(id); 
			//cargarRegistrosHoras();
		}
	}	
	else
	{
		alert("El proceso no existe");
	}
	booleano=false;
		
	
}


function modificarRegistroTrabajo() 
{
	var id = document.getElementById("registroProcesoModal2").innerHTML;
	
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

	consulta += "&impresora=" + document.getElementById("impresoraModal2").value;
	consulta += "&tamanio=" + document.getElementById("tamanoModal2").value;
	consulta += "&tipo=" + document.getElementById("tipoModal2").value;
	consulta += "&acabado=" + document.getElementById("acabadoModal2").value;
	consulta += "&gramaje=" + document.getElementById("gramajeModal2").value;
	consulta += "&origen=" + document.getElementById("origenModal2").value;
	consulta += "&numCaras=" + document.getElementById("numCarasModal2").value;

	consulta += "&tipoProceso=" + document.getElementById("tipoProcesoModal2").value;
	consulta += "&proceso=" + document.getElementById("procesoModal2").value;
	consulta += "&cliente=" + document.getElementById("clienteModal2").value;

	

	
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
				$("#modalRegistroManualSinProceso_nuevo").modal('hide');			
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
	consulta += "&idDepartamento=1";
	
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
	consulta += "&idTipoProceso=" + document.getElementById("tipoProcesoModal2").value;
	consulta += "&idDepartamento=1";
	
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
					document.getElementById("procesoModal2").innerHTML = "";
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
							
						document.getElementById("procesoModal2").innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById("procesoModal2").innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}						
}





