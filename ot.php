<?php 

session_start(); 
$_SESSION['titulo']="OT";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	
	?>

	<table align="center">	
	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="presupuesto">Presupuesto</option>
				<option value="cliente">Cliente</option>
				<option value="campana">Campaña</option>
				<option value="cantidad">Cantidad</option>								
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
				<option value="presupuesto">Presupuesto</option>
				<option value="cliente">Cliente</option>
				<option value="campana">Campaña</option>
				<option value="cantidad">Cantidad</option>	
				<option value="fechaInicioReal">Fecha Inicio</option>	
				<option value="fechaTerminado">Fecha Fin</option>	
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="cargarListadoOtProduccion()">Buscar</button>
			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelOtCibeles()" value="Excel" ></input>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoOtModal" data-whatever="@mdo">Imprimir</button>
			


		</td>	
	</tr>
	<tr>
		
		
		<td align="center" colspan="4">
			<!--Año: <select class=""  id="anioSeleccionado" name="anioSeleccionado" onChange="buscarOt()"></select>-->
			Nº Meses: <input type="number" id="buscarNumMeses" value="2"></input>
			Fecha Inicio: <input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
			Fecha Fin: <input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
			Sin Fecha: <input type="checkbox" id="buscarSoloSinFecha"></input> 
		</td>

		
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>



	<?php
	
	echo '<table align="center" class="tabla">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	/*echo '<tbody id="listadoPresupuestosBusqueda" name="listadoPresupuestosBusqueda">';
	
	
	echo '<label for="columnas">Ordenar Por:</label>';
		
			echo '<select name="columnas" id="columnas">';
			echo '<option value="'.presupuesto_Presupuesto.'">'.presupuesto_Presupuesto.'</option>';
			echo '<option value="'.presupuesto_ColumnaCliente.'">'.presupuesto_ColumnaCliente.'</option>';
			echo '</select>';
			echo '<span style="margin-left: 15px"></span>';
			echo 'Desc: <input type="checkbox" id="ordenDescendiente" checked></input>';
	
			echo '<span style="margin-left: 50px"></span>';
			echo 'Buscar: <input type="text" id="buscarTexto"></input>';
	
			echo '<input type="radio" id="tipoBusquedaPresupuesto" name="tipoBusqueda" value="tipoBusquedaPresupuesto" checked>
				<label for="tipoBusquedaPresupuesto">Presupuesto</label>';
			
			echo '<span style="margin-left: 15px"></span>';
			
			echo '<input type="radio" id="tipoBusquedaCliente" name="tipoBusqueda" value="tipoBusquedaCliente">
				<label for="tipoBusquedaCliente">Cliente</label>';
			echo '<span style="margin-left: 50px"></span>';
			echo '<input type="button" class="btn btn-info" value="Buscar" onClick="cargarListadoPresupuesto()"></input>';
	
	
	
	echo '</tbody>';*/
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listadoOt" name="listadoOt"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->


<!-- NUEVA FORMA DE PAGO -->
<div class="modal fade" id="orderTrabajoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">ORDEN DE TRABAJO: <span id="numPresuModal"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>                 
		  <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fecha Aceptacion:</label>
            <input type="date" class="form-control" id="fechaAceptacion">
          </div>
		  <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fecha Compromiso:</label>
            <input type="date" class="form-control" id="fechaCompromiso">
          </div>		 	  
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="modificarPresupuesto3()">Guardar e Imprimir</button>
      </div>
    </div>
  </div>
</div> 


<!-- OBSERVACION DEL PRESUPUESTO -->
<div class="modal fade" id="observacionOtModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">PRESUPUESTO: <span id="numOtModal"></span></h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Observaciones</label>						
						<textarea maxlength="250" rows="10" class="form-control" id="observacionModal"></textarea>
          			</div>		 			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

				<?php 
	                 if ($_SESSION["permiso_ot"]==2)
	                 {		
		               echo '<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="modificarObservacionOT()">Modificar</button>';		
	                 }
	           ?>

        		
      		</div>
    	</div>
  	</div>
</div>


<!-- IMPRIMIR VARIAS OT  POR RANGO DE NUMERO DE PRESUPUESTO -->




<div class="modal fade" id="imprimirRangoOtModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Imprimir OT's Mensual:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>		
        </button>		  
      </div>	
	  
			<div class="modal-body">
				<form>
				
					<div class="form-group">						
						<label for="recipient-name" class="col-form-label">Año:</label>
						<select id="anioImprimirModal" class="form-control">
							<!--<option value="21" selected>2021</option>-->
							<option value="26" selected>2026</option>
							<option value="25">2025</option>
							<option value="24">2024</option>
							<option value="23">2023</option>
							<option value="22">2022</option>							
						</select>
					</div>
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Mes:</label>
						<select id="mesImprimirModal" class="form-control">
							<option value="01">Enero</option>
							<option value="02">Febrero</option>
							<option value="03">Marzo</option>
							<option value="04">Abril</option>
							<option value="05">Mayo</option>
							<option value="06">Junio</option>
							<option value="07">Julio</option>
							<option value="08">Agosto</option>
							<option value="09">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</div>
				</form>
      		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<!--<button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirPresupuestoMensuales()">Imprimir</button>-->
				<button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirOtRango()">Imprimir</button>
			</div>
		
		
		
		
		
		
    </div>
  </div>
</div>


<form id="formImprimirOT" name="formImprimirOT" method="post"  target="_blank" action="imprimirOtProduccion.php">
	<input type="hidden" id="imprimirNumOT" name="imprimirNumOT" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirOT"></input>		
</form>

<form id="formImprimirOTMensuales" name="formImprimirOTMensuales" method="post"  target="_blank" action="imprimirOtProduccion.php">	
	<input type="hidden" id="mesRango" name="mesRango" value=""></input>
	<input type="hidden" id="anioRango" name="anioRango" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirOT"></input>		
</form>

<form id="formImprimirOTPortada" name="formImprimirOTPortada" method="post"  target="_blank" action="imprimirOtProduccionPortada.php">
	<input type="hidden" id="imprimirNumOTPortada" name="imprimirNumOTPortada" value=""></input>
	<input type="hidden" id="imprimirAccionPortada" name="imprimirAccionPortada" value="imprimirOT"></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>

<form id="formImprimirOTPortadaMensual" name="formImprimirOTPortadaMensual" method="post"  target="_blank" action="imprimirOtProduccionPortada.php">
	<input type="hidden" id="mesRangoPortadaRango" name="mesRangoPortadaRango" value=""></input>
	<input type="hidden" id="anioRangoPortadaRango" name="anioRangoPortadaRango" value=""></input>
	<input type="hidden" id="imprimirAccionPortada" name="imprimirAccionPortada" value="imprimirOT"></input>	
</form>

<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarOtCibelesExcel.php">	

	<input type="hidden" id="camposFormExportar" name="campos">
    <input type="hidden" id="filtrosFormExportar" name="filtros">
    <input type="hidden" id="filtrosOperadoresFormExportar" name="filtrosOperadores">
    <input type="hidden" id="filtrosLikeFormExportar" name="filtrosLike">
    <input type="hidden" id="orderFormExportar" name="order">
	<input type="hidden" id="mesesFormExportar" name="meses">

	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
</form>




<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php



if ($_SESSION["permiso_fechaCompromisoPresu"]==1 || $_SESSION["permiso_fechaCompromisoPresu"] == 2)
{
	echo '<input type="hidden" id="cambiarFechaCompromiso" value="1" required></input>';
}
else
{
	echo '<input type="hidden" id="cambiarFechaCompromiso" value="0" required></input>';
}

if ($_SESSION["permiso_fechaAceptacionPresu"]==1 || $_SESSION["permiso_fechaAceptacionPresu"] == 2)
{
	echo '<input type="hidden" id="cambiarFechaAceptacion" value="1" required></input>';
}
else
{
	echo '<input type="hidden" id="cambiarFechaAceptacion" value="0" required></input>';
}

echo '<input type="hidden" id="permiso_otBajadaPresu" value="'.$_SESSION["permiso_otBajadaPresu"].'"></input>';
echo '<input type="hidden" id="permiso_otAbiertaPresu" value="'.$_SESSION["permiso_otAbiertaPresu"].'"></input>';
echo '<input type="hidden" id="permiso_fechaTerminadaPresu" value="'.$_SESSION["permiso_fechaTerminadaPresu"].'"></input>';




echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_ot.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script> 

<script language="javascript">

	<?php 
	if ($_SESSION["permiso_ot"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>


	//cargarListadoOtProduccion();
	//cargarAnios("anioSeleccionado");
	//document.getElementById("anioSeleccionado").value = 2024;
	cargarListadoOtProduccion();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>