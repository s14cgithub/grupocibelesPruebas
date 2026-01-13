var peticionUnica1 = null;

function cargarInformePorOt()
{
	if (document.getElementById("buscarOt").trim != "")
	{
		document.getElementById("InformeOt").innerHTML = document.getElementById("buscarOt").value;
		
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCargarInformePorOt;
			peticionUnica1.open("POST","ajax/produccion_verInformePorOt.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCargarInformePorOt();
			peticionUnica1.send(query_string);						
		}
	}
	else
	{
		alert("Introducir una ot");
	}
}

function consultaCargarInformePorOt()
{	
	var consulta = "accion=verInformePorOt";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarInformePorOt()
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
				//alert(peticion7.responseText);				
				
				var datos = new Array;
				datos = JSON.parse(peticionUnica1.responseText);
				
				var contenido = '';
				
				if (datos.length>0)
				{
					contenido += '<thead class="thead-dark"><tr><th class="thead-dark" colspan="3">'+datos[0]["cliente"]+'</th></tr></thead>';
					contenido += '<thead class="thead-dark"><tr><th class="thead-dark" colspan="3">'+datos[0]["campana"]+'</th></tr></thead>';
				}
				
				contenido += '<thead class="thead-dark"><tr><th class="thead-dark">Proceso</th><th class="thead-dark">Cantidad</th><th class="thead-dark">Horas Realizadas</th></tr></thead>';
				
				
				contenido += '<tbody>';
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
						
						contenido += '<tr><td '+contraste+'><span onclick="cargarInformePorOtDetalle(\''+datos[contador]["codigoBarras"]+'\');">'+ datos[contador]["concepto"]+'</span></td><td '+contraste+'>'+ datos[contador]["cantidad"]+'</td><td '+contraste+'>'+ datos[contador]["horas"]+'</td></tr>';
						contador++;

					}
					
					contenido += '<tr class="sinBorde"><td '+contraste+'></td><td '+contraste+'></td><td '+contraste+'></td></tr>';
				    //contenido += '<tr class="textoNegrita sinBorde"><td '+contraste+' align="right">Total</td><td '+contraste+'>'+ datos[0]["cantidadTotal"]+'</td><td '+contraste+'>'+ datos[0]["horasTotal"]+'</td></tr>';
					contenido += '<tr class="textoNegrita sinBorde"><td '+contraste+' align="right"></td><td '+contraste+'>Total</td><td '+contraste+'>'+ datos[0]["horasTotal"]+'</td></tr>';
					
					
					//contador++;

					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido;
				
				cargarImagenes();
				
			
				
				
				
				
			}
		}
	}						
}


function cargarImagenes()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarImagenes;
		peticionUnica1.open("POST","ajax/cargarImagenes.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarImagenes();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarImagenes()
{	
	var consulta = "accion=cargarImagenes";
	consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarImagenes()
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
				document.getElementById("gallery").innerHTML = peticionUnica1.responseText;
				
				
			}
		}
	}						
}

function cargarDatosImagenes()
{
	document.getElementById("numOtImagen").innerHTML =  document.getElementById("buscarOt").value;
}


function cargarInformePorOtDetalle(codigoBarras)
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarInformePorOtDetalle;
		peticionUnica1.open("POST","ajax/produccion_verInformePorOtDetalle.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarInformePorOtDetalle(codigoBarras);
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarInformePorOtDetalle(codigoBarras)
{	
	var consulta = "accion=verInformePorOtDetalle";		
	consulta += "&codigoBarras=" +  codigoBarras
	return consulta;	
}

function mostrarCargarInformePorOtDetalle()
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
				
				var contenido = '';
				if (datos.length>0)
				{
					contenido = '<thead class="thead-dark"><tr><th class="thead-dark" colspan="8">'+datos[0]["concepto"]+' ('+datos[0]["codigoBarras"]+')</th></tr></thead>';
				}		
				
				contenido += '<thead class="thead-dark"><tr><th class="thead-dark">Empleado</th><th class="thead-dark">Cantidad</th><th class="thead-dark">Horas</th></tr></thead>';
				contenido += '<tbody>';
				
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
												
						contenido += '<tr><td '+contraste+'>'+ datos[contador]["nombreEmpleado"]+'</a></td><td '+contraste+'>'+ datos[contador]["cantidad"]+'</td><td '+contraste+'>'+ datos[contador]["horas"]+'</td></tr>';
						contador++;

					}
					
					contraste = 'class="contraste2"';
					contenido += '<tr class="sinBorde"><td '+contraste+'></td><td '+contraste+'></td><td '+contraste+'></td></tr>';
					contenido += '<tr class="textoNegrita sinBorde"><td '+contraste+' align="right">Total</td><td '+contraste+'>'+datos[0]["cantidadTotal"]+'</td><td '+contraste+'>'+datos[0]["horasTotal"]+'</td></tr>';
					
					

					
				}
				contenido += '</tbody>';								

				document.getElementById("resultadoInforme").innerHTML = contenido; 
				
				
				
				
				
				
				
			}
		}
	}						
}


