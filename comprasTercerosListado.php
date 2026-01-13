


<?php 

session_start(); 
$_SESSION['titulo']= "COMPRAS A TERCEROS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	if($_SESSION["permiso_comprasAterceros"] == 2)
	{
		echo '<button type="button" class="btn btn-info"  class="btn btn-info" data-toggle="modal" onclick="gestionAbrirPedidoNuevo()" data-whatever="@mdo" style="float: left">Nuevo</button>';
	}
	
	
	
	?>
	

	




	<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">					
				<option value="t2.cliente">Cliente</option>
				<option value="t3.proveedor" >Proveedor</option>
				<option value="t1.pedido" selected>Pedido</option>
				<option value="t1.presupuesto">Presupuesto</option>
				<option value="t4.descripcion">Productos</option>
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">				
				<option value="t1.fechaFacturaCompra">Fecha</option>				
				<option value="t2.cliente">Cliente</option>
				<option value="importe">Importe</option>
				<option value="t3.proveedor" >Proveedor</option>
				<option value="t1.pedido" selected>Pedido</option>
				<option value="t1.presupuesto">Presupuesto</option>
				<option value="t4.descripcion">Productos</option>
				
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelProveedores()" value="Excel" ></input>-->
			


		</td>	
	</tr>
	<tr>
		<td align="right">
			Excluir presupuestos Internos: <input type="checkbox" id="exluirInternos"></input>
			&nbsp;Anuales: <input type="checkbox" id="anuales"></input>
			&nbsp;Activos: <input type="checkbox" id="activos"></input>
		</td>
	</tr>

	<tr><td colspan=""><hr></td></tr>
</table>



	<?php
	
	echo '<table align="center" class="tabla">';
	echo '<tbody id="listadoProveedores" name="listadoProveedores"></tbody>';	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->


<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarProveedoresExcel.php">	
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
</form>


<!--NUEVO PROVEEDOR-->
<div class="modal fade" id="nuevoProveedorModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">PEDIDO NUEVO</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
				<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Copiar del pedido:</label>
						<input type="checkbox" class="" id="copiarPedidoCk_modal" onchange="gestionCopiarPedido()"></input>
						<input type="number" class="" id="copiarPedidoTxt_modal" disabled></input>
						<input type="button" class="btn btn-primary" id="copiarPedidoBtn_modal" onclick="copiarPedido()" value="Copiar" disabled></input>
          		</div>
				<hr>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Anual:</label>
						<input type="checkbox" class="" id="predidoNuevo_anual"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Comercial:</label>
						<select class="form-control" id="comercial"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Presupuesto:</label>
						<input class="form-control" type="text" id="pedidoNuevo_presupuesto"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Proveedor:</label>
						<select class="form-control" id="proveedores"></select>
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Forma de Pago:</label>
						<select class="form-control" id="pedido_formaPago"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Contacto Proveedor: </label>
						<input class="form-control" type="text" id="proveedorNuevo_contactorP"></input>
          			</div>					
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="crearCompra()">Crear Compra</button>
      		</div>
    	</div>
	</div>
</div> 

<form id="formImprimirCompraTercero"  method="post"  target="_blank" action="imprimirCompraTercero.php">
	<input type="hidden" id="imprimirNumeroCompra" name="imprimirNumeroCompra" value=""></input>	
	<input type="hidden" id="imprimirPrevisualizacion" name="imprimirPrevisualizacion" value="">	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirCompra"></input>	
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
<script  src="js/js_comprasTercerosListado.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	
	buscarFactura();
	
	cargarPresupuestadores("comercial");
	cargarProveedores();
	cargarFormasDePagoCompraAterceros();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>