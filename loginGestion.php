<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_login.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<?php 


ini_set("session.cookie_lifetime","1");
ini_set("session.gc_maxlifetime","1");

session_start(); 
$_SESSION['titulo']="LOGIN - USUARIOS";
$ruta="/";


require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
//require($ruta."Archivos Comunes/constantes.php");

/*session_name('Conection5!129mQzfjd28934') ;
echo session_name() ;
session_cache_limiter('private');
session_cache_expire(0.02);
echo '<br>'.session_cache_expire();
echo '<br>'.session_cache_limiter();

echo '<br>session_id: '.session_id() ;
session_regenerate_id(true);
echo '<br>session_id: '.session_id() ;

echo '<br>session_save_path(): '.session_save_path();

echo '<br>user_agent:'.$_SERVER['HTTP_USER_AGENT'];
echo '<br>'.session_cache_limiter();
echo '<br>remote_addr: '.$_SERVER['REMOTE_ADDR'];
echo '<br>REQUEST_TIME_FLOAT: '.$_SERVER['REQUEST_TIME_FLOAT'];
echo '<br>QUERY_STRING: '.$_SERVER['QUERY_STRING'];
echo '<br>DOCUMENT_ROOT: '.$_SERVER['DOCUMENT_ROOT'];
echo '<br>HTTP_ACCEPT: '.$_SERVER['HTTP_ACCEPT'];
echo '<br>HTTP_ACCEPT_CHARSET: '.$_SERVER['HTTP_ACCEPT_CHARSET'];
echo '<br>HTTP_ACCEPT_ENCODING: '.$_SERVER['HTTP_ACCEPT_ENCODING'];
echo '<br>HTTP_ACCEPT_LANGUAGE: '.$_SERVER['HTTP_ACCEPT_LANGUAGE'];
echo '<br>HTTP_CONNECTION: '.$_SERVER['HTTP_CONNECTION'];
echo '<br>HTTP_HOST: '.$_SERVER['HTTP_HOST'];
echo '<br>HTTP_REFERER: '.$_SERVER['HTTP_REFERER'];
echo '<br>HTTP_USER_AGENT: '.$_SERVER['HTTP_USER_AGENT'];
echo '<br>HTTPS: '.$_SERVER['HTTPS'];
echo '<br>REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR'];
echo '<br>REMOTE_PORT: '.$_SERVER['REMOTE_PORT'];
echo '<br>REMOTE_USER: '.$_SERVER['REMOTE_USER'];
echo '<br>REDIRECT_REMOTE_USER: '.$_SERVER['REDIRECT_REMOTE_USER'];
echo '<br>SCRIPT_FILENAME: '.$_SERVER['SCRIPT_FILENAME'];
echo '<br>SERVER_ADMIN: '.$_SERVER['SERVER_ADMIN'];
echo '<br>SERVER_PORT: '.$_SERVER['SERVER_PORT'];
echo '<br>SERVER_SIGNATURE: '.$_SERVER['SERVER_SIGNATURE'];
echo '<br>PATH_TRANSLATED: '.$_SERVER['PATH_TRANSLATED'];
echo '<br>SCRIPT_NAME: '.$_SERVER['SCRIPT_NAME'];
echo '<br>REQUEST_URI: '.$_SERVER['REQUEST_URI'];
echo '<br>PHP_AUTH_DIGEST: '.$_SERVER['PHP_AUTH_DIGEST'];
echo '<br>PHP_AUTH_USER: '.$_SERVER['PHP_AUTH_USER'];
echo '<br>PHP_AUTH_PW: '.$_SERVER['PHP_AUTH_PW'];
echo '<br>AUTH_TYPE: '.$_SERVER['AUTH_TYPE'];
echo '<br>PATH_INFO: '.$_SERVER['PATH_INFO'];
echo '<br>ORIG_PATH_INFO: '.$_SERVER['ORIG_PATH_INFO'];
echo '<br>PHP_SELF: '.$_SERVER['PHP_SELF'];*/


?>
<table align="center" border="0"  class="">
	
	<tr>
		<td>
			<table border ="0" align="center">
				<tr>
					<td align="center">
						<span style="margin-left: 100px">
							<span class="textoNegrita">Buscar</span>
						
							Empleado: <select name="listadoEmpleado1" id="listadoEmpleado1"></select>							
							usuario: <input class="tamanioFecha" type="text" id="buscarUsuario" name="buscarUsuario" value=""></input>
							
						</span>
						<input type="submit" class="btn btn-info" onClick="cargarLogin();" value="Buscar" ></input>
					</td>
				</tr>
				

				<tr>
					<td>
						<hr>
						<table style="text-align: center">
							<tr>
								<td>&nbsp;</td>
								<td align="center">Empleado</td>
								<td align="center">Usuario</td>							
								<td align="center">Contraseña</td>														
								<td align="center"></td>
							</tr>
							<tr>
								<td></td>
								
								<td><select id="empleadoNuevo" name="empleadoNuevo"></input> </td>							
								<td><input class="" type="text" id="usuarioNuevo" name="usuarioNuevo" value=""></input></td>
								<td><input class="" type="text" id="contrasenaNuevo" name="contrasenaNuevo" value=""></input></td>
								
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="insertarRegistroUsuario()" ></td>
							</tr>
						</table>

					</td>
				</tr>

			</table>
		</td>
	</tr>
			
	<tr>
		<td>
			<hr>
			<table border="1">
				<tbody id="usuarios" name="usuarios"></tbody>
			</table>
		</td>
	</tr>	
</table>

	
	






</div> <!-- class="tabla" -->

					
<div class="modal fade" id="permisos" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title" id="ModalLabel" style="text-align: center"><span  style="color:#007CBA; text-align: center">PERMISOS - <label id="idLoginModal" style="visibility: hidden; display: none;"></label> <label id="usuarioModal"></label></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  
		<form>                  
		<div class="form-group">
            
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_pda">
 	 			<label class="custom-control-label" for="pms_pda" style="padding-top: 3px !important;">PDA</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_pdaAdjunto">
 	 			<label class="custom-control-label" for="pms_pdaAdjunto"style="padding-top: 3px !important;">PDA - Subir fotos adjuntas</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_pda_gestion">
 	 			<label class="custom-control-label" for="pms_pda_gestion"style="padding-top: 3px !important;">PDA - Gestion</label>
			</div>
			
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_pdaConductor">
 	 			<label class="custom-control-label" for="pms_pdaConductor"style="padding-top: 3px !important;">PDA - Conductor</label>
			</div>
			
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_informesProduccion">
 	 			<label class="custom-control-label" for="pms_informesProduccion"style="padding-top: 3px !important;">Informes de Produccion</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_presupuestos">
 	 			<label class="custom-control-label" for="pms_presupuestos"style="padding-top: 3px !important;">Presupuestos</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_nuevoProcesoPresu">
 	 			<label class="custom-control-label" for="pms_nuevoProcesoPresu"style="padding-top: 3px !important;">Presupuestos - Crear Nuevos Procesos</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_cambiarFechaCompromisoPresu">
 	 			<label class="custom-control-label" for="pms_cambiarFechaCompromisoPresu"style="padding-top: 3px !important;">Presupuestos- Cambiar fecha de Compromiso</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_cambiarFechaAceptacionPresu">
 	 			<label class="custom-control-label" for="pms_cambiarFechaAceptacionPresu"style="padding-top: 3px !important;">Presupuestos- Cambiar fecha de Aceptacion</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_otBajada">
 	 			<label class="custom-control-label" for="pms_otBajada"style="padding-top: 3px !important;">Presupuestos- Ot Bajada</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_otAbierta">
 	 			<label class="custom-control-label" for="pms_otAbierta"style="padding-top: 3px !important;">Presupuestos- Ot Abierta</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_otTerminada">
 	 			<label class="custom-control-label" for="pms_otTerminada"style="padding-top: 3px !important;">Presupuestos- Ot Terminada</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_otBajadaAutomatico">
 	 			<label class="custom-control-label" for="pms_otBajadaAutomatico"style="padding-top: 3px !important;">Presupuestos- Ot Bajada Automatico</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_presupuestoMensual">
 	 			<label class="custom-control-label" for="pms_presupuestoMensual"style="padding-top: 3px !important;">Presupuestos - Mensual</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_prodOt">
 	 			<label class="custom-control-label" for="pms_prodOt"style="padding-top: 3px !important;">Produccion - Ot</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_prodGrabarFranqueo">
 	 			<label class="custom-control-label" for="pms_prodGrabarFranqueo"style="padding-top: 3px !important;">Produccion - Grabar Franqueo</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_prodFranqueoF12">
 	 			<label class="custom-control-label" for="pms_prodFranqueoF12"style="padding-top: 3px !important;">Produccion - Franqueo - F12</label>
			</div>
			
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_rutas">
 	 			<label class="custom-control-label" for="pms_rutas"style="padding-top: 3px !important;">Produccion - Rutas</label>
			</div>
			
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_administracion">
 	 			<label class="custom-control-label" for="pms_administracion"style="padding-top: 3px !important;">Administracion</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_admContabilidad">
 	 			<label class="custom-control-label" for="pms_admContabilidad"style="padding-top: 3px !important;">Administracion - Contabilidad</label>
			</div>
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_admFacturacion">
 	 			<label class="custom-control-label" for="pms_admFacturacion"style="padding-top: 3px !important;">Administracion - Facturacion</label>
			</div>
			
			
			
			<div class="custom-control custom-switch">
  				<input type="checkbox" class="custom-control-input" id="pms_actualizarDatos">
 	 			<label class="custom-control-label" for="pms_actualizarDatos"style="padding-top: 3px !important;">Actualizar Datos</label>
			</div>
			
			
			
			
           	 
          </div>
        </form>		
		
      </div>
		
      <div class="modal-footer">
      		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  	<button type="button" class="btn btn-secondary" data-dismiss="" onClick="modificarPermisos()">Guardar</button>
       		
      </div>						
    </div>
  </div>
</div>	

	
				


<?php
echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script language="javascript">
	idInputListado = "empleadoNuevo";
	cargarListadoEmpleado();
	idInputListado = "listadoEmpleado1";
	cargarListadoEmpleado();
	
	cargarLogin();

</script>