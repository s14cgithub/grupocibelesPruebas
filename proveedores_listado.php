


<?php 

session_start(); 
$_SESSION['titulo']="PROVEEDORES LISTADO";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	if($_SESSION["permiso_proveedores"] == 2)
	{
		echo '<button type="button" class="btn btn-info"  class="btn btn-info" data-toggle="modal" data-target="#nuevoProveedorModal" data-whatever="@mdo" style="float: left">Nuevo</button>';
	}
	?>


	

	<table border ="0" align="center">
		
		<tr>
			<td align="right">Solo Homologados:</td>
			<td align="left"><input type="checkbox" id="proveedorHomologado" name="proveedorHomologado" value="" onchange="" style="" > </input></td>
			


		</tr>
	
	</table>





	<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="cp">CP</option>
				<option value="direccion">Direccion</option>
				<option value="proveedor" selected >Proveedor</option>
				<option value="servicio">Servicio</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">				
				<option value="cp">CP</option>
				<option value="direccion">Direccion</option>
				<option value="id" >Id</option>
				<option value="proveedor" selected >Proveedor</option>
				<option value="servicio">Servicio</option>
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc"></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelProveedores()" value="Excel" ></input>-->
			


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
        		<h5 class="modal-title" id="ModalLabel">PROVEEDOR NUEVO</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Nombre Proveedor:</label>
						<input class="form-control" type="text" id="proveedorNuevo_nombre"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) NIF:</label>
						<input class="form-control" type="text" id="proveedorNuevo_nif"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Servicio:</label>
						<input class="form-control" type="text" id="proveedorNuevo_servicio"></input>
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Direccion: </label>
						<input class="form-control" type="text" id="proveedorNuevo_direccion"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">(*) Localidad: </label>
						<input class="form-control" type="text" id="proveedorNuevo_localidad"></input>
          			</div>	
			
					<div class="form-group">
						<hr>
            			<label for="recipient-name" class="col-form-label">(*) Provincia: </label>
						<input class="form-control" type="text" id="proveedorNuevo_provincia"></input>
					</div>
					<div class="form-group">				
						<label for="recipient-name" class="col-form-label">(*) CP: </label>
						<input class="form-control" type="text" id="proveedorNuevo_cp"></input>
					</div>						
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Precio Comparado:</label>
						<input class="form-control" type="text" id="proveedorNuevo_precioComparado"></input>
					</div>
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">(*) Telefono:</label>
						<input class="form-control" type="text" id="proveedorNuevo_Telefono"></input>
					</div>
          			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="crearProveedor()">Crear Proveedor</button>
      		</div>
    	</div>
	</div>
</div> 



<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php




echo ("</div>");
echo ("</body>");
echo ("</html>");

?>
<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_proveedoresListado.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>