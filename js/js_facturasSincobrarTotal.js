var peticionUnica1 = null;
var claymaG1=false;
var guardarBusqueda = "";
var arrayAgrupamiento = [];
var refrescar = true;
var busquedaFiltros = {};
var busquedaFiltrosLike = [];
var busquedaFiltrosOperadores = [];
var busquedaOrder = [];

function listadoFacturasPendientesTotal() //js_facturaSinCobrar
{	
	arrayAgrupamiento = [];
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	guardarBusqueda = "buscarCampo=" + campoAbuscar + "|buscarTexto=" + textoAbuscar + "|ordenBuscar=" + orden + "|ordenDesc=" + desc + "|fechaInicio=" + fechaInicio + "|fechaFin=" + fechaFin + "|origen=" + document.getElementById("buscarPorOrigen").value + "|domiciliada=" + document.getElementById("domiciliada").checked;

	busquedaFiltros = {};
	busquedaFiltrosLike = [];
	busquedaFiltrosOperadores = [];

	busquedaFiltros.sinFormaPago = 1;

	if (campoAbuscar == "codigo_saldo" && textoAbuscar != "")
	{
		busquedaFiltros.codigo_saldo = textoAbuscar;
	}
	else if (textoAbuscar != "")
	{
		busquedaFiltrosLike.push({campo: campoAbuscar, valor: textoAbuscar});
	}

	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		busquedaFiltrosOperadores.push({campo1: 'fecha', valor: fechaInicio, operador: '>='});
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		busquedaFiltrosOperadores.push({campo1: 'fecha', valor: fechaFin, operador: '<='});
	}
	
	
	if (document.getElementById("domiciliada").checked==true)
	{
		busquedaFiltros.domiciliada = 1;
	}
	
	if (document.getElementById("buscarPorOrigen").value!="todos")
	{
		busquedaFiltros.origen2 = document.getElementById("buscarPorOrigen").value;
	}
	
	busquedaOrder = [{campo: orden, dir: desc ? 'DESC' : 'ASC'}];

	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturasPendientesTotal;
		peticionUnica1.open("POST","ajax/cargarFacturasCibelesClaymaCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturasPendientesTotal();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturasPendientesTotal()
{	
	var consulta = "accion=cargarFacturasCibelesClaymaCorreos";

	var campos = ['origen','origen2','numeroFacturaCompleto','idCliente','codigo_saldo','cliente','importe','aPagar','fecha'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(busquedaFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(busquedaFiltrosOperadores));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(busquedaFiltrosLike));
	consulta += "&order=" + encodeURIComponent(JSON.stringify(busquedaOrder));

	consulta += "&pantallaDeOrigen=js_facturasSinCobrarTotal.js";
	consulta += "&guardarBusqueda=" + encodeURIComponent(guardarBusqueda);
	guardarBusqueda = "";
	
	
	return consulta;	
}

function mostrarListadoFacturasPendientesTotal()
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
				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=3 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioApagar">aaa</td><td style="border:none !important;"></td><td style="border:none !important; text-align:right;">Dar de baja varias a la vez:</td><td style="border:none !important;"><input type="image" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendienteMasivo2()"></td></tr>';
				
				
				
				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
					contenido += '<th align="center">Origen</th>';	
					contenido += '<th align="center">Factura</th>';	
					contenido += '<th align="center">Codigo Saldo</th>';	
					contenido += '<th>Cliente</th>';					
					contenido += '<th>Total</th>';
					contenido += '<th>Total a Pagar</th>';
					contenido += '<th>Fecha</th>';					
					contenido += '<th>Forma de Pago</th>';
					contenido += '<th></th>';

					contenido += '</tr>';
				
				var contador = 0;	
				var contraste = "";
				var mandarFoco="";
				while  (contador<datos.length)
				{  //Number(n).toLocaleString('es');
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					var numFactura = datos[contador]["numeroFacturaCompleto"];
					
					if (contador%2==0)
					{
						contraste = "";
					}
					else
					{						
						contraste = ' class="tablaContenidoColor" ';
					}
					
					contenido += '<tr ' + contraste + '>';
					
					//Origen: Abono, Cibles, Clayma, Correos
					if (datos[contador]["origen"]=="ABONO")
					{
						contenido += '<td align="center"  style="background: #FF0000" title="ABONO">'+datos[contador]["origen"]+'</td>';
					}
					else if (datos[contador]["origen2"]=="CIBELES")
					{
						contenido += '<td align="center"  style="background:green" title="Cibeles">'+datos[contador]["origen"]+'</td>';
					}
					else if (datos[contador]["origen2"]=="CLAYMA")
					{
						contenido += '<td align="center"  style="background: #B87240" title="Clayma">'+datos[contador]["origen"]+'</td>';
					}					
					else
					{
						contenido += '<td align="center">'+datos[contador]["origen"]+'</td>';
						
					}
					

					contenido += '<td align="right" id="'+numFactura+'_factura">'+numFactura+'</td>';
					
					contenido += '<td id="'+numFactura+'_idCliente" style="visibility: hidden;display: none;">'+datos[contador]["idCliente"]+'</td>';
					
					
					contenido += '<td  align="center" id="'+numFactura+'_codSaldo">'+datos[contador]["codigo_saldo"]+'</td>';
					
					contenido += '<td  id="'+numFactura+'_Cliente">'+datos[contador]["cliente"]+'</td>';
					
					contenido += '<td align="right"><span style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</span></td>';
					contenido += '<td align="right"><span style="overflow:hidden; white-space: nowrap;" id="'+numFactura+'_aPagar">'+Number(datos[contador]["aPagar"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</span></td>';
					
					
					
					contenido += '<td><span style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</span></td>';		
					
					//forma de pago
					contenido += '<td><input type="text" id="'+numFactura+'_formaPago_'+datos[contador]["origen2"]+'" value="" onblur="modificarFacturaPendienteMasivo(\''+numFactura+'_formaPago_'+datos[contador]["origen2"]+'\')"></input></td>';
					
					if (contador==0)
					{
						mandarFoco = numFactura+'_formaPago_'+datos[contador]["origen2"];
					}
					

					//modificar
					if (datos[contador]["origen"]=='CORREOS')
					{
						contenido += '<td><input type="image" id="'+numFactura+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaCorreospendienteDesdeTotal(\''+numFactura+'\')"></td>';
					}
					else
					{
						contenido += '<td><input type="image" id="'+numFactura+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendiente(\''+numFactura+'\',\''+datos[contador]["origen2"]+'\')"></td>';
					}

					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listadoFacturasPendientesTotal").innerHTML = contenido;				
				
				if (mandarFoco!="")
				{
					document.getElementById(mandarFoco).focus();
				}				
			}
			peticionUnica1 = null;
			listadoFacturasPendientesTotal_Sumatorio();
			
		}
	}						
}

function modificarFacturaPendienteMasivo(la_factura)
{
	

	if (document.getElementById(la_factura).value.length>3)
	{
		document.getElementById(la_factura).style.background = "green";
		
		var seguir = true;
		var contador=0;
		while (seguir==true && contador<arrayAgrupamiento.length)
		{
			if (arrayAgrupamiento[contador]==la_factura)
			{				
				seguir = false;
			}
			contador++;
		}
		if (seguir==true)
		{
			arrayAgrupamiento.push(la_factura);
		}
		
	}
	else
	{
		document.getElementById(la_factura).style.background = "white";

		var seguir = true;
		var contador=0;
		while (seguir==true && contador<arrayAgrupamiento.length)
		{
			if (arrayAgrupamiento[contador]==la_factura)
			{
				arrayAgrupamiento.splice(contador, 1); //borra la factura/abono del array
				seguir = false;
				
			}
			contador++;			
		}		
	}

}

function modificarFacturaPendienteMasivo2()
{
	if (arrayAgrupamiento.length>0)
	{
		if (confirm('¿Dar de bajar los registros cuyo campo "forma de pago" este relleno?')) 
		{
			refrescar = false;

			var contador=0;
			while (contador<arrayAgrupamiento.length)		
			{
				var datosArray = arrayAgrupamiento[contador].split('_formaPago_');
				var numeroFacturaCompleto = datosArray[0];
				var origen2 = datosArray[1];
				
				if (origen2=='CORREOS')
				{
					modificarFacturaCorreospendienteDesdeTotal(numeroFacturaCompleto);
				}
				else
				{
					modificarFacturaPendiente(numeroFacturaCompleto, origen2);
				}

				contador++;
			}

			
			refrescar = true;
			arrayAgrupamiento = [];
			listadoFacturasPendientesTotal();
			alert("Finalizado");
		}

		
	}
	else
	{
		alert('No hay nada para dar de baja');
	}
}


function listadoFacturasPendientesTotal_Sumatorio() //js_facturaSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturasPendientesTotal_Sumatorio;
		peticionUnica1.open("POST","ajax/cargarFacturasCibelesClaymaCorreos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturasPendientesTotal_Sumatorio();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturasPendientesTotal_Sumatorio()
{	
	var consulta = "accion=cargarFacturasCibelesClaymaCorreos";	

	var campos = ['aPagarSumatorio'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(busquedaFiltros));
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(busquedaFiltrosOperadores));
	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(busquedaFiltrosLike));
	
	return consulta;	
}

function mostrarListadoFacturasPendientesTotal_Sumatorio()
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
				
				
				document.getElementById("sumatorioApagar").innerHTML = "Total Pendiente: " + Number(datos[0]["aPagarSumatorio"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';				
								
			}
			peticionUnica1 = null;
			
			
		}
	}						
}



function modificarFacturaPendiente(numeroFacturaCompleto, origen2) 
{
	if (document.getElementById(numeroFacturaCompleto+"_formaPago_"+origen2).value.trim()=="")
	{
		alert("Introducir una Forma de pago");
		document.getElementById(numeroFacturaCompleto+"_formaPago_"+origen2).focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarFacturaPendiente;
			peticionUnica1.open("POST","ajax/modificarFacturaSinCobrarPendiente.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarFacturaPendiente(numeroFacturaCompleto, origen2);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarFacturaPendiente(numeroFacturaCompleto, origen2)
{	
	var consulta = "accion=modificarFacturaSinCobrarPendiente";
	consulta += "&numeroFacturaCompleto=" + encodeURIComponent(numeroFacturaCompleto);
	consulta += "&origen2=" + encodeURIComponent(origen2);
	consulta += "&formaPago=" + encodeURIComponent(document.getElementById(numeroFacturaCompleto+"_formaPago_"+origen2).value);
	
	return consulta;	
}

function mostrarModificarFacturaPendiente()
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
				if (refrescar == true)
				{
					listadoFacturasPendientesTotal();
				}				
			}
			peticionUnica1=null;			
		}
	}						
}





function gestionInformeFacSinCobrar() //js_facturasSinCobrarTotal
{

	document.getElementById("imprimirIdCliente").value  =  document.getElementById("buscarCliente").value;
	document.getElementById("imprimirFechaInicio").value  =  document.getElementById("buscarFechaInicio").value;
	document.getElementById("imprimirFechaFin").value  =  document.getElementById("buscarFechaFin").value;
	document.getElementById("imprimirOrden").value  =  document.getElementById("orden").value;
	document.getElementById("imprimirDesc").value  =  document.getElementById("ordenDesc").checked;
	document.getElementById("imprimirDomiciliada").value  =  document.getElementById("domiciliada").checked;
	
	document.getElementById("formImprimirInforme").submit();
}

function modificarFacturaCorreospendienteDesdeTotal(numeroFacturaCompleto) //js_facturasSinCobrarTotal
{
	
	if (document.getElementById(numeroFacturaCompleto+"_formaPago_CORREOS").value.trim()==""||document.getElementById(numeroFacturaCompleto+"_formaPago_CORREOS").value==null)
	{
		alert("Rellenar la Forma de Pago");
		document.getElementById(numeroFacturaCompleto+"_formaPago_CORREOS").focus();		
	}
	else
	{
	
		//var elImporte = 
		
		modificarSaldoFacturaCorreos(numeroFacturaCompleto);
		
		modificarFacturaPendiente(numeroFacturaCompleto, 'CORREOS');
		
	}
}

function modificarSaldoFacturaCorreos(numeroFacturaCompleto)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarSaldoFacturaCorreos;
		peticionUnica1.open("POST","ajax/modificarSaldo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarSaldoFacturaCorreos(numeroFacturaCompleto);
		peticionUnica1.send(query_string);						
	}
}

function consultaModificarSaldoFacturaCorreos(numeroFacturaCompleto)
{	
	var consulta = "accion=modificarSaldo";

	var codigoCliente = document.getElementById(numeroFacturaCompleto+"_idCliente").innerHTML;	
	var aPagar = document.getElementById(numeroFacturaCompleto+"_aPagar").innerHTML.replace(' €','').replace('.','').replace(',','.');
	var formaPago = document.getElementById(numeroFacturaCompleto+"_formaPago_CORREOS").value;
	var numeroOficial = document.getElementById(numeroFacturaCompleto+"_factura").innerHTML;

	var datos = {
		codigoCliente: codigoCliente,
		fecha: "",
		formaPago: formaPago,
		importe: aPagar,
		clayma: 0,
		informacionCuadre: 'pantalla: facturas sin cobrar - correos',
		presupuesto: numeroOficial
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarModificarSaldoFacturaCorreos()
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
			peticionUnica1=null;
		}
	}						
}

function insertarMovimientoAbonoCibeles(numAbono) //js_facturasSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarMovimientoAbonoCibeles;
		peticionUnica1.open("POST","ajax/insertarMovimientoAbono.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarMovimientoAbonoCibeles(numAbono);
		peticionUnica1.send(query_string);
	}
	
}

function consultaInsertarMovimientoAbonoCibeles(numAbono)
{	
	var consulta = "accion=insertarMovimientoAbono";
	consulta += "&abono="+numAbono;
	//consulta +="&clayma=" + document.getElementById("clienteOrigen").checked;
	
	if (claymaG1==false)
	{		
		consulta +="&clayma=false";
	}
	else if (claymaG1==true)
	{		
		consulta +="&clayma=true";
	}
		
	return consulta;	
}

function mostrarInsertarMovimientoAbonoCibeles()
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
				//alert(peticionUnica1.responseText);
			}
		}
	}						
}

function gestionImprimir()
{
	document.getElementById("imprimirFiltros").value = JSON.stringify(busquedaFiltros);
	document.getElementById("imprimirFiltrosLike").value = JSON.stringify(busquedaFiltrosLike);
	document.getElementById("imprimirFiltrosOperadores").value = JSON.stringify(busquedaFiltrosOperadores);
	document.getElementById("imprimirOrder").value = JSON.stringify(busquedaOrder);
	
	document.getElementById("formImprimirInforme").submit();
}

function gestionExportarExcelFacturaSinCobrar()
{	
	document.getElementById("exportarExcel_Filtros").value = JSON.stringify(busquedaFiltros);
	document.getElementById("exportarExcel_FiltrosLike").value = JSON.stringify(busquedaFiltrosLike);
	document.getElementById("exportarExcel_FiltrosOperadores").value = JSON.stringify(busquedaFiltrosOperadores);
	document.getElementById("exportarExcel_Order").value = JSON.stringify(busquedaOrder);
	
	document.getElementById("formExcelFacturasSinCobrar").submit();
}