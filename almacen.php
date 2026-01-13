

<?php 

session_start(); 
$_SESSION['titulo']="ALMACEN - MOVIMIENTOS";
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
				<option value="cantidad">Cantidad</option>	
				<option value="t2.subCliente">Cliente</option>	
				<option value="t3.codigo" selected>Codigo</option>	
				<option value="hueco">Hueco</option>
				<option value="modalidad">Modalidad</option>
				<option value="ot">Ot</option>
				<option value="t3.nombre">Producto</option>
				
				
						
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
				<option value="cantidad">Cantidad</option>	
				<option value="t2.subCliente">Cliente</option>	
				<option value="t3.codigo" >Codigo</option>	
				<option value="fecha" >Fecha</option>
				<option value="hueco">Hueco</option>
				<option value="id" selected>Id</option>	
				<option value="modalidad">Modalidad</option>
				<option value="ot">Ot</option>
				<option value="t3.nombre">Producto</option>			
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			
			<?php 
				if ($_SESSION["permiso_almacen_Nuevo"] == 2)
				{
					echo ('<button type="button" class="btn btn-info" data-toggle="modal" data-target="#crearNuevoMovimientoModal" data-whatever="@mdo">NUEVO</button>');

				}
	
			?>

			
			<!--<button type="button" class="btn btn-info" onClick="generarExcel()">EXCEL</button>-->
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#excelAlbaranModal" data-whatever="@mdo">EXCEL</button>
			

			<?php 
				if ($_SESSION["permiso_almacen_Albaran"] == 2)
				{
					echo ('<button type="button" class="btn btn-info" onClick="gestionAlbaran2()">ALBARAN</button>	');

				}
			?>


<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoNumerosFacturasModal" data-whatever="@mdo">Imprimir</button>-->


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
	
	echo '<tbody id="listadoMovimientosAlmacen" name="listadoMovimientosAlmacen"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->



<!-- CREAR NUEVO MOVIMIENTO -->
<div class="modal fade" id="crearNuevoMovimientoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">Nuevo Movimiento</h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
					<div class="form-group">
            			<label class="col-form-label">Almacen:</label>    
						<select class="" id="almacenModal"></select>
						<label  class="col-form-label">Fecha:</label>    
						<input type="date" class="" id="fechaModal"></input>
          			</div>
         			<div class="form-group">
            			
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Cliente:</label>
						<select class="form-control" id="clienteModal" onChange="cargarListadoProductos()"></select>
          			</div>
		 			<div class="form-group">
            			<label class="col-form-label">Producto:</label> 
						<select class="form-control" id="productoModal" onChange="gestionHuecos()"></select>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Modalidad:</label>  
						<select class="form-control" id="modalidadModal" onChange="gestionHuecos()"></select>
          			</div>		  			
					
					<div class="form-group">
            			<label class="col-form-label">Hueco: </label>  
						<select class="form-control" id="huecoModal" onChange="verCantidadHueco()"></select>						
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Cantidad: </label> 
						<input type="number" class="form-control" id="cantidadModal"></input>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Ot: </label> 
						<input type="text" class="form-control" id="otModal"></input>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Observaciones:</label>    
						<textarea maxlength="250" rows="5" class="form-control" id="observacionModal"></textarea>
          			</div>
					
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="crearMovimientoAlmacen()">Crear</button>
      		</div>
    	</div>
  	</div>
</div>



<!-- CREAR NUEVO ALBARAN -->
<div class="modal fade" id="crearNuevoAlbaranModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">Nuevo Albarán</h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label class="col-form-label">Observaciones:</label>    
						<textarea maxlength="250" rows="5" class="form-control" id="observacionAlbaranModal"></textarea>
          			</div>
					
        		</form>
				
				<form>					
					<div class="form-group">
						<h5>&nbsp;</h5>
            			<h5>Direccion de Envío</h5>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Empresa:</label>    
						<input type="text" class="form-control" id="empresaModal"></input>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Dirección:</label>    
						<input type="text" class="form-control" id="direccionModal"></input>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">CP:</label>    
						<input type="text" class="form-control" id="cpModal"></input>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Localidad:</label>    
						<input type="text" class="form-control" id="localidadModal"></input>
          			</div>
					<div class="form-group">
            			<label class="col-form-label">Provincia:</label>    
						<input type="text" class="form-control" id="provinciaModal"></input>
          			</div>
					
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="grabarAlbaranAlmacen()">Crear</button>
      		</div>
    	</div>
  	</div>
</div>



<!-- EXCEL -->
<div class="modal fade" id="excelAlbaranModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">ALMACEN - EXCEL</h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
						<input type="radio" id="radioExcelTodo" name="fav_language" value="Todo"  checked="checked">
						<label for="radioExcelTodo">Todo</label><br>
						<input type="radio" id="radioExcelUbicaciones" name="fav_language" value="Ubicaciones">
						<label for="radioExcelUbicaciones">Ubicaciones</label><br>
						<input type="radio" id="radioExcelArticulos" name="fav_language" value="Articulos">
						<label for="radioExcelArticulos">Articulos</label>
					</div>
				</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="generarExcel()">EXCEL</button>
      		</div>
    	</div>
  	</div>
</div>



<form id="formImprimirExcelAlmacen" method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarInformeMovimientoAlmacen.php">
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>
	<input type="hidden" id="formExcelCondicion" name="formExcelCondicion" value=""></input>	
	<input type="hidden" id="formExcelTipo" name="formExcelTipo" value=""></input>
</form>

<form id="formImprimirAlbaran" method="post"  target="_blank" action="imprimirAlbaranAlmacen.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAlbaran"></input>
	<input type="hidden" id="formNumAlbaran" name="formNumAlbaran" value=""></input>
	<input type="hidden" id="formAlbaranEmpresa" name="formAlbaranEmpresa" value=""></input>
	<input type="hidden" id="formAlbaranDireccion" name="formAlbaranEmpresa" value=""></input>
	<input type="hidden" id="formAlbaranCP" name="formAlbaranCP" value=""></input>
	<input type="hidden" id="formAlbaranLocalidad" name="formAlbaranLocalidad" value=""></input>
	<input type="hidden" id="formAlbaranProvincia" name="formAlbaranProvincia" value=""></input>

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
<script  src="js/js_almacen.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	
	document.getElementById("fechaModal").valueAsDate =new Date();
	buscarFactura();
	
	/*idInputListado="proveedorModal";
	cargarListadoProveedoresAlmacen();*/
	
	cargarSubClientes("A","clienteModal");
	
	cargarListadoProductos();
	cargarListadoModalidadAlmacen();
	cargarListadoAlmacenesAlmacen();
	cargarListadoHuecosAlmacen();
	
	
	gestionHuecos();
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>