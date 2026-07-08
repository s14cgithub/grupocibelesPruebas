var peticionUnica1 = null;


function cargarClientes()
{
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientes;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientes();
		peticionUnica1.send(query_string);
	}	
} 


function consultaCargarClientes()
{
	var consulta = "accion=cargarClientes";	
	var campos = [
		'codigo_saldo',
		'nombre_empresa'		
	];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	activo: 1
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var filtrosOperadores = [
			{
				campo1: 'codigo_saldo',
				operador: '=',
				campo2: 'codigo'
			}
		];

	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));



	var order = [
    	{ campo: 'codigo_saldo', dir: 'ASC' }
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	
	
	return consulta;	
}

function mostrarCargarClientes()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				var datos = res.datos;
				
				if (datos != "")
				{		
							
					var contenido = "";
					if (booleano==true)
					{
						contenido += '  <option value="todos">todos</option>';	
					}
					booleano=false;

					var contador = 0;					
					

					while  (contador<datos.length)
					{						
						contenido += '  <option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["codigo_saldo"]+' - '+datos[contador]["nombre_empresa"]+'</option>';	
												
						contador++;
					}

					document.getElementById("clienteAjusteModal").innerHTML = contenido;
				}			
				
			}
			peticionUnica1=null;
				
		}
	}						
}

function imprimirLibroContabilidad() //js_admContabilidad.js
{
	if (document.getElementById("fechaInicioModal").value=="" || document.getElementById("fechaInicioModal").value == null)
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioModal").focus();
	}
	else if (document.getElementById("fechaFinModal").value=="" || document.getElementById("fechaFinModal").value == null)
	{
		alert("Introducir una fecha Fin");
		document.getElementById("fechaFinModal").focus();
	}
	else
	{	
		document.getElementById("fechaInicio").value = document.getElementById("fechaInicioModal").value;	
		document.getElementById("fechaFin").value = document.getElementById("fechaFinModal").value;
		document.getElementById("formImprimirLibroContabilidad").submit();
	}
}
function imprimirDomiciliados()
{
	if (document.getElementById("fechaInicioDomModal").value=="" || document.getElementById("fechaInicioDomModal").value == null)
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioDomModal").focus();
	}
	else if (document.getElementById("fechaFinDomModal").value=="" || document.getElementById("fechaFinDomModal").value == null)
	{
		alert("Introducir una fecha Fin");
		document.getElementById("fechaFinDomModal").focus();
	}
	else
	{	
		document.getElementById("fechaDomInicio").value = document.getElementById("fechaInicioDomModal").value;	
		document.getElementById("fechaDomFin").value = document.getElementById("fechaFinDomModal").value;
		document.getElementById("numClienteDomiciliado").value = document.getElementById("numClienteDomiciliadoModal").value;
		document.getElementById("formImprimirDomiciliados").submit();
	}
}

function imprimirACompensar()
{
	if (document.getElementById("fechaInicioAComModal").value=="" || document.getElementById("fechaInicioAComModal").value == null)
	{
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioAComModal").focus();
	}
	else if (document.getElementById("fechaFinAComModal").value=="" || document.getElementById("fechaFinAComModal").value == null)
	{
		alert("Introducir una fecha Fin");
		document.getElementById("fechaFinAComModal").focus();
	}
	else
	{	
		document.getElementById("fechaAComInicio").value = document.getElementById("fechaInicioAComModal").value;	
		document.getElementById("fechaAComFin").value = document.getElementById("fechaFinAComModal").value;
		document.getElementById("formImprimirACompensar").submit();
	}
}

function exportarAsage() //js_admContabilidad
{
	if (document.getElementById("fechaInicioSageModal").value=="" || document.getElementById("fechaInicioSageModal").value==null)
	{
		document.getElementById("fechaInicioSageModal").focus();
		alert("Introducir una fecha de inicio");
	}
	else if (document.getElementById("fechaFinSageModal").value=="" || document.getElementById("fechaFinSageModal").value==null)
	{
		document.getElementById("fechaFinSageModal").focus();
		alert("Introducir una fecha Fin");
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarExportarAsage;
			peticionUnica1.open("POST","ajax/generarTxtParaSage.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaExportarAsage();
			peticionUnica1.send(query_string);						
		}
	}
		
}




function consultaExportarAsage()
{	
	var consulta = "accion=mostrarFacturas";
	
	consulta+="&fechaInicio="+document.getElementById("fechaInicioSageModal").value;
	consulta+="&fechaFin="+document.getElementById("fechaFinSageModal").value;
	consulta+="&clayma="+document.getElementById("claymaSageModal").checked;
	return consulta;	
}


function mostrarExportarAsage()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				
				if (document.getElementById("claymaSageModal").checked==true)
				{
					document.getElementById("claymaAsage").click();
				}
				else
				{
					document.getElementById("cibelesAsage").click();
				}
			}				
		}
	}						
}



///
function exportarAsageCorreos() //js_admContabilidad
{
	if (document.getElementById("fechaInicioSageCorreosModal").value=="" || document.getElementById("fechaInicioSageCorreosModal").value==null)
	{
		document.getElementById("fechaInicioSageCorreosModal").focus();
		alert("Introducir una fecha de inicio");
	}
	else if (document.getElementById("fechaFinSageCorreosModal").value=="" || document.getElementById("fechaFinSageCorreosModal").value==null)
	{
		document.getElementById("fechaFinSageCorreosModal").focus();
		alert("Introducir una fecha Fin");
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarExportarAsageCorreos;
			peticionUnica1.open("POST","ajax/generarTxtParaSageCorreos.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaExportarAsageCorreos();
			peticionUnica1.send(query_string);						
		}
	}
		
}




function consultaExportarAsageCorreos()
{

	consulta = "&accion=mostrarFacturas";
	
	var campos = [
		'codigo_saldo',
		'importe',
		'fecha',
		'numeroOficial'
	];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtrosOperadores = [
		{
			campo1: 'fecha',
			operador: '>=',
			valor: document.getElementById("fechaInicioSageCorreosModal").value
		},
		{
			campo1: 'fecha',
			operador: '<=',
			valor: document.getElementById("fechaFinSageCorreosModal").value
		}
	];

	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));


	var joins = [
		'tabla2'
	];
	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var order = [
    	{ 
			campo: 'codigo_saldo', dir: 'ASC' 
		}		
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;


}


function mostrarExportarAsageCorreos()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				document.getElementById("correosAsage").click();				
			}				
		}
	}						
}

function gestionAjustarSaldo()
{
	/*if (document.getElementById("fechaAjusteModal").value=="")
	{
		document.getElementById("fechaAjusteModal").focus();
		alert("Introducir una fecha");
	}
	else*/ if (document.getElementById("importeAjusteModal").value=="")
	{
		document.getElementById("importeAjusteModal").focus();
		alert("Introducir un importe");
	}
	else if (document.getElementById("conceptoAjusteModal").value=="")
	{
		document.getElementById("conceptoAjusteModal").focus();
		alert("Introducir un concepto");
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGestionAjustarSaldo;
			peticionUnica1.open("POST","ajax/ajustarSaldo.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGestionAjustarSaldo();
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaGestionAjustarSaldo()
{	

	var consulta = "accion=gestionAjustarSaldo";

	var datos = {		
		idCliente: document.getElementById("clienteAjusteModal").value,
		importe: document.getElementById("importeAjusteModal").value,
		concepto: document.getElementById("conceptoAjusteModal").value

	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;
	
}


function mostrarGestionAjustarSaldo()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				alert("Saldo Ajustado");
				document.getElementById("importeAjusteModal").value="";
				document.getElementById("conceptoAjusteModal").value="";
				
				document.getElementById("clienteAjusteModal").focus();
				
				
			
			}				
		}
	}						
}



function cobroMasivoDomiciliado()
{
	if (document.getElementById("fechaInicioCobMasModal").value=="")
	{		
		alert("Introducir una fecha de Inicio");
		document.getElementById("fechaInicioCobMasModal").focus();
	}
	else if (document.getElementById("fechaFinCobMasModal").value=="")
	{
		document.getElementById("fechaFinCobMasModal").focus();
		alert("Introducir una fecha Fin");
	}
	else if (document.getElementById("formaPagoCobMasModal").value.trim()=="" || document.getElementById("formaPagoCobMasModal").value.trim().length <= 0 || document.getElementById("formaPagoCobMasModal").value=="NULL")
	{
		document.getElementById("formaPagoCobMasModal").focus();
		alert("Introducir una forma de Pago");
	}
	/*else if (document.getElementById("fechaCobroCobMasModal").value=="")
	{
		document.getElementById("fechaCobroCobMasModal").focus();
		alert("Introducir una fecha de Cobro");
	}*/
	else
	{
		if (confirm('¿Realizar el cobro masivo a los domiciliados?')) 
		{
			/*alert("entra");*/
			peticionUnica1=crearComunicacion(peticionUnica1);

			if(peticionUnica1)
			{							
				peticionUnica1.onreadystatechange = mostrarCobroMasivoDomiciliado;
				peticionUnica1.open("POST","ajax/cobroMasivoDomiciliados.php",false);
				peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
				var query_string = consultaCobroMasivoDomiciliado();
				peticionUnica1.send(query_string);
			}
		}
	}
}

function consultaCobroMasivoDomiciliado()
{	
	var consulta = "accion=cobroMasivoDomiciliado";

	var datos = {		
		formaPagoReal: document.getElementById("formaPagoCobMasModal").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));


	var filtrosOperadores = [
			{
				campo1: 'fecha',
				operador: '>=',
				valor: document.getElementById("fechaInicioCobMasModal").value
			},
			{
				campo1: 'fecha',
				operador: '<=',
				valor: document.getElementById("fechaFinCobMasModal").value
			}
		];

	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

		
	
	return consulta;	
}


function mostrarCobroMasivoDomiciliado()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);

			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{
				alert("Cobro Masivo Realizado");
			}				
		}
	}						
}





