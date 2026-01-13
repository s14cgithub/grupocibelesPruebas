
<?php 

session_start(); 
$_SESSION['titulo']="Menú Principal";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");







?>

<!--<button type="button" class="btn btn-primary">Primary</button>
<button type="button" class="btn btn-secondary">Secondary</button>
<button type="button" class="btn btn-success">Success</button>
<button type="button" class="btn btn-danger">Danger</button>
<button type="button" class="btn btn-warning">Warning</button>
<button type="button" class="btn btn-info">Info</button>
<button type="button" class="btn btn-light">Light</button>
<button type="button" class="btn btn-dark">Dark</button>-->

<!-- CLIENTES-->

<?php 
	if ($_SESSION["permiso_clientes"]==1 || $_SESSION["permiso_clientes"] == 2)
	{
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'clientes.php\'">CLIENTES</button>');
		
	}

?>



<!--PDA_GESTION-->
<?php
	
	//ALMACEN
	if ($_SESSION["permiso_almacen"]==1 || $_SESSION["permiso_almacen"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4>ALMACEN</h4>');
		
		
		if ($_SESSION["permiso_almacen"]==1 || $_SESSION["permiso_almacen_Listado"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacen.php\'">Listado</button>');	
		}		
		
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacenProveedor.php\'">Proveedor</button>');
		
		if ($_SESSION["permiso_almacen_Nuevo"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacenProducto.php\'">Productos</button>');
		}
		
		if ($_SESSION["permiso_almacen_Albaran"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacenHueco.php\'">Huecos</button>');
		}
		if ($_SESSION["permiso_preEntradaGestion"]==2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'almacenPreEntrada.php\'">preEntrada - gestion</button>';
		}
		
		echo '<button type="button" class="btn btn-info" onclick="cargarImagenes()" data-toggle="modal" data-target="#exampleModal">Ver - PreEntrada</button>';
			
		
	}

	//PDA_GESTION
	if ($_SESSION["permiso_pdaGestion"]==1 || $_SESSION["permiso_pdaGestion"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4>PDA - Horas Realizadas</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'pda_gestion.php\'">REGISTROS PDA</button>');	
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales.php\'">REGISTROS INFORMATICA</button>');
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManualesGF.php\'">REGISTROS G.F.</button>');	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales_Comprobacion.php\'">COMPROBAR REG. MANUALES</button>');	
	}
	else if($_SESSION["permiso_registrosHorasManuales"]==1 || $_SESSION["permiso_registrosHorasManuales"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4>PDA - Horas Realizadas</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManualesGF.php\'">REGISTROS G.F.</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales.php\'">REGISTROS INFORMATICA</button>');	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManualesAlmacen.php\'">REGISTROS ALMACEN</button>');	
		
	}
	else if ( $_SESSION["permiso_registrosHorasManuales_Comprobacion"]==2)
	{
		echo ('<br><br>');	
		echo ('<h4>PDA - Horas Realizadas</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales_Comprobacion.php\'">COMPROBAR INFORMATICA</button>');
	}

	//INFORMES PRODUCCION
	if ($_SESSION["permiso_InformesProduccion"]==1 || $_SESSION["permiso_InformesProduccion"] == 2)
	{
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'informesProduccion.php\'">Informes de Producción</button>');		
	}




	//PRESUPUESTOS
	if ($_SESSION["permiso_presupuestos"]==1 || $_SESSION["permiso_presupuestos"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4>PRESUPUESTOS</h4>');	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'presupuestos.php\'">Presupuestos</button>');		
	}

	//MATERIALES
	if ($_SESSION["permiso_materialesPapel"]==1 || $_SESSION["permiso_materialesPapel"] == 2)
	{	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'materiales.php\'">Materiales</button>');		
	}

	
	//OT - PRODUCCION
	if ($_SESSION["permiso_ot"]==1 || $_SESSION["permiso_ot"] == 2)
	{
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'ot.php\'">OT</button>');		
	}
	

	//COMPRAS A TERCEROS
	if ($_SESSION["permiso_comprasAterceros"]==1 || $_SESSION["permiso_comprasAterceros"] == 2 || $_SESSION["permiso_proveedores"]==1 || $_SESSION["permiso_proveedores"] == 2)	
	{
		echo ('<br><br>');	
		echo ('<h4>Compras a Terceros</h4>');
		if($_SESSION["permiso_proveedores"]==1 || $_SESSION["permiso_proveedores"] == 2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'proveedores_listado.php\'">Proveedores</button>');
		}				
		if ($_SESSION["permiso_comprasAterceros"]==1 || $_SESSION["permiso_comprasAterceros"] == 2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'comprasTercerosListado.php\'">Compras a Terceros</button>');
			//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'comprasTercerosListadoAntiguo.php\'">Antiguos</button>');
		}
		
		if ($_SESSION["permiso_administracion_contabilidad"] == 1 || $_SESSION["permiso_administracion_contabilidad"] == 2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'comprasTercerosContabilidad.php\'">GESTION</button>';
		}
	}

	//OT - ADMINISTRACION
	if ($_SESSION["permiso_administracion"]==1 || $_SESSION["permiso_administracion"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4>ADMINISTRACION</h4>');
		/*echo ('<button type="button" class="btn btn-info" onClick="location.href = \'administracion.php\'">ADMINISTRACION</button>');*/	
		
		if ($_SESSION["permiso_administracion_contabilidad"] == 1 || $_SESSION["permiso_administracion_contabilidad"] == 2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'admContabilidad.php\'">CONTABILIDAD</button>';
		}

		if ($_SESSION["permiso_administracion_facturacion"] == 1 || $_SESSION["permiso_administracion_facturacion"] == 2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'admFacturacion.php\'">FACTURACION</button>';
		}
		
	}
	//FRANQUEO-GRABAR
	if ($_SESSION["permiso_grabarFranqueo"]==1 || $_SESSION["permiso_grabarFranqueo"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4>FRANQUEO</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoGrabacion.php\'">GRABAR FRANQUEO</button>');
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'importarCertificadosNotificaciones.php\'">IMPORTAR CERTIFICADOS O NOTIFICACIONES</button>');
		
		if ($_SESSION["permiso_grabarFranqueo"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'importarExcelIndra.php\'">IMPORTAR EXCEL INDRA</button>');
		}
		
		
	}
	
	if ($_SESSION["permiso_franqueoF12"]==1||$_SESSION["permiso_franqueoF12"] == 2)
	{
		
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'f12.php\'">F12</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoRegistros.php\'">REGISTROS</button>');
		
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#registroOtSidi" data-whatever="@mdo">OT\'s SIDI</button>';
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#importarOTSidiGrabarOtSidi" data-whatever="@mdo">Importar OT SIDI</button>';
		//echo '<button type="button" class="btn btn-info" onclick="actualizarOtSidiACibeles()">Actualizar OT</button>';
		
		if ($_SESSION["permiso_franqueoF12"] == 2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoPagadoGrabacion.php\'">GRABAR FRANQUEO PAGADO</button>');
		}
		
		
		//echo ('<button type="button" class="btn btn-info" onClick="cargarListadoClientesInformeFranqueo()">FRANQUEO por Cliente</button>');
	}
	/*if ($_SESSION["permiso_actualizarDatos"]==1||$_SESSION["permiso_actualizarDatos"]==2)
	{
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'actualizarDatos.php\'">ActualizarDatos</button>');
	}*/

	if ($_SESSION["permiso_clientesAutorizados_franqueo"]==1||$_SESSION["permiso_clientesAutorizados_franqueo"] == 2)
	{
		
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoClientesAutorizados.php\'">Clientes Autorizados</button>');
		

	}



	//RUTAS
	if ($_SESSION["permiso_rutas"]==1||$_SESSION["permiso_rutas"]==2)
	{	
		echo ('<br><br>');	
		echo ('<h4>RUTAS</h4>');		
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutas.php\'">RUTAS</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasAdicionales.php\'">RUTAS Adicionales</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasVinculaciones.php\'">RUTAS Vinculaciones</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasHistorico.php\'">RUTAS Historico</button>');
	}

	if ($_SESSION["permiso_empleados"]==1||$_SESSION["permiso_empleados"]==2)
	{	
		echo ('<br><br>');	
		echo ('<h4>EMPLEADOS</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'empleados.php\'">EMPLEADOS</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'loginGestion.php\'">LOGIN</button>');
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasVinculaciones.php\'">PERMISOS</button>');
		
	}


	if ($_SESSION["permiso_tarifas"]==1||$_SESSION["permiso_tarifas"]==2)
	{	
		echo ('<br><br>');	
		echo ('<h4>TARIFAS</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'tarifasPapel.php\'">Papel</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'tarifasTipoImpresora.php\'">Tipo Impresora</button>');	
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'tarifasGranFormato.php\'">Gran Formato</button>');	
		
	}




?>

<!--INFORME FRANQUEO-->
<!--<div class="modal fade" id="imprimirInformeFranqueoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
						<input type="checkbox" class="" id="saldoFinInformeFranqueoModal"></input>
          			</div>	
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Sin Iva: </label>
						<input type="checkbox" class="" id="sinIvaInformeFranqueoModal"></input>
          			</div>	
			
					<div class="form-group">
						<hr>
            			<label for="recipient-name" class="col-form-label" style="width: 75px">Extension: </label>
						<input type="text" class="" id="extensionInformeFranqueoModal"></input>
					</div>
					<div class="form-group">				
						<label for="recipient-name" class="col-form-label" style="width: 75px">OT: </label>
						<input type="number" class="" id="otInformeFranqueoModal"></input>
					</div>	
						
						
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Cliente a Facturar:</label>
						<select id="cliente2InformeFranqueoModal" class="form-control"></select>
					</div>	
		
          			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="informeFranqueo()">Generar Informe</button>
      		</div>
    	</div>
	</div>
</div>--> 


 


<!--VISUALIZAR IMAGENES ALMACEN-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">       
		  		<h5 class="modal-title" id="ModalLabel">ALMACEN - PRE-ENTRADA</h5>
      		</div>
      		<div class="modal-body">
		 
				<div class="row">

  				<!-- Grid column -->
			 		<!--<div class="col-md-12 d-flex justify-content-center mb-5">
						
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="all">Todos</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="arranque">Arraque</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="calidad">Calidad</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="incidencia">Incidencia</button>
			  		</div>-->
 				 <!-- Grid column -->

				</div>
				<div class="gallery" id="gallery"></div>

		  
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
      		</div>
    	</div>
  </div>
</div>


<!--  OT'S DE SIDI  -->

<a href="archivosDescargas/otSidi.txt" download="otSidi.txt" id="otSidiTXT" style="visibility: hidden">button</a>

<div class="modal fade" id="registroOtSidi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">       
		  		<h5 class="modal-title" id="ModalLabel">REGISTROS DE OT PARA SIDI</h5>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">OT's:</label>
						<textarea id="otSidiModal" class="form-control" rows="15"></textarea>
          			</div>	
					
		
          			
        		</form>
      		</div>
			  <div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="generarTxtSIDI()">Generar TXT</button>
      		</div>
    	</div>
  </div>
</div> 




<!--  GRABAR OT SIDI  -->

<div class="modal fade" id="importarOTSidiGrabarOtSidi" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">IMPORTACION OT's SIDI</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<!--<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clientesImportacionMensualesModal" class="form-control"></select>
          			</div>-->
					<div class="" style="color:black;">					
						<input type="file" class="" id="elArchivoExcelOtSidi" name="elArchivoExcelOtSidi" accept=".xlsx"></input>
          			</div>	
						
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="subirOtSidiDesdeExcel()">Importar Datos</button>
      		</div>
    	</div>
	</div>
</div> 


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_principal.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<span style="float: none; width: 100%"><br>&nbsp;</span>
<?php

echo ("</div>");

echo ("</html>");



?>

<script language="javascript">

	
$(function() {
var selectedClass = "";
$(".filter").click(function(){
selectedClass = $(this).attr("data-rel");
$("#gallery").fadeTo(100, 0.1);
$("#gallery div").not("."+selectedClass).fadeOut().removeClass('animation');
setTimeout(function() {
$("."+selectedClass).fadeIn().addClass('animation');
$("#gallery").fadeTo(300, 1);
}, 300);
});
});
	

	

	
	

	//alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;

</script>

<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>-->


<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>


    

<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>-->

<!-- 2012120  -->

<style>
.gallery {	
-webkit-column-count: 3;
-moz-column-count: 3;
column-count: 3;
-webkit-column-width: 33%;
-moz-column-width: 33%;
column-width: 33%; }
.gallery .pics {
-webkit-transition: all 350ms ease;
transition: all 350ms ease; }
.gallery .animation {
-webkit-transform: scale(1);
-ms-transform: scale(1);
transform: scale(1); }

@media (max-width: 450px) {
.gallery {
-webkit-column-count: 1;
-moz-column-count: 1;
column-count: 1;
-webkit-column-width: 100%;
-moz-column-width: 100%;
column-width: 100%;
}
}

@media (max-width: 400px) {
.btn.filter {
padding-left: 1.1rem;
padding-right: 1.1rem;
}
}
</style>

