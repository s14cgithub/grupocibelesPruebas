


<?php 

session_start(); 
$_SESSION['titulo']="CLIENTES LISTADO";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	
	?>



	<table border ="0" align="center">		
		<tr>
			<td align="right">Clayma:</td>
			<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionDeCargarClientesListado('buscarCliente')" style="" > </input></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="right">Solo Retenidas:</td>
			<td align="left"><input type="checkbox" id="clienteRetener" name="clienteRetener" value="" onchange="" style="" > </input></td>

			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="right">Activos, Correo Diario, Fijos y Mensuales:</td>
			<td align="left"><input type="checkbox" id="clienteActivosFijosMensual" name="clienteActivosFijosMensual" value="" onchange="" style="" > </input></td>

			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="right">Activos y Fijos:</td>
			<td align="left"><input type="checkbox" id="clienteActivosFijos" name="clienteActivosFijos" value="" onchange="" style="" > </input></td>

			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td align="right">Activos:</td>
			<td align="left"><input type="checkbox" id="clienteActivos" name="clienteActivos" value="" onchange="" style="" > </input></td>


		</tr>
	
	</table>



	<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="nombre_empresa" >Cliente</option>
				<option value="codigo_postal">CP</option>
				<option value="direccion">Direccion</option>				
				<option value="nombre_franqueo">Franqueo</option>
				<option value="Localidad">Localidad</option>
				<option value="nif" selected>Nifcliente</option>
				<option value="codigo" selected>Numero</option>
				<option value="subCliente" >SubCliente</option>
				
				
				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
				<option value="codigo">Numero</option>
				<option value="nombre_empresa" selected>Cliente</option>
				<option value="nombre_franqueo">Franqueo</option>
				<option value="direccion">Direccion</option>
				<option value="codigo_postal">CP</option>
				<option value="Localidad">Localidad</option>
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc"></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelClientesCibeles()" value="Excel" ></input>
			


		</td>	
	</tr>
	
	

	<tr><td colspan=""><hr></td></tr>
</table>



	<?php
	
	echo '<table align="center" class="tabla">';
	
	
	
	echo '<tbody id="listadoClientes" name="listadoClientes"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->


<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarClientesCibelesExcel.php">	
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>
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
<script  src="js/js_clientesListado.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>