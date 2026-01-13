var peticionUnica1 = null;

function listadoFacPendAnt()
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListFacPendAnt;
		peticionUnica1.open("POST","ajax/mostrarListadoFacturaPendienteAnterior.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListFacPendAnt();
		peticionUnica1.send(query_string);
	}
}

function consultaListFacPendAnt()
{	
	var consulta = "accion=mostrarListFacPendAnt";
	consulta += "&clayma=" + document.getElementById("clayma").checked;
	consulta += "&correos=" + document.getElementById("correos").checked;	
	consulta += "&anio=" + document.getElementById("anio").value;
	
	return consulta;	
}

function mostrarListFacPendAnt()
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
				contenido += '<th align="center">Año</th>';		
				contenido += '<th align="center">Origen</th>';	
				contenido += '<th align="center">Factura</th>';						
				contenido += '<th>Cliente</th>';					
				contenido += '<th>Total</th>';
				contenido += '<th>Total a Pagar</th>';
				contenido += '<th>Fecha</th>';					
				contenido += '<th>Forma de Pago</th>';
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
					
					contenido += '<tr ' + contraste + '>';
					contenido += '<td align="center" id="'+datos[contador]["numero"]+'_anio">'+datos[contador]["anio"]+'</td>';
					contenido += '<td align="center" id="'+datos[contador]["numero"]+'_origen">'+datos[contador]["origen"]+'</td>';
					contenido += '<td align="center">'+datos[contador]["numero"]+'</td>';					
					contenido += '<td id="'+datos[contador]["numero"]+'_cliente">'+datos[contador]["cliente"]+'</td>';
					contenido += '<td align="right">'+Number(datos[contador]["total"]).toLocaleString('es')+' €</td>';
					contenido += '<td align="right" id="'+datos[contador]["numero"]+'_aPagar">'+Number(datos[contador]["aPagar"]).toLocaleString('es')+' €</td>';					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td>'+dia + "-" + mes+ "-" + anio+'</td>';					
					
					contenido += '<td><input type="text" id="'+datos[contador]["numero"]+'_formaPago" value=""></input></td>';
					
					contenido += '<td><input type="image" id="'+datos[contador]["numero"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendienteAnteriores(\''+datos[contador]["numero"]+'\')"></td>';
					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listFacPendAnt").innerHTML = contenido;
			}
			
			peticionUnica1=null;
			
		}
	}						
}

function modificarFacturaPendienteAnteriores(numero)
{
	if (document.getElementById(numero+"_formaPago").value=="")
	{
		alert("Introducir una Forma de pago");
		document.getElementById(numero+"_formaPago").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarFacturaPendienteAnteriores;
			peticionUnica1.open("POST","ajax/modificarFacturasPendientesAnterior.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarFacturaPendienteAnteriores(numero);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarFacturaPendienteAnteriores(numero)
{	
	var consulta = "accion=modificarFacturasPendientes";
	consulta += "&factura="+numero;
	consulta += "&formaPago=" + document.getElementById(numero+"_formaPago").value;
	consulta +="&origen=" + document.getElementById(numero+"_origen").innerHTML;
	consulta +="&anio=" + document.getElementById(numero+"_anio").innerHTML;
	consulta +="&importe=" + document.getElementById(numero+"_aPagar").innerHTML.replace(' €','');
	consulta +="&cliente=" + document.getElementById(numero+"_cliente").innerHTML;
	
	
	return consulta;	
}

function mostrarModificarFacturaPendienteAnteriores()
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
				listadoFacPendAnt();
			}
			peticionUnica1 = null;
			
		}
	}						
}

function crearComunicacion(laPeticion)
{				
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		laPeticion=new ActiveXObject("Msxml2e.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			//laPeticion=new ActiveXObject("Microsoft.XMLHTTP");
			laPeticion=new XMLHttpRequest();
			}
		catch(E)
		{
			if (!laPeticion && typeof XMLHttpRequest!='undefined') laPeticion=new XMLHttpRequest();
		}
	}
	
	return laPeticion;
}

