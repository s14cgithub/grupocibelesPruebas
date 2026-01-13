<?php 

session_start(); 
$_SESSION['titulo']="OT's POR FECHA";
$ruta="/";
require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
?>	
	
	

<table class="d-none d-print-block sinBordesMargenPaddin" style="width: 100%">
			
			
	<tr style="width: 100%">
		<td><img src="imagenes/Logo Grupo Cibeles.jpg"></td>
		<td align="center" style="width: 100%" class="sinBordesMargenPaddin"><h2  style="color: black" class="sinBordesMargenPaddin">Informe  de OT por Fecha</h2></td>
	</tr>
	<!--<tr>
		<td align="center" colspan="2" class="sinBordesMargenPaddin"><h2 class="sinBordesMargenPaddin" style="color: black" id="InformeDia"></h2></td>
	</tr>-->
	<!--<tr>
		<td colspan="2" align="right" class="sinBordesMargenPaddin"><p style="color: black" class="sinBordesMargenPaddin">Informe realizado el día: <?php //echo date("d/m/Y"). " a las " .date("H:i:s"); ?> </p></td>
	</tr>-->
	<tr>
		<td colspan="2" align="right" class="sinBordesMargenPaddin"><p style="color: black" class="sinBordesMargenPaddin"><span id="InformeDia" class="sinBordesMargenPaddin" style="font-size:30px"></span>&nbsp;&nbsp;&nbsp;&nbsp;<span align="right">Informe realizado el día: <?php echo date("d/m/Y"). " a las " .date("H:i:s"); ?></span></p></td>
	</tr>
	<tr>
		<td colspan="2" align="center" ><h2 style="color: black" id="informeNombreEmpleado"></h2></td>
	</tr>
	<tr>
		<td colspan="2" align="center" ><p style="color: black;font-size:20px" id="informeTiemposEmpleado"></p></td>
	</tr>

	<!--<tr><td colspan="2"></td></tr>-->
</table>



<form align="center"  class="d-print-none" >
		Fecha Inicio: <input class="tamanioFecha" type="date" id="buscarFecha" name="buscarFecha" value="" onChange="cambiarFechaFin('buscarFecha','buscarFechaFin')"></input>
		Fecha Fin: <input class="tamanioFecha" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		<input type="button"  class="btn btn-info" value="Buscar" onClick="cargarInformePorFecha()"></input>
		<button type="button"  class="btn btn-info" onclick="javascript:window.print()">Imprimir</button>		
</form>

<br>	
	

<table class="table" id="resultadoInforme" align="center" style="text-align: center"></table>
	
<table><tr><td><br></td></tr></table>


</div> <!-- class="tabla" -->


<form id="formImprimirSancionEmpleado" name="formImprimirSancionEmpleado" method="post"  target="_blank" action="imprimirSancionEmpleado.php">
	<input type="hidden" id="empleado_sancion" name="empleado_sancion" value=""></input>	
	<input type="hidden" id="fechaInicio_sancion" name="fechaInicio_sancion" value=""></input>	
	<input type="hidden" id="fechaFin_sancion" name="fechaFin_sancion" value=""></input>	
	<input type="hidden" id="tiempoArealizar_sancion" name="tiempoArealizar_sancion" value=""></input>	
	<input type="hidden" id="tiempoTrabajado_sancion" name="tiempoTrabajado_sancion" value=""></input>	
	<input type="hidden" id="diferencia_sancion" name="diferencia_sancion" value=""></input>	
	<input type="hidden" id="imprimirAccionSancion" name="imprimirAccionSancion" value="imprimirSancion"></input>		
</form>







<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php
echo ("</div>");
echo ("</body>");
echo ("</html>");

?>



<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_informeProd_ot_por_fecha.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">	

	//document.getElementById("buscarFecha").value='<?php //echo $fechaAbuscar?>';
	
	window.onbeforeprint = (event) => {
		
		if (document.getElementById("buscarFecha").value== "")
		{
			alert("Elegir una fecha de Inicio");
		}
		if (document.getElementById("buscarFechaFin").value== "")
		{
			alert("Elegir una fecha Fin");
		}
		
	};


	
	

	//alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;

</script>