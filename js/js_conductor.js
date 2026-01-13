var peticionUnica1 = null;


function cargarDniRuta() //js_conductor
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDniRuta;
		peticionUnica1.open("POST","ajax/cargarDniRuta.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDniRuta();
		peticionUnica1.send(query_string);						
	}	
}

function consultaCargarDniRuta()
{	
	var consulta = "accion=cargarDniRuta";
	consulta += "&nombre="+document.getElementById("nombrePersonaRecogida1").value;
	return consulta;	
}

function mostrarCargarDniRuta()
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
				if (peticionUnica1.responseText!="")
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

					if (datos.length>0)
					{
						document.getElementById("dniPersonaRecogida1").value=datos[0]["dni"];
					}
					else
					{						
					}					
				}	
			}
			peticionUnica1=null;	
		}			
	}						
}

function guardarNombreRuta() //js_conductor
{		
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGuardarNombreRuta;
		peticionUnica1.open("POST","ajax/guardarHistorico.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGuardarNombreRuta();
		peticionUnica1.send(query_string);						
	}	
}

function consultaGuardarNombreRuta()
{	
	var consulta = "accion=guardarHistoricoRuta";
	consulta += "&nombre="+document.getElementById("nombrePersonaRecogida1").value;
	consulta += "&dni="+document.getElementById("dniPersonaRecogida1").value;
	//consulta += "&horaRuta="+document.getElementById("firmaHoraRuta1").innerHTML;
	//consulta += "&idCliente="+document.getElementById("firmaIdCliente1").innerHTML;
	consulta += "&horaRuta="+valorHoraRuta;
	consulta += "&idCliente="+valorNumero;
	consulta += "&firma=no";
	
	return consulta;	
}

function mostrarGuardarNombreRuta()
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
				
				$("#nombreYdni").modal('hide');
			}
			peticionUnica1=null;	
		}
	}						
}

function cargarLasRutasDelDia() //js_conductor
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarLasRutasDelDia;
		peticionUnica1.open("POST","ajax/cargarLasRutasDelDia.php",true);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarLasRutasDelDia();
		peticionUnica1.send(query_string);						
	}	
}

function consultaCargarLasRutasDelDia()
{	
	var consulta = "accion=cargarLasRutasDelDia";
	return consulta;	
}

function mostrarCargarLasRutasDelDia()
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
					
					//contenido +='<td id="'++'">'+datos[contador]["incidencia"]+'</td>';
					contenido +='<td>'+datos[contador]["hora"]["date"].substring(datos[contador]["hora"]["date"].indexOf("."),10)+'</td>';
					contenido +='<td onclick="datosClienteRuta(\''+datos[contador]["idCliente"]+'\',\''+datos[contador]["incidencia"]+'\',\''+datos[contador]["contacto"]+'\')">'+datos[contador]["nombre_franqueo"]+'</td>';
									
					
					contenido+='<td align="center"><a href="javascript:abrirDatosRutas(\''+datos[contador]["subcliente"]+'||--||'+datos[contador]["idCliente"]+'||--||'+datos[contador]["hora"]["date"].substr(11,8)+'\')" id="'+datos[contador]["idCliente"]+'_'+datos[contador]["hora"]["date"].substr(11,8)+'_datosRutas" value="" ><img id="'+datos[contador]["idCliente"]+'_'+datos[contador]["hora"]["date"].substr(11,8)+'_datosRutasImagen" src="imagenes/01_aspaRoja.png"  style="width:20px  !important;"></img> </a></td>';
					
					contenido+='<td align="center"><a href="javascript:abrirFimar(\''+datos[contador]["subcliente"]+'||--||'+datos[contador]["idCliente"]+'||--||'+datos[contador]["hora"]["date"].substr(11,8)+'\')" id="'+datos[contador]["idCliente"]+'_'+datos[contador]["hora"]["date"].substr(11,8)+'_firmaIdCliente" value="" ><img id="'+datos[contador]["idCliente"]+'_'+datos[contador]["hora"]["date"].substr(11,8)+'_firmaIdClienteImagen" src="imagenes/01_aspaRoja.png"  style="width:20px  !important;"></img> </a></td>';
					
					
					contenido +='</tr>';
					
					contador++;
					
				}
				
				
				document.getElementById("lasRutas").innerHTML = contenido;
				document.getElementById("conductorRuta").innerHTML = "&nbsp;&nbsp;&nbsp;-   Ruta: " +  datos[0]["ruta"];
				
				contador = 0;
				
				while  (contador<datos.length) 
				{
					verSiTieneFirma(datos[contador]["idCliente"],datos[contador]["hora"]["date"].substr(11,8));
					verSiTieneNombreYdniRuta(datos[contador]["idCliente"],datos[contador]["hora"]["date"].substr(11,8));
					contador++;
				}
						
			}
			peticionUnica1=null;	
		}			
	}						
}

function datosClienteRuta(idCliente,incidencia, contacto)
{

	cargarListadoDireccionesRutasClientes(idCliente);

	document.getElementById("clienteObservaciones").innerHTML = incidencia; 
	document.getElementById("clienteContactos").innerHTML = contacto; 
	

	$("#clienteDetallesModal").modal('show');
}

function verSiTieneFirma(idCliente, horaRuta) //js_conductor
{	
	valorNumero = idCliente;
	valorHoraRuta = horaRuta;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiTieneFirma;
		peticionUnica1.open("POST","ajax/verSiTieneFirma.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiTieneFirma(idCliente, horaRuta);
		peticionUnica1.send(query_string);						
	}	
}

function consultaVerSiTieneFirma(idCliente, horaRuta)
{	
	var consulta = "accion=verSiTieneFirma";
	consulta += "&idCliente="+idCliente;
	consulta += "&horaRuta="+horaRuta;
	return consulta;	
}

function mostrarVerSiTieneFirma()
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
				if (peticionUnica1.responseText==true)
				{
					//document.getElementById(valorNumero+'_firmaIdCliente').src = "imagenes/01_checkVerde.png";
					document.getElementById(valorNumero+'_'+valorHoraRuta+'_firmaIdClienteImagen').src = "imagenes/01_checkVerde.png";
					var a = document.getElementById(valorNumero+'_'+valorHoraRuta+'_firmaIdCliente');
					//a.href = "";
					//a.href = "javascript:abrirFirmar("+valorNumero+")"; 
					a.href = "javascript:verFirma("+valorNumero+")";
					
					//document.getElementById(valorNumero+'_firmaIdClienteImagen').href = "javascript:verFirma('"+valorNumero+"')";
					//document.getElementById(valorNumero+'_firmaIdCliente').onclick=function(){verFirma(valorNumero)};
					
				}
				else
				{
					//document.getElementById(valorNumero+'_firmaIdCliente').src = "imagenes/01_aspaRoja.png";
					document.getElementById(valorNumero+'_'+valorHoraRuta+'_firmaIdClienteImagen').src = "imagenes/01_aspaRoja.png";
				}
				valorNumero=0;
				valorHoraRuta='';
						
			}
			peticionUnica1=null;		
		}		
	}						
}


function verSiTieneNombreYdniRuta(idCliente, horaRuta) //js_conductor
{	
	valorNumero = idCliente;
	valorHoraRuta = horaRuta;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiTieneNombreYdniRuta;
		peticionUnica1.open("POST","ajax/verSiTieneNombreYdniRuta.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiTieneNombreYdniRuta(idCliente, horaRuta);
		peticionUnica1.send(query_string);						
	}	
}

function consultaVerSiTieneNombreYdniRuta(idCliente, horaRuta)
{	
	var consulta = "accion=verSiTieneNombreYdniRuta";
	consulta += "&idCliente="+idCliente;
	consulta += "&horaRuta="+horaRuta;
	return consulta;	
}

function mostrarVerSiTieneNombreYdniRuta()
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
				if (peticionUnica1.responseText==true)
				{
					//document.getElementById(valorNumero+'_firmaIdCliente').src = "imagenes/01_checkVerde.png";
					document.getElementById(valorNumero+'_'+valorHoraRuta+'_datosRutasImagen').src = "imagenes/01_checkVerde.png";
					/*var a = document.getElementById(valorNumero+'_firmaIdCliente');
					a.href = "javascript:verFirma("+valorNumero+")";*/
					
				}
				else
				{
					//document.getElementById(valorNumero+'_firmaIdCliente').src = "imagenes/01_aspaRoja.png";
					document.getElementById(valorNumero+'_'+valorHoraRuta+'_datosRutasImagen').src = "imagenes/01_aspaRoja.png";
				}
				valorNumero=0;
				valorHoraRuta='';
						
			}
			peticionUnica1=null;
		}	
	}						
}

function abrirDatosRutas(cliente) //js_conductor
{
	var nombreCliente = cliente.split("||--||")[0]
	var idCliente = cliente.split("||--||")[1];
	var horaRuta = cliente.split("||--||")[2];
	document.getElementById("firmaCliente1").innerHTML = nombreCliente;
	document.getElementById("firmaHoraRuta1").innerHTML = horaRuta;
	
	cargarPersonasRutasPorCliente(idCliente); //sirve para completar el autocompletar del campo nombre del contacto
	valorNumero = idCliente;
	valorHoraRuta = horaRuta;
	
	cargarValorNombreYdniRuta(idCliente,horaRuta);
	
	$("#nombreYdni").modal('show');
}

function cargarPersonasRutasPorCliente(idCliente) //js_conductor
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarPersonasRutasPorCliente;
		peticionUnica1.open("POST","ajax/cargarPersonasRutasPorCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPersonasRutasPorCliente(idCliente);
		peticionUnica1.send(query_string);						
	}	
}

function consultaCargarPersonasRutasPorCliente(idCliente)
{	
	var consulta = "accion=cargarPersonasRutasPorCliente";
	consulta += "&idCliente="+idCliente;
	return consulta;	
}

function mostrarCargarPersonasRutasPorCliente()
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
				document.getElementById("nombrePersonaRecogida1").value = "";
				document.getElementById("dniPersonaRecogida1").value = "";
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				if (datos.length<=0)
				{
					//alert("No hay ningun registro");
					//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
				}
				else
				{
					
					var contador = 0;
					
					unArray = [];
					var campo1="";
					while  (contador<datos.length)
					{						
						unArray.push(datos[contador]["nombrePersona"]);
						contador++;
					}	
					
					//campo1 = [{"label":"sergio", "value":"sergio1" },{"label":"beta", "value":"beta1" }];
					
					$('#nombrePersonaRecogida1').autocomplete({ 
							 source: unArray,							
							 change: function (event, ui) {  } });
					
					unArray = [];
				}						
			}
			peticionUnica1=null;
		}		
	}						
}

function cargarValorNombreYdniRuta(idCliente,horaRuta) //js_conductor
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarValorNombreYdniRuta;
		peticionUnica1.open("POST","ajax/cargarValorNombreYdniRuta.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarValorNombreYdniRuta(idCliente,horaRuta);
		peticionUnica1.send(query_string);						
	}	
}

function consultaCargarValorNombreYdniRuta(idCliente,horaRuta)
{	
	var consulta = "accion=cargarValorNombreYdniRuta";
	consulta += "&idCliente="+idCliente;
	consulta += "&horaRuta=" + horaRuta;
	return consulta;	
}

function mostrarCargarValorNombreYdniRuta()
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
				
				if (datos.length<=0)
				{
					document.getElementById("nombrePersonaRecogida1").value = "";
					document.getElementById("dniPersonaRecogida1").value = "";
				}
				else
				{					
					document.getElementById("nombrePersonaRecogida1").value = datos[0]["nombrePersona"];
					document.getElementById("dniPersonaRecogida1").value = datos[0]["dniPersona"];
				}						
			}
			peticionUnica1=null;
		}			
	}						
}

function abrirFimar(cliente) //js_conductor
{
	var nombreCliente = cliente.split("||--||")[0]
	var idCliente = cliente.split("||--||")[1];
	var horaRuta = cliente.split("||--||")[2];
	document.getElementById("firmaCliente").innerHTML = nombreCliente;
	document.getElementById("firmaIdCliente").innerHTML = idCliente;	
	
	document.getElementById("iframeFirma").src = "signature_pad-master/docs/index.html?idCliente1="+idCliente + "&horaRuta1="+horaRuta;
	//alert("signature_pad-master/docs/index.html?idCliente1="+idCliente);
	//alert(horaRuta);
	valorNumero = idCliente;
	valorHoraRuta = horaRuta;
	$("#firma").modal('show');
}



function cargarListadoDireccionesRutasClientes(idCliente)		
{
	
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoDireccionesRutasClientes;
		peticionUnica1.open("POST","ajax/cargarListadoDireccionesRutasClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoDireccionesRutasClientes(idCliente);
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarListadoDireccionesRutasClientes(idCliente)
{	
	var consulta = "accion=cargarListadoDireccionesRutasClientes";	
	consulta += "&idCliente="+idCliente;
	return consulta;	
}

function mostrarCargarListadoDireccionesRutasClientes()
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
				
				
				document.getElementById("clienteDetalles").innerHTML = datos[0]["nombre"];

				var laDireccion =  datos[0]["direccion"] + ", " + datos[0]["codigo_postal"] + ", " + datos[0]["localidad"]+ ", " + datos[0]["provincia"]+ ", " + datos[0]["pais"];
				
				document.getElementById("clienteDireccion").innerHTML = laDireccion;
				document.getElementById("clienteDireccionMapa").href ="http://maps.google.com?q=l" + laDireccion;
				
				
					
				
					
				
							
			}
			peticionUnica1=null;
		}
	}						
}

