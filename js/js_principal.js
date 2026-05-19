var peticionUnica1 = null;
//NUEVA VERSION


//CODIGO ANTERIOR REVISADO
//EL CODIGO SEGUIENTE ESTA SIN REVISAR

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
		
		data.append('ruta',"../");
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
				//alert(peticion11.responseText);
			}
			peticionUnica1=null;
		}
	}
}



function cargarImagenes()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarImagenes;
		peticionUnica1.open("POST","ajax/cargarImagenesAlmacen.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarImagenes();
		peticionUnica1.send(query_string);						
	}
}

function consultaCargarImagenes()
{	
	var consulta = "accion=cargarImagenes";
	//consulta += "&ot=" + document.getElementById("buscarOt").value;	
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
				document.getElementById("gallery").innerHTML = peticionUnica1.responseText;				
			}
		}
	}						
}



function generarTxtSIDI() 
{
	
	peticionUnica1=crearComunicacion(peticionUnica1);
						
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarGenerarTxtSIDI;
		peticionUnica1.open("POST","ajax/generarTxtOtSidi.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGenerarTxtSIDI();
		peticionUnica1.send(query_string);						
	}

		
}




function consultaGenerarTxtSIDI()
{	
	var consulta = "accion=generarTxtSIDI";
	
	consulta+="&otSidiModal="+document.getElementById("otSidiModal").value;
	
	return consulta;	
}


function mostrarGenerarTxtSIDI()
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
				document.getElementById("otSidiTXT").click();				
			}				
		}
	}						
}



function subirOtSidiDesdeExcel() 
{
		
	var inputFileImage;
	var file;
	var data;

	if (document.getElementById("elArchivoExcelOtSidi").value != "")
	{
		inputFileImage = document.getElementById("elArchivoExcelOtSidi");

		file = inputFileImage.files[0];

		data = new FormData();

		data.append('archivo',file);

		data.append('accion',"subir");
				
		data.append('ruta',"../");
		peticionUnica1=crearComunicacion(peticionUnica1);
		

		peticionUnica1.onreadystatechange = mostrarSubirOtSidiDesdeExcel;
		peticionUnica1.open("POST","ajax/importacionDatosExcel_OtSidi.php",false);
		

		peticionUnica1.send(data);
	}
	else
	{
		alert("Seleccionar Archivo");
	}
	
}

function mostrarSubirOtSidiDesdeExcel()
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
				//alert("Proceso Finalizado");
				
				document.getElementById("elArchivoExcelOtSidi").value = null;
			}
			peticionUnica1=null;
		}
	}
}



function actualizarOtSidiACibeles() 
{	
	peticionUnica1=crearComunicacion(peticionUnica1);
						
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarActualizarOtSidiACibeles;
		peticionUnica1.open("POST","ajax/actualizarOtSidiEnCibeles.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaActualizarOtSidiACibeles();
		peticionUnica1.send(query_string);						
	}
}

function consultaActualizarOtSidiACibeles()
{	
	var consulta = "accion=actualizarOtSidiACibeles";		
	
	return consulta;	
}


function mostrarActualizarOtSidiACibeles()
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
			}				
		}
	}						
}

