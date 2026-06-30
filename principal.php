
<?php  //NUEVA VERSION

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


<?php
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

?>

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

