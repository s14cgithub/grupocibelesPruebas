
var peticionUnica1 = null;
var permisosSoloLectura = null;

function cargarDetallesOt()//js_presupuestosOtDetalle
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarCargarDetallesOt;
		peticionUnica1.open("POST","ajax/cargarDetallesPresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaCargarDetallesOt();
		peticionUnica1.send(query_string);
	}
}

function consultaCargarDetallesOt()
{	
	var consulta = "accion=cargarDetalles";
	
	var campos = [
		'idTipo',
		'tipoProceso',
		'id',
		'descripcion',
		'notaCibeles',
		'notaAdmonProd',
		'unidades',
		'unidades2',
		'precio',
		'orden',
		'idDepartamento',
		'idConcepto'
		];

	consulta += "&campos=" + encodeURIComponent(JSON.stringify(campos));

	var joins = [
		'tabla5',
		'tabla6',
		'tabla7',
		'tabla8',
		'tabla9',
		'tabla10',
		'tabla11',
		'tabla12',
		'tabla13',
		'tabla14'		
	];

	consulta += "&joins=" + encodeURIComponent(JSON.stringify(joins));

	var filtros = {
    	presupuesto: document.getElementById("numPresupuesto").innerHTML
	};

	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));
	
	var order = [
    	{ campo: 'ordenTipoProceso', dir: 'ASC' },
		{ campo: 'idTipo', dir: 'ASC' },
		{ campo: 'orden', dir: 'ASC' },
		{ campo: 'id', dir: 'ASC' }
	];

	consulta += "&order=" + encodeURIComponent(JSON.stringify(order));


	return consulta;	
}

function mostrarCargarDetallesOt()
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

				if (datos != "")
				{				
					var grupoAntiguo = "9999999999999999999999999999";
					var contenido = "";

					var contador = 0;				
					while  (contador<datos.length)
					{
						if (datos[contador]["idTipo"]!= grupoAntiguo)
						{
							contenido += '<tr><td colspan="9" style="padding-top:15px;border:0px;"><h2>'+datos[contador]["tipoProceso"]+'</h2></td></tr>';
							grupoAntiguo = datos[contador]["idTipo"];
						}


						contenido += '<tr><td>proceso:</td><td colspan="7"> <select name="'+datos[contador]["id"]+'_procesoDetalle" id="'+datos[contador]["id"]+'_procesoDetalle"  disabled></select></td>';					


						if (pdfPresupuestoGenerado!=1 && !permisosSoloLectura)
						{
							contenido += '<td ROWSPAN="5" align="center"><input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarDetallePresupuesto2('+datos[contador]["id"]+')" ></td></tr>';
						}

						contenido += '<tr><td>Descripcion:</td><td colspan="7"><input type="text" id="'+datos[contador]["id"]+'_descripcionDetalle" value="'+datos[contador]["descripcion"]+'" style="width:100%" readonly></input></td></tr>';
						//contenido += '<tr><td>Nota Cibeles:</td><td colspan="7"><input type="text" id="'+datos[contador]["id"]+'_notaDetalle" value="'+datos[contador]["notaCibeles"]+'" style="width:100%"></input></td>'
						
						
						contenido += '<tr><td>Nota Cibeles:</td><td colspan="7"><textarea id="'+datos[contador]["id"]+'_notaDetalle" style="width: 100%; ">'+datos[contador]["notaCibeles"]+'</textarea></td>'
						
						contenido += '<tr><td>Nota Admon-Prod:</td><td colspan="7"><textarea id="'+datos[contador]["id"]+'_notaAdmonProd" style="width: 100%; ">'+datos[contador]["notaAdmonProd"]+'</textarea></td>'

						/*if (pdfPresupuestoGenerado!=1)
						{
							contenido += '<td ROWSPAN="2"><input type="image" id="'+datos[contador]["id"]+'_eliminarDetalle" value="" src="imagenes/eliminar.png" style="width:20px;" onclick="eliminarDetallePresupuesto('+datos[contador]["id"]+')" ></td></tr>';
						}*/

						contenido += '<tr>';
						contenido += '<td>Uds. Presu:</td><td><input type="number" id="'+datos[contador]["id"]+'_unidadesDetalle" value="'+datos[contador]["unidades"]+'" readonly></input></td>';
						contenido += '<td>Precio:</td><td><input type="number" id="'+datos[contador]["id"]+'_precioDetalle" value="'+datos[contador]["precio"]+'" readonly></input></td>';
						contenido += '<td>Orden:</td><td><input type="number" id="'+datos[contador]["id"]+'_ordenDetalle" value="'+datos[contador]["orden"]+'" readonly></input></td>';
						
						var unidades2=datos[contador]["unidades2"];
						if (unidades2!="" && unidades2!=null )
						{
							if (unidades2.substr(-4)==".000")
							{
								unidades2 = unidades2.substr(0,unidades2.indexOf('.'));
							}
						}
						
						
						contenido += '<td>Uds Ot:</td><td><input type="number" id="'+datos[contador]["id"]+'_unidadesDetalle2" value="'+unidades2+'"></input></td>';

						contenido += '<tr><td colspan="7" style="border:0px;"><hr></td></tr>';

						contenido += '</tr>';

						contador++;					
					}

					document.getElementById("detallesPresupuesto").innerHTML = contenido;

					contador = 0;

					while  (contador<datos.length)
					{ 
						valorTipo = datos[contador]["idTipo"];
						valorDepartamento = datos[contador]["idDepartamento"];
						
						cargarSubprocesos(datos[contador]["id"]+'_procesoDetalle',1);

						document.getElementById(datos[contador]["id"]+'_procesoDetalle').value = datos[contador]["idConcepto"];					
						
						valorTipo = 0;
						valorDepartamento=0;
						contador++;	
					}

					document.getElementById("detallesPresupuesto").style.visibility = "visible";
					document.getElementById("detallesPresupuesto").style.display = "table-row-group";
					document.getElementById("detallesPresupuesto").colSpan = "6";
				}
				
				//rellenar los listado y seleccionar el correspondiente
				//hacer visible el tbody
				
			}
			peticionUnica1=null;
		}
	}						
}

function modificarDetallePresupuesto2(idInput)//js_presupuestosOtDetalle
{	
	peticionUnica1=crearComunicacion(peticionUnica1);

	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarModificarDetallePresupuesto2;
		peticionUnica1.open("POST","ajax/modificarDetallePresupuesto.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaModificarDetallePresupuesto2(idInput);
		peticionUnica1.send(query_string);
	}
}

function consultaModificarDetallePresupuesto2(idInput)
{	

	var consulta = "accion=modificarDetalle";	

	var datos = {	
		notaCibeles: document.getElementById(idInput+"_notaDetalle").value,
		notaAdmonProd: document.getElementById(idInput+"_notaAdmonProd").value,
		unidades2:  document.getElementById(idInput+"_unidadesDetalle2").value === "" ? 0  : parseFloat(document.getElementById(idInput+"_unidadesDetalle2").value.replace(',', '.'))
	}

	consulta += "&datos=" + encodeURIComponent(JSON.stringify(datos));

	var presu = document.getElementById("numPresupuesto").innerHTML === "" ? document.getElementById("numPresupuesto").innerHTML : document.getElementById("numPresupuesto").innerHTML + document.getElementById("letraPresupuesto").innerHTML
		
	consulta+="&numPresupuesto="+presu;	

	var filtros = {
    	id: idInput
	};
	consulta += "&filtros=" + encodeURIComponent(JSON.stringify(filtros));

	consulta += "&origen=presuestosOtDetalle";
	
	return consulta;
	
}

function mostrarModificarDetallePresupuesto2()
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
				alert("Detalle Modificado");
				peticionUnica1=null;
				cargarDetallesOt();
			}
		}
	}						
}
