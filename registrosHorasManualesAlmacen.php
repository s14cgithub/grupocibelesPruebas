

<?php 

session_start(); 
$_SESSION['titulo']="REGISTROS MANUALES";
$ruta="/";


require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
//require($ruta."Archivos Comunes/constantes.php");
//$_SESSION["idEmpleado"];	

$tiposProceso = cargarTipoDeProceso($conexion);
$numero = count($tiposProceso);

?>
<table align="center" border="0"  class="">
	
	<tr>
		<td>
			<table border ="0" align="center">
				<tr>
					<td align="center">
						<span style="margin-left: 100px">
							<span class="textoNegrita">Buscar</span>

							Ot: <input class="tamanio7" type="text" id="buscarOtValor" name="buscarOtValor"></input>						

							Fecha Inicio: <input class="tamanioFecha" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input>
							Fecha Fin: <input class="tamanioFecha" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
						</span>
					</td>
				</tr>



				<tr>

				
					<td align="center">

					<!--Nº Meses: <input type="number" id="buscarNumMeses" value="2"></input>-->
					<span style="margin-left: 15px"></span>


						<label for="columnas">Ordenar Por:</label>

						<select name="orden" id="orden">
							<option value="<?php echo registroHora_columnaId ?>"><?php echo registroHora_columnaId ?></option>							
							<option value="<?php echo registroHora_columnaCodigoBarras ?>"><?php echo registroHora_columnaCodigoBarras ?></option>							
							<option value="<?php echo registroHora_columnaHoraInicio ?>">fecha y hora - inicio</option>
							<option value="<?php echo registroHora_columnaHoraFin ?>">fecha y hora - fin</option>
							<option value="<?php echo registroHora_columnaCantidad ?>"><?php echo registroHora_columnaCantidad ?></option>
						</select>
						<input type="checkbox" id="ordenDesc" checked>Desc</input>
						<input type="submit" class="btn btn-info" onClick="cargarRegistrosHoras();" value="Buscar" ></input>
						<!--<button class="btn btn-info">insertar Registro</button>-->
					</td>
				</tr>

				<tr>
					<td>
						<hr>
						<table style="text-align: center">
							<tr>								
								<td align="center">Proceso</td>							
								<td align="center">Fecha Inicio</td>
								<td align="center">Fecha Fin</td>
								<td align="center">Cantidad</td>
								<td align="center">Observaciones</td>								
								<td align="center"></td>
							</tr>
							<tr>
								
								<td><input class="tamanio14" type="text" id="RN_proceso" name="RN_proceso"></input> </td>							
								<td><input class="" type="datetime-local" id="RN_fechaInicio" name="RN_fechaInicio" value=""></input></td>
								<td><input class="" type="datetime-local" id="RN_fechaFin" name="RN_fechaFin" value=""></input></td>
								<td><input class="tamanio7" type="number" id="RN_cantidad" name="RN_cantidad" value=""></input></td>
								<td><textarea rows="1" style="margin-top:3px !important;" class="" id="RN_observaciones" name="RN_observaciones" value=""></textarea></td>
								
								
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="comprobarInsertarRegistroHoraManual()" ></td>
							</tr>
							
						</table>
						<table align="center" id="datosSinProceso">
							<tr>
							<hr>
								<td>Tipo de Proceso: <select id="tipoProceso"  onChange="cargarSubprocesos();">

								<?php 
								for ($i = 0; $i < $numero; $i++)
								{		
									echo '<option value="'.$tiposProceso[$i]["id"].'">'.$tiposProceso[$i]["tipoProceso"].'</option>';
								}
								?>

								</select></td>

								<td>Proceso: <select id="procesoNombre"></select></td>
								
								<!--<td rowspan="2" style="text-align:center;"><textarea id="observacionesConcepto" cols="30"></textarea>-->
							</tr>
							<tr>
								<td colspan="2" >Cliente:<select id="clientes"></select></td>
							</tr>
						</table>	

					</td>
				</tr>

			</table>
		</td>
	</tr>
			
	<tr>
		<td>
			<hr>
			<table class="tabala">
				<tbody id="registrosHoras" name="registrosHoras"></tbody>
			</table>
		</td>
	</tr>	
</table>

	
	






</div> <!-- class="tabla" -->

					

<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>

	
				


<?php
echo ("</div>");
echo ("</body>");
echo ("</html>");

?>


<!--proceso manuales-->

<div class="modal fade" id="modalRegistroManualSinProceso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">       
		  		<h5 class="modal-title" id="ModalLabel">REGISTRO <span id="registroProcesoModal"></span></h5>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Tipo de Proceso:</label>
						<select id="tipoProcesoModal" class="form-control"   onChange="cargarSubprocesos2();">
						<?php 
								for ($i = 0; $i < $numero; $i++)
								{		
									echo '<option value="'.$tiposProceso[$i]["id"].'">'.$tiposProceso[$i]["tipoProceso"].'</option>';
								}
						?>

						</select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Proceso:</label>
						<select id="procesoModal" class="form-control"></select>
          			</div>	

					  <div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clienteModal" class="form-control"></select>
          			</div>	
          			
        		</form>
      		</div>
      		<div class="modal-footer">
			  	
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" data-dismiss="" onClick="cambiarProcesoSinIndicar()">Modificar</button>
       
      		</div>
    	</div>
  </div>
</div>



<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_registrosHorasManualesAlmacen.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">

	

	cargarSubprocesos();
	cargarClientes('A','clientes');

	cargarSubprocesos2();
	cargarClientes('A','clienteModal');


	cargarRegistrosHoras();

	//cargarEmpleadosPDA("buscarEmpleado");
	//cargarEmpleadosPDA("RN_empleado");
	
	var fechaActual = new Date();
	fechaActual.setDate(fechaActual.getDate() - 7);
	var mes=0;
	mes = fechaActual.getMonth()+1;
	var mesText = "";
	mesText = mes.toString();
	if (mesText.length==1)
	{
		mesText = "0" + mesText;
	}
	document.getElementById("buscarFechaInicio").value = fechaActual.getFullYear()+"-"+mesText + "-" + fechaActual.getDate();
	

	

	//cargarAutomaticosPda();
	document.getElementById("button-up").addEventListener("click", scrollUp);

</script>