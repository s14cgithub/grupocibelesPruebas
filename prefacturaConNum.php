

<?php 

session_start(); 

$_SESSION['titulo']="PREFACTURA CON NUMERO ASIGNADO";
$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"" && $_POST["imprimirAccion"]=="verPrefactura")
{
	/*if ($_SESSION["permiso_pdaGestion"]==1)
	{
		$soloLectura="readonly";
	}*/
	
	$numFactura = $_POST["preFacturaNumFactura"];
	$anioSeleccionado = $_POST["preFacturaNumAnio"];
	$clayma = $_POST["preFacturaNumClayma"];
	$checked = "";
	
	if ($clayma=="true")
	{
		$checked = "checked";
	}
	
	echo '<table align="center" border="0"  class="">';
	
	
	
	echo ('<tr><td colspan="6" id="numPresupuestoTd" align="center">');
	
	
	echo ('<span id="numPresupuesto" style="font-size: 30px;font-weight: bold;">');
	echo ('PREFACTURA Nº: <span id="numFactura">'.$numFactura.'</span>/<span id="anio">'.substr($anioSeleccionado,2,2)).'</span>';
	echo ('</span>
	
	
	</td></tr>');
	
	//echo ('<tr><td colspan="7" id="numPresupuesto"></td></tr>');
	echo '<tr><td colspan="6"><hr></td></tr>';
	
	
	echo '<tbody id="datosPresupuestoGenerico">';
	
	echo '<tr>';
	
	//////////////////////////////////////////
	echo '<tr>';
	echo '<td align="right">Clayma:</td>';
	echo '<td  align="left" colspan="5"><span id="camposClayma"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" style="" onclick="return false;" '.$checked.'></input> </span></td>';
	echo '</tr>';
	
	//echo '<tr><td colspan="1" align="right">Cliente:</td><td colspan="5"> <span id="camposCliente"><input type="text" name="clientes" id="clientes" style="width:100%" disabled>';
	//echo '</input></span></td></tr>';
	
	echo '<tr><td colspan="1" align="right">Cliente:</td><td colspan="5"> <span id="clientes" style="width:100%; font-weight: bold;"></span></td></tr>';
	
	
	
	echo '<tr><td align="right">Pedido Cliente:</td><td colspan="3"><input type="text" id="pedidoCliente" style="width:100%"></input></td>';
	//echo '<td align="right">Fecha:</td><td colspan="1" style="text-align:right" ><input type="text" id="fechaFactura" value="" style="width:100%" disabled></input></td></tr>';
	echo '<td align="right">Fecha: </td><td style="text-align: center;"><span id="fechaFactura" value="" style="width:100%; font-weight: bold;"></span></td></tr>';
	
	echo '<tr>
	<td align="right">Cantidad:</td><td><input type="number" id="cantidad"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="sinIvaCliente"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="conIRPFCliente"></input></td>
	
	</tr>';
	
	echo '<tr><td colspan="1" align="right">Forma de pago:</td><td colspan="5"> <select name="formaPago" id="formaPago">';
	echo '</select>&nbsp;<button type="button" class="btn btn-info" data-toggle="modal" data-target="#NuevaFormaPagoProcesoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;">NFP</button></td>';
	
	echo '<tr><td align="right">Campaña:</td><td colspan="5"><input type="text" id="campana" style="width:100%"></input></td></tr>';	
	
	echo '<tr><td align="right">Para Imprimir:</td><td colspan="3">Detallada:&nbsp&nbsp<input type="checkbox" id="detallada" name="detallada" value="detallada"></td></tr>';
	
	echo '<tr>
	<!--<td align="right">Neto:</td><td><input type="number" id="Neto" readonly></input></td>
	<td align="right">Iva:</td><td><input type="number" id="iva" readonly></input></td>	
	<td align="right">Total:</td><td><input type="number" id="total" readonly></input></td>	-->


	<td align="right">Neto:</td><td><input type="number" id="Neto" readonly></input></td>
	<td align="right">Iva:</td><td><input type="number" id="iva" readonly></input></td>	
	<td align="right">IRPF:</td><td><input type="number" id="irpf" readonly></input></td>	


	</tr>';
	
	echo '<tr>	
	
	<!--<td align="right">Provision Total:</td><td><input type="number" id="provisionTotal" readonly></td>
	<td align="right">A Pagar:</td><td><input type="number" id="aPagar" readonly></input></td>-->	


	<td align="right">Provision Total:</td><td><input type="number" id="provisionTotal" readonly></td>
	<td align="right">A Pagar:</td><td><input type="number" id="aPagar" readonly></input></td>
	<td align="right">Total:</td><td><input type="number" id="total" readonly></input></td>	


	<td></td><td></td>	
	</tr>';
	
	
	
	echo '<tr><td colspan="6">&nbsp;</td></tr>';
	
	echo '<tr><td colspan="6" align="center">';
	
	echo '<button type="button" class="btn btn-info" id="modificarDatosGenericos" onClick="modificarDatosGenericos()">MODIFICAR PREFACTURA</button>';
	
	echo '<button type="button" class="btn btn-info" id="botonPrevisualizarPrefactura" onClick="getionVerPreFactura()">VISUALIZAR</button>';
		
	echo '<button type="button" class="btn btn-info" id="botonConvertirAFactura" onClick="convertirAFactura()">EMITIR FACTURA</button>';
	
	echo '</td></tr>';
		//echo '<button type="button" class="btn btn-info" id="botonModificarPresupuesto" onClick="combinarPresupuestos()">PARA COMBINAR</button>';
	echo '<tr><td colspan="6">&nbsp;</td></tr>';
	
	echo '</tr>';
	echo '</tbody>';
	
	
	/*echo '<td align="right" style="visibility: hidden; display: none;">Numero de Cuenta:</td>
			<td colspan="2" style="visibility: hidden; display: none;"><textarea id="numCuenta"></textarea></td></tr>';*/
	
	
	echo '<tbody id="detallesPrefactura" name="detallesPrefactura" style=""></tbody>';
	
	echo '<tr><td colspan="6"><hr></td></tr>';
	
	echo '<tbody id="anadirDetallesPreFactura">';	
	echo '<tr>';
	echo '<td>Concepto:</td><td colspan="6"> <input type="text" id="conceptoNuevoTemp" value="" style="width:100%" placeholder="Introducir Concepto"></input></td>';
	echo '</tr>';
	
	
	
	echo '<tr><td>Descripcion:</td><td colspan="6"><input type="text" id="descripcionDetalleNuevoTemp" style="width:100%"  placeholder="Introducir Descripcion"></input></td></tr>';
	echo '<tr><td>Nota Cibeles:</td><td colspan="6"><input type="text" id="notaDetalleNuevoTemp" style="width:100%" placeholder="Introducir Notas de Cibeles"></input></td></tr>';
	echo '<tr>
	<td>Unidades:</td><td><input type="number" id="unidadesDetalleNuevoTemp"></input></td>
	<td>Precio:</td><td><input type="number" id="precioDetalleNuevoTemp"></input></td>	
	<td></td>
	<td align="right">Exento de IVA:</td>
		<td><input type="checkbox" id="exentoIVA"></input></td>	
	</tr>';
	
	echo '<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onClick="anadirDetallePrefactura()">AÑADIR DETALLE</button></tr>';
	
	//echo '</tbody>';
	
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

<!-- NO SE FACTURA-->
<div class="modal fade" id="noSeFacturaModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">NO FACTURABLE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>                  
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Observacion</label>						
				<textarea maxlength="250" rows="5" class="form-control" id="observacionNoFacturableModal"></textarea>
			</div>
		  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="noSeFactura2()">No Facturar</button>
      </div>
    </div>
  </div>
</div> 

<form id="formImprimirFactura" method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirNumFactura" name="imprimirNumFactura" value=""></input>
	<input type="hidden" id="anioSeleccionado0" name="anioSeleccionado0" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>


<form id="formImprimirFacturaClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirNumFacturaClayma" name="imprimirNumFacturaClayma" value=""></input>
	<input type="hidden" id="anioSeleccionado3" name="anioSeleccionado3" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>



	
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
<script  src="js/js_prefacturaConNum.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	
	
	cargarFormasDePago();	
	
	verDatosFactura();	



	
	
	
//verDatosUnPresupuesto();
//gestionDeCargarClientesListado('clientes');

	
//cargarClientes('A','cliente_importeFranqueoModal');
	
	//var array = [1,3,4,5,6];
//copiarPresupuestoAFacturaDetalleTemporal();

	/*<?php 
		/*if ($opcionOrigen==1)
		{			
			echo "copiarPresupuestoAFacturaDetalleTemporal(1);";
		}
		else if ($opcionOrigen==2)
		{			
			echo "copiarPresupuestoAFacturaDetalleTemporal(2);";
			
			
			echo 'if (document.getElementById("clienteOrigen").checked)
					{
						document.getElementById("camposClaymaCombinado").innerHTML = "Sí";
					}
					else
					{
						document.getElementById("camposClaymaCombinado").innerHTML = "No";
					}
					document.getElementById("camposClaymaCombinado").style.visibility="visible";
					document.getElementById("camposClaymaCombinado").style.display="table-row";
					document.getElementById("camposClayma").style.visibility="hidden";
					document.getElementById("camposClayma").style.display="none";


					var sel = document.getElementById("clientes");
					var text= sel.options[sel.selectedIndex].text;
					document.getElementById("camposClienteCombiando").innerHTML = text;
					document.getElementById("camposClienteCombiando").style.visibility="visible";
					document.getElementById("camposClienteCombiando").style.display="table-row";
					document.getElementById("camposCliente").style.visibility="hidden";
					document.getElementById("camposCliente").style.display="none";';
			
			echo ("verDatosUnPresupuestoTemporal(document.getElementById('numPresupuesto').innerHTML)");
			
			
			
			
		}*/

	//echo "alert(".$opcionOrigen.");";
	?>*/
	
	
	//comprobarIvaCliente(document.getElementById("clientes").value);
	
	//guardarValorClienteInicial();
	
	
	/*$('#imprimirFacturaProcesoModal').on('hidden.bs.modal', function () {
    	location.href='admEmisionFacturasPendientes.php';
});*/
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>