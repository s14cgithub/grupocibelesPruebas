var peticionUnica1 = null;

function buscarFactura()
{
	cargarHuecos();
}



function cargarHuecos()
{
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarHuecos;
		peticionUnica1.open("POST","ajax/mostrarAlmacenHuecos.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarHuecos();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarHuecos()
{	
	var consulta = "accion=mostrarAlmacenHuecos";

	var campos = ['id','hueco'];
	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var filtrosOperadores = [];
	var campoAbuscar = document.getElementById("buscarCampo").value;
	var textoAbuscar = document.getElementById("buscarTexto").value;
	if (textoAbuscar != "")
	{
		filtrosOperadores.push({ campo1: campoAbuscar, valor: '%' + textoAbuscar + '%', operador: 'LIKE' });
	}
	consulta += "&filtrosOperadores=" + encodeURIComponent(JSON.stringify(filtrosOperadores));

	var orden = document.getElementById("ordenBuscar").value;
	var desc = document.getElementById("ordenDesc").checked;
	var order = [
		{ campo: orden, dir: desc ? 'DESC' : 'ASC' }
	];
	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));
	
	return consulta;	
}

function mostrarCargarHuecos()
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
				contenido += '<th style="text-align:center;">Hueco</th>';
				while  (contador<datos.length)
				{ 
					contenido += '<tr>';
					contenido += '<td> <input type="text" id="'+datos[contador]["id"]+'_hueco" value="'+datos[contador]["hueco"]+'"></input></td>';	
					contenido += '<td><input type="image" id="'+datos[contador]["id"]+'_modificar" value="" src="imagenes/modificar.png" style="width:15px;"  onclick="modificarHueco('+datos[contador]["id"]+')"></td>';
					
					contenido += '</tr>';
					contador++;	
				}
				
				document.getElementById("historicoHueco").innerHTML = contenido;				
			}
			peticionUnica1=null;
			
		}
	}						
}

function guardarHueco()
{
	if (document.getElementById("nombreHueco").value=="")
	{
		alert("Introducir un Hueco");
		document.getElementById("nombreHueco").focus();
	}
	else
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarGuardarHueco;
			peticionUnica1.open("POST","ajax/insertarAlmacenHueco.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaGuardarHueco();
			peticionUnica1.send(query_string);
		}
	}
	
	
}

function consultaGuardarHueco()
{	
	var consulta = "accion=insertarHueco";	
	consulta += "&hueco="+document.getElementById("nombreHueco").value;

	
	return consulta;	
}

function mostrarGuardarHueco()
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
				document.getElementById("historicoHueco").value = "";	
				document.getElementById("nombreHueco").value = "";
				alert(res.mensaje);
			}
			peticionUnica1=null;
			
			cargarHuecos();
		}
	}						
}


function modificarHueco(id)
{
	if (document.getElementById(id+'_hueco').value=="")
	{
		alert("Introducir un Hueco");
		document.getElementById(id+'_hueco').focus();
	}	
	else if (confirm('¿Modificar Hueco?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarModificarHueco;
			peticionUnica1.open("POST","ajax/modificarAlmacenHueco.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaModificarHueco(id);
			peticionUnica1.send(query_string);
		}
	}
}

function consultaModificarHueco(id)
{	
	var consulta = "accion=modificarHueco";	
	consulta += "&id="+id;
	consulta += "&hueco=" + document.getElementById(id+'_hueco').value;

	
	return consulta;	
}

function mostrarModificarHueco()
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
				alert(res.mensaje);
			}
			peticionUnica1=null;	
			
		}
	}						
}










