

<?php 

session_start(); 
$_SESSION['titulo']="FACTURAS RECTIFICATIVAS POR SUSTITUCION";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>
	<table border ="0" align="center">		
			<tr>
				<td align="right">Año:</td>
				<td><select class=""  id="anio" name="anio" onChange="buscarFactura()"></select></td>
				
				<td align="right">&nbsp;&nbsp;&nbsp;Clayma:</td>
				<td align="left"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionDeCargarClientesListado('buscarCliente')" style="" > </input>  <span id="pantalla"></span> </td>

				

				<?php
				if ($_SESSION["permiso_soloDireccion"]==2)
				{
					//echo '<td align="right">&nbsp;&nbsp;&nbsp;Agentes-Comerciales:</td>';		
					//echo '<td align="left"><input type="checkbox" id="agenteComercial" name="agenteComercial" onchange="gestionAgenteComercial()" value="" style="" > </input> </td>';

				}
				else
				{					
					//echo '<td align="left"><input type="checkbox" id="agenteComercial" name="agenteComercial" onchange="gestionAgenteComercial()" value="0" style="visibility:hidden" > </input> </td>';
				}
				?>


			</tr>
	</table>




<table align="center">

	<tr>
		<td align="center" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="t1.numero">Factura</option>	
				<option value="t1.descripcion">Campaña</option>	
				<option value="t1.cliente">Cliente</option>
				<option value="t1.presupuesto">Presupuesto</option>
				<option value="t1.precioTotal">Total</option>				
				<option value="t1.aPagar">Total a Pagar</option>				
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		</td>
	
	
		<td align="left" colspan="3">
			Orden:
			<select class="" id="ordenBuscar">
					<option value="t1.descripcion">Campaña</option>	
					<option value="cliente">Cliente</option>
					<option value="numero" selected>Factura</option>	
					<option value="fecha">Fecha Factura</option>
					<option value="t1.fechaPago">Fecha Pago</option>					
					<option value="presupuesto">Presupuesto</option>
					<option value="precioTotal">Total</option>				
					<option value="aPagar">Total a Pagar</option>
									
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoNumerosFacturasModal" data-whatever="@mdo" id="btnImprimir">Imprimir</button>


		</td>	
	</tr>
	<tr>
		<td align="left" colspan="4">Fecha Inicio:
			<input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> Fecha Fin:<input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		 &nbsp;&nbsp;&nbsp;&nbsp; Sólo Pagadas:
		<input type="checkbox" id="facSoloPagadas" name="facSoloPagadas" value="" onchange="" style="" > </input>
		</td>

		
	</tr>
	

	<tr><td colspan=""><hr></td></tr>
</table>




	<?php

	
	echo '<table align="center" class="tabla" style="border-color:grey;">';

	
	echo '<tbody id="listadoFacturasSinEmitir" name="listadoFacturasSinEmitir"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->






<!-- IMPRIMIR VARIAS FACTURAS POR RANGO DE NUMERO DE FACTURA -->
<div class="modal fade" id="imprimirRangoNumerosFacturasModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">Imprimir Facturas: </h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
			 
      		<div class="modal-body">

				
				
        		<form>
         			<div class="form-group">
					 	<!--<h1 align="center"><img src="imagenes/ordenadorPc1.gif" ></h1>-->
            			<label for="recipient-name" class="col-form-label">Primer Numero de Factura</label>						
						<input type="number" class="form-control" id="primeraFacturaImprmirModal"></input>
          			</div>		 			
        		</form>
				<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Ultimo Numero de Factura</label>						
						<input type="number" class="form-control" id="ultimaFacturaImprmirModal"></input>
          			</div>		 			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirFacturaRango()">Imprimir</button>
      		</div>
    	</div>
  	</div>
</div>




<form id="formImprimirFactura"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirNumFactura" name="imprimirNumFactura" value=""></input>
	<input type="hidden" id="anioSeleccionado0" name="anioSeleccionado0" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>
<form id="formImprimirFacturaRango"  method="post"  target="_blank" action="imprimirFactura.php">
	<input type="hidden" id="imprimirPrimeraFactura" name="imprimirPrimeraFactura" value=""></input>
	<input type="hidden" id="imprimirUltimaFactura" name="imprimirUltimaFactura" value=""></input>
	<input type="hidden" id="anioSeleccionado1" name="anioSeleccionado1" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirFacturaClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirNumFacturaClayma" name="imprimirNumFacturaClayma" value=""></input>
	<input type="hidden" id="anioSeleccionado3" name="anioSeleccionado3" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirFacturaRangoClayma"  method="post"  target="_blank" action="imprimirFacturaClayma.php">
	<input type="hidden" id="imprimirPrimeraFacturaClayma" name="imprimirPrimeraFacturaClayma" value=""></input>
	<input type="hidden" id="imprimirUltimaFacturaClayma" name="imprimirUltimaFacturaClayma" value=""></input>
	<input type="hidden" id="anioSeleccionado2" name="anioSeleccionado2" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFactura"></input>	
</form>

<form id="formImprimirAbono"  method="post"  target="_blank" action="imprimirAbono.php">
	<input type="hidden" id="imprimirNumFacturaAbono" name="imprimirNumFacturaAbono" value=""></input>	
	<input type="hidden" id="imprimirAnioSeleccionado" name="imprimirAnioSeleccionado" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbono"></input>	
</form>

<form id="formImprimirAbonoClayma"  method="post"  target="_blank" action="imprimirAbonoClayma.php">
	<input type="hidden" id="imprimirNumFacturaAbonoClayma" name="imprimirNumFacturaAbonoClayma" value=""></input>	
	<input type="hidden" id="imprimirAnioSeleccionadoClayma" name="imprimirAnioSeleccionadoClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbonoClayma"></input>	
</form>

<form id="formImprimirFacRec"  method="post"  target="_blank" action="imprimirFacturaRec.php">
	<input type="hidden" id="imprimirNumFacturaRec" name="imprimirNumFacturaRec" value=""></input>		
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFacturaRec"></input>	
</form>

<form id="formImprimirFacRecClayma"  method="post"  target="_blank" action="imprimirFacturaRecClayma.php">
	<input type="hidden" id="imprimirNumFacturaRecClayma" name="imprimirNumFacturaRecClayma" value=""></input>		
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirFacturaRecClayma"></input>	
</form>


<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarFacturasCibelesExcel.php">	
	<input type="hidden" id="exportarClayma" name="exportarClayma" value=""></input>
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>
	<input type="hidden" id="exportarAnioSeleccionado" name="exportarAnioSeleccionado" value=""></input>	
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
</form>


<form id="formExportarAgenteComercialExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarFacturasAgenteComercialExcel.php">	
	<input type="hidden" id="exportarACClayma" name="exportarACClayma" value=""></input>	
	<input type="hidden" id="exportarACCondiciones" name="exportarACCondiciones" value=""></input>
	<input type="hidden" id="exportarACAnioSeleccionado" name="exportarACAnioSeleccionado" value=""></input>	
	<input type="hidden" id="exportarACAccion" name="exportarACAccion" value="exportarExcel"></input>			
</form>

<form id="formIrAprefacturaConNum" name="formIrAprefacturaConNum" method="post"  target="" action="prefacturaConNum.php">
	<input type="hidden" id="preFacturaNumFactura" name="preFacturaNumFactura" value=""></input>
	<input type="hidden" id="preFacturaNumAnio" name="preFacturaNumAnio" value=""></input>
	<input type="hidden" id="preFacturaNumClayma" name="preFacturaNumClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="verPrefactura"></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>

<form id="formIrAfacRecDiferencias" name="formIrAfacRecDiferencias" method="post"  target="" action="crearFacRecDiferencias.php">
	<input type="hidden" id="facRecDiferencias_numeroFactura" name="facRecDiferencias_numeroFactura" value=""></input>
	<input type="hidden" id="facRecDiferencias_clayma" name="facRecDiferencias_clayma" value=""></input>
	<input type="hidden" id="facRecDiferencias_OrigenFac" name="facRecDiferencias_OrigenFac" value="facturaRectificativa"></input>
	<input type="hidden" id="facRecDiferencias_Accion" name="facRecDiferencias_Accion" value="verRecDiferencias"></input>	
</form>

<form id="formIrAfacRecSustitucion" name="formIrAfacRecSustitucion" method="post"  target="" action="crearFacRecSustitucion.php">
	<input type="hidden" id="facRecSustitucion_numeroFactura" name="facRecSustitucion_numeroFactura" value=""></input>
	<input type="hidden" id="facRecSustitucion_clayma" name="facRecSustitucion_clayma" value=""></input>
	<input type="hidden" id="facRecSustitucion_OrigenFac" name="facRecSustitucion_OrigenFac" value="facturaRectificativa"></input>
	<input type="hidden" id="facRecSustitucion_Accion" name="facRecSustitucion_Accion" value="verRecSustitucion"></input>	
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
<script  src="js/js_facSustitutivas.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">
	
	cargarAnios("anio");
	document.getElementById("anio").value = 2025;
	//gestionDeCargarClientesListado('buscarCliente');
	//cargarListadoFacturas();
	buscarFactura();
	
	/*jQuery('document').ready(function($)
							{
		var subir = $('.back-to-top');
		subir.click(function(e){
			
		})
	})*/
	
	
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
</script>