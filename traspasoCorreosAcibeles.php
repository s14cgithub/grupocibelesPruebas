

<?php 

session_start(); 

$_SESSION['titulo']="TRASPASO DE CORREOS A CIBELES";
$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
?>


<table align="center" border="0"  class="">
	
	
	
	
	
	<tr><td colspan="2" style="text-align: center;"></td></tr>
	
	<tr style="visibility: hidden">
		<td colspan="2" align="center"><b>Tratamiento de duplicados</b></td>

	</tr>
	<tr><td colspan="2" style="text-align: center;"></td></tr>
	<tr style="visibility: hidden">
		<td colspan="2" style="text-align: center;">		
			<input type="radio" id="reemplazar" name="proceso" ></input>
			<label for="reemplazar">Reemplazar</label>
			<input type="radio" id="ignorar" name="proceso" checked></input>
			<label for="ignorar">Ignorar</label>
		</td>
	
	</tr>
	



	<!--
	<tr><td colspan="2"><hr></hr></td></tr>
	<tr><td colspan="2" style="text-align: center;">		
			<input type="file" class="" id="elArchivo" name="elArchivo" accept=".txt" multiple></input>
			<input type="button" class="" onClick="leerArchivo();" value="Enviar"></input>
		</td>
	</tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
</table>

-->
<table align="center">
	<hr>
	<tr>
		<td  align="center">
			<h1>SIDI - Facturas</h1>
		</td>
	</tr>
	<tr>
		<td>
			<input type="file" class="" id="elArchivoExcelMensual" name="elArchivoExcelMensual" accept=".xlsx"></input>
			<button type="button" class="btn btn-primary" data-dismiss="" onClick="subirDatosCorreosMensualesDesdeExcel()">Importar Facturas</button>
		</td>
	</tr>
	
</table>



<table align="center">
	<hr>
	<tr>
		<td  align="center">
			<h1>SIDI - Albaranes</h1>
		</td>
	</tr>
	<tr>
		<td>
			<input type="file" class="" id="elArchivoExcelMensualAlbaran" name="elArchivoExcelMensualAlbaran" accept=".xlsx"></input>
			<button type="button" class="btn btn-primary" data-dismiss="" onClick="subirDatosCorreosMensualesDesdeExcelAlbaran()">Importar Albaranes</button>
		</td>
	</tr>
	
</table>




 
<?php	
}
?>

	
<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>




<?php



echo ("</div>");
echo ("</body>");
echo ("</html>");

?>


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_traspasoCorreosACibeles.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	

	/*$(".component-container").sortable({
cursor: "move",
items: ".component-section",
placeholder: "ui-state-highlight",
start: function(e, ui) {
ui.placeholder.width(ui.item.find(".component-section").width());
ui.placeholder.height(ui.item.find(".component-section").height());
ui.placeholder.addClass(ui.item.attr("class"));
}
});*/
	

document.getElementById("button-up").addEventListener("click", scrollUp);
	
	
	

</script>