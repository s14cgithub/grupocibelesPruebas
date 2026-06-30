var peticionUnica1 = null;

////////////////////////////////////////////////////////////////////

function subirArchivoGenerico() 
{
		
	var inputFileImage;
	var file;
	var data;

	if (document.getElementById("elArchivo").value != "")
	{
		inputFileImage = document.getElementById("elArchivo");

		file = inputFileImage.files[0];

		data = new FormData();

		data.append('archivo',file);

		data.append('accion',"subir");
		data.append('tipo',"excelIndra");		
		data.append('ruta',"../");
		peticionUnica1=crearComunicacion(peticionUnica1);
		//peticion5=new XMLHttpRequest();

		peticionUnica1.onreadystatechange = mostrarSubirArchivo;
		peticionUnica1.open("POST","ajax/subirExcelIndra.php",false);
		

		peticionUnica1.send(data);
	}
	else
	{
		alert("Seleccionar Archivo");
	}
	
}

function mostrarSubirArchivo()
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
				
				var datos = peticionUnica1.responseText.split('Nombre:');
				
				
				document.getElementById("descargarArchivoIndra").setAttribute('href', datos[1].trim());
				document.getElementById("descargarArchivoIndra").setAttribute('download',datos[1].trim().substring(datos[1].trim().lastIndexOf('/')+1));
				
				document.getElementById("descargarArchivoIndra").click();
				
				
				
			}
			peticionUnica1=null;
		}
	}
}


////////////////////////////////////////////////////////////////////

function leerArchivo() 
{
	if (document.getElementById("elArchivo").value != "")
	{
		var inputFileText = document.getElementById("elArchivo");
		var contador=0;
		var producto="";
		
		if (document.getElementById("certificados").checked==true)
		{
			producto="certificado";
		}
		else
		{
			producto="notificacion";
		}
		
		while (contador<inputFileText.files.length)
		{
			var file = inputFileText.files[contador];
		
			var data = new FormData();	

			data.append('archivo',file);
			data.append('accion',"leer");
			data.append('producto',producto);
			data.append('fechaDeposito',document.getElementById("fechaDeposito").value);
			peticionUnica1=crearComunicacion(peticionUnica1);
			peticionUnica1.onreadystatechange = mostrarLeerArchivo;
			peticionUnica1.open("POST","ajax/leerArchivosImportacionCertNot.php",false);
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
			var res = JSON.parse(peticionUnica1.responseText);
			
			if (res.error!="")
			{
				alert(res.error);
			}
			else
			{		
				var datos = res.datos.split(".txt");
				
				
				//var datos = peticionUnica1.responseText.split(".txt");
				
				var rutaArchivo=datos[0]+".txt";
				
				if (datos.length==1)
				{
					alert(peticionUnica1.responseText);
				}
				else
				{
					document.getElementById("generarTxtCertificados").setAttribute('href',rutaArchivo);
					document.getElementById("generarTxtCertificados").setAttribute('download',rutaArchivo.trim().substring(rutaArchivo.trim().lastIndexOf('/')+1));

					document.getElementById("generarTxtCertificados").click();
					
					alert(datos[1]);
				}
				
				
				
				peticionUnica1="";
			}
			
			//document.getElementById("elArchivo").value="";
		}
	}						
}




