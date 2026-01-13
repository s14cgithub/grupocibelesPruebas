

<?php 

session_start(); 
$_SESSION['titulo']="REGISTROS INFORMATICA - COMPROBACIONES";
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

							Fecha Inicio (Desde): <input class="tamanioFecha" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value="" onChange="cambiarFechaFin('buscarFechaInicio','buscarFechaFin')"></input>
							Fecha Inicio (Hasta): <input class="tamanioFecha" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
						</span>
						<input type="submit" class="btn btn-info" onClick="buscarRegistros();" value="Buscar" ></input>	
					</td>
				</tr>



				<tr>

				
					<td align="center">

					<!--Nº Meses: <input type="number" id="buscarNumMeses" value="2"></input>
					<span style="margin-left: 15px"></span>-->


						<!--<label for="columnas">Ordenar Por:</label>

						<select name="orden" id="orden">
							<option value="<?php echo registroHora_columnaId ?>"><?php echo registroHora_columnaId ?></option>											
							<option value="<?php echo registroHora_columnaHoraInicio ?>">fecha y hora - inicio</option>
							<option value="<?php echo registroHora_columnaHoraFin ?>">fecha y hora - fin</option>
							<option value="<?php echo registroHora_columnaCantidad ?>"><?php echo registroHora_columnaCantidad ?></option>
						</select>
						<input type="checkbox" id="ordenDesc" checked>Desc</input> -->
											
					</td>
				</tr>
				

			</table>
		</td>
	</tr>
			
	<tr>
		<td>
			<hr>
			<table class="tabla">
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
<script  src="js/js_registrosHorasManuales_Comprobacion.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">

	<?php 
		if ($_SESSION["permiso_pdaGestion"]==1)
		{		
			echo 'permisosSoloLectura = true;';		
		}
	?>


	buscarRegistros();	

	document.getElementById("button-up").addEventListener("click", scrollUp);

</script>