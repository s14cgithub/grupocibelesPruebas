

<?php 

session_start(); 
$_SESSION['titulo']="P.F. PENDIENTES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	/*echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#provisionFondoManualModal" data-whatever="@mdo">Manual</button>';
	
	echo '<table align="center" class="tabla">';
	
	//echo '<tr><td colspan="11" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listadoPFpendientes" name="listadoPFpendientes"></tbody>';	
	
	echo '</table>';*/
	
	
}
else
{
	header('Location: index.php');	
}

?>



<table align="center">
	<tr>
		<td align="center" colspan="6">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="codigo">idCliente</option>
				<!--<option value="fechaCreacion">Fecha Creacion</option>-->
				<option value="nombre_franqueo">Franqueo</option>	
				<option value="importe">Importe</option>
				<option value="presupuesto" selected>Presupuesto</option>
				<option value="tipoNombre">Tipo</option>				
										
			</select>

		Texto:
		<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		Orden: 
		<select class="" id="ordenBuscar">
				<option value="clayma">Clayma</option>
				<option value="idCliente">Cliente</option>
				<option value="fechaCreacion">Fecha Creacion</option>
				<option value="nombre_franqueo">Franqueo</option>	
				<option value="importe">Importe</option>
				<option value="presupuesto" selected>Presupuesto</option>
				<option value="tipoTexto">Tipo</option>	
		</select>
		Desc: <input type="checkbox" id="ordenDesc"></input>
		
			<br>
			<button type="button" class="btn btn-info" onClick="cargarListadoPFpendientes()">BUSCAR</button>
			<!--<button type="button" class="btn btn-info" onClick="imprimirInformeProvision()">IMPRIMIR</button>-->
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#provisionFondoManualModal" data-whatever="@mdo">Manual</button>

		</td>		
	</tr>

	<tr><td colspan="6"><hr></td></tr>
</table>





<table align="center" class="tabla">
	
	<tbody id="listadoPFpendientes" name="listadoPFpendientes"></tbody>	
	
</table>








</div> <!-- class="tabla" -->



<!--PROVISIONES DE FONDO MANUAL -->
<div class="modal fade" id="provisionFondoManualModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">P.F. MANUAL - CORREO DIARIO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                     
		<!--<div class="form-group">
            <label for="recipient-name" class="col-form-label">Ruta:</label>
            <input type="text" class="form-control" id="rutaCarpetaCliente2">
          </div>	-->
		<form>                  
		
		<!--<div class="form-group">
            <label for="recipient-name" class="col-form-label">PRESUPUESTO:</label>
            <select id="presupuestoModal" class="form-control" onChange="pfManual_Presupuesto()"></select>
        </div>-->
			
			

    <div class="form-group" id="pfManual_Clayma">
      <label for="recipient-name" class="col-form-label">clayma:</label>
      <input type="checkbox" id="claymaModal" class="" onchange="cargarListadoClientes()"></input>
    </div>	

			
		<div class="form-group" id="pfManual_Clientes">
            <label for="recipient-name" class="col-form-label">CLIENTE:</label>
            <select id="clientesModal" class="form-control"></select>
        </div>	
		
			
		<div class="form-group">
			<label for="recipient-name" class="col-form-label">TIPO:</label>
			<select id="tipoModal" class="form-control"></select>
        </div>
			
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">IMPORTE:</label>
            <input type="number" class="form-control" id="importeModal">
        </div>
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">CONCEPTO:</label>
            <input type="text" class="form-control" id="conceptoModal">
        </div>
		<!--<div class="form-group">
            <label for="recipient-name" class="col-form-label">FECHA CUADRE:</label>
            <input type="date" class="form-control" id="fechaModal">
        </div>
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">INFORMACION CUADRE:</label>
            <input type="text" class="form-control" id="informacionModal">
        </div>	-->
			
			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="guardarPFmanual()">Aceptar</button>
      </div>
    </div>
  </div>
</div>




<!-- IMPRIMIR PROVISION DE FONDOS -->
<div class="modal fade" id="imprimirProvisionFondoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">IMPRIMIR PROVISION DE FONDO <br><span id="numPresupuesto"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  
		   <form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label"><span id="claymaProvisionFondoModal"></span></label>
           	 
          </div>
        </form>
		  
		   <form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Cliente: <span id="clienteProvisionFondoModal"></span></label>
           	 
          </div>
        </form>
		<form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Importe: <span id="importeProvisionFondoModal"></span></label>            
          </div>		         
        </form>
		
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
		<button type="button" class="btn btn-primary"  onClick="imprimirProvisionDeFondos_presupuesto2()" id="botonImprimirProvision">Imprimir PF</button>
		<button type="button" class="btn btn-primary"  onClick="imprimirPagoACuenta_presupuesto2()" id="botonImprimirProvisionPagoCuenta">Imprimir PAGO A CUENTA</button>
      </div>
    </div>
  </div>
</div>


<!-- CAMBIO DE ORIGEN -->
<div class="modal fade" id="cambioClaymaCibelesModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">CAMBIO CIBELES / CLAYMA <br></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  	
		<form>  
			<div class="form-group"><h5><b>Presupuesto: <span id="presupuestoCambioModal"></span></b></h5></div>
			<div class="form-group">
            <label for="recipient-name" class="col-form-label" style="color: red !important;">Se cambiará el cliente en todas las Provisiones de Fondos y en el Presupuesto </label>           	 
          </div>
        </form>
		<form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Clientes de <span id="clientes1"></span>:</label> 			
          </div>
		<div class="form-group">
           
			<select  for="recipient-name" class="col-form-label" name="clientes" id="clientes"></select>
          </div>
		
        </form>
		
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
		<button type="button" class="btn btn-primary"  onClick="cambiarClienteEnPFClayma()" id="botonImprimirProvision">Cambiar Cliente</button>
		</button>
      </div>
    </div>
  </div>
</div>




<form id="formImprimirProvisionDeFondo" name="formImprimir" method="post"  target="_blank" action="imprimirProvisionDeFondo.php">
	<input type="hidden" id="imprimirProvisionFondoNumPresupuesto" name="imprimirProvisionFondoNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirProvisionFondoContador" name="imprimirProvisionFondoContador" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>

<form id="formImprimirPagoACuenta" name="formImprimir" method="post"  target="_blank" action="imprimirPagoACuenta.php">
	<input type="hidden" id="imprimirPagoACuentaNumPresupuesto" name="imprimirPagoACuentaNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirPagoACuentaNumPresupuestoContador" name="imprimirPagoACuentaNumPresupuestoContador" value=""></input>
	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>


<form id="formImprimirProvisionDeFondoClayma" name="formImprimir" method="post"  target="_blank" action="imprimirProvisionDeFondoClayma.php">
	<input type="hidden" id="imprimirProvisionFondoNumPresupuestoClayma" name="imprimirProvisionFondoNumPresupuestoClayma" value=""></input>
	<input type="hidden" id="imprimirProvisionFondoContadorClayma" name="imprimirProvisionFondoContadorClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>

<form id="formImprimirPagoACuentaClayma" name="formImprimir" method="post"  target="_blank" action="imprimirPagoACuentaClayma.php">
	<input type="hidden" id="imprimirPagoACuentaNumPresupuestoClayma" name="imprimirPagoACuentaNumPresupuestoClayma" value=""></input>
	<input type="hidden" id="imprimirPagoACuentaNumPresupuestoContadorClayma" name="imprimirPagoACuentaNumPresupuestoContadorClayma" value=""></input>
	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>


<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php

echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_provisionFondosPendiente.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	//cargarListadoPFpendientes();
	cargarListadoPFpendientes();
	
	cargarListadoClientes();
	//cargarListadoPresupuestosActivo('presupuestoModal');
	cargarTipoProvisionFondo('tipoModal','');
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>