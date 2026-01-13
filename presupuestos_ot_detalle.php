


<?php 

session_start(); 
$_SESSION['titulo']="OT - DETALLES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	if ($_SESSION["permiso_pdaGestion"]==1)
	{
		$soloLectura="readonly";
	}
	
	echo '<table align="center" border="0"  class="">';
	
	
	
	echo ('<tr><td colspan="8" id="numPresupuestoTd" align="center">
	<span id="nombrePresupuesto" style="font-size: 20px;font-weight: bold;"></span>
	<span id="numPresupuesto" style="font-size: 30px;font-weight: bold;"></span>
	<span id="letraPresupuesto" style="font-size: 30px;font-weight: bold;"></span>&nbsp;&nbsp;&nbsp;&nbsp;
	<span id="botonVerDatosGenericosPresupuesto"></span>
	
	
	</td></tr>');
	
	
	echo '<tr><td colspan="8"><hr></td></tr>';
	
	
	
	echo '<tbody id="detallesPresupuesto" name="detallesPresupuesto" style="visibility: hidden;display: none;"></tbody>';
	
	
	echo '<tr><td colspan="7"><hr></td></tr>';

	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>




</div> <!-- class="tabla" -->

	
<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>


<?php





echo '<input type="hidden" id="permiso_otBajadaAutomatico" value="'.$_SESSION["permiso_otBajadaAutomatico"].'"></input>';

echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_presupuestosOtDetalle.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">
	<?php 
	if ($_SESSION["permiso_ot"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>
	
	
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("presupuesto");
	
	if (parametro!=null)
	{	
		document.getElementById("numPresupuesto").innerHTML = parametro;
		
		cargarDetallesOt();
		
	}

	
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>