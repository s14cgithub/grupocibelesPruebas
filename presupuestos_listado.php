


<?php 

session_start(); 
$_SESSION['titulo']="PRESUPUESTOS LISTADO";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	

	<table align="center" border="0"  class="">
		<tr>
			<td>
				Buscar: <input type="text" id="buscarTexto"></input>
				<input type="radio" id="tipoBusquedaPresupuesto" name="tipoBusqueda" value="tipoBusquedaPresupuesto" checked>
				<label for="tipoBusquedaPresupuesto">Presupuesto</label>
				<span style="margin-left: 15px"></span>
				<input type="radio" id="tipoBusquedaCliente" name="tipoBusqueda" value="tipoBusquedaCliente">
				<label for="tipoBusquedaCliente">Cliente</label>
				<span style="margin-left: 15px"></span>
				<input type="radio" id="tipoBusquedaCampana" name="tipoBusqueda" value="tipoBusquedaCampana">
				<label for="tipoBusquedaCampana">Campaña</label>
				<span style="margin-left: 50px"></span>

				Ot Bajada: <input type="checkbox" id="busqBajada"></input>	
				<span style="margin-left: 15px"></span>
				Ot Abierta: <input type="checkbox" id="busqAbierta"></input>
			</td>

			<?php
			if ($_SESSION["permiso_presupuestos"] == 2)
			{
				
				echo '<td>';
				
				
				/*echo '<td style=visibility:"hidden";display:"none">';
				echo 'Ot Bajada: <input type="checkbox" id="busqBajada"></input>';
				echo '<span style="margin-left: 15px"></span>';
				echo 'Ot Abierta: <input type="checkbox" id="busqAbierta"></input>';
				echo '</td>';*/
			}
			else
			{
				echo '<td style=visibility:hidden;display:none>';
			}

			?>
			
				
			</td>
				
		</tr>
		<tr>
			<td>
				
				Nº Meses: <input type="number" id="buscarNumMeses" value="2"></input>

				<span style="margin-left: 15px"></span>
				<label>Fecha Acept. Registrado:</label>
				<input type="date" id="buscarFechaAceptacion"></input>
				
				<span style="margin-left: 15px"></span>
				<label for="columnas">Ordenar Por:</label>
				<select name="columnas" id="columnas">
					<option value="presupuesto">Presupuesto</option>
					<option value="cliente">Cliente</option>					
				</select>
				<span style="margin-left: 15px"></span>
				Desc: <input type="checkbox" id="ordenDescendiente" checked></input>
			</td>
			<td align="right">
				
				
				<input type="button" class="btn btn-info" value="Buscar" onClick="cargarListadoPresupuesto1()"></input>
				&nbsp;&nbsp;<input type="submit" class="btn btn-info" onClick="gestionExportarExcelPresupuestos()" value="Excel" ></input>
			</td>
		</tr>
		
	</table>


	
	<?php
	
	echo '<table align="center" class="tabla">';
	
	echo '<tbody id="listadoPresupuestos" name="listadoPresupuestos"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->


<!-- ORDEN DE TRABAJO -->
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

<form id="formImprimirOT" name="formImprimirOT" method="post"  target="_blank" action="imprimirOtGenerico.php">
	<input type="hidden" id="imprimirNumOT" name="imprimirNumOT" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirOT"></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>


<form id="formExcelPresupuestosListado" method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarPresupuestosListado.php">
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
<script  src="js/js_presupuestosListado.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	var permisos=0;
	permisos = <?php echo $_SESSION["permiso_presupuestos"]  ?>

	var idUsuario = <?php echo $_SESSION["idEmpleado"]  ?>

	<?php 
		if (isset($_SESSION["presupuestoListado_texto"]))
		{
			
			echo ('document.getElementById("buscarTexto").value = "'.trim($_SESSION["presupuestoListado_texto"]).'";');

			if (trim($_SESSION["presupuestoListado_queBusca"])=="presupuesto")
			{
				echo ('document.getElementById("tipoBusquedaPresupuesto").checked=true;');
			}
			else if (trim($_SESSION["presupuestoListado_queBusca"])=="cliente")
			{
				echo ('document.getElementById("tipoBusquedaPresupuesto").checked=true;');
			}
			else 
			{
				echo ('document.getElementById("tipoBusquedaCampana").checked=true;');
			}

			
			if ($_SESSION["presupuestoListado_Bajada"]=="true")
			{
				echo ('document.getElementById("busqBajada").checked=true;');
			}
			if ($_SESSION["presupuestoListado_Abierta"]=="true")
			{
				echo ('document.getElementById("busqAbierta").checked=true;');
			}

			echo ('document.getElementById("buscarNumMeses").value = "'.trim($_SESSION["presupuestoListado_meses"]).'";');

			echo ('document.getElementById("columnas").value = "'.trim($_SESSION["presupuestoListado_orden"]).'";');			
			
			if ($_SESSION["presupuestoListado_Desc"]=="asc")
			{
				echo ('document.getElementById("ordenDescendiente").checked=false;');
			}
			else
			{
				echo ('document.getElementById("ordenDescendiente").checked=true;');
			}
			
			
		}
	?>

	
	cargarListadoPresupuesto1();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
	/*$(function() {
  $("table").stickyTableHeaders();
});*/
</script>