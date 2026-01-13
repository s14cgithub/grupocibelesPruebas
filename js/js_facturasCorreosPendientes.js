var peticionUnica1 = null;
var laCondicion="";



function buscarFactura()
{
	var condicion=" where (formaPago is null or formaPago='') ";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	
	var fechaInicio = document.getElementById("buscarFechaInicio").value;
	var fechaFin = document.getElementById("buscarFechaFin").value;
	
	
	if ((campoAbuscar=="codigo_saldo" && textoAbuscar!=""))
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
	
	cargarListadoFacturasCorreosPendientes();	
	
}

function cargarListadoFacturasCorreosPendientes() //js_facturasCorreosPendientes
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoFacturasCorreosPendientes;
		peticionUnica1.open("POST","ajax/mostrarFacturasCorreosPendientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoFacturasCorreosPendientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoFacturasCorreosPendientes()
{	
	var consulta = "accion=mostrarFacturasCorreosPendiente";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	return consulta;	
}

function mostrarCargarListadoFacturasCorreosPendientes()
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
				contenido += '<th align="center">Número Oficial</th>';						
				contenido += '<th>Codigo Saldo</th>';
				contenido += '<th>Nombre</th>';
				contenido += '<th>Fecha</th>';
				contenido += '<th>A Pagar</th>';
				contenido += '<th>Anticipio</th>';
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
					
					contenido += '<td align="center" id="'+datos[contador]["numeroOficial"]+'_numeroOficial">'+datos[contador]["numeroOficial"]+'</td>';
					
					//contenido += '<td id="'+datos[contador]["numeroOficial"]+'_codCliente">'+datos[contador]["codigoCliente"]+'</td>';
					contenido += '<td id="'+datos[contador]["numeroOficial"]+'_codCliente">'+datos[contador]["codigo_saldo"]+'</td>';
					
					
					
					
					
					contenido += '<td>'+datos[contador]["nombre_franqueo"]+'</td>';
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td>'+dia + "-" + mes+ "-" + anio+'</td>';
					
					var aux = datos[contador]["aPagar"];
					if (datos[contador]["aPagar"].startsWith('.') || datos[contador]["aPagar"]=="" )
					{
						aux = "0" + datos[contador]["aPagar"];
					}						
					
					contenido += '<td align="right" id="'+datos[contador]["numeroOficial"]+'_aPagar">'+aux+' €</td>';
					
					aux = datos[contador]["anticipo"];
					if (datos[contador]["anticipo"].startsWith('.') || datos[contador]["anticipo"]=="" )
					{
						aux = "0" + datos[contador]["anticipo"];
					}
					
					contenido += '<td align="right">'+aux+' €</td>';
					contenido += '<td><input type="text" id="'+datos[contador]["numeroOficial"]+'_formaPago" value=""></input></td>';
					
					contenido += '<td><input type="image" id="'+datos[contador]["numeroOficial"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarFacturaCorreospendiente(\''+datos[contador]["numeroOficial"]+'\')"></td>';
					
					contenido += '</tr>';
					
					contador++;	
				}	
				
				document.getElementById("listadoFacturasCorreosPendientes").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}


function modificarFacturaCorreospendiente(factura) //js_facturasCorreosPendientes
{	
	if (document.getElementById(factura+"_formaPago").value==""||document.getElementById(factura+"_formaPago").value==null)
	{
		alert("Rellenar la Forma de Pago");
		document.getElementById(factura+"_formaPago").focus();		
	}
	else
	{
		var codigoCliente = document.getElementById(factura+"_codCliente").innerHTML;
		var aPagar = document.getElementById(factura+"_aPagar").innerHTML.replace(' €','');
		var formaPago = document.getElementById(factura+"_formaPago").value;
		var numeroOficial = document.getElementById(factura+"_numeroOficial").innerHTML;
		
		var fecha="";
		
		insertarMovimientoPF(codigoCliente,fecha,formaPago,aPagar,numeroOficial);	
		
		modificarDatosPFenCliente(codigoCliente, fecha, aPagar);
		
		modificarFacturaCorreosPendiente2(factura);		
	}
}





