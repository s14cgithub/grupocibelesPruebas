var peticionUnica1 = null;
var permisosSoloLectura = null;
var vieneDeAutomaticos = false;

//muestra u oculta el buscador y el formulario de alta (se ocultan mientras haya cierres automaticos pendientes)
function mostrarBuscadorAlta(mostrar)
{
	var b = document.getElementById("buscador");
	if (b != null) { b.style.display = mostrar ? "" : "none"; }
	var f = document.getElementById("insertarNuevoRegistroManual");
	if (f != null) { f.style.display = mostrar ? "" : "none"; }
}

function cargarAutomaticosPda()//js_pdaGestion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarAutomaticosPda;
		peticionUnica1.open("POST","ajax/cargarRegistrosHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAutomaticosPda();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarAutomaticosPda()
{	
	var consulta = "accion=cargarRegistrosHoras";

	var campos = ['id','nombreEmpleado','codigoBarras','horaInicio','modo','horaFin'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {modo: 'automatico'};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarCargarAutomaticosPda()
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
				
				if (datos.length==0)
				{
					cargarRegistrosHoras();
				}
				else
				{
					//hay cierres automaticos pendientes: se oculta el buscador y el formulario de alta
					mostrarBuscadorAlta(false);

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
					}//while

					document.getElementById("registrosHoras").innerHTML = contenido;
					
				}
				
				
						
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
		peticionUnica1.open("POST","ajax/cargarRegistrosHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosHoras();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosHoras()
{	
	var consulta = "accion=cargarRegistrosHoras";

	var campos = ['id','nombreEmpleado','codigoBarras','estado','horaInicio','horaFin','cantidad','observaciones'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var dmy = function(s) { var p = s.split("-"); return p[2] + "-" + p[1] + "-" + p[0]; };

	var filtros = {};
	var ot = document.getElementById("buscarOtValor").value;
	if (ot != "")
	{
		filtros["otLike"] = ot;
	}
	var empleado = document.getElementById("buscarEmpleado").value;
	if (empleado != "")
	{
		filtros["idEmpleado"] = empleado;
	}
	if (document.getElementById("masde10horas").checked)
	{
		filtros["masde10horas"] = true;
	}
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	//la fecha de inicio se limita a "N meses" atras (dia 1 del mes): no se permite buscar mas antiguo que ese limite
	var fi = document.getElementById("buscarFechaInicio").value;
	var meses = parseInt(document.getElementById("buscarNumMeses").value);
	if (meses > 0)
	{
		var d = new Date();
		d.setMonth(d.getMonth() - (meses - 1));
		var fechaInicioLimite = d.getFullYear() + "-" + ('0'+(d.getMonth()+1)).slice(-2) + "-01";
		if (fi == "" || fechaInicioLimite > fi)
		{
			fi = fechaInicioLimite;
		}
	}

	var filtrosOperadores = [];
	if (fi != "")
	{
		filtrosOperadores.push({campo1: 'horaInicio', valor: dmy(fi), operador: '>='});
	}
	var ff = document.getElementById("buscarFechaFin").value;
	if (ff != "")
	{
		var d2 = new Date(ff);
		d2.setDate(d2.getDate() + 1);
		var ff1 = d2.getFullYear() + "-" + ('0'+(d2.getMonth()+1)).slice(-2) + "-" + ('0'+d2.getDate()).slice(-2);
		filtrosOperadores.push({campo1: 'horaInicio', valor: dmy(ff1), operador: '<'});
	}
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

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
				//listado normal: se muestran de nuevo el buscador y el formulario de alta
				mostrarBuscadorAlta(true);

				var datos = res.datos;
				
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
		peticionUnica1.open("POST","ajax/insertarRegistroHoraManualGestion.php",false);
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
		idEmpleado: document.getElementById("RN_empleado").value,
		codigoBarras: (proceso != "") ? proceso : "0-9999999",
		horaInicio: fechaSQL(document.getElementById("RN_fechaInicio").value),
		horaFin: fechaSQL(document.getElementById("RN_fechaFin").value),
		estado: "cerrado",
		cantidad: document.getElementById("RN_cantidad").value,
		observaciones: document.getElementById("RN_observaciones").value,
		modo: "manual"
	};

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
		peticionUnica1.open("POST","ajax/cargarListadoEmpleado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarEmpleadosPDA();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarEmpleadosPDA()
{	
	var consulta = "accion=cargarListadoEmpleado";

	var campos = ['idEmpleado','nombre','apellidos'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {pda_o_registrosManuales: 1};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var joins = ['tabla_login','tabla_permisos'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var order = [{campo: 'nombre', dir: 'ASC'}, {campo: 'apellidos', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarEmpleadosPDA()
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
	//si algun campo de la fila esta vacio, no se modifica nada (en cierres automaticos no existen cantidad/observaciones, por eso se comprueba que el input exista)
	var campos = ['_codigoBarras','_fechaInicio','_horaInicio','_fechaFin','_horaFin','_cantidad','_observaciones'];
	for (var i = 0; i < campos.length; i++)
	{
		var el = document.getElementById(id + campos[i]);
		if (el != null && el.value == "")
		{
			alert("Rellenar todos los campos");
			return;
		}
	}

	//el registro viene de los cierres automaticos si tiene el label _modo
	vieneDeAutomaticos = (document.getElementById(id+"_modo") != null);

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

	//en esta pantalla la fecha y la hora vienen en inputs separados (date + time)
	var fechaHoraSQL = function(fecha, hora) { var f = fecha.split("-"); return f[2]+"/"+f[1]+"/"+f[0]+" "+hora; };
	//lee el valor de un input aunque no exista (en cierres automaticos no hay cantidad/observaciones)
	var val = function(sufijo) { var el = document.getElementById(id + sufijo); return el ? el.value : ""; };

	var datos = {
		horaInicio: fechaHoraSQL(val("_fechaInicio"), val("_horaInicio"))
	};

	//la hora fin solo se manda si el registro esta cerrado (tiene fecha y hora fin)
	var fechaFin = val("_fechaFin");
	var horaFin = val("_horaFin");
	if (fechaFin != "" && horaFin != "")
	{
		datos["horaFin"] = fechaHoraSQL(fechaFin, horaFin);
	}

	//cantidad y observaciones solo existen en el listado normal, no en los cierres automaticos
	if (document.getElementById(id+"_cantidad") != null)
	{
		datos["cantidad"] = val("_cantidad");
	}
	if (document.getElementById(id+"_observaciones") != null)
	{
		datos["observaciones"] = val("_observaciones");
	}

	//si el registro viene de los cierres automaticos (tiene el label _modo), al modificarlo pasa a modo manual
	if (document.getElementById(id+"_modo") != null)
	{
		datos["modo"] = "manual";
	}

	//solo se cambia el codigoBarras si el usuario ha puesto uno (los manuales lo dejan vacio)
	var codigoBarras = val("_codigoBarras");
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
			else if (vieneDeAutomaticos)
			{
				cargarAutomaticosPda();
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
	//el registro viene de los cierres automaticos si tiene el label _modo
	vieneDeAutomaticos = (document.getElementById(id+"_modo") != null);

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
			else if (vieneDeAutomaticos)
			{
				cargarAutomaticosPda();
			}
			else
			{				
				cargarRegistrosHoras();				
			}
			peticionUnica1=null;
		}
	}						
}


