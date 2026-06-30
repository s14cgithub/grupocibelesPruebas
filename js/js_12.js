var peticionUnica1 = null;
var permisosSoloLectura = null;

function ejecutarF12()//js_12
{
	if (confirm("¿EJECUTAR F12?")) 
	{
	  ejecutarF12_2();
	}
}


function ejecutarF12_2() //js_12			
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEjecutarF12_2;
		peticionUnica1.open("POST","ajax/ejecutarF12.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEjecutarF12_2();
		peticionUnica1.send(query_string);						
	}
}

function consultaEjecutarF12_2()
{	
	var consulta = "accion=ejecutarF12";
	return consulta;	
}


function mostrarEjecutarF12_2()
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
				rellenarHistoricoFranqueoEnviosEspeciales();		
			}
			peticionUnica1=null;
		}
	}						
}


function rellenarHistoricoFranqueoEnviosEspeciales()	//js_12		
{	
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarRellenarHistoricoFranqueoEnviosEspeciales;
		peticionUnica1.open("POST","ajax/cargarFranqueoTipo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string =consultaRellenarHistoricoFranqueoEnviosEspeciales();
		peticionUnica1.send(query_string);
	}	
}

function consultaRellenarHistoricoFranqueoEnviosEspeciales()
{	
	var consulta = "accion=cargarFranqueoTipo";
	
	var campos = [
		'id',
		'idCliente',
		'ot',
		'gramosTipo',
		'unidades',
		'importe',
		'comprobado'
	];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
		tipo: '1'
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 

	var order = [
    	{ campo: 'id', dir: 'DESC' }		
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));

	return consulta;

}

function mostrarRellenarHistoricoFranqueoEnviosEspeciales()
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

					contenido+='<tr>';
					
					contenido+='<td align="center"><b>id</td>';				
					contenido+='<td align="center"><b>Cliente</td>';					
					contenido+='<td align="center"><b>ot</td>';
					contenido+='<td align="center"><b>gramos</td>';
					contenido+='<td align="center"><b>envios</td>';
					contenido+='<td align="center"><b>precioUnidad</td>';
					contenido+='<td align="center"><b>precioTotal</td>';
					//contenido+='<td align="center"></td>';
					contenido+='<td align="center"></td>';
						
					contenido+='</tr>';					
					
					var contador = 0;					
					
					while  (contador<datos.length)
					{
						contenido+='<tr>';
						
						contenido+='<td><input value="'+datos[contador]["id"]+'" style="width: 100%;text-align: center" readonly></td>';	
						contenido+='<td><input value="'+datos[contador]["idCliente"]+'" style="width: 100%;text-align: center" readonly></td>';			
						contenido+='<td><input value="'+datos[contador]["ot"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["gramos"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["unidades"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["importe"]/datos[contador]["unidades"]+'" style="width: 100%;text-align: center" readonly></td>';
						contenido+='<td><input value="'+datos[contador]["importe"]+'" style="width: 100%;text-align: center" readonly></td>';
						
						//contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_modificarRegistroF" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarRegistroFranqueo('+datos[contador]["id"]+')"></td>';	
						if (!permisosSoloLectura)
						{
							if(datos[contador]["comprobado"]=="0")
							{
								contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarRegistroF" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarRegistroFranqueoEspecial('+datos[contador]["id"]+')"></td>';
							}
							else
							{
								contenido+='<td><input type="image" id="'+datos[contador]["id"]+'_eliminarRegistroF" value="" src="imagenes/eliminarRojo.png" style="width:20px;" onclick="eliminarRegistroFranqueoEspecial('+datos[contador]["id"]+')"></td>';
							}
						}
						
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
			peticionUnica1=null;
		}
	}						
}

function calcularImporteTotalEnvioEspecial() //js_12
{
	var precioTotal=0;	
	
	if(!isNaN(document.getElementById("totalEnvios").value) && !isNaN(document.getElementById("precioUnidad").value))
	{	
		precioTotal = Number(document.getElementById("totalEnvios").value) * Number(document.getElementById("precioUnidad").value);			
	}
	else
	{
		
	}
	document.getElementById("precioTotal").value = precioTotal.toFixed(2);
}


function grabarFranqueoEnvioEspecial()
{
	if(document.getElementById("fecha").value=="")
	{
		alert("Introducir una fecha");
		document.getElementById("fecha").focus();
	}
	else if(document.getElementById("gramos").value=="")
	{
		alert("Introducir los gramos");
		document.getElementById("gramos").focus();
	}
	/*else if(document.getElementById("ot").value=="")
	{
		alert("Introducir los ot");
		document.getElementById("ot").focus();
	}*/
	else if(document.getElementById("totalEnvios").value=="")
	{
		alert("Introducir el numero de envios");
		document.getElementById("totalEnvios").focus();
	}
	else if(document.getElementById("precioUnidad").value=="")
	{
		alert("Introducir el numero de unidad");
		document.getElementById("precioUnidad").focus();
	}
	else
	{		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGrabarFranqueoEnvioEspecial;
			peticionUnica1.open("POST","ajax/crearFranqueoTipo.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string =consultaGrabarFranqueoEnvioEspecial();
			peticionUnica1.send(query_string);
		}
	}
}

function consultaGrabarFranqueoEnvioEspecial()
{	
	var consulta = "accion=cargarFranqueoTipo";	
	

	var datos = {
		"idCliente": document.getElementById("listadoNombreFranqueo").value,
		"fecha": document.getElementById("fecha").value,		
		"gramos": document.getElementById("gramos").value,
		"ot": document.getElementById("ot").value,
		"unidades": document.getElementById("totalEnvios").value,
		"importe": document.getElementById("precioTotal").value,
		"tipo": "1",
		"comprobado": 0,
		"txt": 0
	};
	
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));
	
	return consulta;	
}

function mostrarGrabarFranqueoEnvioEspecial()
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
				/*document.getElementById("gramos").value="";
				document.getElementById("ot").value="";
				document.getElementById("numEnvios").value="";
				document.getElementById("precioUnidad").value="";
				document.getElementById("precioTotal").value=0;*/
			}
			peticionUnica1=null;
			rellenarHistoricoFranqueoEnviosEspeciales();
		}
	}
}


function eliminarRegistroFranqueoEspecial(id) //js_12
{	
	if (confirm("¿Eliminar la id: "+id+"?"))
	{
	 	eliminarRegistroFranqueoEspecial2(id);
	} 	
}


function eliminarRegistroFranqueoEspecial2(id) //js_12
{		
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarRegistroFranqueoEspecial2;
		peticionUnica1.open("POST","ajax/eliminarRegistroFranqueoEspecial.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarRegistroFranqueoEspecial2(id);
		peticionUnica1.send(query_string);
	}	
}

function consultaEliminarRegistroFranqueoEspecial2(id)
{	
	var consulta = "accion=eliminarRegistroFranqueoEspecial2";

	var filtros = {
		id: id
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	return consulta;	
}

function mostrarEliminarRegistroFranqueoEspecial2()
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
				rellenarHistoricoFranqueoEnviosEspeciales();
			}
			peticionUnica1=null;
		}
	}						
}


function cargarListadoNombreFranqueo(idInput)
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoNombreFranqueo;
		peticionUnica1.open("POST","ajax/cargarClientes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoNombreFranqueo();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoNombreFranqueo()
{	
	var consulta = "accion=cargarClientes";
	
	var campos = [
		'codigo',
		'nombre_franqueo'		
	];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtros = {
    	activo: 1,
		autorizadoFranqueo: 1

	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros)); 
	
	var order = [
    	{ campo: 'nombre_franqueo', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarListadoNombreFranqueo()
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
				}
			}						
			
			peticionUnica1=null;			
		}
	}						
}



