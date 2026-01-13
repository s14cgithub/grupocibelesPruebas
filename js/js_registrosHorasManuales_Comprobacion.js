var peticionUnica1 = null;
var idInputListado = null;
var permisosSoloLectura = null;

var laCondicion="";
var laId1="-1";


function buscarRegistros()
{
	var condicion="";

	condicion = "where t1.codigoBarras = '0-9999999' ";

	if (document.getElementById("buscarFechaInicio").value!="")
	{
		condicion += " and  convert(date, t1.horaInicio) >= '"+document.getElementById("buscarFechaInicio").value+"'";
	}
	if (document.getElementById("buscarFechaFin").value!="")
	{
		condicion += " and  convert(date, t1.horaInicio) <= '"+document.getElementById("buscarFechaFin").value+"'";
	}
	



	condicion += " order by t6.subcliente";
	laCondicion = condicion;
	condicion = condicion.replaceAll('%','%25');
	cargarRegistrosSinProcesos(condicion);
}

function cargarRegistrosSinProcesos(condicion="")
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRegistrosSinProcesos;
		peticionUnica1.open("POST","ajax/cargarRegistrosHoras.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosSinProcesos(condicion);
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosSinProcesos(condicion)
{	
	var consulta = "accion=cargarRegistros";
	consulta += "&condicion="+condicion;	
	return consulta;	
}

function mostrarCargarRegistrosSinProcesos()
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
			peticionUnica1.open("POST","ajax/insertarProceso_RegistroHora.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaComprobarInsertarProceso(laIdRegistroHora);
			peticionUnica1.send(query_string);
		}
	}
	
}
function consultaComprobarInsertarProceso(laIdRegistroHora)
{	
	var consulta = "accion=insertarProceso";
	consulta += "&idRegistroHora="+laIdRegistroHora;
	consulta += "&idProceso=" + document.getElementById(laIdRegistroHora + "_proceso").value;
	
	return consulta;	
}

function mostrarComprobarInsertarProceso()
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
			peticionUnica1.open("POST","ajax/cargarProcesosEspecificos.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarListadoProcesos(idDepartamento);
			peticionUnica1.send(query_string);
		}
	}
} 



function consultaCargarListadoProcesos(idDepartamento)
{	
	var consulta = "accion=cargarProcesos";
	consulta += "&ot="+document.getElementById(laId1+"_ot").value;
	consulta += "&idDepartamento=" + idDepartamento;
	
	return consulta;	
}

function mostrarCargarListadoProcesos()
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







