var peticionUnica1 = null;

function cargarInformePorAnio()
{
	if (document.getElementById("buscarAnio").trim != "")
	{
		document.getElementById("InformeAnio").innerHTML = document.getElementById("buscarAnio").value;
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarInformePorAnio;
			peticionUnica1.open("POST","ajax/produccion_verInformePorMediaProceso.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarInformePorAnio();
			peticionUnica1.send(query_string);						
		}
	}
	else
	{
		alert("Introducir una ot");
	}
}

function consultaCargarInformePorAnio()
{	
	var consulta = "accion=verInformePorAnio";
	consulta += "&anio=" + document.getElementById("buscarAnio").value;	
	return consulta;	
}

function mostrarCargarInformePorAnio()
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
				var contenido = '<thead class="thead-dark"><tr><th class="thead-dark">Proceso</th><th class="thead-dark">Media</th></tr></thead>';
				contenido += '<tbody>';
				
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				if (datos.length<=0)
				{
					//alert("No hay ningun registro");
					contenido += '<tr><td>No hay registros</td><td>No hay registros</td></tr>';
				}
				else
				{
					var contador = 0;
				
					while  (contador<datos.length)
					{
						var contraste=(contador%2)?'class="contraste1"':'class="contraste2"';
						//var contraste = "";
						
						contenido += '<tr><td '+contraste+'>'+ datos[contador]["concepto"]+'</td><td '+contraste+'>'+ datos[contador]["media"]+'</td></tr>';
						contador++;

					}

					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido;				
			}
		}
	}						
}