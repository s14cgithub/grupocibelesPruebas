<?php 

session_start(); 
$_SESSION['titulo']="MEDIAS PROCESOS";
$ruta="/";
require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");


?>
	
	
	
	

<table class="d-none d-print-block" style="width: 100%">
			
			
	<tr style="width: 100%">
		<td><img src="imagenes/Logo Grupo Cibeles.jpg"></td>
		<td align="center" style="width: 100%"><h1>Informe de Medias de los Procesos</h2></td>

	</tr>
	<tr>
		<td align="center" colspan="2"><h2 id="InformeAnio"></h2></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><p>Informe realizado el día: <?php echo date("d/m/Y"). " a las " .date("H:i:s"); ?> </p></td>
	</tr>

	<tr><td colspan="2"></td></tr>
</table>



<form align="center"  class="d-print-none" >
		Año: <input class="tamanio7" type="text" id="buscarAnio" name="buscarAnio" value=""></input>
		<input type="button" class="btn btn-info" value="Buscar" onClick="cargarInformePorAnio()"></input>
		<button type="button" class="btn btn-info" onclick="javascript:window.print()">Imprimir</button>
</form>

<br>	
	

<table class="table" id="resultadoInforme" align="center" style="text-align: center"></table>
	
<table><tr><td><br></td></tr></table>


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
<script  src="js/js_informeProd_mediasProcesos.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	//document.getElementById("buscarAnio").value='<?php //echo $fechaAbuscar?>';
	
	window.onbeforeprint = (event) => {
		
		if (document.getElementById("buscarAnio").value== "")
		{
			alert("Introducir una año");
		}
		
	};
	
	

	//alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;

</script>