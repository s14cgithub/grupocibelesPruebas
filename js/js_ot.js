var peticionUnica1 = null;
var laCondicion = "";

var permisosSoloLectura = null;




function cargarListadoOtProduccion()
{
	peticionUnica1 = crearComunicacion(peticionUnica1);

	if (peticionUnica1) {
		peticionUnica1.onreadystatechange = mostrarCargarListadoOtProduccion;
		peticionUnica1.open("POST", "ajax/mostrarPresupuestos1.php", true);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoOtProduccion();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoOtProduccion() {
	var consulta = "accion=mostrarPresupuesto";
	
	
	var campos =[
		'facturaCibeles',
		'presupuesto',
		'facturaClayma',
		'numNoFactura',
		'campana2',
		'campana',
		'cantidad2',
		'cantidad',
		'fechaInicioReal',
		'fechaTerminado',
 		'numeroFacturaCompleto',
 		'clayma',
		'fechaCompromiso',
		'cliente'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {			
		sinFecha: document.getElementById("buscarSoloSinFecha").checked ? 1 : 0
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	consulta += "&pantallaOrigen=ot";
	consulta += "&meses=" + document.getElementById("buscarNumMeses").value;

	var filtrosLike = [
		{
			campo: document.getElementById("buscarCampo").value,
			valor: document.getElementById("buscarTexto").value
		}
	];

	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(filtrosLike));


	var filtrosOperadores = [];

	if (document.getElementById("buscarFechaInicio").value)
	{
		var fechaInicio = document.getElementById("buscarFechaInicio").value;
		var laFecha = fechaInicio ? fechaInicio.replace("/", "-") : "";
		
		if (laFecha) {
			var datos = laFecha.split('-');
			var anio = datos[0];
			var mes = datos[1];
			var dia = datos[2];

			var laFecha1 = `${dia}-${mes}-${anio}`;

			filtrosOperadores.push({
				campo1: 'fechaInicioReal',
				operador: '>=',
				valor: laFecha1
			});
		}
	}
	if (document.getElementById("buscarFechaFin").value)
	{
		var fechaInicio2 = document.getElementById("buscarFechaFin").value;
		var laFecha2 = fechaInicio2 ? fechaInicio2.replace("/", "-") : "";
		
		if (laFecha2) {
			var datos2 = laFecha2.split('-');
			var anio2 = datos2[0];
			var mes2 = datos2[1];
			var dia2 = datos2[2];

			var laFecha2 = `${dia2}-${mes2}-${anio2}`;

			filtrosOperadores.push({
				campo1: 'fechaInicioReal',
				operador: '<=',
				valor: laFecha2
			});
		}
	}

	
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var order = [
		{
			campo: document.getElementById("ordenBuscar").value,
			dir: document.getElementById("ordenDesc").checked ? 'DESC' : 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	
	return consulta;

}

function mostrarCargarListadoOtProduccion() {
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

				document.getElementById("listadoOt").innerHTML = "";
				
				//let datosOt = document.getElementById("listadoOt"); 
					

				var contenido = "";
				contenido += '<tr class="centrarTexto">';
				contenido += '<th align="center">Presupuesto</th>';

				contenido += '<th>Cliente</th>';
				contenido += '<th>Campaña</th>';
				contenido += '<th>Campaña</th>';
				contenido += '<th>Cantidad</th>';
				contenido += '<th>Inicio</th>';
				contenido += '<th>Terminado</th>';


				if (!permisosSoloLectura)
				{
					contenido += '<th></th>';
				}
				
				contenido += '<th>Det.</th>';
				contenido += '<th>Obs.</th>';
				contenido += '<th></th>';


				contenido += '</tr>';

				var contador = 0;
				var contraste = "";
				//console.time('OT');

				
				while (contador < datos.length) {
					if (contador % 2 == 0) {
						contraste = "";
					}
					else {
						contraste = ' class="tablaContenidoColor" ';
					}
					
					contenido += '<tr ' + contraste + '>';


					if (datos[contador]["numeroFacturaCompleto"] != null && datos[contador]["clayma"]==0 )
					{
						contenido += '<td align="center" style="background: green;" title="'+datos[contador]["numeroFacturaCompleto"]+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</span></td>';
					}
					else if (datos[contador]["numeroFacturaCompleto"] != null && datos[contador]["clayma"]==1 )
					{
						contenido += '<td align="center" style="background: #B87240;" title="'+datos[contador]["facturaClayma"]+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</span></td>';
					}
					else if (datos[contador]["numNoFactura"] != null)
					{
						contenido += '<td align="center" style="background: black;" title="'+datos[contador]["numNoFactura"]+'-No Facturable"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</span></td>';
					}
					else
					{
						contenido += '<td align="center">' + datos[contador]["presupuesto"] + '</td>';
					}
					
					contenido += '<td align="left" style="width:  300px !important;">' + datos[contador]["cliente"] + '</td>';

					
					if (datos[contador]["campana2"] != "" && datos[contador]["campana2"] != null) {						
						contenido += '<td><label>' + datos[contador]["campana2"] + '</label></td>';
						contenido += '<td><input type="text" id="campanaOt_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["campana2"].replace(/"/g, '&quot;') + '" style="width:  300px !important;"></input></td>';
					}
					else {
						contenido += '<td><label>' + datos[contador]["campana"] + '</label></td>';
						contenido += '<td><input type="text" id="campanaOt_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["campana"].replace(/"/g, '&quot;') + '" style="width:  300px !important;"></input></td>';
					}

					if (datos[contador]["cantidad2"] != "" && datos[contador]["cantidad2"] != null && datos[contador]["cantidad2"] != datos[contador]["cantidad"]) {
						var valor = datos[contador]["cantidad2"];
						


						contenido += '<td><input type="number" id="cantidadOt_' + datos[contador]["presupuesto"] + '" value="' + valor + '" style="text-align: right; width: 120px;"></input></td>';
					}
					else {
						var valor = datos[contador]["cantidad"];
						
						if (valor == "" || valor == null || valor == "null") {
							valor = 0;
						}

						contenido += '<td><input type="number" id="cantidadOt_' + datos[contador]["presupuesto"] + '" value="' + valor + '" style="text-align: right; width:  120px;"></input></td>';
					}


					if (datos[contador]["fechaInicioReal"] != "" && datos[contador]["fechaInicioReal"] != null) {
						contenido += '<td><input type="date" id="fechaInicioReal_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["fechaInicioReal"]["date"].substring(0, 10) + '" style="width:  140px !important;"></input></td>';
					}
					else {
						contenido += '<td><input type="date" id="fechaInicioReal_' + datos[contador]["presupuesto"] + '" value="" style="width:  140px !important;"></input></td>';
					}

					if (datos[contador]["fechaTerminado"] != "" && datos[contador]["fechaTerminado"] != null) {
						contenido += '<td><input type="date" id="fechaTerminado_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["fechaTerminado"]["date"].substring(0, 10) + '" style="width:  140px !important;"></input></td>';
					}
					else {
						contenido += '<td><input type="date" id="fechaTerminado_' + datos[contador]["presupuesto"] + '" value="" max="' + (datos[contador]["fechaCompromiso"] != null ? datos[contador]["fechaCompromiso"]["date"].substring(0, 10) : "") + '"  style="width:  140px !important;"></input></td>';
					}


					if (!permisosSoloLectura)
					{
						contenido += '<td><input type="image" id="' + datos[contador]["presupuesto"] + '_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarOt(' + datos[contador]["presupuesto"] + ')"></td>';
					}
					contenido += '<td><input type="image" id="' + datos[contador]["presupuesto"] + '_verCantidadDetalle" value="" src="imagenes/ojo.png" style="width:15px;" onclick="verCantidadDetalleProduccion(' + datos[contador]["presupuesto"] + ')"></td>';


					contenido += '<td><input type="image" id="' + datos[contador]["presupuesto"] + '_observacion" value="" src="imagenes/ojo.png" style="width:15px;" onclick="verObservacionOt(' + datos[contador]["presupuesto"] + ')"></td>';

					contenido += '<td><button type="button" class="btn btn-info" style="height: =5px;line-height: 5px;" onclick="imprimirOtProduccion(' + datos[contador]["presupuesto"] + ')">OT</button></td>';

					contenido += '</tr>';
					

					contador++;
				}
				//console.timeEnd('OT');
				

				console.time('mostrarDatos');
				document.getElementById("listadoOt").innerHTML = contenido;
				console.timeEnd('mostrarDatos');

				
			}
			peticionUnica1 = null;

		}
	}
}

function verObservacionOt(presupuesto) //js_facturas
{
	document.getElementById("numOtModal").innerHTML = presupuesto;


	peticionUnica1 = crearComunicacion(peticionUnica1);

	if (peticionUnica1) {
		peticionUnica1.onreadystatechange = mostrarVerObservacionOt;
		peticionUnica1.open("POST", "ajax/cargarPresupuestos.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaVerObservacionOt(presupuesto);
		peticionUnica1.send(query_string);
	}
}

function consultaVerObservacionOt(presupuesto) {
	
	var consulta = "accion=cargarPresupuestos";	
	
	var campos = [
		'observaciones2'	
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	presupuesto: presupuesto		
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	
	return consulta;	
}

function mostrarVerObservacionOt() {
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

				if (datos.length > 0) {
					document.getElementById("observacionModal").value = datos[0]["observaciones2"];
				}
				//document.getElementById("").value = peticionUnica.responseText;
				$("#observacionOtModal").modal('show');
			}
			peticionUnica1 = null;
		}
	}
}


function modificarOt(numOt)//js_ot
{
	if (document.getElementById("fechaInicioReal_" + numOt).value != "" && document.getElementById("fechaInicioReal_" + numOt).value != null && document.getElementById("fechaTerminado_" + numOt).value != "" && document.getElementById("fechaTerminado_" + numOt).value != null) {
		peticionUnica1 = crearComunicacion(peticionUnica1);

		if (peticionUnica1) {
			peticionUnica1.onreadystatechange = mostrarModificarOt;
			peticionUnica1.open("POST", "ajax/modificarPresupuesto.php", false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var query_string = consultaModificarOt(numOt);
			peticionUnica1.send(query_string);
		}
	}
	else {
		alert("Introducir la fecha de Inicio y la fecha Terminado");
	}

}

function consultaModificarOt(numOt) {
	var consulta = "accion=modificarRegistro";


	var datos = {
		campana2: document.getElementById("campanaOt_" + numOt).value,	
		cantidad2: document.getElementById("cantidadOt_" + numOt).value === "" ? 0  : parseFloat(document.getElementById("cantidadOt_" + numOt).value.replace(',', '.')),
		fechaInicioReal: document.getElementById("fechaInicioReal_" + numOt).value,
		fechaTerminado: document.getElementById("fechaTerminado_" + numOt).value	
		
	};

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	presupuesto: numOt
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	
	
	return consulta;

/*
	//////////////////////////////////////////////////
	
	var paraFecha = document.getElementById("fechaInicioReal_" + numOt).value;
	var anioFin = paraFecha.substr(0, paraFecha.indexOf('-'));
	var mesFin = paraFecha.substr(paraFecha.indexOf('-') + 1, paraFecha.length - paraFecha.lastIndexOf('-') - 1);
	var diaFin = paraFecha.substr(paraFecha.lastIndexOf('-') + 1);

	consulta += "&fechaInicio=" + diaFin + "/" + mesFin + "/" + anioFin;

	paraFecha = document.getElementById("fechaTerminado_" + numOt).value;
	anioFin = paraFecha.substr(0, paraFecha.indexOf('-'));
	mesFin = paraFecha.substr(paraFecha.indexOf('-') + 1, paraFecha.length - paraFecha.lastIndexOf('-') - 1);
	diaFin = paraFecha.substr(paraFecha.lastIndexOf('-') + 1);

	consulta += "&fechaTerminado=" + diaFin + "/" + mesFin + "/" + anioFin;

	*/
}

function mostrarModificarOt() {
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);
			
			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				alert("Presupuesto Modificado");

				cargarListadoOtProduccion();

			}
		}
	}
}


function verCantidadDetalleProduccion(presupuesto1)//js_ot
{
	var n = presupuesto1.toString().indexOf("_");
	var presupuesto = "";
	if (n == -1) {
		presupuesto = presupuesto1;
	}
	else {
		presupuesto = presupuesto1.substr(0, presupuesto1.indexOf('_'));
	}


	//window.location.href = "http://172.26.0.17:8080/gestionGrupocibelesPreproduccion/presupuestos_ot_detalle.php?presupuesto="+presupuesto;
	window.location.href = "presupuestos_ot_detalle.php?presupuesto=" + presupuesto;
}


function imprimirOtProduccion(numOt)//js_ot
{
	if (document.getElementById("fechaInicioReal_" + numOt).value == "" || document.getElementById("fechaTerminado_" + numOt).value == "") {
		alert("Hay que guardar una fecha de Inicio y una fecha de Terminado ");
	}
	else {
		document.getElementById("imprimirNumOT").value = numOt;
		document.getElementById("formImprimirOT").submit();

		document.getElementById("imprimirNumOTPortada").value = numOt;
		document.getElementById("formImprimirOTPortada").submit();
	}


}


function imprimirOtRango() {

	document.getElementById("mesRango").value = document.getElementById("mesImprimirModal").value;
	document.getElementById("anioRango").value = document.getElementById("anioImprimirModal").value;
	document.getElementById("formImprimirOTMensuales").submit();


	document.getElementById("mesRangoPortadaRango").value = document.getElementById("mesImprimirModal").value;
	document.getElementById("anioRangoPortadaRango").value = document.getElementById("anioImprimirModal").value;
	document.getElementById("formImprimirOTPortadaMensual").submit();


}


function modificarObservacionOT()
{

	peticionUnica1 = crearComunicacion(peticionUnica1);

	if (peticionUnica1) {
		peticionUnica1.onreadystatechange = mostrarModificarObservacionOT;
		peticionUnica1.open("POST", "ajax/modificarPresupuesto.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaModificarObservacionOT();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarObservacionOT() {
	var consulta = "accion=modificarRegistro";

	var datos = {
		observaciones2: document.getElementById("observacionModal").value
		
	};

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	presupuesto: document.getElementById("numOtModal").innerHTML
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	
	
	return consulta;
	
}

function mostrarModificarObservacionOT() {
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			var res = JSON.parse(peticionUnica1.responseText);
			
			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				alert("Observacion Modificado");
			}
			peticionUnica1 = null;
		}
	}
}

function gestionExportarExcelOtCibeles() //js_facturas
{
	var campos =[
		'presupuesto',
		'cliente',
		'campana2',
		'cantidad2',
		'fechaInicioReal',
		'fechaTerminado',
		'observaciones2'
	];

	document.getElementById("camposFormExportar").value = JSON.stringify(campos);

	var filtros = {			
		sinFecha: document.getElementById("buscarSoloSinFecha").checked ? 1 : 0
	};
	document.getElementById("filtrosFormExportar").value = JSON.stringify(filtros);

	document.getElementById("mesesFormExportar").value = document.getElementById("buscarNumMeses").value;
	

	var filtrosLike = [
		{
			campo: document.getElementById("buscarCampo").value,
			valor: document.getElementById("buscarTexto").value
		}
	];

	document.getElementById("filtrosLikeFormExportar").value = JSON.stringify(filtrosLike);


	var filtrosOperadores = [];

	if (document.getElementById("buscarFechaInicio").value)
	{
		var fechaInicio = document.getElementById("buscarFechaInicio").value;
		var laFecha = fechaInicio ? fechaInicio.replace("/", "-") : "";
		
		if (laFecha) {
			var datos = laFecha.split('-');
			var anio = datos[0];
			var mes = datos[1];
			var dia = datos[2];

			var laFecha1 = `${dia}-${mes}-${anio}`;

			filtrosOperadores.push({
				campo1: 'fechaInicioReal',
				operador: '>=',
				valor: laFecha1
			});
		}
	}
	if (document.getElementById("buscarFechaFin").value)
	{
		var fechaInicio2 = document.getElementById("buscarFechaFin").value;
		var laFecha2 = fechaInicio2 ? fechaInicio2.replace("/", "-") : "";
		
		if (laFecha2) {
			var datos2 = laFecha2.split('-');
			var anio2 = datos2[0];
			var mes2 = datos2[1];
			var dia2 = datos2[2];

			var laFecha2 = `${dia2}-${mes2}-${anio2}`;

			filtrosOperadores.push({
				campo1: 'fechaInicioReal',
				operador: '<=',
				valor: laFecha2
			});
		}
	}

	document.getElementById("filtrosOperadoresFormExportar").value = JSON.stringify(filtrosOperadores);
	
	var order = [
		{
			campo: document.getElementById("ordenBuscar").value,
			dir: document.getElementById("ordenDesc").checked ? 'DESC' : 'ASC'
		}
	];

	document.getElementById("orderFormExportar").value = JSON.stringify(order);	
	
	document.getElementById("formExportarExcel").submit();
}



