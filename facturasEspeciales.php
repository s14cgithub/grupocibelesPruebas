
<?php 

session_start(); 
$_SESSION['titulo']="GASTOS ADICIONALES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


?>
	
	<table align="center" border="0"  class="">		
		<tr>
			<!--<td align="right">Numero del Cliente:</td>
			<td>
				<input id="numCliente" type="number" onChange="mirarDatosEmpresaPorCodigo2(this.value)"></input>
			</td>-->
			

			<!--<td align="right">Nombre:</td>
			<td>
				<input id="nombreCliente" type="text" readonly></input>
			</td>-->
			<td align="right">Nombre:</td>
			<td>
				<select id="nombreCliente"></select>
			</td>
			
		</tr>		
		
		<tr>
			<td align="right">Orden de Trabajo:</td>
			<td>
				<input id="ordenTrabajo" type="text" value="CD"></input>
			</td>
			<td align="right">Fecha:</td>
			<td>
				<input type="date" id="fecha"></input>
			</td>
		</tr>
		<!--<tr>
			<td align="right">Descripcion:
			</td>
			<td colspan="3">
				<input type="text" id="descripcion" style="width: 100%;"></input>
			</td>
			
		</tr>-->
		<tr>
			<td align="right">Concepto Especial:
			</td>
			<td colspan="3">
				<input type="text" id="concepto" style="width: 100%;"></input>
			</td>
		</tr>
		<tr>			
			<td align="right">Unidades:</td>
			<td>
				<input type="text" id="unidades" style="width: 100%;"></input>
			</td>
			<td align="right">Importe Unitario:</td>
			<td>
				<input type="number" id="importe" style="width: 100%;"></input>
			</td>
		</tr>
		

		<tr>			
			<td colspan="4" align="center">				
				<button class="btn btn-info" onClick="guardarFacturaEspecial()">Guardar</button>				
				<button id="botonGuardar"  class="btn btn-info"  data-toggle="modal" data-target="#gastosAdicionalesInformeModal" data-whatever="@mdo">Informe</button>
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
			  
            <label for="recipient-name" class="col-form-label">Elegir Mes:</label>
            <select id="mesModal" class="form-control" id="numPresuCopiar">
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
			
			 <div class="form-group">
			  
            <label for="recipient-name" class="col-form-label">Elegir Fecha de Facturacion:</label>
            <input type="date" class="form-control" id="fechaModal">
          </div>
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

<!-- INFORME GASTOS ADICIONALES -->
<div class="modal fade" id="gastosAdicionalesInformeModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">GASTOS ADICIONALES</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Clientes:</label>
						<select id="clienteModal"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="" type="date" id="fechaInicioModal"></input>
          			</div>	
				<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="" type="date" id="fechaFinModal"></input>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeGastosAdionales()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div> 

<form id="formImprimirGastosAdicionales"  method="post"  target="_blank" action="imprimirInformeGastosAdicionales.php">
	<input type="hidden" id="imprimirIdCliente" name="imprimirIdCliente" value=""></input>
	<input type="hidden" id="imprimirFechaInicio" name="imprimirFechaInicio" value=""></input>
	<input type="hidden" id="imprimirFechaFin" name="imprimirFechaFin" value=""></input>
	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInformeGastosAdicionales"></input>	
</form>



<?php


echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_facturasEspeciales.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">
	
	idInputListado="nombreCliente";
	cargarListadoNombreFranqueo();
	idInputListado="";
	
	idInputListado = 'clienteModal';
	cargarListadoNombreFranqueo();
	idInputListado="";
	
	
	cargarFacturasEspeciales();
</script>