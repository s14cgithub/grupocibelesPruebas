var peticionUnica1 = null;

var clienteInicial = null;
var anioSeleccionado="";
var busquedaPantallaAnterior="";
var unError = "";

function guardarValorClienteInicial()
{
	clienteInicial = document.getElementById("clientes").value;
}

function verProvisionPrefactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerProvisionPrefactura;
		peticionUnica1.open("POST","ajax/cargarProvisionDeFondos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerProvisionPrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaVerProvisionPrefactura()
{	
	var consulta = "accion=cargarProvisionDeFondos";

	var campos = ['importeTotal'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
		presupuesto: document.getElementById("numPresupuesto").innerHTML,
		cobrada: 2,
		tipo: 3,
		facCompletaAplicada: 1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarVerProvisionPrefactura() 
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{			
				var datos = res.datos;
				var importeTotal = parseFloat(datos[0]["importeTotal"]);

				document.getElementById("provisionTotal").value = importeTotal.toFixed(2);
			}
			peticionUnica1 = null;

		}
	}
}

function verDatosUnPresupuesto() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerClaymaUnPresupuesto;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerClaymaUnPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaVerClaymaUnPresupuesto()
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['clayma'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
		presupuesto: document.getElementById("numPresupuesto").innerHTML
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarVerClaymaUnPresupuesto()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var clayma = (res.datos.length>0 && (res.datos[0]["clayma"]=="1" || res.datos[0]["clayma"]==1));

				peticionUnica1=null;

				
				verDatosUnPresupuesto2(clayma);
			}
		}
	}
}

function verDatosUnPresupuesto2(clayma)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosUnPresupuesto;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosUnPresupuesto(clayma);
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosUnPresupuesto(clayma)
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['clayma','fecha','pedcli','cantidad','campana','detallada'];
	campos.push(clayma ? 'codigo_saldoClayma' : 'codigo_saldo');
	campos.push(clayma ? 'idFormaPagoClienteClayma' : 'idFormaPagoCliente');
	campos.push(clayma ? 'nuestraCuentaClayma' : 'nuestraCuenta');
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [clayma ? 'tabla8' : 'tabla7'];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtros = {
		presupuesto: document.getElementById("numPresupuesto").innerHTML
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarVerDatosUnPresupuesto()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var datos = res.datos;

				if (datos[0]["clayma"]=="1")
				{
					document.getElementById("clienteOrigen").checked = true;
				}
				else
				{
					document.getElementById("clienteOrigen").checked = false;
				}
				cargarListadoClientesPrefactura();
				
				valorNumero = datos[0]["codigo_saldo"];
				
				document.getElementById("clientes").value = datos[0]["codigo_saldo"];
				document.getElementById("fechaFactura").value = datos[0]["fecha"]["date"].substring(0,10);
				document.getElementById("pedidoCliente").value = datos[0]["pedcli"];
				document.getElementById("cantidad").value = datos[0]["cantidad"];
				document.getElementById("formaPago").value = datos[0]["idFormaPagoCliente"];				
				document.getElementById("campana").value = datos[0]["campana"];
				
				document.getElementById("numCuenta").value = datos[0]["nuestraCuenta"];
				
				if (datos[0]["detallada"]=="1")
				{
					document.getElementById("detallada").checked = true;
				}
				else
				{
					document.getElementById("detallada").checked = false;
				}
				

				/*
				if (datos[0]["prefactura"]==1)
				{
					document.getElementById("botonModificarPresupuesto").style.display = "none";
					document.getElementById("botonModificarPresupuesto").style.visibility = "hidden";
					
					//document.getElementById("botonEmitirPreFactura").style.display = "table-row";					
					//document.getElementById("botonEmitirPreFactura").style.visibility = "visible";
					
					
				}
				else*/
				{
					var botonModificarPresupuesto = document.getElementById("botonModificarPresupuesto");
					if (botonModificarPresupuesto)
					{
						botonModificarPresupuesto.style.display = "inline-block";
						botonModificarPresupuesto.style.visibility = "visible";
					}
					
					//document.getElementById("botonEmitirPreFactura").style.display = "none";					
					//document.getElementById("botonEmitirPreFactura").style.visibility = "hidden";
					
				}
				
			}
			peticionUnica1=null;
		}
	}						
}





function copiarPresupuestoAFacturaDetalleTemporal(opcion) //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCopiarPresupuestoAFacturaDetalleTemporal;
		peticionUnica1.open("POST","ajax/copiarPresupuestoAFacturaDetalleTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCopiarPresupuestoAFacturaDetalleTemporal(opcion);
		peticionUnica1.send(query_string);
	}
}

function consultaCopiarPresupuestoAFacturaDetalleTemporal(opcion)
{	
	var consulta = "accion=copiarPresupuestoAFacturaDetalleTemporal";
	consulta += "&numPresupuesto=" + document.getElementById("numPresupuesto").innerHTML;
	consulta += "&opcion="+opcion;	
	return consulta;	
}

function mostrarCopiarPresupuestoAFacturaDetalleTemporal()
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
				cargarDetallesPrefactura();
			}
			peticionUnica1=null;
		}
	}						
}







function cambiarClienteEnPrefactura()//presupuestoCambioModal //js_prefactura
{	
	
	if (confirm('¿Cambiar Cliente?')) 
	{
		buscarDatosClienteParaCambio();
		
		clienteInicial = document.getElementById("clientes").value;
	}
	else
	{
		document.getElementById("clientes").value = clienteInicial;
	}
	
	
	
}

function buscarDatosClienteParaCambio()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarBuscarDatosClienteParaCambio;
		if (document.getElementById("clienteOrigen").checked)
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBuscarDatosClienteParaCambio();
		peticionUnica1.send(query_string);
	}
}

function consultaBuscarDatosClienteParaCambio()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['nombre_empresa','direccion','localidad','codigo_postal','codigo'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { codigo: document.getElementById("clientes").value };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarBuscarDatosClienteParaCambio()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="" || res.datos.length<=0)
			{
				alert(res.error!="" ? res.error : "No se ha encontrado el cliente");
			}
			else
			{
				var cliente = res.datos[0];
				peticionUnica1 = null;
				modificarPresupuestoClienteCambio(cliente);
			}
		}
	}						
}

function modificarPresupuestoClienteCambio(cliente)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = function() { mostrarModificarPresupuestoClienteCambio(cliente); };
		peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarPresupuestoClienteCambio(cliente);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarPresupuestoClienteCambio(cliente)
{	
	var consulta = "accion=modificarRegistro";

	var clayma = document.getElementById("clienteOrigen").checked ? 1 : 0;

	var datos = {
		cliente: cliente["nombre_empresa"],
		codigoCliente: cliente["codigo"],
		direccion: cliente["direccion"],
		poblacion: cliente["localidad"],
		cp: cliente["codigo_postal"],
		clayma: clayma
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { presupuesto: document.getElementById("numPresupuesto").innerHTML };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarModificarPresupuestoClienteCambio(cliente)
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				peticionUnica1 = null;
				modificarProvisionFondoClienteCambio();
			}
		}
	}						
}

function modificarProvisionFondoClienteCambio()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarProvisionFondoClienteCambio;
		peticionUnica1.open("POST","ajax/modificarProvisionFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarProvisionFondoClienteCambio();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarProvisionFondoClienteCambio()
{	
	var consulta = "accion=modificarProvisionFondo";
	consulta += "&pantallaOrigen=prefactura";

	var clayma = document.getElementById("clienteOrigen").checked ? 1 : 0;

	var datos = {
		idCliente: document.getElementById("clientes").value,
		clayma: clayma
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { presupuesto: document.getElementById("numPresupuesto").innerHTML };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarModificarProvisionFondoClienteCambio()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				comprobarIvaCliente();
			}
			peticionUnica1 = null;
		}
	}						
}

function comprobarIvaCliente() //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarIvaCliente;
		if (document.getElementById("clienteOrigen").checked)
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarIvaCliente();
		peticionUnica1.send(query_string);
	}
}
function consultaComprobarIvaCliente()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['sinIva','retencion'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { codigo: document.getElementById("clientes").value };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarComprobarIvaCliente()
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

				if (datos.length>0)
				{
					if (datos[0]["sinIva"]==1)
					{
						document.getElementById("sinIvaCliente").value="sinIva";
					}
					else
					{
						document.getElementById("sinIvaCliente").value="conIva";
					}

					if (datos[0]["retencion"]==1)
					{
						document.getElementById("conIRPFCliente").value="conIRPF";
					}
					else
					{
						document.getElementById("conIRPFCliente").value="sinIRPF";
					}
				}				
			}
			peticionUnica1=null;
			calcularTotalTodoPreFactura();
		}
	}						
}

function noSeFactura() //js_prefactura
{
	/*if (confirm("Confirmar ¿El presupuesto "+document.getElementById("numPresupuesto").innerHTML+" no se va a facturar?")) 
	{
		noSeFactura2();
	}	*/
	
	$("#noSeFacturaModal").modal('show');
}

function noSeFactura2()//js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarNumNoFacturaMax;
		peticionUnica1.open("POST","ajax/cargarPresupuestos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarNumNoFacturaMax();
		peticionUnica1.send(query_string);
	}
}
function consultaCargarNumNoFacturaMax()
{	
	var consulta = "accion=cargarPresupuestos";

	var campos = ['numNoFacturaMax'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	return consulta;	
}

function mostrarCargarNumNoFacturaMax()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var numNoFactura = res.datos[0]["numNoFacturaMax"] + 1;
				peticionUnica1 = null;
				modificarNumNoFactura2(numNoFactura);
			}
		}
	}						
}

function modificarNumNoFactura2(numNoFactura)
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = function() { mostrarModificarNumNoFactura2(numNoFactura); };
		peticionUnica1.open("POST","ajax/modificarPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarNumNoFactura2(numNoFactura);
		peticionUnica1.send(query_string);
	}
}
function consultaModificarNumNoFactura2(numNoFactura)
{	
	var consulta = "accion=modificarRegistro";

	var datos = {
		numNoFactura: numNoFactura,
		noSeFacturaObservaciones: document.getElementById("observacionNoFacturableModal").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { presupuesto: document.getElementById("numPresupuesto").innerHTML };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarModificarNumNoFactura2(numNoFactura)
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				alert("El numero de la NO factura es: "+numNoFactura);		
				gestionarVolverPrefacturaBorrarTemporal(1);
			}
			peticionUnica1 = null;
		}
	}						
}

function gestionarVolverPrefacturaBorrarTemporal(valor) //js_prefactura
{
	if (valor==1)
	{	
		eliminarPresupuestoDeFacturaDetalleTemporal(document.getElementById("numPresupuesto").innerHTML);
		eliminarPresupuestoDeFacturaTemporal(document.getElementById("numPresupuesto").innerHTML);
	}
	else if (valor==2)
	{
		modificarFacturaTemporal();
	}
	
	var parametros = busquedaPantallaAnterior.split("|||");


	if (document.getElementById("clienteOrigen").checked)
	{
		location.href = 'admEmisionFacturasPendientes.php?clayma=1&buscarCampo=' + parametros[0] + '&buscarTexto=' + parametros[1] + '&ordenBuscar=' + parametros[2] + '&buscarDesc=' + parametros[3];
	}
	else
	{
		location.href = 'admEmisionFacturasPendientes.php?clayma=0&buscarCampo=' + parametros[0] + '&buscarTexto=' + parametros[1] + '&ordenBuscar=' + parametros[2] + '&buscarDesc=' + parametros[3];
	}	
}

function modificarFacturaTemporal()//presupuestoCambioModal //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarFacturaTemporal;
		peticionUnica1.open("POST","ajax/modificarFacturasTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarFacturaTemporal();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarFacturaTemporal()
{	
	var consulta = "accion=modificarFacturasTemporal";

	var datos = {
		idCliente: document.getElementById("clientes").value,
		clayma: document.getElementById("clienteOrigen").checked ? 1 : 0,
		pedido: document.getElementById("pedidoCliente").value,
		cantidad: document.getElementById("cantidad").value,
		formaPago: document.getElementById("formaPago").value,
		descripcion: document.getElementById("campana").value,
		detallada: document.getElementById("detallada").checked ? 1 : 0,
		precioNeto: document.getElementById("Neto").value,
		iva: document.getElementById("iva").value,
		irpf: document.getElementById("irpf").value,
		precioTotal: document.getElementById("total").value,
		provision: document.getElementById("provisionTotal").value,
		aPagar: document.getElementById("aPagar").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = { presupuesto: document.getElementById("numPresupuesto").innerHTML };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarModificarFacturaTemporal()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			peticionUnica1 = null;
		}
	}						
}

function previsualizarFactura() //js_prefactura
{
	document.getElementById("previsualizar_presupuesto").value = document.getElementById("numPresupuesto").innerHTML;		
	
	document.getElementById("previsualizar_idCliente").value = document.getElementById("clientes").value;		
	
	document.getElementById("previsualizar_fecha").value = document.getElementById("fechaFactura").value;
	document.getElementById("previsualizar_pedido").value = (document.getElementById("pedidoCliente").value);
	
	var valor = 0;
	if (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null)
	{
		valor = 0;
	}
	else
	{
		valor = document.getElementById("cantidad").value;
	}
	
	document.getElementById("previsualizar_cantidad").value = valor;
	document.getElementById("previsualizar_formaPago").value = document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text;
	
	document.getElementById("previsualizar_nuestraCuenta").value = document.getElementById("numCuenta").value;
	
	document.getElementById("previsualizar_campana").value = (document.getElementById("campana").value);
	
	valor = 0;
	if (document.getElementById("detallada").checked)
	{
		valor = 1;
	}
	
	document.getElementById("previsualizar_detallada").value = valor;
	document.getElementById("previsualizar_neto").value = document.getElementById("Neto").value;
	document.getElementById("previsualizar_iva").value = document.getElementById("iva").value;
	document.getElementById("previsualizar_irpf").value = document.getElementById("irpf").value;
	document.getElementById("previsualizar_total").value = document.getElementById("total").value;
	document.getElementById("previsualizar_provision").value = document.getElementById("provisionTotal").value;
	document.getElementById("previsualizar_aPagar").value = document.getElementById("aPagar").value;
	document.getElementById("previsualizar_clayma").value = document.getElementById("clienteOrigen").checked ? 1 : 0;
				
	document.getElementById("formPrevisualizarFactura").submit();
}

function grabarFactura() //
{	
	if (document.getElementById("clientes").value<=0)
	{
		alert("Elegir un Cliente");
		document.getElementById("clientes").focus();
	}
	else if (!seguirSiNoEsPrimeraFacturaDelMesSinConfirmar(document.getElementById("clienteOrigen").checked ? 1 : 0))
	{
		//el usuario ha cancelado tras el aviso de primera factura del mes
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarFactura;
			peticionUnica1.open("POST","ajax/anadirFactura.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGrabarFactura();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGrabarFactura()
{	
	var consulta = "accion=anadirFactura";

	var combo = document.getElementById("clientes");
	var selected = combo.options[combo.selectedIndex].text;

	var datos = {
		presupuesto: document.getElementById("numPresupuesto").innerHTML,
		inicialComercial: document.getElementById("inicialComercial").innerHTML,
		cliente: selected,
		idCodigoCliente: document.getElementById("clientes").value,
		clayma: document.getElementById("clienteOrigen").checked ? 1 : 0,
		pedido: document.getElementById("pedidoCliente").value,
		cantidad: (document.getElementById("cantidad").value=="" || document.getElementById("cantidad").value==null) ? 0 : document.getElementById("cantidad").value,
		formaPago: document.getElementById("formaPago").options[document.getElementById("formaPago").selectedIndex].text,
		numCuentaBanco: document.getElementById("numCuenta").value,
		descripcion: document.getElementById("campana").value,
		detallada: document.getElementById("detallada").checked ? 1 : 0,
		precioNeto: document.getElementById("Neto").value,
		iva: document.getElementById("iva").value,
		irpf: document.getElementById("irpf").value,
		precioTotal: document.getElementById("total").value,
		provision: document.getElementById("provisionTotal").value,
		aPagar: document.getElementById("aPagar").value,
		prefactura: 0,
		serieFactura: 'FAC'
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarGrabarFactura()
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
				verNumFacturaPorPresupuesto(document.getElementById("numPresupuesto").innerHTML);
				
				document.getElementById("modalNumFactura").innerHTML = facCompleto;
				
				grabarFacturaDetalle();
				
				eliminarTodoFacturaDetalleTemporal();
				
				if (document.getElementById("provisionTotal").value!=0)
				{				
					modificarProvisionNumFacturaPorPresupuesto();
				}
				if (unError == "Error En Verifactu")
				{
					unError="";
					alert("ERROR EN VERIFACTU\nSi se ha generado la factura, NO ENVIARSELO AL CLIENTE\nRevisar lo que ha pasado");
				}
				else
				{
					if (document.getElementById("clienteOrigen").checked)
					{
						irAImprimirFacturaClayma(facCompleto);
					}
					else
					{
						irAImprimirFactura(facCompleto);
					}
				}
				
				facCompleto = "";
				numFactura="";
				anioSeleccionado="";
				location.href='admEmisionFacturasPendientes.php';				
			}
			peticionUnica1=null;
		}
	}						
}

function verNumFacturaPorPresupuesto(numPresupuesto) //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);
	var clayma = document.getElementById("clienteOrigen").checked;

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerNumFacturaPorPresupuesto;
		peticionUnica1.open("POST", clayma ? "ajax/cargarFacturacionClayma.php" : "ajax/cargarFacturacion.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerNumFacturaPorPresupuesto(numPresupuesto);
		peticionUnica1.send(query_string);
	}
}

function consultaVerNumFacturaPorPresupuesto(numPresupuesto)
{	
	var consulta = "accion=cargarFacturacion";

	var campos = ['numeroFacturaCompleto'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
		presupuesto: numPresupuesto
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarVerNumFacturaPorPresupuesto()
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
				if (res.datos.length>0)
				{
					res.datos[0]["numero"];
					facCompleto = res.datos[0]["numeroFacturaCompleto"];
				}
				else
				{
					
					facCompleto="";
				}
			}
			peticionUnica1=null;
		}
	}						
}

function grabarFacturaDetalle() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);
	var clayma = document.getElementById("clienteOrigen").checked;

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGrabarFacturaDetalle;
		peticionUnica1.open("POST", clayma ? "ajax/insertarFacturacionDetalleClaymaDesdeTemporal.php" : "ajax/insertarFacturacionDetalleDesdeTemporal.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGrabarFacturaDetalle();
		peticionUnica1.send(query_string);
	}
}

function consultaGrabarFacturaDetalle()
{	
	var consulta = "accion=insertarFacturacionDetalles";

	consulta += "&numPresupuesto="+document.getElementById("numPresupuesto").innerHTML;
	consulta += "&numeroFacturaCompleto="+encodeURIComponent(facCompleto);
	consulta += "&campana="+encodeURIComponent(document.getElementById("campana").value);
	
	return consulta;	
}

function mostrarGrabarFacturaDetalle()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			var huboError = res.some(function(fila) { return !fila.ok; });

			if (huboError)
			{
				alert("Error al grabar el detalle de la factura");
			}

			peticionUnica1=null;
		}
	}						
}




function modificarProvisionNumFacturaPorPresupuesto() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarProvisionNumFacturaPorPresupuesto;
		peticionUnica1.open("POST","ajax/modificarProvisionFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarProvisionNumFacturaPorPresupuesto();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarProvisionNumFacturaPorPresupuesto()
{	
	var consulta = "accion=modificarProvisionFondo";
	consulta += "&pantallaOrigen=prefactura";

	var datos = {
		facCompletaAplicada: facCompleto
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
		presupuesto: document.getElementById("numPresupuesto").innerHTML,
		cobrada: 2,
		tipo: 3,
		sinFacturaAplicada: 1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarModificarProvisionNumFacturaPorPresupuesto()
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

			peticionUnica1 = null;
		}
	}						
}

function calcularImporteFranqueoDesdePrefactura1() //js_prefactura
{	
	if (document.getElementById("clienteOrigen").checked)
	{
		document.getElementById("cliente_importeFranqueoModal").value=0;
	}
	else
	{
		document.getElementById("cliente_importeFranqueoModal").value = document.getElementById("clientes").value;
	}
	
	$("#verImporteFranqueoModal").modal('show');
}

function anadirDetallePrefactura() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarAnadirDetallePrefactura;
		peticionUnica1.open("POST","ajax/insertarFacturasDetallesTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaAnadirDetallePrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaAnadirDetallePrefactura()
{	
	var consulta = "accion=insertarFacturasDetallesTemporal";

	var unidad = document.getElementById("unidadesDetalleNuevoTemp").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}

	var precio = document.getElementById("precioDetalleNuevoTemp").value;	
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}

	var datos = {
		presupuesto: document.getElementById("numPresupuesto").innerHTML,
		concepto: document.getElementById("conceptoNuevoTemp").value,
		descripcion: document.getElementById("descripcionDetalleNuevoTemp").value,
		notaCibeles: document.getElementById("notaDetalleNuevoTemp").value,
		unidades: unidad,
		precio: precio,
		total: Math.round(parseFloat(precio)*parseFloat(unidad)*100)/100,
		ordenTipo: 1000,
		orden: 1000,
		tipoIva: document.getElementById("tipoIvaNuevoTemp").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarAnadirDetallePrefactura()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				cargarDetallesPrefactura();
				
				document.getElementById("conceptoNuevoTemp").value="";
				document.getElementById("descripcionDetalleNuevoTemp").value="";
				document.getElementById("notaDetalleNuevoTemp").value="";
				document.getElementById("tipoIvaNuevoTemp").value=21;
				
			}
			peticionUnica1=null;
		}
	}						
}

function calcularImporteFranqueoDesdePrefactura() //js_prefactura
{
	if (document.getElementById("fechaInicio_importeFranqueoModal").value=="")
	{
		alert("Introducir una fecha de inicio.");
		document.getElementById("fechaInicio_importeFranqueoModal").focus();
	}
	else if (document.getElementById("fechaFin_importeFranqueoModal").value=="")
	{
		alert("Introducir una fecha fin.");
		document.getElementById("fechaFin_importeFranqueoModal").focus();
	}
	else if (document.getElementById("clientes").value<=0)
	{
		alert("Introducir una fecha fin.");
		document.getElementById("clientes").focus();
	}
	else if (document.getElementById("cliente_importeFranqueoModal").value<=0)
	{
		alert("Seleccionar un cliente");
		document.getElementById("cliente_importeFranqueoModal").focus();
	}
	else
	{
		calcularImporteFranqueoDesdePrefactura2();
	}
}


function calcularImporteFranqueoDesdePrefactura2() //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCalcularImporteFranqueoDesdePrefactura2;
		peticionUnica1.open("POST","ajax/calcularImporteFranqueoDesdePrefactura.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCalcularImporteFranqueoDesdePrefactura2();
		peticionUnica1.send(query_string);
	}
}

function consultaCalcularImporteFranqueoDesdePrefactura2()
{	
	var consulta = "accion=mostrarListadoFacturasPendientesTotal";
	
	//consulta += "&idCliente=" + document.getElementById("clientes").value;
	consulta += "&fechaInicio=" + document.getElementById("fechaInicio_importeFranqueoModal").value;
	consulta += "&fechaFin=" + document.getElementById("fechaFin_importeFranqueoModal").value;
	//consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	consulta += "&idClienteCibeles=" + document.getElementById("cliente_importeFranqueoModal").value;
	consulta += "&extension=" + document.getElementById("extension_importeFranqueoModal").value;
	consulta += "&ot=" + document.getElementById("numPresupuesto").innerHTML;
	
	return consulta;	
}

function mostrarCalcularImporteFranqueoDesdePrefactura2()
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
						
				document.getElementById("precioDetalleNuevoTemp").value=datos[0]["importe"];
				document.getElementById("unidadesDetalleNuevoTemp").value = 1;
				document.getElementById("conceptoNuevoTemp").value = "Franqueo";

				$("#verImporteFranqueoModal").modal('hide');
			}
			
		}
		peticionUnica1 = null;
	}						
}

function gestionarCambioClientePrefactura(destino) //js_prefactura 
{
	
	if (confirm('¿Cambiar Cliente?')) 
	{
	
		cargarListadoClientesPrefactura();

		document.getElementById(destino).value=0;
	}
}

function cargarListadoClientesPrefactura() //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarListadoClientesPrefactura;
		if (document.getElementById("clienteOrigen").checked)
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoClientesPrefactura();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoClientesPrefactura()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['codigo_saldo','nombre_empresa'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { activo: 1 };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var filtrosOperadores = [{ campo1: 'codigo', campo2: 'codigo_saldo', operador: '=' }];
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{ campo: 'nombre_empresa', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarListadoClientesPrefactura()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var datos = res.datos;
				var contenido = "";

				var contador = 0;
				while (contador<datos.length)
				{
					contenido += '  <option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+'</option>';
					contador++;
				}

				document.getElementById("clientes").innerHTML = contenido;

				document.getElementById("clientes").value = valorNumero;
				valorNumero = 0;
			}
			peticionUnica1=null;
		}
	}
}

function cargarListadoClientesImporteFranqueoModal() //js_prefactura
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarCargarListadoClientesImporteFranqueoModal;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoClientesImporteFranqueoModal();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoClientesImporteFranqueoModal()
{	
	var consulta = "accion=cargarClientes";

	var campos = ['codigo_saldo','nombre_empresa'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { activo: 1 };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	var filtrosOperadores = [{ campo1: 'codigo', campo2: 'codigo_saldo', operador: '=' }];
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [{ campo: 'nombre_empresa', dir: 'ASC' }];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;	
}

function mostrarCargarListadoClientesImporteFranqueoModal()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var datos = res.datos;
				var contenido = "";

				var contador = 0;
				while (contador<datos.length)
				{
					contenido += '  <option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+'</option>';
					contador++;
				}

				document.getElementById("cliente_importeFranqueoModal").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}
}



function verDatosUnPresupuestoTemporal(numPresupuesto) //js_prefactura
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosUnPresupuestoTemporal;
		peticionUnica1.open("POST","ajax/mostrarFacturasTemporal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosUnPresupuestoTemporal(numPresupuesto);
		peticionUnica1.send(query_string);
	}
}

function consultaVerDatosUnPresupuestoTemporal(numPresupuesto)
{	
	var consulta = "accion=mostrarFacturasTemporal";

	var campos = ['pedido','cantidad','formaPago','descripcion','detallada'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = { presupuesto: numPresupuesto, idUsuario: true };
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarVerDatosUnPresupuestoTemporal()
{
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var datos = res.datos;

				document.getElementById("pedidoCliente").value = datos[0]["pedido"];
				document.getElementById("cantidad").value = datos[0]["cantidad"];
				document.getElementById("formaPago").value = datos[0]["formaPago"];				
				document.getElementById("campana").value = datos[0]["descripcion"];
				
				if (datos[0]["detallada"]=="1")
				{
					document.getElementById("detallada").checked = true;
				}
				else
				{
					document.getElementById("detallada").checked = false;
				}			
				
			}	
			peticionUnica1 = null;
		}
	}						
}



