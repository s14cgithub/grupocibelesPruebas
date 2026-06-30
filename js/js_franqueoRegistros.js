var peticionUnica1 = null;
var laCondicion="";
var anioSeleccionado="";

function buscarRegistroFranqueo()
{
	var condicion="";
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("buscarDesc").checked;
	
	
	if (campoAbuscar =="t1.idCliente" && textoAbuscar!="")
	{
		condicion = " where "+campoAbuscar+" = '" + textoAbuscar + "'";
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
	
	
	cargarRegistrosFranqueo();
	
}

function cargarAniosFranqueoTipo()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarAniosFranqueoTipo;
		peticionUnica1.open("POST","ajax/cargarFranqueoTipo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAniosFranqueoTipo();
		peticionUnica1.send(query_string);
	}
}
function consultaCargarAniosFranqueoTipo()
{	
	var consulta = "accion=cargarFranqueoTipo";		

	var campos = [
		'aniosDistintos'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	
	var order = [
    	{ campo: 'aniosDistintos', dir: 'ASC' }		
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;
	
}

function mostrarCargarAniosFranqueoTipo()
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
					var contador = 0;	
					
					while  (contador<datos.length)
					{	
						if (contador === datos.length - 1) {
							contenido += '<option value="'+datos[contador]["aniosDistintos"]+'" selected>'+datos[contador]["aniosDistintos"]+'</option>';
						}
						else
						{
							contenido += '<option value="'+datos[contador]["aniosDistintos"]+'">'+datos[contador]["aniosDistintos"]+'</option>';
						}
						
						contador++;	
					}
					
					document.getElementById("anioSeleccionado").innerHTML = contenido;
										
				}
							
			}
			peticionUnica1=null;
		}
	}						
}


function cargarRegistrosFranqueo()
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarRegistrosFranqueo;
		peticionUnica1.open("POST","ajax/cargarFranqueoTipo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarRegistrosFranqueo();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarRegistrosFranqueo()
{	
	var consulta = "accion=cargarFranqueoTipo";

	var campos = [
		'fecha',
		'idCliente',
		'producto_Padre_left',
		'tituloTarifasProducto2',
		'ot',
		'unidades',
		'importe',
		'idProductoPadre_left',
		'referencia',
		'nombre_franqueo'
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));


	var filtros = {
    	anio: document.getElementById("anioSeleccionado").value		
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var joins = [
		'tabla2',
		'tabla3',
		'tabla11',
		'tabla10',
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins)); 

	var filtrosLike = [
		{
			campo: document.getElementById("buscarCampo").value,
			valor: document.getElementById("buscarTexto").value
		}
	];

	consulta += "&filtrosLike=" + encodeURIComponent(JSON.stringify(filtrosLike));


	var order = [
		{
			campo: document.getElementById("ordenBuscar").value,
			dir: document.getElementById("buscarDesc").checked ? 'DESC' : 'ASC'
		}
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	consulta += "&anioTarifas=" + document.getElementById("anioSeleccionado").value;

	return consulta;	
}

function mostrarCargarRegistrosFranqueo()
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
				
				var contenido = "";
				contenido += '<tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">Frecha</th>';	
				contenido += '<th align="center">Cliente</th>';	
				contenido += '<th>Producto</th>';
				contenido += '<th>Destino</th>';
				contenido += '<th>OT</th>';					
				contenido += '<th>Unidades</th>';
				contenido += '<th>Importe</th>';
				contenido += '<th>Referencia</th>';
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

					var dia = datos[contador]["fecha"]["date"].substr(8,2);
					var mes = datos[contador]["fecha"]["date"].substr(5,2);
					var anio = datos[contador]["fecha"]["date"].substr(0,4);					
					
					contenido += '<td style="overflow:hidden; white-space: nowrap;">'+dia + "-" + mes+ "-" + anio+'</td>';	

					contenido += '<td>'+datos[contador]["idCliente"] + " - " +datos[contador]["nombre_franqueo"] + '</td>';		
					
					var producto = datos[contador]["producto"];					
					if (producto == null)
					{
						producto = '';
					}

					var titulo = datos[contador]["titulo"];					
					if (titulo == null)
					{
						titulo = '';
					}

					var referencia = datos[contador]["referencia"];					
					if (referencia == null)
					{
						referencia = '';
					}
				
					contenido += '<td>'+producto+'</td>';		
					contenido += '<td>'+titulo+'</td>';		
					contenido += '<td>'+datos[contador]["ot"]+'</td>';		
					contenido += '<td align="right" >'+datos[contador]["unidades"]+'</td>';					
					contenido += '<td align="right" style="overflow:hidden; white-space: nowrap;">'+Number(datos[contador]["importe"]).toLocaleString('de-DE',{minimumFractionDigits: 2})+' €</td>'; 
						
					

					//contenido += '<td>'+datos[contador]["referencia"]+'</td>';	
					if (producto=="null" || producto == null )
					{
						contenido += '<td>'+referencia+'</td>';
					}
					else
					{
						contenido += '<td><a href="franqueoGrabacion.php?producto='+datos[contador]["idProductoPadre"]+'&fecha='+anio + "-" + mes+ "-" + dia+'&referencia='+referencia+'" target="_blank"  style="color:#FFFFFF;">'+referencia+'</a></td>';
					}

							
					
					//contenido += '<td><input type="image" value="" src="imagenes/prefactura.png" style="width:15px;" id="'+datos[contador]["presupuesto"]+'_prefactura"" onclick="irAprefactura('+datos[contador]["presupuesto"]+',\''+datos[contador]["inicial"]+'\')"></td>';					
					
					//contenido += '<td align="center"><input type="checkbox" id="'+datos[contador]["presupuesto"]+'_combinado" onchange="gestionCombiacionesPresu(\''+datos[contador]["presupuesto"]+'\')"></input></td>';
					
					contenido += '</tr>';
					
					contador++;	
				}

				document.getElementById("historico1").innerHTML = contenido;

			}
			peticionUnica1=null;
			
		}
	}						
}