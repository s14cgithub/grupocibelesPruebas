
<?php 

session_start(); 

$_SESSION['titulo']="FRANQUEO - CLIENTES AUTORIZADOS";
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
	
	<td align="center" colspan="6"> &nbsp&nbsp Buscar por:
		<select class=""  id="buscarCampo" name="buscarCampo">
				<option value="codigo" selected>Codigo Cliente</option>
				<option value="nombre_empresa"  selected="selected">Nombre Cliente</option>
				<option value="nombre_franqueo">Nombre Franqueo</option>
		</select>

		Texto:
		<input class="" type="text" id="buscarTexto" name="buscarTexto"></input>
		Orden: 
		<select class="" id="ordenBuscar">
				<option value="codigo" selected>Codigo Cliente</option>
				<option value="nombre_empresa"  selected="selected">Nombre Cliente</option>
				<option value="nombre_franqueo">Nombre Franqueo</option>
				
				
				
				
		</select>
		Desc: <input type="checkbox" id="buscarDesc" ></input>
		
		<button type="button" class="btn btn-info" onClick="buscarRegistroFranqueo()">BUSCAR</button>
		
	</td>		
</tr>

<tr><td colspan="6"><hr></td></tr>
</table>
<!-- ------------------------------------------------------------------------->
	


<br>	
	
<table id="historico1" align="center" class="tabla"></table>
<br>


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
<script  src="js/js_franqueoClientesAutorizados.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">	

<?php 
	if ($_SESSION["permiso_clientesAutorizados_franqueo"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>


	buscarRegistroFranqueo();

	//buscarRegistroFranqueo();
	document.getElementById("button-up").addEventListener("click", scrollUp);
</script>