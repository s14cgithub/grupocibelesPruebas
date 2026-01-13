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
			verInformacionCliente("nombre_empresa",document.getElementById("cliente_NombreEmpresa").value)
			
			if(error==true)
			{
				seguir = false;	
				mensaje += "\nNombre de Empresa ya existe";
			}

			verInformacionCliente("nombre_franqueo",document.getElementById("cliente_nomFranqueo").value)

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
			verInformacionCliente("subcliente",document.getElementById("cliente_SubCliente").value)
		
			if(error==true)
			{
				mensaje += "\nEl nombre de Subcliente ya existe";
				seguir = false;	
			}
			
			verInformacionCliente("nombre_franqueo",document.getElementById("cliente_nomFranqueo").value)

			if(error==true)
			{
				seguir = false;	
				mensaje += "\nEl nombre de franqueo ya existe";
			}

			verInformacionCliente("nif_subcliente",document.getElementById("cliente_NifSubCliente").value)//el nif de un subcliente se puede repetir
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
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCrearClienteNuevo;
		peticionUnica1.open("POST","ajax/crearClienteNuevo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearClienteNuevo();
		peticionUnica1.send(query_string);						
	}
}

function consultaCrearClienteNuevo()
{	
	var consulta = "accion=crearClienteNuevo";
	
	//consulta +="codigo="+; esto es automatico	
	
	if (document.getElementById("tipoClienteCliente").checked==true)//cliente
	{
		consulta +="&codigoSaldo=0";//el codigoSalgo será igual al codigo, que se creará a la hora de insertar el codigo
		consulta +="&subCliente="+document.getElementById("cliente_NombreEmpresa").value;		
		consulta +="&nifSubCliente="+document.getElementById("cliente_Nif").value;
		
	}
	else //subcliente
	{
		consulta +="&codigoSaldo="+document.getElementById("cliente_codigoSaldo").value;
		consulta +="&subCliente="+document.getElementById("cliente_SubCliente").value;
		consulta +="&nifSubCliente="+document.getElementById("cliente_NifSubCliente").value;
	}
	
	
	consulta +="&nombreEmpresa="+document.getElementById("cliente_NombreEmpresa").value;
	consulta +="&nif="+document.getElementById("cliente_Nif").value;
	
	
	consulta +="&direccion="+document.getElementById("cliente_direccion").value;
	consulta +="&localidad="+document.getElementById("cliente_localidad").value;
	consulta +="&provincia="+document.getElementById("cliente_provincia").value;
	consulta +="&cp="+document.getElementById("cliente_cp").value;

	consulta +="&pais="+document.getElementById("cliente_pais").value;
	consulta +="&codigoPais="+document.getElementById("cliente_codigoPais").value;


	consulta +="&nombreFranqueo="+document.getElementById("cliente_nomFranqueo").value;
	consulta +="&comercial="+document.getElementById("comercial").value;
	
	//consulta +="&diasApagar="+document.getElementById("diasApagar").value;
	consulta +="&diasApagar=1";
	
	consulta +="&formaPago="+document.getElementById("formaPago").value;
	consulta +="&EmailFactura="+document.getElementById("email").value;
	//consulta +="&numCuenta="+document.getElementById("numCuenta2").value+document.getElementById("numCuenta").value;
	consulta +="&numCuenta=" + document.getElementById("numCuenta").value;
	
	consulta +="&cuotaRecogida="+document.getElementById("fac_cuotaRecogida").value;
	consulta +="&periodoFacturacion="+document.getElementById("fac_periodoFac").value;
	consulta +="&prodNoBon="+document.getElementById("fac_ProdNoBon").value;
	consulta +="&otrosConceptos="+document.getElementById("fac_otrosConceptos").value;
	
	consulta +="&importeFijoOtrosConceptos="+document.getElementById("fac_importeOtrosConceptos").value;
	consulta +="&provisionFondos="+document.getElementById("fac_provFondos").value;
	consulta +="&pfFijaImporte=" + document.getElementById("fac_pfFijaImporte").value;
	consulta +="&cobroUnitarioEnvio="+document.getElementById("fac_cobroUnitarioEnvio").value;
	
	consulta +="&envAtt="+document.getElementById("envio_Att").value;
	consulta +="&envNombre="+document.getElementById("envio_Nombre").value;
	consulta +="&envDireccion="+document.getElementById("envio_Direccion").value;
	consulta +="&envCp="+document.getElementById("envio_cp").value;
	consulta +="&envPoblacion="+document.getElementById("envio_poblacion").value;
	consulta +="&envProvincia="+document.getElementById("envio_provincia").value;
	consulta +="&envPais="+document.getElementById("envio_pais").value;
	
	consulta +="&correoDiario=" + document.getElementById("cliente_correoDiario").checked;
	
	consulta +="&activo=" + document.getElementById("cliente_activo").checked;
	consulta +="&domiciliado=" + document.getElementById("cliente_domiciliado").checked;
	consulta +="&sinIva=" + document.getElementById("cliente_sinIva").checked;
	consulta +="&retener=" + document.getElementById("cliente_retener").checked;
	consulta +="&prefactura=" + document.getElementById("cliente_preFactura").checked;
	consulta +="&noAplicarPF=" + document.getElementById("cliente_sinAplicarPF").checked;
	consulta +="&retencion=" + document.getElementById("cliente_conRetencion").checked;
	
	
	consulta +="&nuestraCuenta=" + document.getElementById("nuestraCuenta").value;
	consulta +="&pedidoCliente=" + document.getElementById("cliente_pedido").value;
	consulta +="&vencimiento=" + document.getElementById("vencimiento").value;
	
	
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
	
	return consulta;	
}

function mostrarCrearClienteNuevo()
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
					window.location.href = "clientes.php?codigo="+datos[0]["codigo"]+"&clayma=1";
				}
				else
				{
					window.location.href = "clientes.php?codigo="+datos[0]["codigo"]+"&clayma=0";
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
	
	var condicion = " where codigo = " + idCliente;	
		
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

function consultaCargarClientesBuscador(condicion)
{	
	var consulta = "accion=cargarClientesBuscador";	
	consulta += "&condicion="+encodeURIComponent(condicion);
	//consulta = encodeURIComponent(consulta);
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));	
	
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

function cargarPaises2(condicion)				
{
	peticionUnica0=crearComunicacion(peticionUnica0);
				
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarPaises2;
		peticionUnica0.open("POST","ajax/cargarPaises.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPaises2(condicion);
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarPaises2(condicion)
{	
	var consulta = "accion=cargarPaises";
	consulta += "&condicion=" + condicion;
	return consulta;	
}


function mostrarCargarPaises2()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			if (peticionUnica0.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica0.responseText);
			}
			else
			{				
				var datos = new Array;				
				try 
				{
					datos = JSON.parse(peticionUnica0.responseText);
				}
				catch (error)
				{
					datos="";					
				}
				
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
			peticionUnica0 = null;
			input = null;
		}
	}						
}


function gestionarCodigoPais()
{
	cargarPaises2(" where id="+document.getElementById("cliente_pais").value);
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
	return consulta;	
}

function mostrarCargarPeriodosFacturacion()
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
					document.getElementById("fac_periodoFac").innerHTML = "";
				}
				
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
	return consulta;	
}


function mostrarCargarFacturasProvisionFondo()
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
					document.getElementById("fac_provFondos").innerHTML = "";
				}
				
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
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarObservacionesClientes;
		peticionUnica1.open("POST","ajax/cargarObservacionesClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarObservacionesClientes();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarObservacionesClientes()
{	
	var consulta = "accion=cargarObservacionesClientes";	
	consulta += "&idCliente="+document.getElementById("cliente_codigo").value;
	
	var URLactual = window.location;
	/*
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
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
	
	
	return consulta;	
}

function mostrarCargarObservacionesClientes()
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
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarClientesContactos;
		peticionUnica1.open("POST","ajax/cargarClientesContactos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesContactos();
		peticionUnica1.send(query_string);						
	}
	
}

function consultaCargarClientesContactos()
{	
	var consulta = "accion=cargarClientesContactos";	
	consulta += "&idCliente="+document.getElementById("cliente_codigo").value;	
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
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
	
	return consulta;
}

function mostrarCargarClientesContactos()
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
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarContacto;
		peticionUnica1.open("POST","ajax/modificarClienteContacto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarContacto(idRegistroContacto);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarContacto(idRegistroContacto)
{	
	var consulta = "accion=modificarClienteContacto";	
	
	consulta +="&id=" + idRegistroContacto;
	consulta +="&sexo=" + document.getElementById(idRegistroContacto+"_contactoSexo").value;	
	consulta +="&nombre=" + document.getElementById(idRegistroContacto+"_contactoNombre").value;	
	consulta +="&apellidos=" + document.getElementById(idRegistroContacto+"_contactoApellidos").value;	
	consulta +="&departamento=" + document.getElementById(idRegistroContacto+"_contactoDepartamento").value;	
	consulta +="&cargo=" +document.getElementById(idRegistroContacto+"_contactoCargo").value;	
	consulta +="&telefono=" +document.getElementById(idRegistroContacto+"_contactoTelefono").value ;	
	consulta +="&movil=" +document.getElementById(idRegistroContacto+"_contactoMovil").value;	
	consulta +="&email=" +document.getElementById(idRegistroContacto+"_contactoEmail").value;	
	consulta +="&comentario=" +document.getElementById(idRegistroContacto+"_contactoComentario").value;	
	
	
	
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


function mostrarModificarContacto()
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
				alert(peticionUnica1.responseText);				
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
	
	consulta +="&id=" + idRegistroContacto;
	consulta +="&sexo=" + document.getElementById(idRegistroContacto+"_contactoSexo").value;	
	consulta +="&nombre=" + document.getElementById(idRegistroContacto+"_contactoNombre").value;	
	consulta +="&apellidos=" + document.getElementById(idRegistroContacto+"_contactoApellidos").value;	
	consulta +="&departamento=" + document.getElementById(idRegistroContacto+"_contactoDepartamento").value;	
	consulta +="&cargo=" +document.getElementById(idRegistroContacto+"_contactoCargo").value;	
	consulta +="&telefono=" +document.getElementById(idRegistroContacto+"_contactoTelefono").value ;	
	consulta +="&movil=" +document.getElementById(idRegistroContacto+"_contactoMovil").value;	
	consulta +="&email=" +document.getElementById(idRegistroContacto+"_contactoEmail").value;	
	consulta +="&comentario=" +document.getElementById(idRegistroContacto+"_contactoComentario").value;	

	consulta +="&idCliente=" +document.getElementById("cliente_codigo").value;
	
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
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else
			{
				//alert(peticionUnica1.responseText);				
				cargarClientesContactos();
			}				
		}
	}						
}


function modificarCliente()			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarCliente;
		peticionUnica1.open("POST","ajax/modificarCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarCliente();
		peticionUnica1.send(query_string);
	}
}

function consultaModificarCliente()
{	
	var consulta = "accion=modificarCliente";	
	
	consulta +="&codigoCliente=" + document.getElementById("cliente_codigo").value;	
	
	//datos genericos (codigo, nif, nombre de mpresa, nombre de franqueo --> NO SE PUEDE CAMBIAR)
	consulta +="&direccion=" + document.getElementById("cliente_direccion").value;	
	consulta +="&localidad=" + document.getElementById("cliente_localidad").value;	
	consulta +="&provincia=" + document.getElementById("cliente_provincia").value;	
	consulta +="&cp=" + document.getElementById("cliente_cp").value;	
	consulta +="&pais=" + document.getElementById("cliente_pais").value;
	consulta +="&codigoPais=" + document.getElementById("cliente_codigoPais").value;

	consulta +="&comercial=" +document.getElementById("comercial").value ;	
	
	//consulta +="&diasApagar=" + document.getElementById("diasApagar").value;	
	
	var elCodigoSidi = document.getElementById("cliente_codigoSIDI").value;
	if (elCodigoSidi == "")
	{
		elCodigoSidi = "NULL";
	}
	consulta += "&codigoSIDI=" + elCodigoSidi;
	
	
	consulta +="&diasApagar=1";
	
	consulta +="&formaPago=" + document.getElementById("formaPago").value;	
	consulta +="&email=" + document.getElementById("email").value;
	
	
	consulta +="&numCuenta=" + document.getElementById("numCuenta").value;
	//consulta +="&numCuenta=" +  document.getElementById("numCuenta2").value + document.getElementById("numCuenta").value;
	
	consulta +="&nuestraCuenta=" +  document.getElementById("nuestraCuenta").value;
	
	
	consulta +="&correoDiario=" + document.getElementById("cliente_correoDiario").checked;
	consulta +="&activo=" + document.getElementById("cliente_activo").checked;
	consulta +="&domiciliado=" + document.getElementById("cliente_domiciliado").checked;
	consulta +="&sinIva=" + document.getElementById("cliente_sinIva").checked;
	consulta +="&retener=" + document.getElementById("cliente_retener").checked;
	consulta +="&preFactura=" + document.getElementById("cliente_preFactura").checked;
	consulta +="&noAplicarPF=" + document.getElementById("cliente_sinAplicarPF").checked;
	consulta +="&retencion=" + document.getElementById("cliente_conRetencion").checked;

	
	
	//datos de facturacion
	consulta +="&cuotaRecogida=" + document.getElementById("fac_cuotaRecogida").value;	
	consulta +="&periodo=" + document.getElementById("fac_periodoFac").value;	
	consulta +="&prodNoBon=" + document.getElementById("fac_ProdNoBon").value;	
	consulta +="&otrosConceptos=" + document.getElementById("fac_otrosConceptos").value;	
	consulta +="&importeOtrosConceptos=" +document.getElementById("fac_importeOtrosConceptos").value ;	
	consulta +="&provFondos=" + document.getElementById("fac_provFondos").value;	
	consulta +="&pfFijaImporte=" + document.getElementById("fac_pfFijaImporte").value;
	consulta +="&cobroUnitarioEnvio=" + document.getElementById("fac_cobroUnitarioEnvio").value;	
	
	
	//direccion de envio	
	consulta +="&envio_Att=" + document.getElementById("envio_Att").value;	
	consulta +="&envio_Nombre=" + document.getElementById("envio_Nombre").value;	
	consulta +="&envio_Direccion=" + document.getElementById("envio_Direccion").value;	
	consulta +="&envio_cp=" + document.getElementById("envio_cp").value;	
	consulta +="&envio_poblacion=" +document.getElementById("envio_poblacion").value ;	
	consulta +="&envio_provincia=" + document.getElementById("envio_provincia").value;	
	consulta +="&envio_pais=" + document.getElementById("envio_pais").value;	
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));	
	
	
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
	
	consulta +="&pedidoCliente=" + document.getElementById("cliente_pedido").value;
	consulta += "&vencimiento=" + document.getElementById("vencimiento").value;
	
	
	return consulta;
}


function mostrarModificarCliente()
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
				alert(peticionUnica1.responseText);				
				//cargarListadoPFpendientes();				
			}
				
		}
	}						
}

function insertarContactoCliente()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarContactoCliente;
		peticionUnica1.open("POST","ajax/insertarContactoCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarContactoCliente();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarContactoCliente()
{	
	var consulta = "accion=insertarContactoCliente";	
	consulta += "&idCliente="+document.getElementById("cliente_codigo").value;
	consulta += "&idSexo="+document.getElementById("contSexo").value;
	consulta += "&nombre="+document.getElementById("contNombre").value;
	consulta += "&apellidos="+document.getElementById("contApellidos").value;
	consulta += "&departamento="+document.getElementById("contDepartamento").value;
	consulta += "&cargo="+document.getElementById("contCargo").value;
	consulta += "&telefono="+document.getElementById("contTelefono").value;
	consulta += "&movil="+document.getElementById("contMovil").value;
	consulta += "&email="+document.getElementById("contEmail").value;
	consulta += "&comentario="+document.getElementById("contComentario").value;
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
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
	
	return consulta;	
}

function mostrarInsertarContactoCliente()
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
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarObservacionCliente;
		peticionUnica1.open("POST","ajax/insertarObservacionCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarObservacionCliente();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarObservacionCliente()
{	
	var consulta = "accion=insertarObservacionCliente";	
	consulta += "&idCliente="+document.getElementById("cliente_codigo").value;
	consulta += "&asunto="+document.getElementById("obs_asunto").value;
	consulta += "&texto="+document.getElementById("obs_texto").value;
	
	/*var URLactual = window.location;
	
	var direccion  = URLactual.pathname.substr(URLactual.pathname.lastIndexOf('/'));
	
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
	
	return consulta;	
}

function mostrarInsertarObservacionCliente()
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
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDireccionRutas;
		peticionUnica1.open("POST","ajax/cargarClientesDirecRutas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDireccionRutas();
		peticionUnica1.send(query_string);						
	}
	
}

function consultaCargarDireccionRutas()
{	
	var consulta = "accion=cargarClientesDireccionRutas";	
	consulta += "&idCliente="+document.getElementById("cliente_codigo").value;	
	
	
	
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

function mostrarCargarDireccionRutas()
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
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarDireccionRutaCliente;
		peticionUnica1.open("POST","ajax/insertarDirecRutasCliente.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarDireccionRutaCliente();
		peticionUnica1.send(query_string);						
	}
}

function consultaInsertarDireccionRutaCliente()
{	
	var consulta = "accion=insertarClienteDireccionesRutas";	
	consulta += "&idCliente="+document.getElementById("cliente_codigo").value;
	consulta += "&att="+document.getElementById("dirRutAtt").value;
	consulta += "&nombre="+document.getElementById("dirRutNombre").value;
	consulta += "&direccion="+document.getElementById("dirRutDireccion").value;
	consulta += "&cp="+document.getElementById("dirRutCP").value;
	consulta += "&poblacion="+document.getElementById("dirRutPoblacion").value;
	consulta += "&provincia="+document.getElementById("dirRutProvincia").value;
	consulta += "&pais="+document.getElementById("dirRutPais").value;
	//consulta += "&activo="+document.getElementById("dirRutActivar").checked;
	
	
	
	
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

function mostrarInsertarDireccionRutaCliente()
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
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDireccionRuta;
		peticionUnica1.open("POST","ajax/modificarClienteDireccionRutas.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDireccionRuta(idRegistroRutaDireccion);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarDireccionRuta(idRegistroRutaDireccion)
{	
	var consulta = "accion=modificarClienteDireccionRutas";	
	
	consulta +="&id=" + idRegistroRutaDireccion;
	consulta +="&att=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaAtt").value;	
	consulta +="&nombre=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaNombre").value;	
	consulta +="&direccion=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaDireccion").value;	
	consulta +="&cp=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaCp").value;	
	consulta +="&poblacion=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaPoblacion").value;	
	consulta +="&provincia=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaProvincia").value ;	
	consulta +="&pais=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaPais").value;	
	consulta +="&activo=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaActivo").checked;	
	consulta +="&idCliente=" + document.getElementById("cliente_codigo").value;	 

	
	
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


function mostrarModificarDireccionRuta()
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
				alert(peticionUnica1.responseText);	
				cargarDireccionRutas();				
			}				
		}
	}						
}



function eliminarDireccionRuta(idRegistroRutaDireccion)			
{

	if (confirm('¿Eliminar Direccion de Ruta?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarDireccionRuta;
			peticionUnica1.open("POST","ajax/eliminarClienteDireccionRuta.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarDireccionRuta(idRegistroRutaDireccion);
			peticionUnica1.send(query_string);
		}
	}
	
}

function consultaEliminarDireccionRuta(idRegistroRutaDireccion)
{	
	var consulta = "accion=eliminarRutaDireccionCliente";	
	
	
	consulta +="&id=" + idRegistroRutaDireccion;
	consulta +="&att=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaAtt").value;	
	consulta +="&nombre=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaNombre").value;	
	consulta +="&direccion=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaDireccion").value;	
	consulta +="&cp=" + document.getElementById(idRegistroRutaDireccion+"_dirRutaCp").value;	
	consulta +="&poblacion=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaPoblacion").value;	
	consulta +="&provincia=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaProvincia").value ;	
	consulta +="&pais=" +document.getElementById(idRegistroRutaDireccion+"_dirRutaPais").value;	

	consulta +="&idCliente=" +document.getElementById("cliente_codigo").value;	

	


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


function mostrarEliminarDireccionRuta()
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
				//alert(peticionUnica1.responseText);				
				cargarDireccionRutas();
			}				
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











