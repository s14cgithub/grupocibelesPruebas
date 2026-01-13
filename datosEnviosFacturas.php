


<?php 

session_start(); 
$_SESSION['titulo']="CLIENTES - DATOS PARA EL ENVIO DE FACTURAS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	
	?>



	<table border ="0" align="center">		
		<tr>
			<td align="right">Clayma:</td>
			<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="buscarFactura('buscarCliente')" style="" > </input></td>
			<td>&nbsp;</td>
		</tr>
	
	</table>



	<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="codigo" selected>Codigo</option>
				<option value="nombre_empresa" >Cliente</option>
				<option value="fecha">Fecha</option>
				<option value="observacion">Observacion</option>
				
				
				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	Orden:
	
		<td align="left" colspan="3">
			
			<select class="" id="ordenBuscar">
				<option value="codigo" selected>Codigo</option>
				<option value="nombre_empresa">Cliente</option>
				<!--<option value="fecha">Fecha</option>-->
				<option value="observacion">Observacion</option>
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc"></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelClientesCibeles()" value="Excel" ></input>-->
			


		</td>	
	</tr>
	
	

	<tr><td colspan=""><hr></td></tr>
</table>



	<?php
	
	echo '<table align="center" class="tabla">';
	
	
	
	echo '<tbody id="listadoClientesObservaciones" name="listadoClientesObservaciones"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->


<!--<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarClientesCibelesExcel.php">	
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
</form>-->

<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php




echo ("</div>");
echo ("</body>");
echo ("</html>");

?>
<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_clientesDatosEnvioFacturas.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>