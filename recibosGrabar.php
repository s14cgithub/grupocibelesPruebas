

<?php 

session_start(); 
$_SESSION['titulo']="GRABAR RECOGIDAS Y ENTREGAS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");


?>
	

<table align="center">

	<tr>
		<td align="right" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="numeroRecibo">Albarán</option>
				<option value="descripcion">Descripcion</option>
				<option value="idCliente">IdCliente</option>
				<option value="importe">Importe</option>	
				<option value="nombre_franqueo">NombreFranqueo</option>
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	
	
		<td align="right" colspan="">
			Orden:
			<select class="" id="ordenBuscar">
				<option value="numeroRecibo">Albarán</option>
				<option value="descripcion">Descripcion</option>
				<option value="idCliente">IdCliente</option>
				<option value="id" selected>IdRegistro</option>
				<option value="importe">Importe</option>	
				<option value="nombre_franqueo">NombreFranqueo</option>				
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoNumerosFacturasModal" data-whatever="@mdo">Imprimir</button>-->


		</td>	
	</tr>
	<tr>
		<td align="right">
			Fecha Inicio: <input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
			Fecha Fin: <input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		</td>
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>




	<table align="center"  class="">		
		

	



<?php 
	//if ($_SESSION["soloGrabarRecogidasEntregas"]==2)
	{	
		echo '<tr align="center">
			<td><label></label></td>
			<td><label>Fecha</label></td>
			<td><label>Empleado</label></td>
			<td><label>Tipo</label></td>
			<td><label>Franqueo</label></td>
			<td><label>Cantidad</label></td>
			<td><label>Importe</label></td>
			<td><label>Descripcion</label></td>
			<td colspan="2"></td>
		</tr>
		<tr>
				<td><input type="hidden" id="numAlbaran" type="text"></input></td>
				<td><input type="datetime-local" id="fechaAlbaran"></input> </td>
				<td><select id="listadoEmpleado"></select></td>
				<td><select id="listadoTipoAlbaran"></select></td>
				<td><select id="listadoNombreFranqueo"></select></td>
				<td><input type="number" id="cantidadAlbaran" style="width: 100%;"></input></td>
				<td><input type="number" id="importeAlbaran" style="width: 100%;"></input></td>
				<td><input type="text" id="descripcionAlbaran" style="width: 100%;"></input></td>
				<td colspan="2" align="center"><input type="image" src="imagenes/crear.png" style="width:15px;"  onclick="guardarAlbaran()"></td>
			</tr>';
	}	

?>



</table>
<table align="center" class="tabla">
<tbody id="historicoAlbaranes" bgcolor=""> </tbody>

</table>


</div> <!-- class="tabla" -->




<form id="formImprimirAlbaran" name="formImprimirAlbaran" method="post" target="_blank" action="imprimirAlbaran.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAlbaran"></input>
	<input type="hidden" id="imprimirAlbaran_numero" name="imprimirAlbaran_numero" value=""></input>
	<input type="hidden" id="imprimirAlbaran_empleado" name="imprimirAlbaran_empleado" value=""></input>
	<input type="hidden" id="imprimirAlbaran_cliente" name="imprimirAlbaran_cliente" value=""></input>	
	<input type="hidden" id="imprimirAlbaran_tipo" name="imprimirAlbaran_tipo" value=""></input>	
	<input type="hidden" id="imprimirAlbaran_cantidad" name="imprimirAlbaran_cantidad" value=""></input>	
	<input type="hidden" id="imprimirAlbaran_importe" name="imprimirAlbaran_importe" value=""></input>	
	<input type="hidden" id="imprimirAlbaran_descripcion" name="imprimirAlbaran_descripcion" value=""></input>	
	<input type="hidden" id="imprimirAlbaran_fecha" name="imprimirAlbaran_fecha" value=""></input>	

	<!--<input type="submit" name="submit" value="submit">-->
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
<script  src="js/js_recibosGrabar.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">

<?php
	if ($_SESSION["soloGrabarRecogidasEntregas"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>



	idInputListado = "listadoEmpleado";
	cargarListadoEmpleado();
	idInputListado = "listadoTipoAlbaran";
	cargarListadoTipoAlabaran();
	idInputListado = "listadoNombreFranqueo";
	cargarListadoNombreFranqueo();
	idInputListado = "";
	//cargarAlbaranes();
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>