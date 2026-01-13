var peticionUnica1 = null;


function cargarListadoPresupuesto1()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion();

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPresupuesto1;
		peticionUnica1.open("POST","ajax/mostrarPresupuestos1.php",true);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPresupuesto1();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPresupuesto1()
{	
	var consulta = "accion=mostrarPresupuesto";	
	
	consulta += "&orden="+document.getElementById("columnas").value;
	
	if (document.getElementById("ordenDescendiente").checked)
	{
		consulta += "&desc=desc";
	}
	else
	{
		consulta += "&desc=asc";
	}
	
	
	consulta += "&texto="+document.getElementById("buscarTexto").value;
	
	if (document.getElementById("tipoBusquedaPresupuesto").checked)
	{
		consulta += "&queBusca=presupuesto";
	}
	else if (document.getElementById("tipoBusquedaCliente").checked)
	{
		consulta += "&queBusca=cliente";
	}
	else
	{
		consulta += "&queBusca=campana";
	}
	
	consulta += "&bajada=" + document.getElementById("busqBajada").checked;
	consulta += "&abierta=" + document.getElementById("busqAbierta").checked;
	
	consulta += "&meses=" + document.getElementById("buscarNumMeses").value;
	consulta += "&fechaAceptacion=" + document.getElementById("buscarFechaAceptacion").value;
	
	
	return consulta;	
}

function mostrarCargarListadoPresupuesto1()
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
				if (peticionUnica1.responseText!="")
				{
					datos = JSON.parse(peticionUnica1.responseText);		
				}					
				
				var contenido = "";
				
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					contenido += '<th align="center">Presupuesto</th>';
					//contenido += '<th>comercial</th>';			
					contenido += '<th>Cliente</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Fecha Creacion</th>';
					
					if (permisos==2 || idUsuario==5 )
					{
						contenido += '<th>Ot Bajada</th>';
						contenido += '<th>ot abierta</th>';
						contenido += '<th>Terminado</th>';

						contenido += '<th></th>';
						contenido += '<th></th>';
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
					
					var anioDeFactura = datos[contador]["anioFactura"];
					if (anioDeFactura!=null)
					{
						anioDeFactura = anioDeFactura - 2000;
					}
					
					if (datos[contador]["numFactura"] != null && datos[contador]["clayma"]==0 )
					{
						contenido += '<td align="right" style="background:green;" title="'+datos[contador]["numFactura"]+'/'+anioDeFactura+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
					}
					else if (datos[contador]["numFactura"] != null && datos[contador]["clayma"]==1 )
					{
						contenido += '<td align="right" style="background: #B87240" title="'+datos[contador]["numFactura"]+'/'+anioDeFactura+'-Clayma"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
					}
					else if (datos[contador]["numNoFactura"] != null)
					{
						contenido += '<td align="right" style="background: black" title="'+datos[contador]["numNoFactura"]+'-No Facturable"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
					}
					else
					{
						contenido += '<td align="right"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
					}
					
					
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					contenido += '<td>'+datos[contador]["cliente"]+'</td>';
					contenido += '<td>'+datos[contador]["campana"]+'</td>';
					//contenido += '<td><input type="date" id="fechaCreacion" value="'+datos[contador]["fecha"]["date"].substring(0,10)+'" readonly></input></td>';
					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="fechaCreacion"><span style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</span></td>';
					
					
					if (permisos==2  || (permisos==1 && idUsuario==5))
					{
						var soloLectura = '';
						if (document.getElementById("permiso_otBajadaPresu").value==0)
						{
							soloLectura = 'onclick="return false;"';
						}

						if(datos[contador]["otBajada"]==1)
						{
							contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otBajada" name="otBajada" value="" checked '+soloLectura+'></input></td>';						
						}
						else
						{
							contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otBajada" name="otBajada" value="" '+soloLectura+'></input></td>';
						}


						soloLectura = '';
						if (document.getElementById("permiso_otAbiertaPresu").value==0)
						{
							soloLectura = 'onclick="return false;"';
						}

						if(datos[contador]["otAbierta"]==1)
						{
							contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otAbierta" name="_otAbierta" value=""  onchange="comprobarFechasAcepYComp(this.id)" checked '+soloLectura+'></input></td>';						
						}
						else
						{
							contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otAbierta" name="_otAbierta" value=""  onchange="comprobarFechasAcepYComp(this.id)" '+soloLectura+'></input></td>';
						}
						//alert(datos[contador]["fechaRealizada"]);
						//var fechaTerminado = "";


						soloLectura = '';
						if (document.getElementById("permiso_otAbiertaPresu").value==0 || datos[contador]["fechaCompromiso"]=="" || datos[contador]["fechaCompromiso"]==null )
						{
							soloLectura = 'readonly';
						}

						var fechaCompromiso2="";

						if (datos[contador]["fechaCompromiso"]==""||datos[contador]["fechaCompromiso"]==null)
						{
							fechaCompromiso2="";						
						}
						else
						{
							fechaCompromiso2=datos[contador]["fechaCompromiso"]["date"].substring(0,10);
						}

						if (datos[contador]["fechaTerminado"]==""||datos[contador]["fechaTerminado"]==null)
						{
							contenido += '<td><input type="date" id="'+datos[contador]["presupuesto"]+'_fechaTerminado" value="" '+soloLectura+'></input></td>';
						}
						else
						{ 
							contenido += '<td><input type="date" id="'+datos[contador]["presupuesto"]+'_fechaTerminado" value="'+datos[contador]["fechaTerminado"]["date"].substring(0,10)+'" max="'+fechaCompromiso2+'"  onChange="verificarFechaTerminado(this.value,this.max, this.id)" '+soloLectura+'></input></td>';
						}

						if (datos[contador]["activo"]=="1" && idUsuario!=5 && permisos == 2)
						{
							contenido += '<td><input type="image" id="'+datos[contador]["presupuesto"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarPresupuesto2('+datos[contador]["presupuesto"]+')"></td>';	
						}
						else if (datos[contador]["activo"]=="1" && permisos == 1 && idUsuario==5)
						{
							//contenido += '<td></td>';
						}
						else
						{
							contenido += '<td></td>';
						}



						contenido += '<td><button type="button" class="btn btn-info" style="height: =5px;line-height: 5px;" id="'+datos[contador]["presupuesto"]+'_verPresupuesto" onclick="irAVerPresupuesto(this.id)" >PPTO</button></td>';



						var fechaAceptacion="";					
						var fechaCompromiso="";

						if (datos[contador]["fechaAceptacion"]=="null"||datos[contador]["fechaAceptacion"]==null||datos[contador]["fechaAceptacion"]=="")
						{
							fechaAceptacion="";
						}
						else
						{
							fechaAceptacion=datos[contador]["fechaAceptacion"]["date"].substring(0,10);
						}

						if (datos[contador]["fechaCompromiso"]=="null"||datos[contador]["fechaCompromiso"]==null||datos[contador]["fechaCompromiso"]=="")
						{
							fechaCompromiso="";
						}
						else
						{
							fechaCompromiso=datos[contador]["fechaCompromiso"]["date"].substring(0,10);
						}					


						if (datos[contador]["activo"]=="1" && idUsuario!=5 && permisos == 2)
						{
							contenido += '<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderTrabajoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;" onClick="pasarDatosPresupuesto(\''+datos[contador]["presupuesto"]+'\',\''+fechaAceptacion+'\',\''+fechaCompromiso+'\')">OT</button></td>';	
						}
						else if (datos[contador]["activo"]=="1" && permisos == 1 && idUsuario==5)
						{
							//contenido += '<td></td>';
						}
						else
						{
							contenido += '<td></td>';
						}
					}					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoPresupuestos").innerHTML = contenido;
			}
			peticionUnica1=null;			
		}
	}						
}


function modificarPresupuesto2(presupuesto1)//js_presupuestosListado
{
	peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarPresupuesto2;
			peticionUnica1.open("POST","ajax/modificarPresupuesto2.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarPresupuesto2(presupuesto1);
			peticionUnica1.send(query_string);						
		}
}

function consultaModificarPresupuesto2(presupuesto1)
{	
	var consulta = "accion=modificarRegistro2";
	
	consulta+="&numPresupuesto="+presupuesto1;
	
	if (document.getElementById(presupuesto1+"_otBajada").checked == true)
	{
		consulta+="&otBajada=1";
	}
	else
	{
		consulta+="&otBajada=0";
	}
	
	if (document.getElementById(presupuesto1+"_otAbierta").checked == true)
	{
		consulta+="&otAbierta=1";
	}
	else
	{
		consulta+="&otAbierta=0";
	}	
	
	var temporalFin = document.getElementById(presupuesto1+"_fechaTerminado").value;
	
	if (temporalFin==null || temporalFin=="null" || temporalFin=="")
	{
		consulta+="&fechaTerminado=";
	}
	else
	{
		var anioFin = temporalFin.substr(0, temporalFin.indexOf('-'));
		var mesFin = temporalFin.substr(temporalFin.indexOf('-')+1,temporalFin.length  - temporalFin.lastIndexOf('-') -1);
		var diaFin = temporalFin.substr(temporalFin.lastIndexOf('-')+1);

		consulta+="&fechaTerminado=" + diaFin + "/" + mesFin + "/" + anioFin;	
	}	
	
	return consulta;	
}

function mostrarModificarPresupuesto2()
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
				alert("Presupuesto Modificado");				
			}
			peticionUnica1=null;
		}
	}						
}




function comprobarFechasAcepYComp(id)//js_presupuestosListado
{
	if (document.getElementById(id).checked)
	{
		//si no hay fecha de compromiso y fecha de aceptacion se desactiva el check
		var numPresu = id.split("_")[0];
		mostrarDatosPresupuesto(numPresu);
		
		if (campo1==true || campo1 =="true")
		{
			
		}
		else
		{
			alert("Primero hay que introducir una fecha de compromiso y una fecha de aceptacion");
			document.getElementById(id).checked = false;
		}
	}
}

function mostrarDatosPresupuesto(numPresu)//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarMostrarDatosPresupuesto;
		peticionUnica1.open("POST","ajax/mostrarDatosPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaMostrarDatosPresupuesto(numPresu);
		peticionUnica1.send(query_string);
	}
}

function consultaMostrarDatosPresupuesto(numPresu)
{	
	var consulta = "accion=mostrarDatosPresupuesto";	
	
	consulta +="&numPresupuesto="+numPresu;
	
	return consulta;	
}

function mostrarMostrarDatosPresupuesto()
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
				
				campo1=true;
				
				if (datos.length<=0)
				{
					campo1 = "false";
				}
				else
				{										
						
					if (datos[0]["fechaAceptacion"]==null||datos[0]["fechaAceptacion"]=="null" ||datos[0]["fechaAceptacion"]=="")
					{
						if (datos[0]["fechaCompromiso"]==null ||datos[0]["fechaCompromiso"]=="null" ||datos[0]["fechaCompromiso"]=="")
						{
							campo1 = "false";
						}
					}					
				
				}
						
			}
			peticionUnica1=null;			
		}
	}						
}

function pasarDatosPresupuesto(presupuesto, fechaAceptacion,fechaCompromiso)//js_presupuestoListado
{
	
	if (fechaAceptacion!="" && document.getElementById("cambiarFechaAceptacion").value==0)
	{
		document.getElementById("fechaAceptacion").readOnly = true;
	}
	else
	{
		document.getElementById("fechaAceptacion").readOnly = false;
	}
	
	if (fechaCompromiso!="" && document.getElementById("cambiarFechaCompromiso").value==0)
	{
		document.getElementById("fechaCompromiso").readOnly = true;
	}
	else
	{
		document.getElementById("fechaCompromiso").readOnly = false;
	}	
	
	document.getElementById("numPresuModal").innerHTML = presupuesto;
	document.getElementById("fechaAceptacion").value = fechaAceptacion;
	document.getElementById("fechaCompromiso").value = fechaCompromiso;
		
}

function irAVerPresupuesto(presupuesto1)//js_presupuestoListado
{
	var presupuesto = presupuesto1.substr(0, presupuesto1.indexOf('_'));
	
	//window.location.href = "http://172.26.0.17:8080/gestionGrupocibeles/presupuestos_alta.php?presupuesto="+presupuesto;
	window.location.href = "presupuestos_alta.php?presupuesto="+presupuesto;
}




function gestionExportarExcelPresupuestos()
{
	document.getElementById("formExcelPresupuestosListado").submit();
}