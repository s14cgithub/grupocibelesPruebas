

<?php 

session_start(); 
$_SESSION['titulo']="FACTURAS DE CORREOS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	<table border ="0" align="center">
			<!--<tr>
				<td align="right">Año:</td>
				<td><select class=""  id="anioSeleccionado" name="anioSeleccionado" onChange="buscarFactura()"></select></td>
			</tr>-->
			<tr>					
				<td align="right">Cliente:</td> 
				<td align="left" colspan="2"><select name="buscarCliente" id="buscarCliente"></select></td>
			</tr>

			<tr>
				<td align="right">Fecha Inicio: </td> 
				<td align="left" colspan="2"><input class="tamanioFecha" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
				&nbsp;&nbsp;Fecha Fin:  
				<input class="tamanioFecha" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
				<!--&nbsp;&nbsp;&nbsp;<input type="checkbox" id="domiciliada" name="domiciliada" style="padding-left: 10px"> Domiciliada</input>-->
				

				&nbsp;&nbsp;&nbsp;Ordenar Por:

				<select name="orden" id="orden">
						<option value="aPagar">aPagar</option>	
						<option value="nombre_franqueo">Cliente</option>
						<option value="numeroOficial">Factura</option>						
						<option value="fecha" selected>Fecha</option>
						<option value="importe">Importe</option>
					</select>

					<input type="checkbox" id="ordenDesc" checked>Desc</input>
				</td>

			</tr>	
			<tr>
				<td></td><td></td>

				<td colspan="" align="right">
					<input type="submit" class="btn btn-info" onClick="cargarListadoFacturasCorreos();" value="Buscar" ></input>					
					<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCorreos()" value="Excel" ></input>

				</td>
			</tr>
	</table>

	<?php
	
	echo '<table align="center" class="tabla">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listadoPF" name="listadoPF"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>



</div> <!-- class="tabla" -->


<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarFacturasCorreosExcel.php">
	<input type="hidden" id="exportarCliente" name="exportarCliente" value=""></input>	
	<input type="hidden" id="exportarFechaInicio" name="exportarFechaInicio" value=""></input>	
	<input type="hidden" id="exportarFechaFin" name="exportarFechaFin" value=""></input>	
	<input type="hidden" id="exportarOrdenarPor" name="exportarOrdenarPor" value=""></input>	
	<input type="hidden" id="exportarDesc" name="exportarDesc" value=""></input>	
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>	
			
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
<script  src="js/js_facturasCorreos.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	//cargarAnios("anioSeleccionado");
	cargarClientes('B','buscarCliente');
	//document.getElementById("buscarCliente").innerHTML = '<option value="0">Todos</option>;	';
	var fechaActual = new Date();
	
	/*var dia=("0" + (fechaActual.getDate())).slice(-2);	
	var mes=("0" + (fechaActual.getMonth()+1)).slice(-2);
	document.getElementById("buscarFechaFin").value = fechaActual.getFullYear() + "-" + mes + "-" + dia;
	*/
	
	fechaActual.setMonth(fechaActual.getMonth() - 1);
	var mes=("0" + (fechaActual.getMonth()+1)).slice(-2);
	document.getElementById("buscarFechaInicio").value = fechaActual.getFullYear() + "-" +mes + "-01";
	
	



	
	
		
	
	//document.getElementById("buscarFechaFin").value = '2022/03/05';
	
	cargarListadoFacturasCorreos();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>