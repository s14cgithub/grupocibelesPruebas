var peticionUnica1 = null;
var primeraVez = true;


function subirImagenArranque() //js_pdaProduccion
{	
	if (enviarImagen==true)
	{
		
		var inputFileImage;
		var file;
		var data;
		
		if (document.getElementById("imagen_Arranque").value != "")
		{
			inputFileImage = document.getElementById("imagen_Arranque");

			file = inputFileImage.files[0];

			data = new FormData();

			data.append('archivo',file);
			
			data.append('accion',"subir");
			data.append('tipo',"arranque");
			data.append('codigoBarras',codigoActivo);
			data.append('ruta',"../../");
			peticionUnica1=crearComunicacion(peticionUnica1);
			//peticion5=new XMLHttpRequest();

			peticionUnica1.onreadystatechange = mostrarSubirArchivo;
			peticionUnica1.open("POST","ajax/subirArchivo.php",false);
			//peticion27.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			peticionUnica1.send(data);
		}
		if (document.getElementById("imagen_Calidad").value != "")
		{
			inputFileImage = document.getElementById("imagen_Calidad");

			file = inputFileImage.files[0];

			data = new FormData();

			data.append('archivo',file);
			//data.append('tamanio',tamanio);
			//data.append('idProducto',idProducto);*/
			data.append('accion',"subir");
			data.append('tipo',"calidad");
			data.append('codigoBarras',codigoActivo);
			data.append('ruta',"../../");
			peticionUnica1=crearComunicacion(peticionUnica1);			

			peticionUnica1.onreadystatechange = mostrarSubirArchivo;
			peticionUnica1.open("POST","ajax/subirArchivo.php",false);			

			peticionUnica1.send(data);
		}
		if (document.getElementById("imagen_Incidencia").value != "")
		{
			inputFileImage = document.getElementById("imagen_Incidencia");

			file = inputFileImage.files[0];

			data = new FormData();

			data.append('archivo',file);
			//data.append('tamanio',tamanio);
			//data.append('idProducto',idProducto);*/
			data.append('accion',"subir");
			data.append('tipo',"incidencia");
			data.append('codigoBarras',codigoActivo);
			data.append('ruta',"../../");
			peticionUnica1=crearComunicacion(peticionUnica1);
			//peticion5=new XMLHttpRequest();

			peticionUnica1.onreadystatechange = mostrarSubirArchivo;
			peticionUnica1.open("POST","ajax/subirArchivo.php",false);
			//peticion27.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

			peticionUnica1.send(data);
		}
		
		document.getElementById("imagen_Arranque").value = "";
		document.getElementById("imagen_Calidad").value = "";
		document.getElementById("imagen_Incidencia").value = "";
		
	}
	else
	{
		alert("Leer un codigo de barras");
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
			}
			peticionUnica1=null;
		}
	}
}


function verHistorialDelDia() //js_pdaProduccion
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerHistorialDelDia;
		peticionUnica1.open("POST","ajax/cargarRegistrosHoraEmpleado.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerHistorialDelDia();
		peticionUnica1.send(query_string);
	}
}

function consultaVerHistorialDelDia()
{	
	var consulta = "accion=verHistorialDelDia";	
	return consulta;	
}

function mostrarVerHistorialDelDia()
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
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";					
				}
				
				var contenido = "";
				//contenido += '<table>';
				contenido += '<tr class="centrarTexto">';
					contenido += '<th align="center">Id</th>';						
					contenido += '<th>Proceso</th>';					
					contenido += '<th>Inicio</th>';
					contenido += '<th>Fin</th>';
					contenido += '<th>Total</th>';
					contenido += '</tr>';
				
				var contador = 0;				
				while  (contador<datos.length)
				{  //Number(n).toLocaleString('es');
					contenido += '<tr>';
					contenido += '<td align="center">'+datos[contador]["id"]+'</td>';
					//contenido += '<td align="center">'+datos[contador]["inicialComercial"]+'</td>';
					
					contenido += '<td align="center">'+datos[contador]["codigoBarras"]+'</td>';
					contenido += '<td align="center">'+datos[contador]["horaInicio"]["date"].substring(11,19)+'</td>';
					
					if (datos[contador]["horaFin"]=="" || datos[contador]["horaFin"]=="null" || datos[contador]["horaFin"]==null)
					{
						contenido += '<td></td>'
					}
					else
					{
						contenido += '<td>'+datos[contador]["horaFin"]["date"].substring(11,19)+'</td>';		
					}
					
					//contenido += '<td>'+datos[contador]["horaFin"]["date"].substring(11,19)+'</td>';					
					contenido += '<td>'+datos[contador]["horas"]+'</td>';
					
					contenido += '</tr>';
					
					contador++;	
				}
				//contenido += '</table>';
				
				document.getElementById("historiaDelDia").innerHTML = contenido;
				
				if (datos.length<=0)
				{
					document.getElementById("historiaDelDiaTotal").innerHTML = "<tr><td></td></tr><tr><td><b>Tiempo Total Realizado: 0</b></td></tr>";
				}
				else
				{
					document.getElementById("historiaDelDiaTotal").innerHTML = "<tr><td></td></tr><tr><td><b>Tiempo Total Realizado: "+datos[0]["horasTotal"]+"</b></td></tr>";
				}
				
						
			}
			peticionUnica1=null;			
		}
	}						
}


function verSiEsIntro(e,valor) //js_pdaProduccion
{
	if (e.keyCode === 13 && !e.shiftKey) 
	{
		//alert(valor);
		comprobarCodigo(valor);
	}
}
function verSiEsIntro2(e,valor)//js_pdaProduccion
{
	if (e.keyCode === 13 && !e.shiftKey) 
	{		
		document.getElementById("pda_observaciones").value = "";					
		document.getElementById("pda_observaciones").focus();
	}
}
function verSiEsIntro3(e,valor)//js_pdaProduccion
{
	if (e.keyCode === 13 && !e.shiftKey) 
	{		
		document.getElementById("pda_codigoBarras").value = "";					
		document.getElementById("pda_codigoBarras").focus();
	}
}


function comprobarCodigo(valor) //js_pdaProduccion
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarCodigo;
		peticionUnica1.open("POST","ajax/comprobarCodigo.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaComprobarCodigo(valor);
		peticionUnica1.send(query_string);						
	}
}

function consultaComprobarCodigo(valor)
{	
	var consulta = "accion=comprobarCodigo";
	consulta += "&valor="+valor;
	consulta += "&notas="+document.getElementById("pda_observaciones").value;
	consulta += "&cantidad="+document.getElementById("pda_cantidadRealizada").value;
	
	return consulta;	
}



function mostrarComprobarCodigo()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{				
				document.getElementById("pda_estado").innerHTML = peticionUnica1.responseText;
				document.getElementById("pda_estado").style.color = "red";
				document.getElementById("pda_estado").style.textAlign = "center";
				document.getElementById("pda_estado").style.fontWeight = "900";
				document.getElementById("pda_codigoBarras").value = "";					
				document.getElementById("pda_codigoBarras").focus();				
			}
			else
			{
				var datos = new Array;
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
				if (datos != "")
				{	
					document.getElementById("botonAnadirVerMultiUsuario1").innerHTML = 'VER EMPLEADOS';			
					document.getElementById("pda_cliente").innerHTML = datos[0]["cliente"];
					document.getElementById("pda_campana").innerHTML = datos[0]["campana"];
					document.getElementById("pda_cantidadTrabajo").innerHTML = datos[0]["cantidad trabajo"];
					document.getElementById("pda_presupuestador").innerHTML = datos[0]["pda_presupuestador"];				
					//document.getElementById("pda_fechaCompromiso").innerHTML = datos[0]["fe-compromiso"]["date"].substring(0,10);
					
					if (datos[0]["fe-compromiso"] != null)
					{
						if (datos[0]["fe-compromiso"] != '' && datos[0]["fe-compromiso"] !== true)
						{

							document.getElementById("pda_fechaCompromiso").innerHTML = datos[0]["fe-compromiso"]["date"].substring(0,10);
						}
					}					
					else 
					{
						document.getElementById("pda_fechaCompromiso").innerHTML = '';
					}
					
					
					document.getElementById("pda_concepto").innerHTML = datos[0]["concepto"];
					document.getElementById("pda_cantidadProceso").innerHTML = datos[0]["cantidad proceso"];
					document.getElementById("pda_descripcion").innerHTML = datos[0]["descripcion"];
					document.getElementById("pda_notasCibeles").innerHTML = datos[0]["notaCibeles"];
					document.getElementById("pda_estado").innerHTML = document.getElementById("pda_codigoBarras").value + ": Proceso Abierto";				
					document.getElementById("pda_estado").style.color = "greenyellow";
					document.getElementById("pda_estado").style.textAlign = "center";
					codigoActivo = document.getElementById("pda_codigoBarras").value;
					document.getElementById("pda_codigoBarras").value = "";					
					document.getElementById("pda_codigoBarras").focus();

					verHoraInicio();
					enviarImagen = true;

					//document.getElementById("botonesEliminarMultiUsuario").style.visibility = "hidden"; //quitar esto
					
				}
				else
				{
					document.getElementById("botonAnadirVerMultiUsuario1").innerHTML = 'AÑADIR EMPLEADOS';
					
					document.getElementById("pda_cliente").innerHTML = "";
					document.getElementById("pda_campana").innerHTML = "";
					document.getElementById("pda_cantidadTrabajo").innerHTML = "";
					document.getElementById("pda_presupuestador").innerHTML = "";				
					document.getElementById("pda_fechaCompromiso").innerHTML = "";
					document.getElementById("pda_concepto").innerHTML = "";
					document.getElementById("pda_cantidadProceso").innerHTML = "";
					document.getElementById("pda_descripcion").innerHTML = "";
					document.getElementById("pda_notasCibeles").innerHTML = "";
					document.getElementById("pda_horaInicio").innerHTML = ""; 
					document.getElementById("pda_cantidadRealizada").value = ""; 
					document.getElementById("pda_observaciones").value = "";
					document.getElementById("pda_codigoBarras").value = "";					
					
					document.getElementById("pda_estado").innerHTML = codigoActivo + ": Proceso Cerrado";
					document.getElementById("pda_estado").style.color = "greenyellow";
					document.getElementById("pda_estado").style.textAlign = "center";
					
					document.getElementById("pda_codigoBarras").focus();				
					
					enviarImagen = false;
					
					codigoActivo = "";
					
					verHistorialDelDia();
				}
				
			}
			peticionUnica1=null;
		}
	}						
}

function verHoraInicio() //js_pdaProduccion
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerHoraInicio;
		peticionUnica1.open("POST","ajax/verHoraInicio.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerHoraInicio();
		peticionUnica1.send(query_string);						
	}
}

function consultaVerHoraInicio(valor)
{	
	var consulta = "accion=verHoraInicio";		
	return consulta;	
}

function mostrarVerHoraInicio()
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
				var espacio = datos[0]["horaInicio"]["date"].lastIndexOf(' ');
				var punto = datos[0]["horaInicio"]["date"].lastIndexOf('.');
				
				var longitud = datos[0]["horaInicio"]["date"].length;				
				
				var fecha = datos[0]["horaInicio"]["date"].substr(espacio+1, longitud-punto+1);				
				
				document.getElementById("pda_horaInicio").innerHTML = fecha; 
			}
			peticionUnica1=null;
		}
	}						
}




function verSiHayProcesoAbierto()
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarVerSiHayProcesoAbierto;
		peticionUnica1.open("POST","ajax/verEmpleadoProcesoAbierto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaVerSiHayProcesoAbierto();
		peticionUnica1.send(query_string);						
	}
}

function consultaVerSiHayProcesoAbierto()
{	
	var consulta = "accion=verSiHayProcesoAbierto";		
	return consulta;	
}

function mostrarVerSiHayProcesoAbierto()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{				
				//document.getElementById("pda_estado").innerHTML = peticionUnica1.responseText;
				//document.getElementById("pda_estado").style.color = "red";
				//document.getElementById("pda_estado").style.textAlign = "center";
				//document.getElementById("pda_estado").style.fontWeight = "900";
				document.getElementById("pda_codigoBarras").value = "";					
				document.getElementById("pda_codigoBarras").focus();	
				document.getElementById("botonAnadirVerMultiUsuario1").innerHTML = 'AÑADIR EMPLEADOS';			
			}
			else
			{
				var datos = new Array;
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
				}
				
				if (datos != "")
				{			
					document.getElementById("botonAnadirVerMultiUsuario1").innerHTML = 'VER EMPLEADOS';
					
					document.getElementById("pda_cliente").innerHTML = datos[0]["cliente"];

					var espacio = datos[0]["horaInicio"]["date"].lastIndexOf(' ');
					var fecha = datos[0]["horaInicio"]["date"].substr(0, espacio);		
					var partesFecha = fecha.split('-');

					var dia=partesFecha[2];
					var mes=partesFecha[1];
					var anio=partesFecha[0];

					var punto = datos[0]["horaInicio"]["date"].lastIndexOf('.');
					
					var longitud = datos[0]["horaInicio"]["date"].length;				
					
					var hora = datos[0]["horaInicio"]["date"].substr(espacio+1, longitud-punto+1);		


					document.getElementById("pda_horaInicio").innerHTML = dia + "/" + mes + "/" + anio + " "+hora;							


					document.getElementById("pda_campana").innerHTML = datos[0]["campana"];
					document.getElementById("pda_cantidadTrabajo").innerHTML = datos[0]["cantidad trabajo"];
					document.getElementById("pda_presupuestador").innerHTML = datos[0]["presupuestador"];	
					//document.getElementById("pda_comercial").innerHTML = datos[0]["comercial"];			
					
					
					if (datos[0]["fechaCompromiso"] != null)
					{
						if (datos[0]["fechaCompromiso"] != '' && datos[0]["fechaCompromiso"] !== true)
						{
							fecha = datos[0]["fechaCompromiso"]["date"].substring(0,10);
							partesFecha = fecha.split('-');

							dia=partesFecha[2];
							mes=partesFecha[1];
							anio=partesFecha[0];

							document.getElementById("pda_fechaCompromiso").innerHTML = dia + "/" + mes + "/" + anio;
						}
					}					
					else 
					{
						document.getElementById("pda_fechaCompromiso").innerHTML = '';
					}
					
					
					document.getElementById("pda_concepto").innerHTML = datos[0]["concepto"];
					document.getElementById("pda_cantidadProceso").innerHTML = datos[0]["cantidad proceso"];
					document.getElementById("pda_descripcion").innerHTML = datos[0]["descripcion"];
					document.getElementById("pda_notasCibeles").innerHTML = datos[0]["notaCibeles"];

					document.getElementById("pda_codigoBarras").value = datos[0]["codigoBarras"];

					document.getElementById("pda_estado").innerHTML = document.getElementById("pda_codigoBarras").value + ": Proceso Abierto";				
					document.getElementById("pda_estado").style.color = "greenyellow";
					document.getElementById("pda_estado").style.textAlign = "center";
					codigoActivo = document.getElementById("pda_codigoBarras").value;
					document.getElementById("pda_codigoBarras").value = "";					
					document.getElementById("pda_codigoBarras").focus();

					//verHoraInicio();  
					enviarImagen = true;
					
				}
				else
				{
				
					document.getElementById("botonAnadirVerMultiUsuario1").innerHTML = 'AÑADIR EMPLEADOS';
					document.getElementById("pda_cliente").innerHTML = "";
					document.getElementById("pda_campana").innerHTML = "";
					document.getElementById("pda_cantidadTrabajo").innerHTML = "";
					document.getElementById("pda_comercial").innerHTML = "";				
					document.getElementById("pda_fechaCompromiso").innerHTML = "";
					document.getElementById("pda_concepto").innerHTML = "";
					document.getElementById("pda_cantidadProceso").innerHTML = "";
					document.getElementById("pda_descripcion").innerHTML = "";
					document.getElementById("pda_notasCibeles").innerHTML = "";
					document.getElementById("pda_horaInicio").innerHTML = ""; 
					document.getElementById("pda_cantidadRealizada").value = ""; 
					document.getElementById("pda_observaciones").value = "";
					document.getElementById("pda_codigoBarras").value = "";					
					
					document.getElementById("pda_estado").innerHTML = codigoActivo + ": Proceso Cerrado";
					document.getElementById("pda_estado").style.color = "greenyellow";
					document.getElementById("pda_estado").style.textAlign = "center";
					
					document.getElementById("pda_codigoBarras").focus();				
					
					enviarImagen = false;
					
					codigoActivo = "";
					
					verHistorialDelDia();
				}
				
			}
			peticionUnica1=null;
		}
	}						
}




function cargarEmpleadosPDA(idInput) 
{	
	idInputListado = idInput;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarEmpleadosPDA;
		peticionUnica1.open("POST","ajax/cargarEmpleadosPDA.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarEmpleadosPDA();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarEmpleadosPDA()
{	
	var consulta = "accion=cargarEmpleadosPDA";	
	return consulta;	
}

function mostrarCargarEmpleadosPDA()
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
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
					
					
				}
				
				var contenido = "";
				
				if (idInputListado=="buscarEmpleado")
				{
					contenido += '<option value="">Todos</option>';
				}
				
				
				
				
				var contador = 0;				
				while  (contador<datos.length)
				{  
					contenido += '<option value="'+datos[contador]["idEmpleado"]+'">'+datos[contador]["nombre"]+' '+datos[contador]["apellidos"]+'</option>';
					
					contador++;	
				}
				
				document.getElementById(idInputListado).innerHTML = contenido;
				idInputListado="";
						
			}
			peticionUnica1=null;			
		}
	}						
}


function anadirMultiUsuario(event) 
{	
	event.preventDefault();
	mostrarModal = true;
	peticionUnica1=null;
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{
		peticionUnica1.onreadystatechange = mostrarAnadirMultiUsuario;		
		peticionUnica1.open("POST","ajax/anadirMultiUsuario.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		let query_string1 = consultaAnadirMultiUsuario();
		peticionUnica1.send(query_string1);
	}
}

function consultaAnadirMultiUsuario()
{	
	var consulta = "accion=insertarMultiUsuario";	
	consulta += "&idMultiEmpleado=" + document.getElementById("empleadosModal").value;
	return consulta;	
}

function mostrarAnadirMultiUsuario()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			var datos = new Array;
				
			try 
			{
				datos = JSON.parse(peticionUnica1.responseText);
			}
			catch (error)
			{
				datos="";				
			}

			if (peticionUnica1.responseText.substr(0,6)=="Error1" || peticionUnica1.responseText.substr(0,6)=="Error2")
			{			
				document.getElementById("errorMultiusuarioProcesoAbierto").innerHTML='<font style="color:red">' + peticionUnica1.responseText + '</font>';
			}
			else if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				document.getElementById("errorMultiusuarioProcesoAbierto").innerHTML="";
				alert(peticionUnica1.responseText);				
			}
			else
			{
				document.getElementById("errorMultiusuarioProcesoAbierto").innerHTML="";
				cargarListadoMultiUsuario();
			}
			

			peticionUnica1=null;
			//mostrarModal = false;
				
						
		}
	}
	//$("#listadoEmpleadosModal").modal('show');				
}




function cargarListadoMultiUsuario() 
{	
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarListadoMultiUsuario;
		peticionUnica1.open("POST","ajax/cargarMultiUsuario.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarListadoMultiUsuario();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarListadoMultiUsuario()
{	
	var consulta = "accion=cargarMultiUsuario";		
	return consulta;	
}

function mostrarCargarListadoMultiUsuario()
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
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
					
					
				}
				
				var contenido = "";
			
				var contador = 0;				
				while  (contador<datos.length)
				{


					contenido += '<tr>';
					contenido += '<td scope="row">'+datos[contador]["nombreEmpleado"]+'</td>';
					
					if (!document.getElementById("pda_estado").innerHTML.includes("Abierto"))
					{
						contenido += '<td id="botonesEliminarMultiUsuario"><input type="image" src="imagenes/eliminarNegro.png" style="width:20px" onclick="quitarMultiUsuario('+datos[contador]["idEmpleado"]+')"></td> ';
					}
					
					

					contenido += '</tr>';
					
					contador++;	
				}
				
				document.getElementById("empleadosAnadidos").innerHTML = contenido;


				if (!document.getElementById("pda_estado").innerHTML.includes("Abierto"))
				{	
					document.getElementById("botonAnadirMultiUsuario").style.visibility = "visible";
				}
				else
				{
					document.getElementById("botonAnadirMultiUsuario").style.visibility = "hidden";
				}
				
						
			}

			primeraVez=false;

			peticionUnica1=null;	

			//if (mostrarModal==true)
			{
				//$("#listadoEmpleadosModal").modal('show');	
				
			}

			/*if (primeraVez==true)
			{
				$("#listadoEmpleadosModal").modal('hide');
				primeraVez=false;
			}*/
			
				
		}
	}						
}



function quitarMultiUsuario(idMultiEmpleado) 
{	
	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarQuitarMultiUsuario;
		peticionUnica1.open("POST","ajax/quitarMultiUsuario.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaQuitarMultiUsuario(idMultiEmpleado);
		peticionUnica1.send(query_string);
	}
}

function consultaQuitarMultiUsuario(idMultiEmpleado)
{	
	var consulta = "accion=quitarMultiUsuario";	
	consulta += "&idMultiEmpleado=" + idMultiEmpleado;
	return consulta;	
}

function mostrarQuitarMultiUsuario()
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
				
				try 
				{
					datos = JSON.parse(peticionUnica1.responseText);
				}
				catch (error)
				{
					datos="";
					
					
				}
				
				cargarListadoMultiUsuario();
						
			}
			peticionUnica1=null;			
		}
	}						
}


function gestionBotonVerEmpleados()
{
	cargarListadoMultiUsuario();
	$("#listadoEmpleadosModal").modal('show');
	
}
