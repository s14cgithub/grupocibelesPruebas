var peticionUnica1 = null;

function guardarCertificado() //js_certGrabar		
{
	if(document.getElementById("fechaFranqueo").value == "")
	{
		alert("Insertar una fecha");
		document.getElementById('fechaFranqueo').focus();
	}
	else if (document.getElementById("listadoNombreFranqueo").value == "")
	{
		alert("Insertar un nombre de franqueo");
		document.getElementById('listadoNombreFranqueo').focus();
	}
	else if (document.getElementById("unidades").value == "")
	{
		alert("Insertar las unidades");
		document.getElementById('unidades').focus();
	}
	else if (document.getElementById("listadoProducto").value == "")
	{
		alert("Insertar un producto");
		document.getElementById('listadoProducto').focus();
	}
	else
	{	
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGuardarCertificado;
			peticionUnica1.open("POST","ajax/guardarCertificado.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGuardarCertificado();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGuardarCertificado()
{	
	var consulta = "accion=guardarCertificado";	
	consulta += "&idCliente="+document.getElementById("listadoNombreFranqueo").value;
	consulta += "&unidad="+document.getElementById("unidades").value;
	consulta += "&producto="+document.getElementById("listadoProducto").value;
	consulta += "&fecha="+document.getElementById("fechaFranqueo").value;
	
	return consulta;	
}

function mostrarGuardarCertificado()
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
				cargarCertificados();
				
				document.getElementById("listadoNombreFranqueo").value = "";
				document.getElementById("unidades").value = "";
				document.getElementById("listadoProducto").value = "";
				document.getElementById("listadoNombreFranqueo").focus();
			}
			peticionUnica1=null;
		}
	}						
}

function cargarCertificados() //js_certGrabar			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarCertificados;
		peticionUnica1.open("POST","ajax/cargarCertificados.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarCertificados();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarCertificados()
{	
	var consulta = "accion=cargarCertificados";
	
	return consulta;	
}


function mostrarCargarCertificados()
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
				
				if (datos != "")
				{					
					var contenido = "";

					contenido+='<tr class="centrarTexto tablaCabeceraColor">';						
					
					contenido+='<th align="center"><b>Cliente</b></th>';
					contenido+='<th align="center"><b>Nombre Franqueo</b></th>';
					contenido+='<th align="center"><b>Unidades</b></th>';
					contenido+='<th align="center"><b>Descripcion</b></th>';
					contenido+='<th align="center"><b>Fecha</b></th>';
					contenido+='<th align="center"></th>';
						
					contenido+='</tr>';					
					
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
						
					contenido+='<td id="'+datos[contador]["id"]+'_certificado" style="text-align: center">'+datos[contador]["idCliente"]+'</td>';	
						contenido+='<td><input id="'+datos[contador]["id"]+'_nomFranqueo" value="'+datos[contador]["nombre_franqueo"]+'" style="width: 100%;text-align: center" readonly></td>';			
						contenido+='<td><input value="'+datos[contador]["unidades"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["producto"]+'" style="width: 100%;text-align: center" readonly></td>';
													
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
						
						contenido+='<td><input value="'+dia + "-" + mes+ "-" + anio+'" style="width: 100%;text-align: center" readonly></td>';
												
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarCertificado" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroCertificado('+datos[contador]["id"]+')"></td>';												
						contenido+='</tr>';						
						contador++;
					}
					
					document.getElementById("historico1").innerHTML = contenido;					
				}
				else
				{
					document.getElementById("historico1").innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}						
}


function informeCertificados() //js_certGrabar
{
	if (document.getElementById("fechaInicioModal").value == "")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioModal").focus();
	}
	else if (document.getElementById("fechaFinModal").value == "")
	{
		alert("Introducir una fecha de Fin");
		document.getElementById("fechaFinModal").focus();
	}
	else
	{	
		document.getElementById("imprimirIdCliente").value = document.getElementById("clienteModal").value;
		document.getElementById("imprimirFechaInicio").value = document.getElementById("fechaInicioModal").value;
		document.getElementById("imprimirFechaFin").value = document.getElementById("fechaFinModal").value;
		document.getElementById("formImprimirCertificados").submit();		
	}
}

function cargarCertificadoProductos()	//js_certGrabar		
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarCertificadoProductos;
		peticionUnica1.open("POST","ajax/cargarCertificadoProductos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarCertificadoProductos();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarCertificadoProductos()
{	
	var consulta = "accion=cargarCertificadoProductos";	
	
	return consulta;	
}

function mostrarCargarCertificadoProductos()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["producto"]+'</option>';
					
					contador++;
				}
				document.getElementById("listadoProducto").innerHTML = contenido;							
			}
			peticionUnica1 = null;
		}
	}						
}

function eliminarRegistroCertificado(id) //js_certGrabar
{
	if (confirm("¿Eliminar certificado: "+id+"?")) 
	{
	  eliminarRegistroCertificado2(id);
	} 	
}


function eliminarRegistroCertificado2(id) //js_certGrabar
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroTrabajo2;
		peticionUnica1.open("POST","ajax/eliminarRegistroCertificado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarRegistroTrabajo2(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaEliminarRegistroTrabajo2(id)
{	
	var consulta = "accion=eliminarRegistro";	
	consulta += "&id=" + id;	
	return consulta;	
}

function mostrarEliminarRegistroTrabajo2()
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
				cargarCertificados();
			}
			
			peticionUnica1=null;
		}
	}						
}

