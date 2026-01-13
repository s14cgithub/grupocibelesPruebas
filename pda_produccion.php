


<?php 

session_start(); 
$_SESSION['titulo']="PRODUCCION";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");





?>
<script type="text/javascript"> 
	//var primeraVez = true;
</script>


<table class="table sinBorde pda">

	
	
	<tr>
		<td col colspan="2" style="text-align: center;"><label id="pda_estado" name="pda_estado"></label></td>
	</tr>
	<tr>
		<td align="right">Codigo de Barras: </td>
		<td><input  id="pda_codigoBarras" name="pda_codigoBarras" onKeyPress="verSiEsIntro(event,this.value)"></input></td>		
		
	</tr>
	<tr>
		
		<td col colspan="2" style="text-align: center;"><button id="botonAnadirVerMultiUsuario1" class="btn  btn-info btn-sm"  style="font-size: 0.6rem; padding: 0.15rem 0.4rem;" data-toggle="modal"  onclick="gestionBotonVerEmpleados()" data-whatever="@mdo">AÑADIR-VER EMPLEADOS</button></td>		
		
	</tr>
	<tr>
		<td align="right">Cliente: </td>
		<td><label  id="pda_cliente" name="pda_cliente"></label></td>
	</tr>
	<tr>
		<td align="right">Hora Inicio: </td>
		<td><label  id="pda_horaInicio" name="pda_horaInicio"></label></td>		
	</tr>

	<tr>
		<td align="right">Campaña: </td>
		<td><label   id="pda_campana" name="pda_campana" ></label></td>
	</tr>

	<tr>
		<td align="right">Concepto: </td>
		<td ><label id="pda_concepto" name="pda_concepto" ></label></td>
	</tr>
	<tr>
		<td align="right">Descripcion: </td>
		<td><label  id="pda_descripcion" name="pda_descripcion" ></label></td>
	</tr>
	<tr>
		<td align="right">Notas: </td>
		<td><label style="width: 100%" class="" id="pda_notasCibeles" name="pda_notasCibeles" ></label></td>
	</tr>
	<tr>
		<td align="right">Cantidad Trabajo: </td>
		<td><label class="" id="pda_cantidadTrabajo" name="pda_cantidadTrabajo" ></label></td>
	</tr>
	<tr>
		<td align="right">Cantidad Proceso: </td>
		<td><label class="" id="pda_cantidadProceso" name="pda_cantidadProceso" ></label></td>
	</tr>
	<tr>
		<td align="right">Presupuestador: </td>
		<td><label class="" id="pda_presupuestador" name="pda_presupuestador" ></label></td>
	</tr>
	<!--<tr>
		<td align="right">Comercial: </td>
		<td><label class="" id="pda_comercial" name="pda_comercial" ></label></td>
	</tr>-->
	<tr>
		<td align="right">Fecha Compromiso: </td>
		<td><label class="" id="pda_fechaCompromiso" name="pda_fechaCompromiso" ></label></td>
	</tr>
	<tr>
		<td align="right">Cantidad Realizada: </td>
		<td><input class="" type="text" id="pda_cantidadRealizada" name="pda_cantidadRealizada"  onKeyPress="verSiEsIntro2(event)"></input></td>
	</tr>
	<tr>
		<td align="right" colspan="2">Observaciones: </td>
	</tr>
	<tr>
		
		<td colspan="2"><textarea  id="pda_observaciones" name="pda_observaciones"  rows="10" cols="35" onKeyPress="verSiEsIntro3(event)"></textarea></td>
	</tr>



<?php 
	if ($_SESSION["permiso_pdaAdjuntos"]==1 || $_SESSION["permiso_pdaAdjuntos"] == 2)
	{
		echo '<table class="table sinBorde pda2"><tr>';
		
		echo ('<tr><td>Arranque:</td><td><input type="file" name="imagen_Arranque" id="imagen_Arranque" accept=".jpg,.png,.gif" /></td></tr><br>');
		echo ('<tr><td>Calidad:</td><td><input type="file" name="imagen_Calidad" id="imagen_Calidad" accept=".jpg,.png,.gif" /></td></tr><br>');
		echo ('<tr><td>Incidencia:</td><td><input type="file" name="imagen_Incidencia"  id="imagen_Incidencia" accept=".jpg,.png,.gif,.jpeg" /></td></tr><br>');
		echo ('<tr><td colspan="2" align="center"><button id="enviarImagen" class="btn btn-info btn-sm" onClick="subirImagenArranque();">Enviar Imagen</button></td></tr><br>');
		
		
		echo ('<tr><td colspan="2" align="center"><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#historialModal" data-whatever="@mdo">REGISTROS DEL DIA</button></td></tr><br>');
		
		
		
		
		
		
		echo ('</tr><table>');
	}

?>

<!-- HISTORIAL DEL DIA-->
<!--<label class="btn btn-info btn-sm">Arranque<input type="file" name="imagen_Arranque" id="imagen_Arranque" accept=".jpg,.png,.gif" hidden/>
</label>
<label class="btn btn-info btn-sm">Calidad<input type="file" name="imagen_Calidad" id="imagen_Calidad" accept=".jpg,.png,.gif" hidden/>
</label>
<label class="btn btn-info btn-sm">Incidencia<input type="file" name="imagen_Incidencia"  id="imagen_Incidencia" accept=".jpg,.png,.gif" hidden/>
</label>-->


</table>

<!--<table align="center" id="historiaDelDia" border="1"></table>-->


			
			

			
	<!--REGISTROS DEL DIA -->
<div class="modal fade" id="historialModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static" >
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header555" style="text-align: center;margin-top: 10px;">
        		<h5 class="modal-title" id="ModalLabel" style="text-align: center">
					<span  style="color:#007CBA; text-align: center">REGISTROS DEL DIA</span>
				</h5>
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>-->
      		</div>
      		<div class="modal-body">		
				<table align="center" id="historiaDelDia" border="1" style="color: #007cba;" cellspacing="5" cellpadding="5"></table>
				<table align="center" id="historiaDelDiaTotal" border="0" style="color: #007cba;" cellspacing="5" cellpadding="5"></table>
      		</div>
		
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="" onClick="eventCerrarModal()">Cerrar</button>        
      		</div>
    	</div>
  	</div>
</div>	



<!--LISTADO EMPLEADOS-->
<div class="modal fade" id="listadoEmpleadosModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ModalLabel">EMPLEADOS</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">		
				<form> 
					<div class="form-group">					

						<table class="table">							
								<tr>
									<td>Añadir
									<select id="empleadosModal" class=""></select> 
									<input type="image" id="botonAnadirMultiUsuario" value="" src="imagenes/crearNegro.png" style="width:20px;" onclick="anadirMultiUsuario(event)"/></td>
								</tr>
						</table>
					</div>

					<div class="form-group">
						<label id="errorMultiusuarioProcesoAbierto"></label>						
					</div>

					<div class="form-group">
						<table class="table">
							<thead>
								<tr>
									<th scope="col">Empleados Seleccionados</th>
									<th scope="col">Quitar</th>      
								</tr>
							</thead>
							<tbody id="empleadosAnadidos">					
							</tbody>
						</table>
					</div>			
				</form>
			</div>
		
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="" onClick="eventCerrarModalEmpleado()">Cerrar</button>       
			</div>
    	</div>
  	</div>
</div>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_pdaProduccion.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>			


		<script type="text/javascript"> 

			if (primeraVez==false)
			{
				//$("#listadoEmpleadosModal").modal('show');
			}


			document.getElementById("pda_codigoBarras").value = "";					
			
			document.getElementById("pda_codigoBarras").focus();
			
			
			
			
			
			
			
			//alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;

			
			
			
			//alert(screen.width + " x " + screen.height);

			//HacerAlgo();
			
			/*if (window.navigator && window.navigator.vibrate)
			{
		   		//alert("con vibrador");
				
			} else 
			{
			   //alert("sin vibrador");
			}
			


			function prueba()
			{
				window.navigator.vibrate([500]);
			}*/
			
			document.getElementById("menu_prinpipal").style.visibility = "hidden";
			document.getElementById("menu_prinpipal").style.display = "none";
			
			//document.getElementById("menu_irAtras").style.visibility = "hidden";
			//document.getElementById("menu_irAtras").style.display = "none";
			
			document.getElementById("cabeceraBotonAtras").style.visibility = "hidden";
			document.getElementById("cabeceraBotonAtras").style.display = "none";
			
			cargarEmpleadosPDA("empleadosModal");
			
			//cargarListadoMultiUsuario();
			
			verSiHayProcesoAbierto();
			verHistorialDelDia();
			
			
			function eventCerrarModal()
			{
				$('#historialModal').modal('hide');
				setTimeout(function(){ 

					document.getElementById("pda_codigoBarras").focus();
				}, 500);  
				
			}

			
			function eventCerrarModalEmpleado()
			{
				$('#listadoEmpleadosModal').modal('hide');
				setTimeout(function(){ 

					document.getElementById("pda_codigoBarras").focus();
				}, 500);  
				
			}
			
				
			
		</script>
	
				


<?php

echo ("</div>");

//echo ('<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>');



echo ("</body>");

echo ("</html>");



?>



