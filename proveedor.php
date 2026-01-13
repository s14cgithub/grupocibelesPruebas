

<?php 

session_start(); 
$_SESSION['titulo']="Proveedor";
$ruta="/";

if ($_SESSION["usuario"]!="")
{
		
	require($ruta."Archivos Comunes/cabecera.php");
	$soloLectura = "";
	
	if ($_SESSION["permiso_clientes"]==1)
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
		
		<td align="right">Id:</td>
		<td><input type="text" id="proveedor_id" name="proveedor_id" readonly></input></td>
		

		<td align="right">Nombre del Proveedor:</td>
		<td  colspan="3" ><input  style="width: 100%" class="" type="text" id="proveedor_nombre" name="proveedor_nombre" ></input></td>
	</tr>

	<tr>
		<td align="right">NIF:</td>
		<td><input class="" type="text" id="proveedor_nif" name="proveedor_nif" ></input></td>
		<td align="right">Servicio:</td>
		<td colspan="3"><input style="width: 100%" class="" type="text" id="proveedor_servicio" name="proveedor_servicio" ></input></td>
	</tr>
<tr>
		
		<td align="right">Direccion:</td>
		<td colspan="5"><input style="width: 100%" class="" type="text" id="proveedor_direccion" name="proveedor_direccion"></input></td>
	</tr>

	<tr>
		<td align="right">Localidad:</td>
		<td><input class="" type="text" id="proveedor_localidad" name="proveedor_localidad" ></input></td>

		<td align="right">Provincia:</td>
		<td><input class="" type="text" id="proveedor_provincia" name="proveedor_provincia" ></input></td>

		<td align="right">CP:</td>
		<td><input class="input5digitos" type="text" id="proveedor_cp" name="proveedor_cp" ></input></td>
	</tr>

	<tr>

		<td align="right">Precio Comparado:</td>
		<td><input class="" type="text" id="proveedor_precioComparado" name="proveedor_precioComparado" ></input></td>		

		<td align="right">Fecha Alta:</td>
		<td><input  class="" type="date" id="proveedor_fechaAlta" name="proveedor_fechaAlta" readonly></input></td>
		
	</tr>


	<tr>
		<td align="right">Homologado:</td>
		<td><input class="" type="checkbox" id="proveedor_homologado" name="proveedor_homologado" onChange="gestionHomolodgado()" ></input></td>		
		<td align="right">Motivo Deshomologado:</td>
		<td colspan="3"><input  style="width: 100%" class="" type="text" id="proveedor_deshomologado" name="proveedor_deshomologado"></input></td>		
	</tr>

<?php
if($_SESSION["permiso_proveedores"] == 2)
	{
		echo '<tr><td align="center" colspan="6">
			<button type="button" class="btn btn-info" onClick="modificarProveedor()" id="botonModificarProveedor">MODIFICAR</button>
			<!--<button type="button" class="btn btn-info" onClick="nuevoCliente()" id="botonNuevoCliente">NUEVO</button>-->
			<button type="button" class="btn btn-info" onClick="crearCliente()" style="visibility: hidden;display: none;" id="botonCrearCliente">CREAR</button>
	</td></tr>';
	}

?>

	
	<tbody id="verProveedorContactos" ></tbody>
	
	
	

<!--<form id="formImprimirCliente" name="formImprimirCliente" method="post"  target="_blank" action="imprimirInformeCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirCliente"></input>
	<input type="hidden" id="imprimirClaymaCliente" name="imprimirClaymaCliente" value=""></input>
	<input type="hidden" id="imprimirCodigoCliente" name="imprimirCodigoCliente" value=""></input>		
</form>-->


</table>
			
			
</div> <!-- class="tabla" -->

					


<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_proveedores.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script type="text/javascript">
	
	<?php 
	if($_SESSION["permiso_proveedores"] == 1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>
	
	
			
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("id");
	
	
	if (parametro!=null)
	{		
		cargarUnProveedor();		
	}
	else
	{
		nuevoProveedor();
	}

	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>





<?php

echo ("</div>");
echo ("</html>");

?>