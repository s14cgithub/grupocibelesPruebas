<script  src="js/js_global.js" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_admEmisionFacturaPendienteMensual.js" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<?php 

session_start(); 
$_SESSION['titulo']="PRE-FACTURAS - MENSUALES";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");

if ($_SESSION["usuario"]<>"")
{
	

	
	echo '<table align="center"  class="tabla">';
	
	//echo '<tr><td colspan="10" style="text-align: center;"><table>';	
	
	//echo '</table></td></tr>';	
	
	echo '<tbody id="listadoFacturasSinEmitirMensuales" name="listadoFacturasSinEmitirMensuales"></tbody>';
	
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->



<form id="formIrAprefacturaMensual" name="formIrAprefacturaMensual" method="post"  target="" action="prefacturaMensual.php">
	<!--<input type="hidden" id="preFacturaInicialComercial" name="preFacturaInicialComercial" value=""></input>-->
	<input type="hidden" id="preFacturaNumFactura" name="preFacturaNumFactura" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="verPrefacturaMensual"></input>	
	<!--<input type="submit" name="submit" value="submit">-->
</form>





<?php







echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script language="javascript">
	cargarListadoFacturasSinEmitirMensuales();
	
	
</script>