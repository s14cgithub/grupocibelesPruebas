
<?php 

session_start(); 
$_SESSION['titulo']="PASAR DATOS A FACTURAS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


?>
	
	<table align="center" border="0"  class="">		
		<tr>
			<td align="right">Fecha Inicio:</td>
			<td id="fechaInicio" style="border-right: 2px solid black"></td>
			<td align="right">Fecha Fin:</td>
			<td id="fechaFin" style="border-right: 2px solid black"></td>	
			<td align="right">Fecha Facturacion:</td>
			<td id="fechaFacturacion"></td>	
		</tr>
		<tr><td colspan="6"><hr></td></tr>
		
		
		
		<tr>			
			<td colspan="6" align="center">				
				<!--<button class="btn btn-info" onClick="guardarFacturaEspecial()">Guardar</button>				-->
				<!--<button class="btn btn-info" onClick="limpiarFacturasEspeciales()">Limpiar Datos</button>-->
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#juntarCertRutModal" data-whatever="@mdo"  id="botonJuntarCertRut" >Juntar Certificados, Rutas y gastos adicionales</button>
				<button class="btn btn-info" onClick="crearFacturasMensuales()">Crear Facturas</button>
				
			</td>
		</tr>


	
</table>

<table id="historico1" align="center" class="tabla"></table>

<br>




</div> <!-- class="tabla" -->

<div class="modal fade" id="juntarCertRutModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Copiar Datos Certificados y Rutas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>		
        </button>		  
      </div>
      <div class="modal-body">
        <form>
			
			<div class="form-group">
			  
            <label for="recipient-name" class="col-form-label">Elegir Año:</label>
            <select id="anioModal" class="form-control">
				<option value="2026">2026</option>	
				<option value="2025" selected>2025</option>	
				<option value="2024">2024</option>
				<option value="2023">2023</option>
			</select>
          </div>
			
          <div class="form-group">
			  
            <label for="recipient-name" class="col-form-label">Elegir Mes:</label>
            <select id="mesModal" class="form-control">
				<option value="1">Enero</option>
				<option value="2">Febrero</option>
				<option value="3">Marzo</option>
				<option value="4">Abril</option>
				<option value="5">Mayo</option>
				<option value="6">Junio</option>
				<option value="7">Julio</option>
				<option value="8">Agosto</option>
				<option value="9">Septiembre</option>
				<option value="10">Octubre</option>
				<option value="11">Noviembre</option>
				<option value="12">Diciembre</option>
			  
			</select>
          </div>
			<!--
			 <div class="form-group">
			  
            <label for="recipient-name" class="col-form-label">Elegir Fecha de Facturacion:</label>
            <input type="date" class="form-control" id="fechaModal">
          </div>
			-->
          <!--<div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onClick="copiarCertRut()">Copiar Datos</button>
      </div>
    </div>
  </div>
</div>



<?php


echo ("</div>");
echo ("</body>");
echo ("</html>");

?>


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_facturasEspecialesAfacturas.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">
	cargarFacturasEspecialesTemporal();
</script>

