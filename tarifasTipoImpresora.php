

<?php 

session_start(); 
$_SESSION['titulo']="TARIFAS - TIPO IMPRESORA";
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


<!--<table align="center">
							<tr>								
								<td align="center">Tamaño</td>
								<td align="center">Tipo</td>
								<td align="center">Acabado</td>
								<td align="center">Gramaje</td>
                <td align="center">Precio</td>						
								<td align="center"></td>
							</tr>
							<tr>
								<td><select name="RN_Tamanio" id="RN_Tamanio"></select></td>
								<td><select name="RN_Tipo" id="RN_Tipo"></select></td>
								<td><select name="RN_Acabado" id="RN_Acabado"></select></td>
								<td><select name="RN_Gramaje" id="RN_Gramaje"></select></td>			
                <td><input type="number" name="RN_Precio" id="RN_Precio"></input></td>	
								
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="insertarTarifasPapel()" ></td>
							</tr>
							
</table>-->



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
<script  src="js/js_tarifasTipoImpresora.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">

 
	

	cargarTarifasTipoImpresora();
	
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>