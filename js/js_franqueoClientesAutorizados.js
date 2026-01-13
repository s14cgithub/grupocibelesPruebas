var peticionUnica1 = null;
var laCondicion="";
var anioSeleccionado="";
var permisosSoloLectura = null;

function buscarRegistroFranqueo()
{
	var condicion=" where activo = 1";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("buscarDesc").checked;
	
	
	
	if (campoAbuscar =="codigo" && textoAbuscar!="")
	{
		condicion += " and "+campoAbuscar+" = '" + textoAbuscar + "'";
	}
	else
	{
		condicion += " and "+campoAbuscar+" like '%" + textoAbuscar + "%'";
	}
	
		
	
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = reemplazarSimbolosBusqueda(condicion);
	
	
	cargarClienteAutorizados();
	
}



function cargarClienteAutorizados()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClienteAutorizados;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClienteAutorizados();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarClienteAutorizados()
{	
	var consulta = "accion=cargarClientes";	
	
	consulta +="&condicion=" + laCondicion;	
	
	return consulta;
}


function mostrarCargarClienteAutorizados()
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
				
				var contenido = "";
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Codigo</th>';	
				contenido += '<th align="center">Cliente</th>';	
				contenido += '<th>Nombre Franqueo</th>';
				contenido += '<th>Autorizado</th>';	
				
				if (!permisosSoloLectura)
				{
					contenido += '<th></th>';		
				}
						
				contenido += '</tr>';
			
				var contador = 0;
				var contraste = "";
				
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
					
					
					contenido += '<tr ' + contraste + '>';					

					contenido += '<td>'+datos[contador]["codigo"]+ '</td>';		
					
					contenido += '<td>'+datos[contador]["nombre_empresa"]+'</td>';		
					contenido += '<td>'+datos[contador]["nombre_franqueo"]+'</td>';	

					contenido += '<td><select id="'+datos[contador]["codigo"]+'_autorizado">';	

					var seleccionado="";
					var idSeleccionado=datos[contador]["idAutorizacionFranqueo"];

					
					if (idSeleccionado==1)
					{
						contenido += '<option value="1" selected="selected">No Autorizado</option>';						
						contenido += '<option value="2">Autorizado</option>';
						contenido += '<option value="3">Autorizado por un dia</option>';	
					}
					else if (idSeleccionado==2)
					{
						contenido += '<option value="1">No Autorizado</option>';						
						contenido += '<option value="2" selected="selected">Autorizado</option>';
						contenido += '<option value="3">Autorizado por un dia</option>';	
					}
					else  if (idSeleccionado==3)
					{
						contenido += '<option value="1">No Autorizado</option>';						
						contenido += '<option value="2">Autorizado</option>';
						contenido += '<option value="3" selected="selected">Autorizado por un dia</option>';	
					}

					
					contenido += '</select></td>';		


					if (!permisosSoloLectura)
					{
						contenido +='<td><input type="image" id="'+datos[contador]["codigo"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarClienteAutorizado('+datos[contador]["codigo"]+')" ></td>';	
					}
					
					
					

						
					//contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
						
					
					
					contenido += '</tr>';
					
					contador++;	
				}

				document.getElementById("historico1").innerHTML = contenido;

			}
			peticionUnica1=null;
			
		}
	}						
}

function modificarClienteAutorizado(elCodigo)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarClienteAutorizado;
		peticionUnica1.open("POST","ajax/modificarClienteAutorizado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarClienteAutorizado(elCodigo);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarClienteAutorizado(elCodigo)
{	
	var consulta = "accion=modificarClienteAutorizado";	
	
	consulta +="&codigo=" + elCodigo;
	consulta +="&valor="+document.getElementById(elCodigo+"_autorizado").value;	
	
	return consulta;
}


function mostrarModificarClienteAutorizado()
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
			}
		}
	}
}
