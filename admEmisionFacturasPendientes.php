

<?php 



session_start(); 
$_SESSION['titulo']="PREFACTURA";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	
	?>
	<table border ="0" align="center">
		
			<tr>
				<!--<td align="right">Año:</td>
				<td><select class=""  id="anioSeleccionado" name="anioSeleccionado" onChange="buscarPrefactura()"></select></td>-->
				
				<td align="right">Clayma:</td>
				<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="borrarDetallesTemporalPrefactura()" style="" > </input></td>
			</tr>
	</table>

<table align="center">

	<tr>
		<td align="center" colspan="6">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="presupuesto">Presupuesto</option>				
				<option value="cliente">Cliente</option>
				<option value="campana">Campaña</option>				
				<!--<option value="fechaTerminado">Fecha Terminado</option>--><!--se comenta porque no funciona bien con el guardado de busqueda-->
			</select>

		Texto:
		<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		Orden: 
		<select class="" id="ordenBuscar">
				<option value="presupuesto">Presupuesto</option>				
				<option value="cliente">Cliente</option>
				<option value="campana">Campaña</option>				
				<option value="fechaTerminado">Fecha Terminado</option>
				
		</select>
	Desc: <input type="checkbox" id="buscarDesc"></input>
	
	
		<button type="button" class="btn btn-info" onClick="buscarPrefactura()">BUSCAR</button>		
		<!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#facturasMasivasModal" data-whatever="@mdo"">FACTURAS MASIVAS</button>-->
		<button id="tablaBotonCombinar" style="visibility:hidden;display:none" type="button" class="btn btn-info" onClick="combinarPresupuestos()">COMBINAR PRESUPUESTOS</button>
		
	
	</td>		
	</tr>

	<tr><td colspan="6"><hr></td></tr>
</table>



	<?php
	
	
	
	/*echo '<table align="center" border="0"  class="" id="tablaBotonCombinar" style="visibility:hidden;display:none";>';

	echo '<tr><td align="center"><button type="button" class="btn btn-info" onClick="combinarPresupuestos()">COMBINAR PRESUPUESTOS</button></td></tr>';
	echo '</table>';*/
	
	
	
	echo '<table align="center"   class="tabla">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	
	
	
	echo '<tbody id="listadoFacturasSinEmitir" name="listadoFacturasSinEmitir"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->


<!-- COMBINAR PRESUPUESTOS -->
<div class="modal fade" id="combinarPresupuestosModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">Combinacion Presupuestos: <span id="listadoPresupuestoModal"></span> </h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>
					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Sumatorio de un mismo concepto:</label>
						<input type="checkbox" class="" id="sumatorioModal"></input>
          			</div>
					
        		</form>
      		</div>
      		<div class="modal-footer">
        		<!--<button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="location.href='admEmisionFacturasPendientes.php'">Cerrar</button>-->
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="window.location.reload()">Cerrar</button>

				
				<button type="button" class="btn btn-primary"  data-dismiss="modal" onClick="imprimirFacturaCombinadaPrevisualizar()">Previsualizar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="verDatosPresupuestosCombinados()">Generar Factura</button>
      		</div>
    	</div>
	</div>
</div> 



<!--CREAR FACTURAS MASIVAMENTE-->
<!--
<div class="modal fade" id="facturasMasivasModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">CREAR FACTURAS MASIVAMENTE</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>										
					<div class="form-group">
					<label for="recipient-name" class="col-form-label" style="color:red !important;"><b>Se emitirá todas las Pre-Facturas hasta la fecha de "terminado" que se indidque (la fecha indicada está incluida en la emision de facturas)</b></label>
						<label for="recipient-name" class="col-form-label">Introducir al última fecha de "Terminado". </label>
						
						<input type="date" class="form-control" id="fechaTerminadoMensualModal_masivo"></input>
					</div>					
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>				
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="gestionEmitirFacturasMasivo()">Crear Facturas</button>
				
      		</div>
    	</div>
	</div>
</div> 
-->



<form id="formIrAprefactura" name="formIrAprefactura" method="post"  target="" action="prefactura.php">
	<input type="hidden" id="preFacturaInicialComercial" name="preFacturaInicialComercial" value=""></input>
	<input type="hidden" id="preFacturaPresupuesto" name="preFacturaPresupuesto" value=""></input>
	<input type="hidden" id="preFacturaCombinacion" name="preFacturaCombinacion" value=""></input>
	<input type="hidden" id="combinaciones" name="combinaciones" value=""></input>
	<input type="hidden" id="busquedaPantallaAnterior" name="busquedaPantallaAnterior" value = ""></input>

	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="verPrefactura"></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>


<form id="formImprimirFactura"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirNumFactura" name="imprimirNumFactura" value=""></input>
	<input type="hidden" id="anioSeleccionado0" name="anioSeleccionado0" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>


<form id="formImprimirFacturaClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirNumFacturaClayma" name="imprimirNumFacturaClayma" value=""></input>
	<input type="hidden" id="anioSeleccionado3" name="anioSeleccionado3" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>




<form id="formImprimirFacturaPrevisualizacion"  method="post"  target="_blank" action="previsualizarFacturaCombinada.php">
	<input type="hidden" id="imprimirNumPresupuestoPrevisualizacion" name="imprimirNumPresupuestoPrevisualizacion" value=""></input>	
	<input type="hidden" id="imprimirCombinadoSumatorioPrevisualizacion" name="imprimirCombinadoSumatorioPrevisualizacion" value=""></input>

	<input type="hidden" id="previsualizarAccion" name="previsualizarAccion" value="previsualizarFactura"></input>	
</form>


<form id="formImprimirFacturaClaymaPrevisualizacion"  method="post"  target="_blank" action="previsualizarFacturaCombinadaClayma.php">
	<input type="hidden" id="imprimirNumPresupuestoClaymaPrevisualizacion" name="imprimirNumPresupuestoClaymaPrevisualizacion" value=""></input>
	<input type="hidden" id="imprimirCombinadoSumatorioClaymaPrevisualizacion" name="imprimirCombinadoSumatorioClaymaPrevisualizacion" value=""></input>	
	<input type="hidden" id="previsualizarAccion" name="previsualizarAccion" value="previsualizarFactura"></input>	
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
<script  src="js/js_admEmisionFacturaPendiente.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">		
	
	arrayCombinaciones = [];
	
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("clayma");
	
	if (parametro!=null)
	{	
		if (parametro==1)
		{
			document.getElementById("clienteOrigen").checked=true;
		}

		document.getElementById("buscarCampo").value = params.get("buscarCampo");
		document.getElementById("buscarTexto").value = params.get("buscarTexto");
		document.getElementById("ordenBuscar").value = params.get("ordenBuscar");
		if (params.get("buscarDesc")=="true")
		{
			document.getElementById("buscarDesc").checked=true;
		}
		else
		{
			document.getElementById("buscarDesc").checked=false;
		}		
	}


	<?php 

		//echo ("\nAqui2: ".$_SESSION["prefactura_queBusca"]."\n");
	if (isset($_SESSION["prefactura_queBusca"]))
	{
		if ($_SESSION['prefactura_Clayma']=="true")
		{
			echo ('document.getElementById("clienteOrigen").checked=true;');
		}
		if ($_SESSION["prefactura_Desc"]=="true")
		{
			echo ('document.getElementById("buscarDesc").checked=true;');
		}	

		
		//
		//echo ('\n'.$_SESSION["prefactura_texto"].'\n');
		echo ('document.getElementById("buscarCampo").value =\''.$_SESSION["prefactura_queBusca"].'\';');
		echo ('document.getElementById("buscarTexto").value =\''.$_SESSION["prefactura_texto"].'\';');
		echo ('document.getElementById("ordenBuscar").value ="'.$_SESSION["prefactura_orden"].'";');

		
	}



	?>
	
	

	//document.getElementById("cerrarModalCombinacion").onClick = ()=>'location.href=\'admEmisionFacturasPendientes.php?clayma=' + parametro + '&buscarCampo='+document.getElementById("buscarCampo").value
	//+'&buscarTexto='+document.getElementById("buscarTexto").value+'&ordenBuscar='+document.getElementById("ordenBuscar").value+'&buscarDesc='+document.getElementById("buscarDesc").checked + '\'';
	
	//cargarAnios("anioSeleccionado");
	buscarPrefactura();
	//cargarListadoFacturasSinEmitir();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>