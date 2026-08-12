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


function cargarConcepto(idInput) //js_pdaGestion
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarConcepto;
		peticionUnica1.open("POST","ajax/cargarConceptosGF.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarConcepto();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarConcepto()
{	
	var consulta = "accion=cargarConcepto";

	var campos = ['id','nombreConcepto'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [{campo: 'nombreConcepto', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarConcepto()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreConcepto"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}

function cargarSubConcepto1(idInput) //js_pdaGestion
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarSubConcepto1;
		peticionUnica1.open("POST","ajax/cargarSubConceptos1GF.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubConcepto1();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarSubConcepto1()
{	
	var consulta = "accion=cargarConcepto";

	var campos = ['id','nombreSubconcepto'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var idConcepto;
	var arrayCampo = idInputListado.split("_");
	if (arrayCampo[0]=="RN")
	{
		idConcepto = document.getElementById("RN_concepto").value;
	}
	else 
	{		
		idConcepto = document.getElementById(arrayCampo[0] + "_concepto").value;
	}
	arrayCampo = null;	

	var filtros = {idConcepto: idConcepto};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarCargarSubConcepto1()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreSubconcepto"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;

				var arrayCampo = idInputListado.split("_");
				if (arrayCampo[0]=="RN")
				{
					cargarSubConcepto2("RN_subconcepto2");
				}
				else 
				{
					cargarSubConcepto2(arrayCampo[0] + "_subconcepto2"); 
				}
				arrayCampo = null;

				idInputListado="";
				//cargarSubConcepto2("RN_subconcepto2");
						
			}
			peticionUnica1=null;			
		}
	}						
}

function cargarSubConcepto2(idInput) //js_pdaGestion
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarSubConcepto2;
		peticionUnica1.open("POST","ajax/cargarSubConceptos2GF.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubConcepto2();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarSubConcepto2()
{	
	var consulta = "accion=cargarConcepto";

	var campos = ['id','nombreSubconcepto2'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var idSubconcepto1;
	var arrayCampo = idInputListado.split("_");
	if (arrayCampo[0]=="RN")
	{
		idSubconcepto1 = document.getElementById("RN_subconcepto1").value;
	}
	else 
	{		
		idSubconcepto1 = document.getElementById(arrayCampo[0] + "_subconcepto1").value;
	}
	arrayCampo = null;	

	var filtros = {idSubconcepto1: idSubconcepto1};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarCargarSubConcepto2()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreSubconcepto2"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


//////////////////////////////////////////////////////////


function comprobarInsertarRegistroHoraManual()
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
		idGFSubconjunto2: document.getElementById("RN_subconcepto2").value
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

	var campos = ['id','gfTipoProceso','gfProceso','gfDescripcion','sinProceso_idConcepto','sinProceso_idTipoProceso','sinProceso_idCliente','codigoBarras','horaInicio','horaFin','cantidad','observaciones','idGFConcepto','idGFSubconcepto1','idGFSubconjunto2'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = ['tabla_gfDetalle','tabla_gfProcesosTipos','tabla_gfProcesos','tabla_gfProcesoSin','tabla_gfSub2','tabla_gfSub1','tabla_gfConcepto'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtros = {idEmpleado: "@sesion", idGFSubconjunto2NotNull: true};
	var ot = document.getElementById("buscarOtValor").value;
	if (ot != "")
	{
		filtros["otLike"] = ot;
	}
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var dmy = function(s) { var p = s.split("-"); return p[2] + "-" + p[1] + "-" + p[0]; };
	var filtrosOperadores = [];
	var fi = document.getElementById("buscarFechaInicio").value;
	var ff = document.getElementById("buscarFechaFin").value;
	if (fi != "")
	{
		filtrosOperadores.push({campo1: 'horaInicio', valor: dmy(fi), operador: '>='});
	}
	if (ff != "")
	{
		var d = new Date(ff);
		d.setDate(d.getDate() + 1);
		var ff1 = d.getFullYear() + "-" + ('0'+(d.getMonth()+1)).slice(-2) + "-" + ('0'+d.getDate()).slice(-2);
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
				var datos = res.datos;
				
				var contenido = "";

				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
				contenido +='<th align="center">Id</th>';				
				contenido +='<th>Proceso</th>';
				contenido +='<th>Hora Inicio</th>';				
				contenido +='<th>Hora Fin</th>';
				contenido +='<th>Cantidad</th>';
				contenido +='<th>Observaciones</th>';
				contenido +='<th>Producto</th>';				
				
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

					contenido +='<td align="center"><select id="'+datos[contador]["id"]+'_concepto" onchange="cargarSubConcepto1(\''+datos[contador]['id']+'_subconcepto1\');" value=""></td>';
					contenido +='<td align="center"><select id="'+datos[contador]["id"]+'_subconcepto1"   onchange="cargarSubConcepto2(\''+datos[contador]['id']+'_subconcepto2\');" value=""></td>';
					contenido +='<td align="center"><select id="'+datos[contador]["id"]+'_subconcepto2" value=""></td>';
					
					
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="comprobarModificaregistroHoraManual('+datos[contador]["id"]+')" ></td>';
					contenido +='<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
					contenido +='</tr>';					
					
					contador++;
					
				}
				
				document.getElementById("registrosHoras").innerHTML = contenido;

				contador=0;
				while  (contador<datos.length)
				{

					cargarConcepto(datos[contador]["id"]+'_concepto');

					document.getElementById(datos[contador]["id"]+'_concepto').value =   datos[contador]["idGFConcepto"];

					cargarSubConcepto1(datos[contador]["id"]+'_subconcepto1');
					document.getElementById(datos[contador]["id"]+'_subconcepto1').value =   datos[contador]["idGFSubconcepto1"];
					
					
					cargarSubConcepto2(datos[contador]["id"]+'_subconcepto2');					
					document.getElementById(datos[contador]["id"]+'_subconcepto2').value =   datos[contador]["idGFSubconjunto2"];

					

					
					contador++;
				}
						
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
		//alert("aaaaaae"); 

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
	var consulta = "accion=modificarRegistroHoras";

	var fechaSQL = function(dt) { var p = dt.split("T"); var f = p[0].split("-"); return f[2]+"/"+f[1]+"/"+f[0]+" "+p[1]+":00"; };

	var datos = {
		horaInicio: fechaSQL(document.getElementById(id+"_fechaInicio").value),
		horaFin: fechaSQL(document.getElementById(id+"_fechaFin").value),
		cantidad: document.getElementById(id+"_cantidad").value,
		observaciones: document.getElementById(id+"_observaciones").value,
		idGFSubconjunto2: document.getElementById(id+"_subconcepto2").value
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

	var filtros = {idTipoProceso: document.getElementById("tipoProceso").value, idDepartamento: 4};
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

	var filtros = {idTipoProceso: document.getElementById("tipoProcesoModal").value, idDepartamento: 1};
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
				document.getElementById("procesoModal").innerHTML = "";
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
					
				document.getElementById("procesoModal").innerHTML = contenido;						
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
	var consulta = "accion=modificarRegistroHoras";

	var datos = {sinProceso_idConcepto: document.getElementById("procesoModal").value, sinProceso_idCliente: document.getElementById("clienteModal").value};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {id: id};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
		
	return consulta;
}

function mostrarCambiarProcesoSinIndicar()
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
				$("#modalRegistroManualSinProceso").modal('hide');		
				//cargarRegistrosHoras();
				
			}
			peticionUnica1=null;
		}
	}						
}

/*function cargarAutomaticosPda()//js_pdaGestion
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

						contenido += '<td align="center"><input type="text" id="'+datos[contador]["id"]+'_codigoBarras" value="'+datos[contador]["codigoBarras"]+'"></td>';

						contenido += '<td align="center"><input type="date"  id="'+datos[contador]["id"]+'_fechaInicio" value="'+datos[contador]["horaInicio"]["date"].substring(0,10)+'"></td>'; 

						contenido += '<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horaInicio" value="'+datos[contador]["horaInicio"]["date"].substring(11,19)+'"></td>';

						contenido += '<td align="center"><label id="'+datos[contador]["id"]+'_modo">'+datos[contador]["modo"]+'</label></td>';

						contenido += '<td align="center"><input type="date" id="'+datos[contador]["id"]+'_fechaFin" value="'+datos[contador]["horaFin"]["date"].substring(0,10)+'"></td>';

						contenido += '<td align="center"><input type="time" id="'+datos[contador]["id"]+'_horaFin" value="'+datos[contador]["horaFin"]["date"].substring(11,19)+'"></td>';

						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';

						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroTrabajo('+datos[contador]["id"]+')" ></td>';
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




*/


