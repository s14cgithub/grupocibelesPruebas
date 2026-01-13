var peticionUnica1 = null;
var seguir1 = false;
var numeroFacturaACopiar="";
var anioFacturaAcopiar="";
var numeroFacturaNueva="";
var anioFacturaNueva="";


function imprimirFacturasMensualesCD() //js_admFacturacio
{
	if (document.getElementById("numFacInicialModal").value=="")
	{
		alert("Introducir un numero de factura inicial");
		document.getElementById("numFacInicialModal").focus();
	}
	else if (document.getElementById("numFacFinalModal").value=="")
	{
		alert("Introducir un numero de factura final");
		document.getElementById("numFacFinalModal").focus();
	}
	else
	{
		document.getElementById("numFacInicio").value = document.getElementById("numFacInicialModal").value;
		document.getElementById("numFacFin").value = document.getElementById("numFacFinalModal").value;
		
		
		
		document.getElementById("numFacInicioRet").value = document.getElementById("numFacInicialModal").value;
		document.getElementById("numFacFinRet").value = document.getElementById("numFacFinalModal").value;
		
		document.getElementById("formImprimirFactMensualCDsinRetener").submit();
		
		document.getElementById("formImprimirFactMensualCDRetenidas").submit();			
	}
}

function informeFranqueo() //js_admFacturacion
{
	var anio1 = document.getElementById("fechaInicioInformeFranqueoModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeFranqueoModal").value.substr(0,4);
	
	
	
	if (document.getElementById("clienteInformeFranqueoModal").value == 0 )
	{
		alert("Elegir un Cliente");
		document.getElementById("clienteInformeFranqueoModal").focus();
	}
	else if (document.getElementById("fechaInicioInformeFranqueoModal").value == "")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioInformeFranqueoModal").focus();
	}
	else if (document.getElementById("fechaFinInformeFranqueoModal").value == "")
	{
		alert("Introducir una fecha de Fin");
		document.getElementById("fechaFinInformeFranqueoModal").focus();
	}
	else if (anio1!=anio2)
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}	
	else if ((document.getElementById("extensionInformeFranqueoModal").value.trim()!="" && document.getElementById("otInformeFranqueoModal").value.trim()!="")||document.getElementById("otInformeFranqueoModal").value.trim()=="0")
	{
		if (document.getElementById("otInformeFranqueoModal").value.trim()=="0")
		{
			document.getElementById("otInformeFranqueoModal").value="";
		}
		
		document.getElementById("imprimirClienteInformeDigitalClaymaFranqueoModal").value = document.getElementById("clienteInformeFranqueoModal").value;
		document.getElementById("imprimirFechaInicioInformeDigitalClaymaFranqueoModal").value = document.getElementById("fechaInicioInformeFranqueoModal").value;
		document.getElementById("imprimirFechaFinInformeDigitalClaymaFranqueoModal").value = document.getElementById("fechaFinInformeFranqueoModal").value;
		document.getElementById("imprimirSaldoFinInformeDigitalClaymaFranqueoModal").value = document.getElementById("saldoFinInformeFranqueoModal").checked;
		
		document.getElementById("imprimirCliente2InformeDigitalClaymaFranqueoModal").value = document.getElementById("cliente2InformeFranqueoModal").options[document.getElementById("cliente2InformeFranqueoModal").selectedIndex].text;
		
		document.getElementById("imprimirExtensionInformeDigitalClaymaFranqueoModal").value = document.getElementById("extensionInformeFranqueoModal").value;
		
		document.getElementById("imprimirOtInformeDigitalClaymaFranqueoModal").value = document.getElementById("otInformeFranqueoModal").value;
		
		document.getElementById("imprimirSinIvaInformeDigitalClaymaFranqueoModal").value = document.getElementById("sinIvaInformeFranqueoModal").checked;
		
		document.getElementById("formImprimirFranqueoDigitalClayma").submit();
		
	}
	else
	{	
		document.getElementById("imprimirClienteInformeFranqueoModal").value = document.getElementById("clienteInformeFranqueoModal").value;
		document.getElementById("imprimirFechaInicioInformeFranqueoModal").value = document.getElementById("fechaInicioInformeFranqueoModal").value;
		document.getElementById("imprimirFechaFinInformeFranqueoModal").value = document.getElementById("fechaFinInformeFranqueoModal").value;
		document.getElementById("imprimirSaldoFinInformeFranqueoModal").value = document.getElementById("saldoFinInformeFranqueoModal").checked;
		document.getElementById("imprimirSinIvaInformeFranqueoModal").value = document.getElementById("sinIvaInformeFranqueoModal").checked;
		document.getElementById("imprimirPorFechasInformeFranqueoModal").value = document.getElementById("separarFechasInformeFranqueoModal").checked;
		document.getElementById("imprimirCorreosDetalleModal").value = document.getElementById("saldoDetalleFacCoInformeFranqueoModal").checked;
		document.getElementById("imprimirOtInformeFranqueoModal1").value = document.getElementById("otInformeFranqueoModal").value;	
		document.getElementById("formImprimirFranqueo").submit();		
	}
}

function informeFranqueoDigitalClaymaExcel()
{
	
	
	var anio1 = document.getElementById("fechaInicioInformeFranqueoModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeFranqueoModal").value.substr(0,4);
	
	if (anio1 == anio2)
	{
		if (document.getElementById("clienteInformeFranqueoModal").value == 0 )
		{
			alert("Elegir un Cliente");
			document.getElementById("clienteInformeFranqueoModal").focus();
		}
		else if (document.getElementById("fechaInicioInformeFranqueoModal").value == "")
		{
			alert("Introducir una fecha de Inicio");
			document.getElementById("fechaInicioInformeFranqueoModal").focus();
		}
		else if (document.getElementById("fechaFinInformeFranqueoModal").value == "")
		{
			alert("Introducir una fecha de Fin");
			document.getElementById("fechaFinInformeFranqueoModal").focus();
		}
		else if (document.getElementById("extensionInformeFranqueoModal").value.trim()!="" && document.getElementById("otInformeFranqueoModal").value.trim()!="")
		{
			document.getElementById("imprimirClienteInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("clienteInformeFranqueoModal").value;
			document.getElementById("imprimirFechaInicioInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("fechaInicioInformeFranqueoModal").value;
			document.getElementById("imprimirFechaFinInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("fechaFinInformeFranqueoModal").value;
			document.getElementById("imprimirSaldoFinInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("saldoFinInformeFranqueoModal").checked;

			document.getElementById("imprimirCliente2InformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("cliente2InformeFranqueoModal").options[document.getElementById("cliente2InformeFranqueoModal").selectedIndex].text;

			document.getElementById("imprimirExtensionInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("extensionInformeFranqueoModal").value;

			document.getElementById("imprimirOtInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("otInformeFranqueoModal").value;

			document.getElementById("imprimirSinIvaInformeDigitalClaymaFranqueoModalExcel").value = document.getElementById("sinIvaInformeFranqueoModal").checked;

			document.getElementById("formExportarFranqueoExcel").submit();

		}
		else
		{	
			document.getElementById("imprimirClienteInformeFranqueoModalExcel").value = document.getElementById("clienteInformeFranqueoModal").value;
			document.getElementById("imprimirFechaInicioInformeFranqueoModalExcel").value = document.getElementById("fechaInicioInformeFranqueoModal").value;
			document.getElementById("imprimirFechaFinInformeFranqueoModalExcel").value = document.getElementById("fechaFinInformeFranqueoModal").value;
			document.getElementById("imprimirSaldoFinInformeFranqueoModalExcel").value = document.getElementById("saldoFinInformeFranqueoModal").checked;
			document.getElementById("imprimirSinIvaInformeFranqueoModalExcel").value = document.getElementById("sinIvaInformeFranqueoModal").checked;
			document.getElementById("formImprimirFranqueoExcelCibeles").submit();		
		}
	}
	else
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
		
}


function informeFranqueoSubCliente() //js_admFacturacion
{
	var anio1 = document.getElementById("fechaInicioInformeFranqueoSubClientesModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeFranqueoSubClientesModal").value.substr(0,4); 
	
	
	
	if (document.getElementById("clienteInformeFranqueoSubClientesModal").value == 0 )
	{
		alert("Elegir un SubCliente");
		document.getElementById("clienteInformeFranqueoSubClientesModal").focus();
	}
	else if (document.getElementById("fechaInicioInformeFranqueoSubClientesModal").value == "")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioInformeFranqueoSubClientesModal").focus();
	}
	else if (document.getElementById("fechaFinInformeFranqueoSubClientesModal").value == "")
	{
		alert("Introducir una fecha de Fin");
		document.getElementById("fechaFinInformeFranqueoSubClientesModal").focus();
	}
	else if (anio1!=anio2)
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
	else
	{	
		document.getElementById("imprimirClienteInformeFranqueoSubClienteModal").value = document.getElementById("clienteInformeFranqueoSubClientesModal").value;
		
		document.getElementById("imprimirFechaInicioInformeFranqueoSubClienteModal").value = document.getElementById("fechaInicioInformeFranqueoSubClientesModal").value;
		
		document.getElementById("imprimirFechaFinInformeFranqueoSubClienteModal").value = document.getElementById("fechaFinInformeFranqueoSubClientesModal").value;
		
		//document.getElementById("imprimirSaldoFinInformeFranqueoSubClienteModal").value = document.getElementById("saldoFinInformeFranqueoSubClientesModal").checked;
		
		document.getElementById("imprimirSinIvaFinInformeFranqueoSubClienteModal").value = document.getElementById("sinIvaInformeFranqueoSubClientesModal").checked;		
		
		document.getElementById("formImprimirFranqueoSubCliente").submit();		
	}
}

function informeFranqueoOT() //js_admFacturacion
{
	if (document.getElementById("clienteInformeFranqueoOTModal").value == "" )
	{
		alert("Introducir una OT.");
		document.getElementById("clienteInformeFranqueoOTModal").focus();
	}
	else if (document.getElementById("clienteInformeFranqueoOTModal").value.length<7)
	{
		alert("OT incorrecta");
		document.getElementById("clienteInformeFranqueoOTModal").focus();
	}	
	else
	{	
		document.getElementById("imprimirOTInformeFranqueoModal").value = document.getElementById("clienteInformeFranqueoOTModal").value;
		document.getElementById("imprimirOTSinIvaInformeFranqueoModal").value = document.getElementById("clienteInformeFranqueoSinIvaModal").checked;
		document.getElementById("imprimirOTSepararFechasinformeFranqueoModal").value = document.getElementById("otInformeFranqueoSepararFechaModal").checked;
		
		
		document.getElementById("imprimirOTInformeAnioFranqueoModal").value = document.getElementById("otInformeFranqueoOTModal").value;
		
		
		
		
		document.getElementById("formImprimirFranqueoOT").submit();
		
	}
}

function informeFranqueoConsumoPorProducto() 
{
	var anio1 = document.getElementById("fechaInicioInformeConsumoProductoModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeConsumoProductoModal").value.substr(0,4);
	
	
	
	if (document.getElementById("fechaInicioInformeConsumoProductoModal").value == "" )
	{
		alert("Introducir una fecha Inicio.");
		document.getElementById("fechaInicioInformeConsumoProductoModal").focus();
	}
	else if (document.getElementById("fechaFinInformeConsumoProductoModal").value == "")
	{
		alert("Introducir una fecha Fin.");
		document.getElementById("fechaFinInformeConsumoProductoModal").focus();
	}	
	else if (anio1!=anio2)
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
	else
	{		
		document.getElementById("fechaInicioConsumoProductoModal").value = document.getElementById("fechaInicioInformeConsumoProductoModal").value;
		document.getElementById("fechaFinConsumoProductoModal").value = document.getElementById("fechaFinInformeConsumoProductoModal").value;	
		
		document.getElementById("formImprimirInformeConsumoProducto").submit();
		
	}
}

function informeFranqueoConsumoPorProducto2() 
{
	var anio1 = document.getElementById("fechaInicioInformeConsumoProductoModal2").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeConsumoProductoModal2").value.substr(0,4);
	
	
	
	if (document.getElementById("fechaInicioInformeConsumoProductoModal2").value == "" )
	{
		alert("Introducir una fecha Inicio.");
		document.getElementById("fechaInicioInformeConsumoProductoModal2").focus();
	}
	else if (document.getElementById("fechaFinInformeConsumoProductoModal2").value == "")
	{
		alert("Introducir una fecha Fin.");
		document.getElementById("fechaFinInformeConsumoProductoModal2").focus();
	}	
	else if (anio1!=anio2)
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
	else
	{		
		document.getElementById("fechaInicioConsumoProductoModal2").value = document.getElementById("fechaInicioInformeConsumoProductoModal2").value;
		document.getElementById("fechaFinConsumoProductoModal2").value = document.getElementById("fechaFinInformeConsumoProductoModal2").value;	
		
		document.getElementById("formImprimirInformeConsumoProducto2").submit();
		
	}
}


function leerArchivoCorreosComparar() //js_admFacturacion
{	
	//if (document.getElementById("elArchivoComparar").value != "")
	
	var anio1 = document.getElementById("fechaInicioInformeFranqueoDifCibCorrModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeFranqueoFranqueoDifCibCorrModal").value.substr(0,4);
	if (anio1==anio2)
	{
		if (document.getElementById("fechaInicioInformeFranqueoDifCibCorrModal").value=='' || document.getElementById("fechaFinInformeFranqueoFranqueoDifCibCorrModal").value == '' )
		{
			alert("Introducir fechas");
		}
		else
		{		
			document.getElementById("imprimirInformeFranqueoDirencias_fechaInicio").value = document.getElementById("fechaInicioInformeFranqueoDifCibCorrModal").value;
			document.getElementById("imprimirInformeFranqueoDirencias_fechaFin").value = document.getElementById("fechaFinInformeFranqueoFranqueoDifCibCorrModal").value;		

			document.getElementById("formImprimirDiferenciasFranqueo").submit();

		}
	}	
	else
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
}

function cargarListadoClientesInformeFranqueo()
{	
	booleano=true;
	cargarClientes('B','clienteInformeFranqueoModal');
	cargarSubClientes('A','cliente2InformeFranqueoModal');
	
	$("#imprimirInformeFranqueoModal").modal('show');
}

function cargarListadoSubClientesInformeFranqueo()
{
	booleano=true;
	cargarSubClientes('','clienteInformeFranqueoSubClientesModal');
	$("#imprimirInformeFranqueoSubClientesModal").modal('show');
	
}

function botonFechaFinFacturacion1(estado1) //estado1=0: botonFechaFacMesAnterior | estado1=1:botonFechaFacMesActual
{
	
	document.getElementById("estadoModal").innerHTML = estado1;
	
	if (estado1==1)
	{
		document.getElementById("textoFechaFacturaModal").innerHTML = "<font color='#FF0004'>Las fechas de las nuevas facturas se están creando con la fecha del último día del mes anterior<br>__<br>Al dar Click a 'Cambiar': Las fechas de las nuevas facturas se crearán con la fecha actual</font>";
	}
	else
	{
		document.getElementById("textoFechaFacturaModal").innerHTML = "<font color='#FF0004'>Las fechas de las nuevas facturas se están creando con la fecha actual <br>__<br>Al dar Click a 'Cambiar': Las fechas de las nuevas facturas se crearán con la fecha del último día del mes anterior</font>";
	}
	
	$("#cambiarFechaFacturacionModal").modal('show');
}

function botonFechaFinFacturacion() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarBotonFechaFinFacturacion;
		peticionUnica1.open("POST","ajax/verEstadoFacturacionFinMes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBotonFechaFinFacturacion();
		peticionUnica1.send(query_string);
	}
}

function consultaBotonFechaFinFacturacion()
{	
	var consulta = "accion=verEstadoFacturacionFinMes";	
	
	consulta += "&estado="+document.getElementById("estadoModal").innerHTML; 
	
	return consulta;	
}

function mostrarBotonFechaFinFacturacion()
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
				var datos = peticionUnica1.responseText;
				if (datos.trim()!="")
				{
					if (datos.trim()=="0")
					{
						document.getElementById("botonFechaFacMesAnterior").style.visibility = "hidden";
						document.getElementById("botonFechaFacMesAnterior").style.display = "none";
						document.getElementById("botonFechaFacMesActual").style.visibility = "visible";
						document.getElementById("botonFechaFacMesActual").style.display = "inline";
					}
					else
					{
						document.getElementById("botonFechaFacMesActual").style.visibility = "hidden";
						document.getElementById("botonFechaFacMesActual").style.display = "none";
						document.getElementById("botonFechaFacMesAnterior").style.visibility = "visible";
						document.getElementById("botonFechaFacMesAnterior").style.display = "inline";
					}					
				}
				else
				{
					document.getElementById("estadoModal").innerHTML=3;
					botonFechaFinFacturacion();
					$("#cambiarFechaFacturacionModal").modal('hide');
				}
				
			}
			peticionUnica1=null;
		}
	}						
}

function botonFechaFinFacturacion1Clayma(estado1) 
{
	
	document.getElementById("estadoModalClayma").innerHTML = estado1;
	
	if (estado1==1)
	{
		document.getElementById("textoFechaFacturaModalClayma").innerHTML = "<font color='#FF0004'>Las fechas de las nuevas facturas se están creando con la fecha del último día del mes anterior<br>__<br>Al dar Click a 'Cambiar': Las fechas de las nuevas facturas se crearán con la fecha actual</font>";
	}
	else
	{
		document.getElementById("textoFechaFacturaModalClayma").innerHTML = "<font color='#FF0004'>Las fechas de las nuevas facturas se están creando con la fecha actual <br>__<br>Al dar Click a 'Cambiar': Las fechas de las nuevas facturas se crearán con la fecha del último día del mes anterior</font>";
	}
	
	$("#cambFechaFactModalClayma").modal('show');
}

function botonFechaFinFacturacionClayma() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarBotonFechaFinFacturacionClayma;
		peticionUnica1.open("POST","ajax/verEstadoFacturacionFinMesClayma.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBotonFechaFinFacturacionClayma();
		peticionUnica1.send(query_string);
	}
}

function consultaBotonFechaFinFacturacionClayma()
{	
	var consulta = "accion=verEstadoFacturacionFinMes";	
	
	consulta += "&estado="+document.getElementById("estadoModalClayma").innerHTML; 
	
	return consulta;	
}

function mostrarBotonFechaFinFacturacionClayma()
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
				var datos = peticionUnica1.responseText;
				if (datos!="")
				{
					if (datos=="0")
					{
						document.getElementById("botonFechaFacMesAnteriorClayma").style.visibility = "hidden";
						document.getElementById("botonFechaFacMesAnteriorClayma").style.display = "none";
						document.getElementById("botonFechaFacMesActualClayma").style.visibility = "visible";
						document.getElementById("botonFechaFacMesActualClayma").style.display = "inline";
					}
					else
					{
						document.getElementById("botonFechaFacMesActualClayma").style.visibility = "hidden";
						document.getElementById("botonFechaFacMesActualClayma").style.display = "none";
						document.getElementById("botonFechaFacMesAnteriorClayma").style.visibility = "visible";
						document.getElementById("botonFechaFacMesAnteriorClayma").style.display = "inline";
					}					
				}
				else
				{
					document.getElementById("estadoModalClayma").innerHTML=3;
					botonFechaFinFacturacionClayma();
					$("#cambFechaFactModalClayma").modal('hide');
				}
				
			}
			peticionUnica1=null;
		}
	}						
}

function gestionIrAFichaCliente()
{
	if (document.getElementById("origen1").checked==true)
	{
		location.href = 'clientes.php?clayma=0';
	}
	else
	{
		location.href = 'clientes.php?clayma=1';
	}
	
}

function informeFranqueoBonificaciones()
{
	var anio1 = document.getElementById("fechaInicioInformeBonificacionModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeBonificacionModal").value.substr(0,4);
	
	if (anio1==anio2)
	{
		document.getElementById("fechaInicioBon").value = document.getElementById("fechaInicioInformeBonificacionModal").value;		
		document.getElementById("fechaFinBon").value =document.getElementById("fechaFinInformeBonificacionModal").value;	
		document.getElementById("numeroClienteBon").value = 1085;


		document.getElementById("formImprimirBonificiones").submit();			
	}
	else
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
}



function borrarPreFacturasCorreos() //js_prefactura
{	
	
	if (confirm('¿Borrar las Pre-Facturas de Correos?')) 
	{
	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarBorrarPreFacturasCorreos;
			peticionUnica1.open("POST","ajax/eliminarPrefacturasCorreos.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaBorrarPreFacturasCorreos();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaBorrarPreFacturasCorreos()
{	
	var consulta = "accion=borrarPreFacturasCorreos";	
	
	return consulta;	
}

function mostrarBorrarPreFacturasCorreos()
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
			peticionUnica1=null;
		}
	}						
}




function cargarListadoClientesInformeFranqueoExtension()
{	
	booleano=true;
	cargarClientes('A','clienteInformeFranqueoExtensionModal');
	
	document.getElementById("clienteInformeFranqueoExtensionModal").value = 2823  ;
	
	$("#imprimirInformeFranqueoExtension").modal('show');
}

function informeFranqueoExtensiones() 
{
	
	var anio1 = document.getElementById("fechaInicioInformeFranqueoExtensionModal").value.substr(0,4);
	var anio2 = document.getElementById("fechaFinInformeFranqueoExtensionModal").value.substr(0,4); 
	
	
	
	
	if (document.getElementById("clienteInformeFranqueoExtensionModal").value == 0 )
	{
		alert("Elegir un Cliente");
		document.getElementById("clienteInformeFranqueoExtensionModal").focus();
	}
	else if (document.getElementById("fechaInicioInformeFranqueoExtensionModal").value == "")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioInformeFranqueoExtensionModal").focus();
	}
	else if (document.getElementById("fechaFinInformeFranqueoExtensionModal").value == "")
	{
		alert("Introducir una fecha de Fin");
		document.getElementById("fechaFinInformeFranqueoExtensionModal").focus();
	}
	else if (anio1!=anio2)
	{
		alert("El año de la fecha de inicio y el año de la fecha fin, debe ser el mismo");
	}
	else
	{	
		document.getElementById("numeroClienteExtension_form").value = document.getElementById("clienteInformeFranqueoExtensionModal").value;
		document.getElementById("fechaInicioExtension_form").value = document.getElementById("fechaInicioInformeFranqueoExtensionModal").value;
		document.getElementById("fechaFinExtension_form").value = document.getElementById("fechaFinInformeFranqueoExtensionModal").value;
		
		document.getElementById("formImprimirFranqueoExtensiones").submit();
		
		
		
	}
}

function cargarListadoClientesExcelFacturaTotal()
{
	booleano=true;
	cargarClientes('A','clienteExcelFacturasTotalModal');	
	
	$("#excelFacturasTotal").modal('show');
}

function informeExcelFacturasTotal() 
{
	if (document.getElementById("clienteExcelFacturasTotalModal").value == 0 )
	{
		alert("Elegir un Cliente");
		document.getElementById("clienteExcelFacturasTotalModal").focus();
	}
	else if (document.getElementById("fechaInicioExcelFacturasTotalModal").value == "")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioExcelFacturasTotalModal").focus();
	}
	else if (document.getElementById("fechaFinExcelFacturasTotalModal").value == "")
	{
		alert("Introducir una fecha de Fin");
		document.getElementById("fechaFinExcelFacturasTotalModal").focus();
	}	
	else
	{		
		document.getElementById("numeroClienteExcelFacTotal_form").value = document.getElementById("clienteExcelFacturasTotalModal").value;
		document.getElementById("fechaInicioExcelFacTotal_form").value = document.getElementById("fechaInicioExcelFacturasTotalModal").value;
		document.getElementById("fechaFinExcelFacTotal_form").value = document.getElementById("fechaFinExcelFacturasTotalModal").value;
		
		document.getElementById("formImprimirExcelFacTotal").submit();		
	}
}

function verTodoUnCliente()
{
	booleano=false;
	cargarClientes('B','clienteVerTodoModal');
	$("#verTodoDelClienteModal").modal('show');
}

function verTodoUnClienteClayma()
{
	if (document.getElementById("claymaVerTodoModal").checked==true)
	{
		cargarClientesClayma('A','clienteVerTodoModal');
	}
	else
	{
		cargarClientes('A','clienteVerTodoModal');
	}
}

function verTodoUnCliente2()
{
	document.getElementById("anio").value = document.getElementById("anioVerTodoModal").value;
	document.getElementById("clayma").value = document.getElementById("claymaVerTodoModal").checked;
	document.getElementById("idCliente").value = document.getElementById("clienteVerTodoModal").value;
		
	document.getElementById("formImprimirTodoUnCliente").submit();
}

function verFacturasEstadisticas()
{
	document.getElementById("anioFacturaEstd").value = document.getElementById("facturaEstadistica_anio").value;
	document.getElementById("ordenFacturaEstd").value = document.getElementById("facturaEstadistica_orden").value;
	
	if (document.getElementById("estadisticas_desc").checked==true)
	{
		document.getElementById("ordenFacturaEstd").value = document.getElementById("ordenFacturaEstd").value + " desc";
	}
	
	
	
	if (document.getElementById("origen1Est").checked==true)
	{
		document.getElementById("origenFacturaEstd").value="Cibeles";
	}
	else
	{
		document.getElementById("origenFacturaEstd").value="Clayma";
	}
	
	
	document.getElementById("formVerFacturasEstadisticas").submit();
}


function excelFacturasEstadisticas()
{
	document.getElementById("anioFacturaEstdExcel").value = document.getElementById("facturaEstadistica_anio").value;
	document.getElementById("ordenFacturaEstdExcel").value = document.getElementById("facturaEstadistica_orden").value;
	
	if (document.getElementById("estadisticas_desc").checked==true)
	{
		document.getElementById("ordenFacturaEstdExcel").value = document.getElementById("ordenFacturaEstdExcel").value + " desc";
	}
	
	if (document.getElementById("origen1Est").checked==true)
	{
		document.getElementById("origenFacturaEstdExcel").value="Cibeles";
	}
	else
	{
		document.getElementById("origenFacturaEstdExcel").value="Clayma";
	}
	
	
	document.getElementById("formExcelFacturasEstadisticas").submit();
}

function irAsituacionCliente()
{	
	document.getElementById("formImprimirSituacionCliente").submit();
}

function gestionActivarDetalle()
{
	if (document.getElementById("saldoFinInformeFranqueoModal").checked == true)
	{
		document.getElementById("groupSaldoDetalle").style.visibility = "visible";
	}
	else
	{
		document.getElementById("groupSaldoDetalle").style.visibility = "hidden";
	}
}




function gestionCrearMensualPorCopia()
{
	seguir1=false;	
	//ver si existe la factura y si es mensual
	comprobarNumFactura();
	
	if (seguir1==true)
	{
		copiarFacturaMensual();

		//hacer copia de la factura Mensual
		//hacer copia de la facturaDetalles
		//redirigir pagina prefacturaConNum.php
	}
	seguir1=false;
	
	
}

function comprobarNumFactura() 
{
	var numFacturaCopiar = document.getElementById("crearFacturaMensualModal_numFactura").value.replace('-','/');
	
	if (numFacturaCopiar.includes('/'))
	{
		var num_y_anio = numFacturaCopiar.split('/');
		if(num_y_anio.length==2)
		{
			numeroFacturaACopiar = num_y_anio[0];
			anioFacturaAcopiar = num_y_anio[1];

			peticionUnica1=crearComunicacion(peticionUnica1);
			if(peticionUnica1)
			{							
				peticionUnica1.onreadystatechange = mostrarComprobarNumFactura;
				peticionUnica1.open("POST","ajax/verDatosUnaFactura.php",false);
				peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
				var query_string = consultaComprobarNumFactura();
				peticionUnica1.send(query_string);
			}
		}
		else
		{
			alert("El numero de factura tiene un formato incorrecto.\nEjemplo: 1234/24");
		}
	}
	else
	{
		alert("El numero de factura debe contener el año");
	}
}

function consultaComprobarNumFactura()
{	
	var consulta = "accion=verDatosUnaFactura";	
	consulta += "&clayma=false";
	consulta += "&numeroFactura="+ numeroFacturaACopiar;
	var elAnio = parseInt(anioFacturaAcopiar);
	if (elAnio<2000)
	{
		elAnio += 2000;
	}
	consulta += "&anioSeleccionado=" + elAnio;
	
	return consulta;	
}

function mostrarComprobarNumFactura()
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
						alert("No existe ninguna factura con ese numero.\nComprueba de que este bien escrito.");						
					}
					else if (datos.length>1)
					{
						alert("Se ha encontrado más de un resultado.\nInformar al administrador");
					}
					else
					{
						if (datos[0]["presupuesto"]=="mensual" || datos[0]["presupuesto"]=="mensual_v")
						{
							seguir1 = true;
						}
						else
						{
							alert("La factura indicada no es mensual.\nHay que indicar un numero de factura que corresponda a una factura mensual");
						}					
					}
				}
			}
			peticionUnica1=null;
		}
	}						
}


function copiarFacturaMensual() 
{
	peticionUnica1=crearComunicacion(peticionUnica1);
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCopiarFacturaMensual;
		peticionUnica1.open("POST","ajax/copiarFacturaMensual.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCopiarFacturaMensual();
		peticionUnica1.send(query_string);
	}
		
}

function consultaCopiarFacturaMensual()
{	
	var consulta = "accion=copiarFacturaMensual";	
	consulta += "&clayma=false";
	consulta += "&numeroFactura="+ numeroFacturaACopiar;

	var elAnio = parseInt(anioFacturaAcopiar)+2000;
	if (elAnio<2000)
	{
		elAnio += 2000;
	}
	consulta += "&anioSeleccionado=" + elAnio;
	
	return consulta;	
}

function mostrarCopiarFacturaMensual()
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
				seguir1=false;
				numeroFacturaACopiar="";
				anioFacturaAcopiar="";
				var datos = peticionUnica1.responseText.split("||||");
				if (datos.length==2)
				{
					numeroFacturaNueva=datos[0];
					anioFacturaNueva = datos[1];

					var elAnio = parseInt(anioFacturaNueva)-2000;					
					document.getElementById("crearFacturaMensualModal_numFactura").value = "";
					$("#crearFacturaMensualModal").modal('hide');
					alert("Se a creado la factura con el numero: " + numeroFacturaNueva + "/" + elAnio);
					//seguir1 = true;
				}
				else
				{
					alert("Error: al copiar la Factura. Avisar al administrador.");
				}
				
				
			}
			peticionUnica1=null;
		}
	}						
}



/*function informeAgenteComercial()
{
	document.getElementById("fechaInicioAgenteComercial_form").value = document.getElementById("fechaInicioInformeAgenteComercialModal").value;
	document.getElementById("fechaFinAgenteComercial_form").value = document.getElementById("fechaFinInformeAgenteComercialModal").value;	
	
	document.getElementById("formImprimirAgenteComercialCliente").submit();
	
}*/