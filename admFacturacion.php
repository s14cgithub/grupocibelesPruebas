

<?php 

session_start(); 
$_SESSION['titulo']="ADMINISTRACION - FACTURACION";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

//require($ruta."Archivos Comunes/constantes.php");
require($ruta."Archivos Comunes/cabecera.php");

?>

<?php
if ($_SESSION["permiso_clientes"]==1 || $_SESSION["permiso_clientes"]==2)
{
	/*echo '<br><br>
	<h3>CLIENTES</h3>
	<!--<button type="button" class="btn btn-info" onClick="location.href = \'clientes.php\'">CLIENTES</button>-->
	<button type="button" class="btn btn-info" onClick="location.href = \'clientes_listado.php\'">CLIENTES LISTADO</button>
	<button type="button" class="btn btn-info" onClick="location.href = \'clientes.php\'">NUEVO</button>
	<!--<button type="button" class="btn btn-info" onClick="location.href = \'clientesClayma.php\'">CLIENTES CLAYMA</button>-->';*/
	
	echo '<br><br>
	<h3>CLIENTES</h3>
	<button type="button" class="btn btn-info" onClick="location.href = \'clientes_listado.php\'">CLIENTES LISTADO</button>';
	
	if ($_SESSION["permiso_clientes"]==2)
	{
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#nuevoClienteModal" data-whatever="@mdo">NUEVO</button>';
		echo '<button type="button" class="btn btn-info" onClick="location.href = \'datosEnviosFacturas.php\'">Datos Envios Facturas</button>';
	}
	
	
	
	
	/*if ($_SESSION["permiso_soloDireccion"]==2)
	{
		echo '<button type="button" class="btn btn-info" onClick="irAsituacionCliente()">SITUACION</button>';		
		echo '<button type="button" class="btn btn-info"  onClick="location.href = \'agentesComerciales.php\'">AGENTES COMERCIALES</button>';
		
	}*/
	
	
}


if ($_SESSION["provisionFondos"]==1 || $_SESSION["provisionFondos"]==2)
{
	echo '<br><br>
	<h3>PROVISION DE FONDOS</h3>
	<button type="button" class="btn btn-info" onClick="location.href = \'admProvisionFondos.php\'">PROVISION DE FONDOS</button>';
	
	if ($_SESSION["provisionFondos"]==2)
	{
		echo '<button type="button" class="btn btn-info" onClick="location.href = \'admProvisionFondoPendientes.php\'">P.F. SIN COBRAR</button>';
	}
	
	
}

if ($_SESSION["facturasManipulacion"]==1 || $_SESSION["facturasManipulacion"]==2)
{
	echo '<br><br>
<h3>FACTURAS DE MANIPULACION</h3>
<button type="button" class="btn btn-info" onClick="location.href = \'admEmisionFacturasPendientes.php\'">PREFACTURA</button>
<button type="button" class="btn btn-info" onClick="location.href = \'admEmisionFacturasPendientesMensuales.php\'">PREFACTURA - MENSUAL</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#crearFacturaMensualModal" data-whatever="@mdo"">CREAR FAC. MENSUAL</button>
<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasVisualizar.php\'">FACTURAS</button>

<!--<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasSinCobrar.php\'">FACTURAS SIN COBRAR</button>-->
<button type="button" class="btn btn-info" onClick="location.href = \'noFacturables.php\'">NO FACTURABLES</button>

<button type="button" class="btn btn-info" id="botonFechaFacMesAnterior" onClick="botonFechaFinFacturacion1(0)">Facturar fecha mes Anterior</button>
<button type="button" class="btn btn-info" id="botonFechaFacMesActual" onClick="botonFechaFinFacturacion1(1)">Factura fecha mes Actual</button>

	
<button type="button" class="btn btn-info" id="botonFechaFacMesAnteriorClayma" onClick="botonFechaFinFacturacion1Clayma(0)">Facturar fecha mes Anterior - Clayma</button>
<button type="button" class="btn btn-info" id="botonFechaFacMesActualClayma" onClick="botonFechaFinFacturacion1Clayma(1)">Factura fecha mes Actual-Clayma</button>';	
	
	echo '<br><br>
<h3>ABONOS</h3>
<button type="button" class="btn btn-info" onClick="location.href = \'abonos.php\'">ABONOS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facRectificativas.php\'">FAC. RECT. DIFERENCIAS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facRectificativasSustitutivas.php\'">FAC. RECT. SUSTITUCION</button>
<!--<button type="button" class="btn btn-info" onClick="location.href = \'abonosSinCobrar.php\'">ABONOS SIN COBRAR</button>-->';
	
}

if ($_SESSION["permiso_soloNoFacturable"]==1 || $_SESSION["permiso_soloNoFacturable"]==2)
{
	echo '<br><br>
<h3>FACTURAS DE MANIPULACION</h3>

<button type="button" class="btn btn-info" onClick="location.href = \'noFacturables.php\'">NO FACTURABLES</button>';
	
}



if ($_SESSION["facturasCorreos"]==1 || $_SESSION["facturasCorreos"]==2)
{
	echo '<br><br>
	<h3>FACTURAS DE CORREOS</h3>
	<button type="button" class="btn btn-info" onClick="location.href = \'traspasoCorreosAcibeles.php\'">CORREOS A CIBELES</button>
	<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasCorreos.php\'">FAC. CORREOS</button>
	<!--<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasCorreosPendientes.php\'">FAC. CORREOS SIN COBRAR</button>-->
	<button type="button" class="btn btn-info" onClick="borrarPreFacturasCorreos()">BORRAR ALBARANES</button>';

	
}

if ($_SESSION["facturas"]==1 || $_SESSION["facturas"]==2)
{
	echo '<br><br>
<h3>FACTURAS</h3>
<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasSinCobrarTotal.php\'">FAC. TOTAL SIN COBRAR</button>
<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasSinCobrarAnteriores.php\'">SIN COBRAR AÑOS ANTERIORES</button>
<button type="button" class="btn btn-info" onClick="cargarListadoClientesExcelFacturaTotal()">EXCEL</button>
<button type="button" class="btn btn-info" onClick="verTodoUnCliente()">VER UN CLIENTE</button>
';
	
	if ($_SESSION["permiso_informeFacturaEstadisticas"]==1 || $_SESSION["permiso_informeFacturaEstadisticas"]==2)
	{
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#facturasEstadisticasModal" data-whatever="@mdo"">ESTADISTICAS</button>';

		echo '<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasVisualizarTodo.php\'">VISUALIZAR TODO</button>';
		

	}
	
}

if ($_SESSION["certAlbGastAdicional"]==1 || $_SESSION["certAlbGastAdicional"]==2)
{
	echo '<br><br>
<h3>CERTIFICADOS - ALBARANES - GASTOS ADICIONALES </h3>
<button type="button" class="btn btn-info" onClick="location.href = \'certGrabar.php\'">GRABAR CERTIFICADOS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'recibosGrabar.php\'">GRABAR RECOGIDAS Y ENTREGAS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facturasEspeciales.php\'">GASTOS ADICIONALES</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facturasEspecialesAfacturas.php\'">CREAR FACTURAS</button>
<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirFacMensualCdModal" data-whatever="@mdo">IMPRIMIR FAC MENSUALES</button>-->';
}

if ($_SESSION["soloGrabarRecogidasEntregas"]==1 || $_SESSION["soloGrabarRecogidasEntregas"]==2)
{
	echo '<br><br>
	<h3>RECOGIDAS Y ENTREGAS</h3>

	<button type="button" class="btn btn-info" onClick="location.href = \'recibosGrabar.php\'">GRABAR RECOGIDAS Y ENTREGAS</button>';

}

if ($_SESSION["admInformes"]==1 || $_SESSION["admInformes"]==2)
{
	echo '<br><br>
<h3>INFORMES FRANQUEO</h3>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeCibelesCorreosDiferencias" data-whatever="@mdo">Cibeles VS Correos</button>
<button type="button" class="btn btn-info" onClick="cargarListadoClientesInformeFranqueo()">Cliente</button>
<button type="button" class="btn btn-info" onClick="cargarListadoSubClientesInformeFranqueo()">SubCliente</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoOTModal" data-whatever="@mdo">OT</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoConsumoPorProducto" data-whatever="@mdo">Consumo por Productos 1 - Produccion</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoConsumoPorProducto2" data-whatever="@mdo">Consumo por Productos 2 -  Raul</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoBonificacionGial" data-whatever="@mdo">Bonificaciones Gial</button>
<button type="button" class="btn btn-info" onClick="cargarListadoClientesInformeFranqueoExtension()">VISALIA</button>

';
	
}

?>









<span style="float: none; width: 100%"><br>&nbsp;</span>

</div> <!-- class="tabla" -->

<!--IMPRIMIR FACTURAS MENSUALES CD -->
<div class="modal fade" id="imprimirFacMensualCdModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">IMPRIMIR MENSUALES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">		
		<form> 
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Numero Factura Inicial:</label>
            <input type="number" class="form-control" id="numFacInicialModal">
        </div>
			
			
		<div class="form-group">
          	<label for="recipient-name" class="col-form-label">Numero Factura Final:</label>
            <input type="number" class="form-control" id="numFacFinalModal">
        </div>			
        </form>
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirFacturasMensualesCD()">Imprimir</button>
      </div>
    </div>
  </div>
</div>



<!--INFORME FRANQUEO-->
<div class="modal fade" id="imprimirInformeFranqueoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Clientes:</label>
						<select id="clienteInformeFranqueoModal" class="form-control"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeFranqueoModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeFranqueoModal"></input>
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Saldo: </label>
						<input type="checkbox" class="" id="saldoFinInformeFranqueoModal" onClick="gestionActivarDetalle()"></input>
						&nbsp;&nbsp;&nbsp;
						<span id="groupSaldoDetalle" style="visibility: hidden">
						<label for="recipient-name" class="col-form-label">Detalle Facturas Correos: </label>
						<input type="checkbox" class="" id="saldoDetalleFacCoInformeFranqueoModal"></input>
						</span>
						
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Sin Iva: </label>
						<input type="checkbox" class="" id="sinIvaInformeFranqueoModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Separar Por Fechas: </label>
						<input type="checkbox" class="" id="separarFechasInformeFranqueoModal"></input>
          			</div>	
			
					<div class="form-group">
						<hr>
						<label>Si se introduce la extension <b>y</b> la ot, sale el logo de Clayma</label>
            			<label for="recipient-name" class="col-form-label" style="width: 75px">Extension: </label>
						<input type="text" class="" id="extensionInformeFranqueoModal"></input>
					</div>
					<div class="form-group">				
						<label for="recipient-name" class="col-form-label" style="width: 75px">OT: </label>
						<input type="number" class="" id="otInformeFranqueoModal"></input>
					</div>	
						<!--<label for="recipient-name" ><font style="color:red">Solo se muestra datos si la extension contiene el valor introducido.</font> </label>-->
						
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Cliente a Facturar:</label>
						<select id="cliente2InformeFranqueoModal" class="form-control"></select>
					</div>	
		
          			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoDigitalClaymaExcel()">Excel</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueo()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div> 


<!--INFORME FRANQUEO POR SUBCLIENTE-->
<div class="modal fade" id="imprimirInformeFranqueoSubClientesModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">SubClientes:</label>
						<select id="clienteInformeFranqueoSubClientesModal" class="form-control"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeFranqueoSubClientesModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeFranqueoSubClientesModal"></input>
          			</div>
					<!--<div class="form-group"> LO HE QUITADO PORQUE LOS SALDOS / FRANQUEO NO LO PONE BIEN
            			<label for="recipient-name" class="col-form-label">Saldo: </label>
						<input type="checkbox" class="" id="saldoFinInformeFranqueoSubClientesModal"></input>
          			</div>	-->
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Sin Iva: </label>
						<input type="checkbox" class="" id="sinIvaInformeFranqueoSubClientesModal"></input>
          			</div>	
		
		
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoSubCliente()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div> 


<!--INFORME FRANQUEO POR OT-->
<div class="modal fade" id="imprimirInformeFranqueoOTModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO POR OT</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>	
					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Año:</label>
						<select id="otInformeFranqueoOTModal" class="form-control">
							<option value="2026">2026</option>
							<option value="2025" selected>2025</option>	
							<option value="2024">2024</option>
							<option value="2023">2023</option>
							<option value="2022">2022</option>
						</select>	
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">OT:</label>
						<input type="text" id="clienteInformeFranqueoOTModal" class="form-control"></input>
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Sin Iva: </label>
						<input type="checkbox" class="" id="clienteInformeFranqueoSinIvaModal"></input>
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Separar Por Fechas: </label>
						<input type="checkbox" class="" id="otInformeFranqueoSepararFechaModal"></input>
          			</div>	
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoOT()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div>

<!--INFORME FRANQUEO CONSUMO POR PRODUCTO-->
<div class="modal fade" id="imprimirInformeFranqueoConsumoPorProducto" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO - CONSUMO POR PRODUCTO</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeConsumoProductoModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeConsumoProductoModal"></input>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoConsumoPorProducto()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div>

<!--INFORME FRANQUEO CONSUMO POR PRODUCTO2-->
<div class="modal fade" id="imprimirInformeFranqueoConsumoPorProducto2" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO - CONSUMO POR PRODUCTO - CON DESCUENTOS</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeConsumoProductoModal2"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeConsumoProductoModal2"></input>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoConsumoPorProducto2()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div>




<!--INFORME COMPARACION FRANQUEO ENTRE CIBELES Y CORREOS-->
<div class="modal fade" id="imprimirInformeCibelesCorreosDiferencias" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">COMPARACION DE FRANQUEO ENTRE CIBELES Y CORREOS</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<!--<div class="form-group" style="color: black !important">
            			<input type="file" class="" id="elArchivoComparar" name="elArchivoComparar" accept=".txt" form="formImprimirDiferenciasFranqueo"></input>						
          			</div>-->
					
					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeFranqueoDifCibCorrModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeFranqueoFranqueoDifCibCorrModal"></input>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="leerArchivoCorreosComparar()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div> 

<!--CAMBIAR FECHA DE FACTURACION-->
<div class="modal fade" id="cambiarFechaFacturacionModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">CAMBIAR FECHA DE FACTURACION</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
			<h5 id="estadoModal" style="visibility:hidden; display: none"></h5>
      		<div class="modal-body">
        		<form>
					<div class="form-group">
            			<h5 id="textoFechaFacturaModal"></h5>
            			
						
          			</div>	
					
            		
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="botonFechaFinFacturacion()">Cambiar</button>
      		</div>
    	</div>
	</div>
</div>
	
<!--CAMBIAR FECHA DE FACTURACION CLAYMA-->
<div class="modal fade" id="cambFechaFactModalClayma" tabindex="-1" role="dialog" aria-labelledby="ModalLabel1" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel1">CAMBIAR FECHA DE FACTURACION - Clayma</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
			<h5 id="estadoModalClayma" style="visibility:hidden; display: none"></h5>
      		<div class="modal-body">
        		<form>
					<div class="form-group">
            			<h5 id="textoFechaFacturaModalClayma"></h5>
          			</div>	
					           		
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="botonFechaFinFacturacionClayma()">Cambiar</button>
      		</div>
    	</div>
	</div>
</div>


<!--NUEVO CLIENTE-->
<div class="modal fade" id="nuevoClienteModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel1" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel1">NUEVO CLIENTE</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>			
      		<div class="modal-body">
			
        		<form>
					<center>
				 	<input type="radio" id="origen1" name="origen" value="email" checked>
					<label  class="col-form-label" for="origen1" style="color: black">CIBELES</label>
					<br>
					<input type="radio" id="origen2" name="origen" value="phone">
					<label  class="col-form-label" for="origen2" style="color: black">CLAYMA</label>
					  </center>         		
        		</form>
				
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="gestionIrAFichaCliente()">Aceptar</button>
      		</div>
    	</div>
	</div>
</div>


<!--INFORME FRANQUEO BONIFICACIONES -->
<div class="modal fade" id="imprimirInformeFranqueoBonificacionGial" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO - BONIFICACIONES GIAL</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeBonificacionModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeBonificacionModal"></input>
						<input class="" type="hidden" id="idClienteInformeBonificacionModal"></input>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoBonificaciones()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div>
	

<!--INFORME FRANQUEO POR EXTENSION (VISALIA)-->
<div class="modal fade" id="imprimirInformeFranqueoExtension" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FRANQUEO POR EXTENSION</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clienteInformeFranqueoExtensionModal" class="form-control"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeFranqueoExtensionModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeFranqueoExtensionModal"></input>
          			</div>		
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueoExtensiones()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div> 
	

<!--EXCEL FACTURAS TOTAL-->
<div class="modal fade" id="excelFacturasTotal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">FACTURAS</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clienteExcelFacturasTotalModal" class="form-control"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioExcelFacturasTotalModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinExcelFacturasTotalModal"></input>
          			</div>		
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeExcelFacturasTotal()">Generar Excel</button>
      		</div>
    	</div>
	</div>
</div> 


<!--VER TODO DE UN CLIENTE-->
<div class="modal fade" id="verTodoDelClienteModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">VER FACTURAS/ABONOS/FRANQUEO</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Clayma: </label>
						<input type="checkbox" class="" id="claymaVerTodoModal" onChange="verTodoUnClienteClayma()"></input>
          			</div>						
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clienteVerTodoModal" class="form-control"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Año</label>
													
						<select id="anioVerTodoModal" class="form-control"><option value="2026">2026</option><option value="2025" selected>2025</option><option value="2024">2024</option><option value="2023">2023</option><option value="2022">2022</option></select>
          			</div>							
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="verTodoUnCliente2()">Ver</button>
      		</div>
    	</div>
	</div>
</div> 


<!--ESTADISTICAS-->
<div class="modal fade" id="facturasEstadisticasModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">VER ESTADISTICAS</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>										
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Ordenar Por:</label>
						<select id="facturaEstadistica_orden" class="form-control">
							<option value="t9.nombre_empresa">Cliente</option>
							<option value="t9.codigo_saldo">Codigo Saldo</option>
							<option value="isnull(franqueo,0)">Franqueo</option>
							<option value="isnull(t9.factura,0) + isnull(t9.abono,0)">Manipulado</option>							
							<option value="isnull(isnull(t9.franqueo,0)/nullif(isnull(numFacturasCorreos,0),0),0) ">Media Franqueo</option>
							<option value="isnull((isnull(t9.factura,0) + isnull(t9.abono,0))/nullif(isnull(numFactura,0),0),0)">Media Manipulado</option>
							<option value="isnull(franqueo,0) as franqueo, isnull(numFacturasCorreos,0)">Nº de Facturas Franqueo</option>
							<option value="isnull(numFactura,0) + isnull(numAbono,0)">Nº de Facturas Manipulado</option>
						</select>
          			</div>
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Descendiente:</label>
						<input type="checkbox" class="" id="estadisticas_desc"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Año</label>
						<select id="facturaEstadistica_anio" class="form-control"><option value="2026">2026</option><option value="2025" selected>2025</option><option value="2024">2024</option><option value="2023">2023</option><option value="2022">2022</option></select>							
          			</div>	
					<form>						
						<input type="radio" id="origen1Est" name="origenEst" value="cibeles" checked>
						<label  class="col-form-label" for="origen1Est" style="color: black">CIBELES</label>						
						<input type="radio" id="origen2Est" name="origenEst" value="clayma">
						<label  class="col-form-label" for="origen2Est" style="color: black">CLAYMA</label>						        		
					</form>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>				
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="verFacturasEstadisticas()">Ver</button>
				<button type="button" class="btn btn-primary" data-dismiss="" onClick="excelFacturasEstadisticas()">Excel</button>
      		</div>
    	</div>
	</div>
</div> 

<!--CREAR FACTURA MENSUAL POR COPIA-->

<div class="modal fade" id="crearFacturaMensualModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">CREAR FACTURA MENSUAL POR COPIA</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>										
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Introducir número de factura a Copiar: numero/año</label>
						
						<input type="text" class="form-control" id="crearFacturaMensualModal_numFactura"></input>
					</div>					
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>				
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="gestionCrearMensualPorCopia()">Crear</button>
				
      		</div>
    	</div>
	</div>
</div> 


<!--AGENTES COMERCIALES
<div class="modal fade" id="imprimirInformeAgenteComercial" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">AGENTES COMERCIALES</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
						<input class="form-control" type="date" id="fechaInicioInformeAgenteComercialModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
						<input class="form-control" type="date" id="fechaFinInformeAgenteComercialModal"></input>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeAgenteComercial()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div>-->


	

<form id="formImprimirLibroContabilidad" name="formImprimirLibroContabilidad" method="post"  target="_blank" action="imprimirLibroContabilidad.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="libroConta"></input>
	<input type="hidden" id="fechaInicio" name="fechaInicio" value=""></input>	
	<input type="hidden" id="fechaFin" name="fechaFin" value=""></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>

<a href="archivosDescargas/exportarFacturas.txt" download="cibelesAsage.txt" id="cibelesAsage" style="visibility: hidden">button</a>

<form id="formImprimirFactMensualCDsinRetener" name="formImprimirFactMensualCDsinRetener" method="post"  target="_blank" action="imprimirFacturaMensual.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirMensuales"></input>
	<input type="hidden" id="numFacInicio" name="numFacInicio" value=""></input>
	<input type="hidden" id="numFacFin" name="numFacFin" value=""></input>	
</form>

<form id="formImprimirFactMensualCDRetenidas" name="formImprimirFactMensualCDRetenidas" method="post"  target="_blank" action="imprimirFacturaMensualRetenidas.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirMensuales"></input>
	<input type="hidden" id="numFacInicioRet" name="numFacInicioRet" value=""></input>
	<input type="hidden" id="numFacFinRet" name="numFacFinRet" value=""></input>	
</form>



<form id="formImprimirFranqueo" name="formImprimirFranqueo" method="post"  target="_blank" action="imprimirInformeFranqueo.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFranqueo"></input>
	<input type="hidden" id="imprimirClienteInformeFranqueoModal" name="imprimirClienteInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirFechaInicioInformeFranqueoModal" name="imprimirFechaInicioInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirFechaFinInformeFranqueoModal" name="imprimirFechaFinInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirSinIvaInformeFranqueoModal" name="imprimirSinIvaInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirSaldoFinInformeFranqueoModal" name="imprimirSaldoFinInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirPorFechasInformeFranqueoModal" name="imprimirPorFechasInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirCorreosDetalleModal" name="imprimirCorreosDetalleModal" value=""></input>
	<input type="hidden" id="imprimirOtInformeFranqueoModal1" name="imprimirOtInformeFranqueoModal1" value=""></input>


</form>

<form id="formImprimirFranqueoDigitalClayma" name="formImprimirFranqueoDigitalClayma" method="post"  target="_blank" action="imprimirInformeFranqueoDigitalClayma.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFranqueo"></input>
	<input type="hidden" id="imprimirClienteInformeDigitalClaymaFranqueoModal" name="imprimirClienteInformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirFechaInicioInformeDigitalClaymaFranqueoModal" name="imprimirFechaInicioInformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirFechaFinInformeDigitalClaymaFranqueoModal" name="imprimirFechaFinInformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirSaldoFinInformeDigitalClaymaFranqueoModal" name="imprimirSaldoFinInformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirCliente2InformeDigitalClaymaFranqueoModal" name="imprimirCliente2InformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirExtensionInformeDigitalClaymaFranqueoModal" name="imprimirExtensionInformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirOtInformeDigitalClaymaFranqueoModal" name="imprimirOtInformeDigitalClaymaFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirSinIvaInformeDigitalClaymaFranqueoModal" name="imprimirSinIvaInformeDigitalClaymaFranqueoModal" value=""></input>
</form>





<form id="formImprimirFranqueoSubCliente" name="formImprimirFranqueoSubCliente" method="post"  target="_blank" action="imprimirInformeFranqueoSubCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFranqueo"></input>
	<input type="hidden" id="imprimirClienteInformeFranqueoSubClienteModal" name="imprimirClienteInformeFranqueoSubClienteModal" value=""></input>
	<input type="hidden" id="imprimirFechaInicioInformeFranqueoSubClienteModal" name="imprimirFechaInicioInformeFranqueoSubClienteModal" value=""></input>
	<input type="hidden" id="imprimirFechaFinInformeFranqueoSubClienteModal" name="imprimirFechaFinInformeFranqueoSubClienteModal" value=""></input>
	<input type="hidden" id="imprimirSaldoFinInformeFranqueoSubClienteModal" name="imprimirSaldoFinInformeFranqueoSubClienteModal" value=""></input>
	<input type="hidden" id="imprimirSinIvaFinInformeFranqueoSubClienteModal" name="imprimirSinIvaFinInformeFranqueoSubClienteModal" value="">
</form>
	
</form>

<form id="formImprimirFranqueoOT" name="formImprimirFranqueoOT" method="post"  target="_blank" action="imprimirInformeFranqueoOT.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFranqueo"></input>
	<input type="hidden" id="imprimirOTSinIvaInformeFranqueoModal" name="imprimirOTSinIvaInformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirOTSepararFechasinformeFranqueoModal" name="imprimirOTSepararFechasinformeFranqueoModal" value=""></input>
	<input type="hidden" id="imprimirOTInformeFranqueoModal" name="imprimirOTInformeFranqueoModal" value=""></input>	
	<input type="hidden" id="imprimirOTInformeAnioFranqueoModal" name="imprimirOTInformeAnioFranqueoModal" value=""></input>
</form>


<form id="formImprimirDiferenciasFranqueo" name="formImprimirDiferenciasFranqueo" method="post"  target="_blank"  enctype="multipart/form-data" action="imprimirInformeFranqueoDiferencias.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFranqueoDirerencias"></input>
	<input type="hidden" id="imprimirInformeFranqueoDirencias_fechaInicio" name="imprimirInformeFranqueoDirencias_fechaInicio" value=""></input>
	<input type="hidden" id="imprimirInformeFranqueoDirencias_fechaFin" name="imprimirInformeFranqueoDirencias_fechaFin" value=""></input>		
</form>


<form id="formImprimirInformeConsumoProducto" name="formImprimirInformeConsumoProducto" method="post"  target="_blank" action="imprimirInformeConsumoProducto.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInforme"></input>
	<input type="hidden" id="fechaInicioConsumoProductoModal" name="fechaInicioConsumoProductoModal" value=""></input>
	<input type="hidden" id="fechaFinConsumoProductoModal" name="fechaFinConsumoProductoModal" value=""></input>	
</form>

<form id="formImprimirInformeConsumoProducto2" name="formImprimirInformeConsumoProducto2" method="post"  target="_blank" action="imprimirInformeConsumoProducto2.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInforme"></input>
	<input type="hidden" id="fechaInicioConsumoProductoModal2" name="fechaInicioConsumoProductoModal2" value=""></input>
	<input type="hidden" id="fechaFinConsumoProductoModal2" name="fechaFinConsumoProductoModal2" value=""></input>	
</form>


<form id="formExportarFranqueoExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarInformeFranqueoDigitalClaymaExcel.php">
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>
	<input type="hidden" id="imprimirClienteInformeDigitalClaymaFranqueoModalExcel" name="imprimirClienteInformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirFechaInicioInformeDigitalClaymaFranqueoModalExcel" name="imprimirFechaInicioInformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirFechaFinInformeDigitalClaymaFranqueoModalExcel" name="imprimirFechaFinInformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirSaldoFinInformeDigitalClaymaFranqueoModalExcel" name="imprimirSaldoFinInformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirCliente2InformeDigitalClaymaFranqueoModalExcel" name="imprimirCliente2InformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirExtensionInformeDigitalClaymaFranqueoModalExcel" name="imprimirExtensionInformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirOtInformeDigitalClaymaFranqueoModalExcel" name="imprimirOtInformeDigitalClaymaFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirSinIvaInformeDigitalClaymaFranqueoModalExcel" name="imprimirSinIvaInformeDigitalClaymaFranqueoModalExcel" value=""></input>	
			
</form>

<form id="formImprimirFranqueoExcelCibeles" method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarInformeFranqueoExcel.php">
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>
	<input type="hidden" id="imprimirClienteInformeFranqueoModalExcel" name="imprimirClienteInformeFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirFechaInicioInformeFranqueoModalExcel" name="imprimirFechaInicioInformeFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirFechaFinInformeFranqueoModalExcel" name="imprimirFechaFinInformeFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirSinIvaInformeFranqueoModalExcel" name="imprimirSinIvaInformeFranqueoModalExcel" value=""></input>
	<input type="hidden" id="imprimirSaldoFinInformeFranqueoModalExcel" name="imprimirSaldoFinInformeFranqueoModalExcel" value=""></input>
</form>


<form id="formImprimirBonificiones" name="formImprimirBonificiones" method="post"  target="_blank" action="imprimirBonificaciones.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="Bonificaciones"></input>
	<input type="hidden" id="fechaInicioBon" name="fechaInicioBon" value=""></input>	
	<input type="hidden" id="fechaFinBon" name="fechaFinBon" value=""></input>	
	<input type="hidden" id="numeroClienteBon" name="numeroClienteBon" value=""></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>


<form id="formImprimirFranqueoExtensiones" name="formImprimirFranqueoExtensiones" method="post"  target="_blank" action="imprimirInformeFranqueoExtensiones.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFranqueo"></input>
	<input type="hidden" id="fechaInicioExtension_form" name="fechaInicioExtension_form" value=""></input>	
	<input type="hidden" id="fechaFinExtension_form" name="fechaFinExtension_form" value=""></input>	
	<input type="hidden" id="numeroClienteExtension_form" name="numeroClienteExtension_form" value=""></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>


<form id="formImprimirExcelFacTotal" method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarInformeFacturasTotalExcel.php">
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>
	<input type="hidden" id="numeroClienteExcelFacTotal_form" name="numeroClienteExcelFacTotal_form" value=""></input>	
	<input type="hidden" id="fechaInicioExcelFacTotal_form" name="fechaInicioExcelFacTotal_form" value=""></input>
	<input type="hidden" id="fechaFinExcelFacTotal_form" name="fechaFinExcelFacTotal_form" value=""></input>	
</form>

<form id="formImprimirTodoUnCliente" name="formImprimirTodoUnCliente" method="post"  target="_blank" action="imprimirInformeTodoUnCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirTodoCliente"></input>
	<input type="hidden" id="anio" name="anio" value=""></input>	
	<input type="hidden" id="clayma" name="clayma" value=""></input>
	<input type="hidden" id="idCliente" name="idCliente" value=""></input>	
</form>

<form id="formVerFacturasEstadisticas" name="formVerFacturasEstadisticas" method="post"  target="_blank" action="imprimirInformeFacturasEsdisticas.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFacEstadisticas"></input>
	<input type="hidden" id="anioFacturaEstd" name="anioFacturaEstd" value=""></input>	
	<input type="hidden" id="ordenFacturaEstd" name="ordenFacturaEstd" value=""></input>
	<input type="hidden" id="origenFacturaEstd" name="origenFacturaEstd" value=""></input>
</form>
<form id="formExcelFacturasEstadisticas" method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarFacturasEstadisticasExcel.php">
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>
	<input type="hidden" id="anioFacturaEstdExcel" name="anioFacturaEstdExcel" value=""></input>	
	<input type="hidden" id="ordenFacturaEstdExcel" name="ordenFacturaEstdExcel" value=""></input>
	<input type="hidden" id="origenFacturaEstdExcel" name="origenFacturaEstdExcel" value=""></input>
</form>

<form id="formImprimirSituacionCliente" name="formImprimirSituacionCliente" method="post"  target="_blank" action="imprimirInformeSituacionCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="clientesSituacion"></input>	
</form>

<!--<form id="formImprimirAgenteComercialCliente" name="formImprimirAgenteComercialCliente" method="post"  target="_blank" action="imprimirInformeAgenteComercialCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="clientesAgenteComercial"></input>	
	<input type="hidden" id="fechaInicioAgenteComercial_form" name="fechaInicioAgenteComercial_form" value=""></input>
	<input type="hidden" id="fechaFinAgenteComercial_form" name="fechaFinAgenteComercial_form" value=""></input>
</form>-->


<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>

<?php



echo ("</div>");
echo ("</body>");
echo ("</html>");

?>






<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_admFacturacion.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script> 


<?php
if ($_SESSION["facturasManipulacion"]==1 || $_SESSION["facturasManipulacion"]==2)
{
	
					
	echo '<script type="text/javascript">document.getElementById("estadoModal").innerHTML=3;botonFechaFinFacturacion();</script>';
	echo '<script>document.getElementById("estadoModalClayma").innerHTML=3;botonFechaFinFacturacionClayma();</script>';
	
	
}
?>



<script language="javascript">	
	document.getElementById("button-up").addEventListener("click", scrollUp);
	/*idInputListado = 'clienteInformeFranqueoModal';
	//cargarListadoNombreFranqueo();
	cargarClientes('B','clienteInformeFranqueoModal');
	
	cargarSubClientes('','clienteInformeFranqueoSubClientesModal');
	
	cargarSubClientes('B','cliente2InformeFranqueoModal');
	
	idInputListado="";*/
	
		
	
</script>






