

<?php 

session_start(); 
$_SESSION['titulo']="GRABAR CERTIFICADOS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


?>
	
	<table align="center" border="0"  class="">
		<tr>
			<td align="right">
				Fecha:
			</td>
			<td>
				<input type="date" id="fechaFranqueo" onChange="document.getElementById('listadoNombreFranqueo').focus()"></input>
			</td>
		</tr>
		<tr>
			<td align="right">
				Nombre de Franqueo:
			</td>
			<td>
				<select id="listadoNombreFranqueo" onChange="document.getElementById('unidades').focus()"></select>
			</td>
		</tr>
		
		<tr>
			<td align="right">
				Unidades:
			</td>
			<td>
				<input type="number" id="unidades" name="unidades" onChange="document.getElementById('listadoProducto').focus()"></input>
			</td>
		</tr>

		<tr>
			<td align="right">
				Descripcion:
			</td>
			<td>
				<select id="listadoProducto" onChange="document.getElementById('botonGuardar').focus()"></select>
			</td>
		</tr>

		<tr>			
			<td colspan="2" align="center">
				<button id="botonGuardar"  class="btn btn-info"  onClick="guardarCertificado()">Guardar</button>
				<button id="botonGuardar"  class="btn btn-info"  data-toggle="modal" data-target="#certificadoInformeModal" data-whatever="@mdo">Informe</button>
			</td>
		</tr>
		
	</table>

	<table id="historico1" align="center" class="tabla"></table>
	<br>


</div> <!-- class="tabla" -->


<!-- INFORME CERTIFICADOS -->
<div class="modal fade" id="certificadoInformeModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">CERTIFICADOS</h5>
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
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeCertificados()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div> 

<form id="formImprimirCertificados"  method="post"  target="_blank" action="imprimirInformeCertificados.php">
	<input type="hidden" id="imprimirIdCliente" name="imprimirIdCliente" value=""></input>
	<input type="hidden" id="imprimirFechaInicio" name="imprimirFechaInicio" value=""></input>
	<input type="hidden" id="imprimirFechaFin" name="imprimirFechaFin" value=""></input>
	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInformeCertificado"></input>	
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
<script  src="js/js_certGrabar.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	cargarCertificadoProductos();
	idInputListado = "listadoNombreFranqueo";
	cargarListadoNombreFranqueo();	
	idInputListado="";
	
	cargarCertificados();
	
	//cargarClientes('A','clienteModal');
	
	idInputListado = 'clienteModal';
	cargarListadoNombreFranqueo();
	idInputListado="";

	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>