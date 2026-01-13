

<?php 

session_start(); 
$_SESSION['titulo']="Compras a Terceros";
$ruta="/";

if ($_SESSION["usuario"]!="")
{
		
	require($ruta."Archivos Comunes/cabecera.php");
	$soloLectura = "";
	
	if ($_SESSION["permiso_comprasAterceros"]==1)
	{
		$soloLectura="readonly";
	}
	
	
	//$clientes = cargarClientes($conexion);
	
	
}
else
{
	header('Location: index.php');	
}

?>


<table align="center">

	<!--<tr>
		<td colspan="6" align="center"><button type="button" class="btn btn-info" onClick="imprimirInformeCliente()">IMPRIMIR</button>
	
		</td>		
	</tr>-->
	
	
	
	<tr>
		
		<td align="right">Pedido:</td>
		<td><input type="text" class="soloLectura"  id="pedido_id" name="pedido_id" readonly></input></td>
		
		
		<td align="right">Fecha Alta:</td>
		<td><input type="date" class="soloLectura" id="pedido_fechaAlta" name="pedido_fechaAlta" readonly></label></td>

		
	</tr>

	<tr>		
		<td align="right">Nombre del Proveedor:</td>
		<td  colspan="3" ><select style="width: 100%" id="proveedores" name="proveedores"></select></td>
		<td align="right">Contacto Prov.:</td>
		<td><input class="" type="text" id="pedido_contactoP" name="pedido_contactoP" ></input></td>
	</tr>
	
	<tr>
		
		<td align="right">Comercial:</td>
		<td><select id="comercial" name="comercial"></select></td>		
		<td align="right">Presupuesto:</td>
		<td><input type="text" id="pedido_presupuesto" name="pedido_presupuesto" onKeyUp="buscarDatosPresupuesto();"></input></td>
		
	</tr>

	<tr>
		<td align="right">Nº Factura Venta:</td>
		<td><input class="soloLectura" type="text" id="pedido_numFacVenta" name="pedido_numFacVenta" readonly ></input></td>

		<td align="right">Fecha Factura Venta:</td>
		<td><input class="soloLectura" type="date" id="pedido_fechaFacVenta" name="pedido_fechaFacVenta" readonly></input></td>
		<td align="right">Fecha Presupuesto:</td>
		<td><input class="soloLectura" type="date" id="pedido_fechaPresu" name="pedido_fechaPresu" readonly></input></td>
	</tr>
	<tr>		
		<td align="right">Nombre del Cliente:</td>
		<td  colspan="3" ><input class="soloLectura" style="width: 100%" type="text" id="pedido_cliente" name="pedido_cliente" readonly></input></td>
		<td align="right">Contacto Clie.:</td>
		<td><input type="text" id="pedido_contactoC" name="pedido_contactoC" ></input></td>
	</tr>

	<tr>
		<td align="right">Fecha Entrega:</td>
		<td><input class="" type="date" id="pedido_fechaEntrega" name="pedido_fechaEntrega" ></input></td>

		<td align="right">Forma de Pago:</td>
		<td><select id="pedido_formaPago" name="pedido_formaPago" ></select></td>
	</tr>

	<tr>
		<td align="right">Nº Factura Compra:</td>
		<td><input class="" type="text" id="pedido_numFacCompra" name="pedido_numFacCompra" ></input></td>

		<td align="right">Fecha Factura Compra:</td>
		<td><input class="" type="date" id="pedido_fechaFacCompra" name="pedido_fechaFacCompra" ></input></td>

		<td align="right">Observaciones Internas:</td>
		<td><textarea class="" id="pedido_observacionInterna" name="pedido_observacionInterna" rows=1 style="width:100%" ></textarea></td>
	</tr>

	


	

<?php
	if ($_SESSION["permiso_comprasAterceros"]==2)
	{
		echo '<tr><td align="center" colspan="6">
			<button type="button" class="btn btn-info" onClick="modificarComprarTerceros()" id="botonModificarCompra" name="botonModificarCompra">MODIFICAR</button>
			<button type="button" class="btn btn-info" onClick="imprimirCompra(1)" id="botonPrevisualizarCompra">PREVISUALIZAR</button>
			<button type="button" class="btn btn-info" onClick="imprimirCompra(0)" id="botonImprimirCompra">IMPRIMIR</button>
			<!--<button type="button" class="btn btn-info" onClick="nuevoCliente()" id="botonNuevoCliente">NUEVO</button>-->
			<button type="button" class="btn btn-info" onClick="crearCliente()" style="visibility: hidden;display: none;" id="botonCrearCliente">CREAR</button>
	</td></tr>';
	}

?>

	
	<!--<tbody id="verProveedorContactos" ></tbody>-->

<tbody id="detallesPedido"></tbody>
<?php 
	if ($_SESSION["permiso_comprasAterceros"]==2)
	{

echo'
	<tr><td colspan="6"><hr></td></tr>
	<tr>
		<td align="right">Descripcion:</td>
		<td colspan="6">
			<input type="text" id="descripcionConceptoNuevoTemp" style="width:100%"  placeholder="Introducir Descripcion"></input>
		</td>
	</tr>
	<tr>
		<td align="right">Cantidad:</td>
		<td colspan="1">
			<input type="number" id="cantidadConceptoNuevoTemp" style="width:100%" placeholder="" onKeyUp="calcularPrecioYmargen(-1);"></input>
		</td>
		<td align="right">Precio Unitario:</td>
		<td>
			<input type="number" id="precioUnitarioConceptoNuevoTemp" onKeyUp="calcularPrecioYmargen(-1);"></input>
		</td>
		<td align="right">Precio Total:</td>
		<td>
			<input type="number"  class="soloLectura" id="precioTotalConceptoNuevoTemp"  readonly></input>
		</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td align="right">Precio Venta:</td>
		<td>
			<input type="number" id="precioVentaConceptoNuevoTemp" onKeyUp="calcularPrecioYmargen(-1);"></input>
		</td>
		<td align="right">Margen:</td>
		<td>
			<input type="text" id="margenConceptoNuevoTemp"   class="soloLectura"  readonly></input>
		</td>
	
	</tr>


	<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onClick="anadirDetalleCompraTercero()">AÑADIR CONCEPTO</button></tr>';
	}

?>
	
	
	

<!--<form id="formImprimirCliente" name="formImprimirCliente" method="post"  target="_blank" action="imprimirInformeCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirCliente"></input>
	<input type="hidden" id="imprimirClaymaCliente" name="imprimirClaymaCliente" value=""></input>
	<input type="hidden" id="imprimirCodigoCliente" name="imprimirCodigoCliente" value=""></input>		
</form>-->


</table>
			
			
</div> <!-- class="tabla" -->


<form id="formImprimirCompraTercero"  method="post"  target="_blank" action="imprimirCompraTercero.php">
	<input type="hidden" id="imprimirNumeroCompra" name="imprimirNumeroCompra" value=""></input>
	<input type="hidden" id="imprimirPrevisualizacion" name="imprimirPrevisualizacion" value="">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirCompra"></input>	
</form>


<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_comprasTerceros.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script type="text/javascript">
	
	<?php 
	if ($_SESSION["permiso_comprasAterceros"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>
	
	
			
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("id");
	
	
	if (parametro!=null)
	{
		cargarFormasDePagoCompraAterceros();
		cargarProveedores();
		//cargarComerciales();
		cargarPresupuestadores("comercial");
		cargarUnaCompra(parametro);	
		
	}
	

	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>





<?php

echo ("</div>");
echo ("</html>");

?>