var peticionUnica1 = null;


function cargarListadoPresupuesto1()
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
	
	var campos =[
 		'cliente',
        'campana',
        'numeroFacturaCompleto',
		'numNoFactura',
        'clayma',
        'inicialComercial',
        'presupuesto',
        'fecha',
        'otBajada',
        'otAbierta',   
        'fechaAceptacion',
        'fechaCompromiso',
        'fechaTerminado',
        'activo',
        'nombreComercial',
        'telefonoComercial'
		
	];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {		
		fechaAceptacion: document.getElementById("buscarFechaAceptacion").value		
	};
	
	if (document.getElementById("busqBajada").checked == 1)
	{
		filtros['otBajada'] = 1;
	}
	if (document.getElementById("busqAbierta").checked == 1)
	{
		filtros['otAbierta'] = 1;
	}
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	
	consulta += "&pantallaOrigen=presupuestos_listado";
	consulta += "&meses=" + document.getElementById("buscarNumMeses").value;

	var filtrosLike = [
		{
			campo: document.getElementById("tipoBusquedaPresupuesto").checked ? 'presupuesto' : document.getElementById("tipoBusquedaCliente").checked ? 'cliente' : 'campana',
			valor: document.getElementById("buscarTexto").value
		}
	];

	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(filtrosLike));


	var order = [
		{
			campo: document.getElementById("columnas").value,
			dir: document.getElementById("ordenDescendiente").checked ? 'DESC' : 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));


	
	return consulta;	
}

function mostrarCargarListadoPresupuesto1()
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
					
					
					
					if (datos[contador]["numeroFacturaCompleto"] != null && datos[contador]["clayma"]==0 )
					{
						contenido += '<td align="right" style="background:green;" title="'+datos[contador]["numeroFacturaCompleto"]+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
					}
					else if (datos[contador]["numeroFacturaCompleto"] != null && datos[contador]["clayma"]==1 )
					{
						contenido += '<td align="right" style="background: #B87240" title="'+datos[contador]["numeroFacturaCompleto"]+'-Clayma"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
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

						if (datos[contador]["activo"]=="1" && permisos == 2)
						{
							contenido += '<td><input type="image" id="'+datos[contador]["presupuesto"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarPresupuesto2('+datos[contador]["presupuesto"]+')"></td>';	
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


						if (datos[contador]["activo"]=="1" && permisos == 2)
						{
							contenido += '<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderTrabajoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;" onClick="pasarDatosPresupuesto(\''+datos[contador]["presupuesto"]+'\',\''+fechaAceptacion+'\',\''+fechaCompromiso+'\')">OT</button></td>';	
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
			peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarPresupuesto2(presupuesto1);
			peticionUnica1.send(query_string);						
		}
}

function consultaModificarPresupuesto2(presupuesto1)
{	
	var consulta = "accion=modificarRegistro";
	
	var temporalFin = document.getElementById(presupuesto1+"_fechaTerminado").value;
	
	var fechaTerminado1 = "";

	if (temporalFin==null || temporalFin=="null" || temporalFin=="")
	{
		fechaTerminado1 = "";
	}
	else
	{
		var anioFin = temporalFin.substr(0, temporalFin.indexOf('-'));
		var mesFin = temporalFin.substr(temporalFin.indexOf('-')+1,temporalFin.length  - temporalFin.lastIndexOf('-') -1);
		var diaFin = temporalFin.substr(temporalFin.lastIndexOf('-')+1);

		fechaTerminado1 = diaFin + "/" + mesFin + "/" + anioFin;	
	}	


	var datos = {		
		otBajada: document.getElementById(presupuesto1+"_otBajada").checked == true ? 1 : 0,
		otAbierta: document.getElementById(presupuesto1+"_otAbierta").checked == true ? 1 : 0,
		fechaTerminado: fechaTerminado1				
	};

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	presupuesto: presupuesto1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	
	
	return consulta;

}

function mostrarModificarPresupuesto2()
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
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaMostrarDatosPresupuesto(numPresu);
		peticionUnica1.send(query_string);
	}
}

function consultaMostrarDatosPresupuesto(numPresu)
{	
	var consulta = "accion=cargarPresupuestos";	
	
	var campos = [
		'fechaAceptacion',
		'fechaCompromiso'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	/*
	var joins = [
		'tabla5',
		'tabla6'
	];
	

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));
*/

	var filtros = {
    	presupuesto: numPresu		
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	
	return consulta;	

	
}

function mostrarMostrarDatosPresupuesto()
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

function modificarPresupuesto3()
{
	if (document.getElementById("fechaAceptacion").value=="")
	{
		alert("Introducir fecha de Aceptacion");
		document.getElementById("fechaAceptacion").focus();
	}
	else if (document.getElementById("fechaCompromiso").value=="")
	{
		alert("Introducir fecha de Compromiso");
		document.getElementById("fechaCompromiso").focus();
	}
	else
	{		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarPresupuesto3;
			peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarPresupuesto3();
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaModificarPresupuesto3()
{	
	var consulta = "accion=modificarRegistro";	

	var paraFecha = document.getElementById("fechaAceptacion").value;
	var anioFin = "";
	var mesFin = "";
	var diaFin = "";

	var fechaAceptacion1 = "";
	
	if (paraFecha==null || paraFecha=="null" || paraFecha=="")
	{
		fechaAceptacion1 = "";
	}
	else
	{
		anioFin = paraFecha.substr(0, paraFecha.indexOf('-'));
		mesFin = paraFecha.substr(paraFecha.indexOf('-')+1,paraFecha.length  - paraFecha.lastIndexOf('-') -1);
		diaFin = paraFecha.substr(paraFecha.lastIndexOf('-')+1);

		fechaAceptacion1 = diaFin + "/" + mesFin + "/" + anioFin;	
	}

	paraFecha = document.getElementById("fechaCompromiso").value;
	var fechaCompromiso1 = "";
	
	if (paraFecha==null || paraFecha=="null" || paraFecha=="")
	{
		fechaCompromiso1 = "";
	}
	else
	{
		anioFin = paraFecha.substr(0, paraFecha.indexOf('-'));
		mesFin = paraFecha.substr(paraFecha.indexOf('-')+1,paraFecha.length  - paraFecha.lastIndexOf('-') -1);
		diaFin = paraFecha.substr(paraFecha.lastIndexOf('-')+1);

		fechaCompromiso1 =  diaFin + "/" + mesFin + "/" + anioFin;	
	}


	var datos = {		
		fechaAceptacion: fechaAceptacion1,
		fechaCompromiso: fechaCompromiso1			
	};

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	presupuesto: document.getElementById("numPresuModal").innerHTML
	};	
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	
	
	return consulta;	
}

function mostrarModificarPresupuesto3()
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
				peticionUnica1=null;
				//cargarListadoPresupuesto1();
				
				document.getElementById("imprimirNumOT").value =document.getElementById("numPresuModal").innerHTML;
				document.getElementById("formImprimirOT").submit();
				
			}
			peticionUnica1=null;
		}
	}						
}