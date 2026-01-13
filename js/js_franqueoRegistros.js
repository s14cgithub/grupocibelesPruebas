var peticionUnica1 = null;
var laCondicion="";
var anioSeleccionado="";

function buscarRegistroFranqueo()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("buscarDesc").checked;
	
	
	if (campoAbuscar =="t1.idCliente" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = '" + textoAbuscar + "'";
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";
	}
	
		
	
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	
	cargarRegistrosFranqueo();
	
}



function cargarRegistrosFranqueo()
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRegistrosFranqueo;
		peticionUnica1.open("POST","ajax/mostrarFranqueoRegistrosTipos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosFranqueo();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosFranqueo()
{	
	var consulta = "accion=mostrarRegistrosFranqueoTipos";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');	
	consulta +="&anioSeleccionado=" + document.getElementById("anioSeleccionado").value;
	return consulta;	
}

function mostrarCargarRegistrosFranqueo()
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
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Frecha</th>';	
				contenido += '<th align="center">Cliente</th>';	
				contenido += '<th>Producto</th>';
				contenido += '<th>Destino</th>';
				contenido += '<th>OT</th>';					
				contenido += '<th>Unidades</th>';
				contenido += '<th>Importe</th>';
				contenido += '<th>Referencia</th>';
				contenido += '</tr>';
			
				var contador = 0;
				var contraste = "";
				
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
					
					
					contenido += '<tr ' + contraste + '>';

					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);					
					
					contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	

					contenido += '<td>'+datos[contador]["idCliente"] + " - " +datos[contador]["nombre_franqueo"] + '</td>';		
					
					contenido += '<td>'+datos[contador]["producto"]+'</td>';		
					contenido += '<td>'+datos[contador]["titulo"]+'</td>';		
					contenido += '<td>'+datos[contador]["ot"]+'</td>';		
					contenido += '<td align="right" >'+datos[contador]["unidades"]+'</td>';					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
						
					

					//contenido += '<td>'+datos[contador]["referencia"]+'</td>';	
					if (datos[contador]["producto"]=="null" || datos[contador]["producto"] == null )
					{
						contenido += '<td>'+datos[contador]["referencia"]+'</td>';
					}
					else
					{
						contenido += '<td><a href="franqueoGrabacion.php?producto='+datos[contador]["idProductoPadre"]+'&fecha='+anio + "-" + mes+ "-" + dia+'&referencia='+datos[contador]["referencia"]+'" target="_blank"  style="color:#FFFFFF;">'+datos[contador]["referencia"]+'</a></td>';
					}

							
					
					//contenido += '<td><input type="image" value="" src="imagenes/prefactura.png" style="width:15px;" id="'+datos[contador]["presupuesto"]+'_prefactura"" onclick="irAprefactura('+datos[contador]["presupuesto"]+',\''+datos[contador]["inicial"]+'\')"></td>';					
					
					//contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_combinado" onchange="gestionCombiacionesPresu(\''+datos[contador]["presupuesto"]+'\')"></input></td>';
					
					contenido += '</tr>';
					
					contador++;	
				}

				document.getElementById("historico1").innerHTML = contenido;

			}
			peticionUnica1=null;
			
		}
	}						
}