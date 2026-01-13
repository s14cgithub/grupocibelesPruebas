var peticionUnica1 = null;
var laCondicion="";

var asunto = "Envio Facturas";



function buscarFactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	if (document.getElementById("clienteOrigen").checked==true)
	{
		condicion = " where t1.activo=1 and t1.codigo=t1.codigo_saldo and t2.id in (select  Max(id) From [gestionGrupoCibeles].[dbo].[clientesObservacionesClayma] where asunto = '"+asunto+"' group by idCliente) and asunto = '"+asunto+"'";
	}
	else
	{
		condicion = " where t1.activo=1 and t1.codigo=t1.codigo_saldo and t2.id in (select  Max(id) From [gestionGrupoCibeles].[dbo].[clientesObservaciones] where  asunto = '"+asunto+"' group by idCliente) and asunto = '"+asunto+"'";
	}
	
	
	if (campoAbuscar =="codigo" && textoAbuscar!="")
	{
		condicion += " and "+campoAbuscar+" = " + textoAbuscar;
	}
	else
	{
		condicion += " and "+campoAbuscar+" like '%" + textoAbuscar + "%'";	  
	}
		
	
	
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	condicion += ",fecha desc";
	
	
	
	laCondicion = encodeURIComponent(condicion);
	
	
	
	cargarClientesObservacionesCompleto();
	
	
}



function cargarClientesObservacionesCompleto()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientesObservacionesCompleto;
		
		
		peticionUnica1.open("POST","ajax/cargarObservacionesClientesCompleto.php",false);
		
		
		
	
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesObservacionesCompleto();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarClientesObservacionesCompleto()
{	
	var consulta = "accion=cargarObservacionesClientes";	
	
	consulta += "&condicion="+laCondicion;
	consulta += "&clayma="+document.getElementById("clienteOrigen").checked;
	
	return consulta;	
}

function mostrarCargarClientesObservacionesCompleto()
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
				
				//contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=2 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioSaldos">aaa</td><td style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioPFfijos"">bbb</td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>';
				
				
				
				
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					
					contenido += '<th>Codigo Saldo</th>';					
					contenido += '<th>Cliente</th>';
					contenido += '<th>Fecha</th>';
					contenido += '<th>Observacion</th>';
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
					
					contenido += '<tr '+contraste+'>';
					
					
					contenido += '<td align="right">'+datos[contador]["codigo_saldo"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombre_empresa"]+'</td>';	
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';					
					
					var laObservacion="";
					
					var laObservacion2 = datos[contador]["observacion"];					
					laObservacion2 = laObservacion2.replace(/\r\n/g," <br>");
					laObservacion2 = laObservacion2.replace(/\n/g," <br>");
					laObservacion2 = laObservacion2.replace(/\r/g," <br>");
					
					var laObsPorPalabra=laObservacion2.split(" ");
					
					for (i = 0; i < laObsPorPalabra.length; i++) 
					{		
						if (laObsPorPalabra[i].includes('@'))
						{
							laObservacion += ' <a href="mailto:'+laObsPorPalabra[i]+'"  style="color: orange;">'+laObsPorPalabra[i]+'</a>';
						}
						else
						{
							if (laObservacion=="")
							{
								laObservacion +=  laObsPorPalabra[i];
							}
							else
							{
								laObservacion +=  " " + laObsPorPalabra[i];
							}
						}
					} 
					
					contenido += '<td align="left"  id="'+datos[contador]["id"]+'" ondblclick="modificar1(this.id,'+datos[contador]["codigo_saldo"]+')">'+laObservacion+'</td>';
					
					
					contenido += '<td ><span  style="display:none;visibility:hidden;" id="'+datos[contador]["id"]+'_modificarObs"><input type="image"  value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificar2('+datos[contador]["id"]+','+datos[contador]["codigo_saldo"]+')"></span></td>';
					
					contenido += '<td align="left"  id="'+datos[contador]["id"]+'_comentario" style="display:none;visibility:hidden;">'+datos[contador]["observacion"]+'</td>';
					//contenido += '<td align="left"><textarea  rows="4" cols="150" id="'+datos[contador]["codigo"]+'">'+laObservacion+'</textarea></td>';
					
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoClientesObservaciones").innerHTML = contenido;
			}
			peticionUnica1=null;
		}
	}						
}


function modificar1(idObservacion,idCliente)
{
	
	document.getElementById(idObservacion).innerHTML = '<textarea rows="10" cols="150" id="'+idObservacion+'_textArea" style="width:100%;">'+document.getElementById(idObservacion+"_comentario").innerHTML+'</textarea>';
	
	document.getElementById(idObservacion+"_modificarObs").style.visibility="visible";
	document.getElementById(idObservacion+"_modificarObs").style.display="inline";
	
	
	
	
}

function modificar2(idObservacion,idCliente)
{
	
	insertarObservacionCliente2(idObservacion,idCliente);
	
	
	//alert(idObservacion);
		
	
	var laObservacion="";					
	var laObservacion2 = document.getElementById(idObservacion+'_textArea').value;				
	laObservacion2 = laObservacion2.replace(/\r\n/g," <br>");
	laObservacion2 = laObservacion2.replace(/\n/g," <br>");
	laObservacion2 = laObservacion2.replace(/\r/g," <br>");

	var laObsPorPalabra=laObservacion2.split(" ");

	for (i = 0; i < laObsPorPalabra.length; i++) 
	{		
		if (laObsPorPalabra[i].includes('@'))
		{
			laObservacion += ' <a href="mailto:'+laObsPorPalabra[i]+'"  style="color: orange;">'+laObsPorPalabra[i]+'</a>';
		}
		else
		{
			if (laObservacion=="")
			{
				laObservacion +=  laObsPorPalabra[i];
			}
			else
			{
				laObservacion +=  " " + laObsPorPalabra[i];
			}
		}
	} 
	
	
	
	document.getElementById(idObservacion+'_comentario').innerHTML = document.getElementById(idObservacion+'_textArea').value;
	document.getElementById(idObservacion).innerHTML = laObservacion;
	
	document.getElementById(idObservacion+"_modificarObs").style.visibility="hidden";
	document.getElementById(idObservacion+"_modificarObs").style.display="none";
	
	
	
	
	
}


function insertarObservacionCliente2(idObservacion,idCliente)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarObservacionCliente2;
		peticionUnica1.open("POST","ajax/insertarObservacionCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarObservacionCliente2(idObservacion,idCliente);
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarObservacionCliente2(idObservacion,idCliente)
{	
	var consulta = "accion=insertarObservacionCliente";	
	consulta += "&idCliente="+idCliente;
	consulta += "&asunto="+asunto;
	consulta += "&texto="+document.getElementById(idObservacion+"_textArea").value;
	
	consulta += "&clayma="+document.getElementById("clienteOrigen").checked;
	
	
	return consulta;	
}

function mostrarInsertarObservacionCliente2()
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
				//cargarObservacionesClientes();
				
			}
			peticionUnica1=null;
		}
	}						
}










