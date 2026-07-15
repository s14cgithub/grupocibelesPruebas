var peticionUnica1 = null;

var permisosSoloLectura = false;



function nuevoCliente()
{
	document.getElementById("cliente_codigo").value = "";
	document.getElementById("cliente_codigo").readOnly = true;	
	
	document.getElementById("cliente_SubCliente").value = "";
	
	document.getElementById("cliente_NifSubCliente").value = "";
	document.getElementById("cliente_codigoSaldo").value = "";
	document.getElementById("cliente_NombreEmpresa").value = "";
	document.getElementById("cliente_Nif").value = "";
	document.getElementById("cliente_direccion").value = "";
	document.getElementById("cliente_localidad").value = "";
	document.getElementById("cliente_provincia").value = "";
	document.getElementById("cliente_cp").value = "";
	document.getElementById("cliente_pais").value = 64;
	//document.getElementById("cliente_codigoPais").value = "";
	document.getElementById("cliente_nomFranqueo").value = "";
	var date = new Date();
	document.getElementById("cliente_fechaAlta").value = date.toISOString().substring(0,10);
	document.getElementById("comercial").value = 12;
	
	document.getElementById("cliente_saldo").value = 0;
	
	
	document.getElementById("formaPago").value = 1;
	document.getElementById("email").value = "";
	document.getElementById("numCuenta").value = "";	
	
	document.getElementById("fac_cuotaRecogida").value = "";
	document.getElementById("fac_periodoFac").value = 1;
	document.getElementById("fac_ProdNoBon").value = "";
	document.getElementById("fac_otrosConceptos").value = "";
	document.getElementById("fac_importeOtrosConceptos").value = "";
	document.getElementById("fac_provFondos").value = "";
	document.getElementById("fac_cobroUnitarioEnvio").value = "";
	
	
	document.getElementById("envio_Att").value = "";
	document.getElementById("envio_Nombre").value = "";
	document.getElementById("envio_Direccion").value = "";
	document.getElementById("envio_cp").value = "";
	document.getElementById("envio_poblacion").value = "";
	document.getElementById("envio_provincia").value = "";
	document.getElementById("envio_pais").value = "";
	
	cliente = "";
	
	
	document.getElementById("botonCrearCliente").style.visibility = "visible";
	document.getElementById("botonCrearCliente").style.display = "table-cell";
	
	document.getElementById("cliente_saldo").readOnly = false;
	
	//document.getElementById("botonNuevoCliente").style.visibility = "hidden";
	//document.getElementById("botonNuevoCliente").style.display = "none";
	
	document.getElementById("botonModificarCliente").style.visibility = "hidden";
	document.getElementById("botonModificarCliente").style.display = "none";
	
	
	
	document.getElementById("grupoContactos").style.visibility = "hidden";
	document.getElementById("grupoContactos").style.display = "none";
	
	
	document.getElementById("grupoObservaciones").style.visibility = "hidden";
	document.getElementById("grupoObservaciones").style.display = "none";
	
	document.getElementById("grupoRegistros").style.visibility = "hidden";
	document.getElementById("grupoRegistros").style.display = "none";
	
	
	document.getElementById("grupoTipoCliente").style.visibility = "visible";
	document.getElementById("grupoTipoCliente").style.display = "table-row-group";
	
	document.getElementById("grupoCamposSubClientes").style.visibility = "hidden";
	document.getElementById("grupoCamposSubClientes").style.display = "none";
	
	document.getElementById("cliente_NombreEmpresa").readOnly = false;
	document.getElementById("cliente_Nif").readOnly = false;
	document.getElementById("cliente_nomFranqueo").readOnly = false;
	
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
	
	//if (direccion=="/clientesClayma.php")
	if (direccion.includes("/clientesClayma.php"))
	{
		document.getElementById("nuestraCuenta").value =  'ES42 2100 1945 2202 0006 7880 CAIXESBBXXX\nES38 0081 5626 8100 0120 9927 BSABESBBXXX';
		
	}
	else
	{
		document.getElementById("nuestraCuenta").value = 'ES21 2100 1945 2402 0000 7147 CAIXESBBXXX\nES48 0049 1839 4621 1043 1601 BSCHESMMXXX';
	}*/
	
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
	
	if (parametroClayma2=="1")
	{
		document.getElementById("nuestraCuenta").value =  'ES42 2100 1945 2202 0006 7880 CAIXESBBXXX\nES38 0081 5626 8100 0120 9927 BSABESBBXXX';
	}
	else
	{
		document.getElementById("nuestraCuenta").value = 'ES21 2100 1945 2402 0000 7147 CAIXESBBXXX\nES48 0049 1839 4621 1043 1601 BSCHESMMXXX';
	}
	
	
	
	
}


function crearCliente()//aqui controlar campos obligatorios
{
	var seguir = true;
	var mensaje="";
	
	
	if (document.getElementById("cliente_nomFranqueo").value=="")
	{
		seguir = false;	
		error = true;
		mensaje += "\nIntroducir un nombre de Franqueo";
	}
	else if (document.getElementById("comercial").value=="")
	{
		seguir = false;	
		error = true;
		mensaje += "\nIntroducir un Comercial";
	}
	else if (document.getElementById("tipoClienteCliente").checked==true)//es cliente
	{		
		
		if (document.getElementById("cliente_NombreEmpresa").value=="")
		{
			seguir = false;	
			error = true;
			mensaje += "\nIntroducir un nombre de Empresa";
		}
		else
		{
			verSiExisteEmpresa(document.getElementById("cliente_NombreEmpresa").value);
			
			if(error==true)
			{
				seguir = false;	
				mensaje += "\nNombre de Empresa ya existe";
			}

			verSiExisteFranqueo(document.getElementById("cliente_nomFranqueo").value)

			if(error==true)
			{
				seguir = false;
				mensaje += "\nNombre de Franqueo ya existe";
			}
			
			if (document.getElementById("cliente_Nif").value=="")
			{
				seguir = false;	
				error = true;
				mensaje += "\nIntroductir el NIF";
			}
		}
		
		/*verInformacionCliente("nif",document.getElementById("cliente_Nif").value) // Esta comprobacion se quita por orden de Marian
		if(error==true)
		{
			seguir = false;	
			mensaje += "\nEl Nif ya existe";
		}*/		
	}
	else if (document.getElementById("tipoClienteSubCliente").checked && document.getElementById("cliente_correoDiario").checked && document.getElementById("fac_periodoFac").value == 1 )
	{
		mensaje += "\nNo se puede poner en 'periodo de facturacion' el valor 'especial' en un subcliente"; //si se quita esto, se crea facturas mensuales con subclientes, y esto estaría mal. Las facturas siempre se crea con clientes, nunca con subclientes
		error = true;
		seguir = false;	
	}
	else
	{
		
		if (document.getElementById("cliente_NifSubCliente").value=="")
		{
				seguir = false;	
				error = true;
				mensaje += "\nIntroductir el NIF";
		}
		else
		{
			verSiExisteSucliente(document.getElementById("cliente_SubCliente").value)
		
			if(error==true)
			{
				mensaje += "\nEl nombre de Subcliente ya existe";
				seguir = false;	
			}
			
			verSiExisteFranqueo(document.getElementById("cliente_nomFranqueo").value)

			if(error==true)
			{
				seguir = false;	
				mensaje += "\nEl nombre de franqueo ya existe";
			}

			verSiExisteNifSubcliente(document.getElementById("cliente_NifSubCliente").value)//el nif de un subcliente se puede repetir
		}		
	}
	
			
	
	if (seguir == true && error == true) //el nif del subcliente ya existe
	{
		if (confirm('El nif del subCliente ya existe ¿CREAR SUBCLIENTE?')) 
		{
		 	crearClienteNuevo();
		}		
	}
	else if (seguir == true)
	{
		crearClienteNuevo();
	}
	else
	{
		alert(mensaje);
	}
	
	error = false;
}


function verSiExisteEmpresa(empresa)
{	

	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
			
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiExisteEmpresa;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiExisteEmpresa(empresa);
		peticionUnica1.send(query_string);						
	}	
}

function consultaVerSiExisteEmpresa(empresa)
{
	
	var consulta = "accion=cargarClientes";
	
	var campos = [		
		'nombre_empresa'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	nombre_empresa: empresa
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
		
	
	return consulta;
	
}

function mostrarVerSiExisteEmpresa()
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
				
				if(datos.length>0)
				{
					error = true;
				}
				else
				{
					error = false;
				}
			}
			peticionUnica1=null;
		}
	}						
}


function verSiExisteFranqueo(nombreFranqueo)
{	

	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
			
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiExisteFranqueo;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiExisteFranqueo(nombreFranqueo);
		peticionUnica1.send(query_string);						
	}
	
}

function consultaVerSiExisteFranqueo(nombreFranqueo)
{
	
	var consulta = "accion=cargarClientes";
	
	var campos = [		
		'nombre_franqueo'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	nombre_franqueo: nombreFranqueo
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
		
	
	return consulta;
	
}

function mostrarVerSiExisteFranqueo()
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
				
				if(datos.length>0)
				{
					error = true;
				}
				else
				{
					error = false;
				}
			}
			peticionUnica1=null;
		}
	}						
}

function verSiExisteSucliente(subcliente)
{	
	error = false;
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
			
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiExisteSucliente;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiExisteSucliente(subcliente);
		peticionUnica1.send(query_string);						
	}
	
}

function consultaVerSiExisteSucliente(subcliente)
{
	
	var consulta = "accion=cargarClientes";
	
	var campos = [		
		'subcliente'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	subcliente: subcliente
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
		
	
	return consulta;
	
}

function mostrarVerSiExisteSucliente()
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
				
				if(datos.length>0)
				{
					error = true;
				} 
				else
				{
					error = false;
				}
			}
			peticionUnica1=null;
		}
	}						
}

function verSiExisteNifSubcliente(nif_subcliente)
{	

	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
			
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiExisteNifSubcliente;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiExisteNifSubcliente(nif_subcliente);
		peticionUnica1.send(query_string);						
	}
	
}

function consultaVerSiExisteNifSubcliente(nif_subcliente)
{
	
	var consulta = "accion=cargarClientes";
	
	var campos = [		
		'nif_subcliente'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	nif_subcliente: nif_subcliente
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
		
	
	return consulta;
	
}

function mostrarVerSiExisteNifSubcliente()
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
				
				if(datos.length>0)
				{
					error = true;
				}
				else
				{
					error = false;
				}
			}
			peticionUnica1=null;
		}
	}						
}

function verDatosClientePorCodigo()
{	

	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
			
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerDatosClientePorCodigo;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerDatosClientePorCodigo();
		peticionUnica1.send(query_string);						
	}
	
}

function consultaVerDatosClientePorCodigo()
{
	
	var consulta = "accion=cargarClientes";
	
	var campos = [		
		'nombre_empresa',
		'nif'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	codigo: document.getElementById("cliente_codigoSaldo").value
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
		
	
	return consulta;
	
}

function mostrarVerDatosClientePorCodigo()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			var codigoIncorrecto = false;

			if (res.error!="")
			{
				alert(res.error);
				codigoIncorrecto = true;
			}
			else
			{
				var datos = res.datos;				
				
				if (datos != "")
				{
					
					if (datos.length<=0)
					{
						codigoIncorrecto = true;
					}
					else
					{							
						document.getElementById("cliente_NombreEmpresa").value = datos[0]["nombre_empresa"];	
						document.getElementById("cliente_Nif").value = datos[0]["nif"];							
					}
				}
				else
				{
					codigoIncorrecto = true;
				}
				
				if (codigoIncorrecto)
				{
					datos="";
					alert("No existe ese codigo");
					
					document.getElementById("cliente_NombreEmpresa").value = "";	
					document.getElementById("cliente_Nif").value = "";	
					
					document.getElementById("cliente_codigoSaldo").value = "";
					document.getElementById("cliente_codigoSaldo").focus;					
				}	
			}
			peticionUnica1=null;
		}
	}						
}


function verInformacionCliente(campo,valor)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerInformacionCliente;
		peticionUnica1.open("POST","ajax/verExistenciaCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerInformacionCliente(campo,valor);
		peticionUnica1.send(query_string);						
	}
}

function consultaVerInformacionCliente(campo,valor)
{	
	var consulta = "accion=verExistenciaCliente";
	
	consulta +="&campo="+campo;
	consulta +="&valor="+valor;
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
	
	//if (direccion=="/clientesClayma.php")
	if (direccion.includes("/clientesClayma.php"))
	{
		consulta += "&clayma=true";
	}
	else
	{
		consulta += "&clayma=false";
	}*/
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
	
	if (parametroClayma2=="1")
	{
		consulta += "&clayma=true";
	}
	else
	{
		consulta += "&clayma=false";
	}
	
	
	campo1 = campo;
	
	return consulta;	
}

function mostrarVerInformacionCliente()
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
				//alert(peticion86.responseText);				
				var datos = new Array;
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
				if(datos.length>0)
				{
					//alert("El valor de "+campo1+" ya existe");
					error = true;
				}
				
			}
			peticionUnica1=null;
			
			campo1="";
				
		}
	}						
}

function crearClienteNuevo()		
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearClienteNuevo;
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/crearClienteNuevoClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/crearClienteNuevo.php",false);
		}
		//peticionUnica1.open("POST","ajax/crearClienteNuevo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearClienteNuevo();
		peticionUnica1.send(query_string);						
	}
}

function consultaCrearClienteNuevo()
{
	var consulta = "accion=crearClienteNuevo";

	var datos = {	
		codigo:0 ,
		nombre_empresa: document.getElementById("cliente_NombreEmpresa").value,
		nif: document.getElementById("cliente_Nif").value,
		direccion: document.getElementById("cliente_direccion").value,
		localidad: document.getElementById("cliente_localidad").value,
		provincia: document.getElementById("cliente_provincia").value,
		codigo_postal: document.getElementById("cliente_cp").value,
		pais: document.getElementById("cliente_pais").value,
		codigoPais: document.getElementById("cliente_codigoPais").value == "" ? "ES" : document.getElementById("cliente_codigoPais").value,
		codigoSidi: document.getElementById("cliente_codigoSIDI").value,
		nombre_franqueo: document.getElementById("cliente_nomFranqueo").value,
		idComercial: document.getElementById("comercial").value,
		//diasApagar: document.getElementById("diasApagar").value,
		diasApagar: 1,
		idFormaPago: document.getElementById("formaPago").value,
		email: document.getElementById("email").value,
		//numCuenta: document.getElementById("numCuenta2").value+document.getElementById("numCuenta").value,
		numCuentaBanco: document.getElementById("numCuenta").value,
		fac_cuotaRecogida: document.getElementById("fac_cuotaRecogida").value == "" ? 0 : document.getElementById("fac_cuotaRecogida").value ,
		fac_idPeriodo: document.getElementById("fac_periodoFac").value,
		fac_porCientoNoBonificable: document.getElementById("fac_ProdNoBon").value,
		fac_otrosConceptosFijos: document.getElementById("fac_otrosConceptos").value,

		fac_importeFijoOtrosConcepto: document.getElementById("fac_importeOtrosConceptos").value == "" ? 0 : document.getElementById("fac_importeOtrosConceptos").value,
		fac_idProvisionFondos: document.getElementById("fac_provFondos").value == "" ? 0 : document.getElementById("fac_provFondos").value,
		fac_pfFijaImporte: document.getElementById("fac_pfFijaImporte").value == "" ? 0 : document.getElementById("fac_pfFijaImporte").value,
		fac_cobroUnitarioEnvio: document.getElementById("fac_cobroUnitarioEnvio").value == "" ? 0 : document.getElementById("fac_cobroUnitarioEnvio").value,

		envio_att: document.getElementById("envio_Att").value,
		envio_nombre: document.getElementById("envio_Nombre").value,
		envio_domicilio: document.getElementById("envio_Direccion").value,
		envio_cp: document.getElementById("envio_cp").value,
		envio_poblacion: document.getElementById("envio_poblacion").value,
		envio_provincia: document.getElementById("envio_provincia").value,
		envio_pais: document.getElementById("envio_pais").value,

		correoDiario: document.getElementById("cliente_correoDiario").checked ? 1 : 0,

		activo: document.getElementById("cliente_activo").checked ? 1 : 0,
		domiciliada: document.getElementById("cliente_domiciliado").checked ? 1 : 0,
		sinIva: document.getElementById("cliente_sinIva").checked ? 1 : 0,
		retener: document.getElementById("cliente_retener").checked ? 1 : 0,
		prefactura: document.getElementById("cliente_preFactura").checked ? 1 : 0,
		noAplicarPF: document.getElementById("cliente_sinAplicarPF").checked ? 1 : 0,
		retencion: document.getElementById("cliente_conRetencion").checked ? 1 : 0,


		nuestraCuenta: document.getElementById("nuestraCuenta").value,
		pedidoCliente: document.getElementById("cliente_pedido").value,
		vencimiento: document.getElementById("vencimiento").value,

		importePF: document.getElementById("cliente_saldo").value == "" ? 0 : document.getElementById("cliente_saldo").value
	}


	if (document.getElementById("tipoClienteCliente").checked==true)//cliente
	{
		datos.codigo_saldo = 0;//el codigoSalgo será igual al codigo, que se creará a la hora de insertar el codigo
		datos.subcliente = document.getElementById("cliente_NombreEmpresa").value;
		datos.nif_subcliente = document.getElementById("cliente_Nif").value;		
	}
	else //subcliente
	{
		datos.codigo_saldo = document.getElementById("cliente_codigoSaldo").value;
		datos.subcliente = document.getElementById("cliente_SubCliente").value;
		datos.nif_subcliente = document.getElementById("cliente_NifSubCliente").value;
	}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	

}

function mostrarCrearClienteNuevo()
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
				
				/*var URLactual = window.location;
	
				var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
				
				if (direccion.includes("/clientesClayma.php"))
				{
					window.location.href = "clientesClayma.php?codigo="+datos[0]["codigo"];
				}
				else
				{
					window.location.href = "clientes.php?codigo="+datos[0]["codigo"];
				}*/
				
				let params2 = new URLSearchParams(window.location.search);
				var parametroClayma2 = params2.get("clayma");

				if (parametroClayma2=="1")
				{
					window.location.href = "clientes.php?codigo="+res.codigo+"&clayma=1";
				}
				else
				{
					window.location.href = "clientes.php?codigo="+res.codigo+"&clayma=0";
				}
				
				
			}
			peticionUnica1=null;				
		}
	}						
}

function cargarUnCliente()
{		
	//document.getElementById("buscarCampo").value = "codigo";
	var idCliente = params.get("codigo");
	

	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
			
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientesBuscador;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		}

		//peticionUnica1.open("POST","ajax/cargarClientesBuscador.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesBuscador(idCliente);
		peticionUnica1.send(query_string);						
	}
	
}

function consultaCargarClientesBuscador(idCliente)
{
	
	var consulta = "accion=cargarClientes";
	
	var campos = [
		'codigo_saldo',
		'codigo',
		'subcliente',
		'nif_subcliente',
		'nombre_empresa',
		'nif',
		'codigoSidi',
		'direccion',
		'localidad',
		'provincia',
		'codigo_postal',
		'pais',
		'codigoPais',
		'nombre_franqueo',
		'fecha_alta',
		'idComercial',
		'importePF',
		'idFormaPago',
		'email',
		'numCuentaBanco',
		'nuestraCuenta',
		'envio_att',
		'envio_nombre',
		'envio_domicilio',
		'envio_cp',
		'envio_poblacion',
		'envio_provincia',
		'envio_pais',
		'fac_cuotaRecogida',
		'fac_idPeriodo',
		'fac_porCientoNoBonificable',
		'fac_otrosConceptosFijos',
		'fac_importeFijoOtrosConcepto',
		'fac_idProvisionFondos',
		'fac_pfFijaImporte',
		'fac_cobroUnitarioEnvio',
		'correoDiario',
		'activo',
		'domiciliada',
		'sinIva',
		'retener',
		'prefactura',
		'noAplicarPF',
		'pedidoCliente',
		'retencion',
		'vencimiento'
		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	codigo: idCliente
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
		
	
	return consulta;
	
}

function mostrarCargarClientesBuscador()
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
				
				if (datos.length>0)
				{
					
					document.getElementById("cliente_codigoSaldo").value = datos[0]["codigo_saldo"];
					document.getElementById("cliente_SubCliente").value = datos[0]["subcliente"];
					document.getElementById("cliente_NifSubCliente").value = datos[0]["nif_subcliente"];
					document.getElementById("cliente_codigo").value = datos[0]["codigo"];
					document.getElementById("cliente_NombreEmpresa").value = datos[0]["nombre_empresa"];
					document.getElementById("cliente_Nif").value = datos[0]["nif"];	
					document.getElementById("cliente_codigoSIDI").value = datos[0]["codigoSidi"];
					document.getElementById("cliente_direccion").value = datos[0]["direccion"];
					document.getElementById("cliente_localidad").value = datos[0]["localidad"];
					document.getElementById("cliente_provincia").value = datos[0]["provincia"];
					document.getElementById("cliente_cp").value = datos[0]["codigo_postal"];

					document.getElementById("cliente_pais").value = datos[0]["pais"];
					document.getElementById("cliente_codigoPais").value = datos[0]["codigoPais"];

					document.getElementById("cliente_nomFranqueo").value = datos[0]["nombre_franqueo"];
					document.getElementById("cliente_fechaAlta").value = datos[0]["fecha_alta"]["date"].substring(0,10); //"2020-05-10"
					document.getElementById("comercial").value = datos[0]["idComercial"];
	
	
					if (datos[0]["importePF"] == ".00" ||datos[0]["importePF"] == "" || datos[0]["importePF"] == null || datos[0]["importePF"] == "null")
					{
						document.getElementById("cliente_saldo").value = 0.00;
					}
					else
					{
						document.getElementById("cliente_saldo").value = datos[0]["importePF"];
					}

					document.getElementById("formaPago").value = datos[0]["idFormaPago"];
					document.getElementById("email").value = datos[0]["email"];

					/*if (datos[0]["numCuentaBanco"] != null && datos[0]["numCuentaBanco"] != "null" && datos[0]["numCuentaBanco"].length>=24 )
					{
						document.getElementById("numCuenta").value = datos[0]["numCuentaBanco"].substr(0,2);
						document.getElementById("numCuenta2").value = datos[0]["numCuentaBanco"].substr(2);
					}
					else
					{
						document.getElementById("numCuenta").value = datos[0]["numCuentaBanco"];
					}*/
					
					
					document.getElementById("numCuenta").value = datos[0]["numCuentaBanco"];

					document.getElementById("nuestraCuenta").value = datos[0]["nuestraCuenta"];

					document.getElementById("envio_Att").value = datos[0]["envio_att"];
					document.getElementById("envio_Nombre").value = datos[0]["envio_nombre"];
					document.getElementById("envio_Direccion").value = datos[0]["envio_domicilio"];
					document.getElementById("envio_cp").value = datos[0]["envio_cp"];
					document.getElementById("envio_poblacion").value = datos[0]["envio_poblacion"];
					document.getElementById("envio_provincia").value = datos[0]["envio_provincia"];
					document.getElementById("envio_pais").value = datos[0]["envio_pais"];


					if (datos[0]["fac_cuotaRecogida"]==''||datos[0]["fac_cuotaRecogida"]==null||datos[0]["fac_cuotaRecogida"]=="null")
					{
						document.getElementById("fac_cuotaRecogida").value = "0";
					}
					else if (datos[0]["fac_cuotaRecogida"].startsWith('.'))
					{
						document.getElementById("fac_cuotaRecogida").value = "0"+datos[0]["fac_cuotaRecogida"];
					}
					else		
					{
						document.getElementById("fac_cuotaRecogida").value = datos[0]["fac_cuotaRecogida"];
					}

					document.getElementById("fac_periodoFac").value = datos[0]["fac_idPeriodo"];


					if (datos[0]["fac_porCientoNoBonificable"] == null || datos[0]["fac_porCientoNoBonificable"]=="null" || datos[0]["fac_porCientoNoBonificable"]=='')
					{
						document.getElementById("fac_ProdNoBon").value = "0"; 
					}
					else		
					{
						document.getElementById("fac_ProdNoBon").value = datos[0]["fac_porCientoNoBonificable"];
					}

					document.getElementById("fac_otrosConceptos").value = datos[0]["fac_otrosConceptosFijos"];

					if (datos[0]["fac_importeFijoOtrosConcepto"]==''||datos[0]["fac_importeFijoOtrosConcepto"]==null||datos[0]["fac_importeFijoOtrosConcepto"]=="null")
					{
						document.getElementById("fac_importeOtrosConceptos").value = "0";
					}

					else if (datos[0]["fac_importeFijoOtrosConcepto"].startsWith('.'))
					{
						document.getElementById("fac_importeOtrosConceptos").value = "0"+datos[0]["fac_importeFijoOtrosConcepto"];
					}
					else		
					{
						document.getElementById("fac_importeOtrosConceptos").value = datos[0]["fac_importeFijoOtrosConcepto"];
					}

					document.getElementById("fac_provFondos").value = datos[0]["fac_idProvisionFondos"];

					if (datos[0]["fac_pfFijaImporte"]=="null"||datos[0]["fac_pfFijaImporte"]==null)
					{
						document.getElementById("fac_pfFijaImporte").value = "0";
					}
					else if (datos[0]["fac_pfFijaImporte"].startsWith('.'))
					{
						document.getElementById("fac_pfFijaImporte").value = "0"+datos[0]["fac_pfFijaImporte"];
					}		
					else		
					{
						document.getElementById("fac_pfFijaImporte").value = datos[0]["fac_pfFijaImporte"];
					}

					if (datos[0]["fac_cobroUnitarioEnvio"]=="null"||datos[0]["fac_cobroUnitarioEnvio"]==null)
					{
						document.getElementById("fac_cobroUnitarioEnvio").value = "0";
					}
					else if (datos[0]["fac_cobroUnitarioEnvio"].startsWith('.'))
					{
						document.getElementById("fac_cobroUnitarioEnvio").value = "0"+datos[0]["fac_cobroUnitarioEnvio"];
					}
					else		
					{
						document.getElementById("fac_cobroUnitarioEnvio").value = datos[0]["fac_cobroUnitarioEnvio"];
					}	

					document.getElementById("cliente_correoDiario").checked = datos[0]["correoDiario"];
					document.getElementById("cliente_activo").checked = datos[0]["activo"];
					document.getElementById("cliente_domiciliado").checked = datos[0]["domiciliada"];
					document.getElementById("cliente_sinIva").checked = datos[0]["sinIva"];
					document.getElementById("cliente_retener").checked = datos[0]["retener"];
					document.getElementById("cliente_preFactura").checked = datos[0]["prefactura"];
					document.getElementById("cliente_sinAplicarPF").checked = datos[0]["noAplicarPF"];					
					document.getElementById("cliente_pedido").value = datos[0]["pedidoCliente"];
					document.getElementById("cliente_conRetencion").checked = datos[0]["retencion"];
					document.getElementById("vencimiento").value = datos[0]["vencimiento"];

					gestionMostrarDatosCD();

					cargarObservacionesClientes();

					cargarClientesContactos();
					
					cargarDireccionRutas();	

					

					//document.getElementById("registroActual").value = (filaActual+1);
					//document.getElementById("registroUltimo").innerHTML =  "/"+ cliente.length;
					
					
					
				}
				else
				{
					alert("No se ha encontrado ningun resultado");
				}
			}
			peticionUnica1=null;
		}
	}						
}

function cargarPaises2()				
{
	peticionUnica1=crearComunicacion(peticionUnica1);
				
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarPaises2;
		peticionUnica1.open("POST","ajax/cargarPaises.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPaises2();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarPaises2()
{	
	var consulta = "accion=cargarPaises";

	var campos = [
		'id',
		'codigo'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	id: document.getElementById("cliente_pais").value
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 

	return consulta;
}


function mostrarCargarPaises2()
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
					if (datos.length<=0)
					{							
					}
					else
					{							
						document.getElementById("cliente_codigoPais").value = datos[0]["codigo"];						
					}
				}
				else
				{
					document.getElementById("cliente_codigoPais").value = "";
				}
			}
			peticionUnica1 = null;
			input = null;
		}
	}						
}




function cargarPeriodosFacturacion()				
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarPeriodosFacturacion;
		peticionUnica1.open("POST","ajax/cargarPeriodosFacturacion.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPeriodosFacturacion();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarPeriodosFacturacion()
{
	
	var consulta = "accion=cargarPeriodosFacturacion";

	var campos = [
		'id',
		'periodo'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [
		{
			campo: 'periodo', dir: 'DESC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	

	return consulta;	
}

function mostrarCargarPeriodosFacturacion()
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
					if (datos.length<=0)
					{						
					}
					else
					{
						
						var contenido = "";
						var contador = 0;

						while  (contador<datos.length)
						{
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["periodo"]+'</option>';
							contador++;
						}							
						document.getElementById("fac_periodoFac").innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById("fac_periodoFac").innerHTML = "";
				}
			}
			peticionUnica1=null;
				
		}
	}						
}

function cargarFacturasProvisionFondo()				
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarFacturasProvisionFondo;
		peticionUnica1.open("POST","ajax/cargarFacturasProvisionFondo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarFacturasProvisionFondo();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarFacturasProvisionFondo()
{
	
	var consulta = "accion=cargarFacturasProvisionFondo";

	var campos = [
		'id',
		'tipoProvision'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [
		{
			campo: 'tipoProvision', dir: 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	

	return consulta;

}


function mostrarCargarFacturasProvisionFondo()
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
					if (datos.length<=0)
					{						
					}
					else
					{						
						var contenido = "";
						var contador = 0;

						while  (contador<datos.length)
						{
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tipoProvision"]+'</option>';
							contador++;
						}							
						document.getElementById("fac_provFondos").innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById("fac_provFondos").innerHTML = "";
				}
			}
			
			peticionUnica1=null;
				
		}
	}						
}

function mostrarPfFijaImporte()
{
	if (document.getElementById("cliente_correoDiario").checked && document.getElementById("fac_provFondos").selectedIndex==0)
	{
		document.getElementById("fac_pfFijaImporte").style.visibility = "visible";
		document.getElementById("fac_pfFijaImporte").style.display = "table-row-group";		 
	}
	else
	{
		document.getElementById("fac_pfFijaImporte").style.visibility = "hidden";
		document.getElementById("fac_pfFijaImporte").style.display = "none";		 
	}
}

function imprimirInformeCliente()
{
	document.getElementById("imprimirCodigoCliente").value = document.getElementById("cliente_codigo").value;	
	
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
	
	if (parametroClayma2=="1")
	{
		document.getElementById("imprimirClaymaCliente").value = 1;
	}
	else
	{
		document.getElementById("imprimirClaymaCliente").value = 0;
	}
	
	
	document.getElementById("formImprimirCliente").submit();	
}

function gestionMostrarDatosCD()
{
	if (document.getElementById("cliente_correoDiario").checked)
	{
		document.getElementById("datosFacturacionCD").style.visibility = "visible";
		document.getElementById("datosFacturacionCD").style.display = "table-row-group";	
		document.getElementById("datosFacturacionCD").colSpan = "6";
	}
	else
	{
		document.getElementById("datosFacturacionCD").style.visibility = "hidden";
		document.getElementById("datosFacturacionCD").style.display = "none";
	}
	mostrarPfFijaImporte();
}



function cargarObservacionesClientes()
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarObservacionesClientes;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarObservacionesClientesClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarObservacionesClientes.php",false);
		}
		
		//peticionUnica1.open("POST","ajax/cargarObservacionesClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarObservacionesClientes();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarObservacionesClientes()
{	

	var consulta = "accion=cargarObservacionesClientes";
	
	var campos = [
		'id',
		'fecha',
		'nombreCompleto',
		'asunto',
		'observacion'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [
		'tabla2'		
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtros = {
    	idCliente: document.getElementById("cliente_codigo").value
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var order = [
		{
			campo: 'id', dir:  'DESC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	
	
	return consulta;

}

function mostrarCargarObservacionesClientes()
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
				
				var contenido="";
				var contador = 0;
				
				while  (contador<datos.length)
				{
					
					contenido +='<tr>';
					contenido += '<td align="right">Fecha</td><td><input type="date" id="'+datos[contador]["id"]+'_observacionFecha" name="'+datos[contador]["id"]+'_observacionFecha" style="width: 100%;" value="'+datos[contador]["fecha"]["date"].substring(0,10)+'"></input></td>';
					
					
					contenido += '<td align="right">Persona Cibeles</td><td><input type="text" id="'+datos[contador]["id"]+'_observacionPersona" name="'+datos[contador]["id"]+'_observacionPersona" style="width: 100%;" value="'+datos[contador]["nombreCompleto"]+'"></input></td>';
					
					contenido += '<td align="right">Asunto</td><td><input type="text" id="'+datos[contador]["id"]+'_observacionAsunto" name="'+datos[contador]["id"]+'_observacionAsunto" style="width: 100%;" value="'+datos[contador]["asunto"]+'"></input></td></tr>';
					
					contenido +='<tr><td></td><td colspan="5"><textarea style="width: 100%;">'+datos[contador]["observacion"]+'</textarea></td></tr>';
			
					contenido +='<tr><td colspan="6"><hr></td></tr>';
					
					contador++;

				}
				
				
				contenido += '<tr>'
				contenido += '<td align="right">Asunto:</td>';
				contenido += '<td colspan=""><input class="" type="text" id="obs_asunto" name="obs_asunto" style="width: 100%;"></input></td>';
				contenido += '<td align="right">Texto:</td>';
				contenido += '<td colspan="4"><textarea style="width: 100%;" id="obs_texto"></textarea></td></td></tr>';
				
				if (!permisosSoloLectura)
				{
					contenido += '<tr><td colspan="6" align="center"><button type="button" class="btn btn-info" onClick="insertarObservacionCliente()" id="anadirObservaciones">añadir observaciones</button></td>';
				}
				
				contenido += '</tr>';
			
				
				document.getElementById("verObservacionesCliente").innerHTML = contenido; 
				
			}
			
			peticionUnica1=null;
		}
	}						
}


function cargarClientesContactos()
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientesContactos;
		
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesContactosClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientesContactos.php",false);
		}
		
		//peticionUnica1.open("POST","ajax/cargarClientesContactos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesContactos();
		peticionUnica1.send(query_string);						
	}
	
}

function consultaCargarClientesContactos()
{	
	var consulta = "accion=cargarClientesContactos";
	
	var campos = [
		'id',
		'idSexo',
		'nombre',
		'apellidos',
		'departamento',
		'cargo',
		'telefono',
		'movil',
		'email',
		'comentario'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [
		//'tabla2'		
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtros = {
    	idCliente: document.getElementById("cliente_codigo").value
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var order = [
		{
			campo: 'id', dir:  'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	
	
	return consulta;


	
}

function mostrarCargarClientesContactos()
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
				
				var contenido="";
				var contador = 0;
				
				while  (contador<datos.length)
				{
					
					contenido +='<tr>';
					//contenido += '<td align="right">Sexo</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoSexo" name="'+datos[contador]["id"]+'_contactoSexo" style="width: 100%;" value="'+datos[contador]["sexo"]+'"></input></td>';
					contenido += '<td align="right">Sexo</td><td><select  id="'+datos[contador]["id"]+'_contactoSexo" name="'+datos[contador]["id"]+'_contactoSexo" style="width: 100%;" value="'+datos[contador]["idSexo"]+'">';
					contenido += '<option value="1">Hombre</option>';
					contenido += '<option value="2">Mujer</option>';
					contenido += '</select></td>';
					
					
					contenido += '<td align="right">Nombre</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoNombre" name="'+datos[contador]["id"]+'_contactoNombre" style="width: 100%;" value="'+datos[contador]["nombre"]+'"></input></td>';
					
					contenido += '<td align="right">Apellidos</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoApellidos" name="'+datos[contador]["id"]+'_contactoApellidos" style="width: 100%;" value="'+datos[contador]["apellidos"]+'"></input></td>';
					
					if (!permisosSoloLectura)
					{
						contenido += '<td rowspan="2" align="center"><input type="image" src="imagenes/modificar.png" style="width:20px" onclick="modificarContacto('+datos[contador]["id"]+')"></td>';
					}
					

					contenido +='</tr>';


					contenido +='<tr>';
					
					contenido += '<td align="right">Departamento</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoDepartamento" name="'+datos[contador]["id"]+'_contactoDepartamento" style="width: 100%;" value="'+datos[contador]["departamento"]+'"></input></td>';
					
					contenido += '<td align="right">cargo</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoCargo" name="'+datos[contador]["id"]+'_contactoCargo" style="width: 100%;" value="'+datos[contador]["cargo"]+'"></input></td>';
					
					contenido += '<td align="right">Telefono</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoTelefono" name="'+datos[contador]["id"]+'_contactoTelefono" style="width: 100%;" value="'+datos[contador]["telefono"]+'"></input></td>';
					
					
					contenido +='</tr><tr>';
					
					contenido += '<td align="right">Movil</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoMovil" name="'+datos[contador]["id"]+'_contactoMovil" style="width: 100%;" value="'+datos[contador]["movil"]+'"></input></td>';
					
					contenido += '<td align="right">Email</td><td><input type="text" id="'+datos[contador]["id"]+'_contactoEmail" name="'+datos[contador]["id"]+'_contactoEmail" style="width: 100%;" value="'+datos[contador]["email"]+'"></input></td>';
					
					
					contenido +='<td></td><td colspan="1"><textarea style="width: 100%;" id="'+datos[contador]["id"]+'_contactoComentario" >'+datos[contador]["comentario"]+'</textarea></td>';
					
					if (!permisosSoloLectura)
					{
						contenido += '<td  align="center"><input type="image" src="imagenes/eliminar.png" style="width:20px" onclick="eliminarContacto('+datos[contador]["id"]+')"></td>';
					}
					

					contenido += '</tr>';
			
					contenido +='<tr><td colspan="6"><hr></td></tr>';
					
					contador++;

				}

				
				
				
				
				contenido +='<tr>';
				contenido += '<td align="right">Sexo</td><td><select id="contSexo" name="contSexo"><option value="1">Hombre</option><option value="2">Mujer</option><option value="3" selected></option></select></td>';
				
				contenido += '<td align="right">Nombre</td><td><input type="text" id="contNombre" name="contNombre" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">Apellidos</td><td><input type="text" id="contApellidos" name="contApellidos" style="width: 100%;" value=""></input></td></tr>';

				contenido +='</tr><tr>';

				contenido += '<td align="right">Departamento</td><td><input type="text" id="contDepartamento" name="contDepartamento" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">cargo</td><td><input type="text" id="contCargo" name="contCargo" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">Telefono</td><td><input type="text" id="contTelefono" name="contTelefono" style="width: 100%;" value=""></input></td></tr>';


				contenido +='</tr><tr>';

				contenido += '<td align="right">Movil</td><td><input type="text" id="contMovil" name="contMovil" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">Email</td><td><input type="text" id="contEmail" name="contEmail" style="width: 100%;" value=""></input></td>';


				contenido +='<td align="right">Comentario</td><td colspan="1"><textarea style="width: 100%;" id="contComentario" name="contComentario"></textarea></td></tr>';
				
				
				
				if (!permisosSoloLectura)
				{
				contenido += '<tr><td colspan="6" align="center"><button type="button" class="btn btn-info" onClick="insertarContactoCliente()" id="anadirLosContactos">añadir contacto</button></td></tr>';
				}
			
			
		
	
				
				
				document.getElementById("verClientesContacto").innerHTML = contenido; 

				contador=0;
				while  (contador<datos.length)
				{
					document.getElementById(datos[contador]["id"]+'_contactoSexo').value = datos[contador]["idSexo"];
					contador++;
					
				}


			}
			peticionUnica1=null;
		}
	}						
}


function modificarContacto(idRegistroContacto)			
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarContacto;
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/modificarClienteContactoClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/modificarClienteContacto.php",false);
		}		
		//peticionUnica1.open("POST","ajax/modificarClienteContacto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarContacto(idRegistroContacto);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarContacto(idRegistroContacto)
{
	var consulta = "accion=modificarClienteContacto";
	
	var datos = {		
		idSexo: document.getElementById(idRegistroContacto+"_contactoSexo").value,
		nombre: document.getElementById(idRegistroContacto+"_contactoNombre").value,
		apellidos: document.getElementById(idRegistroContacto+"_contactoApellidos").value,
		departamento: document.getElementById(idRegistroContacto+"_contactoDepartamento").value,
		cargo: document.getElementById(idRegistroContacto+"_contactoCargo").value,
		telefono: document.getElementById(idRegistroContacto+"_contactoTelefono").value,
		movil: document.getElementById(idRegistroContacto+"_contactoMovil").value,
		email: document.getElementById(idRegistroContacto+"_contactoEmail").value,
		comentario: document.getElementById(idRegistroContacto+"_contactoComentario").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {    	
		id: idRegistroContacto
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
	
}


function mostrarModificarContacto()
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
				alert("Contacto Modificado");				
				//cargarListadoPFpendientes();				
			}				
		}
	}						
}



function eliminarContacto(idRegistroContacto)			
{ 

	if (confirm('¿Eliminar Contacto?'))	
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarContacto;			
			peticionUnica1.open("POST","ajax/eliminarContactoCliente.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarContacto(idRegistroContacto);
			peticionUnica1.send(query_string);
		}
	}	
}

function consultaEliminarContacto(idRegistroContacto)
{
	var consulta = "accion=eliminarContactoCliente";

	var filtros = {
    	id: idRegistroContacto
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	

	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");
	
	if (parametroClayma2=="1")
	{
		consulta += "&clayma=true";
	}
	else
	{
		consulta += "&clayma=false";
	}



	return consulta;
}


function mostrarEliminarContacto()
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
				cargarClientesContactos();
			}				
		}
	}						
}


function modificarCliente()			
{
	if (document.getElementById("cliente_codigo").value != document.getElementById("cliente_codigoSaldo").value && document.getElementById("cliente_correoDiario").checked && document.getElementById("fac_periodoFac").value == 1 )
	{
		alert("No se puede poner en 'periodo de facturacion' el valor 'especial' en un subcliente"); //si se quita esto, se crea facturas mensuales con subclientes, y esto estaría mal. Las facturas siempre se crea con clientes, nunca con subclientes
	}
	else
	{

		let params2 = new URLSearchParams(window.location.search);
		var parametroClayma2 = params2.get("clayma");	

		peticionUnica1=crearComunicacion(peticionUnica1);
								
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarCliente;
			if (parametroClayma2=="1")
			{
				peticionUnica1.open("POST","ajax/modificarClienteClayma.php",false);
			}
			else
			{
				peticionUnica1.open("POST","ajax/modificarCliente.php",false);
			}
			
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var query_string = consultaModificarCliente();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarCliente()
{	
	var consulta = "accion=modificarCliente";

	var datos = {			
		direccion: document.getElementById("cliente_direccion").value,
		localidad: document.getElementById("cliente_localidad").value,
		provincia: document.getElementById("cliente_provincia").value,
		codigo_postal: document.getElementById("cliente_cp").value,
		pais: document.getElementById("cliente_pais").value,
		codigoPais: document.getElementById("cliente_codigoPais").value,
		idComercial: document.getElementById("comercial").value == '' || document.getElementById("comercial").value == null ? 0 : document.getElementById("comercial").value,
		codigoSidi: document.getElementById("cliente_codigoSIDI").value,
		idDiasDePago: 1,
		idFormaPago:  document.getElementById("formaPago").value,
		email: document.getElementById("email").value,
		numCuentaBanco: document.getElementById("numCuenta").value,	
		nuestraCuenta: document.getElementById("nuestraCuenta").value,
		correoDiario: document.getElementById("cliente_correoDiario").checked ? 1 : 0,
		activo: document.getElementById("cliente_activo").checked ? 1 : 0,
		domiciliada: document.getElementById("cliente_domiciliado").checked ? 1 : 0,
		sinIva: document.getElementById("cliente_sinIva").checked ? 1 : 0,
		retener: document.getElementById("cliente_retener").checked ? 1 : 0,
		prefactura: document.getElementById("cliente_preFactura").checked ? 1 : 0,
		noAplicarPF: document.getElementById("cliente_sinAplicarPF").checked ? 1 : 0,
		retencion: document.getElementById("cliente_conRetencion").checked ? 1 : 0,
		//datos de facturacion
		fac_cuotaRecogida: document.getElementById("fac_cuotaRecogida").value == "" ? 0 : document.getElementById("fac_cuotaRecogida").value,
		fac_idPeriodo: document.getElementById("fac_periodoFac").value == "" ? 0 : document.getElementById("fac_periodoFac").value,
		fac_porCientoNoBonificable: document.getElementById("fac_ProdNoBon").value == "" ? 0 : document.getElementById("fac_ProdNoBon").value,
		fac_otrosConceptosFijos: document.getElementById("fac_otrosConceptos").value,
		fac_importeFijoOtrosConcepto: document.getElementById("fac_importeOtrosConceptos").value == "" ? 0 : document.getElementById("fac_importeOtrosConceptos").value,
		fac_idProvisionFondos: document.getElementById("fac_provFondos").value == "" ? 0 : document.getElementById("fac_provFondos").value,
		fac_cobroUnitarioEnvio: document.getElementById("fac_cobroUnitarioEnvio").value == "" ? 0 : document.getElementById("fac_cobroUnitarioEnvio").value,
		fac_pfFijaImporte: document.getElementById("fac_pfFijaImporte").value == "" ? 0 : document.getElementById("fac_pfFijaImporte").value,

		//direccion de envio
		envio_att: document.getElementById("envio_Att").value,
		envio_nombre: document.getElementById("envio_Nombre").value,
		envio_domicilio: document.getElementById("envio_Direccion").value,
		envio_cp: document.getElementById("envio_cp").value,
		envio_poblacion: document.getElementById("envio_poblacion").value,
		envio_provincia: document.getElementById("envio_provincia").value,
		envio_pais: document.getElementById("envio_pais").value,		
		
		pedidoCliente: document.getElementById("cliente_pedido").value,
		vencimiento: document.getElementById("vencimiento").value	
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	

	var filtros = {
    	codigo: document.getElementById("cliente_codigo").value
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}


function mostrarModificarCliente()
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
				alert("Cliente Modificado");								
			}
				
		}
	}						
}

function insertarContactoCliente()
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarContactoCliente;
		
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/insertarContactoClienteClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/insertarContactoCliente.php",false);
		}		
		//peticionUnica1.open("POST","ajax/insertarContactoCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarContactoCliente();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarContactoCliente()
{	

	var consulta = "accion=insertarContactoCliente";	

	var datos = {
		idCliente: document.getElementById("cliente_codigo").value,
		idSexo: document.getElementById("contSexo").value,
		nombre: document.getElementById("contNombre").value,
		apellidos: document.getElementById("contApellidos").value,
		departamento: document.getElementById("contDepartamento").value,
		cargo: document.getElementById("contCargo").value,
		telefono: document.getElementById("contTelefono").value,
		movil: document.getElementById("contMovil").value,
		email: document.getElementById("contEmail").value,
		comentario: document.getElementById("contComentario").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarInsertarContactoCliente()
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
				cargarClientesContactos();
				
				document.getElementById("contSexo").value= "";
				document.getElementById("contNombre").value= "";
				document.getElementById("contApellidos").value= "";
				document.getElementById("contDepartamento").value= "";
				document.getElementById("contCargo").value= "";
				document.getElementById("contTelefono").value= "";
				document.getElementById("contMovil").value= "";
				document.getElementById("contEmail").value= "";
				document.getElementById("contComentario").value= "";				
			}
			peticionUnica1=null;
		}
	}						
}


function insertarObservacionCliente()
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarObservacionCliente;
		
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/insertarObservacionClienteClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/insertarObservacionCliente.php",false);
		}

		//peticionUnica1.open("POST","ajax/insertarObservacionCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarObservacionCliente();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarObservacionCliente()
{
	var consulta = "accion=insertarObservacionCliente";	

	var datos = {
		idCliente: document.getElementById("cliente_codigo").value,
		asunto: document.getElementById("obs_asunto").value,
		observacion: document.getElementById("obs_texto").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;		
}

function mostrarInsertarObservacionCliente()
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
				cargarObservacionesClientes();
				document.getElementById("obs_asunto").value = "";
				document.getElementById("obs_texto").value = "";
			}
			peticionUnica1=null;
		}
	}						
}

//////////////////////////////////DE AQUI PARA ABAJO SE BORRA TODO





/*function traspasoClientes(cliente1)//js_admClientes
{
	cliente=cliente1;
	//cargarCliente();
	
	clientePrimero();
}*/

/*function cargarCliente()//js_admClientes
{
	document.getElementById("cliente_codigoSaldo").value = cliente[filaActual]["codigo_saldo"];
	document.getElementById("cliente_SubCliente").value = cliente[filaActual]["subcliente"];
	document.getElementById("cliente_NifSubCliente").value = cliente[filaActual]["nif_subcliente"];
	document.getElementById("cliente_codigo").value = cliente[filaActual]["codigo"];
	document.getElementById("cliente_NombreEmpresa").value = cliente[filaActual]["nombre_empresa"];
	document.getElementById("cliente_Nif").value = cliente[filaActual]["nif"];	
	document.getElementById("cliente_direccion").value = cliente[filaActual]["direccion"];
	document.getElementById("cliente_localidad").value = cliente[filaActual]["localidad"];
	document.getElementById("cliente_provincia").value = cliente[filaActual]["provincia"];
	document.getElementById("cliente_cp").value = cliente[filaActual]["codigo_postal"];
	document.getElementById("cliente_nomFranqueo").value = cliente[filaActual]["nombre_franqueo"];
	document.getElementById("cliente_fechaAlta").value = cliente[filaActual]["fecha_alta"]["date"].substring(0,10); //"2020-05-10"
	document.getElementById("comercial").value = cliente[filaActual]["idComercial"];
	
	
	if (cliente[filaActual]["importePF"] == ".00" ||cliente[filaActual]["importePF"] == "" || cliente[filaActual]["importePF"] == null || cliente[filaActual]["importePF"] == "null")
	{
		document.getElementById("cliente_saldo").value = 0.00;
	}
	else
	{
		document.getElementById("cliente_saldo").value = cliente[filaActual]["importePF"];
	}
	
	document.getElementById("formaPago").value = cliente[filaActual]["idFormaPago"];
	document.getElementById("email").value = cliente[filaActual]["email"];
	
	if (cliente[filaActual]["numCuentaBanco"] != null && cliente[filaActual]["numCuentaBanco"] != "null" && cliente[filaActual]["numCuentaBanco"].length>=24 )
	{
		document.getElementById("numCuenta").value = cliente[filaActual]["numCuentaBanco"].substr(0,2);
		document.getElementById("numCuenta2").value = cliente[filaActual]["numCuentaBanco"].substr(2);
	}
	else
	{
		document.getElementById("numCuenta").value = cliente[filaActual]["numCuentaBanco"];
	}
	
	document.getElementById("nuestraCuenta").value = cliente[filaActual]["nuestraCuenta"];
	
	document.getElementById("envio_Att").value = cliente[filaActual]["envio_att"];
	document.getElementById("envio_Nombre").value = cliente[filaActual]["envio_nombre"];
	document.getElementById("envio_Direccion").value = cliente[filaActual]["envio_domicilio"];
	document.getElementById("envio_cp").value = cliente[filaActual]["envio_cp"];
	document.getElementById("envio_poblacion").value = cliente[filaActual]["envio_poblacion"];
	document.getElementById("envio_provincia").value = cliente[filaActual]["envio_provincia"];
	document.getElementById("envio_pais").value = cliente[filaActual]["envio_pais"];
	
	
	if (cliente[filaActual]["fac_cuotaRecogida"]==''||cliente[filaActual]["fac_cuotaRecogida"]==null||cliente[filaActual]["fac_cuotaRecogida"]=="null")
	{
		document.getElementById("fac_cuotaRecogida").value = "0";
	}
	else if (cliente[filaActual]["fac_cuotaRecogida"].startsWith('.'))
	{
		document.getElementById("fac_cuotaRecogida").value = "0"+cliente[filaActual]["fac_cuotaRecogida"];
	}
	else		
	{
		document.getElementById("fac_cuotaRecogida").value = cliente[filaActual]["fac_cuotaRecogida"];
	}
	
	document.getElementById("fac_periodoFac").value = cliente[filaActual]["fac_idPeriodo"];
	
	
	if (cliente[filaActual]["fac_porCientoNoBonificable"] == null || cliente[filaActual]["fac_porCientoNoBonificable"]=="null" || cliente[filaActual]["fac_porCientoNoBonificable"]=='')
	{
		document.getElementById("fac_ProdNoBon").value = "0"; 
	}
	else		
	{
		document.getElementById("fac_ProdNoBon").value = cliente[filaActual]["fac_porCientoNoBonificable"];
	}
	
	document.getElementById("fac_otrosConceptos").value = cliente[filaActual]["fac_otrosConceptosFijos"];
	
	if (cliente[filaActual]["fac_importeFijoOtrosConcepto"]==''||cliente[filaActual]["fac_importeFijoOtrosConcepto"]==null||cliente[filaActual]["fac_importeFijoOtrosConcepto"]=="null")
	{
		document.getElementById("fac_importeOtrosConceptos").value = "0";
	}
	
	else if (cliente[filaActual]["fac_importeFijoOtrosConcepto"].startsWith('.'))
	{
		document.getElementById("fac_importeOtrosConceptos").value = "0"+cliente[filaActual]["fac_importeFijoOtrosConcepto"];
	}
	else		
	{
		document.getElementById("fac_importeOtrosConceptos").value = cliente[filaActual]["fac_importeFijoOtrosConcepto"];
	}
	
	document.getElementById("fac_provFondos").value = cliente[filaActual]["fac_idProvisionFondos"];
		
	if (cliente[filaActual]["fac_pfFijaImporte"]=="null"||cliente[filaActual]["fac_pfFijaImporte"]==null)
	{
		document.getElementById("fac_pfFijaImporte").value = "0";
	}
	else if (cliente[filaActual]["fac_pfFijaImporte"].startsWith('.'))
	{
		document.getElementById("fac_pfFijaImporte").value = "0"+cliente[filaActual]["fac_pfFijaImporte"];
	}		
	else		
	{
		document.getElementById("fac_pfFijaImporte").value = cliente[filaActual]["fac_pfFijaImporte"];
	}
	
	if (cliente[filaActual]["fac_cobroUnitarioEnvio"]=="null"||cliente[filaActual]["fac_cobroUnitarioEnvio"]==null)
	{
		document.getElementById("fac_cobroUnitarioEnvio").value = "0";
	}
	else if (cliente[filaActual]["fac_cobroUnitarioEnvio"].startsWith('.'))
	{
		document.getElementById("fac_cobroUnitarioEnvio").value = "0"+cliente[filaActual]["fac_cobroUnitarioEnvio"];
	}
	else		
	{
		document.getElementById("fac_cobroUnitarioEnvio").value = cliente[filaActual]["fac_cobroUnitarioEnvio"];
	}	
	
	document.getElementById("cliente_correoDiario").checked = cliente[filaActual]["correoDiario"];
	document.getElementById("cliente_activo").checked = cliente[filaActual]["activo"];
	document.getElementById("cliente_domiciliado").checked = cliente[filaActual]["domiciliada"];
	document.getElementById("cliente_sinIva").checked = cliente[filaActual]["sinIva"];
	document.getElementById("cliente_retener").checked = cliente[filaActual]["retener"];
	document.getElementById("cliente_pedido").value = cliente[filaActual]["pedidoCliente"];
	
	gestionMostrarDatosCD();
	
	cargarObservacionesClientes();
	
	cargarClientesContactos();	
	
	document.getElementById("registroActual").value = (filaActual+1);
	document.getElementById("registroUltimo").innerHTML =  "/"+ cliente.length;
}*/



/*function cargarClientesBuscador()
{	
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenCliente").value;
	
	
	
	
	var condicion = "";
	
	
	if (campoAbuscar =="codigo" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = " + textoAbuscar;
	}
	else
	{
		condicion = " where "+campoAbuscar+" like '%" + textoAbuscar + "%'";
	}
	
	
	
	
	
	if (orden != "id")
	{
		condicion += " order by " + orden;
	}	
	
	if (textoAbuscar=="")
	{
		
		//window.location.href = "clientes.php";
		if (orden != "id")
		{
			condicion = " order by " + orden;
		}
		else
		{
			condicion = "";
		}
		
	}
	//else
	{	
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarClientesBuscador;
			peticionUnica1.open("POST","ajax/cargarClientesBuscador.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarClientesBuscador(condicion);
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaCargarClientesBuscador(condicion)
{	
	var consulta = "accion=cargarClientesBuscador";	
	consulta += "&condicion="+encodeURIComponent(condicion);
	//consulta = encodeURIComponent(consulta);
	
	var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
	
	//if (direccion.includes("/clientesClayma.php"))
	if (direccion.includes("/clientesClayma.php"))
	{
		consulta += "&clayma=true";
	}
	else
	{
		consulta += "&clayma=false";
	}
	
	//alert(direccion);
	
	return consulta;	
}

function mostrarCargarClientesBuscador()
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
				
				if (datos.length>0)
				{
					traspasoClientes(datos);
				}
				else
				{
					alert("No se ha encontrado ningun resultado");
				}
			}
			peticionUnica1=null;
		}
	}						
}*/

function crearClienteGestorTipo()
{
	if (document.getElementById("tipoClienteCliente").checked == true)
	{
		document.getElementById("grupoCamposSubClientes").style.visibility = "hidden";
		document.getElementById("grupoCamposSubClientes").style.display = "none";

		document.getElementById("cliente_codigoSaldo").readOnly = true;
		document.getElementById("cliente_NombreEmpresa").readOnly = false;
		document.getElementById("cliente_Nif").readOnly = false;
		document.getElementById("cliente_nomFranqueo").readOnly = false;
		
	}
	else
	{
		document.getElementById("grupoCamposSubClientes").style.visibility = "visible";
		document.getElementById("grupoCamposSubClientes").style.display = "table-row-group";
		
		document.getElementById("cliente_codigoSaldo").readOnly = false;
		document.getElementById("cliente_NombreEmpresa").readOnly = true;
		document.getElementById("cliente_Nif").readOnly = true;
		
		document.getElementById("cliente_SubCliente").readOnly = false;
		document.getElementById("cliente_NifSubCliente").readOnly = false;
		
	}
	
	document.getElementById("cliente_codigoSaldo").value = "";
	document.getElementById("cliente_NombreEmpresa").value = "";
	document.getElementById("cliente_Nif").value = "";
	
	document.getElementById("cliente_codigoSaldo").value = "";
	document.getElementById("cliente_SubCliente").value = "";
	document.getElementById("cliente_NifSubCliente").value = "";
	
}


function mirarDatosEmpresaPorCodigo()
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarMirarDatosEmpresaPorCodigo;
		peticionUnica1.open("POST","ajax/mirarDatosEmpresaPorCodigo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaMirarDatosEmpresaPorCodigo();
		peticionUnica1.send(query_string);
	}
}

function consultaMirarDatosEmpresaPorCodigo()
{	
	var consulta = "accion=mirarDatosEmpresaPorCodigo"	
	consulta+="&codigo="+document.getElementById("cliente_codigoSaldo").value;
	
	return consulta;	
}

function mostrarMirarDatosEmpresaPorCodigo()
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
				var codigoIncorrecto = false;
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					codigoIncorrecto = true;
				}
				
				if (datos != "")
				{
					
					if (datos.length<=0)
					{
						codigoIncorrecto = true;
					}
					else
					{							
						document.getElementById("cliente_NombreEmpresa").value = datos[0]["subcliente"];	
						document.getElementById("cliente_Nif").value = datos[0]["nif_subcliente"];							
					}
				}
				else
				{
					codigoIncorrecto = true;
				}
				
				if (codigoIncorrecto)
				{
					datos="";
					alert("No existe ese codigo");
					
					document.getElementById("cliente_NombreEmpresa").value = "";	
					document.getElementById("cliente_Nif").value = "";	
					
					document.getElementById("cliente_codigoSaldo").value = "";
					document.getElementById("cliente_codigoSaldo").focus;					
				}				
			}
			peticionUnica1=null;
		}
	}						
}






function cargarClientesDirecRutasGestion()
{
	if (document.getElementById("verClientesDirecRutas").style.visibility == "visible")
	{
		ocultarDireccionesRutas();
	}
	else
	{
		verDireccionesRutas();
	}
}


function cargarClientesContactosGestion()
{
	if (document.getElementById("verClientesContacto").style.visibility == "visible")
	{
		ocultarClientesContactos();
	}
	else
	{
		verClientesContactos();
	}
}

function observacionesClienteGestion()//js_admClientes
{
	if (document.getElementById("verObservacionesCliente").style.visibility == "visible")
	{
		ocultarObservacionesCliente();
	}
	else
	{
		verObservacionesCliente();
	}
}




function verDireccionesRutas()
{
	document.getElementById("verClientesDirecRutas").style.visibility = "visible";
	document.getElementById("verClientesDirecRutas").style.display = "table-row-group";
	
	document.getElementById("botonClientesDireccionRuta").innerHTML = 'DIRECCION RUTAS - Ocultar';
}
function ocultarDireccionesRutas()
{
	document.getElementById("verClientesDirecRutas").style.visibility = "hidden";
	document.getElementById("verClientesDirecRutas").style.display = "none";
	
	document.getElementById("botonClientesDireccionRuta").innerHTML = 'DIRECCION RUTAS - Mostrar';
}

function verClientesContactos()//js_admClientes
{
	document.getElementById("verClientesContacto").style.visibility = "visible";
	document.getElementById("verClientesContacto").style.display = "table-row-group";
	
	document.getElementById("botonClientesContactos").innerHTML = 'CONTACTOS - Ocultar';
}

function ocultarClientesContactos()//js_admClientes
{
	document.getElementById("verClientesContacto").style.visibility = "hidden";
	document.getElementById("verClientesContacto").style.display = "none";
	
	document.getElementById("botonClientesContactos").innerHTML = 'CONTACTOS - Mostrar';
}

function verObservacionesCliente()//js_admClientes
{
	document.getElementById("verObservacionesCliente").style.visibility = "visible";
	document.getElementById("verObservacionesCliente").style.display = "table-row-group";
	
	document.getElementById("botonObservaciones").innerHTML = 'OBSERVACIONES - Ocultar';
}
function ocultarObservacionesCliente()//js_admClientes
{
	document.getElementById("verObservacionesCliente").style.visibility = "hidden";
	document.getElementById("verObservacionesCliente").style.display = "none";
	
	document.getElementById("botonObservaciones").innerHTML = 'OBSERVACIONES - Mostrar';
}


function cargarDireccionRutas()
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDireccionRutas;
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/cargarClientesDirecRutasClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/cargarClientesDirecRutas.php",false);
		}
		//peticionUnica1.open("POST","ajax/cargarClientesDirecRutas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDireccionRutas();
		peticionUnica1.send(query_string);						
	}
	
}

function consultaCargarDireccionRutas()
{	
	var consulta = "accion=cargarClientesDireccionRutas";

	var campos = [
		'id',
		'att',
		'nombre',
		'direccion',
		'cp',
		'poblacion',
		'provincia',
		'pais',
		'activo'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	
	var filtros = {
    	idCliente: document.getElementById("cliente_codigo").value
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var order = [
		{
			campo: 'id', dir:  'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	
	
	return consulta;

	
	
}

function mostrarCargarDireccionRutas()
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
				
				var contenido="";
				var contador = 0;
				
				while  (contador<datos.length)
				{
					
					contenido +='<tr>';					
					contenido += '<td align="right">Att:</td><td><input type="text"  id="'+datos[contador]["id"]+'_dirRutaAtt" name="'+datos[contador]["id"]+'_dirRutaAtt" style="width: 100%;" value="'+datos[contador]["att"]+'">';
					contenido += '</input></td>';					
					contenido += '<td align="right">Nombre:</td><td colspan="3"><input type="text" id="'+datos[contador]["id"]+'_dirRutaNombre" name="'+datos[contador]["id"]+'_dirRutaNombre" style="width: 100%;" value="'+datos[contador]["nombre"]+'"></input></td>';					
					
					contenido += '<td rowspan="2" align="center"><input type="image" src="imagenes/modificar.png" style="width:20px" onclick="modificarDireccionRuta('+datos[contador]["id"]+')"></td>';
					
					contenido +='</tr>';

					contenido +='<tr>';					
					contenido += '<td align="right">Dirección:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_dirRutaDireccion" name="'+datos[contador]["id"]+'_dirRutaDireccion" style="width: 100%;" value="'+datos[contador]["direccion"]+'"></input></td>';
										
					contenido +='</tr><tr>';
					
					contenido += '<td align="right">CP:</td><td><input type="text" id="'+datos[contador]["id"]+'_dirRutaCp" name="'+datos[contador]["id"]+'_dirRutaCp" style="width: 100%;" value="'+datos[contador]["cp"]+'"></input></td>';
					
					contenido += '<td align="right">Población:</td><td><input type="text" id="'+datos[contador]["id"]+'_dirRutaPoblacion" name="'+datos[contador]["id"]+'_dirRutaPoblacion" style="width: 100%;" value="'+datos[contador]["poblacion"]+'"></input></td>';
					
					contenido += '<td align="right">Provincia:</td><td><input type="text" id="'+datos[contador]["id"]+'_dirRutaProvincia" name="'+datos[contador]["id"]+'_dirRutaProvincia" style="width: 100%;" value="'+datos[contador]["provincia"]+'"></input></td>';
					
					contenido += '<td  rowspan="2"  align="center"><input type="image" src="imagenes/eliminar.png" style="width:20px" onclick="eliminarDireccionRuta('+datos[contador]["id"]+')"></td>';
											
					contenido +='</tr><tr>';

					contenido += '<td align="right">Pais:</td><td><input type="text" id="'+datos[contador]["id"]+'_dirRutaPais" name="'+datos[contador]["id"]+'_dirRutaPais" style="width: 100%;" value="'+datos[contador]["pais"]+'"></input></td>';

					contenido += '<td align="right">Activo:</td>';
					var activado = "";
					if (datos[contador]["activo"]==true)
					{
						activado = "checked";
					}
					contenido += '<td><input type="checkbox" id="'+datos[contador]["id"]+'_dirRutaActivo" '+activado+'></input></td>';

					contenido += '</tr>';
			
					contenido +='<tr><td colspan="6"><hr></td></tr>';
					
					contador++;

				}

				
				
				
				
				contenido +='<tr>';
				
				
				contenido += '<td align="right">Att:</td><td><input type="text" id="dirRutAtt" name="dirRutAtt" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">Nombre:</td><td colspan="3"><input type="text" id="dirRutNombre" name="dirRutNombre" style="width: 100%;" value=""></input></td></tr>';

				contenido +='</tr><tr>';

				contenido += '<td align="right">Dirección:</td><td colspan="5"><input type="text" id="dirRutDireccion" name="dirRutDireccion" style="width: 100%;" value=""></input></td>';

				contenido +='</tr><tr>';

				contenido += '<td align="right">CP:</td><td><input type="text" id="dirRutCP" name="dirRutCP" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">Población:</td><td><input type="text" id="dirRutPoblacion" name="dirRutPoblacion" style="width: 100%;" value=""></input></td>';

				contenido += '<td align="right">Provincia:</td><td><input type="text" id="dirRutProvincia" name="dirRutProvincia" style="width: 100%;" value=""></input></td>';


				contenido +='</tr><tr>';

				contenido += '<td align="right">País:</td><td colspan="1"><input type="text" id="dirRutPais" name="dirRutPais" style="width: 100%;" value=""></input></td>';
				//contenido += '<td align="right">Activo:</td>';
				//contenido += '<td><input type="checkbox" id="dirRutActivar"></input></td>';
				contenido += '</tr>';
				
				contenido += '<tr><td colspan="6" align="center"><button type="button" class="btn btn-info" onClick="insertarDireccionRutaCliente()" id="">añadir Direccion</button></td>';
				
				
				
				contenido += '</tr>';
				
			
				
				document.getElementById("verClientesDirecRutas").innerHTML = contenido; 

				


			}
			peticionUnica1=null;
		}
	}						
}



function insertarDireccionRutaCliente()
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");

	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarDireccionRutaCliente;

		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/insertarDirecRutasClienteClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/insertarDirecRutasCliente.php",false);
		}

		//peticionUnica1.open("POST","ajax/insertarDirecRutasCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarDireccionRutaCliente();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarDireccionRutaCliente()
{	
	var consulta = "accion=insertarClienteDireccionesRutas";	

	var datos = {
		idCliente: document.getElementById("cliente_codigo").value,
		att: document.getElementById("dirRutAtt").value,
		nombre: document.getElementById("dirRutNombre").value,
		direccion: document.getElementById("dirRutDireccion").value,
		cp: document.getElementById("dirRutCP").value,
		poblacion: document.getElementById("dirRutPoblacion").value,
		provincia: document.getElementById("dirRutProvincia").value,
		pais: document.getElementById("dirRutPais").value,
		activo: 0
		//activo: document.getElementById("dirRutActivar").checked ? 1 : 0		
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;
}

function mostrarInsertarDireccionRutaCliente()
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
				cargarClientesContactos();
				
				document.getElementById("contSexo").value= "";
				document.getElementById("contNombre").value= "";
				document.getElementById("contApellidos").value= "";
				document.getElementById("contDepartamento").value= "";
				document.getElementById("contCargo").value= "";
				document.getElementById("contTelefono").value= "";
				document.getElementById("contMovil").value= "";
				document.getElementById("contEmail").value= "";
				document.getElementById("contComentario").value= "";				
			}
			peticionUnica1=null;
			cargarDireccionRutas();
		}
	}						
}





function modificarDireccionRuta(idRegistroRutaDireccion)		 	
{
	let params2 = new URLSearchParams(window.location.search);
	var parametroClayma2 = params2.get("clayma");


	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDireccionRuta;
		if (parametroClayma2=="1")
		{
			peticionUnica1.open("POST","ajax/modificarClienteDireccionRutasClayma.php",false);
		}
		else
		{
			peticionUnica1.open("POST","ajax/modificarClienteDireccionRutas.php",false);
		}		
		//peticionUnica1.open("POST","ajax/modificarClienteDireccionRutas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDireccionRuta(idRegistroRutaDireccion);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarDireccionRuta(idRegistroRutaDireccion)
{
	var consulta = "accion=modificarClienteDireccionRutas";
	
	var datos = {		
		att: document.getElementById(idRegistroRutaDireccion+"_dirRutaAtt").value,
		nombre: document.getElementById(idRegistroRutaDireccion+"_dirRutaNombre").value,
		direccion: document.getElementById(idRegistroRutaDireccion+"_dirRutaDireccion").value,
		cp: document.getElementById(idRegistroRutaDireccion+"_dirRutaCp").value,
		poblacion: document.getElementById(idRegistroRutaDireccion+"_dirRutaPoblacion").value,
		provincia: document.getElementById(idRegistroRutaDireccion+"_dirRutaProvincia").value,
		pais: document.getElementById(idRegistroRutaDireccion+"_dirRutaPais").value,
		activo: document.getElementById(idRegistroRutaDireccion+"_dirRutaActivo").checked ? 1 : 0,
		

	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	idCliente: document.getElementById("cliente_codigo").value,
		id: idRegistroRutaDireccion
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));		
	
	var filtrosOperadores = [
		{
			campo1: 'id',
			operador: '!=',
			valor: idRegistroRutaDireccion
		}
	];

	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));


	return consulta;


}


function mostrarModificarDireccionRuta()
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
				alert("Direccion Ruta Modificado");	
				cargarDireccionRutas();				
			}				
		}
	}						
}



function eliminarDireccionRuta(idRegistroRutaDireccion)			
{

	if (confirm('¿Eliminar Direccion de Ruta?')) 
	{
		let params2 = new URLSearchParams(window.location.search);
		var parametroClayma2 = params2.get("clayma");	

		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarDireccionRuta;
			if (parametroClayma2=="1")
			{
				peticionUnica1.open("POST","ajax/eliminarClienteDireccionRutaClayma.php",false);
			}
			else
			{
				peticionUnica1.open("POST","ajax/eliminarClienteDireccionRuta.php",false);
			}			
			//peticionUnica1.open("POST","ajax/eliminarClienteDireccionRuta.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarDireccionRuta(idRegistroRutaDireccion);
			peticionUnica1.send(query_string);
		}
	}
	
}

function consultaEliminarDireccionRuta(idRegistroRutaDireccion)
{
	
	var consulta = "accion=eliminarRutaDireccionCliente";

	var filtros = {
    	id: idRegistroRutaDireccion
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	

	return consulta;	
}


function mostrarEliminarDireccionRuta()
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
				cargarDireccionRutas();
			}				
		}
	}						
}

function cargarComerciales()				
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarComerciales;
		peticionUnica1.open("POST","ajax/cargarComerciales.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarComerciales();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarComerciales()
{
	var consulta = "accion=cargarComerciales";

	var campos = [
		'id',
		'nombre'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [
		{
			campo: 'nombre',dir: 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	

	return consulta;

}


function mostrarCargarComerciales()
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
					if (datos.length<=0)
					{							
					}
					else
					{
						
						var contenido = "";
						var contador = 0;

						while  (contador<datos.length)
						{
							contenido += ' <option value="'+datos[contador]["id"]+'">'+datos[contador]["nombre"]+'</option>';
							contador++;
						}
							
						document.getElementById("comercial").innerHTML = contenido;
						
					}
				}
				else
				{
					document.getElementById("comercial").innerHTML = "";
				}
			}
			peticionUnica1 = null;
		}
	}						
}

function crearNuevoFormaDePago() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearNuevoFormaDePago;
		peticionUnica1.open("POST","ajax/crearNuevaFormaDePago.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearNuevoFormaDePago();
		peticionUnica1.send(query_string);
	}	
}

function consultaCrearNuevoFormaDePago()
{	

	var consulta = "accion=crearFormaDePago";

	var datos = {
		concepto: document.getElementById("nuevaFormaDePago").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	return consulta;	
}

function mostrarCrearNuevoFormaDePago()
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
				/*document.getElementById("filaProcesoGuardados").style.visibility = "visible";
				document.getElementById("filaProcesoGuardados").style.display = "table-row";				

				document.getElementById("filaProcesoNuevo").style.visibility = "hidden";
				document.getElementById("filaProcesoNuevo").style.display = "none";

				document.getElementById("filaProcesoGuardados").colSpan = "7";*/
				//alert(peticion26.responseText);
				cargarFormasDePago();
			}
			peticionUnica1=null;
		}
	}						
}







/*function clientePrimero()
{
	filaActual=0;
	cargarCliente();
}
function clienteUltimo()
{
	filaActual=cliente.length-1;
	cargarCliente();
}
function clienteAnterior()
{
	if (filaActual>0)
	{
		filaActual--;
	}
	cargarCliente();
}
function clientePosterior()
{	
	var limite = cliente.length;
	
	if (filaActual<limite-1)
	{
		filaActual++;
	}
	cargarCliente();
}
function clienteCambioManualRegistro()
{
	document.getElementById("registroActual").value = document.getElementById("registroActual").value.replace('.','');
	
	if (document.getElementById("registroActual").value>cliente.length)
	{
		document.getElementById("registroActual").value = cliente.length;
	}
	else if (document.getElementById("registroActual").value < 1)
	{
		document.getElementById("registroActual").value = 1;
	}	
	
	filaActual=document.getElementById("registroActual").value;
	filaActual = filaActual-1;
	cargarCliente();	
}*/











