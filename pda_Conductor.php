
<?php 

session_start(); 
$_SESSION['titulo']="CONDUCTOR";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");






?>
	

<!--<table class="table sinBorde pdaConductor">-->
<table class="tabla" style="border: none" align="center">
	
	
	<!--<input type="text" id="nombre" name="nombre" value=""></input>-->
	
	<tr style="border: none">
		<td col colspan="4" style="text-align: center;"><h3><label><?php $dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"); echo ($dias[date("w")]); ?></label><label id="conductorRuta" name="conductorRuta">Ruta</label></h3></td>

		
		
	</tr>
	
	<tr style="border-bottom: 1pt solid">
		<td><b>HORA</b></td>
		<td><b>CLIENTE</b></td>
		<td><b>DATOS</b></td>
		<td><b>FIRMA</b></td>
	</tr>
	
	
	<tr><tbody id="lasRutas"></tbody></tr>
	







</table>




			
			
<!--nombre y dni -->
<div class="modal fade" id="nombreYdni" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header555" style="text-align: center;margin-top: 10px;">
        <h5 class="modal-title" id="ModalLabel" style="text-align: center"><span  style="color:#007CBA; text-align: center"><label id="firmaIdCliente1" style="visibility: hidden; display: none"></label><label id="firmaCliente1"></label><br><label id="firmaHoraRuta1"></label></span></h5>
        
		  <div class="modal-body">
		  
			 <form>                  
				<div class="form-group">
					<label for="recipient-name" class="col-form-label">Nombre</label>
					<input type="text" id="nombrePersonaRecogida1" name="nombrePersonaRecogida1"   onBlur="cargarDniRuta()" style="width: 95%;"></input>
				</div>
			</form>
			<form>                  
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">DNI:</label> 
				<input type="text" id="dniPersonaRecogida1" name="dniPersonaRecogida1"></input>
			  </div>		         
			</form>		
     	 </div>
      </div>
		 <div class="modal-footer">
			 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>    
			<button type="button" class="btn btn-secondary"  onClick="guardarNombreRuta()">Guardar</button>          	    
      </div>      
    </div>
  </div>
</div>

 
			
	<!--FIRMA -->
<div class="modal fade" id="firma" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header555" style="text-align: center;margin-top: 10px;">
        <h5 class="modal-title" id="ModalLabel" style="text-align: center"><span  style="color:#007CBA; text-align: center"><label id="firmaIdCliente" style="visibility: hidden; display: none"></label><label id="firmaCliente"></label></span></h5>
		  
		  <div class="embed-responsive embed-responsive-1by1">
		  <iframe id="iframeFirma" class="embed-responsive-item" src=""></iframe>
		</div>
      </div>
		 <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>        
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="verFirma" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header555" style="text-align: center;margin-top: 10px;">
        <h5 class="modal-title" id="ModalLabel" style="text-align: center"><span  style="color:#007CBA; text-align: center"><label id="firmaIdCliente2" style="visibility: hidden; display: none"></label><label id="firmaCliente2"></label></span></h5>
       
		 
		  <img id="imagenFirma" src=""></img>
		
      </div>
		     
			
     
		
      
    </div>
	
  </div>
<center><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></center>
</div>	




<div class="modal fade" id="clienteDetallesModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title" id="ModalLabel" style="text-align: center"><span  style="color:#007CBA; text-align: center"><label id="clienteDetalles"></label></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  
		<form>   
		<div class="form-group">
            <label for="recipient-name" class="col-form-label"><b>Contacto:</b><br> <span id="clienteContactos"></span></label>
           	 
          </div>               
		<div class="form-group">
            <label for="recipient-name" class="col-form-label"><b>Observaciones:</b><br> <span id="clienteObservaciones"></span></label>
           	 
          </div>
        </form>
		<form>                  
		<div class="form-group">
			<label for="recipient-name" class="col-form-label"><b>Direccion:</b><br><label id="clienteDireccion"></label> <a href="http://maps.google.com?q=lcalle torneros, getafe" target="_blank" id="clienteDireccionMapa"><br>Mostrar Mapa</a> <span id="clienteDireccion"></span></label>            
          </div>		         
        </form>
		
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       		
      </div>
    </div>
  </div>
</div>	
	
				


<?php

echo ("</div>");

echo ("</body>");

echo ("</html>");



?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_conductor.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>



<script language="javascript"> 
	cargarLasRutasDelDia();
	
	
	$('#nombreYdni').on('hidden.bs.modal', function (e) {
		verSiTieneNombreYdniRuta(valorNumero,valorHoraRuta);	  
	 	valorNumero="";
	 	valorHoraRuta="";
	})
	
	$('#firma').on('hidden.bs.modal', function (e) {
		verSiTieneFirma(valorNumero,valorHoraRuta);	  
	 	valorNumero="";
	 	valorHoraRuta="";
	})	
	
		
	
	
			
</script>

