

<?php 

session_start(); 
$_SESSION['titulo']="NO FACTURABLES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	?>


<table align="center">

	<tr>
		<td align="right" colspan="">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="numNoFactura">Numero</option>	
				<option value="presupuesto">Presupuesto</option>
				<option value="campana">Campaña</option>	
				<option value="cliente">Cliente</option>			
				<option value="importePresupuesto">Importe</option>	
						
			</select>
		Texto:
			<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
	
	
		<td align="right" colspan="">
			Orden:
			<select class="" id="ordenBuscar">
					<option value="numNoFactura">Numero</option>	
					<option value="presupuesto">Presupuesto</option>
					<option value="campana">Campaña</option>	
					<option value="cliente">Cliente</option>			
					<option value="importePresupuesto">Importe</option>	
					<option value="fecha">Fecha</option>				
			</select>
		
			Desc: <input type="checkbox" id="ordenDesc" checked></input>
		

			<button type="button" class="btn btn-info" onClick="buscarFactura()">Buscar</button>
			<button type="button" class="btn btn-info" onClick="gestionExportarExcel()">Excel</button>
			<!--<input type="submit" class="btn btn-info" onClick="gestionExportarExcelFacturaCibeles()" value="Excel" ></input>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#imprimirRangoNumerosFacturasModal" data-whatever="@mdo">Imprimir</button>-->


		</td>	
	</tr>
	<tr>
		<td align="right">
			Fecha Inicio: <input class="" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input> 
			Fecha Fin: <input class="" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
		</td>	
		<td align="right">
			&nbsp;&nbsp;&nbsp;&nbsp;Excluir "Instituto": <input type="checkbox" id="excluirInstituto"></input> 
			&nbsp;&nbsp;&nbsp;&nbsp;con Importe: <input type="checkbox" id="conImporte"></input>
			&nbsp;&nbsp;&nbsp;&nbsp;Cristina: <input type="checkbox" id="crisMagia"></input>


 	


		
		<?php 
		/*if ($_SESSION["permiso_noFacProcesado"]==1 || $_SESSION["permiso_noFacProcesado"]==2)
		{
			echo ' &nbsp;&nbsp;&nbsp;&nbsp;Solo Procesados:<input type="checkbox" id="ordenProcesado"></input>';
		}
		else
		{
			echo '<span style="display: none; visibility: hidden"> &nbsp;&nbsp;&nbsp;&nbsp;Solo Procesados:<input type="checkbox" id="ordenProcesado"></input></span>';
		}*/
	
		?>
		</td>

		
	</tr>

<?php 
		if ($_SESSION["permiso_noFacProcesado"]==1 || $_SESSION["permiso_noFacProcesado"]==2)
		{
			echo '<tr><td></td><td><input type="radio" id="ordenProcesadosSolo"
     name="ordenProcesados" value="ordenProcesadosSolo">
    <label for="ordenProcesadosSolo">Solo Procesados</label>
	<input type="radio" id="ordenProcesadosSin"
     name="ordenProcesados" value="ordenProcesadosSin">
    <label for="ordenProcesadosSin">Sin Procesar</label>
	<input type="radio" id="ordenProcesadosTodos"
     name="ordenProcesados" value="ordenProcesadosTodos" checked>
    <label for="ordenProcesadosTodos" >Todos</label></td></tr>
	<tr><td colspan="2"><hr></td></tr>';
		}
		else
		{
			echo '<span style="display: none; visibility: hidden"> <tr><td></td><td><input type="radio" id="ordenProcesadosSolo"
     name="ordenProcesados" value="ordenProcesadosSolo">
    <label for="ordenProcesadosSolo">Solo Procesados</label>
	<input type="radio" id="ordenProcesadosSin"
     name="ordenProcesados" value="ordenProcesadosSin">
    <label for="ordenProcesadosSin">Sin Procesar</label>
	<input type="radio" id="ordenProcesadosTodos"
     name="ordenProcesados" value="ordenProcesadosTodos" checked>
    <label for="ordenProcesadosTodos" >Todos</label></td></tr>
	<tr><td colspan="2"><hr></td></tr></span>';
		}
		
?>


</table>





	<?php

	
	echo '<table align="center" class="tabla" style="border-color:grey;">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listado" name="listado"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->





<!-- OBSERVACION DE LA FACTURA -->
<div class="modal fade" id="observacionFacturaModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">NUMERO: <span id="numFacturaModal"></span><span style="visibility: hidden; display: none;" id="claymaModal"></span></h5>
       			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<!--<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Observaciones</label>						
						<textarea maxlength="250" rows="5" class="form-control" id="observacionModal"></textarea>
          			</div>		 			
        		</form>
      		</div>-->
			<div class="modal-body">
        		<form>
         			<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Observaciones Internas</label>						
						<textarea maxlength="250" rows="5" class="form-control" id="observacionInternaModal"></textarea>
          			</div>		 			
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" id="modalModificarObservacion" class="btn btn-primary" data-dismiss="" onClick="modificarObservacion()">Modificar</button>
      		</div>
    	</div>
  	</div>
</div>

<!--ELIMINAR NUMERO-->
<div class="modal fade" id="eliminarFacturaModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="eliminarNumeroModalLabel">ELIMINAR NUMERO NO FACTURABLE</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
			<h5 id="estadoModal" style="visibility:hidden; display: none"></h5>
      		<div class="modal-body">
        		<form>
					<div class="form-group">
						<p id="numFacturaModal" style="display: none; visibility: hidden"></p>
            			<b><h5 id="textoFechaFacturaModal" style="color: red !important">Se borrará el numero de la 'No factura'. Y el presupuesto correspondiente volverá a aparecer en las Prefacturas.</h5></b>
          			</div>
        		</form>
				<form>
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Motivo de Eliminacion</label>						
						<textarea maxlength="250" rows="5" class="form-control" id="motivoEliminacionModal"></textarea>
          			</div>
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="eliminarNumNoFacturable2()">Eliminar</button>
      		</div>
    	</div>
	</div>
</div>




<form id="formImprimirAbono"  method="post"  target="_blank" action="imprimirAbono.php">
	<input type="hidden" id="imprimirNumFacturaAbono" name="imprimirNumFacturaAbono" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbono"></input>	
</form>

<form id="formImprimirAbonoClayma"  method="post"  target="_blank" action="imprimirAbonoClayma.php">
	<input type="hidden" id="imprimirNumFacturaAbonoClayma" name="imprimirNumFacturaAbonoClayma" value=""></input>	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirAbonoClayma"></input>	
</form>


<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarNoFacturablesExcel.php">		
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>	
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
</form>



<!--<form id="formExportarExcel"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarOtCibelesExcel.php">	
	<input type="hidden" id="exportarClayma" name="exportarClayma" value=""></input>
	<input type="hidden" id="exportarCondiciones" name="exportarCondiciones" value=""></input>
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>			
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
<script  src="js/js_noFactura.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script language="javascript">

<?php
	
	if ($_SESSION["permiso_noFacProcesado"]==1)
	{
		echo 'mostrarModificarProcesado=false;';
		echo 'mostrarVisualizarProcesado=true;';
	}
	else if ($_SESSION["permiso_noFacProcesado"]==2)
	{
		echo 'mostrarModificarProcesado=true;';
		echo 'mostrarVisualizarProcesado=true;';
	}
	else
	{
		echo 'mostrarModificarProcesado=false;';
		echo 'mostrarVisualizarProcesado=false;';
	}
		

		
?>
	
	
	buscarFactura();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	

	
	
</script>