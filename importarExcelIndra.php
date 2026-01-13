

<?php 

session_start(); 

$_SESSION['titulo']="IMPORTAR EXCEL - INDRA";
$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
?>


<table align="center" border="0"  class="">
	
	
	
	
	
	


	


<tr><td colspan="2"><hr></hr></td></tr>
	
	<tr><td colspan="2" style="text-align: center;">		
			<input type="file" class="" id="elArchivo" name="elArchivo" accept=".xlsx" multiple></input>
			<input type="button" class="" onClick="subirArchivoGenerico();" value="Enviar"></input>

		</td>
	</tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>

<a href="" download="" id="generarTxtCertificados" style="visibility: hidden">button</a>
<a href="" download="" id="descargarArchivoIndra" style="visibility: hidden">button</a>
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
<script  src="js/js_importarExcelIndra.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	document.getElementById('fechaDeposito').valueAsDate = new Date();

	document.getElementById("button-up").addEventListener("click", scrollUp);

</script>