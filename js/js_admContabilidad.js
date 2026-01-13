var peticionUnica1 = null;


function imprimirLibroContrabilidad() //js_admContabilidad.js
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
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
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
	var consulta = "accion=mostrarFacturas";
	
	consulta+="&fechaInicio="+document.getElementById("fechaInicioSageCorreosModal").value;
	consulta+="&fechaFin="+document.getElementById("fechaFinSageCorreosModal").value;
	return consulta;	
}


function mostrarExportarAsageCorreos()
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
	
	consulta+="&idCliente="+document.getElementById("clienteAjusteModal").value;
	//consulta+="&fecha="+document.getElementById("fechaAjusteModal").value;
	consulta+="&importe="+document.getElementById("importeAjusteModal").value;
	consulta+="&concepto="+document.getElementById("conceptoAjusteModal").value;
	
	return consulta;	
}


function mostrarGestionAjustarSaldo()
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
	
	consulta+="&fechaInicio="+document.getElementById("fechaInicioCobMasModal").value;
	consulta+="&fechaFin="+document.getElementById("fechaFinCobMasModal").value;
	consulta+="&formaPago="+document.getElementById("formaPagoCobMasModal").value;
	//consulta+="&fechaPago="+document.getElementById("fechaCobroCobMasModal").value;
	
	return consulta;	
}


function mostrarCobroMasivoDomiciliado()
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
				alert("Cobro Masivo Realizado");
			}				
		}
	}						
}





