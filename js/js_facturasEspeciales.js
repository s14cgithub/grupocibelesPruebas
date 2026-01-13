var peticionUnica1 = null;

function guardarFacturaEspecial() //js_facturasEspeciales
{	
	if (document.getElementById("nombreCliente").value==0)
	{
		alert("Introduce un cliente");
		document.getElementById("nombreCliente").focus();
	}
	else if (document.getElementById("ordenTrabajo").value=="")
	{
		alert("Introduce un valor en la orden de trabajo");
		document.getElementById("ordenTrabajo").focus();
	}	
	else if (document.getElementById("fecha").value=="")
	{
		alert("Introduce una fecha");
		document.getElementById("fecha").focus();
	}
	else if (document.getElementById("concepto").value=="")
	{
		alert("Introduce un concepto");
		document.getElementById("concepto").focus();
	}
	else if (document.getElementById("unidades").value=="")
	{
		alert("Introduce una  unidad");
		document.getElementById("unidades").focus();
	}
	else if (document.getElementById("importe").value=="")
	{
		alert("Introduce un importe");
		document.getElementById("importe").focus();
	}
	else if (document.getElementById("ordenTrabajo").value.toUpperCase()!="CD" && document.getElementById("ordenTrabajo").value.toUpperCase()!="BUROFAX" && document.getElementById("ordenTrabajo").value.toUpperCase()!="OTROS CONCEPTOS" && document.getElementById("ordenTrabajo").value.toUpperCase()!="FACTURAS" && document.getElementById("ordenTrabajo").value.toUpperCase()!="CROTALES" && document.getElementById("ordenTrabajo").value.toUpperCase()!="RECOGIDAS")
	{
		alert("En Orden de Trabajo se debe poner alguna de las siguientes opciones:\n\n- CD\n- BUROFAX\n- CROTALES\n- RECOGIDAS\n- OTROS CONCEPTOS");
	}
	else
	{
		guardarFacturaEspecial2();
	}
}

function guardarFacturaEspecial2()	//js_facturasEspeciales			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGuardarFacturaEspecial2;
		peticionUnica1.open("POST","ajax/guardarFacturaEspecial.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaGuardarFacturaEspecial2();
		peticionUnica1.send(query_string);						
	}
}

function consultaGuardarFacturaEspecial2()
{	
	var consulta = "accion=guardarFacturaEspecial2";
	
	//consulta += "&idCliente=" + document.getElementById("numCliente").value;
	consulta += "&idCliente=" + document.getElementById("nombreCliente").value;	
	consulta += "&ordenTrabajo=" + document.getElementById("ordenTrabajo").value;
	consulta += "&fecha=" + document.getElementById("fecha").value;
	consulta += "&concepto=" + document.getElementById("concepto").value;
	consulta += "&unidades=" + document.getElementById("unidades").value;
	consulta += "&importe=" + document.getElementById("importe").value;	
	
	return consulta;	
}


function mostrarGuardarFacturaEspecial2()
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
				cargarFacturasEspeciales();		
			}
			peticionUnica1=null;
		}
	}						
}

function cargarFacturasEspeciales()	//js_facturasEspeciales			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarFacturasEspeciales;
		peticionUnica1.open("POST","ajax/cargarFacturasEspeciales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarFacturasEspeciales();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarFacturasEspeciales()
{	
	var consulta = "accion=cargarFacturasEspeciales";
	
	return consulta;	
}


function mostrarCargarFacturasEspeciales()
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

					contenido += '<tr class="centrarTexto tablaCabeceraColor">';
						
					contenido+='<th align="center"><b>Id</b></th>';
					contenido+='<th align="center"><b>Cliente</b></th>';
					contenido+='<th align="center"><b>Orden Trabajo</b></th>';
					contenido+='<th align="center"><b>Fecha</b></th>';
					contenido+='<th align="center"><b>Concepto</b></th>';
					contenido+='<th align="center">Unidades</th>';
					contenido+='<th align="center">Precio Unitario</th>';
					contenido+='<th align="center"></th>';
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
						
						contenido+='<td id="'+datos[contador]["id"]+'_certificado" value="" style="text-align: center">'+datos[contador]["id"]+'</td>';
						
						contenido+='<td id="'+datos[contador]["id"]+'_certCliente" value="" style="text-align: center">'+datos[contador]["nombre_franqueo"]+'</td>';	
						
						contenido+='<td value="" style="text-align: center">'+datos[contador]["ordenTrabajo"]+'</td>';
						
						var fecha1 = datos[contador]["fechaFacturacion"]["date"].substring(0,19).replace(" ","T");
						var dia = fecha1.substr(8,2);
						var mes = fecha1.substr(5,2);
						var anio = fecha1.substr(0,4);
						
						
						contenido+='<td value="" style="text-align: center">'+dia  + "-" + mes + "-" + anio+'</td>';
								
						contenido+='<td><input type="text" id="'+datos[contador]["id"]+'_concepto"  value="'+datos[contador]["concepto"]+'" style="width: 100%;text-align: center"></td>';
						contenido+='<td><input type="number" id="'+datos[contador]["id"]+'_unidades" value="'+datos[contador]["unidades"]+'" style="width: 100%;text-align: center"></td>';
						contenido+='<td><input type="number" id="'+datos[contador]["id"]+'_precio"  value="'+datos[contador]["precioUnitario"]+'" style="width: 100%;text-align: center"></td>';										
						
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarEspecial" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRegistroEspecial('+datos[contador]["id"]+')"></td>';
						
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarEspecial value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroEspecial('+datos[contador]["id"]+')"></td>';
						
												
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






function modificarRegistroEspecial(id) //js_facturasEspeciales
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarRegistroEspecial;
		peticionUnica1.open("POST","ajax/modificarRegistroEspecial.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarRegistroEspecial(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarRegistroEspecial(id)
{	
	var consulta = "accion=modificarRegistroEspecial";	
	consulta += "&id=" + id;
	consulta += "&concepto=" + document.getElementById(id+"_concepto").value ;
	consulta += "&unidades=" + document.getElementById(id+"_unidades").value;
	consulta += "&precioUnitario=" + document.getElementById(id+"_precio").value;
	return consulta;	
}

function mostrarModificarRegistroEspecial()
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
				cargarFacturasEspeciales();
			}
			
			peticionUnica1=null;
		}
	}						
}

function eliminarRegistroEspecial(id) //js_facturasEspeciales
{
	if (confirm("¿Eliminar registro: "+id+"?")) 
	{
		eliminarRegistroEspecial2(id);
	} 	
}


function eliminarRegistroEspecial2(id) //js_facturasEspeciales
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroEspecial2;
		peticionUnica1.open("POST","ajax/eliminarRegistroEspecial.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarRegistroEspecial2(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaEliminarRegistroEspecial2(id)
{	
	var consulta = "accion=eliminarRegistro";	
	consulta += "&id=" + id;	
	return consulta;	
}

function mostrarEliminarRegistroEspecial2()
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
				cargarFacturasEspeciales();
			}
			
			peticionUnica1=null;
		}
	}						
}


function informeGastosAdionales() 
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
		document.getElementById("formImprimirGastosAdicionales").submit();		
	}
}



