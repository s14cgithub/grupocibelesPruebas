var peticionUnica1 = null;
var laCondicion="";
var anioSeleccionado="";

function buscarPrefactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("buscarDesc").checked;
	
	
	if (campoAbuscar=="fechaTerminado" && textoAbuscar!="" && textoAbuscar.length==10 )
	{
		
		textoAbuscar = textoAbuscar.replace("/","-");
		var datos = textoAbuscar.split('-');
		var dia = datos[0];
		var mes = datos[1];
		var anio = datos[2];
		
		
		var textoAbuscarPOsterior = new Date(anio+"-"+mes+"-"+dia);
		textoAbuscarPOsterior.setDate(textoAbuscarPOsterior.getDate() + 1);
	
		var fechaPosterior = textoAbuscarPOsterior.getDate() + '-' + (textoAbuscarPOsterior.getMonth() + 1) + "-" + textoAbuscarPOsterior.getFullYear();
		condicion = " where "+campoAbuscar+" >= '" + textoAbuscar + "' and "+campoAbuscar+" < '" + fechaPosterior + "'";
	}
	else
	{	
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";
	}
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	
	cargarListadoFacturasSinEmitir();
	
}

function borrarDetallesTemporalPrefactura() //js_admemisionFacturaPendiente
{
	eliminarTotoFacturaDetalleTemporal();
	cargarListadoFacturasSinEmitir();
	arrayCombinaciones = [];
	verPresupuestosCombinados();
}

function eliminarTotoFacturaDetalleTemporal() //js_admemisionFacturaPendiente
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarTotoFacturaDetalleTemporal;
		peticionUnica1.open("POST","ajax/eliminarTotoFacturaDetalleTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarTotoFacturaDetalleTemporal();
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarTotoFacturaDetalleTemporal()
{	
	var consulta = "accion=eliminarTotoFacturaDetalleTemporal";	
	return consulta;	
}

function mostrarEliminarTotoFacturaDetalleTemporal()
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
			}
			peticionUnica1=null;			
		}
	}						
}

function cargarListadoFacturasSinEmitir()//js_admEmisionFacturaPendiente
{	
	peticionUnica1=crearComunicacion(peticionUnica1);//not peticionUnica

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoFacturasSinEmitir;
		peticionUnica1.open("POST","ajax/mostrarFacturasSinEmitir.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoFacturasSinEmitir();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoFacturasSinEmitir()
{	
	var consulta = "accion=mostrarFacturasSinEmitir";
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');	
	return consulta;	
}

function mostrarCargarListadoFacturasSinEmitir()
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
					contenido += '<th align="center">Presupuesto</th>';	
					contenido += '<th align="center">Origen</th>';	
					contenido += '<th>Cliente</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Terminado</th>';					
					contenido += '<th></th>';//entrar en pre-factura
					contenido += '<th>Combinar</th>';//combinados

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
					contenido += '<td align="center">'+datos[contador]["inicial"]+"-"+datos[contador]["presupuesto"]+'</td>';					
					
					if (datos[contador]["clayma"]==1)
					{
						contenido += '<td>Clayma</td>';							
					}
					else
					{
						contenido += '<td>Cibeles</td>';
					}
					
					contenido += '<td>'+datos[contador]["cliente"]+'</td>';		
					contenido += '<td>'+datos[contador]["campana"]+'</td>';					
					
					var dia = datos[contador]["fechaTerminado"]["date"].substr(8,2);
					var mes = datos[contador]["fechaTerminado"]["date"].substr(5,2);
					var anio = datos[contador]["fechaTerminado"]["date"].substr(0,4);					
					
					contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';					
					
					contenido += '<td><input type="image" value="" src="imagenes/prefactura.png" style="width:15px;" id="'+datos[contador]["presupuesto"]+'_prefactura"" onclick="irAprefactura('+datos[contador]["presupuesto"]+',\''+datos[contador]["inicial"]+'\')"></td>';					
					
					contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_combinado" onchange="gestionCombiacionesPresu(\''+datos[contador]["presupuesto"]+'\')"></input></td>';
					
					contenido += '</tr>';
					
					contador++;	
				}
				
				
				document.getElementById("listadoFacturasSinEmitir").innerHTML = contenido;
				
				verPresupuestosCombinados();
				
				
				
			}
			peticionUnica1=null;
			
		}
	}						
}

function verPresupuestosCombinados() //js_admEmisionFacturaPendiente
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerPresupuestosCombinados;
		peticionUnica1.open("POST","ajax/verPresupuestosCombinados.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerPresupuestosCombinados();
		peticionUnica1.send(query_string);
	}
}

function consultaVerPresupuestosCombinados()
{	
	var consulta = "accion=verPresupuestosCombinados";	
	return consulta;	
}

function mostrarVerPresupuestosCombinados()
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
				arrayCombinaciones = [];
				if (datos != "")
				{	
				
					var contador = 0;					
					
					while  (contador<datos.length)
					{						
						document.getElementById(datos[contador]["presupuesto"]+"_combinado").checked = true;
						
						arrayCombinaciones.push(datos[contador]["presupuesto"]);
						contador++;
					}		
				}
			}
			peticionUnica1=null;
			
			if (arrayCombinaciones.length>1)
			{
				document.getElementById("tablaBotonCombinar").style.visibility = "visible";
				document.getElementById("tablaBotonCombinar").style.display = "table-cell";
			}
			else
			{
				document.getElementById("tablaBotonCombinar").style.visibility = "hidden";			
				document.getElementById("tablaBotonCombinar").style.display = "none";
			}
		}
	}
}

function combinarPresupuestos() //js_admEmisionFacturaPendiente
{	
	if (arrayCombinaciones.length<=1)
	{
		alert("Seleccionar al menos dos presupuestos.");
	}
	else 
	{		
		verCombinacionesSiMismoCliente();
		
		if (booleano)
		{
			var presupuestos = "";
			var contador = 0;

			while (contador<arrayCombinaciones.length)
			{
				presupuestos += arrayCombinaciones[contador] + " - ";
				contador++;
			}

			presupuestos = presupuestos.substr(0,presupuestos.length-3); 
			document.getElementById("listadoPresupuestoModal").innerHTML  = presupuestos;		

			campo1 = presupuestos;

			$("#combinarPresupuestosModal").modal('show');
		}		
		
		booleano = false;		
	}
}

function verCombinacionesSiMismoCliente() //js_admEmisionFacturaPendiente
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerCombinacionesSiMismoCliente;
		peticionUnica1.open("POST","ajax/verCombinacionesSiMismoCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerCombinacionesSiMismoCliente();
		peticionUnica1.send(query_string);
	}
}

function consultaVerCombinacionesSiMismoCliente()
{	
	var consulta = "accion=verCombinacionesSiMismoCliente";	
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	return consulta;	
}

function mostrarVerCombinacionesSiMismoCliente()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			booleano = true;
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else
			{	
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				if (datos.length>1)				
				{
					var contador = 0;
					
					var contenido = "SE HA SELECCIONADO VARIOS CLIENTES:\n\n";
				
					while  (contador<datos.length)
					{						
						contenido += datos[contador]["clientes"] + " - " +datos[contador]["nombre_empresa"] + "\n" ;
						contador++;
					}
					
					booleano = false;
					alert(contenido);					
				}
			}
			peticionUnica1=null;			
		}
	}						
}


function imprimirFacturaCombinadaPrevisualizar() //js_admEmisionFacturaPendiente
{
	verSiHayDatosCombinacionPrefactura();
	if (booleano)
	{
		alert("Hay que guardar primero los datos del Cliente");
	}
	else
	{
		if (document.getElementById("clienteOrigen").checked)
		{
			document.getElementById("imprimirNumPresupuestoClaymaPrevisualizacion").value = campo1;			
			document.getElementById("imprimirCombinadoSumatorioClaymaPrevisualizacion").value = document.getElementById("sumatorioModal").checked;
			
			campo1 = "";
			
			document.getElementById("formImprimirFacturaClaymaPrevisualizacion").submit();
		}
		else
		{
			document.getElementById("imprimirNumPresupuestoPrevisualizacion").value = campo1;			
			document.getElementById("imprimirCombinadoSumatorioPrevisualizacion").value = document.getElementById("sumatorioModal").checked;
			
			campo1 = "";
			
			document.getElementById("formImprimirFacturaPrevisualizacion").submit();
		}
	}
}

function verSiHayDatosCombinacionPrefactura()//datos en la cabecera //js_admEmisionFacturaPendiente
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiHayDatosCombinacionPrefactura;
		peticionUnica1.open("POST","ajax/verSiHayDatosCombinacionPrefactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiHayDatosCombinacionPrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaVerSiHayDatosCombinacionPrefactura()
{	
	var consulta = "accion=verSiHayDatosCombinacionPrefactura";	
	return consulta;	
}

function mostrarVerSiHayDatosCombinacionPrefactura()
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
				booleano = false;
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
			
				if (datos.length<=0)
				{					
					booleano = true;
				}				
			}
			peticionUnica1=null;	
		}
	}						
}

function verDatosPresupuestosCombinados() //js_admEmisionFacturaPendiente
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosPresupuestosCombinados;
		
		if (document.getElementById("clienteOrigen").checked)
		{
			peticionUnica1.open("POST","ajax/verDatosPresupuestosCombinacionClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/verDatosPresupuestosCombinacion.php",false);
		}		
		
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosPresupuestosCombinados();
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosPresupuestosCombinados()
{	
	var consulta = "accion=verDatosPresupuestosCombinados";
	
	consulta += "&presupuestos="+campo1;
	consulta += "&combinadoSumatorio=" + document.getElementById("sumatorioModal").checked;
	campo1 = "";
	return consulta;	
}

function mostrarVerDatosPresupuestosCombinados()
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
				//var numFactura = peticionUnica1.responseText;
				var datos = peticionUnica1.responseText.split("||||");
				var numFactura = datos[0];
				anioSeleccionado = datos[1];
				
				
				if (document.getElementById("clienteOrigen").checked)
				{
					irAImprimirFacturaClayma(numFactura,anioSeleccionado);	
				}
				else
				{
					irAImprimirFactura(numFactura,anioSeleccionado);	
				}				
				
				location.href='admEmisionFacturasPendientes.php';				
			}
			peticionUnica1=null;
		}
	}						
}






function irAprefactura(presupuesto,inicial) //js_admEmisionFacturaPendiente
{ 
	document.getElementById("preFacturaInicialComercial").value = inicial;
	document.getElementById("preFacturaPresupuesto").value = presupuesto;	
	
	if (arrayCombinaciones.includes(presupuesto.toString())==true)
	{
		document.getElementById("preFacturaCombinacion").value = 2 //combinado
	}
	else
	{
		document.getElementById("preFacturaCombinacion").value = 1 // normal
	}

	document.getElementById("busquedaPantallaAnterior").value = document.getElementById("buscarCampo").value + "|||" + document.getElementById("buscarTexto").value + "|||" + 
		document.getElementById("ordenBuscar").value + "|||" + document.getElementById("buscarDesc").checked;	
	
	document.getElementById("formIrAprefactura").submit();
}


function gestionCombiacionesPresu(numPresupuesto) //js_admEmisionFacturaPendiente
{
	if (document.getElementById(numPresupuesto+"_combinado").checked==true)
	{
		arrayCombinaciones.push(numPresupuesto);
		document.getElementById(numPresupuesto+"_prefactura").click();
	}
	else
	{
		var seguir = true;
		var contador=0;
		while (seguir==true && contador<arrayCombinaciones.length)
		{
			if (arrayCombinaciones[contador]==numPresupuesto)
			{
				arrayCombinaciones.splice(contador, 1); //borra el presupuesto del array
				seguir = false;
				
			}
			contador++;			
		}
		eliminarPresupuestoDeFacturaDetalleTemporal(numPresupuesto);
		eliminarPresupuestoDeFacturaTemporal(numPresupuesto);
	}
	
	if (arrayCombinaciones.length>1)
	{
		document.getElementById("tablaBotonCombinar").style.visibility = "visible";
		document.getElementById("tablaBotonCombinar").style.display = "table-cell";
	}
	else
	{
		document.getElementById("tablaBotonCombinar").style.visibility = "hidden";			
		document.getElementById("tablaBotonCombinar").style.display = "none";
	}	
}



//No borrar
/* 
function gestionEmitirFacturasMasivo() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGestionEmitirFacturasMasivo;		
	
		peticionUnica1.open("POST","ajax/emitirPrefacturasMasivas.php",false);			
		
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultarGestionEmitirFacturasMasivo();
		peticionUnica1.send(query_string);
	}
}

function consultarGestionEmitirFacturasMasivo()
{	
	var consulta = "accion=prefacturasMasivas";
	
	consulta += "&fecha=" + document.getElementById("fechaTerminadoMensualModal_masivo").value;
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;	
	return consulta;	
}

function mostrarGestionEmitirFacturasMasivo()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.includes("Error"))
			{
				alert(peticionUnica1.responseText);
			}
			else
			{
				cargarListadoFacturasSinEmitir();
				$("#facturasMasivasModal").modal('hide');
				alert("Finalizado");
			}
			peticionUnica1=null;
		}
	}						
}
*/

