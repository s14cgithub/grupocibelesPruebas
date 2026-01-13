var peticionUnica1 = null;
var laCondicion="";



function buscarFactura()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	var retener = document.getElementById("clienteRetener").checked;
	var clienteActivosFijosMensual = document.getElementById("clienteActivosFijosMensual").checked;
	var clienteActivoFijos = document.getElementById("clienteActivosFijos").checked;
	var clienteActivos = document.getElementById("clienteActivos").checked;
	
	if (campoAbuscar =="codigo" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = " + textoAbuscar;
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'  COLLATE SQL_LATIN1_GENERAL_CP1_CI_AI";
	}
		
	if (retener==true)
	{
		condicion += " and retener=1";
	}
	
	if (clienteActivosFijosMensual == true)
	{
		condicion += " and activo = 1 and correoDiario = 1 and fac_idProvisionFondos = 1 and fac_idPeriodo = 1 and codigo=codigo_saldo";
	}
	if (clienteActivoFijos)
	{
		condicion += " and fac_idProvisionFondos=1 and fac_pfFijaImporte>0 and activo=1";
	}
	if (clienteActivos)
	{
		condicion += " and activo=1";
	}
	
	
	condicion += " order by " + orden;
	
	if (desc==true)
	{
		condicion += " desc";
	}
	
	
	
	laCondicion = condicion;
	
	
	
	cargarListadoClientes();
	
	
}



function cargarListadoClientes()//js_presupuestosListado
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoClientes;
		
		if (document.getElementById("clienteOrigen").checked==true)
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}
		
		
		//peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoClientes();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoClientes()
{	
	var consulta = "accion=cargarClientes";	
	
	consulta += "&condicion=" + reemplazarSimbolosBusqueda(laCondicion);
	
	return consulta;	
}

function mostrarCargarListadoClientes()
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
				
				contenido += '<tr><td style="border:none !important;"></td><td style="border:none !important;"></td><td style="border:none !important;"></td><td colspan=2 style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioSaldos">aaa</td><td style="border:none !important; font-weight: bold; text-align: right; overflow:hidden; white-space: nowrap;" id="sumatorioPFfijos"">bbb</td><td style="border:none !important;"></td><td style="border:none !important;"></td></tr>';
				
				
				
				
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					
					contenido += '<th>Codigo Saldo</th>';
					contenido += '<th>Numero</th>';
					contenido += '<th>Cliente</th>';
					contenido += '<th>Franqueo</th>';
					contenido += '<th>Direccion</th>';
					contenido += '<th>CP</th>';
					contenido += '<th>Localidad</th>';
				
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
					
					if (datos[contador]["activo"]==1)
					{
						contenido += '<td align="right" style="background:green;">'+datos[contador]["codigo_saldo"]+'</td>';
					}
					else
					{
						contenido += '<td align="right">'+datos[contador]["codigo_saldo"]+'</td>';
					}
					
					
					contenido += '<td align="right">'+datos[contador]["codigo"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombre_empresa"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["nombre_franqueo"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["direccion"]+'</td>';
					contenido += '<td align="center">'+datos[contador]["codigo_postal"]+'</td>';
					contenido += '<td align="left">'+datos[contador]["localidad"]+'</td>';
					
					contenido += '<td><input type="image" id="'+datos[contador]["codigo"]+'_cliente" value="" src="imagenes/ojo.png" style="width:15px;" onclick="irAVerCliente('+datos[contador]["codigo"]+')"></td>';
								
					
					
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoClientes").innerHTML = contenido;
			}
			peticionUnica1=null;
			
			listadoSaldosClientes_Sumatorio();
			//listadoSaldosClientes_PFfijo();//esto no va a ninguna parte
		}
	}						
}


function listadoSaldosClientes_Sumatorio() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarListadoSaldosClientes_Sumatorio;
		peticionUnica1.open("POST","ajax/mostrarListadoSaldosSumatorio.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultarListadoSaldosClientes_Sumatorio();
		peticionUnica1.send(query_string);
	}
}

function consultarListadoSaldosClientes_Sumatorio()
{	
	var consulta = "accion=mostrarListadoSaldosSumatorio";	
	consulta += "&condicion=" +  laCondicion.replaceAll('%','%25');
	
	
	if (document.getElementById("clienteOrigen").checked==true)
	{
		consulta += "&clayma=1";
	}
	else
	{
		consulta += "&clayma=0";
	}
	
	return consulta;	
}

function mostrarListadoSaldosClientes_Sumatorio()
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
				
				
				document.getElementById("sumatorioSaldos").innerHTML = "Total Saldos: " + Number(datos[0]["saldoTotal"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';	
				
				
				document.getElementById("sumatorioPFfijos").innerHTML = "Total PF-Fijo: " + Number(datos[0]["importeFijoTotal"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €';	
				
				
				
				
				
								
			}
			peticionUnica1 = null;
			
			
		}
	}						
}







function irAVerCliente(idCliente)//js_presupuestoListado
{
	var clayma=0;
	if (document.getElementById("clienteOrigen").checked==true)
	{
		clayma=1;
	}	
	
	window.location.href = "clientes.php?codigo="+idCliente+"&clayma="+clayma;
}

function gestionExportarExcelClientesCibeles() //js_facturas
{
	
	document.getElementById("exportarCondiciones").value = laCondicion;		
	document.getElementById("formExportarExcel").submit();
}






