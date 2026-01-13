<?php 

session_start(); 
$_SESSION['titulo']="ADMINISTRACION";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

//require($ruta."Archivos Comunes/constantes.php");
require($ruta."Archivos Comunes/cabecera.php");



if ($_SESSION["permiso_administracion_contabilidad"] == 1 || $_SESSION["permiso_administracion_contabilidad"] == 2)
{
	echo '<button type="button" class="btn btn-info" onClick="location.href = \'admContabilidad.php\'">CONTABILIDAD</button>';
}

if ($_SESSION["permiso_administracion_facturacion"] == 1 || $_SESSION["permiso_administracion_facturacion"] == 2)
{
	echo '<button type="button" class="btn btn-info" onClick="location.href = \'admFacturacion.php\'">FACTURACION</button>';
}

?>












<!--<button type="button" class="btn btn-info" onClick="location.href = 'admProvisionFondos.php'">PROVISION DE FONDOS</button>
<button type="button" class="btn btn-info" onClick="location.href = 'admProvisionFondoPendientes.php'">P.F. PENDIENTES</button>
<button type="button" class="btn btn-info" onClick="location.href = 'admEmisionFacturasPendientes.php'">FACTURAS SIN GENERAR</button>
<button type="button" class="btn btn-info" onClick="location.href = 'admFacturasSinCobrar.php'">FACTURAS SIN COBRAR</button>
<button type="button" class="btn btn-info" onClick="location.href = 'traspasoCorreosAcibeles.php'">CORREOS A CIBELES</button>
<button type="button" class="btn btn-info" onClick="location.href = 'admFacturasCorreos.php'">FAC. CORREOS</button>
<button type="button" class="btn btn-info" onClick="location.href = 'admFacturasCorreosPendientes.php'">FAC. CORREOS PENDIENTES</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#libroContabilidadModal" data-whatever="@mdo">LIBRO DE CONTABILIDAD</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportarSageModal" data-whatever="@mdo">EXPORTAR A SAGE</button>
<button type="button" class="btn btn-info" onClick="location.href = 'clientes.php'">CLIENTES</button>
<button type="button" class="btn btn-info" onClick="location.href = 'clienteNuevo.php'">NUEVO CLIENTE</button>-->





<span style="float: none; width: 100%"><br>&nbsp;</span>

</div> <!-- class="tabla" -->

<!--LIBRO DE CONTABILIDAD -->
<div class="modal fade" id="libroContabilidadModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">LIBRO DE CONTABILIDAD</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
		<form> 
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">FECHA INICIO:</label>
            <input type="date" class="form-control" id="fechaInicioModal">
        </div>
			
			
		<div class="form-group">
           <label for="recipient-name" class="col-form-label">FECHA FIN:</label>
            <input type="date" class="form-control" id="fechaFinModal">
        </div>			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirLibroContrabilidad()">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<!--EXPORTACION A SAGE -->
<div class="modal fade" id="exportarSageModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">EXPORTAR A SAGE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
		<form> 
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">FECHA INICIO:</label>
            <input type="date" class="form-control" id="fechaInicioSageModal">
        </div>
			
			
		<div class="form-group">
           <label for="recipient-name" class="col-form-label">FECHA FIN:</label>
            <input type="date" class="form-control" id="fechaFinSageModal">
        </div>			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="exportarAsage()">generarTxt</button>
      </div>
    </div>
  </div>
</div>




<form id="formImprimirLibroContabilidad" name="formImprimirLibroContabilidad" method="post"  target="_blank" action="imprimirLibroContabilidad.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="libroConta"></input>
	<input type="hidden" id="fechaInicio" name="fechaInicio" value=""></input>	
	<input type="hidden" id="fechaFin" name="fechaFin" value=""></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>

<a href="archivosDescargas/exportarFacturas.txt" download="cibelesAsage.txt" id="cibelesAsage" style="visibility: hidden">button</a>

<?php







echo ("</div>");
echo ("</body>");
echo ("</html>");

?>