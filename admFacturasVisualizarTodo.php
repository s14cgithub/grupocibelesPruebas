

<?php 

session_start(); 
$_SESSION['titulo']="FACTURAS - ABONO - CORREOS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	<table border ="0" align="center">		
		<tr>
			<td align="right">Año:</td>
			<td align="left"><select class=""  id="anio" name="anio" onChange="buscarFactura()"></select></td>
			<td align="right" colspan="6" style="text-align:right;">
			
			
				Visualizar: 
				<input type="checkbox" id="checkCibeles"> 
				<label for="checkCibeles">Cibeles</label>				
				<input type="checkbox" id="checkClayma">
				<label for="checkClayma">Clayma</label>
				<input type="checkbox" id="checkCorreos">
				<label for="checkCorreos">Correos</label>
			</td>
		</tr>
		<tr>
		<td align="right" colspan="1">Buscar por:</td>
		<td align="left">
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="cliente">Cliente</option>	
				<option value="numero">Numero</option>	
				<option value="precioNeto">Importe</option>
				<option value="tipo">Tipo</option>
						
			</select>
		</td>
		<td align="right" colspan="">Texto:</td>
		<td align="left" colspan=""><input class="" type="text" id="buscarTexto" name="buscarTexto"></input></td>
	
	
		<td align="right" colspan="1">Orden:</td>
		<td align="left" colspan=""><select class="" id="ordenBuscar">
					<option value="cliente" selected>Cliente</option>	
					<option value="fecha">Fecha</option>
					<option value="numero">Numero</option>					
					<option value="precioNeto">Precio Neto</option>		
					<option value="tipo">Tipo</option>
			</select>
		</td>

		<td align="right" colspan="1">Desc:</td>
		<td align="left" colspan=""> <input type="checkbox" id="ordenDesc"></input></td>
		
	</tr>
	<tr>
		<td align="right" colspan="1">Fecha Inicio:</td>
		<td align="left" colspan="1">
			<input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
		</td>
		<td align="right" colspan="1">Fecha Fin:</td>
		<td align="left" colspan="1">
			<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		</td>

		<td align="right" colspan="4">
			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
		</td>
	</tr>
	<tr><td colspan=""><hr></td></tr>
		
	</table>




<!--
	<table border ="0" align="center">		
			<tr>
				<td align="left">Año:</td>
				<td><select class=""  id="anio" name="anio" onChange="buscarFactura()"></select></td>
				<td align="right" style="padding-left: 100px;text-align: right;'">
				
				
				Visualizar: 
					<input type="checkbox" id="checkCibeles"> 
					<label for="checkCibeles">Cibeles</label>				
					<input type="checkbox" id="checkClayma">
					<label for="checkClayma">Clayma</label>
					<input type="checkbox" id="checkCorreos">
					<label for="checkCorreos">Correos</label>
				</td>
			</tr>
	</table>

<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="cliente">Cliente</option>	
				<option value="numero">Numero</option>	
				<option value="precioNeto">Importe</option>
				<option value="tipo">Tipo</option>
						
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		</td>
	
	
		<td align="left" colspan="3">
			Orden:
			<select class="" id="ordenBuscar">
					<option value="cliente" selected>Cliente</option>	
					<option value="fecha">Fecha</option>
					<option value="numero">Numero</option>					
					<option value="precioNeto">Precio Neto</option>		
					<option value="tipo">Tipo</option>	
						
					
									
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc"></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
			

		</td>	
	</tr>
	<tr>
		<td align="left" colspan="4">Fecha Inicio:
			<input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> Fecha Fin:<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		
		</td>

		
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>
-->


<!------------------------------------------------>

	<!--<table align="center">	
		
		
			<tr>					
				<td align="right">Cliente:</td> 
				<td align="left" colspan="2"><select name="buscarCliente" id="buscarCliente"></select></td>
			</tr>

			<tr>
				<td align="right">Fecha Inicio: </td> 
				<td align="left" colspan="2"><input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
				&nbsp;&nbsp;Fecha Fin:  
				<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
				
				</td>

			</tr>	
			<tr>
				<td align="right">Ordenar Por:</td>

				<td><select name="orden" id="orden">
						<option value="numero" selected>Factura</option>
						<option value="cliente">Cliente</option>
						<option value="precioTotal">Total</option>
						<option value="fecha">Fecha</option>
						<option value="aPagar">aPagar</option>
						

					</select>

					<input type="checkbox" id="ordenDesc2" checked>Desc</input>
				</td>

				<td colspan="" align="right">
					<input type="submit" class="btn btn-info" onClick="cargarListadoFacturas();" value="Buscar" ></input>					
					<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel"></input>

					

				</td>
			</tr>
	</table>-->

	<?php

	
	echo '<table align="center" class="tabla" style="border-color:grey;">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listadoFacturasSinEmitir" name="listadoFacturasSinEmitir"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->






<form id="formExportarAgenteComercialExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarFacturasAbonosCorreosTodo.php">		
	<input type="hidden" id="exportarACCondiciones" name="exportarACCondiciones" value=""></input>
	<input type="hidden" id="exportarACAnioSeleccionado" name="exportarACAnioSeleccionado" value=""></input>	
	<input type="hidden" id="exportarACAccion" name="exportarACAccion" value="exportarExcel"></input>			
</form>

<form id="formImprimirFactura"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirNumFactura" name="imprimirNumFactura" value=""></input>
	<input type="hidden" id="anioSeleccionado0" name="anioSeleccionado0" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirFacturaClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirNumFacturaClayma" name="imprimirNumFacturaClayma" value=""></input>
	<input type="hidden" id="anioSeleccionado3" name="anioSeleccionado3" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirAbono"  method="post"  target="_blank" action="imprimirAbono.php">
	<input type="hidden" id="imprimirNumFacturaAbono" name="imprimirNumFacturaAbono" value=""></input>	
	<input type="hidden" id="imprimirAnioSeleccionado" name="imprimirAnioSeleccionado" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbono"></input>	
</form>

<form id="formImprimirAbonoClayma"  method="post"  target="_blank" action="imprimirAbonoClayma.php">
	<input type="hidden" id="imprimirNumFacturaAbonoClayma" name="imprimirNumFacturaAbonoClayma" value=""></input>	
	<input type="hidden" id="imprimirAnioSeleccionadoClayma" name="imprimirAnioSeleccionadoClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbonoClayma"></input>	
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
<script  src="js/js_admFacturasVisualizarTodo.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	
	cargarAnios("anio");
	document.getElementById("anio").value = 2025;
	
	buscarFactura();
	//gestionAgenteComercial();

	
	
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>