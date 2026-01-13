

<?php 

session_start(); 
$_SESSION['titulo']="FACTURAS CORREOS SIN COBRAR";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	?>

<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="numeroOficial">Factura</option>				
				<option value="codigo_saldo">Codigo Saldo</option>				
				<option value="nombre_franqueo">Franqueo</option>				
				<option value="aPagar">Total a Pagar</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
					<option value="numeroOficial">Factura</option>				
				<option value="codigo_saldo">Codigo Saldo</option>				
				<option value="nombre_franqueo">Franqueo</option>				
				<option value="aPagar">Total a Pagar</option>	
					<option value="fecha">Fecha</option>				
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
	
	
	echo '<tbody id="listadoFacturasCorreosPendientes" name="listadoFacturasCorreosPendientes"></tbody>';
	
	
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
<script  src="js/js_facturasCorreosPendientes.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">
	
	buscarFactura();
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>