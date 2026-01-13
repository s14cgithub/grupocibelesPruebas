var peticionUnica1 = null;
var laCondicion="";
var paraBorrarPrevision="";

function buscarProvision()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var soloCobradas = document.getElementById("buscarCobrada").checked;
	var desc = document.getElementById("buscarDesc").checked;
	var soloArreglos = document.getElementById("buscarArreglo").checked;
	
	
	if (campoAbuscar=="fechaCobro" || campoAbuscar=="fechaCreacion")
	{
		
		textoAbuscar = textoAbuscar.replace("/","-");
		var datos = textoAbuscar.split('-');
		var dia = datos[0];
		var mes = datos[1];
		var anio = datos[2];
		
		
		var textoAbuscarPOsterior = new Date(anio+"-"+mes+"-"+dia);
		textoAbuscarPOsterior.setDate(textoAbuscarPOsterior.getDate() + 1);
	
		var fechaPosterior = textoAbuscarPOsterior.getDate() + '-' + (textoAbuscarPOsterior.getMonth() + 1) + "-" + textoAbuscarPOsterior.getFullYear();
		condicion = " where tabla."+campoAbuscar+" >= '" + textoAbuscar + "' and tabla."+campoAbuscar+" < '" + fechaPosterior + "'";
	}
	else
	{	
		condicion = " where tabla."+campoAbuscar+" like '%" + textoAbuscar + "%' COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";
	}
	
	if (soloCobradas==true)
	{
		condicion += " and tabla.cobrada=2"
	}
	
	if (soloArreglos==true)
	{
		condicion += " and tabla.tipo=4"
	}
	
	
	
	
	condicion += " order by tabla." + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	condicion = condicion.replaceAll('%','%25');
	
	cargarListadoPF(condicion);
	
}

function cargarListadoPF(condicion="") //js_provisionFondos
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPF;
		peticionUnica1.open("POST","ajax/mostrarProvisionDeFondosTodos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPF(condicion);
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoPF(condicion)
{	
	var consulta = "accion=mostrarProvisionFondo";
	consulta += "&condicion="+condicion;
	
	return consulta;	
}

function mostrarCargarListadoPF()
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
				
				
				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=3 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioImporte"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>';
				
				
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Presupuesto</th>';						
				contenido += '<th>Cliente</th>';
				contenido += '<th>SubCliente</th>';
				contenido += '<th>Campaña / Concepto</th>';
				contenido += '<th>F. Creacion</th>';
				contenido += '<th>Importe</th>';
				contenido += '<th>Cobrada</th>';

				contenido += '<th>Tipo</th>';

				contenido += '<th>Fecha</th>';
				contenido += '<th>Forma de Pago</th>';
				contenido += '<th></th>';

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
					
					var dia = datos[contador]["fechaCreacion"]["date"].substr(8,2);
					var mes = datos[contador]["fechaCreacion"]["date"].substr(5,2);
					var anio = datos[contador]["fechaCreacion"]["date"].substr(0,4);
					
					
					contenido += '<tr '+contraste+'>';
					contenido += '<td align="center" id="'+datos[contador]["id"]+'_presupuesto">'+datos[contador]["presupuesto"]+'</td>';
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					contenido += '<td id="'+datos[contador]["id"]+'_codCliente">'+datos[contador]["codigo"]+'</td>';
					contenido += '<td>'+datos[contador]["subcliente"]+'</td>';
					
					
					
					contenido += '<td>'+datos[contador]["campana"]+'</td>';
					
					
					//contenido += '<td>'+datos[contador]["fechaCreacion"]["date"].substring(0,10)+'</td>';
					contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';
					contenido += '<td align="right" id="'+datos[contador]["id"]+'_importe" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>';
					
					
					
					
					contenido += '<td>'+datos[contador]["cobradaNombre"]+'</td>';
					
					contenido += '<td>'+datos[contador]["tipoNombre"]+'</td>';
					
					
					if (datos[contador]["fechaCobro"]!="" && datos[contador]["fechaCobro"]!=null)
					{
						dia = datos[contador]["fechaCobro"]["date"].substr(8,2);
						mes = datos[contador]["fechaCobro"]["date"].substr(5,2);
						anio = datos[contador]["fechaCobro"]["date"].substr(0,4);
						
						contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';
					}
					else
					{
						contenido += '<td></td>';
					}
					
					
					contenido += '<td>'+datos[contador]["formaPago"]+'</td>';
					
					//if (datos[contador]["cobrada"]!="2")
					
					
										
					if (datos[contador]["cobrada"]=="1" && paraBorrarPrevision=='Si')
					{
						contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminar" value="" src="imagenes/eliminar.png" style="width:15px;"  onclick="eliminarPFpendiente('+datos[contador]["id"]+')"></td>';
					}
					else
					{
						contenido += '<td></td>';
					}
					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listadoPF").innerHTML = contenido;	
				
			}
			peticionUnica1=null;
			cargarSumatorioPF();
			
		}
	}						
}

function cargarSumatorioPF() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarSumatorioPF;
		peticionUnica1.open("POST","ajax/mostrarProvisionDeFondosTodosSumatorio.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSumatorioPF();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarSumatorioPF()
{	
	var consulta = "accion=mostrarProvisionFondoSumatorio";
	consulta += "&condicion="+laCondicion.replaceAll('%','%25');
	
	return consulta;	
}

function mostrarCargarSumatorioPF()
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
				
				
				document.getElementById("sumatorioImporte").innerHTML = "Total Importe: " + Number(datos[0]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' € ';
				
			}
			peticionUnica1=null;
			
		}
	}						
}


function eliminarPFpendiente(id) //js_provisionFondos
{
	if (confirm("¿Eliminar el registro: "+id+"?")) 
	{
	  	eliminarPFpendiente2(id);
	} 
	else 
	{
	  //no hace nada
	}
}



function eliminarPFpendiente2(id)  //js_provisionFondos
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarPFpendiente2;
		peticionUnica1.open("POST","ajax/eliminarProvisionDeFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarPFpendiente2(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarPFpendiente2(id)
{	
	var consulta = "accion=eliminarProvisionFondo";
	consulta +="&id=" + id;	
	return consulta;	
}

function mostrarEliminarPFpendiente2()
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
				peticionUnica1=null;
				cargarListadoPF();	
			}
			peticionUnica1=null;
			
		}
	}						
}



function imprimirInformeProvision()
{
	buscarProvision();
	document.getElementById("condicionImprimir").value = laCondicion;
	laCondicion=null;
	document.getElementById("formImprimirInformeProvisionFondo").submit();
}

function gestionSoloCobradas()
{
	if (document.getElementById("buscarCobrada").checked==true)
	{
		document.getElementById("buscarArreglo").checked=false;
	}
}
function gestionSoloArreglos()
{
	if (document.getElementById("buscarArreglo").checked==true)
	{
		document.getElementById("buscarCobrada").checked=false;
	}
	
}

