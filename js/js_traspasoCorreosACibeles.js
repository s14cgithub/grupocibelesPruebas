var peticionUnica1 = null;

function leerArchivo() //js_traspasoCorreosACibeles
{
	
	if (document.getElementById("elArchivo").value != "")
	{
		var inputFileText = document.getElementById("elArchivo");
		var contador=0;
		var valorDuplicado="";
		
		if (document.getElementById("reemplazar").checked==true)
		{
			valorDuplicado="reemplazar";
		}
		else
		{
			valorDuplicado="ignorar";
		}
		
		while (contador<inputFileText.files.length)
		{
			var file = inputFileText.files[contador];
		
			var data = new FormData();	

			data.append('archivo',file);
			data.append('accion',"leer");
			data.append('ignorar',valorDuplicado);//duplicados vs ignorar
			peticionUnica1=crearComunicacion(peticionUnica1);
			peticionUnica1.onreadystatechange = mostrarLeerArchivo;
			peticionUnica1.open("POST","ajax/leerArchivoCorreos.php",false);
			peticionUnica1.send(data);
			contador++;
		}
	}
	else
	{
		alert("Seleccionar un archivo");
	}
}

function mostrarLeerArchivo()
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
				peticionUnica1="";
			}
			
			document.getElementById("elArchivo").value="";
		}
	}						
}


function subirDatosCorreosMensualesDesdeExcel() 
{
	
	var inputFileImage;
	var file;
	var data;

	if (document.getElementById("elArchivoExcelMensual").value != "")
	{
		//alert("hola");	
		inputFileImage = document.getElementById("elArchivoExcelMensual");

		file = inputFileImage.files[0];

		data = new FormData();

		data.append('archivo',file);

		data.append('accion',"subir");
		//data.append('idCliente',document.getElementById("clientesImportacionMensualesModal").value);			
		data.append('ruta',"../");
		peticionUnica1=crearComunicacion(peticionUnica1);
		

		peticionUnica1.onreadystatechange = mostrarSubirDatosMensualesDesdeExcel;
		peticionUnica1.open("POST","ajax/traspasoCorreosCibelesExcel.php",false);
		

		peticionUnica1.send(data);
	}
	else
	{
		alert("Seleccionar Archivo");
	}
	
}


function mostrarSubirDatosMensualesDesdeExcel()
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
				
				document.getElementById("elArchivoExcelMensual").value = null;

				/*var datos = peticionUnica1.responseText.split('Nombre:');
				
				
				document.getElementById("descargarArchivoIndra").setAttribute('href', datos[1].trim());
				document.getElementById("descargarArchivoIndra").setAttribute('download',datos[1].trim().substring(datos[1].trim().lastIndexOf('/')+1));
				
				document.getElementById("descargarArchivoIndra").click();*/
				
				
				
				
			}
			peticionUnica1=null;
		}
	}
}



function subirDatosCorreosMensualesDesdeExcelAlbaran() 
{
	
	var inputFileImage;
	var file;
	var data;

	if (document.getElementById("elArchivoExcelMensualAlbaran").value != "")
	{
		//alert("hola");	
		inputFileImage = document.getElementById("elArchivoExcelMensualAlbaran");

		file = inputFileImage.files[0];

		data = new FormData();

		data.append('archivo',file);

		data.append('accion',"subir");
		//data.append('idCliente',document.getElementById("clientesImportacionMensualesModal").value);			
		data.append('ruta',"../");
		peticionUnica1=crearComunicacion(peticionUnica1);
		

		peticionUnica1.onreadystatechange = mostrarSubirDatosCorreosMensualesDesdeExcelAlbaran;
		peticionUnica1.open("POST","ajax/traspasoCorreosCibelesExcelAlbaran.php",false);
		

		peticionUnica1.send(data);
	}
	else
	{
		alert("Seleccionar Archivo");
	}
	
}


function mostrarSubirDatosCorreosMensualesDesdeExcelAlbaran()
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
				
				document.getElementById("elArchivoExcelMensualAlbaran").value = null;

				/*var datos = peticionUnica1.responseText.split('Nombre:');
				
				
				document.getElementById("descargarArchivoIndra").setAttribute('href', datos[1].trim());
				document.getElementById("descargarArchivoIndra").setAttribute('download',datos[1].trim().substring(datos[1].trim().lastIndexOf('/')+1));
				
				document.getElementById("descargarArchivoIndra").click();*/
				
				
				
				
			}
			peticionUnica1=null;
		}
	}
}




