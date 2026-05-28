var peticionUnica1 = null;
var laCondicion = "";

var permisosSoloLectura = null;


function activar_Tamanio() 
{
	document.getElementById("pestania_tamanos").className = "nav-link active";
	document.getElementById("pestania_conversionTamano").className = "nav-link";
	document.getElementById("pestania_Tipos").className = "nav-link";
	document.getElementById("pestania_Acabados").className = "nav-link";
	document.getElementById("pestania_Gramaje").className = "nav-link";

	cargarTamaniosListado("listado");
	
}

function activar_ConversionTamano() 
{
	document.getElementById("pestania_tamanos").className = "nav-link";
	document.getElementById("pestania_conversionTamano").className = "nav-link active";
	document.getElementById("pestania_Tipos").className = "nav-link";
	document.getElementById("pestania_Acabados").className = "nav-link";
	document.getElementById("pestania_Gramaje").className = "nav-link";	

	cargarTamanioConversor("listado");
}

function activar_Tipos() 
{
	document.getElementById("pestania_tamanos").className = "nav-link";
	document.getElementById("pestania_conversionTamano").className = "nav-link";
	document.getElementById("pestania_Tipos").className = "nav-link active";
	document.getElementById("pestania_Acabados").className = "nav-link";
	document.getElementById("pestania_Gramaje").className = "nav-link";	

	cargarTipoListado("listado");
}
function activar_Acabados() 
{
	document.getElementById("pestania_tamanos").className = "nav-link";
	document.getElementById("pestania_conversionTamano").className = "nav-link";
	document.getElementById("pestania_Tipos").className = "nav-link";
	document.getElementById("pestania_Acabados").className = "nav-link active";
	document.getElementById("pestania_Gramaje").className = "nav-link";	

	cargarAcabadoListado("listado");
}
function activar_Gramaje() 
{
	document.getElementById("pestania_tamanos").className = "nav-link";
	document.getElementById("pestania_conversionTamano").className = "nav-link";
	document.getElementById("pestania_Tipos").className = "nav-link";
	document.getElementById("pestania_Acabados").className = "nav-link";
	document.getElementById("pestania_Gramaje").className = "nav-link active";	

	cargarGramajeListado("listado");
}



function cargarTamaniosListado(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTamaniosListado;
		peticionUnica1.open("POST","ajax/cargarTamaniosPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTamaniosListado();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarTamaniosListado()
{	
	var consulta = "accion=cargarTamaniosPapel";	
	return consulta;	
}

function mostrarCargarTamaniosListado()
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
				
				var contador = 0;	
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important;"><h1>TAMAÑO</h1></tr>';

				contenido += '<tr><td align="center" colspan="4" style="border:0px !important; vertical-align: center !important;">Nuevo Tamaño: <input type="text" id="nuevoTamanio"></input> <img src="imagenes/crear.png" style="width:20px !important; vertical-align: center !important;"  onclick="insertarTamanio()"></img></td></tr>';


				contenido += '<br><tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">ID</th>';					
				contenido += '<th>TAMAÑO</th>';
				contenido += '<th></th>';
				contenido += '<th></th>';					

				contenido += '</tr>';
				
				while  (contador<datos.length)
				{  
					contenido += "<tr>";
					contenido += '<td><label>' + datos[contador]["id"] + '</label></td>';
					contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_tamanio" value="' + datos[contador]["tamano"] + '"></input></td>';
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificarTamanio" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarTamanio('+datos[contador]["id"]+')"></td>';	
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminarTamanio" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarTamanio('+datos[contador]["id"]+')"></td>';
					contenido += "</tr>";
					
					contador++;	
				}
				

				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


function insertarTamanio() 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarTamanio;
		peticionUnica1.open("POST","ajax/crearTamanioPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarTamanio();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarTamanio()
{	
	var consulta = "accion=insertarTamanio";

	var datos = {tamano: document.getElementById("nuevoTamanio").value}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));	


	return consulta;	
}

function mostrarInsertarTamanio()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="" || res.ok==false )
			{
				alert(res.error);				
			}			
			else
			{
				alert('Tamaño Insertado');
				cargarTamaniosListado("listado");						
			}

			peticionUnica1=null;			
		}
	}						
}



function modificarTamanio(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarTamanio;
		peticionUnica1.open("POST","ajax/modificarTamanioPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarTamanio(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarTamanio(id)
{	
	var consulta = "accion=modificarTamanioPapel";

	var datos = {		
		tamano: document.getElementById(id + "_tamanio").value		
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	


	return consulta;	
}

function mostrarModificarTamanio()
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
				alert("Tamaño modificado");
				cargarTamaniosListado("listado");
			}			
			peticionUnica1=null;
		}
	}
}


function eliminarTamanio(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarTamanio;
		peticionUnica1.open("POST","ajax/eliminarTamanioPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarTamanio(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarTamanio(id)
{	
	var consulta = "accion=borrarTamanioPapel";

	var filtros = {
		id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarEliminarTamanio()
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
				alert("Tamaño Borrado");
				cargarTamaniosListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}


function cargarTipoListado(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTipoListado;
		peticionUnica1.open("POST","ajax/cargarTiposPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTipoListado();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarTipoListado()
{	
	var consulta = "accion=cargarTipos";	
	return consulta;	
}

function mostrarCargarTipoListado()
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
				
				var contador = 0;	
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important;"><h1>TIPO</h1></tr>';
				
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important; vertical-align: center !important;">Nuevo Tipo: <input type="text" id="nuevoTipo"></input> <img src="imagenes/crear.png" style="width:20px !important; vertical-align: center !important;"  onclick="insertarTipo()"></img></td></tr>';
				
				contenido += '<br><tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">ID</th>';					
				contenido += '<th>TIPO</th>';
				contenido += '<th></th>';
				contenido += '<th></th>';					

				contenido += '</tr>';
				
				while  (contador<datos.length)
				{  
					contenido += "<tr>";
					contenido += '<td><label>' + datos[contador]["id"] + '</label></td>';
					contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_tipo" value="' + datos[contador]["tipo"] + '"></input></td>';
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificarTipo" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarTipo('+datos[contador]["id"]+')"></td>';	
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminarTipo" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarTipo('+datos[contador]["id"]+')"></td>';
					contenido += "</tr>";
					
					contador++;	
				}
				

				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}



function modificarTipo(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarTipo;
		peticionUnica1.open("POST","ajax/modificarTipoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarTipo(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarTipo(id)
{	
	var consulta = "accion=modificarTipoPapel";

	var datos = {		
		tipo: document.getElementById(id + "_tipo").value	
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));	

	return consulta;	
}

function mostrarModificarTipo()
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
				alert("Tipo modificado");
				cargarTipoListado("listado");						
			}	
			peticionUnica1=null;			
		}
	}						
}



function cargarAcabadoListado(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarAcabadoListado;
		peticionUnica1.open("POST","ajax/cargarAcabadoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarAcabadoListado();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarAcabadoListado()
{	
	var consulta = "accion=cargarAcabado";	
	return consulta;	
}

function mostrarCargarAcabadoListado()
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
				
				var contador = 0;	
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important;"><h1>ACABADO</h1></tr>';
				
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important; vertical-align: center !important;">Nuevo Acabado: <input type="text" id="nuevoAcabado"></input> <img src="imagenes/crear.png" style="width:20px !important; vertical-align: center !important;"  onclick="insertarAcabado()"></img></td></tr>';

				contenido += '<br><tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">ID</th>';					
				contenido += '<th>ACABADO</th>';
				contenido += '<th></th>';
				contenido += '<th></th>';					

				contenido += '</tr>';
				
				while  (contador<datos.length)
				{  
					contenido += "<tr>";
					contenido += '<td><label>' + datos[contador]["id"] + '</label></td>';
					contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_acabado" value="' + datos[contador]["acabado"] + '"></input></td>';
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificarAcabado" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarAcabado('+datos[contador]["id"]+')"></td>';	
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminarAcabado" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarAcabado('+datos[contador]["id"]+')"></td>';
					contenido += "</tr>";
					
					contador++;	
				}
				

				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


function modificarAcabado(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarAcabado;
		peticionUnica1.open("POST","ajax/modificarAcabadoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarAcabado(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarAcabado(id)
{	
	var consulta = "accion=modificarAcabadoPapel";

	var datos = {		
		acabado: document.getElementById(id + "_acabado").value		
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarModificarAcabado()
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
				alert("Acabado modificado");
				cargarAcabadoListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}


function cargarGramajeListado(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarGramajeListado;
		peticionUnica1.open("POST","ajax/cargarGramajePapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarGramajeListado();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarGramajeListado()
{	
	var consulta = "accion=cargarGramaje";	
	return consulta;	
}

function mostrarCargarGramajeListado()
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
				
				var contador = 0;	
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important;"><h1>GRAMAJE</h1></tr>';
				
				contenido += '<tr><td align="center" colspan="4" style="border:0px !important; vertical-align: center !important;">Nuevo Gramaje: <input type="text" id="nuevoGramaje"></input> <img src="imagenes/crear.png" style="width:20px !important; vertical-align: center !important;"  onclick="insertarGramaje()"></img></td></tr>';
				
				contenido += '<br><tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">ID</th>';					
				contenido += '<th>GRAMAJE</th>';
				contenido += '<th></th>';
				contenido += '<th></th>';					

				contenido += '</tr>';
				
				while  (contador<datos.length)
				{  
					contenido += "<tr>";
					contenido += '<td><label>' + datos[contador]["id"] + '</label></td>';
					contenido += '<td><input type="text" id="'+datos[contador]["id"]+'_gramaje" value="' + datos[contador]["gramaje"] + '"></input></td>';
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificarGramaje" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarGramaje('+datos[contador]["id"]+')"></td>';	
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_eliminarGramaje" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarGramaje('+datos[contador]["id"]+')"></td>';
					contenido += "</tr>";
					
					contador++;	
				}
				

				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


function modificarGramaje(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarGramaje;
		peticionUnica1.open("POST","ajax/modificarGramajePapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarGramaje(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarGramaje(id)
{	
	var consulta = "accion=modificarGramajePapel";
	
	var datos = {		
		gramaje: document.getElementById(id + "_gramaje").value		
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));		
	
	return consulta;	
}

function mostrarModificarGramaje()
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
				alert("Gramaje modificado");
				cargarGramajeListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}



function eliminarTipo(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarTipo;
		peticionUnica1.open("POST","ajax/eliminarTipoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarTipo(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarTipo(id)
{	
	var consulta = "accion=borrarTipoPapel";
	
	var filtros = {
		id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarEliminarTipo()
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
				alert("Tipo Borrado");
				cargarTipoListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}


function eliminarAcabado(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarAcabado;
		peticionUnica1.open("POST","ajax/eliminarAcabadoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarAcabado(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarAcabado(id)
{	
	var consulta = "accion=borrarAcabadoPapel";
	
	var filtros = {
		id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarEliminarAcabado()
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
				alert("Acabado Borrado");
				cargarAcabadoListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}


function eliminarGramaje(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarGramaje;
		peticionUnica1.open("POST","ajax/eliminarGramajePapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarGramaje(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarGramaje(id)
{	
	var consulta = "accion=borrarGramajePapel";
	
	var filtros = {
		id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarEliminarGramaje()
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
				alert("Gramaje Borrado");
				cargarGramajeListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}

function insertarTipo() 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarTipo;
		peticionUnica1.open("POST","ajax/crearTipoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarTipo();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarTipo()
{	
	var consulta = "accion=insertarTipo";

	var datos = {tipo: document.getElementById("nuevoTipo").value}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));	
	
	return consulta;	
}

function mostrarInsertarTipo()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="" || res.ok==false )
			{
				alert(res.error);				
			}			
			else
			{
				alert('Tipo Insertado');
				cargarTipoListado("listado");						
			}			
			peticionUnica1=null;			
		}
	}						
}

function insertarAcabado() 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarAcabado;
		peticionUnica1.open("POST","ajax/crearAcabadoPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarAcabado();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarAcabado()
{	
	var consulta = "accion=insertarAcabado";

	var datos = {acabado: document.getElementById("nuevoAcabado").value}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));	


	return consulta;	
}

function mostrarInsertarAcabado()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="" || res.ok==false )
			{
				alert(res.error);				
			}			
			else
			{
				alert('Acabado Insertado');
				cargarAcabadoListado("listado");						
			}			
			peticionUnica1=null;			
		}
	}						
}


function insertarGramaje() 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarGramaje;
		peticionUnica1.open("POST","ajax/crearGramajePapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarGramaje();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarGramaje()
{	
	var consulta = "accion=insertarGramaje";

	var datos = {gramaje: document.getElementById("nuevoGramaje").value}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));	

	return consulta;	
}

function mostrarInsertarGramaje()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="" || res.ok==false )
			{
				alert(res.error);				
			}			
			else
			{
				alert('Gramaje Insertado');
				cargarGramajeListado("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}


function cargarTamanioConversor(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarTamanioConversor;
		peticionUnica1.open("POST","ajax/cargarTamaniosConversorPapel.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarTamanioConversor();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarTamanioConversor()
{	
	var consulta = "accion=cargarTamanioConversor";	
	return consulta;	
}

function mostrarCargarTamanioConversor()
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
				
				var contador = 0;
				contenido += '<tr><td align="center" colspan="6" style="border:0px !important;"><h1>TAMAÑO CONVERSOR</h1></tr>';

				contenido += '<tr><td align="center" colspan="6" style="border:0px !important; vertical-align: center !important;">Nuevo Tamaño Inicio: <select id="nuevoTamanioInicio"></select>&nbsp;&nbsp;&nbsp;&nbsp;Nuevo Tamaño Final: <select id="nuevoTamanioFinal"></select>&nbsp;&nbsp;&nbsp;&nbsp;Valor <input type="text" id="valorTamanioConversor"></input> <img src="imagenes/crear.png" style="width:20px !important; vertical-align: center !important;"  onclick="insertarTamanioConversor()"></img></td></tr>';


				contenido += '<br><tr class="centrarTexto tablaCabeceraColor">';
				contenido += '<th align="center">ID</th>';					
				contenido += '<th>TAMAÑO INICIO</th>';
				contenido += '<th>TAMAÑO FINAL</th>';
				contenido += '<th style="width:5px;">VALOR</th>';
				contenido += '<th></th>';
				contenido += '<th></th>';					

				contenido += '</tr>';
				
				while  (contador<datos.length)
				{  
					contenido += "<tr>";
					contenido += '<td><label>' + datos[contador]["id"] + '</label></td>';
					contenido += '<td style="text-align: center;"><select id="'+datos[contador]["id"]+'_tamanioInicio"></select></td>';
					contenido += '<td style="text-align: center;"><select id="'+datos[contador]["id"]+'_tamanioFinal" ></select></td>';
					contenido += '<td style="width:5px;"><input type="text" id="'+datos[contador]["id"]+'_valor" value="' + datos[contador]["valor"] + '"></input></td>';
					contenido += '<td style="text-align: center;"><input type="image" id="'+datos[contador]["id"]+'_modificarTamanio" value="" src="imagenes/modificar.png" style="width:20px;" onclick="modificarTamanioConversor('+datos[contador]["id"]+')"></td>';	
					contenido += '<td style="text-align: center;"><input type="image" id="'+datos[contador]["id"]+'_eliminarTamanio" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarTamanioConversor('+datos[contador]["id"]+')"></td>';
					contenido += "</tr>";
					
					contador++;	
				}
				

				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";


				contador=0;
				while  (contador<datos.length)
				{
					cargarTamanios(datos[contador]["id"]+'_tamanioInicio');
					cargarTamanios(datos[contador]["id"]+'_tamanioFinal');
					contador++;	
				}

				cargarTamanios("nuevoTamanioInicio");
				cargarTamanios("nuevoTamanioFinal");

				contador=0;
				while  (contador<datos.length)
				{
					document.getElementById(datos[contador]["id"]+'_tamanioInicio').value = datos[contador]["idTamanioInicio"];
					document.getElementById(datos[contador]["id"]+'_tamanioFinal').value = datos[contador]["idTamanioFinal"];
					contador++;	
				}
				
			}
			peticionUnica1=null;
		}
	}						
}




function insertarTamanioConversor() 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarInsertarTamanioConversor;
		peticionUnica1.open("POST","ajax/crearTamanioConversor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaInsertarTamanioConversor();
		peticionUnica1.send(query_string);
	}
}

function consultaInsertarTamanioConversor()
{	
	var consulta = "accion=insertarTamanioConversor";

	var datos = {
		idTamanioInicio: document.getElementById("nuevoTamanioInicio").value,
		idTamanioFinal: document.getElementById("nuevoTamanioFinal").value,
		valor: document.getElementById("valorTamanioConversor").value
	}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));	
	
	return consulta;	
}

function mostrarInsertarTamanioConversor()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var res = JSON.parse(peticionUnica1.responseText);
			if (res.error!="" || res.ok==false )
			{
				alert(res.error);				
			}			
			else
			{
				alert('Tamaño Conversor Insertado');
				cargarTamanioConversor("listado");						
			}

			peticionUnica1=null;			
		}
	}						
}




function modificarTamanioConversor(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarTamanioConversor;
		peticionUnica1.open("POST","ajax/modificarTamanioConversor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarTamanioConversor(id);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarTamanioConversor(id)
{	
	var consulta = "accion=modificarTamanioConversor";
	
	
	var datos = {		
		idTamanioInicio: document.getElementById(id + "_tamanioInicio").value,
		idTamanioFinal: document.getElementById(id + "_tamanioFinal").value,
		valor: document.getElementById(id + "_valor").value
	};
	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var filtros = {
    	id: id
	};	
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarModificarTamanioConversor()
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
				alert("Tamaño Conversor modificado");
				cargarTamanioConversor("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}





function eliminarTamanioConversor(id) 
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarEliminarTamanioConversor;
		peticionUnica1.open("POST","ajax/eliminarTamanioConversor.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaEliminarTamanioConversor(id);
		peticionUnica1.send(query_string);
	}
}

function consultaEliminarTamanioConversor(id)
{	
	var consulta = "accion=eliminarTamanioConversor";

	var filtros = {
		id: id
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	return consulta;	
}

function mostrarEliminarTamanioConversor()
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
				alert("Tamaño Conversor Borrado");
				cargarTamanioConversor("listado");
						
			}			
			peticionUnica1=null;			
		}
	}						
}




