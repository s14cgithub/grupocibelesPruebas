var peticionUnica1 = null;

var laCondicion="";
var mostrarModificarProcesado=false;
var mostrarVisualizarProcesado=false;

function buscarFactura()
{
	var condicion=" where numNoFactura != '' ";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	
	
	
	//var clayma = document.getElementById("clienteOrigen").checked;
	
	
	if ((campoAbuscar=="numero" && textoAbuscar!="")  ||(campoAbuscar=="factura" && textoAbuscar!=""))
	{
		condicion += " and "+campoAbuscar+" = " + textoAbuscar;	
	}
	else
	{
		condicion += " and "+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";	
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
		
		condicion += " and fecha < '"+laFecha1+"'";
	}
	
	//if (document.getElementById("ordenProcesado").checked==true)
	if (document.getElementById("ordenProcesadosSolo").checked==true)
	{
		condicion +=" and [noFacProcesado]=1";
	}
	else if (document.getElementById("ordenProcesadosSin").checked==true)
	{
		condicion +=" and ([noFacProcesado]=0 or [noFacProcesado] is null)";
	}
	
	if (document.getElementById("excluirInstituto").checked==true)
	{
		condicion += " and cliente not like '%instituto%'";
	}
	
	if (document.getElementById("conImporte").checked==true)
	{
		condicion += "  and importePresupuesto > 0";
	}

	if (document.getElementById("crisMagia").checked==true)
		{
			condicion += " and UPPER(noSeFacturaObservaciones) not like  '%MAGIA%'";
		}

	
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	
	
	
	cargarListadoPresupuestoNoFacturable();
	
}

function cargarListadoPresupuestoNoFacturable()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPresupuestoNoFacturable;
		peticionUnica1.open("POST","ajax/mostrarPresupuestos2.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPresupuestoNoFacturable();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPresupuestoNoFacturable()
{	
	var consulta = "accion=mostrarPresupuesto";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	//consulta += "&condicion="+laCondicion;
	
	//laCondicion=null;
	
	
	return consulta;	
}

function mostrarCargarListadoPresupuestoNoFacturable()
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
					
					contenido += '<th>Numero</th>';
					contenido += '<th>Presupuesto</th>';
					contenido += '<th>Cliente</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Importe</th>';
					contenido += '<th>Fecha</th>';
					contenido += '<th>Obs.</th>';
					
				if (!(mostrarVisualizarProcesado==true && mostrarModificarProcesado==false))
				{
						
					contenido += '<th></th>';	
				}
					
				
				if (mostrarVisualizarProcesado==true)
				{
					contenido += '<th></th>';	
				}
				if (mostrarModificarProcesado==true)
				{
					contenido += '<th></th>';	
				}
				
				
				
				

					contenido += '</tr>';
				
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
					
					
					contenido += '<td align="center"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["numNoFactura"]+'</span></td>';
					contenido += '<td align="center"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</span></td>';
					contenido += '<td align="left"><span>'+datos[contador]["cliente"]+'</span></td>';
					contenido += '<td align="left"><span">'+datos[contador]["campana"]+'</span></td>';
					
					var importe = 0;
					
					//var noTruncarDecimales = {maximumFractionDigits: 2};
					importe = Number(datos[contador]["importePresupuesto"]).toLocaleString('de-DE',{minimumFractionDigits: 2});
					//importe = Number(datos[contador]["importePresupuesto"]).toLocaleString(undefined);
					
					
					
					/*if (importe == ".00")
					{
						importe = "0";
					}*/
					
					contenido += '<td align="right"  style="overflow:hidden; white-space: nowrap;">'+importe+' €</td>';
					
								
					
					var dia = datos[contador]["numNoFacturaFecha"]["date"].substr(8,2);
					var mes = datos[contador]["numNoFacturaFecha"]["date"].substr(5,2);
					var anio = datos[contador]["numNoFacturaFecha"]["date"].substr(0,4);
					
					contenido += '<td><span style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</span></td>';
					
					contenido += '<td align="center"><input type="image" value="" src="imagenes/ojo.png" style="width:15px;"  onclick="verObservacion(\''+datos[contador]["numNoFactura"]+'\')"></td>';
					
					if (!(mostrarVisualizarProcesado==true && mostrarModificarProcesado==false))
					{

						if (datos[contador]["noFacProcesado"]==1)
						{
							contenido += '<td align="center"></td>';
						}
						else
						{
							contenido += '<td align="center"><input type="image" value="" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarNumNoFacturable1(\''+datos[contador]["numNoFactura"]+'\')"></td>';
						}
					}
					else
					{
						document.getElementById("modalModificarObservacion").style.visibility = "hidden";
						document.getElementById("modalModificarObservacion").style.display = "none";
					}
					
					var soloLectura=' onclick="return false;"';
					if (mostrarModificarProcesado==true)
					{
						soloLectura=' onclick=""';
					}
					
					
					if (mostrarVisualizarProcesado==true)
					{
						if (datos[contador]["noFacProcesado"]==1)
						{
							contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_Procesado" '+soloLectura+' checked></input></td>';
						}
						else
						{
							contenido += '<td align="center"><input type="checkbox"  id="'+datos[contador]["presupuesto"]+'_Procesado" '+soloLectura+'></input></td>';
						}
					
					}
					if (mostrarModificarProcesado==true)
					{
						contenido += '<td align="center"><input type="image" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="cambiarProcesado(\''+datos[contador]["presupuesto"]+'\')"></td>';
					}
					
					
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listado").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}

function cambiarProcesado(presupuesto)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCambiarProcesado;
		peticionUnica1.open("POST","ajax/modificarNoFacturableProcesado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarProcesado(presupuesto);
		peticionUnica1.send(query_string);
	}
}
function consultaCambiarProcesado(presupuesto)
{	
	var consulta = "accion=cambiarProcesado";	
	
	consulta += "&presupuesto="+presupuesto;
	consulta += "&procesado="+document.getElementById(presupuesto+'_Procesado').checked;
	
	
	return consulta;	
}

function mostrarCambiarProcesado()
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
				buscarFactura();
			}
			peticionUnica1=null;			
		}
	}						
}




function eliminarNumNoFacturable1(idNoFactura)
{
	document.getElementById("eliminarNumeroModalLabel").innerHTML="ELIMINAR NUMERO NO FACTURABLE: "+idNoFactura;
	//document.getElementById("eliminarNumeroModalLabel").style.color= "#FF0000";
	document.getElementById("numFacturaModal").innerHTML=idNoFactura;
	$("#eliminarFacturaModal").modal('show');
	
}
function eliminarNumNoFacturable2()
{
	if (confirm('¿Eliminar?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarNumNoFacturable2;
			peticionUnica1.open("POST","ajax/eliminarNoFacturable.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarNumNoFacturable2();
			peticionUnica1.send(query_string);
		}
	}
}


function consultaEliminarNumNoFacturable2()
{	
	var consulta = "accion=eliminarNumNoFacturable";	
	
	consulta += "&numero="+document.getElementById("numFacturaModal").innerHTML;
	consulta += "&motivo="+document.getElementById("motivoEliminacionModal").value;
	
	document.getElementById("numFacturaModal").innerHTM="";
	document.getElementById("motivoEliminacionModal").value="";
	
	return consulta;	
}

function mostrarEliminarNumNoFacturable2()
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
				$("#eliminarFacturaModal").modal('hide');
				buscarFactura();
			}
			peticionUnica1=null;			
		}
	}						
}

function verObservacion(numero) //js_facturas
{
	document.getElementById("numFacturaModal").innerHTML = numero;
	//document.getElementById("claymaModal").innerHTML = document.getElementById(numero+"_clayma").checked;
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerObservacion;
		peticionUnica1.open("POST","ajax/verDatosUnPresuNoFac.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerObservacion(numero);
		peticionUnica1.send(query_string);
	}
}

function consultaVerObservacion(numero)
{	
	var consulta = "accion=verDatosPresuNoFac";	
	consulta += "&numero="+numero;
	
	return consulta;	
}

function mostrarVerObservacion()
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
				
				if (datos.length>0)
				{					
					document.getElementById("observacionInternaModal").value = datos[0]["noSeFacturaObservaciones"];
				}
				//document.getElementById("").value = peticionUnica.responseText;
				$("#observacionFacturaModal").modal('show');
			}
			peticionUnica1=null;
		}
	}						
}



function modificarObservacion() //js_facturas
{
	//document.getElementById("numFacturaModal").innerHTML = numero;
	
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarObservacion;
		peticionUnica1.open("POST","ajax/modificarObservacionesNoFac.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarObservacion();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarObservacion()
{	
	var consulta = "accion=modificarObservacion";	
	consulta += "&numero="+document.getElementById("numFacturaModal").innerHTML;
	consulta += "&observacion=" + document.getElementById("observacionInternaModal").value;
	
	return consulta;	
}

function mostrarModificarObservacion()
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
				alert("Observacion Realizada");
				
			}
			peticionUnica1=null;
		}
	}						
}

function gestionExportarExcel()
{
	document.getElementById("exportarCondiciones").value = laCondicion;
	document.getElementById("formExportarExcel").submit();
}


