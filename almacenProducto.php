

<?php 

session_start(); 
$_SESSION['titulo']="ALMACEN - PRODUCTO";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


?>
	

<table align="center">

	<tr>
		<td align="right" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="t1.codigo">Codigo Producto</option>	
				<option value="t1.nombre">Nombre Producto</option>	
				<option value="t2.subCliente">Nombre Cliente</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	
	
		<td align="right" colspan="">
			Orden:
			<select class="" id="ordenBuscar">
				<option value="t1.codigo">Codigo Producto</option>	
				<option value="t1.nombre">Nombre Producto</option>	
				<option value="t2.subCliente">Nombre Cliente</option>		
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>			


		</td>	
	</tr>
	
	

	<tr><td colspan=""><hr></td></tr>
</table>




	<table align="center"  class="">		
		

	

<tr align="center">	
	<td>Cliente: </td>
	<td colspan=""><select id="nombreCliente" style="width: 100%;"></select></td>
	<td>&nbsp;&nbsp;Producto: </td>
	<td colspan=""><input type="text" id="nombreProducto" style="width: 100%;"></input></td>
	<td>&nbsp;&nbsp;Codigo: </td>
	<td colspan=""><input type="text" id="codigoProducto" style="width: 100%;"></input></td>
	<td colspan="" align="center"><input type="image" src="imagenes/crear.png" style="width:15px;"  onclick="guardarProducto()"></td>
</tr>
</table>


<table align="center" class="tabla">
<tbody id="historicoProductos" bgcolor=""> </tbody>

</table>


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
<script  src="js/js_almacenProducto.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">	
	
	
	//idInputListado="nombreProveedor";
	//cargarListadoProveedoresAlmacen();
	cargarSubClientes("A","nombreCliente");
	
	
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
	
</script>