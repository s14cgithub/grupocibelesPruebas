
<?php 

session_start(); 
$_SESSION['titulo']="RUTAS PLANTILLA";
$ruta="/";


require($ruta."comprobarSesion.php");
require($ruta."Archivos Comunes/cabecera.php");
//require($ruta."Archivos Comunes/constantes.php");


?>
<table align="center" border="0"  class="">
	
	<tr>
		<td>
			<table border ="0" align="center">
				<tr>
					<td align="center">
						<span style="margin-left: 100px">
							<span class="textoNegrita">Buscar</span>

							Cliente: <select name="clienteRutaBuscar" id="clienteRutaBuscar" style="max-width:500px;"></select>
						</span>
					
						<span style="margin-left: 100px">
							

							Ruta: <select type="text" name="rutaRutaBuscar" id="rutaRutaBuscar"></select>
						</span>
					</td>
				</tr>



				<tr>
					<td align="center">
						<label for="columnas">Ordenar Por:</label>

						<select name="orden" id="orden">
							<option value="<?php echo rutasPlantilla_id ?>"><?php echo rutasPlantilla_id ?></option>
							<option value="<?php echo rutasPlantilla_idCliente ?>"><?php echo rutasPlantilla_idCliente ?></option>
							<option value="<?php echo rutasPlantilla_lunesRuta ?>"><?php echo rutasPlantilla_lunesRuta ?></option>
							<option value="<?php echo rutasPlantilla_lunesHora ?>"><?php echo rutasPlantilla_lunesHora ?></option>
							<option value="<?php echo rutasPlantilla_martesRuta ?>"><?php echo rutasPlantilla_martesRuta ?></option>
							<option value="<?php echo rutasPlantilla_martesHora ?>"><?php echo rutasPlantilla_martesHora ?></option>
							<option value="<?php echo rutasPlantilla_miercolesRuta ?>"><?php echo rutasPlantilla_miercolesRuta ?></option>
							<option value="<?php echo rutasPlantilla_miercolesHora ?>"><?php echo rutasPlantilla_miercolesHora ?></option>
							<option value="<?php echo rutasPlantilla_juevesRuta ?>"><?php echo rutasPlantilla_juevesRuta ?></option>
							<option value="<?php echo rutasPlantilla_juevesHora ?>"><?php echo rutasPlantilla_juevesHora ?></option>
							<option value="<?php echo rutasPlantilla_viernesRuta ?>"><?php echo rutasPlantilla_viernesRuta ?></option>
							<option value="<?php echo rutasPlantilla_viernesHora ?>"><?php echo rutasPlantilla_viernesHora ?></option>
							<option value="<?php echo rutasPlantilla_contacto ?>"><?php echo rutasPlantilla_contacto ?></option>
							<option value="<?php echo rutasPlantilla_incidencia ?>"><?php echo rutasPlantilla_incidencia ?></option>
						</select>
						<input type="checkbox" id="ordenDesc">Desc</input>
						<input type="submit" class="btn btn-info" onClick="cargarRutasPlantilla();" value="Buscar" ></input>
						<!--<button class="btn btn-info">insertar Registro</button>-->
					</td>
				</tr>

				<?php
				if ($_SESSION["permiso_rutas"]==2)
				{
					echo '<tr>				
					<td>
						<hr>
						<table style="text-align: center">
							<tr>
								<!--<td align="center">cliente</td>
								<td align="center">L_R</td>							
								<td align="center">L_H</td>
								<td align="center">M_R</td>							
								<td align="center">M_H</td>
								<td align="center">X_R</td>							
								<td align="center">X_H</td>
								<td align="center">J_R</td>							
								<td align="center">J_H</td>
								<td align="center">V_R</td>							
								<td align="center">V_H</td>
								<td align="center">Contacto</td>
								<td align="center">Incidencia</td>
								<td align="center"></td>-->
								<td align="center">cliente</td>
								<td align="center" colspan="2" style="border: 1px solid #262626">LUNES</td>							
								<td align="center" colspan="2" style="border: 1px solid #262626">MARTES</td>
								<td align="center" colspan="2" style="border: 1px solid #262626">MIERCOLES</td>							
								<td align="center" colspan="2" style="border: 1px solid #262626">JUEVES</td>
								<td align="center" colspan="2" style="border: 1px solid #262626">VIERNES</td>
								
								<td align="center">Contacto</td>
								<td align="center">Incidencia</td>
								<td align="center"></td>
							</tr>
							<tr>
								
								<td><select name="clienteRuta" id="clienteRuta" style="max-width:500px;"></select></td>
								<td><input class="tamanio2" type="text" id="LR" name="LR"></input> </td>							
								<td><input class="tamanio2" type="time" id="LH" name="LH"></input> </td>
								<td><input class="tamanio2" type="text" id="MR" name="MR"></input> </td>							
								<td><input class="tamanio2" type="time" id="MH" name="MH"></input> </td>
								<td><input class="tamanio2" type="text" id="XR" name="XR"></input> </td>							
								<td><input class="tamanio2" type="time" id="XH" name="XH"></input> </td>
								<td><input class="tamanio2" type="text" id="JR" name="JR"></input> </td>							
								<td><input class="tamanio2" type="time" id="JH" name="JH"></input> </td>
								<td><input class="tamanio2" type="text" id="VR" name="VR"></input> </td>							
								<td><input class="tamanio2" type="time" id="VH" name="VH"></input> </td>
								<td><input class="" type="text" id="contacto" name="contacto" value=""></input></td>
								<td><input class="" type="text" id="incidencia" name="incidencia" value=""></input></td>
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="insertarRutaPlantilla()" ></td>
							</tr>
						</table>

					</td>
				</tr>';	
				}
				?>

			</table>
		</td>
	</tr>
			
	<tr>
		<td>
			<hr>
			<table border="1">
				<tbody id="plantillaRutas" name="plantillaRutas"></tbody>
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
<script  src="js/js_rutas.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>


<script language="javascript">	
	<?php
	if ($_SESSION["permiso_rutas"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>
	
	
	cargarRutasPlantilla();
	/*
	idInputListado = "clienteRuta";
	cargarListadoNombreFranqueo();	
	idInputListado="";
	*/
	/*
	idInputListado = "clienteRutaBuscar";
	cargarListadoNombreFranqueo();	
	idInputListado="";
	*/
	
	//cargarSubClientes('A','clienteRuta');
	//cargarSubClientes('A','clienteRutaBuscar');

	cargarSubClientes2(' codigo, subcliente ', 'A','clienteRuta');
	booleano=true;
	cargarSubClientes2(' codigo, subcliente ', 'A','clienteRutaBuscar');


	idInputListado = "rutaRutaBuscar";
	cargarRutasParaVincular();
	
	

	/*idInputlistado = "direccionRuta";
	cargarListadoDireccionesRutasClientes(document.getElementById("clienteRuta").value);
	idInputListado="";*/
	
	

</script>