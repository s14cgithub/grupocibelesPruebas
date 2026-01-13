
<script  src="js/js_admFacturasAnteriores.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<?php 

session_start(); 
$_SESSION['titulo']="FACTURAS SIN COBRAR - AÑOS ANTERIORES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	<table border ="0" align="center">		
		<tr>
			<td align="right">Clayma:</td>
			<td align="left"><input type="checkbox" id="clayma" name="clayma" value="" onchange="" style="" >&nbsp;&nbsp;</input></td>


			<td align="right">Correos:</td>
			<td align="left"><input type="checkbox" id="correos" name="correos" value="" onchange="" style="" > </input>&nbsp;&nbsp;</td>

			<td align="right">Año:</td>
			<td align="left">
				
				<select id="anio">
					<option value="2021">2021</option>
					<option value="2020">2020</option>
					<option value="2019">2019</option>
					<option value="2018">2018</option>
					
				
				
				</select>&nbsp;&nbsp;</td>


			<!--<td align="right">Todos los Registros:</td>
			<td align="left"><input type="checkbox" id="todos" name="todos" value="" onchange="" style="" > </input></td>-->

			<td align="left"><button type="button" class="btn btn-info" onClick="listadoFacPendAnt()">BUSCAR</button></td>

			

			
		</tr>
	</table>
	
	
	
	<table align="center" class="tabla">
	
	<tbody id="listFacPendAnt" name="listFacPendAnt"></tbody>
	
	</table>
	<?php
	
}
else
{
	header('Location: index.php');	
}

?>


</div> <!-- class="tabla" -->



<?php







echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script language="javascript">
	listadoFacPendAnt();
	
</script>