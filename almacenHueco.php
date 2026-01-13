

<?php 

session_start(); 
$_SESSION['titulo']="ALMACEN - HUECO";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


?>
	

<table align="center">

	<tr>
		<td align="right" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="hueco">Hueco</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	
	
		<td align="right" colspan="">
			Orden:
			<select class="" id="ordenBuscar">
				<option value="hueco">Hueco</option>							
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>			


		</td>	
	</tr>
	
	

	<tr><td colspan=""><hr></td></tr>
</table>




	<table align="center"  class="">		
		

	

<tr align="center">	
	<td>Hueco: </td>
	<td colspan="2"><input type="text" id="nombreHueco" style="width: 100%;"></input></td>
	<td colspan="" align="center"><input type="image" src="imagenes/crear.png" style="width:15px;"  onclick="guardarHueco()"></td>
</tr>
</table>
<table align="center" class="tabla">
<tbody id="historicoHueco" bgcolor=""> </tbody>

</table>


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
<script  src="js/js_almacenHueco.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">	
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>