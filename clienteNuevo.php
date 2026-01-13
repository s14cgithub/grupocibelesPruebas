<?php 

session_start(); 
$_SESSION['titulo']="Nuevo Cliente";
$ruta="/";


require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


$soloLectura = "";

?>


<table align="center">

	<tr>
		<td>Tipo de Cliente: </td>
		<td>	
			<input type="radio" id="tipoClienteCliente" name="tipoCliente" value="tipoBusquedaPresupuesto">
				<label for="tipoClienteCliente" checked>Cliente</label>
			
			<span style="margin-left: 15px"></span>
			
			<input type="radio" id="tipoClienteSubCliente" name="tipoCliente" value="tipoBusquedaCliente">
				<label for="tipoClienteSubCliente">SubCliente</label>
		</td>
	</tr>
	
	
	<!--CLIENTE NORMAL-->
	
	<tr></tr>
	
	
	

	<tr><td colspan="6"><hr></td></tr>
	
	
	
	<tr>

		<td align="right">Nombre de Empresa:</td>
		<td><input class="" type="text" id="nuevo_nombreEmpresa" name="nuevo_nombreEmpresa"></input></td>

		<td align="right">NIF:</td>
		<td><input class="" type="text" id="nuevo_nif" name="nuevo_nif"></input></td>
	</tr>

	
	<tr>

		<td align="right">Codigo Empresa:</td>
		<td><input class="" type="text" id="nuevo_CodigoEmpresa_sub" name="nuevo_CodigoEmpresa_sub"></input></td>

		<td align="right">Nombre Empresa:</td>
		<td><input class="" type="text" id="nuevo_nombreEmpresa_sub" name="nuevo_nombreEmpresa_sub" readonly></input></td>
	</tr>


	<tr>

		<td align="right">Nombre SubCliente:</td>
		<td><input class="" type="text" id="nuevo_subcliente" name="nuevo_subcliente" ></input></td>

		<td align="right">SubNIF:</td>
		<td><input class="" type="text" id="nuevo_subnif" name="nuevo_subnif" ></input></td>
	</tr>




	<tr>
		<td align="right">Direccion:</td>
		<td colspan="5"><input style="width: 100%" class="" type="text" id="cliente_direccion" name="cliente_direccion" <?php echo $soloLectura; ?>></input></td>
	</tr>

	<tr>
		<td align="right">Localidad:</td>
		<td><input class="" type="text" id="cliente_localidad" name="cliente_localidad" <?php echo $soloLectura; ?>></input></td>

		<td align="right">Provincia:</td>
		<td><input class="" type="text" id="cliente_provincia" name="cliente_provincia" <?php echo $soloLectura; ?>></input></td>

		<td align="right">CP:</td>
		<td><input class="input5digitos" type="text" id="cliente_cp" name="cliente_cp" <?php echo $soloLectura; ?>></input></td>
	</tr>

	<tr>
		<td align="right">Nombre Franqueo:</td>
		<td><input class="" type="text" id="cliente_nomFranqueo" name="cliente_nomFranqueo"></input></td>
			
	</tr>


	<tr>
		<td align="right">Comercial:</td>
		<td><select class=""  id="comercial" name="comercial" <?php echo $soloLectura; ?>></select></td>
		<td align="right">Días a Pagar</td>
		<td colspan="5"><select class=""  id="diasApagar" name="diasApagar" <?php echo $soloLectura; ?>></select></td>
	</tr>
	<tr>
		<td align="right">Forma de Pago:</td>
		<td colspan="5"><select class=""  id="formaPago" name="formaPago" <?php echo $soloLectura; ?>></select></td>
	</tr>
	<tr>
		<td align="right">Email Factura:</td>
		<td colspan="5"><input class="" type="text"  id="email" name="email" <?php echo $soloLectura; ?>></input></td>
	</tr>



	<tr><button type="button" class="btn btn-info" onClick="modificarCliente()">CREAR</button></td></tr>


	






</table>




			
			

			
			
</div> <!-- class="tabla" -->

					








		<script type="text/javascript"> 
			cargarComerciales();
			cargarFormasDePago();
			cargarDiasApagar();
			cargarPeriodosFacturacion();
			cargarFacturasProvisionFondo();
			//observacionesClienteGestion();
			traspasoClientes(<?php echo json_encode($clientes); ?>)
			cargarClientesContactos();
			

</script>
	
				


<?php

echo ("</div>");
echo ("</html>");

?>