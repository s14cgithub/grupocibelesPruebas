

<?php 

session_start(); 
$_SESSION['titulo']="RUTAS VINCULACIONES";
$ruta="/";


require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
//require($ruta."Archivos Comunes/constantes.php");


?>
<table align="center" border="0"  class="">
	
<?php 

if ($_SESSION["permiso_rutas"]==2)
	{
		echo '<tr>
				<td>
					<table border ="0" align="center">
						
						<tr><td>Vinculaciones</td></tr>
						<tr>
							<td align="center">
								<label>Conductor:</label>
								<select id="listadoEmpleado"></select>
							</td>
							
							<td align="center">
								<label>Ruta:</label>
								<select id="rutaVinculacion"></select>
							</td>
							<td align="center">&nbsp;
								
							</td>
							<td align="right">
								<input type="submit" class="btn btn-info" onClick="insertarRutasConductoresVinculaciones();" value="Insertar" ></input>
							</td>
						</tr>
					</table>
					
					<table>

					</table>
				</td>
			</tr>';
	}
	?>
			
	<tr>
		<td>
			<hr>
			<table class="tabla" align="center">
				<tbody id="vinculacionesConductoresRutas" name="vinculacionesConductoresRutas"></tbody>
			</table>
		</td>
	</tr>	
</table>

	
	






</div> <!-- class="tabla" -->

					


	
				


<?php
echo ("</div>");
echo ("</body>");
echo ("</html>");

?>

<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_rutasVinculaciones.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">	
	<?php
	if ($_SESSION["permiso_rutas"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>




	idInputListado = "listadoEmpleado";
	cargarListadoEmpleado();
	
	idInputListado = "rutaVinculacion";
	cargarRutasParaVincular();
	idInputListado = "";
	
	cargarRutasConductoresVinculaciones();

</script>