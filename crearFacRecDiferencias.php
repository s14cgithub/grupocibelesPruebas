

<?php 

session_start(); 
$numFacturaOriginal = $_POST["facRecDiferencias_numeroFactura"];
$_SESSION['titulo']="FACTURA RECTIFICATIVA POR DIFERENCIAS - ".$numFacturaOriginal;

$ruta="/";

require($ruta."comprobarSesion.php");





require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]!="" && isset($_POST["facRecDiferencias_Accion"]) && $_POST["facRecDiferencias_Accion"]=="verRecDiferencias")
{
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$clayma = $_POST["facRecDiferencias_clayma"];
	$claymaChecked = "";

	$camposFacturaOriginal = ['idCodigoCliente','detallada','cuentaDelBanco','cliente','pedido','cantidad','formaPago','descripcion'];
	$filtrosFacturaOriginal = ['numeroFacturaCompleto' => $numFacturaOriginal];

	if ($clayma=="true")
	{
		$esClayma = "Sí";
		$claymaChecked = "checked";
		$resFacturaOriginal = mostrarFacturacionClayma($conn, $bbddSql, $camposFacturaOriginal, [], $filtrosFacturaOriginal, [], []);
	}
	else
	{
		$esClayma = "No";
		$resFacturaOriginal = mostrarFacturacion($conn, $bbddSql, $camposFacturaOriginal, [], $filtrosFacturaOriginal, [], []);
	}
	$datosFacturaOriginal = $resFacturaOriginal['datos'];

	$codigoCliente = $datosFacturaOriginal[0]["idCodigoCliente"];
	$detalladaImprimir = $datosFacturaOriginal[0]["detallada"];
	
	if ($detalladaImprimir==true)
	{
		$esDetalladaImprimir = "Sí";		
	}
	else
	{
		$esDetalladaImprimir = "No";		
	}
	
		
	echo '<table align="center" border="0"  class="">';

	

	echo '<tr><td colspan="7"><hr></td></tr>';
	
	
	echo '<tbody id="">';
	
	echo '<tr style="display: none;">';
	echo '<td id="numeroFacturaCompleto">'.$numFacturaOriginal.'</td>';
	echo '<td id="clayma">'.$clayma.'</td>';
	echo '<td id="idCliente">'.$datosFacturaOriginal[0]["idCodigoCliente"].'</td>';
	echo '<td id="detallada">'.$detalladaImprimir.'</td>';
	echo '<td id="numCuenta">'.$datosFacturaOriginal[0]["cuentaDelBanco"].'</td>';
	
	echo '<td><input type="checkbox" id="clienteOrigen" name="clienteOrigen" '.$claymaChecked.'></input></td>';
	echo '</tr>';
	
	//////////////////////////////////////////

	echo '<tr><td align="right">Motivo:</td><td colspan="6"><input type="text" id="motivo" style="width:100%" value="" maxlength="200"></input></td></tr>';
	echo '<tr>';
	echo '<td align="right">Clayma:</td>';	
	echo '<td align="left">'. $esClayma.'</td>';	
	echo '</tr>';
	
	
	
	echo '<tr><td colspan="1" align="right">Cliente:</td>';
	echo '<td id="nombreCliente" align="left" colspan="5">'.$datosFacturaOriginal[0]["cliente"].'</td></tr>';
	
	
	
	////////////////////////////////////////////
	
	
	//echo '<tr><td>Cliente:</td><td colspan="6" id="cliente"></td></tr>';
	
	
	echo  '<tr style="visibility: hidden; display: none;">';
	echo '<td align="right">Fecha:</td><td colspan="1" style="text-align:right" ><input type="date" id="fechaFactura" value="'.date('Y-m-d').'" style="width:100%"></input></td>';	
	
	echo '</tr>';
	
	echo '<tr><td align="right">Pedido Cliente:</td>';
	echo '<td id="pedidoCliente" align="left" colspan="5">'.$datosFacturaOriginal[0]["pedido"].'</td></tr>';
	
	
	
	
	echo '<tr>
	<td align="right">Cantidad:</td><td><input type="number" id="cantidad" value="'.$datosFacturaOriginal[0]["cantidad"].'"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="sinIvaCliente"></input></td>
	<td style="visibility: hidden;display: none;"><input type="text" id="conIRPFCliente"></input></td>
	
	</tr>';
	echo '<tr><td colspan="1" align="right">Forma de pago:</td>';
	echo '<td id="formaPago">'.$datosFacturaOriginal[0]["formaPago"].'</td></tr>';
	
	
	/*echo '<td align="right">Numero de Cuenta:</td>
		<td colspan="2"><input class="" type="text"  id="numCuenta" name="numCuenta"  style="width: 100%;" ></input></td></tr>';*/
	
	
	
	
	
	
	echo '<tr><td align="right">Campaña:</td>';
	echo '<td id="campana" align="left" colspan="5">'.$datosFacturaOriginal[0]["descripcion"].'</td></tr>';	
	
	

	echo '<tr><td align="right" style="visibility: hidden; display: none;">Imprimir Detallada:</td>';
	echo '<td align="left" style="visibility: hidden; display: none;">'.$esDetalladaImprimir.'</td></tr>';
	
	//echo '<td>Para Combinar:</td><td colspan="3"><input type="checkbox" id="paraCombinar" name="paraCombinar" onchange="cambioCombinacionPresupuesto()"></td></tr>';
	
	
	
	echo '<tr>
	<td align="right">Neto:</td><td><input type="number" id="Neto" readonly value=0></input></td>
	<td align="right">Iva:</td><td><input type="number" id="iva" readonly value=0></input></td>	
	<td align="right">IRPF:</td><td><input type="number" id="irpf" readonly value=0></input></td>	
	
	</tr>';
	
	echo '<tr>
	<!--<td>Provision:</td><td><input type="number" id="provision" onKeyUp="calcularTotalTodoPreFactura()"></input></td>-->
	<!--<td>Provision:</td><td><select name="provision" id="provision"  style="width:100%" onchange="calcularTotalTodoPreFactura()"></td>-->
	
	<td align="right">Provision Total:</td><td><input type="number" id="provisionTotal" readonly value=0></td>
	<td align="right">A Pagar:</td><td><input type="number" id="aPagar" readonly value=0></input></td>	

	<td align="right">Total:</td><td><input type="number" id="total" readonly value=0></input></td>	
	
	</tr>';
	
	
	
	echo '<tr><td colspan="7">&nbsp;</td></tr>';
	
	echo '<tr><td colspan="7" align="center">';
	

	
	
			
	echo '<button type="button" class="btn btn-info" id="botonPrevisualizarFactura" onClick="previsualizarFactura()">PREVISUALIZAR</button>';
	
	echo '<button type="button" class="btn btn-info" id="botonModificarPresupuesto" onClick="grabarFacturaRec()">EMITIR FACTURA RECTIFICATIVA</button>';	
	
	echo '<button type="button" class="btn btn-info" id="botonVolver" onClick="volverFacRecDiferencias()">VOLVER</button>';
	
	//echo '<button type="button" class="btn btn-info" onclick="calcularImporteFranqueoDesdePrefactura1()">VER IMPORTE FRANQUEO</button>';
	
	//echo '<button type="button" class="btn btn-info" id="botonVolver" onClick="gestionarVolverPrefacturaBorrarTemporal(1)">VOLVER</button>';
		
		
	
		
	
	
	
	
	
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

	sqlsrv_close($conn);
	
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


<form id="formImprimirFactura" method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="numFacturaCompleto" name="numFacturaCompleto" value=""></input>
	<input type="hidden" id="imprimirClayma" name="clayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formPrevisualizarFactura"  method="post"  target="_blank" action="previsualizarFactura.php">
	<input type="hidden" id="previsualizar_facturaRectificativa" name="facturaRectificativa" value="BORRADOR por Diferencia"></input>
	<input type="hidden" id="previsualizar_facRecMotivo" name="facRecMotivo" value=""></input>
	<input type="hidden" id="previsualizar_facRecfacOriginal" name="facRecfacOriginal" value=""></input>
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


 
	
<!--<form id="formIrAprefacturaListado" name="formIrAprefacturaListado" method="post"  target="" action="admEmisionFacturasPendientes.php">
	
	<input type="hidden" id="combinaciones" name="combinaciones" value=""></input>
	
	<input type="hidden" id="imprimirAccion2" name="imprimirAccion2" value="verPrefacturaListado"></input>	
	
</form>-->

<!-- formImprimirFacRec / formImprimirFacRecClayma: fusionados en formImprimirFactura -->


	


<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php



echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_crearFacRecDiferencias.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	
mostrarTipoIva();
cargarDetallesRegistrosFacRec();
	
//verProvisionPrefactura();	
//cargarFormasDePago();	

//verDatosUnPresupuesto();
//gestionDeCargarClientesListado('clientes');

	
//cargarClientes('A','cliente_importeFranqueoModal');
	
	//var array = [1,3,4,5,6];
//copiarPresupuestoAFacturaDetalleTemporal();

	<?php
	/* 
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
		
	
	?>
	
	
	comprobarIvaCliente(<?php echo $codigoCliente .",".$clayma;?>);
	
	//guardarValorClienteInicial();
	
	
	//$('#imprimirFacturaProcesoModal').on('hidden.bs.modal', function () {
    //	location.href='admEmisionFacturasPendientes.php';
//});


//busquedaPantallaAnterior=<?php //echo ("'".$busquedaPantallaAnterior."'"); ?>

document.getElementById("button-up").addEventListener("click", scrollUp);
</script>