var peticionUnica1 = null;

function crearFacturasMensuales() //js_facturasEspecialesAfacturas				
{	
	if (confirm("¿Crear Facturas?")) 
	{
		peticionUnica1=crearComunicacion(peticionUnica1);
							
		if(peticionUnica1)
		{							
			peticionUnica1.onreadystatechange = mostrarCrearFacturasMensuales;
			peticionUnica1.open("POST","ajax/insertarFacturasMensuales.php",false);
			peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
			var query_string = consultaCrearFacturasMensuales();
			peticionUnica1.send(query_string);						
		}
	} 
}

function consultaCrearFacturasMensuales()
{	
	var consulta = "accion=crearFacturasMensuales";
	consulta += "&fechaInicio=" + document.getElementById("fechaInicio").innerHTML;
	consulta += "&fechaFin=" + document.getElementById("fechaFin").innerHTML;
	consulta +="&fechaFac=" + document.getElementById("fechaFacturacion").innerHTML;
	
	return consulta;	
}


function mostrarCrearFacturasMensuales()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			//let respuesta = peticionUnica1.responseText.trim();
			//console.log(respuesta);
			/*
			if (peticionUnica1.responseText.trim().toLowerCase().includes("error"))
			{
				alert(peticionUnica1.responseText);
			}
			else
			{				
				
			}
*/
			limpiarFacturasEspeciales2();
			peticionUnica1=null;
		}
	}						
}

