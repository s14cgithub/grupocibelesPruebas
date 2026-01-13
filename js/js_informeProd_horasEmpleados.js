var peticionUnica1 = null;


function cambiarFechaFin(inputInicio, inputFin)
{
	if ((document.getElementById(inputInicio).value != "" && document.getElementById(inputFin).value == "") || document.getElementById(inputInicio).value>document.getElementById(inputFin).value)
	{
		document.getElementById(inputFin).value = document.getElementById(inputInicio).value;
	}	
}


function cargarInformePorDia()
{

	horasARealizar_Empleado = "";
	horasRealizadas_Empleado = "";
	horasDiferencia_Empleado = "";

	document.getElementById("informeNombreEmpleado").innerHTML = "";
	document.getElementById("informeTiemposEmpleado").innerHTML = "";


	
	if (document.getElementById("buscarFecha").value!="" && document.getElementById("buscarFechaFin").value!="")
	{
		
		var temporal = document.getElementById("buscarFecha").value;		
		var anio = temporal.substr(0, temporal.indexOf('-'));
		var mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
		var dia = temporal.substr(temporal.lastIndexOf('-')+1);
		
		var temporalFin = document.getElementById("buscarFechaFin").value;		
		var anioFin = temporalFin.substr(0, temporalFin.indexOf('-'));
		var mesFin = temporalFin.substr(temporalFin.indexOf('-')+1,temporalFin.length  - temporalFin.lastIndexOf('-') -1);
		var diaFin = temporalFin.substr(temporalFin.lastIndexOf('-')+1);

		if (document.getElementById("buscarFecha").value == document.getElementById("buscarFechaFin").value)
		{
			document.getElementById("InformeDia").innerHTML = dia + "/" + mes + "/" + anio;
		}
		else
		{
			document.getElementById("InformeDia").innerHTML = dia + "/" + mes + "/" + anio + " - " + diaFin + "/" + mesFin + "/" + anioFin;
		}
		
		cargarInformePorDia2();
	}
	else if (document.getElementById("buscarFecha").value=="")
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("buscarFecha").focus();
	}
	else if (document.getElementById("buscarFechaFin").value=="")
	{
		alert("Introducir una fecha Fin");
	}
}

function cargarInformePorDia2()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarInformePorDia;
		peticionUnica1.open("POST","ajax/produccion_verInformePorDia.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaCargarInformePorDia();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarInformePorDia()
{	
	var consulta = "accion=verInformePorDia";
	consulta += "&fecha=" + document.getElementById("buscarFecha").value;
	consulta += "&fechaFin=" + document.getElementById("buscarFechaFin").value;
	return consulta;	
}

function mostrarCargarInformePorDia()
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
				
				var contenido = '<thead class="thead-dark"><tr><th class="thead-dark">Empleado</th><th class="thead-dark">Tiempo A Realizar</th><th class="thead-dark">Tiempo Trabajado</th><th class="thead-dark">Diferencia</th></tr></thead>';
				contenido += '<tbody>';
				
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				if (datos.length<=0)
				{
					//alert("No hay ningun registro");
					contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
				}
				else
				{
					var contador = 0;
				
					while  (contador<datos.length)
					{

						let aux = datos[contador]["nombreEmpleado"].replace(' ','_');
						let enlaceSancion="";

						var contraste=(contador%2)?'class="contraste1"':'class="contraste2"';
						var contraste2=(contador%2)?'class="contraste1':'class="contraste2';						
						var guion = '';
						
						
						if (parseInt(datos[contador]["diferencia1"])<=0)
						{
							contraste2 += ' colorVerde"';	
							guion = '';
						}
						else if (parseInt(datos[contador]["diferencia1"])>0)
						{
							contraste2 += ' colorRojo"';
							/*if (String(datos[contador]["diasDiferencia"]).indexOf('-')<0)
							{
								guion = '- ';
							}*/

							enlaceSancion = "onclick=imprimirSancionPDA(this.id);";
							//enlaceSancion = "onclick=imprimirSancionPDA(datos[contador][\"nombreEmpleado\"].replace(' ','_'));";
							
							
						}
						else
						{
							contraste2 += '"';
						}
						
						//contenido += '<tr><td id="'+aux+'" '+contraste+'><a href="javascript:cargarInformePorDiaDetalle(\''+datos[contador]["nombreEmpleado"]+'\');">'+ datos[contador]["nombreEmpleado"]+'</a></td><td  id="'+aux+'_tr" '+contraste+'>'+ datos[contador]["horasARealizar1"]+'</td><td id="'+aux+'_tt" '+contraste+'>'+ datos[contador]["horasRealizadas1"]+'</td><td id="'+aux+'_dif" '+contraste2+'  align="center" '+enlaceSancion+'>'+guion+datos[contador]["diferencia1"]+'</td></tr>';
						contenido += '<tr><td id="'+aux+'" '+contraste+'><a href="javascript:cargarInformePorDiaDetalle(\''+datos[contador]["nombreEmpleado"]+'\',\''+datos[contador]["horasARealizar1"]+'\',\''+datos[contador]["horasRealizadas1"]+'\',\''+datos[contador]["diferencia1"]+'\');">'+ datos[contador]["nombreEmpleado"]+'</a></td><td  id="'+aux+'_tr" '+contraste+'>'+ datos[contador]["horasARealizar1"]+'</td><td id="'+aux+'_tt" '+contraste+'>'+ Number(datos[contador]["horasRealizadas1"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+'</td><td id="'+aux+'_dif" '+contraste2+'  align="center" '+enlaceSancion+'>'+guion+Number(datos[contador]["diferencia1"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+'</td></tr>';
						contador++;

					}
					
					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido; 
				cargarInformePorDiaTotal();
				
				
				
			}
			
			peticionUnica1 = null;
		}
	}						
}

function cargarInformePorDiaTotal()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarInformePorDiaTotal;
		peticionUnica1.open("POST","ajax/produccion_verInformePorDiaTotal.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarInformePorDiaTotal();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarInformePorDiaTotal()
{	
	var consulta = "accion=verInformePorDia";
	consulta += "&fecha=" + document.getElementById("buscarFecha").value;
	consulta += "&fechaFin=" + document.getElementById("buscarFechaFin").value;
	return consulta;	
}

function mostrarCargarInformePorDiaTotal()
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
				
				var contenido = '';
				
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				if (datos.length<=0)
				{
					//alert("No hay ningun registro");
					
				}
				else
				{
					var contraste='class="contraste2"';
					var contraste2='class="contraste2';
											
					var guion = '';

					if (parseInt(datos[0]["segundosDiferencia"])>0)
					{
						contraste2 += ' colorVerde"';	
						guion = '';
					}
					else if (parseInt(datos[0]["segundosDiferencia"])<0)
					{
						contraste2 += ' colorRojo"';
						if (String(datos[0]["diasDiferencia"]).indexOf('-')<0)
						{
							guion = '- ';
						}

					}
					else
					{
						contraste2 += '"';
					}
					
					
					
					//contenido += '<tr><td></td></tr><tr'+contraste+'><td '+contraste+'><b>TOTAL</b></td><td '+contraste+'><b>'+ datos[0]["diasArealizar"]+":"+ datos[0]["horasArealizar"]+'</b></td><td '+contraste+'><b>'+ datos[0]["diasTrabajados"]+":"+ datos[0]["horasTrabajados"]+'</b></td><td '+contraste2+'  align="center"><b>'+guion+ datos[0]["diasDiferencia"]+":"+datos[0]["diferencia"]+'</b></td></tr>';
					contenido += '<tr><td></td></tr><tr'+contraste+'><td '+contraste+'><b>TOTAL</b></td><td '+contraste+'><b>'+Number(datos[0]["horasARealizar1"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+'</b></td><td '+contraste+'><b>'+ Number(datos[0]["horasRealizadas1"]).toLocaleString('de-DE',{minimumFractionDigits: 2}) +'</b></td><td '+contraste2+'  align="center"><b>'+guion+ Number(datos[0]["diferencia1"]).toLocaleString('de-DE',{minimumFractionDigits: 2}) +'</b></td></tr>';
					
					
					
				}
											

				document.getElementById("resultadoInforme").innerHTML += contenido; 
				
				
				
				
			}
			
			peticionUnica1 = null;
		}
	}						
}

var horasARealizar_Empleado="";
var horasRealizadas_Empleado="";
var horasDiferencia_Empleado="";

function cargarInformePorDiaDetalle(nombreEmpleado,horasARealizar2,horasRealizadas2,diferencia2)
{

	horasARealizar_Empleado = horasARealizar2;
	horasRealizadas_Empleado = horasRealizadas2;
	horasDiferencia_Empleado = diferencia2;

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarInformePorDiaDetalle;
		peticionUnica1.open("POST","ajax/produccion_verInformePorDiaDetalle.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarInformePorDiaDetalle(nombreEmpleado);
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarInformePorDiaDetalle(nombreEmpleado)
{	
	var consulta = "accion=verInformePorDiaDetalle";
	consulta += "&fecha=" + document.getElementById("buscarFecha").value;
	consulta += "&fechaFin=" + document.getElementById("buscarFechaFin").value;
	consulta += "&nombreEmpleado=" +  nombreEmpleado
	return consulta;	
}

function mostrarCargarInformePorDiaDetalle()
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
				
				var contenido = '';
				if (datos.length>0)
				{

					var espacios= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					document.getElementById("informeNombreEmpleado").innerHTML = datos[0]["nombreEmpleado"];
					document.getElementById("informeTiemposEmpleado").innerHTML = "<b>Tiempo a Realizar: "+Number(horasARealizar_Empleado).toLocaleString('de-DE',{minimumFractionDigits: 2})+espacios+"Tiempo Realizado: " +Number(horasRealizadas_Empleado).toLocaleString('de-DE',{minimumFractionDigits: 2}) +espacios + "Diferencia: " +Number(horasDiferencia_Empleado).toLocaleString('de-DE',{minimumFractionDigits: 2})  + "</b>"; 

					horasARealizar_Empleado = "";
					horasRealizadas_Empleado = "";
					horasDiferencia_Empleado = "";

					
					//contenido = '<thead class="thead-dark" sinBordesMargenPaddin><tr  class="sinBordesMargenPaddin"><th class="thead-dark sinBordesMargenPaddin" colspan="10"><h1 class="sinBordesMargenPaddin"><b>'+datos[0]["nombreEmpleado"]+'</b></h1></th></tr></thead>'; 
					
					

				}				
				
				contenido += '<thead class="thead-dark"><tr><th class="thead-dark">id</th><th class="thead-dark">Concepto</th><th class="thead-dark">Código</th><th class="thead-dark">Inicio</th><th class="thead-dark">Fin</th><th class="thead-dark">cantidad</th><th class="thead-dark">observaciones</th><th class="thead-dark">horas</th><th class="thead-dark">precio/unidad</th><th class="thead-dark">porcentaje</th></tr></thead>';
				contenido += '<tbody>';
				
				if (datos.length<=0)
				{
					//alert("No hay ningun registro");
					contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
				}
				else
				{
					var contador = 0;
				
					var porcentajeTotal=0.00;
					var porcentajeTotalContador=0;

					while  (contador<datos.length)
					{					

						var contraste=(contador%2)?'class="contraste1"':'class="contraste2"';
						//var contraste = "";
						
						var inicio_anio = datos[contador]["horaInicio"]["date"].substr(0,datos[contador]["horaInicio"]["date"].indexOf('-'));
						var inicio_mes = datos[contador]["horaInicio"]["date"].substr(datos[contador]["horaInicio"]["date"].indexOf('-')+1,datos[contador]["horaInicio"]["date"].lastIndexOf('-') - datos[contador]["horaInicio"]["date"].indexOf('-')-1);
						var inicio_dia = datos[contador]["horaInicio"]["date"].substr(datos[contador]["horaInicio"]["date"].lastIndexOf('-')+1, datos[contador]["horaInicio"]["date"].indexOf(' ') - datos[contador]["horaInicio"]["date"].lastIndexOf('-')-1);						
						var inicio_hora = datos[contador]["horaInicio"]["date"].substr(datos[contador]["horaInicio"]["date"].indexOf(' ')+1,datos[contador]["horaInicio"]["date"].indexOf('.') - datos[contador]["horaInicio"]["date"].indexOf(' ')-1);						
						var inicio = inicio_dia+ "/" + inicio_mes + "/" + inicio_anio + " " + inicio_hora;
						
						var fin_anio = datos[contador]["horaFin"]["date"].substr(0,datos[contador]["horaFin"]["date"].indexOf('-'));
						var fin_mes = datos[contador]["horaFin"]["date"].substr(datos[contador]["horaFin"]["date"].indexOf('-')+1,datos[contador]["horaFin"]["date"].lastIndexOf('-') - datos[contador]["horaFin"]["date"].indexOf('-')-1);
						var fin_dia = datos[contador]["horaFin"]["date"].substr(datos[contador]["horaFin"]["date"].lastIndexOf('-')+1, datos[contador]["horaFin"]["date"].indexOf(' ') - datos[contador]["horaFin"]["date"].lastIndexOf('-')-1);						
						var fin_hora = datos[contador]["horaFin"]["date"].substr(datos[contador]["horaFin"]["date"].indexOf(' ')+1,datos[contador]["horaFin"]["date"].indexOf('.') - datos[contador]["horaFin"]["date"].indexOf(' ')-1);						
						var fin = fin_dia+ "/" + fin_mes + "/" + fin_anio + " " + fin_hora;
						
						var presupuesto = datos[contador]["codigoBarras"].split('-');
						
										
						contenido += '<tr><td '+contraste+' align="left">'+ datos[contador]["id"]+'</a></td>';
						contenido += '<td '+contraste+'   align="left" onclick="irAinformeOt(\''+presupuesto[1]+'\');">'+ datos[contador]["concepto"]+'</td>';
						contenido += '<td '+contraste+'>'+ datos[contador]["codigoBarras"]+'</td><td '+contraste+'>'+ inicio+'</td>';
						contenido += '<td '+contraste+'>'+ fin+'</td><td '+contraste+'  align="right">'+ Number(datos[contador]["cantidad"]).toLocaleString('de-DE',{minimumFractionDigits: 0})+'</td>';
						contenido += '<td '+contraste+' align="left">'+ datos[contador]["observaciones"]+'</td><td '+contraste+'>'+ datos[contador]["horas"]+'</td>';
						contenido += '<td '+contraste+'  align="right">'+ Number(datos[contador]["precio/unidad"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+'</td>';
						contenido += '<td '+contraste+'  align="right">'+ Number(datos[contador]["porcentaje"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+'</td></tr>';


						if (datos[contador]["porcentaje"]!="0" && datos[contador]["porcentaje"] != null && datos[contador]["porcentaje"] != "null" && datos[contador]["porcentaje"]!=".000")
						{
							porcentajeTotal += parseFloat(datos[contador]["porcentaje"]);
							porcentajeTotalContador ++;
						}

						
						contador++;


						//Number(datos[contador]["precioTotal"]).toLocaleString('de-DE',{minimumFractionDigits: 2})

					}

					var mediaPorcentaje = porcentajeTotal/porcentajeTotalContador;
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td></td>';
					contenido += '<td colspan="2" align="right"><b>Media Porcentaje: '+Number(mediaPorcentaje).toLocaleString('de-DE',{minimumFractionDigits: 2})+'</b></td>';
					

					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido; 
				
				
				
				
			}
		}
	}						
}

function irAinformeOt(presupuesto)
{
	//window.location.href = "http://172.26.0.17:8080/gestionGrupocibeles/informeProd_ot.php?presupuesto="+presupuesto;
	window.location.href = "./informeProd_ot.php?presupuesto="+presupuesto;
}

/*function imprimirPersonalizado(id){
	document.getElementById('sancion').innerHTML = "El empleado "+document.getElementById(aux).innerHTML+ " bla bla bla bla el día " + document.getElementById("buscarFecha").value + " debería haber registrado con la pda " + document.getElementById(id + "_tr").innerHTML + "; pero solo hay registrado: " + document.getElementById(id + "_tt").innerHTML + ". Por tanto habiendo una diferencia de " + document.getElementById(id + "_dif").innerHTML + " se procede a una sancion bla bla bla bla";
	var imprimirContenido = document.getElementById('sancion').innerHTML;
	w = window.open();
	w.document.write(imprimirContenido);
	w.document.close(); // necessary for IE >= 10
	w.focus(); // necessary for IE >= 10
	w.print();
	w.close();
	return true;
} */

function imprimirSancionPDA(datosEmpleado)
{
	datosEmpleado = datosEmpleado.substr(0,datosEmpleado.length-4);
	
	document.getElementById("empleado_sancion").value = datosEmpleado.replace('_',' ');
	document.getElementById("fechaInicio_sancion").value = document.getElementById("buscarFecha").value;
	document.getElementById("fechaFin_sancion").value = document.getElementById("buscarFechaFin").value;
	document.getElementById("tiempoArealizar_sancion").value = document.getElementById(datosEmpleado + '_tr').innerHTML;
	document.getElementById("tiempoTrabajado_sancion").value = document.getElementById(datosEmpleado + "_tt").innerHTML;
	document.getElementById("diferencia_sancion").value = document.getElementById(datosEmpleado + "_dif").innerHTML;

	document.getElementById("formImprimirSancionEmpleado").submit();

}