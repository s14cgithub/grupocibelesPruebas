var peticionUnica1 = null;

var laCondicion="";

function buscarPF()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	//var fechaInicio = document.getElementById("buscarFechaInicio").value;
	//var fechaFin = document.getElementById("buscarFechaFin").value;
	
	//var clayma = document.getElementById("clienteOrigen").checked;
	
	
		
	condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";
	
	
	/*if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		var laFecha = fechaInicio.replace("/","-");
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];	
	
		var laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and t1.fecha>= '"+laFecha1+"'";
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		laFecha = fechaFin.replace("/","-");
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];	
	
		laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and t1.fecha <= '"+laFecha1+"'";
	}*/
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	
	cargarListadoPFpendientes();
	
}

function cargarListadoPFpendientes() //js_provisionFondosPendiente
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPFpendientes;
		peticionUnica1.open("POST","ajax/mostrarProvisionDeFondosTodos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPFpendientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPFpendientes()
{	
	var consulta = "accion=mostrarProvisionFondo";
	
	var campos = [
		'contador',
		'presupuesto',
		'clayma',
		'id',
		'codigo',
		'nombre_franqueo',
		'fechaCreacion',
		'tipoNombre',
		'importe',
		'formaPago',
		'tipo'	,
		'cobrada'	
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

var filtros = {
    	cobrada: 1,
		formaPago: ''
	};	
	
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 

	var filtrosLike = [];
	if (document.getElementById("buscarCampo").value != "fechaCreacion" && document.getElementById("buscarCampo").value != "fechaCobro" )
	{
		filtrosLike.push({
			campo: document.getElementById("buscarCampo").value,
			valor: document.getElementById("buscarTexto").value
		});
	}
	
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(filtrosLike));

	var order = [
		{
			campo: document.getElementById("ordenBuscar").value,
			dir: document.getElementById("ordenDesc").checked ? 'DESC' : 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	
	laCondicion = consulta;

	return consulta;
}

function mostrarCargarListadoPFpendientes()
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
				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
					contenido += '<th align="center">Presupuesto</th>';	
					contenido += '<th align="center">Clayma</th>';	
					contenido += '<th>Cliente</th>';
					contenido += '<th>Franqueo</th>';
					//contenido += '<th>campaña</th>';
					contenido += '<th>F. Creacion</th>';
					
					contenido += '<th>Tipo</th>';
					contenido += '<th>Importe</th>';
					contenido += '<th>Cobrada</th>';

					contenido += '<th>Fecha</th>';
					contenido += '<th>Forma de Pago</th>';
					contenido += '<th></th>';
					contenido += '<th></th>';

					contenido += '</tr>';
				
				var contador = 0;	
				
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
					
					if (datos[contador]["contador"]=="null" || datos[contador]["contador"]==null || datos[contador]["contador"]=='')
					{
						contenido += '<td align="center" id="'+datos[contador]["id"]+'_presupuesto" style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</td>';
					}
					else
					{
						contenido += '<td align="center" id="'+datos[contador]["id"]+'_presupuesto" style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+" - "+datos[contador]["contador"]+'</td>';
					}
					
					if (datos[contador]["clayma"]=="1")
					{
						//contenido += '<td><input id="'+datos[contador]["id"]+'_clayma" type="checkbox" value="'+datos[contador]["clayma"]+'" onclick="gestionarPFclayma('+datos[contador]["id"]+')" checked></input></td>';
						contenido += '<td><input id="'+datos[contador]["id"]+'_clayma" type="checkbox" value="'+datos[contador]["clayma"]+'" onclick="return false;" checked></td>';
					}
					else
					{
						//contenido += '<td><input id="'+datos[contador]["id"]+'_clayma" type="checkbox" value="'+datos[contador]["clayma"]+'" onchange="gestionarPFclayma('+datos[contador]["id"]+')" ></input></td>';
						contenido += '<td><input id="'+datos[contador]["id"]+'_clayma" type="checkbox" value="'+datos[contador]["clayma"]+'" onclick="return false;" ></input></td>';
					}
					
					contenido += '<td id="'+datos[contador]["id"]+'_codCliente">'+datos[contador]["codigo"]+'</td>';
					contenido += '<td id="'+datos[contador]["id"]+'_nomFranqueo">'+datos[contador]["nombre_franqueo"]+'</td>';					
					
					var dia = datos[contador]["fechaCreacion"]["date"].substr(8,2);
					var mes = datos[contador]["fechaCreacion"]["date"].substr(5,2);
					var anio = datos[contador]["fechaCreacion"]["date"].substr(0,4);
					
					contenido += '<td  style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';
					
					contenido += '<td id="'+datos[contador]["id"]+'_tipo">'+datos[contador]["tipoNombre"]+'</td>';
					//contenido += '<td id="'+datos[contador]["id"]+'_importe">'+datos[contador]["importe"]+'</td>';
					contenido += '<td><input type="number" id="'+datos[contador]["id"]+'_importe" value="'+datos[contador]["importe"]+'" style="text-align: right;"></input></td>';
					
					contenido += '<td><select name="'+datos[contador]["id"]+'_cobrada" id="'+datos[contador]["id"]+'_cobrada"></select></td>';
					
					//contenido += '<td><input type="date" id="'+datos[contador]["id"]+'_fechaCobrada" value="" onChange="gestionarDatosPFpendienteFecha(\''+datos[contador]["id"]+'\')" ></input></td>';
					
					contenido += '<td><input type="date" id="'+datos[contador]["id"]+'_fechaCobrada" value="" onFocus="gestionarAnio(\''+datos[contador]["id"]+'_fechaCobrada\')" ></input></td>';
					//onBlur="gestionFecha(\"'+datos[contador]["id"]+'_importe")" ></input></td>';
					
					contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_formaPago" value="'+datos[contador]["formaPago"]+'"  onChange="gestionarDatosPFpendienteFormaPago(\''+datos[contador]["id"]+'\')" style="width:100%"></input></td>';
					
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="'+datos[contador]["tipo"]+'" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarPFpendiente(\''+datos[contador]["id"]+'\')"></td>';
					
					contenido += '<td><input type="image"  value="'+datos[contador]["tipo"]+'" src="imagenes/imprimir.png" style="width:15px;"  onclick="mostrarImprimirPF('+datos[contador]["id"]+')"></td>';
										
					contenido += '</tr>'; 
					
					contador++;	
				}	
				
				document.getElementById("listadoPFpendientes").innerHTML = contenido;				
				
				contador = 0;

				while  (contador<datos.length)
				{ 					
					idInputListado = datos[contador]["id"]+'_cobrada';
					cargarTiposCobradas();

					document.getElementById(datos[contador]["id"]+'_cobrada').value = datos[contador]["cobrada"];					

					idInputListado = "";
					
					contador++;
				}
			}
			peticionUnica1=null;
			
		}
	}						
}

function gestionarPFclayma(id)
{
	
}

function guardarPFmanual()	//js_provisionFondosPendiente
{	
	if (document.getElementById("claymaModal").checked && document.getElementById("tipoModal").value != 3 )
	{
		alert("Con un cliente de Clayma, solo se puede elegir 'Descontar de Manipulados'");
	}
	else
	{
		peticionUnica1=null;
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGuardarPFmanual;
			peticionUnica1.open("POST","ajax/insertarProvisionDeFondos.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGuardarPFmanual();
			peticionUnica1.send(query_string);
		}	
	}
	
}



function consultaGuardarPFmanual()
{	

	var consulta = "accion=insertarPF";

	var datos = {	
		presupuesto: 'correoDiario',
		importe: document.getElementById("importeModal").value,
		tipo: document.getElementById("tipoModal").value,
		clayma: document.getElementById("claymaModal").checked == true ? 1: 0,
		concepto: document.getElementById("conceptoModal").value,
		idCliente: document.getElementById("clientesModal").value,
	}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;
	
}

function mostrarGuardarPFmanual()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="" || res.ok==false )
			{
				alert(res.error);				
			}			
			else
			{	
				
			  	document.getElementById("importeModal").value="";
				document.getElementById("conceptoModal").value="";
				
				//actualizarDatosPFmanuales();
				
				cargarListadoPFpendientes();
				
			}
			
			peticionUnica1=null;
		}
	}						
}




function cargarTiposCobradas()	//js_provisionFondosPendiente			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTiposCobradas;
		peticionUnica1.open("POST","ajax/mostrarTipoCobradas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTiposCobradas();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarTiposCobradas()
{	
	var consulta = "accion=cargarTipoCobradas";

	var campos = [
		'id',
		'cobrada'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [
		{
			campo: 'id', dir: 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	

	return consulta;	
}

function mostrarCargarTiposCobradas()
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
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["cobrada"]+'</option>';
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

function imprimirProvisionDeFondos_presupuesto2() //js_provisionfondosPendiente
{	
	if (document.getElementById("claymaProvisionFondoModal").innerHTML == "CLAYMA")
	{
		var datos = document.getElementById("numPresupuesto").innerHTML.split(' - ');
		document.getElementById("imprimirProvisionFondoNumPresupuestoClayma").value = datos[0];
		document.getElementById("imprimirProvisionFondoContadorClayma").value = datos[1];
	
		/*if (datos.length==1)
		{
			document.getElementById("imprimirProvisionFondoContadorClayma").value=0;
		}*/

		document.getElementById("formImprimirProvisionDeFondoClayma").submit();	
	}
	else
	{
		var datos = document.getElementById("numPresupuesto").innerHTML.split(' - ');
		document.getElementById("imprimirProvisionFondoNumPresupuesto").value = datos[0];
		document.getElementById("imprimirProvisionFondoContador").value = datos[1];
	
		document.getElementById("formImprimirProvisionDeFondo").submit();	
	}
}

function imprimirPagoACuenta_presupuesto2() //js_provisionfondosPendiente
{
	if (document.getElementById("claymaProvisionFondoModal").innerHTML == "CLAYMA")
	{
		var datos = document.getElementById("numPresupuesto").innerHTML.split(' - ');
		document.getElementById("imprimirPagoACuentaNumPresupuestoClayma").value = datos[0];
		document.getElementById("imprimirPagoACuentaNumPresupuestoContadorClayma").value = datos[1];

		if (datos.length==1)
		{
			document.getElementById("imprimirPagoACuentaNumPresupuestoContadorClayma").value=0;
		}



		document.getElementById("formImprimirPagoACuentaClayma").submit();	
	}
	else
	{	
		var datos = document.getElementById("numPresupuesto").innerHTML.split(' - ');
		document.getElementById("imprimirPagoACuentaNumPresupuesto").value = datos[0];
		document.getElementById("imprimirPagoACuentaNumPresupuestoContador").value = datos[1];
		
		


		document.getElementById("formImprimirPagoACuenta").submit();	
	}
}


function cambiarClienteEnPFClayma()//js_provisionFondosPendiente
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCambiarClienteEnPFClayma;
		peticionUnica1.open("POST","ajax/cambiarClienteEnPFClayma.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarClienteEnPFClayma();
		peticionUnica1.send(query_string);
	}
}

function consultaCambiarClienteEnPFClayma()
{	
	var consulta = "accion=cambiarClienteEnPFClayma";	
	
	consulta += "&numPresupuesto=" + document.getElementById("presupuestoCambioModal").innerHTML;	
	consulta += "&idCliente=" + document.getElementById("clientes").value;
	
	var valor="false";
	if (document.getElementById("clientes1").innerHTML=="Clayma")
	{
		valor="true";
	}
	
	consulta += "&clayma=" + valor;	
	
	return consulta;	
}

function mostrarCambiarClienteEnPFClayma()
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
				cargarListadoPFpendientes();
				$("#cambioClaymaCibelesModal").modal('hide');				
			}			
		}
		peticionUnica1 = null;
	}						
}

function mostrarImprimirPF(idPF) //js_provisionFondosPendientes
{
	document.getElementById("numPresupuesto").innerHTML =   document.getElementById(idPF+"_presupuesto").innerHTML;
	document.getElementById("clienteProvisionFondoModal").innerHTML = document.getElementById(idPF+"_codCliente").innerHTML + " - " + document.getElementById(idPF + "_nomFranqueo").innerHTML;
	document.getElementById("importeProvisionFondoModal").innerHTML = document.getElementById(idPF+"_importe").value;
	
	if(document.getElementById(idPF+"_clayma").checked)
	{
		document.getElementById("claymaProvisionFondoModal").innerHTML = "CLAYMA";
	}
	else
	{
		document.getElementById("claymaProvisionFondoModal").innerHTML = "CIBELES";
	}
	
	
	
	$("#imprimirProvisionFondoModal").modal('show');
}

function modificarPFpendiente(id) //js_provisonFondosPendiente
{	
	
	/*if (document.getElementById(id+"_cobrada").value==1)//1==NO
	{
		alert("El campo 'Cobrada' debe tener valor distinto a 'No'");
		document.getElementById(id+"_cobrada").focus();
	}
	else*/ 
	
	if (document.getElementById(id+"_cobrada").value!=2) // 2==Si 
	{
		modificarPFpendiente2(id);
	}
	else if (document.getElementById(id+"_fechaCobrada").value==""||document.getElementById(id+"_fechaCobrada").value==null) 
	{
		alert("Rellenar la fecha de cobro");
		document.getElementById(id+"_fechaCobrada").focus();		
	}
	else if (document.getElementById(id+"_formaPago").value==""||document.getElementById(id+"_formaPago").value==null)
	{
		alert("Rellenar la Forma de Pago");
		document.getElementById(id+"_formaPago").focus();		
	}
	else //"sí" esta seleccionado
	{		
		
		if (document.getElementById(id+"_modificar").value==1 ||document.getElementById(id+"_modificar").value==2)	 //tipo: 1:Fija; 2: Descontar Franqueo; 3: Descontar de Manipulado	
		{
			modificarSaldo(id);
			//insertarMovimientoPF()	//se crea un registro en movimiento de fondos.		
			//modificarDatosPFenCliente(codigoCliente, fecha, importe, id);  //se modifica el saldo en el cliente
		}
		if (document.getElementById(id+"_modificar").value==1)		
		{		
			guardarImporteEnClienteFac(id,codigoCliente, importe);  
		}		
		
		modificarPFpendiente2(id); //se guarda la forma de pago, importe, etc
		
	}
}

function modificarSaldo(id)		
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarSaldo;
		peticionUnica1.open("POST","ajax/modificarSaldo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarSaldo(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarSaldo(id)
{	
	var consulta = "accion=modificarSaldo";	
	
	var fecha = document.getElementById(id + "_fechaCobrada").value;

	if (fecha=="" || fecha==null)
	{
		fecha = "";
	}
	else
	{	
		var temporal = fecha;

		var anio = temporal.substr(0, temporal.indexOf('-'));
		var mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
		var dia = temporal.substr(temporal.lastIndexOf('-')+1);

		fecha = dia + "-" + mes + "-" + anio;
	}
	var datos = {		
		codigoCliente: document.getElementById(id+"_codCliente").innerHTML,	
		fecha: fecha,	
		formaPago: document.getElementById(id+"_formaPago").value,
		importe: document.getElementById(id+"_importe").value,
		clayma: document.getElementById(id+"_clayma").checked ? 1 : 0,
		informacionCuadre: 'pantalla: provision de fondos pendientes',
		presupuesto: document.getElementById(id+"_presupuesto").innerHTML
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));		
	
	return consulta;
	
}

function mostrarModificarSaldo()
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
			}
			peticionUnica1=null;
		}
	}						
}





function guardarImporteEnClienteFac(id)
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGuardarImporteEnClienteFac;
		if (document.getElementById(id + "_clayma").checked==true)
		{
			peticionUnica1.open("POST","ajax/modificarClienteClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/modificarCliente.php",false);
		}		
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGuardarImporteEnClienteFac(id);
		peticionUnica1.send(query_string);
	}
}

function consultaGuardarImporteEnClienteFac(id)
{	
	var consulta = "accion=modificarCliente";	

	var datos = {			
		fac_pfFijaImporte: document.getElementById(id+"_importe").value == "" ? 0 : document.getElementById(id+"_importe").value,
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	

	var filtros = {
    	codigo: document.getElementById(id+"_codCliente").innerHTML
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	

}

function mostrarGuardarImporteEnClienteFac()
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
			}
			peticionUnica1=null;
		}
	}						
}


function modificarPFpendiente2(id)	//js_provisionFondosPendiente			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarPFpendientes;
		peticionUnica1.open("POST","ajax/modificarProvisionFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarPFpendientes(id);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarPFpendientes(id)
{
	var consulta = "accion=modificarProvisionFondo";
	var fecha = "";
	if (document.getElementById(id+"_fechaCobrada").value=="" || document.getElementById(id+"_fechaCobrada").value==null)
	{
		fecha = "";
	}
	else
	{		
		var temporal = document.getElementById(id+"_fechaCobrada").value;	
	
		var anio = temporal.substr(0, temporal.indexOf('-'));
		var mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
		var dia = temporal.substr(temporal.lastIndexOf('-')+1);

		fecha =   dia + "/" + mes + "/" + anio;
	}
	
	var datos = {		
		cobrada: document.getElementById(id+"_cobrada").value,
		fechaCobro: fecha == "" ? null  : fecha,
		formaPago: document.getElementById(id+"_formaPago").value,
		importe: document.getElementById(id+"_importe").value,
		//presupuesto: document.getElementById(id+"_presupuesto").innerHTML.split("-")[0],
		//clayma: document.getElementById(id+"_clayma").checked ? 1 : 0

	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));		
	
	return consulta;

}


function mostrarModificarPFpendientes()
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
				cargarListadoPFpendientes();
			}
			peticionUnica1=null;				
		}
	}						
}

function gestionarAnio(campo) //js_provisionFondosPendiente
{
	if (document.getElementById(campo).value=="")
	{
		getDate(campo);
	}	
}

function getDate(campo)//js_provisionFondosPendiente
{
    var today = new Date();

	document.getElementById(campo).value = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
}

function gestionarDatosPFpendienteFormaPago(id) //js_provisionFondosPendiente
{
	/*if (document.getElementById(id+"_fechaCobrada").value!="" && document.getElementById(id+"_formaPago").value!="")
	{
		document.getElementById(id+"_cobrada").value=2;//2==Si
	}
	else if (document.getElementById(id+"_formaPago").value=="")
	{
		document.getElementById(id+"_cobrada").value=1;//1==No
	}	*/
}

function cargarClientesPF()
{
	if (document.getElementById("claymaModal").checked==true)
	{
		cargarClientesClayma('A','clientesModal');
	}
	else
	{
		cargarClientes('A','clientesModal');
	}
}



function cargarListadoClientes()
{	
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoClientes;
		if (document.getElementById("claymaModal").checked==true)
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}
		
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoClientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoClientes()
{	
	var consulta = "accion=cargarClientes";
	
	var campos = [
		'codigo',
		'nombre_empresa'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	activo: 1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var filtrosOperadores = [
			{
				campo1: 'codigo_saldo',
				operador: '=',
				campo2: 'codigo'
			}
		];

	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	
	var order = [
    	{ campo: 'nombre_empresa', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoClientes()
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
					
				var contador=0;
				var contenido="";						
				
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo"]+'">'+datos[contador]["nombre_empresa"]+'</option>';
					contador++;
				}
					
				document.getElementById("clientesModal").innerHTML = contenido;
				idInputListado = '';
										
				
			}						
			
			peticionUnica1=null;			
		}
	}						
}


