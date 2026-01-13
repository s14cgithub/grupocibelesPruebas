var peticionUnica1 = null;
var busquedaFallida=false;
var imprimirPresu = false;
var sinDatos=false;
var permisosSoloLectura = null;

var alertarPresupuestoModificado = true;

function visualizarCamposPresuMensual()//js_presupuesto
{
	if (document.getElementById("presu_mensual").checked==true)
	{
		document.getElementById("labelNumPresuMensual").style.visibility = "visible";
		document.getElementById("labelNumPresuMensual").style.display = "table-cell";
		document.getElementById("tdNumPresuMensual").style.visibility = "visible";
		document.getElementById("tdNumPresuMensual").style.display = "table-cell";
		document.getElementById("tdNumPresuMensual").colSpan = "3";		
	}
	else
	{
		document.getElementById("labelNumPresuMensual").style.visibility = "hidden";
		document.getElementById("labelNumPresuMensual").style.display = "none";
		document.getElementById("tdNumPresuMensual").style.visibility = "hidden";
		document.getElementById("tdNumPresuMensual").style.display = "none";
	}
}

function desactivarImporteFranqueoPresupuesto()//js_presupuestosAlta
{
	
	var combo = document.getElementById("totalFraqueo");
	var selected = combo.options[combo.selectedIndex].text;
	
	if (selected =="NINGUNO")
	{
		document.getElementById("TotalFranqueoImporte").disabled = true;
		document.getElementById("TotalFranqueoImporte").readOnly = true;
	}
	else
	{
		document.getElementById("TotalFranqueoImporte").disabled = false;
		document.getElementById("TotalFranqueoImporte").readOnly = false;
	}
}


function modificarPresupuesto()
{
	if (document.getElementById("comercial").value =="")
	{
		alert("Elegir un comercial");
		document.getElementById("comercial").focus();
	}
	else if (document.getElementById("clientes").value =="")
	{
		alert("Escribir un cliente");
		document.getElementById("clientes").focus();
	}
	else if (document.getElementById("campanaPresu").value =="")
	{
		alert("Escribir una campaña");
		document.getElementById("campanaPresu").focus();
	}	
	else
	{		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarPresupuesto;
			peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarPresupuesto();
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaModificarPresupuesto()
{	
	var consulta = "accion=modificarRegistro";
	
	consulta+="&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	
	//consulta += "&comercial=" + document.getElementById("comercial").options[document.getElementById("comercial").selectedIndex].text;
	consulta +="&comercial=" +document.getElementById("comercial").value;
	
	if (document.getElementById("detallada").checked == true)
	{
		consulta += "&detallada=1";
	}
	else
	{
		consulta += "&detallada=0";
	}	
	/*var valorCliente = document.getElementById("clientes").value;
	valorCliente = valorCliente.replace('&','%26');
	consulta += "&cliente=" + valorCliente;*/
	
	consulta +="&cliente=" + reemplazarSimbolos2(document.getElementById("clientes").value);
	
	
	
	/*var auxiliar = document.getElementById("campanaPresu").value;
	
	while (auxiliar.includes('+'))
	{
		auxiliar = auxiliar.replace('+','%2B');
	}
	while (auxiliar.includes('"'))
	{
		auxiliar = auxiliar.replace('"',"'");
	}
	
	consulta += "&campania=" + auxiliar;*/
	
	consulta +="&campania=" + reemplazarSimbolos(document.getElementById("campanaPresu").value);
	consulta +="&campaniaObservacion=" + reemplazarSimbolos(document.getElementById("campanaObservacionPresu").value);
	
	
	
	var cantidad = document.getElementById("cantidadPresu").value;	
	cantidad = cantidad.replace(',','.');
	if (cantidad == "")
	{
		cantidad =0;
	}
	consulta += "&cantidad=" + cantidad;	
	//consulta += "&pedCliente=" + document.getElementById("pedidoClientePresu").value;	
	consulta +="&pedCliente=" + reemplazarSimbolos(document.getElementById("pedidoClientePresu").value);	
	//consulta += "&direccion=" + document.getElementById("direccionPresu").value;
	consulta +="&direccion=" + reemplazarSimbolos(document.getElementById("direccionPresu").value);
	
	
	consulta += "&cp=" + document.getElementById("cpPresu").value;	
	consulta += "&poblacion=" + document.getElementById("poblacionPresu").value;	
	//consulta += "&formaPago=" + document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;	
	consulta += "&formaPago=" + document.getElementById("formaPago").value;	
	//consulta += "&persona=" + document.getElementById("personaPresu").value;
	consulta +="&persona=" + reemplazarSimbolos(document.getElementById("personaPresu").value);
	//consulta += "&nota=" + document.getElementById("notasPresu").value;
	consulta +="&nota=" + reemplazarSimbolos(document.getElementById("notasPresu").value);
	
	if (document.getElementById("totalPresu").checked == true)
	{
		consulta += "&mtp=1";
	}
	else
	{
		consulta += "&mtp=0";
	}	
	
	
	consulta += "&mtf=" + document.getElementById("totalFraqueo").value;
	
	if (document.getElementById("TotalFranqueoImporte").value==null || document.getElementById("TotalFranqueoImporte").value=="null" || document.getElementById("TotalFranqueoImporte").value=="")
	{
		consulta += "&mtfImporte=0";
	}
	else
	{
		consulta += "&mtfImporte=" + document.getElementById("TotalFranqueoImporte").value;
	}
	
	consulta += "&origen="+document.getElementById("clienteOrigen").checked;

	consulta += "&trabajoIniciado="+document.getElementById("trabajoIniciado").checked;
	
	//alert(consulta);
	return consulta;	
}

function mostrarModificarPresupuesto()
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
				if (alertarPresupuestoModificado==true)
				{
					alert("Presupuesto Modificado");
				}
				else
				{
					alertarPresupuestoModificado=true;
				}
				
				document.getElementById("botonCrearPresupuesto").style.visibility = "hidden";
				document.getElementById("botonCrearPresupuesto").style.display = "none";
				
				document.getElementById("botonNuevoPresupuesto").style.visibility = "visible";
				document.getElementById("botonNuevoPresupuesto").style.display = "table-cell";	
				
				document.getElementById("botonModificarPresupuesto").style.visibility = "visible";
				document.getElementById("botonModificarPresupuesto").style.display = "table-cell";
				
				document.getElementById("botonModPedCliente").style.visibility = "hidden";
				document.getElementById("botonModPedCliente").style.display = "none";
				
				
			}
			peticionUnica1=null;
		}
	}						
}



function crearPresupuesto()//js_presupuestosAltas
{
	
	if (document.getElementById("comercial").value =="")
	{
		alert("Elegir un comercial");
		document.getElementById("comercial").focus();
	}
	else if (document.getElementById("clientes").value =="")
	{
		alert("Escribir un cliente");
		document.getElementById("clientes").focus();
	}
	else if (document.getElementById("campanaPresu").value =="")
	{
		alert("Escribir una campaña");
		document.getElementById("campanaPresu").focus();
	}
	
	else  if (document.getElementById("presu_mensual").checked==true)
	{
		if (document.getElementById("numPresuMensual").value=="")	
		{
			alert("Escribir un numero de presupuesto");
			document.getElementById("numPresuMensual").focus();
		}
		else if (document.getElementById("numPresuMensual").value.length!=7 || isNaN(document.getElementById("numPresuMensual").value))
		{
			alert("El numero de presupuesto no tiene la estructura correcta");
			
			
			document.getElementById("imagen_numPresuMensual").style.visibility = "visible";
			document.getElementById("imagen_numPresuMensual").style.display = "inline";
			
			document.getElementById("numPresuMensual").focus();
		}
		else
		{
			document.getElementById("imagen_numPresuMensual").style.visibility = "hidden";
			document.getElementById("imagen_numPresuMensual").style.display = "none";
			//se crear el presupuesto mensual
			crearPresupuestoMensual();
		}
	}	
	else
	{		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCrearPresupuesto;
			peticionUnica1.open("POST","ajax/presupuesto_proximoNumero.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCrearPresupuesto();
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaCrearPresupuesto()
{	
	var consulta = "accion=proximoNumero";
	
	//consulta += "&comercial=" + document.getElementById("comercial").options[document.getElementById("comercial").selectedIndex].text;
	consulta += "&comercial=" + document.getElementById("comercial").value
	if (document.getElementById("detallada").checked == true)
	{
		consulta += "&detallada=1";
	}
	else
	{
		consulta += "&detallada=0";
	}	
	
	var valorCliente = document.getElementById("clientes").value;
	valorCliente = valorCliente.replace('&','%26');
	consulta += "&cliente=" + valorCliente;
	
	
	
	
	consulta +="&campania=" + reemplazarSimbolos(document.getElementById("campanaPresu").value);
	consulta +="&campaniaObservacion=" + reemplazarSimbolos(document.getElementById("campanaObservacionPresu").value);
	
	
	
	var cantidad = document.getElementById("cantidadPresu").value;	
	cantidad = cantidad.replace(',','.');
	if (cantidad == "")
	{
		cantidad =0;
	}
	consulta += "&cantidad=" + cantidad;	
	
	
	//consulta += "&pedCliente=" + document.getElementById("pedidoClientePresu").value;	
	consulta +="&pedCliente=" + reemplazarSimbolos(document.getElementById("pedidoClientePresu").value);	
	
	//consulta += "&direccion=" + document.getElementById("direccionPresu").value;
	consulta +="&direccion=" + reemplazarSimbolos(document.getElementById("direccionPresu").value);
	
	consulta += "&cp=" + document.getElementById("cpPresu").value;	
	consulta += "&poblacion=" + document.getElementById("poblacionPresu").value;	
	//consulta += "&formaPago=" + document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;	
	consulta += "&formaPago=" + document.getElementById("formaPago").value;
	
	//consulta += "&persona=" + document.getElementById("personaPresu").value;
	consulta +="&persona=" + reemplazarSimbolos(document.getElementById("personaPresu").value);
	
	//consulta += "&nota=" + document.getElementById("notasPresu").value;
	consulta +="&nota=" + reemplazarSimbolos(document.getElementById("notasPresu").value);
	consulta +="&observaciones2=" + reemplazarSimbolos(document.getElementById("observaciones2Presu").value);
	
	
	if (document.getElementById("totalPresu").checked == true)
	{
		consulta += "&mtp=1";
	}
	else
	{
		consulta += "&mtp=0";
	}	
	
	
	consulta += "&mtf=" + document.getElementById("totalFraqueo").value;
	
	
	if (document.getElementById("TotalFranqueoImporte").value==null || document.getElementById("TotalFranqueoImporte").value=="null" || document.getElementById("TotalFranqueoImporte").value=="")
	{
		consulta += "&mtfImporte=0";
	}
	else
	{
		consulta += "&mtfImporte=" + document.getElementById("TotalFranqueoImporte").value;
	}
	
	
	if (document.getElementById("permiso_otBajadaAutomatico").value==0)
	{
		consulta += "&otBajada=0";
	}
	else
	{
		consulta += "&otBajada=1";
	}
	
	consulta += "&origen="+document.getElementById("clienteOrigen").checked;
	
	consulta += "&trabajoIniciado=" + document.getElementById("trabajoIniciado").checked;
	
	return consulta;	
}

function mostrarCrearPresupuesto()
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
				document.getElementById("numPresupuesto").innerHTML = peticionUnica1.responseText;
				document.getElementById("numPresupuesto").style.fontWeight = "bolder";
				document.getElementById("numPresupuesto").style.fontSize = "x-large";
				document.getElementById("numPresupuesto").style.textAlign = "center";
				
				
				document.getElementById("nombrePresupuesto").innerHTML = "PRESUPUESTO: "
				
				document.getElementById("botonVerDatosGenericosPresupuesto").innerHTML = '<button type="button" class="btn btn-info" onClick="verDatosGenericosPresupuesto()">VER DATOS GENERICOS</button>';
				
				
				document.getElementById("botonCrearPresupuesto").style.visibility = "hidden";
				document.getElementById("botonCrearPresupuesto").style.display = "none";
				
				document.getElementById("botonNuevoPresupuesto").style.visibility = "visible";
				document.getElementById("botonNuevoPresupuesto").style.display = "table-cell";	
				
				//document.getElementById("botonCopiarPresupuesto").style.visibility = "hidden";
				//document.getElementById("botonCopiarPresupuesto").style.display = "none";	
				
				document.getElementById("botonModificarPresupuesto").style.visibility = "visible";
				document.getElementById("botonModificarPresupuesto").style.display = "table-cell";
				
				document.getElementById("datosPresupuestoGenerico").style.visibility = "hidden";
				document.getElementById("datosPresupuestoGenerico").style.display = "none";
				
				document.getElementById("anadirDetallesPresupuesto").style.visibility = "visible";
				document.getElementById("anadirDetallesPresupuesto").style.display = "table-row-group";
				document.getElementById("anadirDetallesPresupuesto").colSpan = "7";
				
				document.getElementById("botonVersionPresupuesto").style.visibility = "visible";
				document.getElementById("botonVersionPresupuesto").style.display = "table-cell";
				
				document.getElementById("botonProvisionFondo").style.visibility = "visible";
				document.getElementById("botonProvisionFondo").style.display = "table-cell";
				
				document.getElementById("botonModPedCliente").style.visibility = "hidden";
				document.getElementById("botonModPedCliente").style.display = "none";
				
				
				//presupuestoActivarOtBajada();
				
				document.getElementById("numPresuBuscar").value = document.getElementById("numPresupuesto").innerHTML;
				buscarPresupuesto();
				document.getElementById("numPresuBuscar").value="";
				
			}
			peticionUnica1=null;
		}
	}						
}

function gestionarPresuBuscarModal()//js_presupuestosAlta
{
	$("#buscarModal").modal('show');	
	/*setTimeout(function(){ document.getElementById("numPresuBuscar").focus(); }, 1000);
	document.getElementById("numPresuBuscar").select();	*/	

}


function crearNuevaVersion()//js_presupuestosAlta
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearNuevaVersion;
		peticionUnica1.open("POST","ajax/aumentarLetra.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearNuevaVersion();
		peticionUnica1.send(query_string);
	}
	
}

function consultaCrearNuevaVersion()
{	
	var consulta = "accion=aumentarLetra";
	
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;	
	
	return consulta;	
}

function mostrarCrearNuevaVersion()
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
				//alert(peticion25.responseText);
				//cargarDetallesPresupuesto();
				document.getElementById("letraPresupuesto").innerHTML = " - " + peticionUnica1.responseText;
				document.getElementById("letraPresupuesto").style.fontWeight = "bolder";
				document.getElementById("letraPresupuesto").style.fontSize = "x-large";
				document.getElementById("letraPresupuesto").style.textAlign = "center";				
				
				cambiarValorPdfGenerado(false);
				document.getElementById("numPresuBuscar").value = document.getElementById("numPresupuesto").innerHTML;
				buscarPresupuesto();
				document.getElementById("numPresuBuscar").value="";
				
			}
			peticionUnica1=null;
		}
	}						
}


function previsualizarPresupuesto()//js_presupuestosAlta
{	
	document.getElementById("previsualizarNumPresupuesto").value = document.getElementById("numPresupuesto").innerHTML;	
	document.getElementById("formPrevisualizar").submit();	
}

function cargarListadoParaVerPresupuesto()//js_presupuestosAlta
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoParaVerPresupuesto;
		peticionUnica1.open("POST","ajax/cargarListadoParaVerPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoParaVerPresupuesto();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarListadoParaVerPresupuesto()
{	
	var consulta = "accion=cargarListadoParaVerPresupuesto";
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;	
	return consulta;	
}

function mostrarCargarListadoParaVerPresupuesto()
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
					if (datos.length<=0)
					{						
					}
					else
					{
						var contenido = "<table>";

						var contador = 0;

						while  (contador<datos.length)
						{
							contenido += '<tr><td><label> <a href="visualizarPresupuesto.php?nombre='+datos[contador].substr(datos[contador].lastIndexOf('/')+1)+'"  target="_blank">'+datos[contador].substr(datos[contador].lastIndexOf('/')+1)+'</a></label></td></tr>';				
													
							contador++;
						}
						contenido += "</table>";						
					}
				}			
				document.getElementById("listadoArchivosPresupuestoVer").innerHTML= contenido;
			}			
			peticionUnica1 = null;
		}
	}						
}





function modificarPedidoYNotasClienteDesdePresupuesto()//js_presupuestosAlta
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarPedidoYNotasClienteDesdePresupuesto;
		peticionUnica1.open("POST","ajax/modificarPedidoYNotasClienteDesdePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarPedidoYNotasClienteDesdePresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarPedidoYNotasClienteDesdePresupuesto()
{	
	var consulta = "accion=modPedido";
	consulta += "&pedido=" + document.getElementById("pedidoClientePresu").value;
	consulta += "&notas=" + document.getElementById("notasPresu").value;
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;
	
	
	return consulta;	
}

function mostrarModificarPedidoYNotasClienteDesdePresupuesto()
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
			peticionUnica1 = null;
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
	consulta += "&idTipoProceso=" + document.getElementById("tipoProceso").value;
	consulta += "&idDepartamento=" + document.getElementById("departamentoProceso").value;
	
	return consulta;	
}

function mostrarCargarSubProcesos()
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
					document.getElementById("proceso").innerHTML = "";
				}
				
				if (datos != "")
				{
					
					if (datos.length<=0)
					{
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{							
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
							
						document.getElementById("proceso").innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById("proceso").innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}						
}



function anadirDetallePresupuesto()//js_presupuestosAlta
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePresupuesto;
		peticionUnica1.open("POST","ajax/anadirDetallePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePresupuesto()
{	
	var consulta = "accion=anadirDetalle";	
	
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML; 
	consulta += "&letra="+document.getElementById("letraPresupuesto").innerHTML;
	consulta += "&tipoProceso=" + document.getElementById("tipoProceso").value;
	consulta += "&idDepartamento=" + document.getElementById("departamentoProceso").value;
	consulta += "&proceso=" + document.getElementById("proceso").value;
	
	consulta +="&descripcion=" + reemplazarSimbolos(document.getElementById("descripcionDetalle").value);

	consulta +="&nota=" + reemplazarSimbolos(document.getElementById("notaDetalle").value);
	
	var unidad = document.getElementById("unidadesDetalle").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}
	consulta += "&unidad=" + unidad;
	
	var precio = document.getElementById("precioDetalle").value;	
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}
	
	consulta += "&precio=" + precio;
	
	var orden = document.getElementById("ordenDetalle").value;
	if (orden=="")
	{
		orden = 999;
	}
	
	consulta += "&orden=" + orden;
	
	consulta += "&notaProdAdmon=" + document.getElementById("notaDetalleAdmonProd").value;
	consulta += "&exentoIVA=" + document.getElementById("exentoIVA").checked;


	if (document.getElementById("tamanioDetalle").value==4)
	{
		document.getElementById("tipoDetalle").value=6;
		document.getElementById("acabadoDetalle").value=1;
		document.getElementById("gramajeDetalle").value=13;
	}


	consulta += "&idMaterialPapel_tamano=" + document.getElementById("tamanioDetalle").value;
	consulta += "&idMaterialPapel_tipo=" + document.getElementById("tipoDetalle").value;
	consulta += "&idMaterialPapel_acabado=" + document.getElementById("acabadoDetalle").value;
	consulta += "&idMaterialPapel_gramaje=" + document.getElementById("gramajeDetalle").value;

	consulta += "&idImpresoraDetalles=" + document.getElementById("impresoraDetalle").value;
	consulta += "&numCaras=" + document.getElementById("numeroCarasDetalles").value;

	consulta += "&idMaterialPapel_tamanoFinal=" + document.getElementById("tamanioFinalDetalle").value;
	
	
	var elPeso = 0;
	if (document.getElementById("pesoDetalle").value!="")
	{
		elPeso = document.getElementById("pesoDetalle").value;
	}
	
	consulta += "&peso=" + elPeso;

	consulta += "&idGFConcepto=" + document.getElementById("gf_concepto").value;	


	var elMetroCuadrado = 0;
	if (document.getElementById("gf_metrosCuadrados").value!="")
	{
		elMetroCuadrado = document.getElementById("gf_metrosCuadrados").value;
	}

	consulta += "&idGFMetrosCuadrados=" + elMetroCuadrado;

	return consulta;	
}

function mostrarAnadirDetallePresupuesto()
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
				cargarDetallesPresupuesto();
				
				//se limpiar los campos de añadir detalle
				document.getElementById("descripcionDetalle").value = "";
				document.getElementById("notaDetalle").value = "";
				document.getElementById("unidadesDetalle").value = "";
				document.getElementById("precioDetalle").value = "";
				document.getElementById("ordenDetalle").value = "";
				document.getElementById("notaDetalleAdmonProd").value = "";
				
				
			}
			peticionUnica1=null;
		}
	}						
}

function visualizarCamposPresuMensualModal()//js_presupuestosAlta
{
	if (document.getElementById("presu_mensualModal").checked==true)
	{
		document.getElementById("copiarPresuNoMensualModal").style.visibility = "hidden";
		document.getElementById("copiarPresuNoMensualModal").style.display = "none";
		document.getElementById("copiarPresuMensualModal").style.visibility = "visible";
		document.getElementById("copiarPresuMensualModal").style.display = "table-cell";		
	}
	else
	{		
		document.getElementById("copiarPresuNoMensualModal").style.visibility = "visible";
		document.getElementById("copiarPresuNoMensualModal").style.display = "table-cell";
		document.getElementById("copiarPresuMensualModal").style.visibility = "hidden";
		document.getElementById("copiarPresuMensualModal").style.display = "none";
	}
}



function copiarPresupuesto()//js_presupuestosAlta
{
	document.getElementById("numPresuBuscar").value = document.getElementById("numPresuCopiar").value;
	buscarPresupuesto(); 
	
	if (busquedaFallida==false)
	{
		crearPresupuesto();
	
		//document.getElementById("clientes").focus(); 
		

		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCopiarPresupuesto;
			peticionUnica1.open("POST","ajax/copiarDetallesPresupuestos.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCopiarPresupuesto();
			peticionUnica1.send(query_string);
		}
	}
	else
	{
		busquedaFallida=false;
	}
		
	
	
	
}

function consultaCopiarPresupuesto()
{	
	var consulta = "accion=copiarDetalle";
	consulta += "&viejoPresupuesto="+document.getElementById("numPresuCopiar").value;
	consulta += "&nuevoPresupuesto="+document.getElementById("numPresupuesto").innerHTML;	
	
	/*if (document.getElementById("permiso_otBajadaAutomatico").value==0)
	{
		consulta += "&otBajada=0";
	}
	else
	{
		consulta += "&otBajada=1";
	}*/	
		
	return consulta;	
}

function mostrarCopiarPresupuesto()
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
				/*cargarDetallesPresupuesto();
				document.getElementById("letraPresupuesto").innerHTML = "";*/
				
				
				document.getElementById("numPresuBuscar").value = document.getElementById("numPresupuesto").innerHTML;
				
				//buscarPresupuesto();
				cargarDatosPresupuesto();	
				alertarPresupuestoModificado=false;						
				modificarPresupuesto();

				window.location.href = "presupuestos_alta.php?presupuesto="+document.getElementById("numPresupuesto").innerHTML;
				
				document.getElementById("numPresuBuscar").value="";

			}
			peticionUnica1=null;
			
		}
	}						
}

function copiarPresupuestoMensuales()//js_presupuestosAlta
{
	if (document.getElementById("numPresuInicioMensualModal").value=="")
	{
		document.getElementById("numPresuInicioMensualModal").focus();
		alert("Introducir el primer Presupuesto que se quiere copiar");		
	}
	else if (document.getElementById("numPresuInicioMensualModal").value.length!=7 || isNaN(document.getElementById("numPresuInicioMensualModal").value))
	{
		document.getElementById("numPresuInicioMensualModal").focus();
		alert("El primer presupuesto no tiene la estructura correcta");		
	}
	else if (document.getElementById("numPresuUltimoMensualModal").value=="")
	{
		document.getElementById("numPresuUltimoMensualModal").focus();
		alert("Introducir el ultimo Presupuesto que se quiere copiar");		
	}
	else if (document.getElementById("numPresuUltimoMensualModal").value.length!=7 || isNaN(document.getElementById("numPresuUltimoMensualModal").value))
	{
		document.getElementById("numPresuUltimoMensualModal").focus();
		alert("El ultimo presupuesto no tiene la estructura correcta");		
	}
	else if (parseInt(document.getElementById("numPresuInicioMensualModal").value) > parseInt(document.getElementById("numPresuUltimoMensualModal").value))
	{
		alert("El valor del primer presupuesto tiene que ser anterior al ultimoPresupuesto");	
	}
	else
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCopiarPresupuestoMensuales;
			peticionUnica1.open("POST","ajax/copiarPresupuestosMensuales.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCopiarPresupuestoMensuales();
			peticionUnica1.send(query_string);						
		}
	}
	
}

function consultaCopiarPresupuestoMensuales()
{	
	var consulta = "accion=copiarPresupuesto";
	consulta += "&primerPresupuesto="+document.getElementById("numPresuInicioMensualModal").value;
	consulta += "&ultimoPresupuesto="+document.getElementById("numPresuUltimoMensualModal").value;
	consulta += "&ano="+document.getElementById("anioCopiaModal").value;
	consulta += "&mes="+document.getElementById("mesCopiaModal").value;
		
	return consulta;
}

function mostrarCopiarPresupuestoMensuales()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			/*else if (peticionUnica1.responseText.substr(0,6)=="Error2")
			{
				alert(peticionUnica1.responseText+"\nSe han copiado los presupuestos");
				
			}	*/		 
			else
			{				
				//alert("Se han copiado los presupuestos");				
					alert(peticionUnica1.responseText+"\nSe han copiado los presupuestos");
			}
			
			peticionUnica1 = null;
		}
	}					
}


function buscarPresupuesto()//js_presupuestosAlta
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarBuscarPresupuesto;
		peticionUnica1.open("POST","ajax/buscarPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBuscarPresupuesto();
		peticionUnica1.send(query_string);
	}
	
}

function consultaBuscarPresupuesto()
{	
	var consulta = "accion=buscarPresupuesto";
	
	consulta+="&numPresuCopiar="+document.getElementById("numPresuBuscar").value;
	
	return consulta;	
}

function mostrarBuscarPresupuesto()
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
					alert("No existe ese presupuesto");
					busquedaFallida=true;
				}
				
				if (datos != "")
				{				
					document.getElementById("comercial").value = datos[0]["idComercial"];
					
					if (datos[0]["detallada"]=="1")
					{
						document.getElementById("detallada").checked = true;
					}
					else
					{
						document.getElementById("detallada").checked = false;
					}
					
					
					document.getElementById("fechaCreacion").value = datos[0]["fecha"]["date"].substring(0,10);
					document.getElementById("clientes").value = datos[0]["cliente"];
					document.getElementById("campanaPresu").value = datos[0]["campana"];
					
					/*if (datos[0]["cantidad"].startsWith('.') || datos[0]["cantidad"]=="" )
					{
						document.getElementById("cantidadPresu").value = "0"+datos[0]["cantidad"];
					}
					else
					{*/
						document.getElementById("cantidadPresu").value = datos[0]["cantidad"];
					//}
					
					
					document.getElementById("pedidoClientePresu").value = datos[0]["pedcli"];
					document.getElementById("direccionPresu").value = datos[0]["direccion"];
					document.getElementById("cpPresu").value = datos[0]["cp"];
					document.getElementById("poblacionPresu").value = datos[0]["poblacion"];
					document.getElementById("formaPago").value = datos[0]["idFormaPago"];
					document.getElementById("personaPresu").value = datos[0]["persona"];
					document.getElementById("notasPresu").value = datos[0]["notaCibeles"];
					
					document.getElementById("clienteOrigen").checked = datos[0]["clayma"];

					document.getElementById("trabajoIniciado").checked = datos[0]["trabajoIniciado"];
					
					
					
					if (datos[0]["idVisualizarTotalPresu"]=="1")
					{
						document.getElementById("totalPresu").checked = true;
					}
					else
					{
						document.getElementById("totalPresu").checked = false;
					}
					
					
					document.getElementById("totalFraqueo").value = datos[0]["idVisualizarTotalFranqueo"];
					
					
					if (datos[0]["importeFranqueo"].startsWith('.') || datos[0]["importeFranqueo"]=="" )
					{
						document.getElementById("TotalFranqueoImporte").value = "0"+datos[0]["importeFranqueo"];
					}
					else
					{
						document.getElementById("TotalFranqueoImporte").value = datos[0]["importeFranqueo"];
					}
					
					
					document.getElementById("observaciones2Presu").value = datos[0]["observaciones2"];
					
					document.getElementById("numPresupuesto").innerHTML = datos[0]["presupuesto"];
					document.getElementById("numPresupuesto").style.fontWeight = "bolder";
					document.getElementById("numPresupuesto").style.fontSize = "x-large";
					document.getElementById("numPresupuesto").style.textAlign = "center";
					
					var letra=" - ";
					if (datos[0]["letra"]==null ||datos[0]["letra"]=="null" || datos[0]["letra"]=="" )
					{
						letra="";
					}
					else
					{
						letra+=datos[0]["letra"];
					}
					
					document.getElementById("letraPresupuesto").innerHTML = letra;
					document.getElementById("letraPresupuesto").style.fontWeight = "bolder";
					document.getElementById("letraPresupuesto").style.fontSize = "x-large";
					document.getElementById("letraPresupuesto").style.textAlign = "center";
					
					
					document.getElementById("nombrePresupuesto").innerHTML = "PRESUPUESTO: "
				
					document.getElementById("botonVerDatosGenericosPresupuesto").innerHTML = '<button type="button" class="btn btn-info" onClick="verDatosGenericosPresupuesto()">VER DATOS GENERICOS</button>';
					
					if (!permisosSoloLectura)
					{
						document.getElementById("botonCrearPresupuesto").style.visibility = "hidden";
						document.getElementById("botonCrearPresupuesto").style.display = "none";
						
						document.getElementById("botonNuevoPresupuesto").style.visibility = "visible";
						document.getElementById("botonNuevoPresupuesto").style.display = "table-cell";	
	
						//document.getElementById("botonCopiarPresupuesto").style.visibility = "hidden";
						//document.getElementById("botonCopiarPresupuesto").style.display = "none";	

						document.getElementById("botonVersionPresupuesto").style.visibility = "visible";
						document.getElementById("botonVersionPresupuesto").style.display = "table-cell";
					}
					
					

					

					document.getElementById("datosPresupuestoGenerico").style.visibility = "hidden";
					document.getElementById("datosPresupuestoGenerico").style.display = "none";

					
					
					
					
					document.getElementById("botonProvisionFondo").style.visibility = "visible";
					document.getElementById("botonProvisionFondo").style.display = "table-cell";
					
					
					var facturado=datos[0]["numFactura"];
					var facturado2=datos[0]["numFactura2"];
					//facturado = null;
					//facturado2 = null;
					if (facturado!=null || facturado2!=null)
					{
						if (!permisosSoloLectura)
						{
							document.getElementById("botonModificarPresupuesto").style.visibility = "hidden";
							document.getElementById("botonModificarPresupuesto").style.display = "none";
							
							document.getElementById("botonVersionPresupuesto").style.visibility = "hidden";
							document.getElementById("botonVersionPresupuesto").style.display = "none";
							
							document.getElementById("botonModPedCliente").style.visibility = "hidden";
							document.getElementById("botonModPedCliente").style.display = "none";
						}
						
						
						document.getElementById("botonProvisionFondo").style.visibility = "hidden";
						document.getElementById("botonProvisionFondo").style.display = "none";
						
						pdfPresupuestoGenerado=datos[0]["pdfGenerado"];
						if (pdfPresupuestoGenerado=="1")
						{
							document.getElementById("botonVerPresupuesto").style.visibility = "visible";
							document.getElementById("botonVerPresupuesto").style.display = "table-cell";
						}
						else
						{
							document.getElementById("botonVerPresupuesto").style.visibility = "hidden";
							document.getElementById("botonVerPresupuesto").style.display = "none";
						}
					}
					else
					{
						pdfPresupuestoGenerado=datos[0]["pdfGenerado"];
						//pdfPresupuestoGenerado=0; 
						if (pdfPresupuestoGenerado=="1")
						{
							if (!permisosSoloLectura)
							{
								document.getElementById("botonImprimirPresupuesto").style.visibility = "hidden";
								document.getElementById("botonImprimirPresupuesto").style.display = "none";	

								document.getElementById("botonModificarPresupuesto").style.visibility = "hidden";
								document.getElementById("botonModificarPresupuesto").style.display = "none";

								document.getElementById("anadirDetallesPresupuesto").style.visibility = "hidden";
								document.getElementById("anadirDetallesPresupuesto").style.display = "none";

								document.getElementById("botonPrevisualizarPresupuesto").style.visibility = "hidden";
								document.getElementById("botonPrevisualizarPresupuesto").style.display = "none";

								document.getElementById("botonModPedCliente").style.visibility = "visible";
								document.getElementById("botonModPedCliente").style.display = "table-cell";
							}

							

							document.getElementById("botonVerPresupuesto").style.visibility = "visible";
							document.getElementById("botonVerPresupuesto").style.display = "table-cell";

							





						}
						else
						{
							if (!permisosSoloLectura)
							{
								document.getElementById("botonImprimirPresupuesto").style.visibility = "visible";
								document.getElementById("botonImprimirPresupuesto").style.display = "table-cell";

								document.getElementById("botonPrevisualizarPresupuesto").style.visibility = "visible";
								document.getElementById("botonPrevisualizarPresupuesto").style.display = "table-cell";

								document.getElementById("botonModificarPresupuesto").style.visibility = "visible";
								document.getElementById("botonModificarPresupuesto").style.display = "table-cell";


								document.getElementById("anadirDetallesPresupuesto").style.visibility = "visible";
								document.getElementById("anadirDetallesPresupuesto").style.display = "table-row-group";
								document.getElementById("anadirDetallesPresupuesto").colSpan = "7";

								document.getElementById("botonModPedCliente").style.visibility = "hidden";
								document.getElementById("botonModPedCliente").style.display = "none";
							}

							

							document.getElementById("botonVerPresupuesto").style.visibility = "hidden";
							document.getElementById("botonVerPresupuesto").style.display = "none";

							

						}
						
					}
							//document.getElementById("botonImprimirPresupuesto").style.visibility = "visible";
							//document.getElementById("botonImprimirPresupuesto").style.display = "table-cell";
					
							
					//botonNuevaVersiongetion();//esto es algo puntual		

					actualizarHistoricoPFpresupuesto();
					cargarDetallesPresupuesto();
					desactivarImporteFranqueoPresupuesto();
				}
				else
				{
					alert("No existe ese presupuesto");
					busquedaFallida=true;
				}
			}
			peticionUnica1=null;
			
		}
	}						
}


function crearNuevoProceso()//js_presupuestosAlta
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearNuevoProceso;
		peticionUnica1.open("POST","ajax/crearNuevoProceso.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearNuevoProceso();
		peticionUnica1.send(query_string);
	}
	
}

function consultaCrearNuevoProceso()
{	
	var consulta = "accion=crearProceso";
	
	consulta+="&idTipo="+document.getElementById("tipoProceso").value;
	consulta+="&idDepartamento="+document.getElementById("departamentoProceso").value;
	consulta+="&proceso=" + document.getElementById("nuevoProceso").value;
	consulta+="&descripcion=" + document.getElementById("nuevaDescripcion").value;	
	
	return consulta;	
}

function mostrarCrearNuevoProceso()
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
				/*document.getElementById("filaProcesoGuardados").style.visibility = "visible";
				document.getElementById("filaProcesoGuardados").style.display = "table-row";				

				document.getElementById("filaProcesoNuevo").style.visibility = "hidden";
				document.getElementById("filaProcesoNuevo").style.display = "none";

				document.getElementById("filaProcesoGuardados").colSpan = "7";*/
				cargarSubprocesos();
				
			}
			peticionUnica1=null;
		}
	}						
}



function imprimirPresupuesto()//js_presupuestosAlta
{	
	//se cambia el valor de pdfGenerado a 1
	//se desactiva el boton modificar
	
	
	//al cargar la pagina web, al buscar, al copiar y al dar nueva version,  se activa el boton modificar
	// 
	imprimirPresu = false;

	verProcesosObligatorios_Distribucion() //si 'Distribucion' existe, tambien debe de existir 'tratamiento de Sobrante' y tratamiento de devoluciones'
	
	if (imprimirPresu==true)
	{
		cambiarValorPdfGenerado(true);
	
		document.getElementById("numPresuBuscar").value = document.getElementById("numPresupuesto").innerHTML;
		buscarPresupuesto();
		document.getElementById("numPresuBuscar").value="";
		
		document.getElementById("imprimirNumPresupuesto").value = document.getElementById("numPresupuesto").innerHTML;	
		//document.getElementById("nombreCarpetaForm").value = document.getElementById("nombrePresupuestoCarpeta").value;
		document.getElementById("nombreCarpetaForm").value = reemplazarSimbolos3(document.getElementById("nombrePresupuestoCarpeta").value);
		
		
		document.getElementById("formImprimir").submit();
		
		$("#nombrePresupuestoModal").modal('hide');
		document.getElementById("imprimirPresupuestoModalError").innerHTML="";

	}
	else if (sinDatos==true)
	{
		document.getElementById("imprimirPresupuestoModalError").innerHTML = "Hay que introducir algún proceso &nbsp;&nbsp;<img src='imagenes/facepalm.gif'  style=' width:50px !important;'>";
	}
	else
	{		
		document.getElementById("imprimirPresupuestoModalError").innerHTML = "Si existe el tipo de proceso 'Distribución', debe de estar también 'tratamiento de sobrante' y 'tratamiento de devoluciones'";
	}
	
	
	
}

function cerrarModalImprimirPresupuesto()
{
	document.getElementById("imprimirPresupuestoModalError").innerHTML="";
}


function insertarProvisionDeFondos_presupuesto()//js_presupuestosAlta
{
	if (document.getElementById("importeProvisionFondo").value=="")
	{
		alert("Introducir un Importe");
		document.getElementById("importeProvisionFondo").focus();
	}
	else if (confirm("¿Añadir Provision de Fondo?")) 
	{			 
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarProvisionDeFondos_presupuesto;
			peticionUnica1.open("POST","ajax/insertarProvisionDeFondos.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarProvisionDeFondos_presupuesto();
			peticionUnica1.send(query_string);						
		}
	}
}
function consultaInsertarProvisionDeFondos_presupuesto()
{	
	var consulta = "accion=insertarPF";
	consulta += "&presupuesto="+document.getElementById("numPresupuesto").innerHTML;
	consulta += "&importe="+document.getElementById("importeProvisionFondo").value;
	consulta += "&tipo="+document.getElementById("tipoProvisionFondo").value;
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	return consulta;	
}

function mostrarInsertarProvisionDeFondos_presupuesto()
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
				actualizarHistoricoPFpresupuesto();
				
				/*alert(peticionUnica.responseText);
				document.getElementById("botonAnadirProvision").style.visibility = "hidden";
				document.getElementById("botonAnadirProvision").style.display = "none";
				document.getElementById("importeProvisionFondo").readOnly = true;
				document.getElementById("tipoProvisionFondo").disabled = true;
				
				document.getElementById("botonImprimirProvision").style.visibility = "visible";
				document.getElementById("botonImprimirProvision").style.display = "table-cell";
				
				document.getElementById("botonImprimirProvisionPagoCuenta").style.visibility = "visible";
				document.getElementById("botonImprimirProvisionPagoCuenta").style.display = "table-cell";*/
				
				
			}
			peticionUnica1=null;
		}
	}						
}

function imprimirProvisionDeFondos_presupuesto()//js_presupuestosAlta
{	
	if (document.getElementById("clienteOrigen").checked)
	{
		document.getElementById("imprimirProvisionFondoNumPresupuestoClayma").value = document.getElementById("numPresupuesto1").innerHTML;
		document.getElementById("imprimirProvisionFondoContadorClayma").value = document.getElementById("numPresupuesto2").innerHTML;
		document.getElementById("formImprimirProvisionDeFondoClayma").submit();	
	}
	else
	{
		document.getElementById("imprimirProvisionFondoNumPresupuesto").value = document.getElementById("numPresupuesto1").innerHTML;
		document.getElementById("imprimirProvisionFondoContador").value = document.getElementById("numPresupuesto2").innerHTML;
		document.getElementById("formImprimirProvisionDeFondo").submit();	
	}	
}

function imprimirPagoACuenta_presupuesto()//js_presupuestosAlta
{
	if (document.getElementById("clienteOrigen").checked)
	{
		document.getElementById("imprimirPagoACuentaNumPresupuestoClayma").value = document.getElementById("numPresupuesto1").innerHTML;
		document.getElementById("imprimirPagoACuentaNumPresupuestoContadorClayma").value = document.getElementById("numPresupuesto2").innerHTML;
		document.getElementById("formImprimirPagoACuentaClayma").submit();
	}
	else
	{
		document.getElementById("imprimirPagoACuentaNumPresupuesto").value = document.getElementById("numPresupuesto1").innerHTML;
		document.getElementById("imprimirPagoACuentaNumPresupuestoContador").value = document.getElementById("numPresupuesto2").innerHTML;	
		document.getElementById("formImprimirPagoACuenta").submit();
	}		
}


function cargarDepartamentosProceso()//js_presupuestosAlta
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDepartamentosProceso;
		peticionUnica1.open("POST","ajax/mostrarDepartamentosProcesos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDepartamentosProceso();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarDepartamentosProceso()
{	
	var consulta = "accion=cargarDepProcesos";
	return consulta;	
}

function mostrarCargarDepartamentosProceso()
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
					
					if (datos.length<=0)
					{						
					}
					else
					{													
						var contenido = "";	
						var contador = 0;

						while  (contador<datos.length)
						{
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["departamento"]+'</option>';				

							contador++;
						}
						
						document.getElementById("departamentoProceso").innerHTML = contenido;
						
					}
				}				
			}
			peticionUnica1=null;
		}
	}						
}

function cargarTotalFranqueoIVA()	//js_presupuestosAlta			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTotalFranqueoIVA;
		peticionUnica1.open("POST","ajax/mostrarCargarTotalFranqueoIVA.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTotalFranqueoIVA();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarTotalFranqueoIVA()
{	
	var consulta = "accion=mostrarCargarTotalFranqueoIVA";
	
	return consulta;	
}

function mostrarCargarTotalFranqueoIVA()
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

					var contador = 0;

					while  (contador<datos.length)
					{
						if ((datos[contador]["id"]!="3" && document.getElementById("clienteOrigen").checked==true)|| document.getElementById("clienteOrigen").checked==false)
						{
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["tipoIva"]+'</option>';
						}
						
							
						contador++;
					}

					document.getElementById("totalFraqueo").innerHTML = contenido;
				}
			}
			peticionUnica1=null;
		}
	}						
}


function crearPresupuestoMensual()//js_presupuestosAlta
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearPresupuestoMensual;
		peticionUnica1.open("POST","ajax/crearPresupuestoMensual.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearPresupuestoMensual();
		peticionUnica1.send(query_string);						
	}
}

function consultaCrearPresupuestoMensual()
{	
	var consulta = "accion=crearPresupuesto";
	
	//consulta += "&comercial=" + document.getElementById("comercial").options[document.getElementById("comercial").selectedIndex].text;
	consulta += "&comercial=" + document.getElementById("comercial").value;
	if (document.getElementById("detallada").checked == true)
	{
		consulta += "&detallada=1";
	}
	else
	{
		consulta += "&detallada=0";
	}	
	consulta += "&cliente=" + document.getElementById("clientes").value;	
	consulta += "&campania=" + document.getElementById("campanaPresu").value;	
	var cantidad = document.getElementById("cantidadPresu").value;	
	cantidad = cantidad.replace(',','.');
	if (cantidad == "")
	{
		cantidad =0;
	}
	consulta += "&cantidad=" + cantidad;	
	consulta += "&pedCliente=" + document.getElementById("pedidoClientePresu").value;	
	consulta += "&direccion=" + document.getElementById("direccionPresu").value;
	consulta += "&cp=" + document.getElementById("cpPresu").value;	
	consulta += "&poblacion=" + document.getElementById("poblacionPresu").value;	
	//consulta += "&formaPago=" + document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;	
	consulta += "&formaPago=" + document.getElementById("formaPago").value;
	consulta += "&persona=" + document.getElementById("personaPresu").value;
	consulta += "&nota=" + document.getElementById("notasPresu").value;
	consulta +="&observaciones2=" + reemplazarSimbolos(document.getElementById("observaciones2Presu").value);
	
	
	if (document.getElementById("totalPresu").checked == true)
	{
		consulta += "&mtp=1";
	}
	else
	{
		consulta += "&mtp=0";
	}
	
	consulta += "&mtf=" + document.getElementById("totalFraqueo").value;
	
	
	if (document.getElementById("TotalFranqueoImporte").value==null || document.getElementById("TotalFranqueoImporte").value=="null" || document.getElementById("TotalFranqueoImporte").value=="")
	{
		consulta += "&mtfImporte=0";
	}
	else
	{
		consulta += "&mtfImporte=" + document.getElementById("TotalFranqueoImporte").value;
	}
	
	
	if (document.getElementById("permiso_otBajadaAutomatico").value==0)
	{
		consulta += "&otBajada=0";
	}
	else
	{
		consulta += "&otBajada=1";
	}
	
	//consulta +="&mensual="+document.getElementById("presu_mensual").checked;
	consulta +="&numMensual="+document.getElementById("numPresuMensual").value;
	consulta +="&clayma="+document.getElementById("clienteOrigen").checked;	
	
	return consulta;	
}

function mostrarCrearPresupuestoMensual() 
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
				document.getElementById("numPresupuesto").innerHTML = peticionUnica1.responseText;
				document.getElementById("numPresupuesto").style.fontWeight = "bolder";
				document.getElementById("numPresupuesto").style.fontSize = "x-large";
				document.getElementById("numPresupuesto").style.textAlign = "center";				
				
				document.getElementById("nombrePresupuesto").innerHTML = "PRESUPUESTO: "
				
				document.getElementById("botonVerDatosGenericosPresupuesto").innerHTML = '<button type="button" class="btn btn-info" onClick="verDatosGenericosPresupuesto()">VER DATOS GENERICOS</button>';
				
				
				document.getElementById("botonCrearPresupuesto").style.visibility = "hidden";
				document.getElementById("botonCrearPresupuesto").style.display = "none";
				
				document.getElementById("botonNuevoPresupuesto").style.visibility = "visible";
				document.getElementById("botonNuevoPresupuesto").style.display = "table-cell";	
				
				//document.getElementById("botonCopiarPresupuesto").style.visibility = "hidden";
				//document.getElementById("botonCopiarPresupuesto").style.display = "none";	
				
				document.getElementById("botonModificarPresupuesto").style.visibility = "visible";
				document.getElementById("botonModificarPresupuesto").style.display = "table-cell";
				
				document.getElementById("datosPresupuestoGenerico").style.visibility = "hidden";
				document.getElementById("datosPresupuestoGenerico").style.display = "none";
				
				document.getElementById("anadirDetallesPresupuesto").style.visibility = "visible";
				document.getElementById("anadirDetallesPresupuesto").style.display = "table-row-group";
				document.getElementById("anadirDetallesPresupuesto").colSpan = "7";
				
				document.getElementById("botonVersionPresupuesto").style.visibility = "visible";
				document.getElementById("botonVersionPresupuesto").style.display = "table-cell";
				
				document.getElementById("botonProvisionFondo").style.visibility = "visible";
				document.getElementById("botonProvisionFondo").style.display = "table-cell";
				
				document.getElementById("botonModPedCliente").style.visibility = "hidden";
				document.getElementById("botonModPedCliente").style.display = "none";
				
				
				//presupuestoActivarOtBajada();
				
				document.getElementById("numPresuBuscar").value = document.getElementById("numPresupuesto").innerHTML;
				buscarPresupuesto();
				document.getElementById("numPresuBuscar").value="";
			}
			
			peticionUnica1=null;
		}
	}						
}

function verDatosGenericosPresupuesto()//js_presupuestosAlta
{
	document.getElementById("datosPresupuestoGenerico").style.visibility = "visible";
	document.getElementById("datosPresupuestoGenerico").style.display = "table-row-group";
	
	document.getElementById("botonVerDatosGenericosPresupuesto").innerHTML = '<button type="button" class="btn btn-info" onClick="ocultarDatosGenericosPresupuesto()">OCULTAR DATOS GENERICOS</button>';
}
function ocultarDatosGenericosPresupuesto()//js_presupuestosAlta
{
	document.getElementById("datosPresupuestoGenerico").style.visibility = "hidden";
	document.getElementById("datosPresupuestoGenerico").style.display = "none";
	
	document.getElementById("botonVerDatosGenericosPresupuesto").innerHTML = '<button type="button" class="btn btn-info" onClick="verDatosGenericosPresupuesto()">VER DATOS GENERICOS</button>';
}


function cargarDetallesPresupuesto()//js_presupuestosAlta
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDetallesPresupuesto;
		peticionUnica1.open("POST","ajax/mostrarDetallePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarDetallesPresupuesto()
{	
	var consulta = "accion=mostrarDetalles";
	
	consulta+="&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	
	return consulta;	
}

function mostrarCargarDetallesPresupuesto()
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
					//alert("No existe ese presupuesto");
				}
				
				var contenido = "";
				if (datos != "")
				{
					var grupoAntiguo = "9999999999999999999999999999";
					var contador = 0;				
					while  (contador<datos.length)
					{
						if (datos[contador]["idTipo"]!= grupoAntiguo)
						{
							contenido += '<tr><td colspan="7" style="padding-top:15px;border:0px;"><h2>'+datos[contador]["tipoProceso"]+'</h2></td></tr>';
							grupoAntiguo = datos[contador]["idTipo"];
						}

						contenido += '<tr><td>Proceso:</td><td colspan="4"> <select name="'+datos[contador]["id"]+'_procesoDetalle" id="'+datos[contador]["id"]+'_procesoDetalle"></select></td>';
						
						contenido += '<td align="center">'+datos[contador]["departamento"]+'</td>';
						


						if (pdfPresupuestoGenerado!=1 && !permisosSoloLectura)
						{							
							contenido += '<td ROWSPAN="3" align="center"><input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarDetallePresupuesto('+datos[contador]["id"]+')" ></td></tr>';
						}

						
						
						contenido += "<tr>";
						contenido += "<td>Material:</td>";
						var tamanioFinal = ' ';
						
						if (datos[contador]["tamanoFinal"] != null)
						{
							tamanioFinal = " ("+datos[contador]["tamanoFinal"]+") ";
						}
						


						var precioMaterial = "";
						if (datos[contador]["tamano"]+" / "+datos[contador]["tipo"]+" / "+datos[contador]["gramaje"]+" / "+Number(datos[contador]["precioMaterialPapel"]).toLocaleString('de-DE',{minimumFractionDigits: 6}) != "_Ninguno / _Ninguno / 0 / 0,000000" && datos[contador]["tamano"]+" / "+datos[contador]["tipo"]+" / "+datos[contador]["gramaje"]+" / "+Number(datos[contador]["precioMaterialPapel"]).toLocaleString('de-DE',{minimumFractionDigits: 6}) != "null / null / null / 0,000000" )
						{
							contenido += "<td colspan='2'>"+datos[contador]["tamano"]+tamanioFinal+"/ "+datos[contador]["tipo"]+" / "+datos[contador]["gramaje"]+" / "+Number(datos[contador]["precioMaterialPapel"]).toLocaleString('de-DE',{minimumFractionDigits: 6})+"</td>";
						}
						else
						{
							contenido += "<td colspan='2'>Ninguno</td>";
						}

						var tipoImpresora1 = datos[contador]["tipoImpresora"];
						if (tipoImpresora1==null)
						{
							tipoImpresora1='';
						}

						var numeroDeCaras = datos[contador]["impresionNumeroCaras"];
						if (numeroDeCaras==null)
						{
							numeroDeCaras='';
						}
						
						contenido += "<td colspan='2'>Impresora:"+tipoImpresora1+" / Nº Caras: "+numeroDeCaras+"</td>";
							
						if (datos[contador]["exentoIVA"]==1)						
							contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_excentoIVADetalle" checked></input></td>';
						else
							contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_excentoIVADetalle"></input></td>';

							
						contenido += "</tr>";
						
						contenido += "<tr>";
						contenido += "<td>Material GF:</td>";

						if (datos[contador]["gfTipoProceso"]+" / "+datos[contador]["gfMaterial"]+" / "+datos[contador]["gfConcepto"]+" / "+Number(datos[contador]["gfCoste"]).toLocaleString('de-DE',{minimumFractionDigits: 3}) != "_Ninguno / _Ninguno / _Ninguno / 0,000" && datos[contador]["gfTipoProceso"]+" / "+datos[contador]["gfMaterial"]+" / "+datos[contador]["gfConcepto"]+" / "+Number(datos[contador]["gfCoste"]).toLocaleString('de-DE',{minimumFractionDigits: 3}) != "null / null / null / 0,000" )
						{
							contenido += "<td colspan='3'>"+datos[contador]["gfTipoProceso"]+" / "+datos[contador]["gfMaterial"]+" / "+datos[contador]["gfConcepto"]+" / "+Number(datos[contador]["gfCoste"]).toLocaleString('de-DE',{minimumFractionDigits: 3})+"</td>";
						}
						else
						{
							contenido += "<td colspan='2'>Ninguno</td>";
						}
				

						contenido += "</tr>";
						
						
						var auxiliar = datos[contador]["descripcion"];
						
						while (auxiliar.includes('"'))
						{
							auxiliar = auxiliar.replace('"',"'");
						}

						var pesoDetalle = "";
						
						if (datos[contador]["pesoGramos"] != null)
						{
							pesoDetalle =  datos[contador]["pesoGramos"];
						}
						
						contenido += '<tr><td>Descripcion:</td><td colspan="3"><input type="text" id="'+datos[contador]["id"]+'_descripcionDetalle" value="'+auxiliar+'" style="width:100%"></input></td>';
						contenido += '<td>Peso (Gramos):</td><td colspan="1"><input type="text" id="'+datos[contador]["id"]+'_pesoDetalle" value="'+pesoDetalle+'" style="width:100%"></input></td>';
						
						
						contenido += '</tr>';						
						
						auxiliar = datos[contador]["notaCibeles"];
						
						while (auxiliar.includes('"'))
						{
							auxiliar = auxiliar.replace('"',"'");
						}						
						
						contenido += '<tr><td>Nota Cibeles:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_notaDetalle" value="'+auxiliar+'" style="width:100%"></input></td>';

						contenido += '<tr><td>Nota Adm-Pro:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_notaAdmonProd" style="width:100%" placeholder="Introducir Notas de Administracion-Produccion" value="'+datos[contador]["notaAdmonProd"]+'"></input></td>';
							
							
						if (pdfPresupuestoGenerado!=1 && !permisosSoloLectura)
						{
							contenido += '<td ROWSPAN="2"><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalle" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarDetallePresupuesto('+datos[contador]["id"]+')" ></td></tr>';
						}

						contenido += '<tr>';
						
						var aux='';
						if (datos[contador]["unidades"].startsWith('.') || datos[contador]["unidades"]=="" )
						{
							aux = "0"+datos[contador]["unidades"];
						}
						else
						{
							aux = datos[contador]["unidades"];
						}	
						
						contenido += '<td>Unidades:</td><td><input type="number" id="'+datos[contador]["id"]+'_unidadesDetalle" value="'+aux+'"></input></td>';
						
						
						aux='';
						if (datos[contador]["precio"].startsWith('.') || datos[contador]["precio"]=="" )
						{
							aux = "0"+datos[contador]["precio"];
						}
						else
						{
							aux = datos[contador]["precio"];
						}						
						
						contenido += '<td align="right">Precio:</td><td><input type="number" id="'+datos[contador]["id"]+'_precioDetalle" value="'+aux+'"></input></td>';
						contenido += '<td align="right">Orden:</td><td><input type="number" id="'+datos[contador]["id"]+'_ordenDetalle" value="'+datos[contador]["orden"]+'"></input></td>';

						contenido += '<tr><td colspan="7"><hr class="hrborde2px"></td></tr>';

						contenido += '</tr>';

						contador++;					
					}

					document.getElementById("detallesPresupuesto").innerHTML = contenido;

					contador = 0;

					while  (contador<datos.length)
					{ 
						valorTipo = datos[contador]["idTipo"];
						valorDepartamento = datos[contador]["idDepartamento"];
						idInputListado = datos[contador]["id"]+'_procesoDetalle';
						cargarSubprocesosGuardados();

						document.getElementById(datos[contador]["id"]+'_procesoDetalle').value = datos[contador]["idConcepto"];					

						idInputListado = "";
						valorTipo = 0;
						valorDepartamento=0;
						contador++;	
					}

					document.getElementById("detallesPresupuesto").style.visibility = "visible";
					document.getElementById("detallesPresupuesto").style.display = "table-row-group";
					document.getElementById("detallesPresupuesto").colSpan = "6";
				}
				else
				{
					document.getElementById("detallesPresupuesto").innerHTML = contenido;
				}
				
				//rellenar los listado y seleccionar el correspondiente
				//hacer visible el tbody
				
			}
			peticionUnica1=null;
		}
	}						
}


function actualizarHistoricoPFpresupuesto()//js_presupuestosAlta
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarActualizarHistoricoPFpresupuesto;
		peticionUnica1.open("POST","ajax/mostrarProvisionDeFondos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaActualizarHistoricoPFpresupuesto();
		peticionUnica1.send(query_string);						
	}
}
function consultaActualizarHistoricoPFpresupuesto()
{	
	var consulta = "accion=verProvision";
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	return consulta;	
}

function mostrarActualizarHistoricoPFpresupuesto()
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
					contenido = '<table border=1 align="center">';
					

					var contador = 0;
					
					if (datos.length>0)
					{
						contenido += '<tr style="color:black" >';
						contenido+='<td align="center"><b>Nº</b></td>';
						contenido+='<td align="center"><b>Tipo</b></td>';
						contenido+='<td align="center"><b>Importe</b></td>';
						contenido+='<td></td>';
						contenido+='<td></td>';
						
						contenido += "</tr>";
					}
					
					
					while  (contador<datos.length)
					{
						if (datos[contador]["borradaComercial"]=="" || datos[contador]["borradaComercial"]==null || datos[contador]["borradaComercial"]=="null")
						{
							contenido += '<tr style="color:black">';
							contenido+='<td id="'+datos[contador]["id"]+'_presupuesto">'+datos[contador]["presupuesto"]+' - '+datos[contador]["contador"]+'</td>';
							contenido+='<td id="'+datos[contador]["id"]+'_tipo">'+datos[contador]["tipoTexto"]+'</td>';
							contenido+='<td id="'+datos[contador]["id"]+'_importe">'+datos[contador]["importe"]+'</td>';

							contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_imprimir" src="imagenes/imprimirNegro.png" style="width:15px;"  onclick="mostrarImprimirPFpresupuesto('+datos[contador]["id"]+')"></td>';

							if (datos[contador]["cobrada"]=="1" && !permisosSoloLectura)
							{
								contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_imprimir" src="imagenes/eliminarNegro.png" style="width:15px;"  onclick="eliminarProvisionComercial('+datos[contador]["id"]+')"></td>';
							}
							else
							{
								contenido += '<td></td>';
							}
						
						
							contenido += "</tr>";
						}
						
						contador++;
					}
					
					contenido += '</table>';

					document.getElementById("historicoPF").innerHTML = contenido;
				}
				
			}
			peticionUnica1=null;
		}
	}						
}

function eliminarProvisionComercial(idPF)
{
	if (confirm('¿Anular la provision: '+document.getElementById(idPF+"_presupuesto").innerHTML+'?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarProvisionComercial;
			peticionUnica1.open("POST","ajax/eliminarProvisionComercial.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarProvisionComercial(idPF);
			peticionUnica1.send(query_string);
		}		
	}
}

function consultaEliminarProvisionComercial(idPF)
{	
	var consulta = "accion=eliminarProvisionComercial";
	
	consulta+="&idPF="+idPF;	
	
	return consulta;	
}

function mostrarEliminarProvisionComercial()
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
				actualizarHistoricoPFpresupuesto();
				
			}
			peticionUnica1=null;
		}
	}						
}


function cambiarValorPdfGenerado(valor)//true se guarda 1; false se guarda 0.  1->pdfGenerado; 0->pdfSinGenerar  //js_presupuestoAlta
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCambiarValorPdfGenerado;
			peticionUnica1.open("POST","ajax/modificarPresupuestoPdfGenerado.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCambiarValorPdfGenerado(valor);
			peticionUnica1.send(query_string);						
		}
}

function consultaCambiarValorPdfGenerado(valor)
{	
	var consulta = "accion=cambiarValorPdf";
	var valor1=0
	
	if (valor==true)
	{
		valor1=1
	}
	
	consulta+="&valor="+valor1;
	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	
	
	return consulta;	
}

function mostrarCambiarValorPdfGenerado()
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
				//alert(peticion33.responseText);
				//alert(peticion32.responseText);
				//alert("Presupuesto Modificado");
				//cargarListadoPresupuesto();
				
			}
			peticionUnica1=null;
		}
	}						
}


function mostrarImprimirPFpresupuesto(idPF)//js_presupuestosAlta
{
	//document.getElementById("numPresupuesto").innerHTML =   document.getElementById(idPF+"_presupuesto").innerHTML;
	//ESTO FUNCIONA PARA LOS PROVISIONES DE FONDO DE PRESUPUESTOS
	var datos =  document.getElementById(idPF+"_presupuesto").innerHTML.split(' - ');
	
	document.getElementById("numPresupuesto1").innerHTML =  datos[0];
	
	
	
	//document.getElementById("numPresupuesto2").innerHTML =   document.getElementById(idPF+"_presupuesto").innerHTML;
	document.getElementById("numPresupuesto2").innerHTML =   datos[1];
	
	//document.getElementById("clienteProvisionFondoModal").innerHTML = '';
	document.getElementById("importeProvisionFondoModal").innerHTML = document.getElementById(idPF+"_importe").innerHTML;
	
	
	$("#imprimirProvisionFondoModal").modal('show');
}


function modificarDetallePresupuesto(idInput)//js_presupuestosAlta
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDetallePresupuesto;
		peticionUnica1.open("POST","ajax/modificarDetallePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDetallePresupuesto(idInput);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarDetallePresupuesto(idInput)
{	
	var consulta = "accion=modificarDetalle";	
	
	consulta+="&idDetalle="+idInput;		
	consulta += "&proceso=" + document.getElementById(idInput+"_procesoDetalle").value;	
		
	consulta +="&descripcion=" + reemplazarSimbolos(document.getElementById(idInput+"_descripcionDetalle").value);
		
	consulta +="&nota=" + reemplazarSimbolos(document.getElementById(idInput+"_notaDetalle").value);
	consulta +="&notaAdmonProd=" + reemplazarSimbolos(document.getElementById(idInput+"_notaAdmonProd").value);	
	
	var unidad = document.getElementById(idInput+"_unidadesDetalle").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}
	consulta += "&unidad=" + unidad;
	
	var precio = document.getElementById(idInput+"_precioDetalle").value;	
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}
	
	consulta += "&precio=" + precio;
	
	var orden = document.getElementById(idInput+"_ordenDetalle").value;
	if (orden=="" || orden==0)
	{
		orden = 999;
	}
	
	consulta += "&orden=" + orden;
	var presu = "";
	if (document.getElementById("letraPresupuesto").innerHTML == "")
	{
		presu = document.getElementById("numPresupuesto").innerHTML;
	}
	else
	{
		presu = document.getElementById("numPresupuesto").innerHTML + document.getElementById("letraPresupuesto").innerHTML;
	}
	consulta+="&numPresupuesto="+presu;
	
	consulta+="&exentoIVA="+document.getElementById(idInput+"_excentoIVADetalle").checked;

	var elPeso = 0;
	if (document.getElementById(idInput+"_pesoDetalle").value!="")
	{
		elPeso = document.getElementById(idInput+"_pesoDetalle").value;
	}
	
	consulta += "&peso=" + elPeso;
	
	//alert(consulta);
	return consulta;	
}

function mostrarModificarDetallePresupuesto()
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
				cargarDetallesPresupuesto();
			}
			peticionUnica1=null;
		}
	}						
}


function eliminarDetallePresupuesto(idInput)//js_presupuestoAlta
{	
	if (confirm('¿Borrar Detalle?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarDetallePresupuesto;
			peticionUnica1.open("POST","ajax/eliminarDetallePresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarDetallePresupuesto(idInput);
			peticionUnica1.send(query_string);
		}		
	}
}

function consultaEliminarDetallePresupuesto(idInput)
{	
	var consulta = "accion=eliminarDetalle";
	
	consulta+="&idDetalle="+idInput;
	
	var presu = "";
	if (document.getElementById("letraPresupuesto").innerHTML == "")
	{
		presu = document.getElementById("numPresupuesto").innerHTML;
	}
	else
	{
		presu = document.getElementById("numPresupuesto").innerHTML + document.getElementById("letraPresupuesto").innerHTML;
	}
	consulta+="&numPresupuesto="+presu;
	
	return consulta;	
}

function mostrarEliminarDetallePresupuesto()
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
				cargarDetallesPresupuesto();
			}
			peticionUnica1=null;
		}
	}						
}


function cargarDatosPresupuesto() 
{
	peticionUnica1=null;
	peticionUnica1 =crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDatosPresupuesto;
		peticionUnica1.open("POST","ajax/verDatosClientePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDatosPresupuesto();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarDatosPresupuesto()
{	
	var consulta = "accion=verDatosClientePresupuesto";		
	consulta += "&cliente=" + document.getElementById("clientes").value;
	consulta +="&clayma=" + document.getElementById("clienteOrigen").checked;
	
	return consulta;	
}

function mostrarCargarDatosPresupuesto()
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
				if (peticionUnica1.responseText!="no existe el cliente")					
				{				
					var datos = new Array;

					datos = JSON.parse(peticionUnica1.responseText);

					document.getElementById("direccionPresu").value = datos[0]["direccion"];
					document.getElementById("cpPresu").value = datos[0]["codigo_postal"];
					document.getElementById("poblacionPresu").value = datos[0]["localidad"];
					document.getElementById("formaPago").value = datos[0]["idFormaPago"];
				}
			}
			peticionUnica1 = null;
		}
	}						
}

function imprimirPresupuestoMensuales()
{
	/*document.getElementById("numPresuBuscar").value = document.getElementById("numPresupuesto").innerHTML;
	buscarPresupuesto();
	document.getElementById("numPresuBuscar").value="";*/
	
	document.getElementById("imprimirNumPresupuesto").value = "";	
	document.getElementById("nombreCarpetaForm").value = reemplazarSimbolos3(document.getElementById("nombrePresupuestoCarpeta").value);
	
	document.getElementById("mes").value = document.getElementById("mesCopiaModal").value;	
	document.getElementById("anio").value = document.getElementById("anioCopiaModal").value;	
	
	
	document.getElementById("formImprimirPresMensuales").submit();
}




function verProcesosObligatorios_Distribucion()//para comprobrar si existe los procesos si hay distribucion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDetallesPresupuesto_2;
		peticionUnica1.open("POST","ajax/mostrarDetallePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesPresupuesto_2();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarDetallesPresupuesto_2()
{	
	var consulta = "accion=mostrarDetalles";
	
	consulta+="&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	
	return consulta;	
}

function mostrarCargarDetallesPresupuesto_2()
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
					//alert("No existe ese presupuesto");
				}
				
				var contenido = "";
				if (datos != "")
				{
					var hayDistribucion = false;
					var haySobrante = false;
					var hayDevoluciones = false;
					var esFranqueo = false;
					var esTipsa = false;

					var contador = 0;				
					while  (contador<datos.length)
					{
						if (datos[contador]["idTipo"] == "5")
						{
							hayDistribucion = true;
						}
						else if (datos[contador]["idTipo"] == "7")
						{
							haySobrante = true;
						}
						else if (datos[contador]["idTipo"] == "9")
						{
							hayDevoluciones = true;
						}

						if (datos[contador]["idDepartamento"]=="3")
						{
							esFranqueo = true;
						}
						else if (datos[contador]["idTipsa"]=="5")
						{
							esTipsa = true;
						}

						contador++;					
					}

					if (hayDistribucion==true && haySobrante==true && hayDevoluciones==true)
					{
						imprimirPresu = true;
					}
					else if (hayDistribucion==true && (esFranqueo==true || esTipsa == true))
					{
						imprimirPresu = false;
					}
					else if (hayDistribucion==true)
					{
						imprimirPresu = true;
					}
					else if (hayDistribucion==false)
					{
						imprimirPresu = true;
					}
					else
					{
						imprimirPresu = false;
					}
				}
				else
				{
					sinDatos = true;
				}
				
			}
			peticionUnica1=null;
		}
	}						
}





function cargarGfTipoProceso(idInput)
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarGfTipoProceso;
		peticionUnica1.open("POST","ajax/cargarConceptosGF.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGfTipoProceso();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarGfTipoProceso()
{	
	var consulta = "accion=cargarConcepto";	
	return consulta;	
}

function mostrarCargarGfTipoProceso()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreConcepto"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
			cargarGFSubConcepto1("gf_material");
		}
	}						
}


//////
function cargarGFSubConcepto1(idInput) //js_pdaGestion
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarGFSubConcepto1;
		peticionUnica1.open("POST","ajax/cargarSubConceptos1GF.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGFSubConcepto1();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarGFSubConcepto1()
{	
	var consulta = "accion=cargarConcepto";
		
	consulta += "&idConcepto=" + document.getElementById("gf_tipoProceso").value;
	
	return consulta;	
}

function mostrarCargarGFSubConcepto1()
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
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreSubconcepto"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				
				idInputListado="";
				//cargarSubConcepto2("RN_subconcepto2");
				
						
			}
			peticionUnica1=null;	
			cargarSubConcepto2("gf_concepto");		
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
	
	consulta += "&idSubConcepto1=" + document.getElementById("gf_material").value;
		
	return consulta;	
}

function mostrarCargarSubConcepto2()
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


