

<?php 

session_start(); 

$_SESSION['titulo']="IMPORTAR CERTIFICADOS O NOTIFICACIONES";
$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
?>


<table align="center" border="0"  class="">
	
	
	
	
	
	<tr><td colspan="2" style="text-align: center;"></td></tr>
	
	<tr>
		<td colspan="2" align="center"><b>PROCUTO</b></td>

	</tr>
	<tr><td colspan="2" style="text-align: center;"></td></tr>
	<tr>
		<td colspan="2" style="text-align: center;">		
			<input type="radio" id="certificados" name="producto" checked></input>
			<label for="certificados">Certificados</label>
			<input type="radio" id="notificaciones" name="producto" ></input>
			<label for="notificaciones">Notificaciones</label>
		</td>	
	</tr>

<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>

<tr>
		<td colspan="2" align="center"><b>FECHA DE DEPOSITO</b></td>

	</tr>
	<tr><td colspan="2" style="text-align: center;"></td></tr>
	<tr>
		<td colspan="2" style="text-align: center;">		
			<input type="date" id="fechaDeposito" name="fechaDeposito"></input>
				
			
		</td>	
	</tr>


	


<tr><td colspan="2"><hr></hr></td></tr>
	
	<tr><td colspan="2" style="text-align: center;">		
			<input type="file" class="" id="elArchivo" name="elArchivo" accept=".xlsx" multiple></input>
			<input type="button" class="" onClick="leerArchivo();" value="Enviar"></input>
		</td>
	</tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>
	<tr><td colspan="2" style="text-align: center;">&nbsp;</td></tr>

<a href="" download="" id="generarTxtCertificados" style="visibility: hidden">button</a>

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
<script  src="js/js_importarCertificadosYnotificaciones.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	document.getElementById('fechaDeposito').valueAsDate = new Date();

	document.getElementById("button-up").addEventListener("click", scrollUp);	

</script>