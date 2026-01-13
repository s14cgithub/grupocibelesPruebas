var peticionUnica1 = null;
var laCondicion="";



function buscarFactura()
{
	var condicion=" where (formaPagoReal = '' or formaPagoReal is null or formaPagoReal = 'null')";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	
	if ((campoAbuscar=="numero" && textoAbuscar!=""))
	{
		condicion += " and  "+campoAbuscar+" = " + textoAbuscar;	
	}
	else
	{
		condicion += " and "+campoAbuscar+" like '%" + textoAbuscar + "%'";	
	}
		
	
	
	
	if (fechaInicio!="" && fechaInicio!=null && fechaInicio != "null")
	{
		var laFecha = fechaInicio.replace("/","-");
		var datos = laFecha.split('-');
		var anio = datos[0];
		var mes = datos[1];
		var dia = datos[2];	
	
		var laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and fecha>= '"+laFecha1+"'";
	}
	
	if (fechaFin!="" && fechaFin!=null && fechaFin != "null")
	{
		laFecha = fechaFin.replace("/","-");
		datos = laFecha.split('-');
		anio = datos[0];
		mes = datos[1];
		dia = datos[2];	
	
		laFecha1 = dia+"-"+mes+"-"+anio;
		
		condicion += " and fecha <= '"+laFecha1+"'";
	}
	
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	laCondicion = condicion;
	
	listadoFacturasPendientes();
	
}




function listadoFacturasPendientes() //js_facturasSinCobrar
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoFacturaspendientes;
		peticionUnica1.open("POST","ajax/mostrarListadoFacturasPendientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaListadoFacturasPendientes();
		peticionUnica1.send(query_string);
	}
}

function consultaListadoFacturasPendientes()
{	
	var consulta = "accion=mostrarListadoFacturasPendientes";
	consulta += "&clayma=" + document.getElementById("clienteOrigen").checked;
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	
	
	return consulta;	
}

function mostrarListadoFacturaspendientes()
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
				{  //Number(n).toLocaleString('es');
					
					if (contador%2==0)
					{
						contraste = "";
					}
					else
					{						
						contraste = ' class="tablaContenidoColor" ';
					}
					
					contenido += '<tr ' + contraste + '>';
					contenido += '<td align="center">'+datos[contador]["numero"]+'</td>';
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					contenido += '<td>'+datos[contador]["cliente"]+'</td>';
					contenido += '<td align="right">'+Number(datos[contador]["precioTotal"]).toLocaleString('es')+' €</td>';
					contenido += '<td align="right">'+Number(datos[contador]["aPagar"]).toLocaleString('es')+' €</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td>'+dia + "-" + mes+ "-" + anio+'</td>';					
					
					contenido += '<td><input type="text" id="'+datos[contador]["numero"]+'_formaPago" value=""></input></td>';
					
					contenido += '<td><input type="image" id="'+datos[contador]["numero"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaPendiente('+datos[contador]["numero"]+')"></td>';
					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listadoFacturasPendientes").innerHTML = contenido;				
				
			}
			peticionUnica1=null;			
		}
	}						
}


