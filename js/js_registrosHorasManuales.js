var peticionUnica1 = null;
var idInputListado = null;
var condicion = null;


function cargarTiposProceso(idSelect)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = function() { mostrarCargarTiposProceso(idSelect); };
		peticionUnica1.open("POST","ajax/cargarTiposProceso.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarTiposProceso();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarTiposProceso()
{
	var consulta = "accion=cargarTiposProceso";

	var campos = ['id','tipoProceso'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	return consulta;
}

function mostrarCargarTiposProceso(idSelect)
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
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
			while (contador < datos.length)
			{
				contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tipoProceso"]+'</option>';
				contador++;
			}

			document.getElementById(idSelect).innerHTML = contenido;
			peticionUnica1=null;
		}
	}
}


function cargarClientes(destino) //cargarClientes de js_global.js esta comentada; version local
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = function() { mostrarCargarClientes(destino); };
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarClientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarClientes()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['codigo_saldo','nombre_empresa'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { activo: 1 };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var filtrosOperadores = [{ campo1: 'codigo_saldo', campo2: 'codigo', operador: '=' }];
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{ campo: 'nombre_empresa', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarClientes(destino)
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
				var contador = 0;

				while (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+' - '+datos[contador]["codigo_saldo"]+'</option>';
					contador++;
				}

				document.getElementById(destino).innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}
}


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

	var campos = ['id','impresoras'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{campo: 'impresoras', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarImpresoras()
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

	var campos = ['id','origen'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{campo: 'origen', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarOrigen()
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
	if (codigo.indexOf("-") < 0)
	{
		booleano = false;
		return;
	}

	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarCodigoProceso;
		peticionUnica1.open("POST","ajax/cargarDetallesPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarCodigoProceso(codigo);
		peticionUnica1.send(query_string);
	}
}

function consultaComprobarCodigoProceso(codigo)
{	
	var consulta = "accion=cargarDetalles";

	var partes = codigo.split("-");

	var campos = ['id'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {id: partes[0], presupuesto: partes[1]};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarComprobarCodigoProceso()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error != "")
			{
				alert(res.error);
				booleano = false;
			}
			else
			{				
				booleano = (res.datos.length > 0);
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

	var fechaSQL = function(dt) { var p = dt.split("T"); var f = p[0].split("-"); return f[2]+"/"+f[1]+"/"+f[0]+" "+p[1]+":00"; };

	var proceso = document.getElementById("RN_proceso").value;

	var datos = {
		codigoBarras: (proceso != "") ? proceso : "0-9999999",
		horaInicio: fechaSQL(document.getElementById("RN_fechaInicio").value),
		horaFin: fechaSQL(document.getElementById("RN_fechaFin").value),
		estado: "cerrado",
		cantidad: document.getElementById("RN_cantidad").value,
		observaciones: document.getElementById("RN_observaciones").value,
		modo: "manual",
		idImpresoras: document.getElementById("RN_Impresora").value,
		idPapelTamano: document.getElementById("RN_Tamanio").value,
		idPapelTipo: document.getElementById("RN_Tipo").value,
		idPapelAcabado: document.getElementById("RN_Acabado").value,
		idPapelGramaje: document.getElementById("RN_Gramaje").value,
		idPapelOrigen: document.getElementById("RN_Origen").value,
		impresionNumeroCaras: document.getElementById("RN_NumCaras").value
	};

	//si no hay proceso (registro manual sin OT) se guardan el proceso y cliente elegidos a mano
	if (proceso == "")
	{
		datos["sinProceso_idConcepto"] = document.getElementById("procesoNombre").value;
		datos["sinProceso_idCliente"] = document.getElementById("clientes").value;
	}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarInsertarRegistroHoraManual()
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
				alert("Registro Insertado");
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
		peticionUnica1.open("POST","ajax/cargarRegistrosHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosHoras();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosHoras()
{	
	var consulta = "accion=cargarRegistrosHoras";

	var campos = ['id','gfTipoProceso','gfProceso','gfDescripcion','sinProceso_idConcepto','sinProceso_idTipoProceso','sinProceso_idCliente','codigoBarras','horaInicio','horaFin','cantidad','observaciones','impresoras','tamanoPapel','tipoPapel','acabadoPapel','gramajePapel','origenPapel','impresionNumeroCaras','idImpresoras','idPapelTamano','idPapelTipo','idPapelAcabado','idPapelGramaje','idPapelOrigen'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = ['tabla_gfDetalle','tabla_gfProcesosTipos','tabla_gfProcesos','tabla_gfProcesoSin','tabla_impresoras','tabla_papelTamano','tabla_papelTipo','tabla_papelAcabado','tabla_papelGramaje','tabla_papelOrigen'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var dmy = function(s) { var p = s.split("-"); return p[2] + "-" + p[1] + "-" + p[0]; };

	var filtros = {idEmpleado: "@sesion", idImpresorasNotNull: true};
	var ot = document.getElementById("buscarOtValor").value;
	if (ot != "")
	{
		filtros["otLike"] = ot;
	}

	var rango = {};
	var fi = document.getElementById("buscarFechaInicio").value;
	var ff = document.getElementById("buscarFechaFin").value;
	if (fi != "")
	{
		rango["inicio"] = dmy(fi);
	}
	if (ff != "")
	{
		var d = new Date(ff);
		d.setDate(d.getDate() + 1);
		var ff1 = d.getFullYear() + "-" + ('0'+(d.getMonth()+1)).slice(-2) + "-" + ('0'+d.getDate()).slice(-2);
		rango["fin"] = dmy(ff1);
	}
	filtros["fechaRangoOManual"] = rango;

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var order = [{campo: document.getElementById("orden").value, dir: document.getElementById("ordenDesc").checked ? 'DESC' : 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}


function mostrarCargarRegistrosHoras()
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
	var consulta = "accion=modificarRegistroHoras";

	var fechaSQL = function(dt) { var p = dt.split("T"); var f = p[0].split("-"); return f[2]+"/"+f[1]+"/"+f[0]+" "+p[1]+":00"; };

	var datos = {
		horaInicio: fechaSQL(document.getElementById(id+"_fechaInicio").value),
		horaFin: fechaSQL(document.getElementById(id+"_fechaFin").value),
		cantidad: document.getElementById(id+"_cantidad").value,
		observaciones: document.getElementById(id+"_observaciones").value,
		idImpresoras: document.getElementById("impresoraModal2").value,
		idPapelTamano: document.getElementById("tamanoModal2").value,
		idPapelTipo: document.getElementById("tipoModal2").value,
		idPapelAcabado: document.getElementById("acabadoModal2").value,
		idPapelGramaje: document.getElementById("gramajeModal2").value,
		idPapelOrigen: document.getElementById("origenModal2").value,
		impresionNumeroCaras: document.getElementById("numCarasModal2").value,
		sinProceso_idConcepto: document.getElementById("procesoModal2").value,
		sinProceso_idCliente: document.getElementById("clienteModal2").value
	};

	//solo se cambia el codigoBarras si el usuario ha puesto uno (los manuales lo dejan vacio)
	var codigoBarras = document.getElementById(id+"_codigoBarras").value;
	if (codigoBarras != "")
	{
		datos["codigoBarras"] = codigoBarras;
	}

	var filtros = {id: id};

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarModificarRegistroTrabajo()
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
	var consulta = "accion=eliminarRegistroHoras";

	var filtros = {id: id};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarEliminarRegistroTrabajo3()
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

	var campos = ['id','proceso','descripcion'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {idTipoProceso: document.getElementById("tipoProceso").value, idDepartamento: 1};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarCargarSubProcesos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error != "")
			{
				alert(res.error);
				document.getElementById("procesoNombre").innerHTML = "";
			}
			else
			{				
				var datos = res.datos;

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

	var campos = ['id','proceso','descripcion'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {idTipoProceso: document.getElementById("tipoProcesoModal2").value, idDepartamento: 1};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarCargarSubProcesos2()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error != "")
			{
				alert(res.error);
				document.getElementById("procesoModal2").innerHTML = "";
			}
			else
			{				
				var datos = res.datos;

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
			peticionUnica1=null;
		}
	}						
}





