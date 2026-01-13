<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_empleados.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<?php 

session_start(); 
$_SESSION['titulo']="EMPLEADOS";
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
						
							Empleado: <select name="listadoEmpleado1" id="listadoEmpleado1"></select>							
							precio Hora: <input class="tamanioFecha" type="number" id="buscarPrecioHora" name="buscarPrecioHora" value=""></input>
							Horas Laborales: <input style="width: auto !important" type="time" id="buscarHorasLaborales" name="buscarHorasLaborales" value=""></input>
						</span>
					</td>
				</tr>



				<tr>
					<td align="center">
						<label for="columnas">Ordenar Por:</label>

						<select name="orden" id="orden">
							<option value="<?php echo empleado_columnaNombre ?>"><?php echo empleado_columnaNombre ?></option>
							<option value="<?php echo empleado_columnaPrecioHora ?>"><?php echo empleado_columnaPrecioHora ?></option>
							<option value="<?php echo empleado_columnaHoraLaboral ?>"><?php echo empleado_columnaHoraLaboral ?></option>
							
						</select>
						<input type="checkbox" id="ordenDesc">Desc</input>
						<input type="submit" class="btn btn-info" onClick="cargarEmpleados();" value="Buscar" ></input>
						<!--<button class="btn btn-info">insertar Registro</button>-->
					</td>
				</tr>

				<tr>
					<td>
						<hr>
						<table style="text-align: center">
							<tr>
								<td>&nbsp;</td>
								<td align="center">Nombre</td>
								<td align="center">Apellidos</td>							
								<td align="center">Precio Hora</td>
								<td align="center">Horas Laborales</td>								
								<td align="center"></td>
							</tr>
							<tr>
								<td></td>
								
								<td><input class="" type="text" id="nombreNuevo" name="nombreNuevo"></input> </td>							
								<td><input class="" type="text" id="apellidosNuevo" name="apellidosNuevo" value=""></input></td>
								<td><input class="" type="number" id="precioHoraNuevo" name="precioHoraNuevo" value=""></input></td>
								<td><input class="" type="time" id="horasLaboralNuevo" name="horasLaboralNuevo" value=""></input></td>
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="insertarRegistroEmpleado()" ></td>
							</tr>
						</table>

					</td>
				</tr>

			</table>
		</td>
	</tr>
			
	<tr>
		<td>
			<hr>
			<table border="1">
				<tbody id="empleados" name="empleados"></tbody>
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

<script language="javascript">
	idInputListado = "listadoEmpleado1";
	cargarListadoEmpleado();
	cargarEmpleados();

</script>