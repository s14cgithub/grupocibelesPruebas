var peticionUnica1 = null;

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
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else
			{
				//alert(peticionUnica1.responseText);
				
				
				var datos = peticionUnica1.responseText.split(".txt");
				
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




