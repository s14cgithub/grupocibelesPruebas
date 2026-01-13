

<?php 

session_start(); 
$_SESSION['titulo']="TARIFAS - GRAN FORMATO";
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


<table align="center" style="visibility: hidden;">
							<tr>								
								<td align="center">Tipo de Proceso</td>
								<td align="center">Material</td>
								<td align="center">Concepto</td>								
                				<td align="center">Precio</td>						
								<td align="center"></td>
							</tr>
							<tr>
								<td><select name="GF_TipoProceso" id="GF_TipoProceso"></select></td>
								<td><select name="GF_Material" id="GF_Material"></select></td>
								<td><select name="GF_Concepto" id="GF_Concepto"></select></td>
										
                				<td><input type="number" name="GF_Precio" id="GF_Precio"></input></td>	
								
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="insertarTarifasGranFormato()" ></td>
							</tr>
							
</table>



<table align="center" class="tabla">	
	<tbody id="listadoTarifas" name="listadoTarifas"></tbody>		
</table>








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
<script  src="js/js_tarifasGranFormato.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">

	cargarGFTipoProceso("GF_TipoProceso");
	cargarGFMaterial("GF_Material");
	cargarGFConcepto("GF_Concepto");
	
	

	cargarTarifasGranFormato();
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>