

<?php 

session_start(); 
$_SESSION['titulo']="PROVISIONES DE FONDOS";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
}
else
{
	header('Location: index.php');	
}

?>

<table align="center">

	<tr>
		<td align="center" colspan="6">Buscar por:
			<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="campana">Campaña</option>
				<option value="codigo">Codigo Cliente</option>	
				<option value="fechaCobro">Fecha Cobro</option>
				<!--<option value="fechaCreacion">Fecha Creacion</option>-->
				<option value="formaPago">Forma de Pago</option>
				<option value="importe">Importe</option>
				<option value="nombre_empresa">Nombre Cliente</option>
				<option value="presupuesto">Presupuesto</option>
						
			</select>

		Texto:
		<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		Orden: 
		<select class="" id="ordenBuscar">
			<option value="campana">Campaña</option>
			<option value="codigo">Codigo Cliente</option>
			<option value="fechaCobro">Fecha Cobro</option>
			<option value="fechaCreacion">Fecha Creacion</option>
			<option value="formaPago">Forma de Pago</option>
			<option value="importe">Importe</option>
			<option value="nombre_empresa">Nombre Empresa</option>
			<option value="presupuesto">Presupuesto</option>
				
		</select>
	Desc: <input type="checkbox" id="buscarDesc"></input>
	Solo Cobradas: <input type="checkbox" id="buscarCobrada" onChange="gestionSoloCobradas()"></input>
	No Aplicables: <input type="checkbox" id="buscarArreglo" onChange="gestionSoloArreglos()"></input>
		<br>
		<button type="button" class="btn btn-info" onClick="cargarListadoPF()">BUSCAR</button>
		<button type="button" class="btn btn-info" onClick="imprimirInformeProvision()">IMPRIMIR</button>
	
	</td>
		
		
	</tr>

	<tr><td colspan="6"><hr></td></tr>
</table>



	<table align="center" class="tabla">
	<tbody id="listadoPF" name="listadoPF"></tbody>
	</table>
	

<form id="formImprimirInformeProvisionFondo" name="formImprimirInformeProvisionFondo" method="post"  target="_blank" action="imprimirInformeProvisionFondo.php">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirInforme"></input>
</form>



</div> <!-- class="tabla" -->




<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php


echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_provisionFondos.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	
	<?php
	if ($_SESSION["provisionFondos"]==2)
	{
		echo "paraBorrarPrevision='Si';";
	}
	else
	{
		echo "paraBorrarPrevision='No';";
	}
		
	
	?>
	
	
	
	cargarListadoPF();
	document.getElementById("button-up").addEventListener("click", scrollUp);
	
	
	
</script>