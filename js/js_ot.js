var peticionUnica1 = null;
var laCondicion = "";

var permisosSoloLectura = null;


function buscarOt() {
	
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	var sinFecha = document.getElementById("buscarSoloSinFecha").checked;

	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	//var anioSeleccionado = document.getElementById("anioSeleccionado").value;

	//var clayma = document.getElementById("clienteOrigen").checked;
	//condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";	
	var condicion = " " + campoAbuscar + " like '%" + textoAbuscar + "%'";


	var laFecha = fechaInicio ? fechaInicio.replace("/", "-") : "";
    if (laFecha) {
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];

		var laFecha1 = `${dia}-${mes}-${anio}`;

		condicion += ` and t1.fechaInicioReal >= '${laFecha1}'`;
	}

	laFecha = fechaFin ? fechaFin.replace("/", "-") : "";
    if (laFecha) {
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];

		laFecha1 = dia + "-" + mes + "-" + anio;

		condicion += " and t1.fechaInicioReal <= '" + laFecha1 + "'";
	}

	if (sinFecha == true) {
		condicion += " and (t1.fechaInicioReal is null or t1.fechaTerminado is null) "
	}

	if (sinFecha == true) {
		condicion += " and (t1.fechaInicioReal is null or t1.fechaTerminado is null) "
	}

	if (document.getElementById("buscarNumMeses").value!=null && document.getElementById("buscarNumMeses").value>0)
	{

		var fechaActual = new Date();		
		
		fechaActual.setMonth(fechaActual.getMonth() + 1 - document.getElementById("buscarNumMeses").value);
		var mesesAbuscar=("0" + (fechaActual.getMonth()+1)).slice(-2);
		var fechaInicioPorMeses = "01-" + mesesAbuscar + "-" +   fechaActual.getFullYear() ;

		//alert(fechaInicioPorMeses);

		condicion += " and t1.fechaInicioReal >='" + fechaInicioPorMeses + "'";


	}

	/*
		if ($meses>0)
		{
			$meses = $meses-1;
			$fechaActual = date('d-m-Y');
			$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
			$fecha = " fecha >='".$fechaInicio."'";
		}
	*/



	//condicion += " and presupuesto like '" + anioSeleccionado.substring(2) + "%'";

	condicion += " order by " + orden;

	if (desc == true) {
		condicion += " desc";
	}

	laCondicion = condicion;


	//cargarListadoFacturasSinEmitir();

	cargarListadoOtProduccion();

}






function cargarListadoOtProduccion()//js_ot
{
	peticionUnica1 = crearComunicacion(peticionUnica1);

	if (peticionUnica1) {
		peticionUnica1.onreadystatechange = mostrarCargarListadoOtProduccion;
		peticionUnica1.open("POST", "ajax/mostrarOtProduccion.php", true);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarListadoOtProduccion();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoOtProduccion() {
	var consulta = "accion=mostrarOT";
	//laCondicion = reemplazarSimbolos(laCondicion);
	consulta += "&condicion=" + reemplazarSimbolosBusqueda(laCondicion);;

	return consulta;
}

function mostrarCargarListadoOtProduccion() {
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			if (peticionUnica1.responseText.substr(0, 5) == "Error") {
				alert(peticionUnica1.responseText);
			}
			else {
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);

				document.getElementById("listadoOt").innerHTML = "";
				
				let datosOt = document.getElementById("listadoOt"); 
								
				/*let nuevaFila = document.createElement("tr");
				nuevaFila.setAttribute("class","centrarTexto  tablaCabeceraColor");

				var arrayTituto = ["Presupuesto","Cliente","Campaña","Cantidad","Inicio","Terminado","","Det.","Obs.",""];				
				console.time('mostrarDatos');
				for (let contTitulo=0; contTitulo<arrayTituto.length;contTitulo++)
				{
					let nuevaCelda = document.createElement("th");
					nuevaCelda.innerText = arrayTituto[contTitulo];
					nuevaFila.appendChild(nuevaCelda);					
				}
				
				datosOt.appendChild(nuevaFila);
				
				
				for (var contador=0; contador<datos.length;contador++){
					dato = datos[contador];
					let contraste = contador % 2 == 0 ? "" :  'tablaContenidoColor';
					
					nuevaFila = document.createElement("tr");
					nuevaFila.setAttribute("class",contraste);

					nuevaCelda= document.createElement("td");
					nuevaCelda.setAttribute("align","center");
					nuevaCelda.innerText = dato.presupuesto;
					nuevaFila.appendChild(nuevaCelda);	

					nuevaCelda = document.createElement("td");
					nuevaCelda.setAttribute("align","left");
					nuevaCelda.innerText = dato.cliente;
					nuevaFila.appendChild(nuevaCelda);	

					let valorCelda = dato.campana2 && dato.campana2 !== "" ? dato.campana2 : dato.campana;

					nuevaCelda = document.createElement("td");
					let nuevoInput = document.createElement("input");					
					nuevoInput.setAttribute("type","text");
					nuevoInput.setAttribute("id","campanaOt_" + dato.presupuesto);
					nuevoInput.setAttribute("value",valorCelda);
					nuevoInput.setAttribute("style","width: 500px !important;");					
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);					

					nuevaCelda = document.createElement("td");					
					valorCelda= dato.cantidad2 && dato.cantidad2 !== "" ? dato.cantidad2 : dato.cantidad && dato.cantidad !== "" ? dato.cantidad : 0;
					nuevoInput = document.createElement("input");					
					nuevoInput.setAttribute("type","number");
					nuevoInput.setAttribute("id","cantidadOt_" + dato.presupuesto);
					nuevoInput.setAttribute("value",valorCelda);
					nuevoInput.setAttribute("style","text-align: right; width: 120px;");					
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);

					nuevaCelda= document.createElement("td");		
					valorCelda = dato.fechaInicioReal && dato.fechaInicioReal !== "" ? dato.fechaInicioReal["date"].substring(0, 10) : "";
					nuevoInput = document.createElement("input");
					nuevoInput.setAttribute("type","date");
					nuevoInput.setAttribute("id","fechaInicioReal_" + dato.presupuesto);
					nuevoInput.setAttribute("value",valorCelda);
					nuevoInput.setAttribute("style","width: 140px !important;");					
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);

					nuevaCelda= document.createElement("td");		
					valorCelda = dato.fechaTerminado && dato.fechaTerminado !== "" ? dato.fechaTerminado["date"].substring(0, 10) : "";
					nuevoInput = document.createElement("input");
					nuevoInput.setAttribute("type","date");
					nuevoInput.setAttribute("id","fechaTerminado_" + dato.presupuesto);
					nuevoInput.setAttribute("value",valorCelda);
					nuevoInput.setAttribute("style","width: 140px !important;");					
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);

					nuevaCelda= document.createElement("td");				
					nuevoInput = document.createElement("input");
					nuevoInput.setAttribute("type","image");
					nuevoInput.setAttribute("id",dato.presupuesto+ "_modificarDetalle");					
					nuevoInput.setAttribute("src","imagenes/modificar.png");	
					nuevoInput.setAttribute("style","width:15px;");		
					nuevoInput.setAttribute("onclick","modificarOt('" + dato.presupuesto + "')");			
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);				

					nuevaCelda = document.createElement("td");	
					nuevoInput = document.createElement("input");
					nuevoInput.setAttribute("type","image");
					nuevoInput.setAttribute("id",dato.presupuesto+ "_verCantidadDetalle");					
					nuevoInput.setAttribute("src","imagenes/ojo.png");	
					nuevoInput.setAttribute("style","width:15px;");		
					nuevoInput.setAttribute("onclick","verCantidadDetalleProduccion('" + dato.presupuesto + "')");			
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);

					nuevaCelda = document.createElement("td");	
					nuevoInput = document.createElement("input");
					nuevoInput.setAttribute("type","image");
					nuevoInput.setAttribute("id",dato.presupuesto+ "_observacion");					
					nuevoInput.setAttribute("src","imagenes/ojo.png");	
					nuevoInput.setAttribute("style","width:15px;");		
					nuevoInput.setAttribute("onclick","verObservacionOt('" + dato.presupuesto + "')");			
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);

					nuevaCelda = document.createElement("td");	
					nuevoInput = document.createElement("input");
					nuevoInput.setAttribute("type","button");
					nuevoInput.setAttribute("class","btn btn-info");					
					nuevoInput.setAttribute("style","height: =5px;line-height: 5px;");		
					nuevoInput.setAttribute("onclick","imprimirOtProduccion('" + dato.presupuesto + "')");			
					nuevoInput.setAttribute("value","OT");
					nuevaCelda.appendChild(nuevoInput);
					nuevaFila.appendChild(nuevaCelda);

					datosOt.appendChild(nuevaFila);
				}
				console.timeEnd('mostrarDatos');*/
				////////////////////////////////////////////////////





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


					if (datos[contador]["facturaCibeles"]!="/")
					{
						contenido += '<td align="center" style="background: green;" title="'+datos[contador]["facturaCibeles"]+'-Cibeles"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["presupuesto"]+'</span></td>';
					}
					else if (datos[contador]["facturaClayma"]!="/")
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
						contenido += '<td><input type="text" id="campanaOt_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["campana2"] + '" style="width:  300px !important;"></input></td>';
					}
					else {
						contenido += '<td><label>' + datos[contador]["campana"] + '</label></td>';
						contenido += '<td><input type="text" id="campanaOt_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["campana"] + '" style="width:  300px !important;"></input></td>';
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
						contenido += '<td><input type="date" id="fechaTerminado_' + datos[contador]["presupuesto"] + '" value="" max="' + datos[contador]["fechaCompromiso"]["date"].substring(0, 10) + '"  onChange="verificarFechaTerminado(this.value,this.max, this.id)" style="width:  140px !important;"></input></td>';
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

				/*var contenido = [];
				contenido.push('<tr class="centrarTexto">');
				contenido.push('<th align="center">Presupuesto</th>');

				contenido.push('<th>Cliente</th>');
				contenido.push('<th>Campaña</th>');
				contenido.push('<th>Cantidad</th>');
				contenido.push('<th>Inicio</th>');
				contenido.push('<th>Terminado</th>');



				contenido.push('<th></th>');
				contenido.push('<th>Det.</th>');
				contenido.push('<th>Obs.</th>');
				contenido.push('<th></th>');


				contenido.push('</tr>');

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
					
					contenido.push('<tr ' + contraste + '>');

					contenido.push('<td align="center">' + datos[contador]["presupuesto"] + '</td>');
					contenido.push('<td align="left" style="width:  300px !important;">' + datos[contador]["cliente"] + '</td>');


					if (datos[contador]["campana2"] != "" && datos[contador]["campana2"] != null) {
						contenido.push('<td><input type="text" id="campanaOt_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["campana2"] + '" style="width:  300px !important;"></input></td>');
					}
					else {
						contenido.push('<td><input type="text" id="campanaOt_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["campana"] + '" style="width:  300px !important;"></input></td>');
					}

					if (datos[contador]["cantidad2"] != "" && datos[contador]["cantidad2"] != null && datos[contador]["cantidad2"] != datos[contador]["cantidad"]) {
						var valor = datos[contador]["cantidad2"];
						


						contenido.push('<td><input type="number" id="cantidadOt_' + datos[contador]["presupuesto"] + '" value="' + valor + '" style="text-align: right; width: 120px;"></input></td>');
					}
					else {
						var valor = datos[contador]["cantidad"];
						
						if (valor == "" || valor == null || valor == "null") {
							valor = 0;
						}

						contenido.push('<td><input type="number" id="cantidadOt_' + datos[contador]["presupuesto"] + '" value="' + valor + '" style="text-align: right; width:  120px;"></input></td>');
					}


					if (datos[contador]["fechaInicioReal"] != "" && datos[contador]["fechaInicioReal"] != null) {
						contenido.push('<td><input type="date" id="fechaInicioReal_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["fechaInicioReal"]["date"].substring(0, 10) + '" style="width:  140px !important;"></input></td>');
					}
					else {
						contenido.push('<td><input type="date" id="fechaInicioReal_' + datos[contador]["presupuesto"] + '" value="" style="width:  140px !important;"></input></td>');
					}

					if (datos[contador]["fechaTerminado"] != "" && datos[contador]["fechaTerminado"] != null) {
						contenido.push('<td><input type="date" id="fechaTerminado_' + datos[contador]["presupuesto"] + '" value="' + datos[contador]["fechaTerminado"]["date"].substring(0, 10) + '" style="width:  140px !important;"></input></td>');
					}
					else {
						contenido.push('<td><input type="date" id="fechaTerminado_' + datos[contador]["presupuesto"] + '" value="" max="' + datos[contador]["fechaCompromiso"]["date"].substring(0, 10) + '"  onChange="verificarFechaTerminado(this.value,this.max, this.id)" style="width:  140px !important;"></input></td>');
					}


					contenido.push('<td><input type="image" id="' + datos[contador]["presupuesto"] + '_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarOt(' + datos[contador]["presupuesto"] + ')"></td>');

					contenido.push('<td><input type="image" id="' + datos[contador]["presupuesto"] + '_verCantidadDetalle" value="" src="imagenes/ojo.png" style="width:15px;" onclick="verCantidadDetalleProduccion(' + datos[contador]["presupuesto"] + ')"></td>');


					contenido.push('<td><input type="image" id="' + datos[contador]["presupuesto"] + '_observacion" value="" src="imagenes/ojo.png" style="width:15px;" onclick="verObservacionOt(' + datos[contador]["presupuesto"] + ')"></td>');

					contenido.push('<td><button type="button" class="btn btn-info" style="height: =5px;line-height: 5px;" onclick="imprimirOtProduccion(' + datos[contador]["presupuesto"] + ')">OT</button></td>');

					contenido.push('</tr>');
					

					contador++;
				}
				//console.timeEnd('OT');
				

				console.time('mostrarDatos');
				document.getElementById("listadoOt").innerHTML = contenido.join('');
				console.timeEnd('mostrarDatos');*/
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
		peticionUnica1.open("POST", "ajax/mostrarPresupuestoPorNumero.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaVerObservacionOt(presupuesto);
		peticionUnica1.send(query_string);
	}
}

function consultaVerObservacionOt(presupuesto) {
	var consulta = "accion=mostrarPresupuestoPorNumero";
	consulta += "&numeroPresupuesto=" + presupuesto;

	return consulta;
}

function mostrarVerObservacionOt() {
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			if (peticionUnica1.responseText.substr(0, 5) == "Error") {
				alert(peticionUnica1.responseText);
			}
			else {
				var datos = new Array;

				try {
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error) {
					datos = "";
				}

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
			peticionUnica1.open("POST", "ajax/modificarOtProduccion.php", false);
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
	var consulta = "accion=modificarOt";

	consulta += "&numPresupuesto=" + numOt;
	//consulta+="&campana="+document.getElementById("campanaOt_"+numOt).value;
	consulta += "&campana=" + reemplazarSimbolos(document.getElementById("campanaOt_" + numOt).value);





	consulta += "&cantidad=" + document.getElementById("cantidadOt_" + numOt).value;

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

	return consulta;
}

function mostrarModificarOt() {
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			if (peticionUnica1.responseText.substr(0, 5) == "Error") {
				alert(peticionUnica1.responseText);
			}
			else {
				alert(peticionUnica1.responseText);

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


function modificarObservacionOT() //js_facturas
{

	peticionUnica1 = crearComunicacion(peticionUnica1);

	if (peticionUnica1) {
		peticionUnica1.onreadystatechange = mostrarModificarObservacionOT;
		peticionUnica1.open("POST", "ajax/modificarObservacionPresupuesto.php", false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaModificarObservacionOT();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarObservacionOT() {
	var consulta = "accion=modificarObservacionOT";
	consulta += "&numeroPresupuesto=" + document.getElementById("numOtModal").innerHTML;

	/*var texto = reemplazarSimbolos(document.getElementById("observacionModal").value);
	
	consulta += "&observacion=" + texto;	*/

	consulta += "&observacion=" + reemplazarSimbolos(document.getElementById("observacionModal").value);


	return consulta;
}

function mostrarModificarObservacionOT() {
	if (peticionUnica1.readyState == 4) {
		if (peticionUnica1.status == 200) {
			if (peticionUnica1.responseText.substr(0, 5) == "Error") {
				alert(peticionUnica1.responseText);
			}
			else {
				alert(peticionUnica1.responseText);
				//$("#observacionOtModal").modal('hide');
			}
			peticionUnica1 = null;
		}
	}
}

function gestionExportarExcelOtCibeles() //js_facturas
{

	document.getElementById("exportarCondiciones").value = laCondicion;
	document.getElementById("formExportarExcel").submit();
}



