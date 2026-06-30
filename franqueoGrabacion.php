
<?php 

session_start(); 

$_SESSION['titulo']="FRANQUEO - GRABACION";
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
	<tr>
		<td align="right">Elegir un Producto: </td>
		<td>
			<select name="tarifaProducto" id="tarifaProducto"  style="width:100%" onChange="rellenarCamposGrabacionFranqueo()"> </select>		
		</td>
		
		<td align="right">Elegir un cliente: </td>
		<td>
			<select name="listadoNombreFranqueo" id="listadoNombreFranqueo"  style="width:100%" onChange="franqueo_irAcampo_Ot()"> </select>
		
		</td>
	</tr>
	
	<tr>
		
		<td align="right">Fecha: </td>
		<td>
			<input type="date" name="fecha" id="fecha" value="<?php echo date("Y-m-d");?>"  onChange="rellenarCamposGrabacionFranqueo()" > </input>		
		</td>
		<td align="right">Numero de Envios: </td>
		<td>
			<input type="text" name="numEnvios" id="numEnvios" value="0" readonly> </input>		
		</td>
	</tr>
	<tr>
		
		<td align="right">ot: </td>
		<td>
			<input type="text" name="ot" id="ot" onKeyUp="franqueo_irAcampo_PrimerLocal(event,'ot')" onblur="verOtSidi();";  > </input>		
		</td>

		<td align="right">ot SIDI: </td>
		<td>
			<input type="text" name="otSidi" id="otSidi" onKeyUp="franqueo_irAcampo_PrimerLocal(event,'otSidi')" disabled > </input>		
		</td>
		
	</tr>
	<tr><td>&nbsp;</td></tr>

	<tbody id="tipoCertificado">
	<tr>
		<td colspan="4" align="center">

		 <input type="radio" id="certificada" name="certificadoTipo" value="certificada" checked>
  		<label for="certificada">certificada</label>
			&nbsp;&nbsp;
		<input type="radio" id="conAcuse" name="certificadoTipo" value="conAcuse">
  		<label for="conAcuse">Acuse de Recibo</label>
			&nbsp;&nbsp;
		<input type="radio" id="conPEE" name="certificadoTipo" value="conPEE">
  		<label for="conPEE">PEE</label>
		
		</td>
	</tr>
	</tbody>

	<tbody id="tipoNotificacion">
	<tr>
		<td colspan="4" align="center">

		 <input type="radio" id="notificacion" name="notificacionTipo" value="notificacion" checked>
  		<label for="certificada">notificacion</label>
			&nbsp;&nbsp;		
		<input type="radio" id="conPEEnot" name="notificacionTipo" value="conPEE">
  		<label for="conPEE">PEE</label>
		
		</td>
	</tr>
	</tbody>

</table>

	
<br>
<input type="hidden" id="modificarFranqueo" value="false">



<table id="grabacionInput" align="center" border="1"></table>

	
<br>
<center>

<?php 
	if ($_SESSION["permiso_grabarFranqueo"]==2)
	{		
		echo '<button id="botonGrabar" type="button" class="btn btn-info" onClick="grabarFranqueo()">GRABAR</button>';
	}

	?>



	
	<?php 
	if ($_SESSION["permiso_franqueoF12"]==2)
	{
		
		echo ('<button id="botonGrabar" type="button" class="btn btn-info" onClick="generacionTxtCorreosFranqueo_sidi(0)">GENERAR FICHERO</button>');
		echo ('<button id="botonGrabar" type="button" class="btn btn-info" onClick="generacionTxtCorreosFranqueo_sidi(1)">POST F12</button>');
	}
	if ($_SESSION["permiso_franqueoF12"]==2 || $_SESSION["permiso_franqueoF12"]==1)
	{
		echo ('<label>Informes: </label>');
		echo ('<button id="botonGrabar" type="button" class="btn btn-info" onClick="verInformeFranqueoRegistro()">REGISTRO</button>');
		echo ('<button id="botonGrabar" type="button" class="btn btn-info" onClick="verInformeFranqueoCliente()">CLIENTES</button>');
		echo ('<button id="botonGrabar" type="button" class="btn btn-info" onClick="verInformeFranqueoProducto()">PRODUCTO</button>');
		echo ('<button id="botonInformeResumen" type="button" class="btn btn-info" onClick="verInformeFranqueoResumen()">RESUMEN</button>');
		
	}
	
	
	
	
	
	
	?>
	
	<!--<input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" data-toggle="modal" data-target="#modificarFranqueoModal" data-whatever="@mdo" >-->
	
	
	

</center> 
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
				<!--<td colspan=""><label>idCliente:</label><input type="number" id="idClienteModal" value=""></td>	-->
				<td align="right"><label>Cliente:</label><select id="idClienteModal"></select></td>
				
				<td colspan="" align="right"><label>Fecha:</label><input type="date" id="fechaModal"  value="" ></td>	
				
				<td colspan="" align="right"><label>Total:</label><input type="number" id="totalModal"  value="" readonly ></td>

				<!--<td><input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarFranqueoTipo('+datos[contador]["id"]+')" >
					
				<input type="image" id="'+datos[contador]["id"]+'_modificarDetalle" value="" src="imagenes/eliminar.png" style="width:15px;" onclick="eliminarTipoFranqueoTipo('+datos[contador]["id"]+')" ></td>
				<td></td>
				<td></td>
				<td></td>-->
				
			</tr>
			<tr>
				<td colspan="" align="right"><label>OT:</label><input type="text" id="otModal"  value="" ></td>
				<td colspan="" align="right"><label>&nbsp;&nbsp;&nbsp;OT SIDI:</label><input type="text" id="otSidiModal"  value="" ></td>
				<td></td>
			</tr>
			
			<tr><td>&nbsp;</td></tr>
			<tr><td align="center" style="text-align: center;">	
			<tbody id="datosTipoFranqueo" align="center" style="text-align: center;">
				
			<!--<tr style="font-weight: bold">				
				<td align="center">OT</td>	
				<td align="center">Tipo</td>	
				<td align="center">Unidades</td>
				<td align="center">importe</td>
				<td align="center"></td>
			</tr>-->
			</tbody>
			</td></tr>	
		  </table>
		  
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="copiarPresupuesto()">Modificar</button>-->
      </div>
    </div>
  </div>
</div>



<!--<a href="archivosDescargas/exportarAlbaranquesCorreosFranqueo.txt" download="<?php //echo $_SESSION["rutaTXTalbaran"]; ?>.txt" id="generarAlbaranesCorreosFranqueo" style="visibility: hidden">button</a>-->
<a href="" download="" id="generarAlbaranesCorreosFranqueo_sidi" style="visibility: hidden">button</a>
<a href="" download="" id="generarAlbaranesCorreosFranqueo" style="visibility: hidden">button</a>

<!--<a href="archivosDescargas/albaranesTXTcorreos/2021-07-13_PUBLICORREO OPTIMO.zip" download="archivosDescargas/albaranesTXTcorreos/2021-07-13_PUBLICORREO OPTIMO.zip" id="generarAlbaranesCorreosFranqueo" style="visibility: hidden">button</a>-->

<form id="formImprimirInforme"  method="post"  target="_blank" action="imprimirFranqueoInformeResumen.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInforme"></input>
	<input type="hidden" id="imprimirInformeProducto" name="imprimirInformeProducto" value=""></input>
	<input type="hidden" id="imprimirInformeFecha" name="imprimirInformeFecha" value=""></input>	
</form>

<form id="formImprimirInformeCliente"  method="post"  target="_blank" action="imprimirFranqueoInformeCliente.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInformeCliente"></input>
	<input type="hidden" id="imprimirInformeClienteProducto" name="imprimirInformeClienteProducto" value=""></input>
	<input type="hidden" id="imprimirInformeClienteFecha" name="imprimirInformeClienteFecha" value=""></input>	
</form>

<form id="formImprimirInformeProducto"  method="post"  target="_blank" action="imprimirFranqueoInformeProducto.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInformeProducto"></input>
	<input type="hidden" id="imprimirInformeProductoId" name="imprimirInformeProductoId" value=""></input>
	<input type="hidden" id="imprimirInformeProductoFecha" name="imprimirInformeProductoFecha" value=""></input>	
</form>

<form id="formImprimirInformeRegistro"  method="post"  target="_blank" action="imprimirFranqueoInformeRegistro.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInformeRegistro"></input>
	<input type="hidden" id="imprimirInformeRegistroId" name="imprimirInformeRegistroId" value=""></input>
	<input type="hidden" id="imprimirInformeRegistroFecha" name="imprimirInformeRegistroFecha" value=""></input>	
</form>


<form id="formExportarExcelSidi"  method="post"  target="_blank" action="PHPExcel/archivosCibeles/exportarSidiCertyNotExcel.php">	
	<input type="hidden" id="exportarSidiExcel_idProducto" name="exportarSidiExcel_idProducto" value=""></input>
	<input type="hidden" id="exportarSidiExcel_modo" name="exportarSidiExcel_modo" value=""></input>
	<input type="hidden" id="exportarAccion" name="exportarAccion" value="exportarExcel"></input>
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
<script  src="js/js_franqueoGrabacion.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">
	<?php


		if ($_SESSION["permiso_grabarFranqueo"]==1)
		{		
			echo 'permisosSoloLectura = true;';
		}

		if ($_SESSION["permiso_franqueoF12"]==2)
		{
			echo ('document.getElementById("modificarFranqueo").value="true";');
		}
		else
		{
			echo ('document.getElementById("modificarFranqueo").value="false";');
		}
	
	?>
	
	
	
	
	cargarTarifasProductos();
	cargarListadoNombreFranqueo("listadoNombreFranqueo");
	rellenarCamposGrabacionFranqueo();
	cargarListadoNombreFranqueo("idClienteModal");

	
	let params2 = new URLSearchParams(window.location.search);

	//if (params2.size==3) //no lo reconoce windows 8 (chrome)
	if (params2.has("referencia") && params2.has("producto") && params2.has("fecha"))
	{
		var parametroProducto = params2.get("producto");
		var parametroFecha = params2.get("fecha");
		var parametroReferencia = params2.get("referencia");		

		document.getElementById("tarifaProducto").value = parametroProducto;		
		document.getElementById("fecha").value = parametroFecha;

		modificarRegistroFranqueo(parametroReferencia);
	}	
	
	
	
	rellenarHistoricoFranqueo();
	
	obtenerAnioActual();

	document.getElementById("button-up").addEventListener("click", scrollUp);
	

</script>