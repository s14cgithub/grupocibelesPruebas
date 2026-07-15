

<?php 

session_start(); 
$_SESSION['titulo']="Clientes";
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

	<tr>
		<td colspan="6" align="center"><button type="button" class="btn btn-info" onClick="imprimirInformeCliente()">IMPRIMIR</button>
	
		</td>		
	</tr>
	<tr><td colspan="6" align="center"><h5><label id="tituloCliente">CIBELES</label></h5></td></tr>

	<!--<tr><td colspan="6"><hr></td></tr>-->
	

	<tbody id="grupoTipoCliente" bgcolor=""  style="visibility: hidden;display: none;" > 
		<tr>			
			<td colspan="6" align="center">	
				<input type="radio" id="tipoClienteCliente" name="tipoCliente" value="tipoClienteCliente" onChange="crearClienteGestorTipo()" checked>
					<label for="tipoClienteCliente">Cliente</label>

				<span style="margin-left: 15px"></span>

				<input type="radio" id="tipoClienteSubCliente" name="tipoCliente" value="tipoClienteSubCliente" onChange="crearClienteGestorTipo()">
					<label for="tipoClienteSubCliente">SubCliente</label>
			</td>
		</tr>
		<tr><td colspan="6"><hr></td></tr>
	</tbody>
	
	
	<tbody id="grupoCamposSubClientes" bgcolor=""> 
		<tr>

			<td align="right">Codigo:</td>
			<td><input class="input5digitos" type="text" id="cliente_codigo" name="cliente_codigo" readonly></td>

			<td align="right">SubCliente:</td>
			<td><input class="" type="text" id="cliente_SubCliente" name="cliente_SubCliente" readonly></input></td>

			<td align="right">NIF subcliente:</td>
			<td><input class="" type="text" id="cliente_NifSubCliente" name="cliente_NifSubCliente" readonly></input></td>
		</tr>
	</tbody>
	<tr>
		
		<td align="right">Codigo Saldo:</td>
		<td><input class="input5digitos" type="text" id="cliente_codigoSaldo" name="cliente_codigoSaldo" onChange="verDatosClientePorCodigo()" readonly></input></td>
		

		<td align="right">Nombre de Empresa:</td>
		<td><input class="" type="text" id="cliente_NombreEmpresa" name="cliente_NombreEmpresa" readonly></input></td>

		<td align="right">NIF:</td>
		<td><input class="" type="text" id="cliente_Nif" name="cliente_Nif" readonly></input></td>
	</tr>

	<tr>
		<td align="right">Direccion:</td>
		<td colspan="3"><input style="width: 100%" class="" type="text" id="cliente_direccion" name="cliente_direccion" <?php echo $soloLectura; ?>></input></td>

		<td align="right">codigo SIDI:</td>
		<td><input class="" type="text" id="cliente_codigoSIDI" name="cliente_codigoSIDI"></input></td>

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
		<td align="right">Pais:</td>		
		<td colspan="3"><select class=""  id="cliente_pais" name="cliente_pais" onchange="cargarPaises2()" <?php echo $soloLectura; ?>></select></td>

		
		<td align="right">Codigo Pais:</td>
		<td><input class="" type="text" id="cliente_codigoPais" name="cliente_codigoPais" disabled maxlength="2"  oninput="this.value = this.value.toUpperCase().slice(0,2)" title="Solo letras A-Z (1-2)"></input></td>
		<td align="right"></td>
		
		<td></td>
	
	</tr>

	<tr>
		<td align="right">Nombre Franqueo:</td>
		<td><input class="" type="text" id="cliente_nomFranqueo" name="cliente_nomFranqueo" readonly></input></td>

		<td align="right">Fecha Alta:</td>
		<td><input class="" type="date" id="cliente_fechaAlta" name="cliente_fechaAlta" readonly></input></td>		

		<td align="right">Correo Diario:</td>
		<td><input  class="" type="checkbox" id="cliente_correoDiario" name="cliente_correoDiario" onChange="gestionMostrarDatosCD()"></input></td>
		
			
		
	</tr>


	<tr>
		<td align="right">Comercial:</td>
		<td><select class=""  id="comercial" name="comercial" <?php echo $soloLectura; ?>></select></td>
		<td align="right">Saldo:</td>
		<td><input class="" type="number" id="cliente_saldo" name="cliente_saldo" readonly></input></td>
		
		<td align="right">Activo:</td>
		<td><input class="" type="checkbox" id="cliente_activo" name="cliente_activo"></input></td>
		
	</tr>
	
	<tr>
		<td align="right">Email Factura:</td>
		
		
		<td colspan="1"><input class="" type="text"  id="email" name="email" <?php echo $soloLectura; ?> style="width: 100%;" ></input></td>
		

		<td align="right">IBAN:<input class="" type="text"  id="numCuenta2" name="numCuenta2"  style="width: 40px; visibility: hidden; display: none;" value="ES" readonly></input></td>
		<td colspan="1"  style="overflow:hidden; white-space: nowrap;">
			
			<input class="" type="text"  id="numCuenta" name="numCuenta"  style="width: 100%;"   maxlength="22" <?php echo $soloLectura; ?> ></input>
		</td>

		<td align="right">Domiciliado:</td>
		<td><input align="right" class="" type="checkbox" id="cliente_domiciliado" name="cliente_domiciliado"></input></td>	

	</tr>
	<tr>
		<td align="right">Forma de Pago:</td>
		<td colspan="3">
			<select class=""  id="formaPago" name="formaPago" <?php echo $soloLectura; ?>></select> 
			<?php 
			
			if ($_SESSION["permiso_clientes"]==2)
			{
				echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#NuevaFormaPagoProcesoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;">NFP</button>';
			}
			
			
			?>
			
			
			</td>
		<!--<td align="right">Sin Iva:</td>
		<td><input align="right" class="" type="checkbox" id="cliente_sinIva" name="cliente_sinIva"></input>
		-->
		<td align="right">Sin Iva:</td>
		<td align="left"><input align="left" class="" type="checkbox" id="cliente_sinIva" name="cliente_sinIva"></input>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retencion 19%:<input align="right" class="" type="checkbox" id="cliente_conRetencion" name="cliente_conRetencion"></input></td>
	</tr>
	<tr>
		<td align="right">Vencimiento:</td>		
		<td colspan="1"><input class="" type="text"  id="vencimiento" name="vencimiento" <?php echo $soloLectura; ?> style="width: 100%;" ></input></td>
		<td></td>	<td></td>
		<td align="right" style="vertical-align: top;">Retener:</td>
		<td style="vertical-align: top;"><input align="right" class="" type="checkbox" id="cliente_retener" name="cliente_retener"></input></td>
	</tr>
	<tr>
		<td align="right" style="vertical-align: text-top">Nª Cuenta:</td>
		<td colspan="3"><textarea id="nuestraCuenta" style="width: 100%; resize: none"></textarea></td>
		
		<td align="right" style="vertical-align: top;">Pre-Factura:</td>
		<td style="vertical-align: top;"><input align="right" class="" type="checkbox" id="cliente_preFactura" name="cliente_preFactura"></input>&nbsp;&nbsp;(Asignacion nº factura)</td>
			
	</tr>

	<tr>		
		<td align="right">Pedido de Cliente:</td>
		<td><input class="" type="text" id="cliente_pedido" name="cliente_pedido" <?php echo $soloLectura; ?>></input></td>	
		<td colspan="2">Solo se utiliza para las facturas de correo diario</td>	

		<td align="right" style="vertical-align: top;">sin Aplicar P.F.:</td>
		<td style="vertical-align: top;"><input align="right" class="" type="checkbox" id="cliente_sinAplicarPF" name="cliente_sinAplicarPF"></input></td>
	</tr>




	<!--<tr>
		<td align="right" colspan="1">Correo Diario:</td>
		<td align="left" colspan="5"><input  class="" type="checkbox" id="cliente_correoDiario" name="cliente_correoDiario" onChange="gestionMostrarDatosCD()"></input>
		&nbsp;&nbsp;&nbsp;&nbsp;Activo:<input class="" type="checkbox" id="cliente_activo" name="cliente_activo"></input>
		&nbsp;&nbsp;&nbsp;&nbsp;Domiciliado:<input align="right" class="" type="checkbox" id="cliente_domiciliado" name="cliente_domiciliado"></input></td>	
	</tr>-->

<?php
if ($_SESSION["permiso_clientes"]==2)
	{
		echo '<tr><td align="center" colspan="6">
			<button type="button" class="btn btn-info" onClick="modificarCliente()" id="botonModificarCliente">MODIFICAR</button>
			<!--<button type="button" class="btn btn-info" onClick="nuevoCliente()" id="botonNuevoCliente">NUEVO</button>-->
			<button type="button" class="btn btn-info" onClick="crearCliente()" style="visibility: hidden;display: none;" id="botonCrearCliente">CREAR</button>
	</td></tr>';
	}

?>
	


	
<tbody id="datosFacturacionCD">
<tr>
		<td colspan="7" align="center">		
			<button type="button" class="btn btn-secondary"  style="width:100%;">DATOS DE FACTURACION CORREO DIARIO</button>
		</td>
	</tr>

	<tr>
		<td align="right">Cuota de Recogida:</td>
		<td colspan="1"><input class="" type="number" id="fac_cuotaRecogida" name="fac_cuotaRecogida" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
		<td align="right">Periodo de Facturacion:</td>
		<td colspan="1"><select class=""  id="fac_periodoFac" name="fac_periodoFac" <?php echo $soloLectura; ?>></select></td>
	
		<td align="right">% Prod. No Bon.:</td>
		<td colspan="1"><input type="number" id="fac_ProdNoBon" name="fac_ProdNoBon" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	</tr>
	<tr>
		<td align="right">Otros Conceptos:</td>
		<td colspan="2"><input class="" type="text" id="fac_otrosConceptos" name="fac_otrosConceptos" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
		<td align="right">Importe Fijo OtrosConceptos:</td>
		<td colspan="2"><input class="" type="number" id="fac_importeOtrosConceptos" name="fac_importeOtrosConceptos" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	
		
	</tr>

	<tr>
		<td align="right">Provision de Fondos:</td>
		<td colspan="2">
			<select class=""  id="fac_provFondos" name="fac_provFondos"  onChange="mostrarPfFijaImporte()" <?php echo $soloLectura; ?>></select>
			<input type="number" id="fac_pfFijaImporte" name="fac_pfFijaImporte"></input>
		
		</td>
		
		
		
		<td align="right">Cobro Unitario por Envio:</td>
		<td colspan="2"><input class="" type="number" id="fac_cobroUnitarioEnvio" name="fac_cobroUnitarioEnvio" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	</tr>
</tbody>

<!------------------------------------------------------------------------------>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan="7" align="center">		
			<button type="button" class="btn btn-secondary"  style="width:100%;">DIRECCION DE ENVIO</button>
		</td>
	</tr>

	<tr>
		<td align="right">Att:</td>
		<td colspan="1"><input class="" type="text" id="envio_Att" name="envio_Att" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
		<td align="right">Nombre:</td>
		<td colspan="3"><input class="" type="text" id="envio_Nombre" name="envio_Nombre" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	</tr>
	<tr>
		<td align="right">Direccion:</td>
		<td colspan="5"><input class="" type="text" id="envio_Direccion" name="envio_Direccion" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	</tr>
	<tr>
		<td align="right">CP:</td>
		<td colspan="1"><input class="" type="text" id="envio_cp" name="envio_cp" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
		<td align="right">Poblacion:</td>
		<td colspan="1"><input class="" type="text" id="envio_poblacion" name="envio_poblacion" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
		<td align="right">Provincia:</td>
		<td colspan="1"><input class="" type="text" id="envio_provincia" name="envio_provincia" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	</tr>

	<tr>
		<td align="right">Pais:</td>
		<td colspan="2"><input class="" type="text" id="envio_pais" name="envio_pais" style="width: 100%;" <?php echo $soloLectura; ?>></input></td>
	</tr>


	<tr><td>&nbsp;</td></tr>
	<tbody id="grupoDireccionesRutas" bgcolor="">
		<tr>
			<td colspan="7" align="center">		
				<button type="button" class="btn btn-secondary"  style="width:100%;" id="botonClientesDireccionRuta" onClick="cargarClientesDirecRutasGestion()">DIRECCION RUTAS - Mostrar</button>
			</td>
		</tr>
		<tbody id="verClientesDirecRutas" bgcolor="" style="visibility: hidden;display: none;"></tbody>

	</tbody>


	<tr><td>&nbsp;</td></tr>
	<tbody id="grupoObservaciones" bgcolor="">
		<tr>
			<td colspan="7" align="center">		
				<button type="button" class="btn btn-secondary"  style="width:100%;" id="botonObservaciones" onClick="observacionesClienteGestion()">OBSERVACIONES - Mostrar</button>
			</td>
		</tr>
		<tbody id="verObservacionesCliente" bgcolor="" style="visibility: hidden;display: none;"></tbody>
	</tbody>


	<tr><td>&nbsp;</td></tr>
	<tbody id="grupoContactos" bgcolor="">
		<tr>
			<td colspan="7" align="center">		
				<button type="button" class="btn btn-secondary"  style="width:100%;" id="botonClientesContactos" onClick="cargarClientesContactosGestion()">CONTACTOS - Mostrar</button>
			</td>
		</tr>
		<tbody id="verClientesContacto" bgcolor="" style="visibility: hidden;display: none;"></tbody>

	</tbody>
	
			
	
	
	
	<tbody id="grupoRegistros" bgcolor=""   style="visibility: hidden;display: none;" >

		<tr>		
			<td align="center" colspan="6">
				<input type="button" class="btn btn-info" id="cliente_primero" name="cliente_primero" value="primero" onClick="clientePrimero()"></input>
				<input type="button" class="btn btn-info" id="cliente_anterior" name="cliente_anterior" value="<" onClick="clienteAnterior()"></input>
				<input type="number" id="registroActual" name="registroActual" style="width: 100px; text-align: center;" onChange="clienteCambioManualRegistro()"></input>
				<span id="registroUltimo"></span>
				<input type="button" class="btn btn-info" id="cliente_posterior" name="cliente_posterior" value=">" onClick="clientePosterior()"></input>
				<input type="button" class="btn btn-info" id="cliente_ultimo" name="cliente_ultimo" value="ultimo" onClick="clienteUltimo()"></input>

			</td>	
		</tr>
	</tbody>




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




<form id="formImprimirCliente" name="formImprimirCliente" method="post"  target="_blank" action="imprimirInformeCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirCliente"></input>
	<input type="hidden" id="imprimirClaymaCliente" name="imprimirClaymaCliente" value=""></input>
	<input type="hidden" id="imprimirCodigoCliente" name="imprimirCodigoCliente" value=""></input>		
</form>


</table>
			
			
</div> <!-- class="tabla" -->

					


<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_admClientes.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script type="text/javascript"> 
	
	<?php 
	if ($_SESSION["permiso_clientes"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>
	
	cargarComerciales();
	cargarFormasDePago();
	cargarPaises('cliente_pais');
	
	cargarPeriodosFacturacion();
	cargarFacturasProvisionFondo();
	
	
	
	//traspasoClientes(<?php //echo json_encode($clientes); ?>)
	//cargarClientesContactos();
			
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("codigo");
	
	var parametroClayma = params.get("clayma");
	
	if (parametroClayma=="1")
	{
		document.getElementById("tituloCliente").innerHTML="CLAYMA";
	}
	else
	{
		document.getElementById("tituloCliente").innerHTML="CIBELES";
	}
	
	
	if (parametro!=null)
	{		
		cargarUnCliente();
		
	}
	else
	{
		nuevoCliente();
	}
	
	mostrarPfFijaImporte();
	cargarPaises2();

	document.getElementById("button-up").addEventListener("click", scrollUp);

</script>





<?php

echo ("</div>");
echo ("</html>");

?>