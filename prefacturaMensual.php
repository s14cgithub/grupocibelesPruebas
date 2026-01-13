

<?php 

session_start(); 

$_SESSION['titulo']="PREFACTURA - MENSUAL";
$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{	
	
	$numFactura = $_POST["preFacturaNumFactura"];	
	$anioSeleccionado = $_POST["preFacturaAnioSeleccionado"];
	
	echo '<table align="center" border="0"  class="">';
	
	echo ('<tr><td colspan="7"  align="center"><span id="" style="font-size: 30px;font-weight: bold;">');
	echo ('Factura Original: </span><span id="numPresupuesto" style="font-size: 30px;font-weight: bold;">'.$numFactura);
	
	echo ('</span>');
	echo ('<span style="visibility: hidden; display:none" id="numeroAnioSeleccionado">'.$anioSeleccionado.'</span');
	
	echo ('</td></tr>');
	
	
	
	
	
	
	
	
	
	echo '<tr><td colspan="7"><hr></td></tr>';
	
	
	echo '<tbody id="datosPresupuestoGenerico">';
	
	echo '<tr>';
	
	
	echo '<tr><td>Cliente:</td><td colspan="6" id="cliente"></td></tr>';
	
	
	
	//echo '<td>Fecha:</td><td colspan="1" style="text-align:right" ><input type="date" id="fechaFactura" value="'.date('Y-m-d').'" style="width:100%"></input></td>';
	
	
	
	echo '</tr>';
	
	echo '<tr><td>Pedido Cliente:</td><td colspan="4"><input type="text" id="pedidoCliente" style="width:100%"></input></td>
	
	</tr>';
	
	
	
	echo '<tr>
	<td align="">Cantidad:</td><td><input type="number" id="cantidad"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="sinIvaCliente"></input></td>	
	<td style="visibility: hidden;display: none;"><input type="text" id="conIRPFCliente" value="sinIRPF"></input></td>
	
	</tr>';
	echo '<tr><td colspan="1">Forma de pago:</td><td colspan="5"> <select name="formaPago" id="formaPago">';
	echo '</select>&nbsp;<button type="button" class="btn btn-info" data-toggle="modal" data-target="#NuevaFormaPagoProcesoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;">NFP</button></td>';
	
	
	/*echo '<td align="right">Numero de Cuenta:</td>
		<td colspan="2"><input class="" type="text"  id="numCuenta" name="numCuenta"  style="width: 100%;" ></input></td></tr>';*/
	
	
	echo '<td align="right" style="visibility: hidden; display: none;">Numero de Cuenta:</td>
		<td colspan="2" style="visibility: hidden; display: none;"><textarea id="numCuenta"></textarea></td></tr>';
	
	
	echo '<tr><td>Campaña:</td><td colspan="6"><input type="text" id="campana" style="width:100%"></input></td></tr>';
	
	
	
	
	echo '<tr><td>Para Imprimir:</td><td colspan="3">Detallada:&nbsp&nbsp<input type="checkbox" id="detallada" name="detallada" value="detallada"></td>';
	
	//echo '<td>Para Combinar:</td><td colspan="3"><input type="checkbox" id="paraCombinar" name="paraCombinar" onchange="cambioCombinacionPresupuesto()"></td></tr>';
	
	
	
	echo '<tr>
	<td align="right">Neto:</td><td><input type="number" id="Neto" readonly></input></td>
	<td align="right">Iva:</td><td><input type="number" id="iva" readonly></input></td>	
	<td align="right">IRPF:</td><td><input type="number" id="irpf" readonly></input></td>	
	
	</tr>';
	
	echo '<tr>
	<!--<td>Provision:</td><td><input type="number" id="provision" onKeyUp="calcularTotalTodoPreFactura()"></input></td>-->
	<!--<td><td>Provision:</td><td><select name="provision" id="provision"  style="width:100%" onchange="calcularTotalTodoPreFactura()"></td>-->
	
	<td align="right">Provision Total:</td><td><input type="number" id="provisionTotal" readonly></td>
	<td align="right">A Pagar:</td><td><input type="number" id="aPagar" readonly></input></td>
	<td align="right">Total:</td><td><input type="number" id="total" readonly></input></td>		
	<td></td><td></td>	
	</tr>';
	
	
	
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	
	echo '<tr><td colspan="7" align="center">';
	

	
	//echo '<button type="button" class="btn btn-info" id="botonPrevisualizarPresupuesto" onClick="previsualizarFactura2()">PREVISUALIZAR</button>';
	echo '<button type="button" class="btn btn-info" id="botonModificarPresupuesto" onClick="grabarFactura2()">EMITIR FACTURA</button>';
	
	
	
	echo '</td></tr>';
	
	
	
	
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	
	echo '</tr>';
	echo '</tbody>';
	
		
	echo '<tbody id="detallesPrefactura" name="detallesPrefactura" style=""></tbody>';
	
	
	echo '<tr><td colspan="7"><hr></td></tr>';

	
	echo '<tbody id="anadirDetallesPreFactura">';	
	echo '<tr>';
	
	
	echo '<td>Concepto:</td><td colspan="6"> <input type="text" id="conceptoNuevoTemp" value="" style="width:100%"></input></td>';
	
	
	echo '</tr>';
	
	
	
	echo '<tr><td>Descripcion:</td><td colspan="6"><input type="text" id="descripcionDetalleNuevoTemp" style="width:100%"></input></td></tr>';
	echo '<tr><td>Nota Cibeles:</td><td colspan="6"><input type="text" id="notaDetalleNuevoTemp" style="width:100%"></input></td></tr>';
	echo '<tr>
	<td>Unidades:</td><td><input type="number" id="unidadesDetalleNuevoTemp"></input></td>
	<td>Precio:</td><td><input type="number" id="precioDetalleNuevoTemp"></input></td>	
	<td colspan"2"></td>
	</tr>';
	
	echo '<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onClick="anadirDetallePrefactura2()">AÑADIR DETALLE</button></tr>';
	
	echo '</tbody>';
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>



</div> <!-- class="tabla" -->

					

<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Open modal for @fat</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Open modal for @getbootstrap</button>-->



<!-- NUEVA FORMA DE PAGO -->
<div class="modal fade" id="NuevaFormaPagoProcesoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Nuevo Forma Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Forma de Pago:</label>
            <input type="text" class="form-control" id="nuevaFormaDePago">
          </div>		         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="crearNuevoFormaDePago()">Crear Forma de Pago</button>
      </div>
    </div>
  </div>
</div> 

<form id="formImprimirFactura"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirNumFactura" name="imprimirNumFactura" value=""></input>
	<input type="hidden" id="anioSeleccionado0" name="anioSeleccionado0" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<!-- FORM IMPRIMIR FACTURA -->
<div class="modal fade" id="imprimirFacturaProcesoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Factura: <span id=modalNumFactura></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Cuentas Bancarias:</label>
            <textarea class="form-control" name="cuentaBancaria" id="cuentaBancaria" minlength="0" maxlength="300" form="formImprimirFactura">IBAN ES21 2100 1945 2402 0000 7147
IBAN ES48 0049 1839 4621 1043 1601

</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="location.href='admEmisionFacturasPendientes.php'">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="irAImprimirFactura()">ImprimirFactura</button>
      </div>
    </div>
  </div>
</div> 


 
	
<!--<form id="formIrAprefacturaListado" name="formIrAprefacturaListado" method="post"  target="" action="admEmisionFacturasPendientes.php">
	
	<input type="hidden" id="combinaciones" name="combinaciones" value=""></input>
	
	<input type="hidden" id="imprimirAccion2" name="imprimirAccion2" value="verPrefacturaListado"></input>	
	
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
<script  src="js/js_prefacturaMensual.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
//verProvisionPrefactura();	
cargarFormasDePago();	
	
verDatosUnaFactura(document.getElementById("numPresupuesto").innerHTML, document.getElementById("numeroAnioSeleccionado").innerHTML);

copiarFacturaAFacturaDetalleTemporal(document.getElementById("numPresupuesto").innerHTML);

verDatosCliente();	
	
$('#imprimirFacturaProcesoModal').on('hidden.bs.modal', function () {
    	location.href='admEmisionFacturasPendientes.php';
});
	
document.getElementById("button-up").addEventListener("click", scrollUp);
</script>