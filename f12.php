

<?php 

session_start(); 

$_SESSION['titulo']="F12";
$ruta="/";

require($ruta."comprobarSesion.php");
/*if (!isset($_SESSION['tiempo'])) {
    $_SESSION['tiempo']=time();
}
else if (time() - $_SESSION['tiempo'] > 15) {
    session_destroy();
    // Aquí redireccionas a la url especifica
    header("Location: http://www.example.com/");
    die();  
}
$_SESSION['tiempo']=time(); //Si hay actividad seteamos el valor al tiempo actual*/




require($ruta."Archivos Comunes/cabecera.php");


?>

<table align="center">
	
	<?php 
		if ($_SESSION["permiso_franqueoF12"]==2)
		{
			echo '<tr>
				 	<td colspan="6" align="center">
						<button id="ejecutarF12" name="ejecutarF12" class="btn btn-danger btn-lg" onClick="ejecutarF12()">EJECUTAR F12</button>
					</td>
				</tr>
				<tr>
					<td>&nbsp;			
					</td>
				</tr>';
		}

	?>
	
	
	<tr>
		<td colspan="6" align="center"><h2>ENVIOS ESPECIALES</h2></td>
	</tr>


	<?php 
		if ($_SESSION["permiso_franqueoF12"]==2)
		{
			echo '<tr>
					<td align="right">Elegir un cliente: </td>
					<td colspan="3">
						<select name="listadoNombreFranqueo" id="listadoNombreFranqueo"  style="width:100%" style="width:100%"> </select>		
					</td>
		
					<td align="right">Fecha: </td>
					<td>
						<input type="date" name="fecha" id="fecha" value="';
			echo date("Y-m-d");
			echo '" style="width:100%"> </input>		
					</td>		
				</tr>
				<tr>		
					<td align="right">Gramos: </td>
					<td>
						<input type="number" name="gramos" id="gramos" style="width:100%"> </select>		
					</td>

					<td align="right">ot: </td>
					<td colspan="3">
						<input type="text" name="ot" id="ot" style="width: 100%;" > </input>		
					</td>
					
				</tr>
				
				<tr>
					<td align="right">Numero de Envios: </td>
					<td>
						<input type="number" name="totalEnvios" id="totalEnvios" value="0" style="width: 100%;" onChange="calcularImporteTotalEnvioEspecial();"> </input>		
					</td>
				
					<td align="right">Precio Unidad: </td>
					<td>
						<input type="number" name="precioUnidad" id="precioUnidad"  style="width:100%" onChange="calcularImporteTotalEnvioEspecial();" value="0"> </select>		
					</td>

					<td align="right">Precio Total: </td>
					<td>
						<input type="number" name="precioTotal" id="precioTotal"  style="width:100%" readonly> </select>		
					</td>
				</tr>';

		}
	?>
</table>

	
<br>
	<?php
		if ($_SESSION["permiso_franqueoF12"]==2)
		{
			echo '<br>
					<center>
						<button id="botonGrabar" type="button" class="btn btn-info" onClick="grabarFranqueoEnvioEspecial()">GRABAR</button>	
					</center>';
		}
	?>

	
<br>	
		
	

<table id="historico1" align="center" border="1"></table>
<br>





</div> <!-- class="tabla" -->


<!-- NUEVA FORMA DE PAGO -->
<div class="modal fade" id="modificarFranqueoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 85% !important; max-width: 85%;">
    <div class="modal-content">
      <div class="modal-header">
		  <div class="modal-title" id="ModalLabel"><h5>MODIFICAR FRANQUEO - <span id="modReferencia"></span></h5></div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>		
        </button>		  
      </div>
		
		
      <div class="modal-body">
		  
		  	<table style="color: black" align="center">
		  	<tr>
				<td colspan=""><label>idCliente:</label><input type="number" id="idClienteModal" value="">				
				</td>	
				<td colspan=""><label>Fecha:</label><input type="date" id="fechaModal"  value="" ></td>	
				<td colspan=""><label>OT:</label><input type="text" id="otModal"  value="" ></td>
				<td colspan=""><label>Total:</label><input type="number" id="totalModal"  value="" readonly >				
				</td>
				<!--<td><input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarFranqueoTipo('+datos[contador]["id"]+')" >
					
				<input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/eliminar.png" style="width:15px;" onclick="eliminarTipoFranqueoTipo('+datos[contador]["id"]+')" ></td>
				<td></td>
				<td></td>
				<td></td>-->
				
			</tr>
			
			<tr><td>&nbsp;</td></tr>
				
			<tbody id="datosTipoFranqueo">
				
			<!--<tr style="font-weight: bold">				
				<td align="center">OT</td>	
				<td align="center">Tipo</td>	
				<td align="center">Unidades</td>
				<td align="center">importe</td>
				<td align="center"></td>
			</tr>-->
			</tbody>	
		  </table>
		  
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="copiarPresupuesto()">Modificar</button>-->
      </div>
    </div>
  </div>
</div>

<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php




echo ("</div>");
echo ("</body>");
echo ("</html>");

?>
<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_12.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">

<?php 
	if ($_SESSION["permiso_franqueoF12"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>


	cargarListadoNombreFranqueo("listadoNombreFranqueo");

	///idInputListado = "listadoNombreFranqueo";
	//cargarListadoNombreFranqueo(" where activo = 1   and (idAutorizacionFranqueo =2 or idAutorizacionFranqueo=3) order by nombre_franqueo");	
	idInputListado="";
	rellenarHistoricoFranqueoEnviosEspeciales();
	document.getElementById("button-up").addEventListener("click", scrollUp);

</script>