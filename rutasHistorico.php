
<?php 

session_start(); 
$_SESSION['titulo']="RUTAS HISTORICO";
$ruta="/";


require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
//require($ruta."Archivos Comunes/constantes.php");


?>
<table align="center" border="0"  class="">
	
	<tr>
		<td>
			<hr>
			<table class="tabla" align="center">
				<tbody id="rutasHistorico" name="rutasHistorico"></tbody>
			</table>
		</td>
	</tr>	
</table>

	
	








<div class="modal fade" id="verFirmaHistorico" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header555" style="text-align: center;margin-top: 10px;">
      	 
		  <img id="imagenFirma" src=""></img>
      </div>      
    </div>	
  </div>
<center><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></center>
</div>						


	
	</div> <!-- class="tabla" -->			


<?php
echo ("</div>");
echo ("</body>");
echo ("</html>");





?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_rutasHistorico.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">	
	
	
	cargarRutasHistorico();
	

</script>