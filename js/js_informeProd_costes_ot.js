var peticionUnica1 = null;
var minutosTotalTotal=0;
var cantidadTrabajadas=0;
var detallesTransporte= "";
var detallesSatifaccion= "";
var detallesTiempoReal="";
var conversor=1;


function cargarInformePorOt()
{
	if (document.getElementById("buscarOt").trim != "")
	{
		document.getElementById("InformeOt").innerHTML = document.getElementById("buscarOt").value;
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarInformePorOt;
			peticionUnica1.open("POST","ajax/produccion_verInformePorOtCostes.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarInformePorOt();
			peticionUnica1.send(query_string);						
		}
	}
	else
	{
		alert("Introducir una ot");
	}
}

function consultaCargarInformePorOt()
{	
	var consulta = "accion=verInformePorOt";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarInformePorOt()
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
				//alert(peticion7.responseText);				
				
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				var contenido = '';
				
				if (datos.length>0)
				{
					contenido += '<tr id="clientePantalla"><th style="text-align:right;">CLIENTE: </th><th class="" colspan="4"  style="text-align:left;">'+datos[0]["cliente"]+'</th></tr>';
					contenido += '<tr id="campanaPantalla"><th style="text-align:right;">CAMPAÑA: </th><th class="" style="font-size: 20px;text-align:left;" colspan="4"><b>'+datos[0]["campana"]+'</b></th></tr>';
					//contenido += '<thead class="thead-dark"><tr><th class="thead-dark" colspan="5">'+datos[0]["campana"]+'</th></tr></thead>';
				}
				
				//contenido += '<thead class="thead-dark"><tr><th class="thead-dark">Departamento / Tipo / Proceso</th><th class="thead-dark">Cantidad</th><th class="thead-dark">Horas Realizadas</th></tr></thead>';
				
				
				contenido += '<tbody>';
				if (datos.length<=0)
				{
					//alert("No hay ningun registro");
					contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
				}
				else
				{
					var contador = 0;
					var contadorContraste=0;
				
					var procesoAnterior = "ajasdkfajd";
					
					var contraste='';
					var contraste2='';

					var minutosTotal=0;
					var cantidadTotal=0;
					var costesTotal=0;

					var primeraVez=true;
					var cantidadGuardada = false;

					while  (contador<datos.length)
					{
						if (procesoAnterior!=datos[contador]["departamento"] + " / " + datos[contador]["tipoProceso"] + " / " + datos[contador]["proceso"])
						{
							
							if (primeraVez == false)
							{
								contenido += '<tr class="textoNegrita ' + contraste2 + '">';
								if (minutosTotal != 0 || cantidadTotal!=0 )
								{
									

									contenido += '<td colspan="5" style="padding:5px !important;">Cantidad Total: ' + Number(cantidadTotal).toLocaleString('es');
									contenido += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
									contenido += 'Minutos Total: ' + minutosTotal;
									contenido += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
									
									var media = cantidadTotal/minutosTotal;
									if (cantidadTotal=0 || minutosTotal==0)
									{
										media=0;
									}
									contenido += 'Media Total: ' +  Number(media).toLocaleString('es'); 
									contenido += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	
									contenido += 'Costes Total: ' +  Number(costesTotal).toLocaleString('es');
									
								}
								else 
								{
									contenido += '<td colspan="5">';
								}

								contenido += '<hr style="height:1px !important;color: black;background-color: black;margin-top:0px !important; margin-bottom: 0px !important;"></td>';
									
								contenido += '</tr>'; 
								
								
								contadorContraste++;
								//contraste=(contadorContraste%2)?'class="contraste2"':'class="contraste3"';
								//contraste2=(contadorContraste%2)?'contraste2':'contraste3';
								contraste=contraste2='';
							}
							else
							{
								primeraVez = false;
							}
							

							minutosTotal=0;
							cantidadTotal=0;
							costesTotal=0;

							
							

							contenido += '<tr class="textoNegrita textoIzquierda"  >';
							contenido += '<td  ' + contraste + ' colspan="5" style="padding:4px !important;">' + datos[contador]["departamento"] + " / " + datos[contador]["tipoProceso"] + " / " + datos[contador]["proceso"] + "</td>" ;
							contenido += '</tr>';
						
							contenido += '<tr class="textoNegrita">';
							contenido += '<td class="centrarTexto ' + contraste2 +'" style="padding:0px !important;">Fecha</td>';
							contenido += '<td class="textoIzquierda ' + contraste2 +'" style="padding:0px !important;"">Empleado</td>';
							contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;"">Minutos</td>';
							contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;"">Cantidad</td>' ;
							contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;"">Media</td>' ;
							contenido += '</tr>';
							procesoAnterior = datos[contador]["departamento"] + " / " + datos[contador]["tipoProceso"] + " / " + datos[contador]["proceso"];
						}

						
						var minutosRendondeados = Math.round(datos[contador]["segundosTrabajados"]/60);
						


						if (datos[contador]["fechaInicio"]!=null)
						{
							contenido += '<tr>';

							let fechaInicio = datos[contador]["fechaInicio"]; 

							let diaInicio = fechaInicio.substr(8,2);
							let mesInicio = fechaInicio.substr(5,2);
							let anioInicio = fechaInicio.substr(0,4);



							contenido += '<td class="centrarTexto ' + contraste2 +'" style="padding:0px !important;">'+datos[contador]["fechaInicio"]+'</td>';
							contenido += '<td class="textoIzquierda ' + contraste2 +'" style="padding:0px !important;">'+datos[contador]["nombreEmpleado"]+'</td>';
							
							if (minutosRendondeados == 0)
							{
								contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;">'+   Number(datos[contador]["segundosTrabajados"]).toLocaleString('de-DE') +' Seg.</td>';
							}
							else
							{
								contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;">'+   Number(minutosRendondeados).toLocaleString('de-DE') +'</td>';
							}
							

							

							//if (datos[contador]["departamento"]!="Almacen" && cantidadGuardada == false)
							//if (datos[contador]["departamento"]!="Almacen" && datos[contador]["cantidad"]>cantidadTrabajadas)
							if (datos[contador]["departamento"]!="Almacen" )
							{
								cantidadTrabajadas += datos[contador]["cantidad"];
								cantidadGuardada = true;
							}

							contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;">'+   Number(datos[contador]["cantidad"]).toLocaleString('de-DE')+'</td>' ;
							contenido += '<td class="textoDerecha ' + contraste2 +'" style="padding:0px !important;">'+   Number(datos[contador]["media"]).toLocaleString('de-DE')+'</td>' ;
							contenido += '</tr>';
						}


						
						minutosTotal += minutosRendondeados;
						cantidadTotal += datos[contador]["cantidad"];
						costesTotal += parseFloat(datos[contador]["costeHora"],2);

						minutosTotalTotal+=minutosRendondeados;

						
						//contenido += '<tr><td '+contraste+'><span onclick="cargarInformePorOtDetalle(\''+datos[contador]["codigoBarras"]+'\');">'+ datos[contador]["concepto"]+'</span></td><td '+contraste+'>'+ datos[contador]["cantidad"]+'</td><td '+contraste+'>'+ datos[contador]["horas"]+'</td></tr>';
						contador++;

					}
					

					if (minutosTotal!=0 || cantidadTotal!=0)
					{
						contenido += '<tr class="textoNegrita centrarTexto"><td colspan="5">Minutos Total: ' +  Number(minutosTotal).toLocaleString('de-DE');
						contenido += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad Total: ' +  Number(cantidadTotal).toLocaleString('de-DE');
						
						var media = cantidadTotal/minutosTotal;
						if (cantidadTotal=0 || minutosTotal==0)
						{
							media=0;
						}
						contenido += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						contenido += 'Media Total: ' +  Number(media).toLocaleString('es');					
						
	
						contenido += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Costes Total: ' +  Number(costesTotal).toLocaleString('de-DE',{minimumFractionDigits: 3}) + '</td></tr>'; 
					}

					contenido += '<td colspan="5"><hr style="height:1px !important;color: black;background-color: black;margin-top:0px !important; margin-bottom: 0px !important;"></td>';
					
					 

					//contenido += '<tr class="sinBorde"><td '+contraste+'></td><td '+contraste+'></td><td '+contraste+'></td></tr>';
				    
					//contenido += '<tr class="textoNegrita sinBorde"><td '+contraste+' align="right"></td><td '+contraste+'>Total</td><td '+contraste+'>'+ datos[0]["horasTotal"]+'</td></tr>';
					
					
					

					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido;
				


				versiHayConversor();

				cargarTotales();

				

				//cargarImagenes();
				
				if (document.getElementById("imprimirDetalles").checked==true)
				{
					cargarDetallesCalculosInformatica();
					cargarDetallesCalculosPresupuestos();
					cargarDetallesSueltos();
				}
				
				
				
				
			}
		}
	}						
}


function cargarTotales()
{
	if (document.getElementById("buscarOt").trim != "")
	{
		document.getElementById("InformeOt").innerHTML = document.getElementById("buscarOt").value;
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarTotales;
			peticionUnica1.open("POST","ajax/produccion_verInformePorOtCostesTotales.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarTotales();
			peticionUnica1.send(query_string);						
		}
	}
	else
	{
		alert("Introducir una ot");
	}
}

function consultaCargarTotales()
{	
	var consulta = "accion=verInformePorOt";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarTotales()
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
				
				contenido += '<tbody>';
				contenido += '<tr><td colspan="6"><table align="center">';
					
				//contenido += '<tr class="textoNegrita"><td colspan="4">Minutos Total: ' + minutosTotal/60 + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad Total: ' +  cantidadTotal + '</td></tr>';

				//contenido += '<tr><td>';

				
				contenido += '<tr>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Fecha Inicio:</td>';

				let fechaInicio = datos[0]["fechaInicioReal"]["date"]; 

				let diaInicio = fechaInicio.substr(8,2);
				let mesInicio = fechaInicio.substr(5,2);
				let anioInicio = fechaInicio.substr(0,4);

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' + diaInicio + '/' + mesInicio + '/' + anioInicio+ '</td>'; 

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Coste Personal:</td>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">'+Number(datos[0]["costeHora"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €'  + '</td>';

				contenido += '</tr>';
				
				
				
				
				
				contenido += '<tr>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Fecha Compromiso:</td>';

				let fechaCompromiso = datos[0]["fechaCompromiso"]["date"]; 

				let diaCompromiso = fechaCompromiso.substr(8,2);
				let mesCompromiso = fechaCompromiso.substr(5,2);
				let anioCompromiso = fechaCompromiso.substr(0,4);

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' + diaCompromiso + '/' + mesCompromiso + '/' + anioCompromiso+ '</td>'; 

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Fecha Terminado:</td>';

				let fechaTerminado = datos[0]["fechaTerminado"]["date"]; 

				let diaTerminado = fechaTerminado.substr(8,2);
				let mesTerminado = fechaTerminado.substr(5,2);
				let anioTerminado = fechaTerminado.substr(0,4);

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' + diaTerminado + '/' + mesTerminado + '/' + anioTerminado + '</td>'; 

				contenido += '</tr>';

				detallesSatifaccion = "Grado de Satisfacción: fecha de compromiso - fecha terminado: "+diaCompromiso + '/' + mesCompromiso + '/' + anioCompromiso + " - "  + diaTerminado + '/' + mesTerminado + '/' + anioTerminado+ " = "  + +Number(datos[0]["satisfaccion"]).toLocaleString('de-DE')+ " días";
				detallesTiempoReal = "Tiempo Real Realizado: fecha de inicio - fecha terminado: "+diaInicio + '/' + mesInicio + '/' + anioInicio + " - "  + diaTerminado + '/' + mesTerminado + '/' + anioTerminado+ " = "  + Number(datos[0]["tiempoRealizacion"]).toLocaleString('de-DE')+ " días";

				
				/*contenido += '<tr>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Grado Satisfacción:</td>';

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">'+Number(datos[0]["satisfaccion"]).toLocaleString('de-DE')+ ' días'  + '</td>';

				

				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Tiempo Realizado:</td>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">'+Number(datos[0]["tiempoRealizacion"]).toLocaleString('de-DE')+ ' días'  + '</td>';

				contenido += '</tr>';

				*/



				






				//cantidadTrabajadas = 5;
				var revistarCantidad="";
				var revistarCantidad2="";
				var cantidadDelPresupuesto = datos[0]["cantidadPresupuesto"];
				var tantoPorCientoMargen = 20;
				var cantidadMargen = cantidadDelPresupuesto*tantoPorCientoMargen/100;

				var cantidadDiferencia = cantidadTrabajadas - cantidadDelPresupuesto;
				if (cantidadDiferencia<0)
				{
					cantidadDiferencia = cantidadDiferencia * -1;
				}

				if (cantidadDiferencia>cantidadMargen)
				{
					//revistarCantidad = '<b><font style="color: red;"> Revisar</font></b>';
					revistarCantidad = ' style="color: red;"';
					revistarCantidad2 = "<b>Revisar</b>";
				}

				var transporte = cantidadDelPresupuesto*datos[0]["pesoGramos"]/1000*datos[0]["tantoPorCientoTransporte"]/100;

				detallesTransporte = "Transporte: cantidad del presupuesto x (peso Unitario/1000) x (tantoPorciento/100): "+cantidadDelPresupuesto + " x (" + datos[0]["pesoGramos"] + "/1000) x (" + datos[0]["tantoPorCientoTransporte"]+ "/100) = " + Number(transporte).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €';





				contenido += '<tr '+revistarCantidad+'>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Cantidad Trabajadas:</td>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' + Number(cantidadTrabajadas).toLocaleString('de-DE')+ '</td>';



				



				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Cantidad Presupuesto:</td>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">'+ Number(datos[0]["cantidadPresupuesto"]).toLocaleString('de-DE') +" "+revistarCantidad2+'</td>';

				contenido += '</tr>';


				contenido += '<tr>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Minutos Total:</td>';
				var minutosRendondeados = Math.round(datos[0]["segundosTrabajados"]/60);
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' +  Number(minutosTotalTotal).toLocaleString('de-DE') + '</td>';				


				var laMedia =datos[0]["cantidadPresupuesto"]/minutosTotalTotal*60;
				laMedia = Math.trunc(laMedia);
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Transporte ('+datos[0]["pesoGramos"]+' gr.):</td>';			
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' + Number(transporte).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €'  + '</td>';

				minutosTotalTotal=0;
				

				contenido += '</tr>';

				contenido += '<tr>';
				
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Compras a Terceros:</td>';
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">'+ Number(datos[0]["comprasTerceros"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €' + '</td>';


				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Facturado:</td>';
				var importeFactura = parseFloat(datos[0]["importeFactura"])+parseFloat(datos[0]["importeFacturaClayma"]);
				contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' +  Number(importeFactura).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €'  + '</td>';

				contenido += '</tr>';


				if (datos[0]["costePapel"]>0 && datos[0]["costeClick"]>0 )
				{ 
					contenido += '<tr>';
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Papel (Informatica):</td>';			
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' +   Number(datos[0]["costePapel"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €</td>';
					
	
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Click (Informatica):</td>';				
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' +   Number(datos[0]["costeClick"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €</td>';
	
					contenido += '</tr>';					
				}

				if (datos[0]["precioPapel_Presupuesto"]>0 && datos[0]["precioClick_Presupuesto"]>0 )
				{
	
					contenido += '<tr>';
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Papel (Presupuesto):</td>';			
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' +   Number(datos[0]["precioPapel_Presupuesto"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €</td>';
					
	
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">Click (Presupuesto):</td>';				
					contenido += '<td class="textoNegrita textoDerecha textoTamanio20">' +   Number(datos[0]["precioClick_Presupuesto"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €</td>';
	
					contenido += '</tr>';
				}
			


				contenido += '<tr>';

				var costePapel1 = datos[0]["costePapel"]; 
				if (costePapel1==null)
				{
					costePapel1 = 0;
				}

				var beneficios = importeFactura - parseFloat(datos[0]["comprasTerceros"]) - parseFloat(datos[0]["costeHora"])- parseFloat(costePapel1/conversor)- parseFloat(datos[0]["costeClick"]/conversor) - parseFloat(transporte);
				contenido += '<td colspan="2" class="textoNegrita textoDerecha textoTamanio20">BENEFICIOS:</td>';
				contenido += '<td colspan="2" class="textoNegrita textoIzquierda textoTamanio20">' +Number(beneficios).toLocaleString('de-DE',{minimumFractionDigits: 2})+ ' €</td>';
				contenido += '</tr>';
				
			
				
				
			
				
				
				
				contenido += '</tr>';




					
				contenido += '</table>';
				contenido += '</td></tr></tbody>';								

				document.getElementById("resultadoInforme").innerHTML += contenido;
				
				
				cargarImagenes();
				
			
				
				
				
				
			}
		}
	}						
}


/*--------------------------------------------------------------------------------------------------------------------------*/

function cargarDetallesSueltos()
{
	
	var contenido = '';
				
	contenido += '<tbody>';	
	contenido += '<tr>';
	contenido += '<td align="left" class="textoNegrita">';
	contenido += 'Transporte';
	contenido += '</td>'				
	contenido += '</tr>';				
	contenido += '<tr>';
	contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
	contenido += detallesTransporte;
	contenido += '</td>'				
	contenido += '</tr>';
	
		
	contenido += '<tr>';
	contenido += '<td align="left" class="textoNegrita">';
	contenido += 'Grado de Satisfacción';
	contenido += '</td>'				
	contenido += '</tr>';				
	contenido += '<tr>';
	contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
	contenido += detallesSatifaccion;
	contenido += '</td>'				
	contenido += '</tr>';


	contenido += '<tr>';
	contenido += '<td align="left" class="textoNegrita">';
	contenido += 'Tiempo Real de Realización';
	contenido += '</td>'				
	contenido += '</tr>';				
	contenido += '<tr>';
	contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
	contenido += detallesTiempoReal;
	contenido += '</td>'				
	contenido += '</tr>';


	
	contenido += '</tbody>';								

	document.getElementById("resultadoInforme").innerHTML += contenido;
}
function cargarDetallesCalculosInformatica()
{
	if (document.getElementById("buscarOt").trim != "")
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarDetallesCalculosInformatica;
			peticionUnica1.open("POST","ajax/produccion_verInformePorOtCostesDetallesTotales.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarDetallesCalculosInformatica();
			peticionUnica1.send(query_string);						
		}
	}
	else
	{
		alert("Introducir una ot");
	}
}

function consultaCargarDetallesCalculosInformatica()
{	
	var consulta = "accion=verInformePorOtDetalle";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarDetallesCalculosInformatica()
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
				
				contenido += '<tbody>';	
				contenido += '<tr>';
				contenido += '<td align="left" class="textoNegrita">';
				contenido += 'Papel y Click - Informatica';
				contenido += '</td>'				
				contenido += '</tr>';	
				contenido += '<tr>';
				contenido += '<td align="left" style="border-top:0px">';
				contenido += datos[0]["nombreEmpleado"];
				contenido += '</td>'				
				contenido += '</tr>';			
				contenido += '<tr>';
				contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
				contenido += 'Impresora: ' + datos[0]["impresoras"] + '('+datos[0]["tipoImpresora"]+'): click x cantidad x nº caras / conversor: ' + Number(datos[0]["precioClick"]).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' x ' + datos[0]["cantidadEmpleado"]  + ' x ' + datos[0]["numeroCaras"] + ' / ' + conversor + ' = ' + Number(datos[0]["costeClick"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' €';
				contenido += '</td>'				
				contenido += '</tr>';
				contenido += '<tr>';
				contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
				contenido += 'Papel: Tamaño: ' + datos[0]["tamano"] + '; Tipo: ' + datos[0]["tipo"] + '; Acabado: ' + datos[0]["acabado"] + '; Gramaje:  ' + datos[0]["gramaje"] + ': costePapel x cantidad / conversor: ' + Number(datos[0]["precioMaterial"]).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' x ' + datos[0]["cantidadEmpleado"]  + ' / ' + conversor+ ' = ' + Number(datos[0]["costePapel"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' €';
				contenido += '</td>'				
				contenido += '</tr>';
				
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML += contenido;
				
				
				
				
			}
		}
	}						
}



function cargarDetallesCalculosPresupuestos()
{
	if (document.getElementById("buscarOt").trim != "")
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarDetallesCalculosPresupuestos;
			peticionUnica1.open("POST","ajax/produccion_verInformePorOtCostesDetallesTotalesPresupuesto.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarDetallesCalculosPresupuestos();
			peticionUnica1.send(query_string);						
		}
	}
	else
	{
		alert("Introducir una ot");
	}
}

function consultaCargarDetallesCalculosPresupuestos()
{	
	var consulta = "accion=verInformePorOtDetalle";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarDetallesCalculosPresupuestos()
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
				
				contenido += '<tbody>';	
				contenido += '<tr>';
				contenido += '<td align="left" class="textoNegrita">';
				contenido += 'Papel y Click - Presupuesto';
				contenido += '</td>'				
				contenido += '</tr>';	
				contenido += '<tr>';
				contenido += '<td align="left" style="border-top:0px">';
				contenido += datos[0]["nombre"];
				contenido += '</td>'				
				contenido += '</tr>';			
				contenido += '<tr>';
				contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
				contenido += datos[0]["departamento"] + ' / '+datos[0]["tipoProceso"]+ ' / ' + datos[0]["proceso"];
				contenido += '</td>'				
				contenido += '</tr>';
				
				if (datos[0]["costeClick"]>0)
				{
					contenido += '<tr>';
					contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
					contenido += 'Impresora: ' + datos[0]["tipoImpresora"]+': click x cantidad x nº caras / conversor: ' + Number(datos[0]["precioClick"]).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' x ' + datos[0]["unidadesParaUtilizar"]  + ' x ' + datos[0]["impresionNumeroCaras"] + ' / ' + conversor + ' = ' + Number(datos[0]["costeClick"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' €';
					contenido += '</td>'				
					contenido += '</tr>';
				}
				if (datos[0]["costePapel"]>0)
				{
					contenido += '<tr>';
					contenido += '<td colspan="6" align="left" style="padding-left:50px; border-top:0px">';
					contenido += 'Papel: Tamaño: ' + datos[0]["tamano"] + '; Tipo: ' + datos[0]["tipo"] + '; Acabado: ' + datos[0]["acabado"] + '; Gramaje:  ' + datos[0]["gramaje"] + ': costePapel x cantidad / conversor: ' + Number(datos[0]["precioMaterial"]).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' x ' + datos[0]["unidadesParaUtilizar"] + ' / ' + conversor + ' = ' + Number(datos[0]["costePapel"]/conversor).toLocaleString('de-DE',{minimumFractionDigits: 3}) + ' €';
					contenido += '</td>'				
					contenido += '</tr>';
				}
				
				
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML += contenido;
				
				
				
				
			}
		}
	}						
}


function cargarImagenes()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarImagenes;
		peticionUnica1.open("POST","ajax/cargarImagenes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarImagenes();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarImagenes()
{	
	var consulta = "accion=cargarImagenes";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarImagenes()
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
				document.getElementById("gallery").innerHTML = peticionUnica1.responseText;
				
				
			}
		}
	}						
}

function cargarDatosImagenes()
{
	document.getElementById("numOtImagen").innerHTML =  document.getElementById("buscarOt").value;
}


function cargarInformePorOtDetalle(codigoBarras)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarInformePorOtDetalle;
		peticionUnica1.open("POST","ajax/produccion_verInformePorOtDetalle.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarInformePorOtDetalle(codigoBarras);
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarInformePorOtDetalle(codigoBarras)
{	
	var consulta = "accion=verInformePorOtDetalle";		
	consulta += "&codigoBarras=" +  codigoBarras
	return consulta;	
}

function mostrarCargarInformePorOtDetalle()
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
					contenido = '<thead class="thead-dark"><tr><th class="thead-dark" colspan="8">'+datos[0]["concepto"]+' ('+datos[0]["codigoBarras"]+')</th></tr></thead>';
				}		
				
				contenido += '<thead class="thead-dark"><tr><th class="thead-dark">Empleado</th><th class="thead-dark">Cantidad</th><th class="thead-dark">Horas</th></tr></thead>';
				contenido += '<tbody>';
				
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
						var contraste=(contador%2)?'class="contraste1"':'class="contraste2"';
												
						contenido += '<tr><td '+contraste+'>'+ datos[contador]["nombreEmpleado"]+'</a></td><td '+contraste+'>'+ datos[contador]["cantidad"]+'</td><td '+contraste+'>'+ datos[contador]["horas"]+'</td></tr>';
						contador++;

					}
					
					contraste = 'class="contraste2"';
					contenido += '<tr class="sinBorde"><td '+contraste+'></td><td '+contraste+'></td><td '+contraste+'></td></tr>';
					contenido += '<tr class="textoNegrita sinBorde"><td '+contraste+' align="right">Total</td><td '+contraste+'>'+datos[0]["cantidadTotal"]+'</td><td '+contraste+'>'+datos[0]["horasTotal"]+'</td></tr>';
					
					

					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido; 
				
				
				
				
				
				
				
			}
		}
	}						
}



function versiHayConversor()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVersiHayConversor;
		peticionUnica1.open("POST","ajax/mostrarValorConversorTamanio.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVersiHayConversor();
		peticionUnica1.send(query_string);						
	}
}

function consultaVersiHayConversor()
{	
	var consulta = "accion=versiHayConversor";		
	consulta += "&presupuesto=" +  document.getElementById("buscarOt").value;
	return consulta;	
}

function mostrarVersiHayConversor()
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
					conversor = datos[0]["valorConversor"];
				}
				else
				{
					conversor = 1;
				}
					
				
			}
		}
	}						
}



function cargarListadoCampana()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoCampana;
		peticionUnica1.open("POST","ajax/mostrarPresupuestos3_Informe.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoCampana();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarListadoCampana()
{	
	var consulta = "accion=mostrarPresupuesto";
	consulta += "&meses=6";
	consulta += "&orden=presupuesto";
	
	
	return consulta;	
}

function mostrarCargarListadoCampana()
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
					
					if (datos != "")
					{					
						if (datos.length<=0)
						{						
						}
						else
						{
							
							var contenido = "";
							var contenido2 = "";
							var contador = 0;
	

							contenido += '<option value="2412135">2412135</option>';
							contenido2 += '<option value="2412135">2412135</option>';
							contenido += '<option value="2412233">2412233</option>';
							contenido2 += '<option value="2412233">2412233</option>';
							
							
							while  (contador<datos.length)
							{
								contenido += '<option value="'+datos[contador]["presupuesto"]+'">'+datos[contador]["campana"]+'</option>';
								contenido2 += '<option value="'+datos[contador]["presupuesto"]+'">'+datos[contador]["presupuesto"]+'</option>';
								contador++;
							}	

							document.getElementById("listadoCampana").innerHTML = contenido;
							document.getElementById("buscarOt").innerHTML = contenido2;					
						}
					}
					else
					{
						document.getElementById("listadoCampana").innerHTML = "";
						document.getElementById("buscarOt").innerHTML = "";
					}
				}
				peticionUnica1=null;
					
			}
		}						
}

function gestionCambioOt()
{
	document.getElementById("listadoCampana").value = document.getElementById("buscarOt").value;	
}

function gestionCambioCampana()
{
	document.getElementById("buscarOt").value = document.getElementById("listadoCampana").value
	
}