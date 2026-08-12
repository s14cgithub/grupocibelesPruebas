var peticionUnica1 = null;
var idInputListado = null;
var permisosSoloLectura = null;

var laCondicion="";
var laId1="-1";


function cargarTiposProceso()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarTiposProceso;
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

function mostrarCargarTiposProceso()
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

			document.getElementById("tipoProcesoModal").innerHTML = contenido;
			peticionUnica1=null;
		}
	}
}


function buscarRegistros()
{
	cargarRegistrosSinProcesos();
}

function cargarRegistrosSinProcesos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRegistrosSinProcesos;
		peticionUnica1.open("POST","ajax/cargarRegistrosHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosSinProcesos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosSinProcesos()
{	
	var consulta = "accion=cargarRegistrosHoras";

	var campos = ['id','horaInicio','nombreEmpleado','departamento','idDepartamento','tipoProceso','proceso','cantidad','observaciones','subcliente'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = ['tabla_procesoManual','tabla_procesosTiposManual','tabla_procesosDepartamentoManual','tabla_clienteManual'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtros = {codigoBarras: '0-9999999'};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var filtrosOperadores = [];
	if (document.getElementById("buscarFechaInicio").value != "")
	{
		filtrosOperadores.push({campo1: 'fechaInicioDia', valor: document.getElementById("buscarFechaInicio").value, operador: '>='});
	}
	if (document.getElementById("buscarFechaFin").value != "")
	{
		filtrosOperadores.push({campo1: 'fechaInicioDia', valor: document.getElementById("buscarFechaFin").value, operador: '<='});
	}
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{campo: 'subcliente', dir: 'ASC'}];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarRegistrosSinProcesos()
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
				var clienteAntiguo = "asdjfaksñdfj j232r f";
				var contraste="";

				while  (contador<datos.length)
				{  
					if (clienteAntiguo != datos[contador]["subcliente"])
					{
						
						clienteAntiguo = datos[contador]["subcliente"]
						contenido += "<tr bgcolor='green'><td colspan='7' >"+clienteAntiguo+"</td></tr>";

						contenido += "<tr>";

						
						contenido += "<th>Inicio</th>";	
						contenido += "<th>Usuario</th>";
						contenido += "<th>Departamento</th>";					
						contenido += "<th>Tipo</th>";
						contenido += "<th>Proceso</th>";
						contenido += "<th>Cantidad</th>";
						contenido += "<th>Observacion</th>";
						

						contenido += "</tr>";
					}	



					if (contador%2==0)
					{
						contraste = "";
					}
					else
					{						
						contraste = ' class="tablaContenidoColor" ';
					}
					
					contenido += '<tr ' + contraste + '>';




					
					
					contenido += "<td>"+datos[contador]["horaInicio"]["date"]+"</td>";
					contenido += "<td>"+datos[contador]["nombreEmpleado"]+"</td>";
					contenido += "<td>"+datos[contador]["departamento"]+"</td>";
					contenido += "<td>"+datos[contador]["tipoProceso"]+"</td>";
					contenido += "<td>"+datos[contador]["proceso"]+"</td>";
					contenido += "<td>"+datos[contador]["cantidad"]+"</td>";
					contenido += "<td>"+datos[contador]["observaciones"]+"</td>";
					
					contenido += "</tr>";

					if (!permisosSoloLectura)
					{
						contenido += '<tr ' + contraste + '>';
						contenido += '<td colspan="7" style="overflow:hidden; white-space: nowrap;">';
						contenido += 'OT: <input type="text" id="'+datos[contador]["id"]+'_ot" onchange="cargarListadoProcesos('+datos[contador]["id"]+','+datos[contador]["idDepartamento"]+');">';
						contenido += 'Proceso: <select id="'+datos[contador]["id"]+'_proceso">';
						contenido += '</select>';
	
						contenido += '<button type="button" class="btn btn-info" onClick="comprobarInsertarProceso('+datos[contador]["id"]+')">Guardar</button>';
						
						
						contenido += '</td>';
						contenido += '</tr>';
					}

					


					contenido += "<tr><hr></tr>";
					
					contador++;	
				}
				
				
				
				
				document.getElementById("registrosHoras").innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


function comprobarInsertarProceso(laIdRegistroHora)
{
	if (document.getElementById(laIdRegistroHora + "_ot").value.length==7 && document.getElementById(laIdRegistroHora + "_proceso").value!='')
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarComprobarInsertarProceso;
			peticionUnica1.open("POST","ajax/modificarRegistroHoras2.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaComprobarInsertarProceso(laIdRegistroHora);
			peticionUnica1.send(query_string);
		}
	}
	
}
function consultaComprobarInsertarProceso(laIdRegistroHora)
{	
	var consulta = "accion=modificarRegistroHoras";

	var datos = {codigoBarras: document.getElementById(laIdRegistroHora + "_proceso").value};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {id: laIdRegistroHora};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarComprobarInsertarProceso()
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
				alert("Proceso Insertado");			
				buscarRegistros();			
			}
			peticionUnica1=null;	
				
		}
	}						
}


function cargarListadoProcesos(laId,idDepartamento)
{
	if (document.getElementById(laId+"_ot").value.length==7)
	{
		laId1=laId;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarListadoProcesos;
			peticionUnica1.open("POST","ajax/cargarDetallesPresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarListadoProcesos(idDepartamento);
			peticionUnica1.send(query_string);
		}
	}
} 



function consultaCargarListadoProcesos(idDepartamento)
{	
	var consulta = "accion=cargarDetalles";

	var campos = ['id','presupuesto','tipoProceso','proceso','descripcion'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	//procesosTipos (t2), procesos (t3) y procesosDepartamento (t4) ya vienen fijos en el FROM de cargarDetallesPresupuesto; no hay que mandarlos como joins
	var filtros = {presupuesto: document.getElementById(laId1+"_ot").value, idDepartamento: idDepartamento};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarCargarListadoProcesos()
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
					contenido += '<option value="'+datos[contador]["id"]+'-'+datos[contador]["presupuesto"]+'">'+datos[contador]["tipoProceso"]+'/'+datos[contador]["proceso"]+'/'+datos[contador]["descripcion"].substr(0,50)+'</option>';
					
					contador++;	
				}
				
				
				
				
				document.getElementById(laId1+"_proceso").innerHTML = contenido;
				idInputListado="";

				laId1="-1";	
						
			}
			peticionUnica1=null;	
				
		}
	}						
}







