var peticionUnica1 = null;



function subirImagenAlmacen() //js_pdaProduccion
{	
	if (document.getElementById("imagen_Almacen").value != "")
	{
		
		var inputFileImage;
		var file;
		var data;		
		
		inputFileImage = document.getElementById("imagen_Almacen");

		file = inputFileImage.files[0];

		data = new FormData();

		data.append('archivo',file);

		data.append('accion',"subir");
		data.append('tipo',"arranque");
		
		data.append('ruta',"../../");
		peticionUnica1=crearComunicacion(peticionUnica1);
		//peticion5=new XMLHttpRequest();

		peticionUnica1.onreadystatechange = mostrarSubirImagenAlmacen;
		peticionUnica1.open("POST","ajax/subirImagenAlmacen.php",false);
		//peticion27.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		peticionUnica1.send(data);
		
		
		
		document.getElementById("imagen_Almacen").value = "";
		
		
	}
	else
	{
		alert("Seleccionar una Imagen");
	}
}

function mostrarSubirImagenAlmacen()
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
				cargarListadoPreEntrada();
			}
			peticionUnica1=null;
		}
	}
}




function cargarListadoPreEntrada()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoPreEntrada;
		peticionUnica1.open("POST","ajax/cargarListadoPreEntrada.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoPreEntrada();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarListadoPreEntrada()
{	
	var consulta = "accion=cargarListadoPreEntrada";
	//consulta += "&ot=" + document.getElementById("buscarOt").value;	
	return consulta;	
}

function mostrarCargarListadoPreEntrada()
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
				document.getElementById("listadoPreEntradas").innerHTML = peticionUnica1.responseText;				
			}
		}
	}						
}


function eliminarFotoAlmacenPreEntrada(nombreImagen)
{
	if (confirm('¿Eliminar?')) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);

		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarEliminarFotoAlmacenPreEntrada;
			peticionUnica1.open("POST","ajax/eliminarImagenAlmacen.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaEliminarFotoAlmacenPreEntrada(nombreImagen);
			peticionUnica1.send(query_string);						
		}
	}
}

function consultaEliminarFotoAlmacenPreEntrada(nombreImagen)
{	
	var consulta = "accion=eliminarFotoAlmacenPreEntrada";
	consulta += "&nombreImagen=" + nombreImagen;	
	return consulta;	
}

function mostrarEliminarFotoAlmacenPreEntrada()
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
				cargarListadoPreEntrada();				
			}
		}
	}						
}



