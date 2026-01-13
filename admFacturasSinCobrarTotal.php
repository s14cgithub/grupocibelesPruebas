<?php 

session_start(); 
$_SESSION['titulo']="FACTURAS SIN COBRAR - TODO";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]!="")
{
	
	?>


<table border ="0" align="center" style="visibility: hidden;display: none;">		
			<tr>
				<td align="right">Clayma:</td>
				<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionDeCargarClientesListado('buscarCliente')" style="" > </input></td>
			</tr>
</table>
<table align="center">
	
	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				
				<option value="t1.cliente" >Cliente</option>
				<option value="t1.codigo_saldo">codigo_saldo</option>
				<option value="t1.factura" selected>Factura</option>
				<option value="t1.importe">Total</option>
				<option value="t1.aPagar">Total a Pagar</option>
				
								
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
			
			<select class="" id="ordenBuscar">					
					<option value="t1.cliente">Cliente</option>
					<option value="t1.cliente, t1.idCliente">Cliente, idCliente</option>
					<option value="t1.codigo_saldo">codigo_saldo</option>				
					<option value="t1.factura">Factura</option>
					<option value="t1.fecha" selected>Fecha</option>
					<option value="t1.importe">Total</option>	
					<option value="t1.aPagar">Total a Pagar</option>
					<option value="totalCliente">Total agrupado por Cliente</option>
				
				
									
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			
			<button type="button" class="btn btn-info" onClick="gestionImprimir()">Imprimir</button>

			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaSinCobrar()" value="Excel" ></input>

		</td>	
	</tr>
	<tr>
		<td>Origen: 
			<select class="" id="buscarPorOrigen">	
				<option value="todos" selected>Todos</option>				
				<option value="CIBELES">Cibeles</option>	
				<option value="CLAYMA">Clayma</option>
				<option value="CORREOS">Correos</option>	
			</select>
			<span style="margin-left: 10px">&nbsp;</span>
		Fecha Inicio:
			<input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> Fecha Fin:<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
			<input type="checkbox" id="domiciliada" name="domiciliada" style="padding-left: 10px"> Domiciliada</input>
		</td>
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>


	
	<!--<table border ="0" align="center">
		
		<tr style="display: none; visibility: hidden">					
			<td align="right">Clayma:</td> 
			<td align="left" colspan="2"><input type="checkbox" name="clienteOrigen" id="clienteOrigen"></input></td>
		</tr>
		
		<tr>					
			<td align="right">Cliente:</td> 
			<td align="left" colspan="2"><select name="buscarCliente" id="buscarCliente"></select></td>
		</tr>

		<tr>
			<td align="right">Fecha Inicio: </td> 
			<td align="left" colspan="2"><input class="tamanioFecha" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
			&nbsp;&nbsp;Fecha Fin:  
			<input class="tamanioFecha" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
			&nbsp;&nbsp;&nbsp;<input type="checkbox" id="domiciliada" name="domiciliada" style="padding-left: 10px"> Domiciliada</input>
			</td>

		</tr>	
		<tr>
			<td align="right">Ordenar Por:</td>

			<td><select name="orden" id="orden">
					<option value="Origen">Origen</option>
					<option value="Factura">Factura</option>
					<option value="Cliente">Cliente</option>
					<option value="importe">Total</option>
					<option value="aPagar">aPagar</option>
					<option value="Fecha" selected>Fecha</option>

				</select>

				<input type="checkbox" id="ordenDesc">Desc</input>
			</td>

			<td colspan="" align="right">
				<input type="submit" class="btn btn-info" onClick="listadoFacturasPendientesTotal();" value="Buscar" ></input>
				<input type="submit" class="btn btn-info" onClick="gestionInformeFacSinCobrar()" value="Imprimir" ></input>

			</td>
		</tr>
</table>-->
	
<?php	
	echo '<table align="center" class="tabla">';
		
	
	echo '<tbody id="listadoFacturasPendientesTotal" name="listadoFacturasPendientesTotal"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>


<form id="formImprimirInforme" name="formImprimirInforme" method="post"  target="_blank" action="imprimirInformeFacturaSinCobrarTotal.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInforme"></input>	
	<input type="hidden" id="imprimirCondicion" name="imprimirCondicion" value=""></input>	
	<input type="hidden" id="imprimirDomiciliado" name="imprimirDomiciliado" value=""></input>	
	<input type="hidden" id="imprimirFechaInicio" name="imprimirFechaInicio" value=""></input>	
	<input type="hidden" id="imprimirFechaFin" name="imprimirFechaFin" value=""></input>	
</form>

<form id="formExcelFacturasSinCobrar" method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarFacturasSinCobrar.php">
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>	
	<input type="hidden" id="exportarExcel_Condicion" name="exportarExcel_Condicion" value=""></input>
	<input type="hidden" id="exportarExcel_Domiciliado" name="exportarExcel_Domiciliado" value=""></input>	
	<input type="hidden" id="exportarExcel_FechaInicio" name="exportarExcel_FechaInicio" value=""></input>	
	<input type="hidden" id="exportarExcel_FechaFin" name="exportarExcel_FechaFin" value=""></input>	
</form>




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
<script  src="js/js_facturasSincobrarTotal.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">

<?php 
		
	if (isset($_SESSION["facturasSinCobrar_texto"]))
	{
		if ($_SESSION['facturasSinCobrar_domiciliada']=="true")
		{
			echo ('document.getElementById("domiciliada").checked=true;');
		}
		if ($_SESSION['facturasSinCobrar_ordenDesc']=="false")
		{
			echo ('document.getElementById("ordenDesc").checked=false;');
		}
		
		echo ('document.getElementById("buscarCampo").value =\''.$_SESSION["facturasSinCobrar_queBusca"].'\';');
		echo ('document.getElementById("buscarTexto").value =\''.$_SESSION["facturasSinCobrar_texto"].'\';');
		echo ('document.getElementById("ordenBuscar").value ="'.$_SESSION["facturasSinCobrar_orden"].'";');
		echo ('document.getElementById("buscarPorOrigen").value ="'.$_SESSION["facturasSinCobrar_origen"].'";');
		echo ('document.getElementById("buscarFechaInicio").value ="'.$_SESSION["facturasSinCobrar_fechaInicio"].'";');
		echo ('document.getElementById("buscarFechaFin").value ="'.$_SESSION["facturasSinCobrar_fechaFin"].'";');		
	}



	?>







	//listadoFacturasPendientesTotal();
	buscarFactura();
	//cargarClientes('A','buscarCliente');
	//cargarClientes('A','clientesModal');
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>