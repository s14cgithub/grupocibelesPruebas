var peticionUnica1 = null;
var laCondicion="";

function buscarPrefacturaMensual()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("buscarDesc").checked;
	
	
	if (campoAbuscar=="fechaTerminado")
	{
		
		textoAbuscar = textoAbuscar.replace("/","-");
		var datos = textoAbuscar.split('-');
		var dia = datos[0];
		var mes = datos[1];
		var anio = datos[2];
		
		
		var textoAbuscarPOsterior = new Date(anio+"-"+mes+"-"+dia);
		textoAbuscarPOsterior.setDate(textoAbuscarPOsterior.getDate() + 1);
	
		var fechaPosterior = textoAbuscarPOsterior.getDate() + '-' + (textoAbuscarPOsterior.getMonth() + 1) + "-" + textoAbuscarPOsterior.getFullYear();
		condicion = " where "+campoAbuscar+" >= '" + textoAbuscar + "' and "+campoAbuscar+" < '" + fechaPosterior + "'";
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
	
	
	cargarListadoFacturasSinEmitirMensuales();
	
}



function cargarListadoFacturasSinEmitirMensuales()//PREFACTURAS MENSUALES //js_admEmisionFacturaPendienteMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoFacturasSinEmitirMensuales;
		peticionUnica1.open("POST","ajax/mostrarFacturasSinEmitirMensuales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoFacturasSinEmitirMensuales();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoFacturasSinEmitirMensuales()
{	
	var consulta = "accion=mostrarFacturasSinEmitirMensuales";
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	return consulta;	
}

function mostrarCargarListadoFacturasSinEmitirMensuales()
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
				//alert(peticion30.responseText);
				var datos = new Array;				
				datos = JSON.parse(peticionUnica1.responseText);				
				
				var contenido = "";
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					contenido += '<th align="center">Fac. Original</th>';						
					contenido += '<th>cliente</th>';
					contenido += '<th>campaña</th>';
					contenido += '<th>fecha</th>';					
					contenido += '<th></th>';
					contenido += '<th></th>';

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
					
					contenido += '<tr>';
					contenido += '<td align="center">'+datos[contador]["numero"]+'</td>';					
					contenido += '<td>'+datos[contador]["cliente"]+'</td>';					
					contenido += '<td>'+datos[contador]["descripcion"]+'</td>';					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					contenido += '<td>'+dia + "-" + mes+ "-" + anio+'</td>';
					
					contenido += '<td><input type="image" value="" src="imagenes/prefactura.png" style="width:15px;"  onclick="irAprefacturaMensual('+datos[contador]["numero"]+','+datos[contador]["anioFactura"]+')"></td>';	
					
					
					contenido += '<td align="center"><input type="image" value="" src="imagenes/01_aspaRoja.png" onclick="borrarPrefacturaMensual(\''+datos[contador]["numero"]+'\','+datos[contador]["anioFactura"]+')" style="width:15px;"></td>';
					
					
					contenido += '</tr>';					
					contador++;	
				}	
				
				document.getElementById("listadoFacturasSinEmitirMensuales").innerHTML = contenido;				
			}
			peticionUnica1=null;			
		}
	}						
}



function borrarPrefacturaMensual(numFactura, anioSeleccionado)
{
	if (confirm('¿Borrar la prefactura Correspondiente a la factura:"'+numFactura+'"?')) 
	{
		borrarPrefacturaMensual1(numFactura, anioSeleccionado);
	}
}


function borrarPrefacturaMensual1(numFactura, anioSeleccionado)//PREFACTURAS MENSUALES //js_admEmisionFacturaPendienteMensual
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarBorrarPrefacturaMensual;
		peticionUnica1.open("POST","ajax/eliminarPrefacturaMensual.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaBorrarPrefacturaMensual(numFactura, anioSeleccionado);
		peticionUnica1.send(query_string);
	}
}

function consultaBorrarPrefacturaMensual(numFactura, anioSeleccionado)
{	
	var consulta = "accion=borrarPrefacturaMensual";	
	consulta += "&numFactura=" + numFactura;
	consulta += "&anioSeleccionado=" + anioSeleccionado;
	return consulta;	
}

function mostrarBorrarPrefacturaMensual()
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
				buscarPrefacturaMensual();		
			}
			peticionUnica1=null;		
		}
	}						
}


function irAprefacturaMensual(numeroFactura, anioSeleccionado) //js_admEmisionFacturaPendienteMensual
{
	document.getElementById("preFacturaNumFactura").value = numeroFactura;
	document.getElementById("preFacturaAnioSeleccionado").value = anioSeleccionado;
	document.getElementById("formIrAprefacturaMensual").submit();
}


