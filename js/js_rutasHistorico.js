var peticionUnica1 = null;



function cargarRutasHistorico() //js_rutas_historico
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRutasHistorico;
		peticionUnica1.open("POST","ajax/cargarRutasHistorico.php",true);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRutasHistorico();
		peticionUnica1.send(query_string);						
	}	
}

function consultaCargarRutasHistorico()
{	
	var consulta = "accion=cargarRutasHistorico";
	return consulta;	
}

function mostrarCargarRutasHistorico()
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
				
				var contenido = "";				
				
				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
				contenido +='<th>RUTA</th>';	
				contenido +='<th>CONDUCTOR</th>';
				contenido +='<th>FECHA PREVISTA</th>';	
				contenido +='<th>HORA FIRMA</th>';	
				contenido +='<th>CLIENTE</th>';							
				contenido +='<th>NOMBRE</th>';
				contenido +='<th>DNI</th>';
				contenido +='<th>FIRMA</th>';
				contenido +='</tr>';
				
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
					

					contenido +='<td>'+datos[contador]["ruta"]+ '</td>';
					contenido +='<td>'+datos[contador]["nombre"]+ ' ' + datos[contador]["apellidos"]+ '</td>'; 

					var fechaRuta = datos[contador]["fecha"]["date"].substr(0, datos[contador]["fecha"]["date"].indexOf(' '));	
					var anio = fechaRuta.substr(0, fechaRuta.indexOf('-'));
					var mes = fechaRuta.substr(fechaRuta.indexOf('-')+1,fechaRuta.length  - fechaRuta.lastIndexOf('-') -1);
					var dia = fechaRuta.substr(fechaRuta.lastIndexOf('-')+1);
					
					contenido +='<td>'+dia + "-" + mes + "-" + anio+  " - " + datos[contador]["hora"]["date"].substr(11,8) + '</td>';

					contenido +='<td>'+datos[contador]["horaFirma"]["date"].substr(11,8) + '</td>';

					
					contenido +='<td>'+datos[contador]["idCliente"] + "  - " + datos[contador]["subcliente"] + '</td>';					
					
					if (datos[contador]["nombrePersona"] == null)
					{
						contenido +='<td></td>';
					}
					else 
					{
						contenido +='<td>'+datos[contador]["nombrePersona"]+ '</td>';
					}

					if (datos[contador]["dniPersona"] == null)
					{
						contenido +='<td></td>';
					}
					else 
					{
						contenido +='<td>'+datos[contador]["dniPersona"]+ '</td>';
					}
					
									
					
					

					//var nombreFirma = fechaRuta+'_'+horaRuta+'_'+datos[contador]["idCliente"]+'.jpg';
					var nombreFirma = datos[contador]["firma"];

					/*var archive = archiver(".jpg");
					archive.pipe(response);
					archive.file("some/file/on/server", { name: "a.pdf", });
*/					
					if (UrlExists("../imagenesAdjuntas/firmas/" + nombreFirma))
					{
						contenido += '<td align="center"><input type="image" id="'+nombreFirma+'" value="" src="imagenes/ojo.png" style="width:15px;" onclick="verFirmaHistorico(\''+nombreFirma+'\')"></td>';					
					}
					else
					{
						contenido += '<td align="center"></td>';					
					}


					
					
					contenido +='</tr>';
					
					contador++;					
				}
				
				document.getElementById("rutasHistorico").innerHTML = contenido;
				
						
			}
		}
		peticionUnica1=null;		
	}						
}

function verFirmaHistorico(nombreFirma) //js_rutasHistorico
{
	
	document.getElementById("imagenFirma").src="../imagenesAdjuntas/firmas/" + nombreFirma;
	$("#verFirmaHistorico").modal('show');
}

function UrlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}

