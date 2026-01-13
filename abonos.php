

<?php 

session_start(); 
$_SESSION['titulo']="ABONOS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	<table border ="0" align="center">
		
			<tr>
				<td align="right">Año:</td>
				<td><select class=""  id="anioSeleccionado" name="anioSeleccionado" onChange="buscarFactura()"></select></td>
				
				<td align="right">Clayma:</td>
				<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionDeCargarClientesListado('buscarCliente')" style="" > </input></td>
			</tr>
</table>




<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="numero">Abono</option>				
				<option value="cliente">Cliente</option>
				<option value="factura">Factura</option>
				<option value="precioTotal">Total</option>				
				<option value="aPagar">Total a Pagar</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
					<option value="numero">Abono</option>				
					<option value="cliente">Cliente</option>
					<option value="factura">Factura</option>
					<option value="fecha">Fecha Factura</option>
					<option value="fechaPago">Fecha Pago</option>
					<option value="precioTotal">Total</option>				
					<option value="aPagar">Total a Pagar</option>
									
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelAbonosCibeles()" value="Excel" ></input>
			<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoNumerosFacturasModal" data-whatever="@mdo">Imprimir</button>-->


		</td>	
	</tr>
	<tr>
		<td align="center">Fecha Inicio:
			<input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> Fecha Fin:<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		</td>

		
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>





	<?php

	
	echo '<table align="center" class="tabla" style="border-color:grey;">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listadoFacturasSinEmitir" name="listadoFacturasSinEmitir"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->



<!-- CREAR NUEVO ABONO -->
<div class="modal fade" id="crearAbonoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">Nuevo Abono</h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Factura: <span id="facturaModal"></span></label>           
          			</div>
		 			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente: <span id="clienteModal"></span></label>            
          			</div>
		  			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Total: <span id="totalModal"></span></label>            
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">A pagar: <span id="aPagarModal"></span></label>            
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Forma de Pago: <span id="formaPagoModal"></span></label>            
          			</div>
					<!--<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha:</label>
						<input type="date" class="form-control" id="fechaModal">
						<label for="recipient-name" class="col-form-label"><font color="#FF0000"><span id="fechaMensaje"></span></font></label>
          			</div>-->
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="comprobarDatosAbonoFactura()">Crear Abono</button>
      		</div>
    	</div>
  	</div>
</div>

<!-- OBSERVACION DE LA FACTURA -->
<div class="modal fade" id="observacionFacturaModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">ABONO: <span id="numFacturaModal"></span><span style="visibility: hidden; display: none;" id="claymaModal"></span></h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Observaciones</label>						
						<textarea maxlength="250" rows="5" class="form-control" id="observacionModal"></textarea>
          			</div>		 			
        		</form>
      		</div>
			<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Observaciones Internas</label>						
						<textarea maxlength="250" rows="5" class="form-control" id="observacionInternaModal"></textarea>
          			</div>		 			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="modificarObservacionAbono()">Modificar</button>
      		</div>
    	</div>
  	</div>
</div>


<!-- IMPRIMIR VARIAS FACTURAS POR RANGO DE NUMERO DE FACTURA -->
<div class="modal fade" id="imprimirRangoNumerosFacturasModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">Imprimir Facturas: </h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Primer Numero de Factura</label>						
						<input type="number" class="form-control" id="primeraFacturaImprmirModal"></input>
          			</div>		 			
        		</form>
				<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Ultimo Numero de Factura</label>						
						<input type="number" class="form-control" id="ultimaFacturaImprmirModal"></input>
          			</div>		 			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirFacturaRango()">Imprimir</button>
      		</div>
    	</div>
  	</div>
</div>




<!--<form id="formImprimirFactura"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirNumFactura" name="imprimirNumFactura" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>
<form id="formImprimirFacturaRango"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirPrimeraFactura" name="imprimirPrimeraFactura" value=""></input>
	<input type="hidden" id="imprimirUltimaFactura" name="imprimirUltimaFactura" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirFacturaClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirNumFacturaClayma" name="imprimirNumFacturaClayma" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirFacturaRangoClayma"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirPrimeraFacturaClayma" name="imprimirPrimeraFacturaClayma" value=""></input>
	<input type="hidden" id="imprimirUltimaFacturaClayma" name="imprimirUltimaFacturaClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>-->

<form id="formImprimirAbono"  method="post"  target="_blank" action="imprimirAbono.php">
	<input type="hidden" id="imprimirNumFacturaAbono" name="imprimirNumFacturaAbono" value=""></input>	
	<input type="hidden" id="imprimirAnioSeleccionado" name="imprimirAnioSeleccionado" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbono"></input>	
</form>

<form id="formImprimirAbonoClayma"  method="post"  target="_blank" action="imprimirAbonoClayma.php">
	<input type="hidden" id="imprimirNumFacturaAbonoClayma" name="imprimirNumFacturaAbonoClayma" value=""></input>	
	<input type="hidden" id="imprimirAnioSeleccionadoClayma" name="imprimirAnioSeleccionadoClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbonoClayma"></input>	
</form>



<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarAbonosExcel.php">	
	<input type="hidden" id="exportarClayma" name="exportarClayma" value=""></input>
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>
	<input type="hidden" id="exportarAnioSeleccionado" name="exportarAnioSeleccionado" value=""></input>
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
</form>



<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
	<!--<img src="imagenes/chevron-up-solid.svg" />-->
</div>

<?php







echo ("</div>");
echo ("</body>");
echo ("</html>");

?>
<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_abonos.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	cargarAnios("anioSeleccionado");

	document.getElementById("anioSeleccionado").value = 2025;
	
	buscarFactura();
	
	document.getElementById("button-up").addEventListener("click", scrollUp);	
	
</script>