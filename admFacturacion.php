

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
	
	//echo '<br><br>';
	echo '<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z"/></svg>CLIENTES</h3>
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
	<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M200-200v-560 560Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v100h-80v-100H200v560h560v-100h80v100q0 33-23.5 56.5T760-120H200Zm320-160q-33 0-56.5-23.5T440-360v-240q0-33 23.5-56.5T520-680h280q33 0 56.5 23.5T880-600v240q0 33-23.5 56.5T800-280H520Zm280-80v-240H520v240h280Zm-160-60q25 0 42.5-17.5T700-480q0-25-17.5-42.5T640-540q-25 0-42.5 17.5T580-480q0 25 17.5 42.5T640-420Z"/></svg>PROVISION DE FONDOS</h3>
	<button type="button" class="btn btn-info" onClick="location.href = \'admProvisionFondos.php\'">PROVISION DE FONDOS</button>';
	
	if ($_SESSION["provisionFondos"]==2)
	{
		echo '<button type="button" class="btn btn-info" onClick="location.href = \'admProvisionFondoPendientes.php\'">P.F. SIN COBRAR</button>';
	}
	
	
}

if ($_SESSION["facturasManipulacion"]==1 || $_SESSION["facturasManipulacion"]==2)
{
	echo '<br><br>
<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M240-80q-50 0-85-35t-35-85v-120h120v-560l60 60 60-60 60 60 60-60 60 60 60-60 60 60 60-60 60 60 60-60v680q0 50-35 85t-85 35H240Zm480-80q17 0 28.5-11.5T760-200v-560H320v440h360v120q0 17 11.5 28.5T720-160ZM360-600v-80h240v80H360Zm0 120v-80h240v80H360Zm320-120q-17 0-28.5-11.5T640-640q0-17 11.5-28.5T680-680q17 0 28.5 11.5T720-640q0 17-11.5 28.5T680-600Zm0 120q-17 0-28.5-11.5T640-520q0-17 11.5-28.5T680-560q17 0 28.5 11.5T720-520q0 17-11.5 28.5T680-480ZM240-160h360v-80H200v40q0 17 11.5 28.5T240-160Zm-40 0v-80 80Z"/></svg>FACTURAS DE MANIPULACION</h3>
<button type="button" class="btn btn-info" onClick="location.href = \'admEmisionFacturasPendientes.php\'">PREFACTURA</button>
<!--<button type="button" class="btn btn-info" onClick="location.href = \'admEmisionFacturasPendientesMensuales.php\'">PREFACTURA - MENSUAL</button>-->
<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#crearFacturaMensualModal" data-whatever="@mdo"">CREAR FAC. MENSUAL</button>-->
<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasVisualizar.php\'">FACTURAS</button>

<!--<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasSinCobrar.php\'">FACTURAS SIN COBRAR</button>-->
<button type="button" class="btn btn-info" onClick="location.href = \'noFacturables.php\'">NO FACTURABLES</button>

<button type="button" class="btn btn-info" id="botonFechaFacMesAnterior" onClick="botonFechaFinFacturacion1(0)">Facturar fecha mes Anterior</button>
<button type="button" class="btn btn-info" id="botonFechaFacMesActual" onClick="botonFechaFinFacturacion1(1)">Factura fecha mes Actual</button>

	
<button type="button" class="btn btn-info" id="botonFechaFacMesAnteriorClayma" onClick="botonFechaFinFacturacion1Clayma(0)">Facturar fecha mes Anterior - Clayma</button>
<button type="button" class="btn btn-info" id="botonFechaFacMesActualClayma" onClick="botonFechaFinFacturacion1Clayma(1)">Factura fecha mes Actual-Clayma</button>';	
	
/*
	echo '<br><br>
<h3>ABONOS</h3>
<button type="button" class="btn btn-info" onClick="location.href = \'abonos.php\'">ABONOS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facRectificativas.php\'">FAC. RECT. DIFERENCIAS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facRectificativasSustitutivas.php\'">FAC. RECT. SUSTITUCION</button>
<!--<button type="button" class="btn btn-info" onClick="location.href = \'abonosSinCobrar.php\'">ABONOS SIN COBRAR</button>-->';
*/	
}

if ($_SESSION["permiso_soloNoFacturable"]==1 || $_SESSION["permiso_soloNoFacturable"]==2)
{
	echo '<br><br>
<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M240-80q-50 0-85-35t-35-85v-120h120v-560l60 60 60-60 60 60 60-60 60 60 60-60 60 60 60-60 60 60 60-60v680q0 50-35 85t-85 35H240Zm480-80q17 0 28.5-11.5T760-200v-560H320v440h360v120q0 17 11.5 28.5T720-160ZM360-600v-80h240v80H360Zm0 120v-80h240v80H360Zm320-120q-17 0-28.5-11.5T640-640q0-17 11.5-28.5T680-680q17 0 28.5 11.5T720-640q0 17-11.5 28.5T680-600Zm0 120q-17 0-28.5-11.5T640-520q0-17 11.5-28.5T680-560q17 0 28.5 11.5T720-520q0 17-11.5 28.5T680-480ZM240-160h360v-80H200v40q0 17 11.5 28.5T240-160Zm-40 0v-80 80Z"/></svg>FACTURAS DE MANIPULACION</h3>

<button type="button" class="btn btn-info" onClick="location.href = \'noFacturables.php\'">NO FACTURABLES</button>';
	
}



if ($_SESSION["facturasCorreos"]==1 || $_SESSION["facturasCorreos"]==2)
{
	echo '<br><br>
	<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M640-200v80q0 17-11.5 28.5T600-80H120q-17 0-28.5-11.5T80-120v-320q0-17 11.5-28.5T120-480h120v-160q0-100 70-170t170-70h160q100 0 170 70t70 170v560h-80v-120H640Zm0-80h160v-360q0-66-47-113t-113-47H480q-66 0-113 47t-47 113v160h280q17 0 28.5 11.5T640-440v160ZM400-560v-80h320v80H400Zm-40 274 200-114H160l200 114Zm0 70L160-330v170h400v-170L360-216ZM160-400v240-240Z"/></svg>FACTURAS DE CORREOS</h3>
	<button type="button" class="btn btn-info" onClick="location.href = \'traspasoCorreosAcibeles.php\'">CORREOS A CIBELES</button>
	<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasCorreos.php\'">FAC. CORREOS</button>
	<!--<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasCorreosPendientes.php\'">FAC. CORREOS SIN COBRAR</button>-->
	<!--<button type="button" class="btn btn-info" onClick="borrarPreFacturasCorreos()">BORRAR ALBARANES</button>-->';

	
}

if ($_SESSION["facturas"]==1 || $_SESSION["facturas"]==2)
{
	echo '<br><br>
<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M440-200h80v-40h40q17 0 28.5-11.5T600-280v-120q0-17-11.5-28.5T560-440H440v-40h160v-80h-80v-40h-80v40h-40q-17 0-28.5 11.5T360-520v120q0 17 11.5 28.5T400-360h120v40H360v80h80v40ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-560v-160H240v640h480v-480H520ZM240-800v160-160 640-640Z"/></svg>FACTURAS</h3>
<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasSinCobrarTotal.php\'">FAC. TOTAL SIN COBRAR</button>
<!--<button type="button" class="btn btn-info" onClick="location.href = \'admFacturasSinCobrarAnteriores.php\'">SIN COBRAR AÑOS ANTERIORES</button>-->
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
<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm80-80h280v-80H280v80Zm0-160h400v-80H280v80Zm0-160h400v-80H280v80Zm200-190q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-200v-560 560Z"/></svg>CERTIFICADOS - ALBARANES - GASTOS ADICIONALES </h3>
<button type="button" class="btn btn-info" onClick="location.href = \'certGrabar.php\'">GRABAR CERTIFICADOS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'recibosGrabar.php\'">GRABAR RECOGIDAS Y ENTREGAS</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facturasEspeciales.php\'">GASTOS ADICIONALES</button>
<button type="button" class="btn btn-info" onClick="location.href = \'facturasEspecialesAfacturas.php\'">CREAR FACTURAS MENSUALES</button>
<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirFacMensualCdModal" data-whatever="@mdo">IMPRIMIR FAC MENSUALES</button>-->';
}

if ($_SESSION["soloGrabarRecogidasEntregas"]==1 || $_SESSION["soloGrabarRecogidasEntregas"]==2)
{
	echo '<br><br>
	<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M240-160q-50 0-85-35t-35-85H40v-440q0-33 23.5-56.5T120-800h560v160h120l120 160v200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H360q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T280-280q0-17-11.5-28.5T240-320q-17 0-28.5 11.5T200-280q0 17 11.5 28.5T240-240ZM120-360h32q17-18 39-29t49-11q27 0 49 11t39 29h272v-360H120v360Zm600 120q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240Zm-40-200h170l-90-120h-80v120ZM360-540Z"/></svg>RECOGIDAS Y ENTREGAS</h3>

	<button type="button" class="btn btn-info" onClick="location.href = \'recibosGrabar.php\'">GRABAR RECOGIDAS Y ENTREGAS</button>';

}

if ($_SESSION["admInformes"]==1 || $_SESSION["admInformes"]==2)
{
	echo '<br><br>
<h3 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M640-160v-280h160v280H640Zm-240 0v-640h160v640H400Zm-240 0v-440h160v440H160Z"/></svg>INFORMES FRANQUEO</h3>
<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeCibelesCorreosDiferencias" data-whatever="@mdo">Cibeles VS Correos</button>-->
<button type="button" class="btn btn-info" onClick="cargarListadoClientesInformeFranqueo()">Cliente</button>
<button type="button" class="btn btn-info" onClick="cargarListadoSubClientesInformeFranqueo()">SubCliente</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoOTModal" data-whatever="@mdo">OT</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoConsumoPorProducto" data-whatever="@mdo">Consumo por Productos 1 - Produccion</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoConsumoPorProducto2" data-whatever="@mdo">Consumo por Productos 2 -  Raul</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirInformeFranqueoBonificacionGial" data-whatever="@mdo">Bonificaciones Gial</button>
<!--<button type="button" class="btn btn-info" onClick="cargarListadoClientesInformeFranqueoExtension()">VISALIA</button>-->

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
						<select id="clienteInformeFranqueoModal" class="form-control" onchange="gestionSaldoSegunCliente()"></select>
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
							<option value="2026 selected">2026</option>
							<option value="2025">2025</option>	
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
						<input type="checkbox" class="" id="claymaVerTodoModal" onChange="cargarClientesVerTodo()"></input>
          			</div>						
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clienteVerTodoModal" class="form-control"></select>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Año</label>
													
						<select id="anioVerTodoModal" class="form-control"><option value="2026" selected>2026</option><option value="2025">2025</option><option value="2024">2024</option><option value="2023">2023</option><option value="2022">2022</option></select>
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
							<option value="nombre_empresa">Cliente</option>
							<option value="codigo_saldo">Codigo Saldo</option>
							<option value="franqueo">Franqueo</option>
							<option value="manipulado">Manipulado</option>							
							<option value="mediaFranqueo">Media Franqueo</option>
							<option value="mediaManipulado">Media Manipulado</option>
							<option value="numFacturasCorreos">Nº de Facturas Franqueo</option>
							<option value="numFacManipulado">Nº de Facturas Manipulado</option>
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






