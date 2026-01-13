var peticionUnica1 = null;

var laCondicion="";



function cargarTarifasPapel()
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTarifasPapel;
		peticionUnica1.open("POST","ajax/mostrarTarifasPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTarifasPapel();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarTarifasPapel()
{	
	var consulta = "accion=cargarTarifasPapel";	
	return consulta;	
}

function mostrarCargarTarifasPapel()
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
				contenido += '<tr class="centrarTexto  tablaCabeceraColor">';
				contenido += '<th align="center">id</th>';	
				contenido += '<th align="center">Tamaño</th>';	
				contenido += '<th>Tipo</th>';
				contenido += '<th>Acabado</th>';					
				contenido += '<th>Gramaje</th>';					
				contenido += '<th>Precio</th>';				
				contenido += '<th></th>';
				contenido += '</tr>';
				
				var contador = 0;	
				
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
					
					
					contenido += '<td><label id="'+datos[contador]["id"]+'_idTarifa">'+datos[contador]["id"]+'</label></td>';
					contenido += '<td><label id="'+datos[contador]["id"]+'_tamanioTarifa">'+datos[contador]["tamano"]+'</label></td>';
					contenido += '<td><label id="'+datos[contador]["id"]+'_tipoTarifa">'+datos[contador]["tipo"]+'</label></td>';
					contenido += '<td><label id="'+datos[contador]["id"]+'_acabadoTarifa">'+datos[contador]["acabado"]+'</label></td>';
					contenido += '<td><label id="'+datos[contador]["id"]+'_gramajeTarifa">'+datos[contador]["gramaje"]+'</label></td>';
					var precio='';
					precio = datos[contador]["precio"]; 
					if (precio == null)
					{
						precio='0'
					}
					else if (precio.toString().substr(0,1)=='.')
					{
						precio='0' + precio;
					}
					contenido += '<td><input type="number" id="'+datos[contador]["id"]+'_precioTarifa" value="'+precio+'" style="text-align: right;"></input></td>';


					contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarTarifa" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarTarifaPapel('+datos[contador]["id"]+')"></td>';	

					contenido += '</tr>'; 
					
					contador++;	
				}	
				
				document.getElementById("listadoTarifas").innerHTML = contenido;

			}
			peticionUnica1=null;			
		}
	}						
}





function modificarTarifaPapel(idPapel)
{	

	if (document.getElementById(idPapel + "_precioTarifa").value.trim()=='')
	{
		alert("Introduce un valor");
		document.getElementById(idPapel + "_precioTarifa").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarTarifaPapel;
			peticionUnica1.open("POST","ajax/modificarTarifaPapel.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaTarifaPapel(idPapel);
			peticionUnica1.send(query_string);
		}
	}
	
}

function consultaTarifaPapel(idPapel)
{	
	var consulta = "accion=modificarTarifaPapel";	
	consulta += "&idPapel=" + idPapel;
	consulta += "&precio=" + document.getElementById(idPapel + "_precioTarifa").value;
	return consulta;
}

function mostrarModificarTarifaPapel()
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
				alert("Tarifa Modificada");
				cargarTarifasPapel();
			}
			peticionUnica1=null;			
		}
	}						
}





function insertarTarifasPapel()
{	

	if (document.getElementById("RN_Precio").value.trim()=='')
	{
		alert("Introduce un valor");
		document.getElementById("RN_Precio").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarInsertarTarifasPapel;
			peticionUnica1.open("POST","ajax/insertarTarifaPapel.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaInsertarTarifasPapel();
			peticionUnica1.send(query_string);
		}
	}
	
}

function consultaInsertarTarifasPapel()
{	
	var consulta = "accion=insertarTarifasPapel";	
	consulta += "&tamanio=" + document.getElementById("RN_Tamanio").value;
	consulta += "&tipo=" + document.getElementById("RN_Tipo").value;
	consulta += "&acabado=" + document.getElementById("RN_Acabado").value;
	consulta += "&gramaje=" + document.getElementById("RN_Gramaje").value;
	consulta += "&precio=" + document.getElementById("RN_Precio").value;

	return consulta;
}

function mostrarInsertarTarifasPapel()
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
				alert("Tarifa Insertada");
				cargarTarifasPapel();
			}
			peticionUnica1=null;			
		}
	}						
}