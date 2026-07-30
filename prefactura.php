

<?php 

session_start(); 

$_SESSION['titulo']="PREFACTURA";
$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	/*if ($_SESSION["permiso_pdaGestion"]==1)
	{
		$soloLectura="readonly";
	}*/
	
	$numPresupuesto = $_POST["preFacturaPresupuesto"];
	$inicialComercial = $_POST["preFacturaInicialComercial"];
	$opcionOrigen = $_POST["preFacturaCombinacion"];

	$busquedaPantallaAnterior = $_POST["busquedaPantallaAnterior"];
	//echo "A".$numPresupuesto. "A";
	//$array=$_POST["preFacturaCombinacion"];
	
	
		
	echo '<table align="center" border="0"  class="">';
	
	
	
	echo ('<tr><td colspan="7" id="numPresupuestoTd" align="center">
	<span id="inicialComercial" style="font-size: 30px;font-weight: bold;">');
	
	echo ($inicialComercial);
	
	
	echo ('</span> - 
	<span id="numPresupuesto" style="font-size: 30px;font-weight: bold;">');
	echo ($numPresupuesto);
	echo ('</span>
	<span id="letraPresupuesto" style="font-size: 30px;font-weight: bold;"></span>
	
	
	
	
	</td></tr>');
	
	//echo ('<tr><td colspan="7" id="numPresupuesto"></td></tr>');
	echo '<tr><td colspan="7"><hr></td></tr>';
	
	
	echo '<tbody id="datosPresupuestoGenerico">';
	
	//echo '<tr>';
	
	//////////////////////////////////////////
	echo '<tr>';
	echo '<td align="right">Clayma:</td>';
	echo '<td  align="left" colspan="6"><span id="camposClayma"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionarCambioClientePrefactura(\'clientes\')" style=""></input> <font color=red> &nbsp;&nbsp;&nbsp;Si se cambia de cliente, se cambiará tambien en el presupuesto y en las provisiones de fondos</font></span><span id="camposClaymaCombinado" style="visibility: hidden;display: none;"></span></td>';	
	
	
	
	echo '</tr>';
	
	
	
	echo '<tr><td colspan="1" align="right">Cliente:</td><td colspan="5"> <span id="camposCliente"><select name="clientes" id="clientes" onchange="cambiarClienteEnPrefactura()">';
	echo '</select></span><span id="camposClienteCombiando" style="visibility: hidden;display: none;"></span></td></tr>';
	
	
	
	////////////////////////////////////////////
	
	
	//echo '<tr><td>Cliente:</td><td colspan="6" id="cliente"></td></tr>';
	
	
	echo  '<tr style="visibility: hidden; display: none;">';
	echo '<td align="right">Fecha:</td><td colspan="1" style="text-align:right" ><input type="date" id="fechaFactura" value="'.date('Y-m-d').'" style="width:100%"></input></td>';	
	
	echo '</tr>';
	
	echo '<tr><td align="right">Pedido Cliente:</td><td colspan="4"><input type="text" id="pedidoCliente" style="width:100%"></input></td>
	
	</tr>';
	
	
	echo '<tr>
	<td align="right">Cantidad:</td><td><input type="number" id="cantidad"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="sinIvaCliente"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="conIRPFCliente"></input></td>
	
	</tr>';
	echo '<tr><td colspan="1" align="right">Forma de pago:</td><td colspan="5"> <select name="formaPago" id="formaPago">';
	echo '</select>&nbsp;<button type="button" class="btn btn-info" data-toggle="modal" data-target="#NuevaFormaPagoProcesoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;">NFP</button></td>';
	
	
	/*echo '<td align="right">Numero de Cuenta:</td>
		<td colspan="2"><input class="" type="text"  id="numCuenta" name="numCuenta"  style="width: 100%;" ></input></td></tr>';*/
	
	
	echo '<td align="right" style="visibility: hidden; display: none;">Numero de Cuenta:</td>
		<td colspan="2" style="visibility: hidden; display: none;"><textarea id="numCuenta"></textarea></td></tr>';
	
	
	echo '<tr><td align="right">Campaña:</td><td colspan="6"><input type="text" id="campana" style="width:100%"></input></td></tr>';
	
	
	
	
	echo '<tr><td align="right"  style="visibility: hidden; display: none;">Para Imprimir:</td><td colspan="3" style="visibility: hidden; display: none;">Detallada:&nbsp&nbsp<input type="checkbox" id="detallada" name="detallada" value="detallada"></td>';
	
	//echo '<td>Para Combinar:</td><td colspan="3"><input type="checkbox" id="paraCombinar" name="paraCombinar" onchange="cambioCombinacionPresupuesto()"></td></tr>';
	
	
	
	echo '<tr>
	<td align="right">Neto:</td><td><input type="number" id="Neto" readonly></input></td>
	<td align="right">Iva:</td><td><input type="number" id="iva" readonly></input></td>	
	<td align="right">IRPF:</td><td><input type="number" id="irpf" readonly></input></td>	
	
	</tr>';
	
	echo '<tr>
	<!--<td>Provision:</td><td><input type="number" id="provision" onKeyUp="calcularTotalTodoPreFactura()"></input></td>-->
	<!--<td>Provision:</td><td><select name="provision" id="provision"  style="width:100%" onchange="calcularTotalTodoPreFactura()"></td>-->
	
	<td align="right">Provision Total:</td><td><input type="number" id="provisionTotal" readonly></td>
	<td align="right">A Pagar:</td><td><input type="number" id="aPagar" readonly></input></td>	

	<td align="right">Total:</td><td><input type="number" id="total" readonly></input></td>	
	
	</tr>';
	
	
	
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	
	echo '<tr><td colspan="7" align="center">';
	

	
	
	if ($opcionOrigen==1)
	{
		echo '<button type="button" class="btn btn-info" id="botonnoSeFactura" onClick="noSeFactura()">NO SE FACTURA</button>';
		
		echo '<button type="button" class="btn btn-info" id="botonPrevisualizarPresupuesto" onClick="previsualizarFactura()">PREVISUALIZAR</button>';
		
		echo '<button type="button" class="btn btn-info" id="botonModificarPresupuesto" onClick="grabarFactura()">EMITIR FACTURA</button>';
		//echo '<button type="button" class="btn btn-warning" id="botonEmitirPreFactura" onClick="grabarFactura(1)">EMITIR PRE-FACTURA</button>';
		
		/*el siguiente boton hace las cosas mal, y lo comento por que al parecer nadie lo utiliza*/ 
		//echo '<button type="button" class="btn btn-info" onclick="calcularImporteFranqueoDesdePrefactura1()">VER IMPORTE FRANQUEO</button>';
		
		echo '<button type="button" class="btn btn-info" id="botonVolver" onClick="gestionarVolverPrefacturaBorrarTemporal(1)">VOLVER</button>';
		
		
	}
	else if ($opcionOrigen==2)
	{
		//echo '<button type="button" class="btn btn-info" onclick="guardarPrefacturaCabeceraTemporal()">GUARDAR</button>';
		
		//echo '<button type="button" class="btn btn-info" onclick="calcularImporteFranqueoDesdePrefactura1()">VER IMPORTE FRANQUEO</button>';
		
		//echo '<button type="button" class="btn btn-info" id="botonVolver" onClick="location.href = \'admEmisionFacturasPendientes.php?clayma=0\'">VOLVER</button>';
		
		echo '<button type="button" class="btn btn-info" id="botonVolver" onClick="gestionarVolverPrefacturaBorrarTemporal(2)">VOLVER</button>';
		
		
	}
	
	
	
	
	
	echo '</td></tr>';
		//echo '<button type="button" class="btn btn-info" id="botonModificarPresupuesto" onClick="combinarPresupuestos()">PARA COMBINAR</button>';
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	
	echo '</tr>';
	echo '</tbody>';
	
		
	echo '<tbody id="detallesPrefactura" name="detallesPrefactura" style=""></tbody>';
	
	
	echo '<tr><td colspan="7"><hr></td></tr>';

	
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
		<td align="right">Tipo IVA:</td>
		<td><select id="tipoIvaNuevoTemp"></select></td>	
	</tr>';
	
	echo '<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onClick="anadirDetallePrefactura()">AÑADIR DETALLE</button></tr>';
	
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
	<input type="hidden" id="numFacturaCompleto" name="numFacturaCompleto" value=""></input>
	<input type="hidden" id="anioSeleccionado0" name="anioSeleccionado0" value=""></input>
	<input type="hidden" id="imprimirClayma" name="clayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>


<form id="formImprimirFacturaClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirNumFacturaClayma" name="imprimirNumFacturaClayma" value=""></input>
	<input type="hidden" id="anioSeleccionado3" name="anioSeleccionado3" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>


<form id="formPrevisualizarFactura"  method="post"  target="_blank" action="previsualizarFactura.php">
	<input type="hidden" id="previsualizar_presupuesto" name="presupuesto" value=""></input>
	<input type="hidden" id="previsualizar_idCliente" name="idCliente" value=""></input>
	<input type="hidden" id="previsualizar_fecha" name="fecha" value=""></input>
	<input type="hidden" id="previsualizar_pedido" name="pedido" value=""></input>
	<input type="hidden" id="previsualizar_cantidad" name="cantidad" value=""></input>
	<input type="hidden" id="previsualizar_formaPago" name="formaPago" value=""></input>
	<input type="hidden" id="previsualizar_nuestraCuenta" name="nuestraCuenta" value=""></input>
	<input type="hidden" id="previsualizar_campana" name="campana" value=""></input>
	<input type="hidden" id="previsualizar_detallada" name="detallada" value=""></input>
	<input type="hidden" id="previsualizar_neto" name="neto" value=""></input>
	<input type="hidden" id="previsualizar_iva" name="iva" value=""></input>
	<input type="hidden" id="previsualizar_irpf" name="irpf" value=""></input>
	<input type="hidden" id="previsualizar_total" name="total" value=""></input>
	<input type="hidden" id="previsualizar_provision" name="provision" value=""></input>
	<input type="hidden" id="previsualizar_aPagar" name="aPagar" value=""></input>
	<input type="hidden" id="previsualizar_clayma" name="clayma" value=""></input>

	<input type="hidden" id="previsualizarAccion" name="previsualizarAccion" value="previsualizarFactura"></input>		
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


 <!-- VER IMORTE FRANQUEO -->
<div class="modal fade" id="verImporteFranqueoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">IMPORTE FRANQUEO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>                  
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Fecha Inicio:</label>
				<input type="date" class="form-control" id="fechaInicio_importeFranqueoModal">
          </div>
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Fecha Fin:</label>
				<input type="date" class="form-control" id="fechaFin_importeFranqueoModal">
          </div>
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Cliente (Cibeles):</label>				
				<select name="cliente_importeFranqueoModal" id="cliente_importeFranqueoModal" class="form-control"></select>
          </div>
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Extension:</label>				
				<input type="text" class="form-control" id="extension_importeFranqueoModal">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> 
		<button type="button" class="btn btn-primary" data-dismiss="" onClick="calcularImporteFranqueoDesdePrefactura()">Calcular Importe</button>    
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
<script  src="js/js_prefactura.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	
verDatosUnPresupuesto();
comprobarIvaCliente();
//cargarListadoClientesPrefactura();
verProvisionPrefactura();

cargarFormasDePago();	
mostrarTipoIva();
//copiarPresupuestoAFacturaTemporal();



	
cargarListadoClientesImporteFranqueoModal();
	
	//var array = [1,3,4,5,6];
//copiarPresupuestoAFacturaDetalleTemporal();

	<?php 
		if ($opcionOrigen==1)
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
					document.getElementById("camposClaymaCombinado").style.display="inline-block";
					document.getElementById("camposClayma").style.visibility="hidden";
					document.getElementById("camposClayma").style.display="none";


					var sel = document.getElementById("clientes");
					var text= sel.options[sel.selectedIndex].text;
					document.getElementById("camposClienteCombiando").innerHTML = text;
					document.getElementById("camposClienteCombiando").style.visibility="visible";
					document.getElementById("camposClienteCombiando").style.display="inline-block";
					document.getElementById("camposCliente").style.visibility="hidden";
					document.getElementById("camposCliente").style.display="none";';
			
			echo ("verDatosUnPresupuestoTemporal(document.getElementById('numPresupuesto').innerHTML)");
			
			
			
			
		}
		
	//echo "alert(".$opcionOrigen.");";
	?>
	
	
	
	
	guardarValorClienteInicial();
	
	
	$('#imprimirFacturaProcesoModal').on('hidden.bs.modal', function () {
    	location.href='admEmisionFacturasPendientes.php';
});

busquedaPantallaAnterior=<?php echo ("'".$busquedaPantallaAnterior."'"); ?>

document.getElementById("button-up").addEventListener("click", scrollUp);
</script>