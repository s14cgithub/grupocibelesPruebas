


<?php 

session_start(); 
$_SESSION['titulo']="ALMACEN - PRE-ENTRADA";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	

	<table align="center" border="0"  class="">
		
		<tr>
			<td><input type="file" name="imagen_Almacen" id="imagen_Almacen" class="btn" accept=".jpg,.png,.gif" /><button type="button" class="btn btn-primary" data-dismiss="" onClick="subirImagenAlmacen()">Subir Imagen</button></td>
			
		</tr>
		
	</table>


	
	<?php
	
	echo '<table align="center" class="tabla">';
	
	echo '<tbody id="listadoPreEntradas" name="listadoPreEntradas"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->

<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>






<?php

echo ("</div>");
echo ("</body>");
echo ("</html>");

?>
<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_almacenPreEntrada.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	cargarListadoPreEntrada();	
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>