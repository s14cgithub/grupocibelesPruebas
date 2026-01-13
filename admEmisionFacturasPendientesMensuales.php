
<?php 

session_start(); 
$_SESSION['titulo']="PRE-FACTURAS - MENSUALES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	?>

<table align="center">

	<tr>
		<td align="center" colspan="6">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="numero">Factura</option>				
				<option value="cliente">Cliente</option>
				<option value="descripcion">Campaña</option>				
				<option value="fecha">Fecha</option>
			</select>

		Texto:
		<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		Orden: 
		<select class="" id="ordenBuscar">
				<option value="numero">Factura</option>				
				<option value="cliente">Cliente</option>
				<option value="descripcion">Campaña</option>				
				<option value="fecha">Fecha</option>
				
		</select>
	Desc: <input type="checkbox" id="buscarDesc"></input>
	
	
		<button type="button" class="btn btn-info" onClick="buscarPrefacturaMensual()">BUSCAR</button>
		<!--<button id="tablaBotonCombinar" style="visibility:hidden;display:none" type="button" class="btn btn-info" onClick="combinarPresupuestos()">COMBINAR PRESUPUESTOS</button>-->
	
	</td>		
	</tr>

	<tr><td colspan="6"><hr></td></tr>
</table>



	<?php
	
	echo '<table align="center"  class="tabla">';
	
	echo '<tbody id="listadoFacturasSinEmitirMensuales" name="listadoFacturasSinEmitirMensuales"></tbody>';	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>


</div> <!-- class="tabla" -->



<form id="formIrAprefacturaMensual" name="formIrAprefacturaMensual" method="post"  target="" action="prefacturaMensual.php">
	
	<input type="hidden" id="preFacturaNumFactura" name="preFacturaNumFactura" value=""></input>
	<input type="hidden" id="preFacturaAnioSeleccionado" name="preFacturaAnioSeleccionado" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="verPrefacturaMensual"></input>	
	
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
<script  src="js/js_admEmisionFacturaPendienteMensual.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	cargarListadoFacturasSinEmitirMensuales();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>