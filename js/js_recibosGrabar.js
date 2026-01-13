var peticionUnica1 = null;
var permisosSoloLectura = null;



function buscarFactura()
{
	var condicion=" where ";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	
	
	if ((campoAbuscar=="numeroRecibo" && textoAbuscar!=""))
	{
		condicion += campoAbuscar+" = '" + textoAbuscar  + "'";	
	}
	else if ((campoAbuscar=="idCliente" && textoAbuscar!=""))
	{
		condicion += campoAbuscar+" = " + textoAbuscar;	
	}
	else
	{
		condicion += campoAbuscar+" like '%" + textoAbuscar + "%'";	
	}
		
	
	
	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		var laFecha = fechaInicio.replace("/","-");
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];	
	
		var laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and fecha>= '"+laFecha1+"'";
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		var aux = new Date(fechaFin);
		aux.setDate(aux.getDate() + 1);    
		fechaFin = aux.getFullYear() + "-" + (aux.getMonth()+1) + "-" +  aux.getDate();
		
		laFecha = fechaFin.replace("/","-");
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];	
	
		laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and fecha <= '"+laFecha1+"'";
	}
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	
	
	
	cargarAlbaranes();
	
}

function cargarAlbaranes() //js_recibosGrabar			
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarAlbaranes;
		peticionUnica1.open("POST","ajax/cargarAlbaranes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string =consultaCargarAlbaranes();
		peticionUnica1.send(query_string);
	}	
}

function consultaCargarAlbaranes()
{	
	var consulta = "accion=cargarAlbaranes";	
	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	//consulta += "&condicion="+laCondicion;
	
	laCondicion=null;
	
	return consulta;	
}

function mostrarCargarAlbaranes()
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
				
				if (datos != "")
				{					
					contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					contenido += '<th align="center">Albarán</th>';						
					contenido += '<th>Fecha</th>';					
					contenido += '<th>Empleado</th>';
					contenido += '<th>Tipo</th>';
					contenido += '<th>Cliente</th>';	
					contenido += '<th>Cantidad</th>';
					contenido += '<th>Importe</th>';
					contenido += '<th>Descripcion</th>';					
					
					if (!permisosSoloLectura)
					{
						contenido += '<th></th>'; //imprimir
						contenido += '<th></th>'; //borrar
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
					
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_albaran"> '+datos[contador]["numeroRecibo"] + '</td>';
					
					var fecha1 = datos[contador]["fecha"]["date"].substring(0,19).replace(" ","T");
					var dia = fecha1.substr(8,2);
					var mes = fecha1.substr(5,2);
					var anio = fecha1.substr(0,4);
					var fecha = dia  + "-" + mes + "-" + anio + " " +  datos[contador]["fecha"]["date"].substr(11,8);
					
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_fecha">' + fecha + '</td>';
					
					contenido += '<td align="center" id = "'+datos[contador]["id"]+'_empleadoAlba">'+datos[contador]["nombre"]+'</td>';
					contenido += '<td align="center" id = "'+datos[contador]["id"]+'_tipoAlba">'+datos[contador]["tipo"]+'</td>';
					contenido += '<td align="left" id = "'+datos[contador]["id"]+'_clienteAlba">'+datos[contador]["idCliente"]+" - "+datos[contador]["nombre_franqueo"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_cantidad">' + datos[contador]["cantidad"] + '</td>';
					
					contenido += '<td align="right" id="'+datos[contador]["id"]+'_importe">' + Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2}) + ' €</td>';
					
					
					
					
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_descripcion">' + datos[contador]["descripcion"] + '</td>';
					

					if (!permisosSoloLectura)
					{
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_imprimir" src="imagenes/imprimir.png" style="width:15px;"  onclick="imprimirAlbaran('+datos[contador]["id"]+')"></td>';
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarAlbaran('+datos[contador]["id"]+')"></td>';
					}
					
					contenido += '</tr>';
					
					contador++;	
				}		
				
				document.getElementById("historicoAlbaranes").innerHTML = contenido;		
				}							
			}			
			peticionUnica1=null;
		}
	}						
}





function guardarAlbaran()	//js_recibosGrabar		
{
	if (document.getElementById("fechaAlbaran").value == "")
	{
		alert("Insertar la fecha del albaran");
		document.getElementById('fechaAlbaran').focus();
	}
	else if (document.getElementById("listadoNombreFranqueo").value==0)
	{
		alert("Insertar un Cliente");
		document.getElementById('listadoNombreFranqueo').focus();
	}
	else if (document.getElementById("cantidadAlbaran").value == "")
	{
		alert("Insertar la cantidad");
		document.getElementById('cantidadAlbaran').focus();
	}
	else if (document.getElementById("importeAlbaran").value == "")
	{
		alert("Insertar el importe");
		document.getElementById('importeAlbaran').focus();
	}
	else if (document.getElementById("descripcionAlbaran").value == "")
	{
		alert("Insertar la descripcion");
		document.getElementById('descripcionAlbaran').focus();
	}	
	else
	{	
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGuardarAlbaran;
			peticionUnica1.open("POST","ajax/guardarAlbaran.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGuardarAlbaran();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGuardarAlbaran()
{	
	var consulta = "accion=guardarAlbaran";	
	consulta += "&idEmpleado="+document.getElementById("listadoEmpleado").value;
	consulta += "&idTipoAlbaran="+document.getElementById("listadoTipoAlbaran").value;
	consulta += "&idCliente="+document.getElementById("listadoNombreFranqueo").value;
	
	consulta += "&fecha="+document.getElementById("fechaAlbaran").value;
	consulta += "&cantidad="+document.getElementById("cantidadAlbaran").value;
	consulta += "&importe="+document.getElementById("importeAlbaran").value;
	consulta += "&descripcion="+document.getElementById("descripcionAlbaran").value;
	
	return consulta;	
}

function mostrarGuardarAlbaran()
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
				
				cargarAlbaranes();
				//albaranUltimo();
				nuevoAlbaran();
				
							
			}
			peticionUnica1=null;
		}
	}						
}



function nuevoAlbaran() // js_recibosGrabar
{
	document.getElementById("numAlbaran").value = "";
	//document.getElementById("listadoEmpleado").value = 0;
	document.getElementById("listadoTipoAlbaran").value = 1;
	document.getElementById("listadoNombreFranqueo").value = 1;
	//document.getElementById("fechaAlbaran").value = albaran[filaActual]["fecha"]["date"].substring(0,10); //"2020-05-10"
	document.getElementById("fechaAlbaran").value = ""; //"2020-05-10"
	document.getElementById("cantidadAlbaran").value = "";
	document.getElementById("importeAlbaran").value = "";
	document.getElementById("descripcionAlbaran").value = "";	
}


function cargarListadoTipoAlabaran()	//js_recibosGrabar		
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoTipoAlabaran;
		peticionUnica1.open("POST","ajax/cargarListadoTipoAlabaran.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoTipoAlabaran();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarListadoTipoAlabaran()
{	
	var consulta = "accion=cargarListadoTipoAlabaran";	
	
	return consulta;	
}

function mostrarCargarListadoTipoAlabaran()
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
				
				var contador=0;
				var contenido="";
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tipo"]+ '</option>';
					contador++;
				}
				document.getElementById(idInputListado).innerHTML = contenido;	
							
			}
			peticionUnica1=null;
			idInputListado="";
		}
	}						
}

function imprimirAlbaran(id) //js_recibosGrabar
{
	
	document.getElementById("imprimirAlbaran_numero").value = document.getElementById(id+"_albaran").innerHTML;
	document.getElementById("imprimirAlbaran_empleado").value = document.getElementById(id+"_empleadoAlba").innerHTML;	
	document.getElementById("imprimirAlbaran_tipo").value = document.getElementById(id+"_tipoAlba").innerHTML;
	
	var idcliente = document.getElementById(id+"_clienteAlba").innerHTML.split(" - ");
	
	document.getElementById("imprimirAlbaran_cliente").value = idcliente[0];

	document.getElementById("imprimirAlbaran_fecha").value = document.getElementById(id+"_fecha").innerHTML; 
	document.getElementById("imprimirAlbaran_cantidad").value = document.getElementById(id+"_cantidad").innerHTML;
	document.getElementById("imprimirAlbaran_importe").value = document.getElementById(id+"_importe").innerHTML;
	document.getElementById("imprimirAlbaran_descripcion").value = document.getElementById(id+"_descripcion").innerHTML;	

	document.getElementById("formImprimirAlbaran").submit();
}


function eliminarAlbaran(id)	// js_recibosGrabar		
{	
	if (confirm('¿Eliminar el albarán: '+ document.getElementById(id + "_albaran").innerHTML+'?'))
	{
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarAlbaran;
			peticionUnica1.open("POST","ajax/eliminarAlbaran.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string =consultaEliminarAlbaran(id);
			peticionUnica1.send(query_string);
		}	
	}
}

function consultaEliminarAlbaran(id)
{	
	var consulta = "accion=eliminarAlbaran";	
	consulta +="&id="+id;
	return consulta;	
}

function mostrarEliminarAlbaran()
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
				cargarAlbaranes();
							
			}
			peticionUnica1=null;
		}
	}						
}

