var peticionUnica0 = null;

var filaActual = 0;
var cliente; //al salir de la pantalla de cliente hay que limpiar esta variable.
var albaran;
var codigoActivo="";
var enviarImagen = false;
var idInputListado="";
var valorTipo=0;
var valorDepartamento=0;
var pdfPresupuestoGenerado = 0;
var booleanoPass=false;
var unArray = [];
var numFactura="";
var facCompleto="";
var numeroFactura="";
var campo1="";
var error=false;
var booleano = false;
var valorNumero=0;
var valorHoraRuta='';
var arrayCombinaciones = [];
var numColumnas =0;
var listadoTipos = [];
var camposAmostrar1="";
var fechaCambioVerifactu = "2025-11-27";


setInterval(function () {

    var xhr = new XMLHttpRequest();

    xhr.open("POST", "ajax/comprobarSesion.php", true);

    xhr.onreadystatechange = function () {

        if (xhr.readyState == 4 && xhr.status == 200) {

            try {

                var res = JSON.parse(xhr.responseText);

                if (!res.activa) {

                    alert("La sesión ha caducado.");

                    window.location.href = "index.php";
                }

            } catch (e) {

                window.location.href = "index.php";
            }
        }
    };

    xhr.send();

}, 300000);


/*
function cargarComerciales()				
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarComerciales;
		peticionUnica0.open("POST","ajax/cargarComerciales.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarComerciales();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarComerciales()
{	
	var consulta = "accion=cargarComerciales";
	return consulta;	
}


function mostrarCargarComerciales()
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
					document.getElementById("comercial").innerHTML = "";
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
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["nombre"]+'</option>';
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
			peticionUnica0 = null;
		}
	}						
}
*/
function cargarPaises(campo)				
{
	peticionUnica0=crearComunicacion(peticionUnica0);
	input = campo;					
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarPaises;
		peticionUnica0.open("POST","ajax/cargarPaises.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPaises();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarPaises()
{	

	var consulta = "accion=cargarPaises";

	var campos = [
		'id',
		'nombreComun'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var order = [
		{
			campo: 'nombreComun', dir: 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));	

	return consulta;

}


function mostrarCargarPaises()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

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
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreComun"]+'</option>';
							contador++;
						}
							
						document.getElementById(input).innerHTML = contenido;
						
					}
				}
				else
				{
					document.getElementById(input).innerHTML = "";
				}
			}
			peticionUnica0 = null;
			input = null;
		}
	}						
}



function cargarPresupuestadores(campo)				
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{	
		input = campo;
		peticionUnica0.onreadystatechange = mostrarCargarPresupuestadores;
		peticionUnica0.open("POST","ajax/cargarPresupuestadores.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarPresupuestadores();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarPresupuestadores()
{	
	var consulta = "accion=cargarPresupuestador";
	return consulta;	
}


function mostrarCargarPresupuestadores()
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
					document.getElementById(input).innerHTML = "";
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
							contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["nombre"]+'</option>';
							contador++;
						}
							
						document.getElementById(input).innerHTML = contenido;
						
					}
				}
				else
				{
					document.getElementById(input).innerHTML = "";
				}
			}
			peticionUnica0 = null;
			input=null;
		}
	}						
}

function cargarFormasDePago()
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarFormasDePago;
		peticionUnica0.open("POST","ajax/mostrarFormaDePago.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarFormasDePago();
		peticionUnica0.send(query_string);
	}
	
}

function consultaCargarFormasDePago()
{	
	var consulta = "accion=cargarFormaDePago";
	
	return consulta;	
}

function mostrarCargarFormasDePago()
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
						var contenido = "";

						var contador = 0;

						while  (contador<datos.length)
						{
							
							if (datos[contador]["concepto"] == "Al contado")
							{
								contenido += '  <option value="'+datos[contador]["id"]+'" selected>'+datos[contador]["concepto"]+'</option>';	
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["concepto"]+'</option>';
							}
													
							contador++;
						}
						
						document.getElementById("formaPago").innerHTML = contenido;
					}
				}
			}
			peticionUnica0=null;
		}
	}						
}


function cargarFormasDePagoCompraAterceros()
{
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarFormasDePagoCompraAterceros;
		peticionUnica0.open("POST","ajax/mostrarFormaDePagoComprasTerceros.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarFormasDePagoCompraAterceros();
		peticionUnica0.send(query_string);
	}
	
}

function consultaCargarFormasDePagoCompraAterceros()
{	
	var consulta = "accion=cargarFormaDePago";
	
	return consulta;	
}

function mostrarCargarFormasDePagoCompraAterceros()
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
						var contenido = "";

						var contador = 0;

						while  (contador<datos.length)
						{
							
							if (datos[contador]["concepto"] == "Al contado")
							{
								contenido += '  <option value="'+datos[contador]["id"]+'" selected>'+datos[contador]["concepto"]+'</option>';	
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["concepto"]+'</option>';
							}
													
							contador++;
						}
						
						document.getElementById("pedido_formaPago").innerHTML = contenido;
					}
				}
			}
			peticionUnica0=null;
		}
	}						
}





function cargarListadoPresupuesto()//js_presupuestosListado
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarListadoPresupuesto;
		peticionUnica0.open("POST","ajax/mostrarPresupuestos.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPresupuesto();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarListadoPresupuesto()
{	
	var consulta = "accion=mostrarPresupuesto";	
	
	consulta += "&orden="+document.getElementById("columnas").value;
	
	if (document.getElementById("ordenDescendiente").checked)
	{
		consulta += "&desc=desc";
	}
	else
	{
		consulta += "&desc=asc";
	}
	
	
	consulta += "&texto="+document.getElementById("buscarTexto").value;
	
	if (document.getElementById("tipoBusquedaPresupuesto").checked)
	{
		consulta += "&queBusca=presupuesto";
	}
	else
	{
		consulta += "&queBusca=cliente";
	}
	
	return consulta;	
}

function mostrarCargarListadoPresupuesto()
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
				datos = JSON.parse(peticionUnica0.responseText);				
				
				var contenido = "";
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
					contenido += '<th align="center">Presupuesto</th>';
					//contenido += '<th>comercial</th>';			
					contenido += '<th>Cliente</th>';
					contenido += '<th>Campaña</th>';
					contenido += '<th>Fecha Creacion</th>';
					contenido += '<th>Ot Bajada</th>';
					contenido += '<th>ot abierta</th>';
					contenido += '<th>Terminado</th>';

					contenido += '<th></th>';
					contenido += '<th></th>';
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
					contenido += '<td align="center"><span style="overflow:hidden; white-space: nowrap;">'+datos[contador]["inicialComercial"]+"-"+datos[contador]["presupuesto"]+'</span></td>';
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					contenido += '<td>'+datos[contador]["cliente"]+'</td>';
					contenido += '<td>'+datos[contador]["campana"]+'</td>';
					//contenido += '<td><input type="date" id="fechaCreacion" value="'+datos[contador]["fecha"]["date"].substring(0,10)+'" readonly></input></td>';
					
					
					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);
					
					contenido += '<td id="fechaCreacion"><span style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</span></td>';
					
					var soloLectura = '';
					if (document.getElementById("permiso_otBajadaPresu").value==0)
					{
						soloLectura = 'onclick="return false;"';
					}
					
					if(datos[contador]["otBajada"]==1)
					{
						contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otBajada" name="otBajada" value="" checked '+soloLectura+'></input></td>';						
					}
					else
					{
						contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otBajada" name="otBajada" value="" '+soloLectura+'></input></td>';
					}
					
					
					soloLectura = '';
					if (document.getElementById("permiso_otAbiertaPresu").value==0)
					{
						soloLectura = 'onclick="return false;"';
					}
					
					if(datos[contador]["otAbierta"]==1)
					{
						contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otAbierta" name="_otAbierta" value=""  onchange="comprobarFechasAcepYComp(this.id)" checked '+soloLectura+'></input></td>';						
					}
					else
					{
						contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_otAbierta" name="_otAbierta" value=""  onchange="comprobarFechasAcepYComp(this.id)" '+soloLectura+'></input></td>';
					}
					//alert(datos[contador]["fechaRealizada"]);
					//var fechaTerminado = "";
					
					
					soloLectura = '';
					if (document.getElementById("permiso_otAbiertaPresu").value==0 || datos[contador]["fechaCompromiso"]=="" || datos[contador]["fechaCompromiso"]==null )
					{
						soloLectura = 'readonly';
					}
					
					var fechaCompromiso2="";
					
					if (datos[contador]["fechaCompromiso"]==""||datos[contador]["fechaCompromiso"]==null)
					{
						fechaCompromiso2="";						
					}
					else
					{
						fechaCompromiso2=datos[contador]["fechaCompromiso"]["date"].substring(0,10);
					}
					
					if (datos[contador]["fechaTerminado"]==""||datos[contador]["fechaTerminado"]==null)
					{
						contenido += '<td><input type="date" id="'+datos[contador]["presupuesto"]+'_fechaTerminado" value="" '+soloLectura+'></input></td>';
					}
					else
					{ 
						contenido += '<td><input type="date" id="'+datos[contador]["presupuesto"]+'_fechaTerminado" value="'+datos[contador]["fechaTerminado"]["date"].substring(0,10)+'" max="'+fechaCompromiso2+'"  onChange="verificarFechaTerminado(this.value,this.max, this.id)" '+soloLectura+'></input></td>';
					}
					
					contenido += '<td><input type="image" id="'+datos[contador]["presupuesto"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarPresupuesto2('+datos[contador]["presupuesto"]+')"></td>';
					
					contenido += '<td><button type="button" class="btn btn-info" style="height: =5px;line-height: 5px;" id="'+datos[contador]["presupuesto"]+'_verPresupuesto" onclick="irAVerPresupuesto(this.id)" >PPTO</button></td>';
					
					
					
					var fechaAceptacion="";					
					var fechaCompromiso="";
					
					if (datos[contador]["fechaAceptacion"]=="null"||datos[contador]["fechaAceptacion"]==null||datos[contador]["fechaAceptacion"]=="")
					{
						fechaAceptacion="";
					}
					else
					{
						fechaAceptacion=datos[contador]["fechaAceptacion"]["date"].substring(0,10);
					}
					
					if (datos[contador]["fechaCompromiso"]=="null"||datos[contador]["fechaCompromiso"]==null||datos[contador]["fechaCompromiso"]=="")
					{
						fechaCompromiso="";
					}
					else
					{
						fechaCompromiso=datos[contador]["fechaCompromiso"]["date"].substring(0,10);
					}					
					
					contenido += '<td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderTrabajoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;" onClick="pasarDatosPresupuesto(\''+datos[contador]["presupuesto"]+'\',\''+fechaAceptacion+'\',\''+fechaCompromiso+'\')">OT</button></td>';
					
					contenido += '</tr>';					
					
					contador++;	
				}
				
				document.getElementById("listadoPresupuestos").innerHTML = contenido;
			}
			peticionUnica0=null;			
		}
	}						
}
/////////////////////////////
function cargarSubprocesos(nombreCampo,tipo=0)//js_presupeustosAlta
{

	if (nombreCampo!="")
	{
		idInputListado = nombreCampo;
	}
	//alert("entra");
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarSubProcesos;
		peticionUnica1.open("POST","ajax/cargarSubProcesos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubProcesos(tipo);
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarSubProcesos(tipo)
{	
	var consulta = "accion=cargarSubProcesos";	

	var campos = [
		'id',
		'proceso',
		'descripcion'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));
	var filtros;
	if (tipo==1)
	{
		filtros = {
    	idTipoProceso: valorTipo,
		idDepartamento: valorDepartamento
		};
	}
	else
	{
		filtros = {
    	idTipoProceso: document.getElementById("tipoProceso").value,
		idDepartamento: document.getElementById("departamentoProceso").value
		};
	}
	
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 

	var order = [
    	{ campo: 'proceso', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarSubProcesos()
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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{							
						var contenido = "";
						var contador = 0;

						while  (contador<datos.length)
						{
								
							if (datos[contador]["descripcion"]==null || datos[contador]["descripcion"]=="" || datos[contador]["descripcion"]== "null")
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+'</option>';
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+ " --- " + datos[contador]["descripcion"]+'</option>';	
							}

							contador++;
						}
							
						document.getElementById(idInputListado).innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById(idInputListado).innerHTML = "";
				}
			}
			peticionUnica1=null;
		}
	}						
}
/* --:: USAR LA FUNCION cargarSubprocesos()
function cargarSubprocesosGuardados()
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarSubprocesosGuardados;
		peticionUnica0.open("POST","ajax/cargarSubProcesos.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubprocesosGuardados();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarSubprocesosGuardados()
{	
	var consulta = "accion=cargarSubProcesos";	
	consulta += "&idTipoProceso=" + valorTipo;
	consulta += "&idDepartamento=" + valorDepartamento;
	//alert(consulta);
	return consulta;	
}

function mostrarCargarSubprocesosGuardados()
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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{
													
							
						var contenido = "";

						var contador = 0;

						while  (contador<datos.length)
						{

							if (datos[contador]["descripcion"]==null || datos[contador]["descripcion"]=="" || datos[contador]["descripcion"]== "null")
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+'</option>';
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["proceso"]+ " --- " + datos[contador]["descripcion"]+'</option>';	
							}

							contador++;

						}

						document.getElementById(idInputListado).innerHTML = contenido;
						
					}
				}				
			}
			peticionUnica0=null;
		}
	}						
}
*/
/*
function cargarListadoNombreFranqueo()	//js_global		
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarListadoNombreFranqueo;
		peticionUnica0.open("POST","ajax/cargarListadoNombreFranqueo.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoNombreFranqueo();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarListadoNombreFranqueo()
{	
	var consulta = "accion=cargarListadoNombreFranqueo";	
	consulta += "&condicion="+condicion;
	return consulta;	
}

*/

// mostrarCargarListadoNombreFranqueo: lo estoy poniendo la funcion en cada js que lo necesito
/*function mostrarCargarListadoNombreFranqueo()
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
				
				var contador=0;
				var contenido="";
				
				if (idInputListado!="clienteAjusteModal")
				{
					contenido += '<option value="0"></option>';
				}
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["codigo"]+'">'+datos[contador]["nombre_franqueo"]+'</option>';
					contador++;
				}
					
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado = '';
							
			}
			peticionUnica0=null;
		}
	}						
}*/

/*
function cargarClientes(condicion="",destino="",camposAmostrar="") //js_global				
{	
	idInputListado = destino;
	camposAmostrar1 = camposAmostrar;
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarClientesA;
		peticionUnica0.open("POST","ajax/cargarClientes.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesA(condicion);
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarClientesA(condicion)
{	
	var consulta = "accion=cargarClientes";	
	
	consulta +="&condicion=" + condicion;	
	
	return consulta;
}


function mostrarCargarClientesA()
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
							
					var contenido = "";
					if (booleano==true)
					{
						contenido += '  <option value="todos">todos</option>';	
					}
					booleano=false;

					var contador = 0;
					
					if (idInputListado == 'buscarCliente')
					{
						contenido += '  <option value="0">Todos</option>';	
					}

					while  (contador<datos.length)
					{
						if (camposAmostrar1=="codigoynombre")
						{
							contenido += '  <option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["codigo_saldo"]+' - '+datos[contador]["nombre_empresa"]+'</option>';	
						}
						else
						{
							contenido += '  <option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+' - '+datos[contador]["codigo_saldo"]+'</option>';	
						}
						
						contador++;
					}

					document.getElementById(idInputListado).innerHTML = contenido;
				}
				
				idInputListado="";
				camposAmostrar1="";
			}
			peticionUnica0=null;
				
		}
	}						
}
*/
function cargarSubClientes(condicion="",destino="")	//js_global			
{	
	idInputListado = destino;
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarSubClientes;
		peticionUnica0.open("POST","ajax/cargarSubClientes.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubClientes(condicion);
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarSubClientes(condicion)
{	
	var consulta = "accion=cargarClientes";	
	
	consulta +="&condicion=" + condicion;	
	
	return consulta;
}


function mostrarCargarSubClientes()
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
							
					var contenido = "";
					if (booleano==true)
					{
						contenido += '  <option value="todos">todos</option>';	
					}

					var contador = 0;
					
					/*if (idInputListado == 'buscarCliente')
					{
						contenido += '  <option value="0">Todos</option>';	
					}*/

					while  (contador<datos.length)
					{
						contenido += '  <option value="'+datos[contador]["codigo"]+'">'+datos[contador]["subcliente"]+' - '+datos[contador]["codigo"]+'</option>';	
						contador++;
					}

					document.getElementById(idInputListado).innerHTML = contenido;
				}
				
				idInputListado="";
			}
			peticionUnica0=null;
				
		}
	}						
}

function cargarSubClientes2(campos,condicion="",destino="")	//js_global			
{	
	idInputListado = destino;
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarSubClientes2;
		peticionUnica0.open("POST","ajax/cargarSubClientes2.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarSubClientes2(campos,condicion);
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarSubClientes2(campos,condicion)
{	
	var consulta = "accion=cargarClientes";	
	
	consulta +="&condicion=" + condicion;	
	consulta +="&campos=" + campos;	
	
	return consulta;
}


function mostrarCargarSubClientes2()
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
							
					var contenido = "";
					if (booleano==true)
					{
						contenido += '  <option value="todos">todos</option>';	
					}

					var contador = 0;
					
					/*if (idInputListado == 'buscarCliente')
					{
						contenido += '  <option value="0">Todos</option>';	
					}*/

					while  (contador<datos.length)
					{
						contenido += '  <option value="'+datos[contador]["codigo"]+'">'+datos[contador]["subcliente"]+' - '+datos[contador]["codigo"]+'</option>';	
						contador++;
					}

					document.getElementById(idInputListado).innerHTML = contenido;
				}
				
				idInputListado="";
			}
			peticionUnica0=null;
				
		}
	}						
}


function cargarTipoProvisionFondo(idInput,condicion)//js_presupuestosAlta //js_provisonFondosPendiente
{
	idInputListado = idInput;
	
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarTipoProvisionFondo;
		peticionUnica0.open("POST","ajax/mostrarTiposProvisionesFondo.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTipoProvisionFondo(condicion);
		peticionUnica0.send(query_string);						
	}
}
function consultaCargarTipoProvisionFondo(condicion)
{	
	var consulta = "accion=cargarTipoProvisionesFondos";
	

	var campos = [
		'id',
		'tipo'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	if (condicion=="limitacionA")
	{
		var filtrosOperadores = [
			{
				campo1: 'id',
				operador: '!=',
				valor: 4
			}
		];

		consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));
	}

	var order = [
    	{ campo: 'id', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	


	return consulta;	
}

function mostrarCargarTipoProvisionFondo()
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
						var contenido = "";					


						var contador = 0;

						while  (contador<datos.length)
						{
							if (datos[contador]["id"] == 2)
							{
								contenido += '  <option value="'+datos[contador]["id"]+'" selected>'+datos[contador]["tipo"]+'</option>';
							}
							else
							{
								contenido += '  <option value="'+datos[contador]["id"]+'">'+datos[contador]["tipo"]+'</option>';
							}
							
							contador++;
						}

						document.getElementById(idInputListado).innerHTML = contenido;						
					}
				}
			}
			idInputListado = "";
			peticionUnica0=null;
		}
	}						
}


function cargarClientesClayma(condicion="",destino="") //js_global		
{	
	idInputListado = destino;
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarClientesClayma;
		peticionUnica0.open("POST","ajax/cargarClientesClayma.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarClientesClayma(condicion);
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarClientesClayma(condicion)
{	
	var consulta = "accion=cargarClientes";
	consulta +="&condicion=" + condicion;
	return consulta;
}

function mostrarCargarClientesClayma()
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
					var contenido = "";

					var contador = 0;
					
					if (idInputListado == 'buscarCliente')
					{
						contenido += '  <option value="0">Todos</option>';	
					}

					while  (contador<datos.length)
					{
						contenido += '  <option value="'+datos[contador]["codigo_saldo"]+'">'+datos[contador]["nombre_empresa"]+' - '+datos[contador]["codigo_saldo"]+'</option>';	
						contador++;
					}

					document.getElementById(idInputListado).innerHTML = contenido;
				}
				
				idInputListado="";
			}
			peticionUnica0=null;
				
		}
	}						
}

function irAImprimirFacturaClayma(numFactura, anioSeleccionado) //js_admEmisionFacturaPendiente
{		
	document.getElementById("imprimirNumFacturaClayma").value =numFactura;
	document.getElementById("anioSeleccionado3").value =anioSeleccionado;
	document.getElementById("formImprimirFacturaClayma").submit();
}

function irAImprimirFactura(numFactura, anioSeleccionado) //js_admEmisionFacturaPendiente
{
	document.getElementById("imprimirNumFactura").value =numFactura;
	document.getElementById("anioSeleccionado0").value =anioSeleccionado;
	document.getElementById("formImprimirFactura").submit();
}

function crearNuevoFormaDePago()//js_presupuestosAlta
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCrearNuevoFormaDePago;
		peticionUnica0.open("POST","ajax/crearNuevaFormaDePago.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCrearNuevoFormaDePago();
		peticionUnica0.send(query_string);
	}	
}

function consultaCrearNuevoFormaDePago()
{	
	var consulta = "accion=crearFormaDePago";
	
	consulta+="&formaPago="+document.getElementById("nuevaFormaDePago").value;	
	
	return consulta;	
}

function mostrarCrearNuevoFormaDePago()
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
				/*document.getElementById("filaProcesoGuardados").style.visibility = "visible";
				document.getElementById("filaProcesoGuardados").style.display = "table-row";				

				document.getElementById("filaProcesoNuevo").style.visibility = "hidden";
				document.getElementById("filaProcesoNuevo").style.display = "none";

				document.getElementById("filaProcesoGuardados").colSpan = "7";*/
				//alert(peticion26.responseText);
				cargarFormasDePago();
			}
			peticionUnica0=null;
		}
	}						
}

function eliminarPresupuestoDeFacturaDetalleTemporal(numPresupuesto) //js_prefactura
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarEliminarPresupuestoDeFacturaDetalleTemporal;
		peticionUnica0.open("POST","ajax/eliminarPresupuestoDeFacturaDetalleTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarPresupuestoDeFacturaDetalleTemporal(numPresupuesto);
		peticionUnica0.send(query_string);
	}
}

function consultaEliminarPresupuestoDeFacturaDetalleTemporal(numPresupuesto)
{	
	var consulta = "accion=mostrarEliminarPresupuestoDeFacturaDetalleTemporal";
	consulta +="&numPresupuesto="+numPresupuesto;
	return consulta;	
}

function mostrarEliminarPresupuestoDeFacturaDetalleTemporal()
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
			}
			peticionUnica0=null;			
		}
	}						
}

function eliminarPresupuestoDeFacturaTemporal(numPresupuesto) //js_prefactura
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarEliminarPresupuestoDeFacturaTemporal;
		peticionUnica0.open("POST","ajax/eliminarPresupuestoDeFacturaTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarPresupuestoDeFacturaTemporal(numPresupuesto);
		peticionUnica0.send(query_string);
	}
}

function consultaEliminarPresupuestoDeFacturaTemporal(numPresupuesto)
{	
	var consulta = "accion=eliminarPresupuestoDeFacturaTemporal";
	consulta +="&numPresupuesto="+numPresupuesto;
	return consulta;	
}

function mostrarEliminarPresupuestoDeFacturaTemporal()
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
			}
			peticionUnica0=null;			
		}
	}						
}

function calcularTotalTodoPreFactura() //js_prefactura
{
	var totalNeto=0.00;
	var valor =0.00;
	var provision=0;

	var totalNetoIVAincluido=0.00;
	var irpf=0.00;
	var iva=0.00;
	
	provision = document.getElementById("provisionTotal").value;	
	
	unArray.forEach(function(valorId){
		valor = document.getElementById(valorId+'_totalDetalleTemp').value;
		totalNeto = Number(totalNeto) + Number(valor);

		if (document.getElementById(valorId+'_exentoIVADetalleTemp').checked==false)
		{
			valor2 = document.getElementById(valorId+'_totalDetalleTemp').value;
			totalNetoIVAincluido = Number(totalNetoIVAincluido) + Number(valor2);
		}
	});
	
	document.getElementById("Neto").value=totalNeto.toFixed(2);	
	iva= totalNetoIVAincluido*0.21;
	irpf=totalNeto*0.19*-1;

	if (document.getElementById("conIRPFCliente").value=="sinIRPF")
		irpf= 0;
	if (document.getElementById("sinIvaCliente").value=="sinIva")
		iva=0;


	document.getElementById("iva").value= iva.toFixed(2);
	document.getElementById("irpf").value = irpf.toFixed(2);	
	
	document.getElementById("total").value= (totalNeto+iva+irpf).toFixed(2);
	
	document.getElementById("aPagar").value= (totalNeto+iva+irpf-provision).toFixed(2);


	//document.getElementById("iva").value= (totalNeto*0.21).toFixed(2);
	
	/*document.getElementById("iva").value= ((totalNeto*1.21)-totalNeto).toFixed(2);
	
	document.getElementById("total").value= (totalNeto*1.21).toFixed(2);
	
	document.getElementById("aPagar").value= ((totalNeto*1.21).toFixed(2)-provision).toFixed(2);	
	
	if (document.getElementById("sinIvaCliente").value=="sinIva")
	{
		document.getElementById("iva").value= 0;
		document.getElementById("total").value = document.getElementById("Neto").value;
		document.getElementById("aPagar").value = document.getElementById("Neto").value - provision;
	}*/
}

function eliminarDetallesFacturaTemporal(numPresupuesto) //js_prefactura
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarEliminarDetallesFacturaTemporal;
		peticionUnica0.open("POST","ajax/eliminarDetallePrefacturaPorPresupuesto.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarDetallesFacturaTemporal(numPresupuesto);
		peticionUnica0.send(query_string);
	}
}

function consultaEliminarDetallesFacturaTemporal(numPresupuesto)
{	
	var consulta = "accion=eliminarFacturaDetalleTemporal";	
	consulta += "&numPresupuesto="+numPresupuesto;
	
	return consulta;	
}

function mostrarEliminarDetallesFacturaTemporal()
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
			}
			peticionUnica0=null;
		}
	}						
}


function cargarDetallesPrefactura(opcion="") //js_prefactura
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarDetallesPrefactura;
		peticionUnica0.open("POST","ajax/cargarDetallesPrefactura.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesPrefactura(opcion);
		peticionUnica0.send(query_string);
	}
}

function consultaCargarDetallesPrefactura(opcion)
{
	var consulta = "accion=cargarDetallesPrefactura";
	consulta += "&numPresupuesto=" +opcion+ document.getElementById("numPresupuesto").innerHTML;
	return consulta;	
}

function mostrarCargarDetallesPrefactura()
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
				datos = JSON.parse(peticionUnica0.responseText);
				unArray = [];
				var contador=0;
				var contenido = "";
				
				while  (contador<datos.length) 
				{					
					contenido += '<tr><td>Concepto:</td><td colspan="5"> <input type="text" id="'+datos[contador]["id"]+'_procesoTemp" value="'+datos[contador]["concepto"]+'" style="width:100%"></input></td>';					
					
					contenido += '<td ROWSPAN="2" align="center"><input type="image" id="'+datos[contador]["id"]+'_modificarDetalleTemp" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarDetallePreFactura('+datos[contador]["id"]+')" ></td></tr>';
					
					contenido += '<tr><td>Descripcion:</td><td colspan="5"><input type="text" id="'+datos[contador]["id"]+'_descripcionDetalleTemp" value="'+datos[contador]["descripcion"]+'" style="width:100%"></input></td></tr>';
						
					contenido += '<tr><td>Nota Cibeles:</td><td colspan="4"><input type="text" id="'+datos[contador]["id"]+'_notaDetalleTemp" value="'+datos[contador]["notaCibeles"]+'" style="width:100%"></input></td>';
					
					if (datos[contador]["exentoIVA"]==true)
						contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_exentoIVADetalleTemp" checked></input></td>';
					else
						contenido += '<td style="text-align:center;">Exento de IVA: <input type="checkbox" id="'+datos[contador]["id"]+'_exentoIVADetalleTemp"></input></td>';
				
									


					contenido += '<td ROWSPAN="2"><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalleTemp" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarDetallePreFactura('+datos[contador]["id"]+')" ></td></tr>';

					contenido += '<tr>'; 
					
					var valor = datos[contador]["unidades"];
					if (datos[contador]["unidades"].startsWith('.'))
					{
						valor = "0" + datos[contador]["unidades"];
					}
					
					contenido += '<td>Unidad:</td><td><input type="number" id="'+datos[contador]["id"]+'_unidadesDetalleTemp" value="'+valor+'" onkeyup="calcularTotal('+datos[contador]["id"]+')"></input></td>';
										
					valor = datos[contador]["precio"];
					if (datos[contador]["precio"].startsWith('.'))
					{
						valor = "0" + datos[contador]["precio"];
					}					
					
					contenido += '<td>Precio:</td><td><input type="number" id="'+datos[contador]["id"]+'_precioDetalleTemp" value="'+valor+'" onkeyup="calcularTotal('+datos[contador]["id"]+')"></input></td>';
					
					valor = datos[contador]["total"];
					if (datos[contador]["total"].startsWith('.'))
					{
						valor = "0" + datos[contador]["total"];
					}					
					
					
					contenido += '<td>Total:</td><td><input type="number" id="'+datos[contador]["id"]+'_totalDetalleTemp" value="'+valor+'"></input></td>';
					//contenido += '<td>Total:</td><td><input type="number" id="'+datos[contador]["id"]+'_totalDetalleTemp" value="'+valor+'"></input>&nbsp<input type="checkbox" id="'+datos[contador]["id"]+'_IVATemp" onChange="calcularTotal('+datos[contador]["id"]+')">IVA</input></td>';
					//document.getElementById(datos[contador]["id"]+'_IVATemp').checked = document.getElementsByTagName("ivaIncluido");
					
					contenido += '<tr><td colspan="7" style="border:0px;"><hr></td></tr>';

					contenido += '</tr>';
					
					unArray.push(datos[contador]["id"]);
					
					contador++;
				}				
				
				document.getElementById("detallesPrefactura").innerHTML=contenido;				
				
				calcularTotalTodoPreFactura();				
			}
			
			peticionUnica0=null;
		}
	}						
}



function gestionDeCargarClientesListado(destino) //js_prefactura
{
	if (destino=="buscarCliente")
	{
		buscarFactura();
	}	
	else
	{
		if (document.getElementById("clienteOrigen").checked)
		{
			cargarClientesClayma(condicion="A",destino=destino);
		}
		else
		{
			cargarClientes(condicion="A",destino=destino);
		}
		
		document.getElementById(destino).value = valorNumero;
		valorNumero=0;	
	}
	
	
	
	
	
	
	
	/*if (destino=="buscarCliente")
	{
		//cargarListadoFacturas();
		
	}*/
}


/*
function insertarMovimientoPF(codigoCliente, fecha,formaPago,importe,presupuesto="", fechaCuadre="",informacionCuadre="", aPagarFacturaCibeles="", numFactura="",clayma=0) // js_provisionFondosPendiente				
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarInsertarMovimientoPF;
		peticionUnica0.open("POST","ajax/insertarMovimientoPF.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarMovimientoPF(codigoCliente, fecha,formaPago,importe,presupuesto, fechaCuadre,informacionCuadre,clayma);
		peticionUnica0.send(query_string);						
	}
}

function consultaInsertarMovimientoPF(codigoCliente, fecha,formaPago,importe,presupuesto, fechaCuadre,informacionCuadre,clayma)
{	
	var consulta = "accion=insertarMovimiento";	
	
	consulta +="&codigoCliente=" +codigoCliente;	
	
	if (fecha=="" || fecha==null)
	{
		consulta+="&fecha=";
	}
	else
	{	
		var temporal = fecha;

		var anio = temporal.substr(0, temporal.indexOf('-'));
		var mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
		var dia = temporal.substr(temporal.lastIndexOf('-')+1);

		consulta+="&fecha=" + dia + "/" + mes + "/" + anio;
	}
	
	consulta +="&formaPago=" + formaPago;	
	consulta +="&importe=" + importe;	
	consulta +="&presupuesto=" + presupuesto;
	
	if (fechaCuadre!="" && fechaCuadre!=null)
	{
		temporal = fechaCuadre;
	
		anio = temporal.substr(0, temporal.indexOf('-'));
		mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
		dia = temporal.substr(temporal.lastIndexOf('-')+1);

		consulta+="&fechaCuadre=" + dia + "/" + mes + "/" + anio;
	}
	else
	{	
		consulta +="&fechaCuadre=" + fechaCuadre;
	}
	
	consulta +="&informacionCuadre=" + informacionCuadre;
	consulta +="&clayma=" + clayma;
	
	return consulta;
}

function mostrarInsertarMovimientoPF()
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
			}
			peticionUnica0=null;
		}
	}						
}
*/
function modificarDatosPFenCliente(codigoCliente, fecha, importe, id=0) //js_provisionFondosPendiente				
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarModificarDatosPFenCliente;
		peticionUnica0.open("POST","ajax/modificarDatosPFenCliente.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDatosPFenCliente(codigoCliente, fecha, importe,id);
		peticionUnica0.send(query_string);						
	}
}

function consultaModificarDatosPFenCliente(codigoCliente, fecha, importe,id)
{	
	var consulta = "accion=actualizarDatosPFcliente";	
	
	consulta +="&codigoCliente=" + codigoCliente;	
	
	if (fecha==""||fecha==null)
	{
		consulta+="&fecha=";	
	}
	else
	{
		var temporal = fecha;

		var anio = temporal.substr(0, temporal.indexOf('-'));
		var mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
		var dia = temporal.substr(temporal.lastIndexOf('-')+1);

		consulta+="&fecha=" + dia + "/" + mes + "/" + anio;	
	}
	consulta +="&importe=" + importe;	
	
	var clayma="false";
	if (id!=0)
	{
		if (document.getElementById(id+"_clayma").checked)
		{
			clayma="true";
		}
	}
	
	consulta +="&clayma=" + clayma;
	
	return consulta;
}


function mostrarModificarDatosPFenCliente()
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
			}
			peticionUnica0=null;
		}
	}						
}








function cargarListadoEmpleado()	 //js_empleados		
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarListadoEmpleado;
		peticionUnica0.open("POST","ajax/cargarListadoEmpleado.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoEmpleado();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarListadoEmpleado()
{	
	var consulta = "accion=cargarListadoEmpleado";	
	
	return consulta;	
}

function mostrarCargarListadoEmpleado()
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
				
				var contador=0;
				var contenido="";
				if (idInputListado=="listadoEmpleado1")
				{
					contenido += '<option value="">Todos</option>';
				}
				while  (contador<datos.length)
				{
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombre"]+ ' ' + datos[contador]["apellidos"] + '</option>';
					contador++;
				}
				//document.getElementById("listadoEmpleado").innerHTML = contenido;	
				document.getElementById(idInputListado).innerHTML = contenido;	
				idInputListado="";
							
			}
			peticionUnica0=null;
		}
	}						
}

function limpiarFacturasEspeciales2() //js_facturasEspeciales
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarLimpiarFacturasEspeciales2;
		peticionUnica0.open("POST","ajax/eliminarFacturasEspeciales.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaLimpiarFacturasEspeciales2();
		peticionUnica0.send(query_string);						
	}
}

function consultaLimpiarFacturasEspeciales2()
{	
	var consulta = "accion=limpiarFacturasEspeciales2";
	return consulta;	
}

function mostrarLimpiarFacturasEspeciales2()
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
				cargarFacturasEspecialesTemporal();				
			}
			
			peticionUnica0=null;
		}
	}						
}

function cargarFacturasEspecialesTemporal()		//js_facturasEspeciales		
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarFacturasEspecialesTemporal;
		peticionUnica0.open("POST","ajax/cargarFacturasEspecialesTemporal.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarFacturasEspecialesTemporal();
		peticionUnica0.send(query_string);						
	}
}

function consultaCargarFacturasEspecialesTemporal()
{	
	var consulta = "accion=cargarFacturasEspeciales";
	
	return consulta;	
}


function mostrarCargarFacturasEspecialesTemporal()
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
					
					var contenido = "";

					contenido += '<tr class="centrarTexto tablaCabeceraColor">';
						
					contenido+='<th align="center"><b>Id</b></th>';
					contenido+='<th align="center"><b>Cliente</b></th>';
					contenido+='<th align="center"><b>Orden Trabajo</b></th>';
					contenido+='<th align="center"><b>Fecha</b></th>';
					contenido+='<th align="center"><b>Concepto</b></th>';
					contenido+='<th align="center">Unidades</th>';
					contenido+='<th align="center">Precio Unitario</th>';
					/*contenido+='<td align="center"></td>';
					contenido+='<td align="center"></td>';*/
						
					contenido+='</tr>';
					
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
						
						contenido+='<td id="'+datos[contador]["id"]+'_certificado" value="" style="text-align: center">'+datos[contador]["id"]+'</td>';
						
						contenido+='<td id="'+datos[contador]["id"]+'_certCliente" value="" style="text-align: center">'+datos[contador]["nombre_franqueo"]+'</td>';	
						
						contenido+='<td value="" style="text-align: center">'+datos[contador]["ordenTrabajo"]+'</td>';
						
						
						var fecha1 = datos[contador]["fechaFacturacion"]["date"].substring(0,19).replace(" ","T");
						var dia = fecha1.substr(8,2);
						var mes = fecha1.substr(5,2);
						var anio = fecha1.substr(0,4);
						
						
						
						contenido+='<td value="" style="text-align: center">'+dia  + "-" + mes + "-" + anio+'</td>';
								
						contenido+='<td id="'+datos[contador]["id"]+'_concepto"  value="" style="text-align: center">'+datos[contador]["concepto"]+'</td>';
						contenido+='<td id="'+datos[contador]["id"]+'_unidades" value="" style="text-align: center">'+datos[contador]["unidades"]+'</td>';
						contenido+='<td id="'+datos[contador]["id"]+'_precio"  value="" style="text-align: center">'+datos[contador]["precioUnitario"]+'</td>';										
						
						/*contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarEspecial" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRegistroEspecial('+datos[contador]["id"]+')"></td>';
						
						contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarEspecial value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroEspecial('+datos[contador]["id"]+')"></td>';*/
						
												
						contenido+='</tr>';
						
						contador++;
						
					}
					
					document.getElementById("historico1").innerHTML = contenido;					
				}
				else
				{
					document.getElementById("historico1").innerHTML = "";
				}
			}
			peticionUnica0=null;
		}
	}						
}

function copiarCertRut() //js_facturasEspeciales
{	
	/*
	if (document.getElementById("fechaModal").value!="")
	{*/
		limpiarFacturasEspeciales2();
		copiarCertRut2();
	/*}
	else
	{
		alert("Introducir la fecha de facturacion");
		document.getElementById("fechaModal").focus();
	}*/
 
}
function copiarCertRut2() //js_facturasEspeciales
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCopiarCertRut2;
		peticionUnica0.open("POST","ajax/insertarCertYrutasEnEspeciales.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCopiarCertRut2();
		peticionUnica0.send(query_string);						
	}
}

function consultaCopiarCertRut2()
{	
	var consulta = "accion=modificarRegistroEspecial";	
	consulta += "&mes=" + document.getElementById("mesModal").value;
	//consulta += "&fechaFac=" + document.getElementById("fechaModal").value ;
	
	return consulta;	
}

function mostrarCopiarCertRut2()
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
				var datos = peticionUnica0.responseText;
				
				cargarFacturasEspecialesTemporal();
				$("#juntarCertRutModal").modal('hide');
				
				//document.getElementById("fechaModal").value
				//document.getElementById("mesModal").value
				var date = new Date(document.getElementById('anioModal').value+"-"+document.getElementById('mesModal').value+"-5");
				var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
				var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
				//alert (primerDia.getDate() + "-" + (primerDia.getMonth()+1)+ "-" + primerDia.getFullYear());
				//alert (ultimoDia.getDate() + "-" + (ultimoDia.getMonth()+1)+ "-" + ultimoDia.getFullYear());
				
				document.getElementById("fechaInicio").innerHTML = primerDia.getDate() + "-" + (primerDia.getMonth()+1)+ "-" + primerDia.getFullYear();
				document.getElementById("fechaFin").innerHTML = ultimoDia.getDate() + "-" + (ultimoDia.getMonth()+1)+ "-" + ultimoDia.getFullYear();
				
				document.getElementById("fechaFacturacion").innerHTML = datos.trim();
				/*
				var temporal = document.getElementById("fechaModal").value;		
				
				var anio = temporal.substr(0, temporal.indexOf('-'));
				var mes = temporal.substr(temporal.indexOf('-')+1,temporal.length  - temporal.lastIndexOf('-') -1);
				var dia = temporal.substr(temporal.lastIndexOf('-')+1);
				
				document.getElementById("fechaFacturacion").innerHTML = dia+"-"+mes+"-"+anio;
				*/
			}
			
			peticionUnica0=null;
		}
	}						
}


function cargarRutasParaVincular() //js_rutasAdicionales
{
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarRutasParaVincular;
		peticionUnica0.open("POST","ajax/cargarRutasParaVincular.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRutasParaVincular();
		peticionUnica0.send(query_string);						
	}		
}

function consultaCargarRutasParaVincular()
{	
	var consulta = "accion=cargarRutasParaVincular";	
	
	return consulta;	
}

function mostrarCargarRutasParaVincular()
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
													
						var contenido = "";					


						var contador = 0;
						
						if (idInputListado=="LR" || idInputListado=="MR" || idInputListado=="XR" || idInputListado=="JR" || idInputListado=="VR"|| idInputListado=="rutaRutaBuscar")
						{
							contenido += '  <option value=""></option>';
						}

						while  (contador<datos.length)
						{
							if (datos[contador]["ruta"] != null && datos[contador]["ruta"] != "")
							{
								contenido += '  <option value="'+datos[contador]["ruta"]+'">'+datos[contador]["ruta"]+'</option>';
							}

							contador++;
						}
						
						document.getElementById(idInputListado).innerHTML = contenido;						
					}
				}				
			}
			peticionUnica0=null;	
		}
			
	}						
}

function calcularTotal(id) //js_prefactura
{
	var total=0.00;
	var unidad = document.getElementById(id+'_unidadesDetalleTemp').value;
	var precio = document.getElementById(id+'_precioDetalleTemp').value;
	if (document.getElementById(id+'_procesoTemp').value=='Manipulado de Productos No Bonificables')
	{
		total = (unidad*precio/100).toFixed(2);
	}
	else
	{
		total = (unidad*precio).toFixed(2);
	}



	
	document.getElementById(id+'_totalDetalleTemp').value=total;
	//calcularTotalTodoPreFactura();
}

function eliminarDetallePreFactura(id) //js_prefactura
{	
	if (confirm('¿Borrar Detalle?')) 
	{
		peticionUnica0=crearComunicacion(peticionUnica0);

		if(peticionUnica0)
		{							
			peticionUnica0.onreadystatechange = mostrarEliminarDetallePreFactura;
			peticionUnica0.open("POST","ajax/eliminarDetallePrefactura.php",false);
			peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarDetallePreFactura(id);
			peticionUnica0.send(query_string);
		}		
	}
}

function consultaEliminarDetallePreFactura(id)
{	
	var consulta = "accion=eliminarDetalle";	
	consulta+="&idDetalle="+id;
	
	return consulta;	
}

function mostrarEliminarDetallePreFactura()
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
				var URLactual = window.location;
				var url = URLactual.pathname.substring(URLactual.pathname.lastIndexOf("/"))
				
				if (url=="/prefacturaMensual.php")
				{
					cargarDetallesPrefactura('Factura Original: ');
				}
				else
				{
					cargarDetallesPrefactura();
				}
				
				//
			}
		}
	}						
}

function modificarDetallePreFactura(id) //js_prefactura
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarModificarDetallePreFactura;
		peticionUnica0.open("POST","ajax/modificarDetallePreFactura.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDetallePreFactura(id);
		peticionUnica0.send(query_string);
	}
}

function consultaModificarDetallePreFactura(id)
{	
	var consulta = "accion=modificarDetallePrefactura";
	
	consulta+="&idDetalle="+id;		
	consulta += "&concepto=" + document.getElementById(id+"_procesoTemp").value;	
	
	//consulta += "&descripcion=" + document.getElementById(id+"_descripcionDetalleTemp").value;	
	consulta +="&descripcion=" + reemplazarSimbolos(document.getElementById(id+"_descripcionDetalleTemp").value);
	
	//consulta += "&nota=" + document.getElementById(id+"_notaDetalleTemp").value;	
	consulta +="&nota=" + reemplazarSimbolos(document.getElementById(id+"_notaDetalleTemp").value);
	
	
	var unidad = document.getElementById(id+"_unidadesDetalleTemp").value;	
	unidad = unidad.replace(',','.');
	if (unidad == "")
	{
		unidad =0;
	}
	consulta += "&unidad=" + unidad;
	
	var precio = document.getElementById(id+"_precioDetalleTemp").value;
	precio = precio.replace(',','.');
	if (precio == "")
	{
		precio =0;
	}
	
	consulta += "&precio=" + precio;
	
	var total = document.getElementById(id+"_totalDetalleTemp").value;
	total = total.replace(',','.');
	if (total == "")
	{
		total =0;
	}
	
	consulta += "&total=" + total;
	
	consulta += "&exentoIVA="+document.getElementById(id+"_exentoIVADetalleTemp").checked;
	
	return consulta;	
}

function mostrarModificarDetallePreFactura()
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
				alert(peticionUnica0.responseText);
				peticionUnica0=null;
				cargarDetallesPrefactura();
				//calcularTotalTodoPreFactura();
			}
			peticionUnica0=null;
		}
	}						
}







function insertarMovivimientoFacturaCibeles(numeroFactura) //js_facturasSinCobrar
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarInsertarMovivimientoFacturaCibeles;
		peticionUnica0.open("POST","ajax/insertarMovimientoFactura.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarMovivimientoFacturaCibeles(numeroFactura);
		peticionUnica0.send(query_string);
	}
	
}

function consultaInsertarMovivimientoFacturaCibeles(numeroFactura)
{	
	var consulta = "accion=insertarMovimientoFacturas";
	consulta += "&factura="+numeroFactura;
	return consulta;	
}

function mostrarInsertarMovivimientoFacturaCibeles()
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
				//alert(peticionUnica0.responseText);
			}
		}
	}						
}






	

	
	
function reemplazarSimbolos(texto)
{
	var resultado = texto;
	
	
	resultado = resultado.replaceAll('"',' ');
	resultado = resultado.replaceAll("'",' ');
	resultado = resultado.replaceAll("#",' ');
	//resultado = resultado.replaceAll("%",' ');
	
	resultado = resultado.replaceAll('&',' ');
	
	resultado = resultado.replaceAll('?',' ');
	
	resultado = resultado.replaceAll('+','%2B');
	resultado = resultado.replaceAll('ñ','%C3%B1');
	resultado = resultado.replaceAll('Ñ','%C3%91');
	resultado = resultado.replaceAll('²','%C2%B2');
	
		
	return resultado;
}

function reemplazarSimbolos2(texto)
{
	var resultado = texto;
	
	
	resultado = resultado.replaceAll('"',' ');
	resultado = resultado.replaceAll("'",' ');
	resultado = resultado.replaceAll("#",' ');
	resultado = resultado.replaceAll("%",' ');
	
	resultado = resultado.replaceAll('&','%26');
	
	resultado = resultado.replaceAll('?',' ');
	
	resultado = resultado.replaceAll('+','%2B');
	resultado = resultado.replaceAll('ñ','%C3%B1');
	resultado = resultado.replaceAll('Ñ','%C3%91');
	resultado = resultado.replaceAll('²','%C2%B2');
	
	
		
	return resultado;
}
function reemplazarSimbolos3(texto) //pensado para los nombre de las carpetas
{
	var resultado = texto;
	
	resultado = resultado.replaceAll('!',' ');	
	resultado = resultado.replaceAll('"',' ');
	resultado = resultado.replaceAll("'",' ');
	resultado = resultado.replaceAll("#",' ');
	resultado = resultado.replaceAll("%",' ');
	resultado = resultado.replaceAll('&',' ');
	resultado = resultado.replaceAll('?',' ');
	resultado = resultado.replaceAll('/',' ');
	resultado = resultado.replaceAll('|',' ');
	resultado = resultado.replaceAll('*',' ');
	resultado = resultado.replaceAll(':',' ');
	resultado = resultado.replaceAll('<',' ');
	resultado = resultado.replaceAll('>',' ');
	resultado = resultado.replaceAll('¡',' ');
	resultado = resultado.replaceAll('@','a');
	
	resultado = resultado.replaceAll('º',' ');
	resultado = resultado.replaceAll('ª',' ');
	resultado = resultado.replaceAll('\\',' ');
	resultado = resultado.replaceAll('·',' ');
	resultado = resultado.replaceAll('~',' ');
	resultado = resultado.replaceAll('¬',' ');
	resultado = resultado.replaceAll('¿',' ');
	resultado = resultado.replaceAll('`',' ');
	resultado = resultado.replaceAll('´',' ');
	resultado = resultado.replaceAll('ç','c');
	resultado = resultado.replaceAll('Ç','C');
	
	resultado = resultado.replaceAll('¨',' ');
	resultado = resultado.replaceAll('€',' ');
	
	
	resultado = resultado.replaceAll('Á','A');
	resultado = resultado.replaceAll('É','E');
	resultado = resultado.replaceAll('Í','I');
	resultado = resultado.replaceAll('Ó','O');
	resultado = resultado.replaceAll('Ú','U');
	
	resultado = resultado.replaceAll('á','a');
	resultado = resultado.replaceAll('é','e');
	resultado = resultado.replaceAll('i','i');
	resultado = resultado.replaceAll('ó','o');
	resultado = resultado.replaceAll('ú','u');
	
	resultado = resultado.replaceAll('ñ','n');
	resultado = resultado.replaceAll('Ñ','N');
	resultado = resultado.replaceAll('²','%C2%B2');
	 
	
		
	return resultado;
}

function reemplazarSimbolosBusqueda(texto)
{
	var resultado = texto;
	
	//esultado = resultado.replaceAll('"','%22');
	//resultado = resultado.replaceAll('#','%23');
	resultado = resultado.replaceAll('%','%25');
	resultado = resultado.replaceAll('&','%26');
	resultado = resultado.replaceAll('+','%2B');
	resultado = resultado.replaceAll('ñ','%C3%B1');
	resultado = resultado.replaceAll('Ñ','%C3%91');
	
	
	return resultado;
}

function cargarProveedores()//js_presupuestosListado
{	
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarProveedores;		
		
		peticionUnica0.open("POST","ajax/cargarProveedor.php",false);
		
		//peticionUnica0.open("POST","ajax/cargarClientes.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarProveedores();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarProveedores()
{	
	var consulta = "accion=cargarProveedores";		
	
	consulta += "&condicion= order by proveedor";
	
	return consulta;	
}

function mostrarCargarProveedores()
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
				datos = JSON.parse(peticionUnica0.responseText);				
				
				var contenido = "";
				var contador = 0;	
				
				while  (contador<datos.length)
				{												 
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["proveedor"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById("proveedores").innerHTML = contenido;
			}
			peticionUnica0=null;			
		}
	}						
}


function cargarAnios(input)//js_presupuestosListado
{	
	idInputListado = input
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarAnios;		
		
		peticionUnica0.open("POST","ajax/cargarAnios.php",false);	
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAnios();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarAnios()
{	
	var consulta = "accion=cargarAnios";
	
	return consulta;	
}

function mostrarCargarAnios()
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
				datos = JSON.parse(peticionUnica0.responseText);				
				
				var contenido = "";
				var contador = 0;	
				
				while  (contador<datos.length)
				{	
					//if (contador==datos.length-1)
					if (contador==0)
					{
						contenido += '<option value="'+datos[contador]["anio"]+'">'+datos[contador]["anio"]+'</option>';						
					}
					else
					{
						contenido += '<option value="'+datos[contador]["anio"]+'" selected>'+datos[contador]["anio"]+'</option>';
					}
					
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
			}
			peticionUnica0=null;			
		}
	}						
}


function cambiarContrasenia()
{
	booleanoPass=false;
	comprobarContraseniaActual();
	if (booleanoPass)	
	{		
		if (document.getElementById("passNueva").value != document.getElementById("passNuevaRepe").value)
		{
			alert("No coincide la contraseña nueva con la repetición");
		}		
		else
		{
			var tamanio=document.getElementById("passNueva").value.length;
			var mayuscula = false;
			var minuscula = false;
			var numero = false;
			var caracter_raro = false;
			var cadena = document.getElementById("passNueva").value;
			var seguir = false;
			
			if (tamanio >=8)
			{			
				for(var i = 0;i<tamanio;i++)
				{
					if(cadena.charCodeAt(i) >= 65 && cadena.charCodeAt(i) <= 90)
					{
						mayuscula = true;
					}
					else if(cadena.charCodeAt(i) >= 97 && cadena.charCodeAt(i) <= 122)
					{
						minuscula = true;
					}
					else if(cadena.charCodeAt(i) >= 48 && cadena.charCodeAt(i) <= 57)
					{
						numero = true;
					}
					else
					{
						caracter_raro = true;
					}
				}
				
				if (mayuscula&&minuscula&&numero)
				{
					seguir = true;
				}
			}
			
			
			if (seguir)
			{				
				cambiarContrasenia2();
			}
			else
			{
				alert("La Contraseña debe tener:\n  - Mayusculas\n  - Minusculas\n  - Numero\n\  - Al menos 8 caracteres");
			}
		}
	}
	else
	{
		alert("Contraseña Actual Incorrecta");
	}
}


function comprobarContraseniaActual()
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarComprobarContraseniaActual;
		peticionUnica0.open("POST","ajax/comprobarContraseniaActual.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarContraseniaActual();
		peticionUnica0.send(query_string);		
	}
}
function consultaComprobarContraseniaActual()
{	
	var consulta = "accion=comprobarContrasenia";
	consulta += "&contra="+document.getElementById("passActual").value;
	return consulta;	
}

function mostrarComprobarContraseniaActual()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			if (peticionUnica0.responseText.substr(0,5)=="Error")
			{				
				//alert(peticion42.responseText);
				peticionUnica0=null;
				booleanoPass=false;
			}
			else
			{	
				peticionUnica0=null;
				booleanoPass=true;
			}
		}
	}						
}





function cambiarContrasenia2()
{
	peticionUnica0=crearComunicacion(peticionUnica0);
							
	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCambiarContrasenia;
		peticionUnica0.open("POST","ajax/cambiarContraseniaActual.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCambiarContrasenia();
		peticionUnica0.send(query_string);		
	}
}
function consultaCambiarContrasenia()
{	
	var consulta = "accion=cambiarContrasenia";
	consulta += "&contra="+document.getElementById("passNueva").value;
	return consulta;	
}

function mostrarCambiarContrasenia()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			if (peticionUnica0.responseText.substr(0,5)=="Error")
			{				
				alert(peticionUnica0.responseText);
				peticionUnica0=null;				
			}
			else
			{	
				alert("Contraseña Cambiada");				
				$("#cambiarContrasenia").modal('hide');
				
			}
		}
	}						
}


function cargarTamanios(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarTamanios;
		peticionUnica0.open("POST","ajax/cargarTamaniosPapel.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTamanios();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarTamanios()
{	
	var consulta = "accion=cargarTamaniosPapel";	
	return consulta;	
}

function mostrarCargarTamanios()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{				
						var contenido = "";
						var contador = 0;
						while  (contador<datos.length)
						{  
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tamano"]+'</option>';
							
							contador++;	
						}
						
						document.getElementById(idInputListado).innerHTML = contenido;						
					}
				}
				else
				{
					document.getElementById(idInputListado).innerHTML = "";
				}
				idInputListado="";						
			}
			peticionUnica0=null;
		}
	}						
}

function cargarTipos(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarTipos;
		peticionUnica0.open("POST","ajax/cargarTiposPapel.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTipos();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarTipos()
{	
	var consulta = "accion=cargarTipos";	
	return consulta;	
}

function mostrarCargarTipos()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{	
				
						var contenido = "";
						
						var contador = 0;			
						while  (contador<datos.length)
						{  
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tipo"]+'</option>';
							
							contador++;	
						}
						
						document.getElementById(idInputListado).innerHTML = contenido;
					}
				}
				else
				{
					document.getElementById(idInputListado).innerHTML = "";
				}
				idInputListado="";
						
			}
			peticionUnica0=null;			
		}
	}						
}




function cargarAcabado(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarAcabado;
		peticionUnica0.open("POST","ajax/cargarAcabadoPapel.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAcabado();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarAcabado()
{	
	var consulta = "accion=cargarAcabado";	
	return consulta;	
}

function mostrarCargarAcabado()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{	
				
						var contenido = "";
						
						var contador = 0;	
						while  (contador<datos.length)
						{  
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["acabado"]+'</option>';
							
							contador++;	
						}
						
						document.getElementById(idInputListado).innerHTML = contenido;
					}
				}
				else
				{
					document.getElementById(idInputListado).innerHTML = "";
				}
				idInputListado="";
						
			}
			peticionUnica0=null;						
		}
	}						
}


function cargarGramaje(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarGramaje;
		peticionUnica0.open("POST","ajax/cargarGramajePapel.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGramaje();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarGramaje()
{	
	var consulta = "accion=cargarGramaje";	
	return consulta;	
}

function mostrarCargarGramaje()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{	
				
						var contenido = "";
						
						var contador = 0;					
						while  (contador<datos.length)
						{  
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["gramaje"]+'</option>';
							
							contador++;	
						}
				
						document.getElementById(idInputListado).innerHTML = contenido;
				
					}
				}
				else
				{
					document.getElementById(idInputListado).innerHTML = "";
				}
				idInputListado="";
						
			}
			peticionUnica0=null;
		}
	}						
}



function cargarTipoImpresoras(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarTipoImpresoras;
		peticionUnica0.open("POST","ajax/cargarTipoImpresora.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaTipoImpresoras();
		peticionUnica0.send(query_string);
	}
}

function consultaTipoImpresoras()
{	
	var consulta = "accion=cargarTipoImpresoras";	
	return consulta;	
}

function mostrarTipoImpresoras()
{
	if (peticionUnica0.readyState == 4)
	{
		if(peticionUnica0.status == 200)
		{
			var res = JSON.parse(peticionUnica0.responseText);

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
						//alert("No hay ningun registro");
						//contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
					}
					else
					{				
						var contenido = "";
						contenido += '<option value="0">_ninguno</option>';
						var contador = 0;				
						while  (contador<datos.length)
						{  
							contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["tipoImpresora"]+'</option>';
							
							contador++;	
						}
						
						document.getElementById(idInputListado).innerHTML = contenido;
					}	
				}			
				else
				{
					document.getElementById(idInputListado).innerHTML = "";
				}
				idInputListado="";						
			}
			peticionUnica0=null;
		}
	}						
}


function cargarGFTipoProceso(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarGFTipoProceso;
		peticionUnica0.open("POST","ajax/cargarGFConcepto.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGFTipoProceso();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarGFTipoProceso()
{	
	var consulta = "accion=cargarGFTipoProceso";	
	return consulta;	
}

function mostrarCargarGFTipoProceso()
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
				
				var contenido = "";
				//contenido += '<option value="0">_ninguno</option>';
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreConcepto"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica0=null;			
		}
	}						
}



function cargarGFMaterial(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarGFMaterial;
		peticionUnica0.open("POST","ajax/cargarGFSubConcepto1.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGFMaterial();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarGFMaterial()
{	
	var consulta = "accion=cargarGFMaterial";	
	return consulta;	
}

function mostrarCargarGFMaterial()
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
				
				var contenido = "";
				//contenido += '<option value="0">_ninguno</option>';
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreSubconcepto"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica0=null;			
		}
	}						
}




function cargarGFConcepto(idInput) 
{	
	idInputListado = idInput;
	peticionUnica0=crearComunicacion(peticionUnica0);

	if(peticionUnica0)
	{							
		peticionUnica0.onreadystatechange = mostrarCargarGFConcepto;
		peticionUnica0.open("POST","ajax/cargarGFSubConcepto2.php",false);
		peticionUnica0.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGFConcepto();
		peticionUnica0.send(query_string);
	}
}

function consultaCargarGFConcepto()
{	
	var consulta = "accion=cargarGFConcepto";	
	return consulta;	
}

function mostrarCargarGFConcepto()
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
				
				var contenido = "";
				//contenido += '<option value="0">_ninguno</option>';
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["id"]+'">'+datos[contador]["nombreSubconcepto2"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica0=null;			
		}
	}						
}

/*function crearComunicacion(laPeticion)
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
}*/

/*IR ARRIBA*/



	
function scrollUp()
{
	var currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
	if (currentScroll>1)
	{
		window.requestAnimationFrame(scrollUp);			
		window.scrollTo(0, currentScroll - (currentScroll / 2 ));
		
	}
	else {
		window.scrollTo(0, 0); // Asegura que llegue al tope exacto
	}
	
}

var buttonUp = document.getElementById("button-up");

window.onscroll = function(){
	var scroll = document.documentElement.scrollTop || document.body.scrollTop;
	
	if (scroll > 500)
	{
		buttonUp.style.transform = "scale(1)";
		
		
	}
	else
	{			
		buttonUp.style.transform = "scale(0)";
	}

	//if (scroll <10)
		//buttonUp.removeEventListener("click", scrollUp);
		
}

//document.getElementById("button-up").addEventListener("click", scrollUp);

function crearComunicacion(laPeticion="") {	
	var laPeticion="";			
	if (!laPeticion && typeof XMLHttpRequest !== 'undefined') {
		laPeticion = new XMLHttpRequest();
	}
	
	return laPeticion;
}