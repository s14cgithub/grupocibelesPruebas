
<?php 

session_start(); 
$_SESSION['titulo']="CONTABILIDAD";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

//require($ruta."Archivos Comunes/constantes.php");
require($ruta."Archivos Comunes/cabecera.php");



if ($_SESSION["permiso_administracion_contabilidad_domiciliados"] == 1 || $_SESSION["permiso_administracion_contabilidad_domiciliados"] == 2)
{  
  echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#domiciliadosModal" data-whatever="@mdo">DOMICILIADOS</button>
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#aCompensarModal" data-whatever="@mdo">A COMPENSAR</button>

  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportarSageModal" data-whatever="@mdo">EXPORTAR A SAGE CIBELES</button>
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportarSageCorreosModal" data-whatever="@mdo">EXPORTAR A SAGE CORREOS</button>
  '; 
}
else
{
  echo '
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#libroContabilidadModal" data-whatever="@mdo">LIBRO DE CONTABILIDAD</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#domiciliadosModal" data-whatever="@mdo">DOMICILIADOS</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#aCompensarModal" data-whatever="@mdo">A COMPENSAR</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportarSageModal" data-whatever="@mdo">EXPORTAR A SAGE CIBELES</button>
  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportarSageCorreosModal" data-whatever="@mdo">EXPORTAR A SAGE CORREOS</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ajusteSaldoModal" data-whatever="@mdo">AJUSTES DE SALDO</button>

<button type="button" class="btn btn-info" data-toggle="modal" data-target="#cobroMasivoDomiciliadoM" data-whatever="@mdo">COBRO MASIVO DOMICILIADOS</button>
  ';
}




?>





<!--<button type="button" class="btn btn-info" onClick="location.href = 'comprasTercerosContabilidad.php'">COMPRAS A TERCEROS</button>-->


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
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirLibroContabilidad()">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!--DOMICILIADOS -->
<div class="modal fade" id="domiciliadosModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">DOMICILIADOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
		<form> 
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">FECHA INICIO:</label>
				<input type="date" class="form-control" id="fechaInicioDomModal">
			</div>
			
			
			<div class="form-group">
			    <label for="recipient-name" class="col-form-label">FECHA FIN:</label>
				<input type="date" class="form-control" id="fechaFinDomModal">
			</div>	
			
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Cliente:</label>
				<input type="text" class="form-control" id="numClienteDomiciliadoModal">
        	</div>	
			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirDomiciliados()">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<!--A COMPENSAR -->
<div class="modal fade" id="aCompensarModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">A COMPENSAR</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
		<form> 
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">FECHA INICIO:</label>
            <input type="date" class="form-control" id="fechaInicioAComModal">
        </div>
			
			
		<div class="form-group">
           <label for="recipient-name" class="col-form-label">FECHA FIN:</label>
            <input type="date" class="form-control" id="fechaFinAComModal">
        </div>			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirACompensar()">Aceptar</button>
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
            <label for="recipient-name" class="col-form-label">CLAYMA:</label>
            <input type="checkbox" class="form-control" id="claymaSageModal">
        </div>
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

<!--EXPORTACION A SAGE CORREOS-->
<div class="modal fade" id="exportarSageCorreosModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
            <input type="date" class="form-control" id="fechaInicioSageCorreosModal">
        </div>
			
			
		<div class="form-group">
           <label for="recipient-name" class="col-form-label">FECHA FIN:</label>
            <input type="date" class="form-control" id="fechaFinSageCorreosModal">
        </div>			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="exportarAsageCorreos()">generarTxt</button>
      </div>
    </div>
  </div>
</div>



<!--AJUSTES SALDOS  -->
<div class="modal fade" id="ajusteSaldoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">AJUSTAR SALDO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
		<form> 
			<div class="form-group">
           		<label for="recipient-name" class="col-form-label">Clientes:</label>
				<select id="clienteAjusteModal" class="form-control"></select>
          	</div>	
			<!--<div class="form-group">
				<label for="recipient-name" class="col-form-label">FECHA:</label>
				<input type="date" class="form-control" id="fechaAjusteModal">
			</div>-->

			<div class="form-group">
				<label for="recipient-name" class="col-form-label">IMPORTE:</label>
				<input type="number" class="form-control" id="importeAjusteModal">
			</div>

			<div class="form-group">
				<label for="recipient-name" class="col-form-label">CONCEPTO:</label>
				<input type="text" class="form-control" id="conceptoAjusteModal">
			</div>
			<div class="form-group">
				<label for="recipient-name" class="col-form-label"><font color="#FF0004"><b>El importe se sumará al saldo del cliente seleccionado</font></b></label>
				
			</div>
				
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="gestionAjustarSaldo()">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<!--COBROS MASIVOS DOMICILIADOS -->
<div class="modal fade" id="cobroMasivoDomiciliadoM" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">COBRO MASIVO PARA DOMICILIADOS</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">		
				<form> 
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">FECHA INICIO:</label>
						<input type="date" class="form-control" id="fechaInicioCobMasModal">
					</div>
			
			
					<div class="form-group">
			    		<label for="recipient-name" class="col-form-label">FECHA FIN:</label>
						<input type="date" class="form-control" id="fechaFinCobMasModal">
					</div>	
			
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Forma de Pago:</label>
						<input type="text" class="form-control" id="formaPagoCobMasModal">
        			</div>	
					<!--<div class="form-group">
			    		<label for="recipient-name" class="col-form-label">Fecha de Cobro:</label>
						<input type="date" class="form-control" id="fechaCobroCobMasModal">
					</div>	-->
			
        		</form>
      		</div>
		
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       		 	<button type="button" class="btn btn-primary" data-dismiss="" onClick="cobroMasivoDomiciliado()">Aceptar</button>
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

<form id="formImprimirDomiciliados" name="formImprimirDomiciliados" method="post"  target="_blank" action="imprimirDomiciliados.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="domiciliados"></input>
	<input type="hidden" id="fechaDomInicio" name="fechaDomInicio" value=""></input>	
	<input type="hidden" id="fechaDomFin" name="fechaDomFin" value=""></input>	
	<input type="hidden" id="numClienteDomiciliado" name="numClienteDomiciliado" value=""></input>	



</form>

<form id="formImprimirACompensar" name="formImprimirACompensar" method="post"  target="_blank" action="imprimirACompensar.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="aCompensar"></input>
	<input type="hidden" id="fechaAComInicio" name="fechaAComInicio" value=""></input>	
	<input type="hidden" id="fechaAComFin" name="fechaAComFin" value=""></input>	
</form>


<a href="archivosDescargas/exportarFacturas.txt" download="cibelesAsage.txt" id="cibelesAsage" style="visibility: hidden">button</a>
<a href="archivosDescargas/exportarFacturas.txt" download="claymaAsage.txt" id="claymaAsage" style="visibility: hidden">button</a>
<a href="archivosDescargas/exportarFacturasCorreos.txt" download="correosAsage.txt" id="correosAsage" style="visibility: hidden">button</a>



<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php


echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_admContabilidad.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script>
	
	//cargarListadoNombreFranqueo(' where codigo_saldo = codigo and activo = 1 order by nombre_franqueo');
	//cargarClientes(' where codigo_saldo = codigo and activo = 1 order by codigo_saldo', idInputListado, 'codigoynombre');
	cargarClientes();
  document.getElementById("button-up").addEventListener("click", scrollUp);
</script>


