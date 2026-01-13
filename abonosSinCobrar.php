


<?php 

session_start(); 
$_SESSION['titulo']="ABONOS SIN COBRAR";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");





if ($_SESSION["usuario"]<>"")
{
	?>
	<!--<table border ="0" align="center">
		
			<tr>
				<td align="right">Clayma:</td>
				<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="listadoFacturasPendientes()" style="" > </input></td>
			</tr>
	</table>-->

<table border ="0" align="center">
		
			<tr>
				<td align="right">Clayma:</td>
				<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionDeCargarClientesListado('buscarCliente')" style="" > </input></td>
			</tr>
	</table>





<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="numero">Abono</option>				
				<option value="cliente">Cliente</option>
				<option value="factura">Factura</option>
				<option value="precioTotal">Total</option>				
				<option value="aPagar">Total a Pagar</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
					<option value="numero">Abono</option>				
					<option value="cliente">Cliente</option>
					<option value="factura">Factura</option>
					<option value="precioTotal">Total</option>				
					<option value="aPagar">Total a Pagar</option>
					<option value="fecha">Fecha Factura</option>				
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoNumerosFacturasModal" data-whatever="@mdo">Imprimir</button>-->


		</td>	
	</tr>
	<tr>
		<td align="center">Fecha Inicio:
			<input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> Fecha Fin:<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		</td>

		
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>



	<?php
	
	
	echo '<table align="center" class="tabla">';
	
	echo '<tbody id="listadoFacturasPendientes" name="listadoFacturasPendientes"></tbody>';	
	
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
<script  src="js/js_abonosSinCobrar.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);	
	
</script>