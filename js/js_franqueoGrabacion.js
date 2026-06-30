var peticionUnica1 = null;
var modo=0;
var fechaActual1="";
var permisosSoloLectura = null;





function cargarListadoNombreFranqueo(idInput) //js_pdaGestion
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoNombreFranqueo;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoNombreFranqueo();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoNombreFranqueo()
{	
	var consulta = "accion=cargarClientes";
	
	var campos = [
		'codigo',
		'nombre_franqueo'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	activo: 1,
		autorizadoFranqueo: 1

	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var order = [
    	{ campo: 'nombre_franqueo', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoNombreFranqueo()
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
				
				if (datos != "")
				{					
					if (datos.length<=0)
					{
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{
						var contador=0;
						var contenido="";
						
						if (idInputListado!="clienteAjusteModal")
						{
							contenido += '<option value="0"></option>';
						}
						while  (contador<datos.length)
						{
							contenido += '<option value="'+datos[contador]["codigo"]+'">'+datos[contador]["nombre_franqueo"]+'</option>';
							contador++;
						}
							
						document.getElementById(idInputListado).innerHTML = contenido;
						idInputListado = '';
					}					
				}
			}						
			
			peticionUnica1=null;			
		}
	}						
}



function rellenarCamposGrabacionFranqueo()//js_franqueoGrabacion	
{	
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarRellenarCamposGrabacionFranqueo;
		peticionUnica1.open("POST","ajax/cargarListadoTarifasProductos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string =consultaRellenarCamposGrabacionFranqueo();
		peticionUnica1.send(query_string);
	}	
}

function consultaRellenarCamposGrabacionFranqueo()
{	
	var consulta = "accion=cargarTarifasProductos";
	
	var campos = [
		'titulo'	
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));	
	

	var filtros = {
    	idProductoPadre: document.getElementById("tarifaProducto").value		
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 

	var order = [
    	{ campo: 'orden', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;

}

function mostrarRellenarCamposGrabacionFranqueo()
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
				
				if (datos != "")
				{
					
					var contenido = "";

					var contador = 0;
						
					contenido = "<tr><td><b>GRAMOS</b></td>";
					
					while  (contador<datos.length)
					{						
						contenido += '<td align="center"><b>'+datos[contador]["titulo"]+'</b></td>';
						contador++;
					}

					contenido+="</tr>";
					document.getElementById("grabacionInput").innerHTML = contenido;
					
					rellenarCamposGrabacionFranqueo2(); 
					
					numColumnas = contador;
				}
				else
				{
					document.getElementById("grabacionInput").innerHTML = "NO HAY TARIFAS";
				}
				
				rellenarHistoricoFranqueo(); 
				
				if (document.getElementById("tarifaProducto").value==3 || document.getElementById("tarifaProducto").value==4 || document.getElementById("tarifaProducto").value==17)
				{					
					document.getElementById("tipoCertificado").style.visibility = "visible";
					document.getElementById("tipoCertificado").style.display = "table-row-group";	
					document.getElementById("tipoCertificado").colSpan = "4";
				}
				else
				{
					document.getElementById("tipoCertificado").style.visibility = "hidden";
					document.getElementById("tipoCertificado").style.display = "none";
				}
				
				if (document.getElementById("tarifaProducto").value==5)
				{					
					document.getElementById("tipoNotificacion").style.visibility = "visible";
					document.getElementById("tipoNotificacion").style.display = "table-row-group";	
					document.getElementById("tipoNotificacion").colSpan = "4";
				}
				else
				{
					document.getElementById("tipoNotificacion").style.visibility = "hidden";
					document.getElementById("tipoNotificacion").style.display = "none";
				}
							
			}
			peticionUnica1=null;
			document.getElementById("numEnvios").value = 0;
		}
	}						
}

function franqueo_irAcampo_Ot()//js_franqueoGrabacion
{
	
	document.getElementById("ot").focus();
	document.getElementById("ot").select();
}

function grabarFranqueo()
{
	if(document.getElementById("numEnvios").value=="0")
	{
		alert("Introducir un envio");
	}
	else if (document.getElementById("listadoNombreFranqueo").value==""||document.getElementById("listadoNombreFranqueo").value=="0")
	{
		alert("Elegir un Cliente");
		document.getElementById("listadoNombreFranqueo").focus();
	}
	else
	{	
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarFranqueo;
			peticionUnica1.open("POST","ajax/insertarGrabacionFranqueo.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string =consultaGrabarFranqueo();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGrabarFranqueo()
{
	var consulta = "accion=grabarFranqueo";	

	var anadidos = "";
	if (document.getElementById("tarifaProducto").value==3 || document.getElementById("tarifaProducto").value==4 || document.getElementById("tarifaProducto").value==17 )
	{	
		if (document.getElementById("conAcuse").checked==true)
		{
			anadidos="AcuseRecibo";
		}
		else if (document.getElementById("conPEE").checked==true)
		{
			anadidos="PEE";
		}		
	}
	else if (document.getElementById("tarifaProducto").value==5)
	{
		if (document.getElementById("conPEEnot").checked==true)
		{
			anadidos="PEE";
		}
		else 
		{
			anadidos="AcuseRecibo";
		}
	}


	var datos = {
		producto: document.getElementById("tarifaProducto").value,
		idCliente: document.getElementById("listadoNombreFranqueo").value,
		fecha: document.getElementById("fecha").value,
		envios: document.getElementById("numEnvios").value,
		ot: document.getElementById("ot").value,
		otSidi: document.getElementById("otSidi").value,
		anadidos: anadidos,
		comprobado: 0,
		txt: 0
	}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));	

	var contenido2=""
	listadoTipos.forEach(function(valorId)
	{	
		if (document.getElementById(valorId).value>0 && document.getElementById(valorId).value!="")
		{		
			contenido2+=document.getElementById(valorId).id;
			contenido2+="|3432ji31|";
			contenido2+=document.getElementById(valorId).value;
			contenido2+="|193fea32|";
		}
	});
	
	contenido2 = contenido2.substring(0,contenido2.length-10);	
	consulta += "&contenido="+contenido2;

	return consulta;	
		
}

function mostrarGrabarFranqueo()
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
			 	//vaciar campos	
				
				listadoTipos.forEach(function(valorId)
				{					
					document.getElementById(valorId).value="";
				});

				document.getElementById("ot").value="";
				document.getElementById("numEnvios").value=0;
				document.getElementById("otSidi").value = "";

			}
			peticionUnica1=null;
			rellenarHistoricoFranqueo();
			
			document.getElementById("certificada").checked=true;
			document.getElementById("listadoNombreFranqueo").value = 0;
			
			document.getElementById("listadoNombreFranqueo").focus();
			
		}
	}	
}

function generacionTxtCorreosFranqueo_sidi(modo1)
{
	modo=modo1;
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{	
		if (document.getElementById("tarifaProducto").value==3 ||document.getElementById("tarifaProducto").value==5||document.getElementById("tarifaProducto").value==4) //3:certificados; 5: notificaciones; 4: certificadoUrgente
		{
			
			/*peticionUnica1.onreadystatechange = mostrarGeneracionTxtCorreosFranqueo_sidi;
			//peticionUnica1.open("POST","ajax/generacionTxtCorreosFranqueo_sidi_Cert_y_Not.php",false);
			peticionUnica1.open("POST","ajax/exportarCorreosranqueo_sidi_Cert_y_Not_Excel.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGeneracionTxtCorreosFranqueo_sidi();
			peticionUnica1.send(query_string);	*/	
			

			document.getElementById("exportarSidiExcel_idProducto").value = document.getElementById("tarifaProducto").value;
			document.getElementById("exportarSidiExcel_modo").value = modo
			document.getElementById("formExportarExcelSidi").submit();


	

			//generacionTxtCorreosFranqueo();//si se quita el comentario anterior, hay que comentar esta lina
		}
		else
		{
			peticionUnica1.onreadystatechange = mostrarGeneracionTxtCorreosFranqueo_sidi;
			peticionUnica1.open("POST","ajax/generacionTxtCorreosFranqueo_sidi.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGeneracionTxtCorreosFranqueo_sidi();
			peticionUnica1.send(query_string);	
		}
		
							
	}
}
function consultaGeneracionTxtCorreosFranqueo_sidi()
{	
	var consulta = "accion=generacionTxtCorreosFranqueo";

	var filtros = {
    	idProducto: document.getElementById("tarifaProducto").value,
		modo: modo
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;
}

function mostrarGeneracionTxtCorreosFranqueo_sidi()
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
				
						
				document.getElementById("generarAlbaranesCorreosFranqueo_sidi").setAttribute('href',datos.trim());
				document.getElementById("generarAlbaranesCorreosFranqueo_sidi").setAttribute('download',datos.trim().substring(datos.trim().lastIndexOf('/')+1));
				
				document.getElementById("generarAlbaranesCorreosFranqueo_sidi").click();
				
			}
			peticionUnica1=null;			
		}
	}						
}




function generacionTxtCorreosFranqueo()	//js_franqueoGrabacion			
{	
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGeneracionTxtCorreosFranqueo;
		peticionUnica1.open("POST","ajax/generacionTxtCorreosFranqueo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGeneracionTxtCorreosFranqueo();
		peticionUnica1.send(query_string);						
	}
}

function consultaGeneracionTxtCorreosFranqueo()
{	
	var consulta = "accion=generacionTxtCorreosFranqueo";	
	consulta+="&idProducto="+document.getElementById("tarifaProducto").value;
	consulta+="&modo="+modo;
	return consulta;	
}

function mostrarGeneracionTxtCorreosFranqueo()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.trim().substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else
			{				
				document.getElementById("generarAlbaranesCorreosFranqueo").setAttribute('href',peticionUnica1.responseText.trim());
				document.getElementById("generarAlbaranesCorreosFranqueo").setAttribute('download',peticionUnica1.responseText.trim().substring(peticionUnica1.responseText.trim().lastIndexOf('/')+1));
				
				document.getElementById("generarAlbaranesCorreosFranqueo").click();			
			}
			peticionUnica1=null;
		}
	}						
}

function verInformeFranqueoRegistro()//js_franqueoGrabacion
{
	if (document.getElementById("fecha").value=="" || document.getElementById("fecha").value==null || document.getElementById("fecha").value=="null")
	{
		alert("Introducir una fecha");
		document.getElementById("fecha").focus();
	}
	else
	{
		document.getElementById("imprimirInformeRegistroId").value = document.getElementById("tarifaProducto").value;	
		document.getElementById("imprimirInformeRegistroFecha").value = document.getElementById("fecha").value;
		document.getElementById("formImprimirInformeRegistro").submit();		
	}
}

function verInformeFranqueoCliente()//js_franqueoGrabacion
{
	if (document.getElementById("fecha").value=="" || document.getElementById("fecha").value==null || document.getElementById("fecha").value=="null")
	{
		alert("Introducir una fecha");
		document.getElementById("fecha").focus();
	}
	else
	{
		document.getElementById("imprimirInformeClienteProducto").value = document.getElementById("tarifaProducto").value;	
		document.getElementById("imprimirInformeClienteFecha").value = document.getElementById("fecha").value;
		document.getElementById("formImprimirInformeCliente").submit();		
	}
}

function verInformeFranqueoProducto()//js_franqueoGrabacion
{
	if (document.getElementById("fecha").value=="" || document.getElementById("fecha").value==null || document.getElementById("fecha").value=="null")
	{
		alert("Introducir una fecha");
		document.getElementById("fecha").focus();
	}
	else
	{
		document.getElementById("imprimirInformeProductoId").value = document.getElementById("tarifaProducto").value;	
		document.getElementById("imprimirInformeProductoFecha").value = document.getElementById("fecha").value;
		document.getElementById("formImprimirInformeProducto").submit();		
	}
}

function verInformeFranqueoResumen()//js_franqueoGrabacion
{
	if (document.getElementById("fecha").value=="" || document.getElementById("fecha").value==null || document.getElementById("fecha").value=="null")
	{
		alert("Introducir una fecha");
		document.getElementById("fecha").focus();
	}
	else
	{
		document.getElementById("imprimirInformeProducto").value = document.getElementById("tarifaProducto").value;	
		document.getElementById("imprimirInformeFecha").value = document.getElementById("fecha").value;
		document.getElementById("formImprimirInforme").submit();		
	}
}


function cargarTarifasProductos()
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTarifasProductos;
		peticionUnica1.open("POST","ajax/cargarListadoTarifasProductosPadre.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string =consultaCargarTarifasProductos();
		peticionUnica1.send(query_string);
	}	
}

function consultaCargarTarifasProductos()
{	
	var consulta = "accion=cargarTarifasProductos";
	
	var campos = [
		'id',
		'producto'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));	
	
	var order = [
    	{ campo: 'producto', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarTarifasProductos()
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
				
				if (datos != "")
				{					
					var contenido = "";

					var contador = 0;

					while  (contador<datos.length)
					{						
						contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["producto"]+'</option>';
						contador++;
					}

					document.getElementById("tarifaProducto").innerHTML = contenido;
				
					document.getElementById("tarifaProducto").value=1;
				}							
			}
			peticionUnica1=null;
		}
	}						
}


function rellenarHistoricoFranqueo() //js_franqueoGrabacion		
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarRellenarHistoricoFranqueo;
		peticionUnica1.open("POST","ajax/cargarFranqueo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string =consultaRellenarHistoricoFranqueo();
		peticionUnica1.send(query_string);
	}	
}

function consultaRellenarHistoricoFranqueo()
{	
	var consulta = "accion=cargarFranqueo";	

	var campos = [
		'id',
		'referencia',
		'idCliente',        
        'ot',
        'otSidi',
        'importe',
        'envios',
        'detalle',
        'anadidos',
        'fecha',
        'comprobado',
        'nombre_franqueo'     		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [
		'tabla2'
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var fecha = document.getElementById("fecha").value;

	if (fecha) {
		var partes = fecha.split('-');
		fecha = partes[2] + '-' + partes[1] + '-' + partes[0];
	}

	var filtros = {
    	producto: document.getElementById("tarifaProducto").value,
		fecha: fecha
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	consulta += "&origen=franqueoGrabacion";
	
	return consulta;

}

function mostrarRellenarHistoricoFranqueo()
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
				
				if (datos != "")
				{
					
					var contenido = "";

					contenido+='<tr>';
						
					contenido+='<td align="center"><b>Referencia</td>';
					//contenido+='<td align="center"><b>Fecha</td>';
					contenido+='<td align="center"><b>Cliente</td>';
					contenido+='<td align="center"><b>OT</td>';
					contenido+='<td align="center"><b>OT SIDI</td>';
					contenido+='<td align="center"><b>Importe</td>';
					contenido+='<td align="center"><b>Envios</td>';
					contenido+='<td align="center"><b>Detalle</td>';
					
					if (document.getElementById("tarifaProducto").value==3 || document.getElementById("tarifaProducto").value==4 || document.getElementById("tarifaProducto").value==5|| document.getElementById("tarifaProducto").value==17)
					{					
						contenido+='<td align="center"><b>Añadidos</b></td>';
					}

					if (!permisosSoloLectura)
					{
						contenido+='<td align="center"></td>';
					}
					
					
					if (document.getElementById("modificarFranqueo").value=="true" && !permisosSoloLectura)
					{
						contenido+='<td align="center"></td>';
					}
						
					contenido+='</tr>';
					
					
					var contador = 0;					
					
					while  (contador<datos.length) 
					{
						contenido+='<tr>';
						
						contenido+='<td><input id="'+datos[contador]["id"]+'_refRegistroF" value="'+datos[contador]["referencia"]+'" style="width: 100%;text-align: center" readonly></td>';
						//contenido+='<td><input value="'+datos[contador]["fecha"]["date"]+'" style="width: 100%;text-align: center"></td>';
						contenido+='<td><input value="'+datos[contador]["idCliente"]+' - '+datos[contador]["nombre_franqueo"]+'" style="width: 100%;text-align: left" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["ot"]+'" style="width: 100%;text-align: left" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["otSidi"]+'" style="width: 100%;text-align: left" readonly></td>';
						contenido+='<td><input value="'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2, style: 'currency', currency: 'EUR'})+'" style="width: 100%;text-align: right" readonly></td>';
								
						
						
						
						contenido+='<td><input value="'+datos[contador]["envios"]+'" style="width: 100%;text-align: center" readonly></td>';
						
						contenido+='<td><select style="width: 100%;">';
						
						var datosGrabados = datos[contador]["detalle"].split('|');
						
						datosGrabados.forEach(
							function(valorId)
							{
								contenido+='<option>'+valorId+'</option>';
							}
						);

						
						
						contenido+='</select></td>';
						if (document.getElementById("tarifaProducto").value==3 || document.getElementById("tarifaProducto").value==4  || document.getElementById("tarifaProducto").value==5|| document.getElementById("tarifaProducto").value==17)
						{
							contenido+='<td><input value="'+datos[contador]["anadidos"]+'" style="width: 100%;text-align: center" readonly></td>';
						}
						
						
						
						var anio = fechaActual1.split('/')[2];
						var anioSeleccionado = document.getElementById("fecha").value.substr(0,4);
						
						
						//if (anio==anioSeleccionado)
						{
							if(datos[contador]["comprobado"]=="0" || datos[contador]["comprobado"]=="null" || datos[contador]["comprobado"]==null)
							{

								if (document.getElementById("modificarFranqueo").value=="true" && !permisosSoloLectura)
								{
									var datoReferencia="";
									datoReferencia = "'"+datos[contador]["referencia"]+"'";
									//alert(datoReferencia);
									contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarRegistroF" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRegistroFranqueo('+String(datoReferencia)+')"></td>';						

								}

								if (!permisosSoloLectura)
								{
									contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarRegistroF" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroFranqueo('+datos[contador]["id"]+')"></td>';
								}
							}
							else
							{
								if (document.getElementById("modificarFranqueo").value=="true" && !permisosSoloLectura)
								{
									var datoReferencia="";
									datoReferencia = "'"+datos[contador]["referencia"]+"'";
									//alert(datoReferencia);
									contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarRegistroF" value="" src="imagenes/modificarRojo.png" style="width:20px;" onclick="modificarRegistroFranqueo('+String(datoReferencia)+')"></td>';

									contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarRegistroF" value="" src="imagenes/eliminarRojo.png" style="width:20px;" onclick="eliminarRegistroFranqueo('+datos[contador]["id"]+')"></td>';
								}							
							}
						}
						
						
											
						
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




function rellenarCamposGrabacionFranqueo2()	//js_franqueoGrabacion		
{
	if (document.getElementById('fecha').value)
	{
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarRellenarCamposGrabacionFranqueo2;
			peticionUnica1.open("POST","ajax/cargarTarifasFranqueo.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string =consultaRellenarCamposGrabacionFranqueo2();
			peticionUnica1.send(query_string);
		}	
	}
	else
	{		
	}	
	
}

function consultaRellenarCamposGrabacionFranqueo2()
{	
	var consulta = "accion=cargarTarifasFranqueo";		

	var campos = [
		'gramos',
		'tipos'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [
		'tabla2'
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));


	var filtros = {
    	idProductoPadre: document.getElementById("tarifaProducto").value		
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var order = [
    	{ campo: 'gramos', dir: 'ASC' },
		{ campo: 'orden_Producto', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	var date = new Date(document.getElementById('fecha').value);
	
	consulta += "&anioSeleccionado=" + date.getFullYear();
	
	return consulta;
	
}

function mostrarRellenarCamposGrabacionFranqueo2()
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
				
				if (datos != "")
				{
					
					var contenido = "";
					var contador = 0;
						
					//contenido = "<tr>";
					var gramosAnterior="asdfjakd32434234234";
					var primeraVez=true;
					
					
					listadoTipos=[];
					
					while  (contador<datos.length)
					{
						if (datos[contador]["gramos"]!= gramosAnterior)
						{
							if (primeraVez==false)
							{
								contenido+="</tr>";
							}
							else
							{
								primeraVez = false;
							}
							
							contenido+="<tr>"
							gramosAnterior = datos[contador]["gramos"];
							contenido += '<td align="right"><b>'+datos[contador]["gramos"]+'</b></td>';
						}
						
						contenido += '<td align="center"><input type="text"  id="'+datos[contador]["tipos"]+'" value="" onkeyup="gestionarValorGrabacionFranqueo('+contador+', event, id)" style="width: 100%;text-align: center;"></input></td>';
						 						
						listadoTipos.push(datos[contador]["tipos"]);
						
						contador++;
						
					}
				
					contenido+="</tr>";
					document.getElementById("grabacionInput").innerHTML += contenido;
										
				}
							
			}
			peticionUnica1=null;
		}
	}						
}

function franqueo_irAcampo_PrimerLocal(e,origen)//js_franqueoGrabacion
{
	if ((e.keyCode === 13  || e.keyCode === 9) && !e.shiftKey)
	{
		//if (origen=="ot")
		{
			//verOtSidi();
		}



		if (document.getElementById("tarifaProducto").value==3)//certificados
		{
			document.getElementById("EB1").focus();
		}
		else if (document.getElementById("tarifaProducto").value==4)//certificados urgente
		{
			document.getElementById("FB1").focus();
		}
		else if (document.getElementById("tarifaProducto").value==9)//libro
		{
			document.getElementById("P1").focus();
		}
		else if (document.getElementById("tarifaProducto").value==10)//libro extranjero
		{
			document.getElementById("LB_E1_100").focus();
		}
		else if (document.getElementById("tarifaProducto").value==5)//notificaciones
		{
			document.getElementById("NOT-M1").focus();
		}
		else if (document.getElementById("tarifaProducto").value==1)//ordinario
		{
			document.getElementById("AB1").focus();
		}
		else if (document.getElementById("tarifaProducto").value==11)//periodico
		{
			document.getElementById("PD1-AP").focus();
		}
		else if (document.getElementById("tarifaProducto").value==12)//periodico extranjero
		{
			document.getElementById("PD1-EXT-1").focus();
		}
		else if (document.getElementById("tarifaProducto").value==6)//publicorreo optimo
		{
			document.getElementById("PO1-AP").focus();
		}
		else if (document.getElementById("tarifaProducto").value==8)//publicorreo premium
		{
			document.getElementById("PP1-AP").focus();
		}
		else if (document.getElementById("tarifaProducto").value==7)//urgente
		{
			document.getElementById("CB1").focus();
		}		
		else if (document.getElementById("tarifaProducto").value==16)//urgente
		{
			document.getElementById("XA31").focus();
		}
		else if (document.getElementById("tarifaProducto").value==17)//urgente
		{
			document.getElementById("XC19").focus();
		}
	}
}

function gestionarValorGrabacionFranqueo(contador, e,id) //js_franqueoGrabacion
{	
	if (e.keyCode === 39 && !e.shiftKey)
	{
		posicionPosterior=contador+1;
		
		if (posicionPosterior==listadoTipos.length)
		{
			posicionPosterior=0;
		}
		document.getElementById(listadoTipos[posicionPosterior]).focus();
		document.getElementById(listadoTipos[posicionPosterior]).select();
		
	}
	else if (e.keyCode === 37 && !e.shiftKey)
	{
		posicionPosterior=contador-1;
		
		if (posicionPosterior<0)
		{
			posicionPosterior=listadoTipos.length-1;
		}
		document.getElementById(listadoTipos[posicionPosterior]).focus();
		document.getElementById(listadoTipos[posicionPosterior]).select();
	}
	
	else if ((e.keyCode === 13 && !e.shiftKey)|| e.keyCode == 40 && !e.shiftKey) 
	{
		/*if(e.keyCode == 40 && !e.shiftKey)
		{
			document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;  
		}*/
		
		var posicionPosterior =0;

		if (contador==listadoTipos.length-1)
		{
			posicionPosterior=0;
		}
		else
		{
			posicionPosterior = contador + numColumnas;
		}

		if (posicionPosterior>listadoTipos.length-1)
		{
			posicionPosterior = posicionPosterior-listadoTipos.length+1;
		}

		document.getElementById(listadoTipos[posicionPosterior]).focus();
		document.getElementById(listadoTipos[posicionPosterior]).select();
	}
	else if (e.keyCode == 38 && !e.shiftKey)
	{
		/*if (document.getElementById(id).value=="")
		{
			document.getElementById(id).value=0;
		}
		else
		{
			document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1; 
		}*/
		
		var posicionAnterior = 0;
		if (contador==0)
		{
			posicionAnterior=listadoTipos.length-1;
		}
		else
		{
			posicionAnterior = contador - numColumnas;
		}
		
		if (posicionAnterior<0)
		{
			posicionAnterior = posicionAnterior+listadoTipos.length-1;
		}
		
		document.getElementById(listadoTipos[posicionAnterior]).focus();
		document.getElementById(listadoTipos[posicionAnterior]).select();
		
	}
	else
	{
		var numeroEnvios = 0;
		listadoTipos.forEach(function(valorId){
			valor = document.getElementById(valorId).value;
			numeroEnvios += Number(valor);
		});

		document.getElementById("numEnvios").value=numeroEnvios;
	}	
}

function modificarRegistroFranqueo(referencia) //js_franqueoGrabacion
{
	//alert(referencia);
	//document.getElementById("referenciaAmodificar").value = referencia;
	document.getElementById("modReferencia").innerHTML = referencia;
	
	cargarDatosFranqueoTipoPorReferencia(referencia);
	
	//document.getElementById("modReferencia").innerHTML = "prueba";
	$("#modificarFranqueoModal").modal('show');
}





function cargarDatosFranqueoTipoPorReferencia(referencia) //js_franqueoGrabacion
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDatosFranqueoTipoPorReferencia;
		peticionUnica1.open("POST","ajax/cargarFranqueoTipo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDatosFranqueoTipoPorReferencia(referencia);
		peticionUnica1.send(query_string);
	}	
}

function consultaCargarDatosFranqueoTipoPorReferencia(referencia)
{	
	var consulta = "accion=cargarFranqueoTipo";	

	var campos = [
		'id',
		'unidades',
		'importe',
		'tarifa',
		'idTarifa',
		'idCliente',
		'fecha',
		'ot',
		'otSidi'
		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	referencia: referencia
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 

	var joins = [
		'tabla3'
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var order = [
    	{ campo: 'id', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	
	return consulta;	
}

function mostrarCargarDatosFranqueoTipoPorReferencia()
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
				
				var contenido="";
				var contador = 0;
				
				contenido += '<tr style="font-weight: bold">';				
				//contenido += '<td align="center">OT</td>';	
				contenido += '<td align="center">Tipo</td>	';
				contenido += '<td align="center">Unidades</td>';
				contenido += '<td align="center">Importe</td>';
				contenido += '<td align="center">Tarifa</td>';
				contenido += '<td align="center"></td>';
				contenido += '<td align="center"></td>';
				contenido += '</tr>';
				
				
				var totalImporte=0.00;
				unArray = [];
				while  (contador<datos.length)
				{
					
					contenido +='<tr  align="center" style="text-align: center;">';
					//contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_ot" name="'+datos[contador]["id"]+'_ot" style="width: 100%;" value="'+datos[contador]["ot"]+'"></input></td>';					
					
					//contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_tipo" name="'+datos[contador]["id"]+'_tipo" style="width: 100%;" value="'+datos[contador]["tipo"]+'"></input></td>';
					
					
					contenido += '<td><select id="'+datos[contador]["id"]+'_tipo" name="'+datos[contador]["id"]+'_tipo" onChange="modificarTarifaAlCambiarTipo('+datos[contador]["id"]+')"></select></td>';
					
					
					contenido += '<td><input type="number" id="'+datos[contador]["id"]+'_unidades" name="'+datos[contador]["id"]+'_unidades" style="width: 100%;" value="'+datos[contador]["unidades"]+'" onKeyUp="recalcularImporteFranqueoTipo('+datos[contador]["id"]+')"></input></td>';
					
					var importe = String(datos[contador]["importe"]);
					if (importe.substring(0, 1) == ".") {
						importe = "0" + importe;
					}

					contenido += '<td><input type="number" id="'+datos[contador]["id"]+'_importe" name="'+datos[contador]["id"]+'_importe" style="width: 100%;" value="'+importe+'" readonly></input></td>';
					
					var tarifa = String(datos[contador]["tarifa"]);
					if (tarifa.substring(0, 1) == ".") {
						tarifa = "0" + tarifa;
					}					

					contenido += '<td><input type="number" id="'+datos[contador]["id"]+'_tarifa" name="'+datos[contador]["id"]+'_tarifa" style="width: 100%;" value="'+tarifa+'" readonly></input></td>';
					
					if (!permisosSoloLectura)
					{
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/modificarNegro.png" style="width:15px;" onclick="modificarFranqueoTipo('+datos[contador]["id"]+')" ></td>';
					}
					
					
					//contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/eliminarNegro.png" style="width:15px;" onclick="eliminarTipoFranqueoTipo('+datos[contador]["id"]+')" ></td>';
										
					
					
					contenido += '</tr>';
					
					
					totalImporte += Number(datos[contador]["importe"]); 
					
					unArray.push(datos[contador]["id"]);
					contador++;

				}
				
				document.getElementById("idClienteModal").value = datos[0]["idCliente"];
				document.getElementById("fechaModal").value = datos[0]["fecha"]["date"].substring(0,10);
				document.getElementById("otModal").value = datos[0]["ot"];
				document.getElementById("otSidiModal").value = datos[0]["otSidi"];
				document.getElementById("totalModal").value = totalImporte.toFixed(2);
				document.getElementById("datosTipoFranqueo").innerHTML = contenido;		
				
				
				
							
				//PARA LISTAR LOS TIPOS
				contador = 0;

				while  (contador<datos.length)
				{ 					
					idInputListado = datos[contador]["id"]+'_tipo';
					cargarTiposFranqueoPorProducto();

					document.getElementById(datos[contador]["id"]+'_tipo').value = datos[contador]["idTarifa"];					

					idInputListado = "";
					
					contador++;
				}
			}
			peticionUnica1=null;
		}
	}						
}



function eliminarRegistroFranqueo(id)//js_franqueoGrabacion
{	
	if (confirm("¿Eliminar la referencia: "+document.getElementById(id+"_refRegistroF").value+"?")) 
	{
	 	eliminarRegistroFranqueo2(id);
	} 
	else 
	{
	  //no hace nada
	}
}

function eliminarRegistroFranqueo2(id)//js_franqueoGrabacion
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroFranqueo2;
		peticionUnica1.open("POST","ajax/eliminarRegistroFranqueo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarRegistroFranqueo2(id);
		peticionUnica1.send(query_string);
	}	
}

function consultaEliminarRegistroFranqueo2(id)
{	
	var consulta = "accion=eliminarRegistroFranqueo2";	

	var filtros = {
		
    	referencia: document.getElementById(id+"_refRegistroF").value		
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarEliminarRegistroFranqueo2()
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
				rellenarHistoricoFranqueo();
			}
			peticionUnica1=null;
		}
	}						
}

function cargarTiposFranqueoPorProducto()	//js_franqueoGrabacion			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTiposFranqueoPorProducto;
		peticionUnica1.open("POST","ajax/cargarTiposFranqueoPorProducto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTiposFranqueoPorProducto();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarTiposFranqueoPorProducto()
{	

	var consulta = "accion=cargarTiposFranqueoPorProducto";	

	var campos = [
		'id',
		'destino',
		'gramos',        
        'tipos',
        'orden',
        'titulo'        		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	
	var filtros = {
    	 idProductoPadre: document.getElementById("tarifaProducto").value		
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var order = [
    	{ campo: 'orden', dir: 'ASC' },
		{ campo: 'titulo', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarTiposFranqueoPorProducto()
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
				
				if (datos != "")
				{
					if (datos.length<=0)
					{						
					}
					else
					{	
						var contenido = "";

						var contador = 0;

						while  (contador<datos.length)
						{
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["titulo"]+'-'+datos[contador]["gramos"]+'-'+datos[contador]["tipos"]+'</option>';
							contador++;
						}

						document.getElementById(idInputListado).innerHTML = contenido;						
					}
				}				
			}
			peticionUnica1=null;
		}
	}						
}

function modificarFranqueoTipo(idInput)	//js_franqueoGrabacion			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarFranqueoTipo;
		peticionUnica1.open("POST","ajax/modificarFranqueoTipoPorId.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarFranqueoTipo(idInput);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarFranqueoTipo(idInput)
{	
	var consulta = "accion=modificarFranqueoTipo";	

	var sel = document.getElementById(idInput+"_tipo");
	
	consulta+="&tipo="+ sel.options[sel.selectedIndex].text;
	
	var datos = {
    	id: idInput,
		tipo: 	sel.options[sel.selectedIndex].text,
		unidades: document.getElementById(idInput+"_unidades").value,
		importe: document.getElementById(idInput+"_importe").value,
		ot: document.getElementById("otModal").value, //genericos
		otSidi: document.getElementById("otSidiModal").value,
		fecha: document.getElementById("fechaModal").value,
		idCliente: document.getElementById("idClienteModal").value,
		referencia: document.getElementById("modReferencia").innerHTML,
		total: document.getElementById("totalModal").value
	};

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarModificarFranqueoTipo()
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
				alert("Modificado");
			}
			rellenarHistoricoFranqueo();
			peticionUnica1=null;
		}
	}						
}

function recalcularImporteFranqueoTipo(idTipo)//js_franqueoGrabacion
{
	var unidades = 0;
	unidades = Number(document.getElementById(idTipo+"_unidades").value);
	
	

	var total=0;
	total = unidades * Number(document.getElementById(idTipo+"_tarifa").value);
	
	var total2 = String(total);

	if (total2.substring(0, 1) == ".") {
		total2 = "0" + total2;
	}
	
	document.getElementById(idTipo+"_importe").value = Number(total2).toFixed(2);
	
	recalcularImporteTotalFranqueoTipo();
	
}

function recalcularImporteTotalFranqueoTipo()//js_franqueoGrabacion
{
	var total=0;
	
	unArray.forEach(function(valorId){		
		valor = document.getElementById(valorId+'_unidades').value;
		total += Number(document.getElementById(valorId+'_unidades').value) * Number(document.getElementById(valorId+'_tarifa').value);
	});
	
	document.getElementById("totalModal").value = total.toFixed(2);	
}

function modificarTarifaAlCambiarTipo(idInput)				
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarTarifaAlCambiarTipo;
		peticionUnica1.open("POST","ajax/cargarTarifasFranqueo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarTarifaAlCambiarTipo(idInput);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarTarifaAlCambiarTipo(idInput)
{	

	var consulta = "accion=cargarTarifasFranqueo";

	var campos = [
		'importe_cantidadIndicada'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	

	var filtros = {
    	id: document.getElementById(idInput+"_tipo").value
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	valorNumero = idInput;

	
	
	consulta +="&anioSeleccionado="+ fechaActual1.split('/')[2];
	consulta +="&cantidad=1";
	
	return consulta;

}
/*var idInputListado="";
var valorTipo=0;
var valorDepartamento=0;*/

function mostrarModificarTarifaAlCambiarTipo()
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
				
				if (datos != "")
				{
					if (datos.length<=0)
					{						
					}
					else
					{	
						var contenido = "";

						var contador = 0;
						
						var importe = String(datos[0]["importe"]);
						if (importe.substring(0, 1) == ".") {
							importe = "0" + importe;
						}
						
						document.getElementById(valorNumero+"_tarifa").value = importe;
						recalcularImporteFranqueoTipo(valorNumero);
						valorNumero=0;
						
					}
				}				
			}
			peticionUnica1=null;
		}
	}						
}


function obtenerAnioActual()				
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarObtenerAnioActual;
		peticionUnica1.open("POST","ajax/mostrarFechaActual.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaObtenerAnioActual();
		peticionUnica1.send(query_string);						
	}
}

function consultaObtenerAnioActual()
{	
	var consulta = "accion=mostrarDatos";
	return consulta;	
}


function mostrarObtenerAnioActual()
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
				fechaActual1 = 	peticionUnica1.responseText;			
			}
			peticionUnica1=null;
		}
	}						
}



function verOtSidi()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerOtSidi;
		peticionUnica1.open("POST","ajax/mostrarPresupuestos2.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerOtSidi();
		peticionUnica1.send(query_string);						
	}
}

function consultaVerOtSidi()
{	
	var consulta = "accion=mostrarPresupuesto";
	consulta += "&condicion= where presupuesto='" + obtenerPrimeros7Digitos(document.getElementById("ot").value)  + "'" ;
	return consulta;	
}


function mostrarVerOtSidi()
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
				if (datos.length>=1)
				{  
					document.getElementById("otSidi").value = datos[0]["otSidi"];
				}
				else
				{
					document.getElementById("otSidi").value = "";
				}
				
						
			}
			peticionUnica1=null;
		}
	}						
}


function obtenerPrimeros7Digitos(cadena) {
	// Extraer todos los dígitos
	const digitos = cadena.match(/\d/g);
	
	// Verificar que haya al menos un dígito
	if (!digitos) return '';
  
	// Unirlos en una cadena y tomar los primeros 7
	return digitos.join('').slice(0, 7);
  }


