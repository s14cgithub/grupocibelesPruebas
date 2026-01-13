

<?php 

session_start(); 
$_SESSION['titulo']="PDA";
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

							Ot: <input class="tamanio7" type="text" id="buscarOtValor" name="buscarOtValor"></input>
							Empleado: <select name="buscarEmpleado" id="buscarEmpleado"></select>

							Fecha Inicio: <input class="tamanioFecha" type="date" id="buscarFechaInicio" name="buscarFechaInicio" value=""></input>
							Fecha Fin: <input class="tamanioFecha" type="date" id="buscarFechaFin" name="buscarFechaFin" value=""></input>
						</span>
					</td>
				</tr>



				<tr>

				
					<td align="center">

					Nº Meses: <input type="number" id="buscarNumMeses" value="2"></input>


					&nbsp;&nbsp;&nbsp;más de 10 horas: <input type="checkbox" id="masde10horas" value="2"></input>

					<span style="margin-left: 15px"></span>


						<label for="columnas">Ordenar Por:</label>

						<select name="orden" id="orden">
							<option value="<?php echo registroHora_columnaId ?>"><?php echo registroHora_columnaId ?></option>
							<option value="<?php echo registroHora_columnaNombreEmpleadod ?>">Empleado</option>
							<option value="<?php echo registroHora_columnaCodigoBarras ?>"><?php echo registroHora_columnaCodigoBarras ?></option>
							<option value="<?php echo registroHora_columnaEstado ?>"><?php echo registroHora_columnaEstado ?></option>
							<option value="<?php echo registroHora_columnaHoraInicio ?>">fecha y hora - inicio</option>
							<option value="<?php echo registroHora_columnaHoraFin ?>">fecha y hora - fin</option>
							<option value="<?php echo registroHora_columnaCantidad ?>"><?php echo registroHora_columnaCantidad ?></option>
						</select>
						<input type="checkbox" id="ordenDesc" checked>Desc</input>
						<input type="submit" class="btn btn-info" onClick="cargarAutomaticosPda();" value="Buscar" ></input>
						<!--<button class="btn btn-info">insertar Registro</button>-->
					</td>
				</tr>
				<?php 
					if ($_SESSION["permiso_pdaGestion"]==2)
					{
						echo '<tr>
					<td>
						<hr>
						<table style="text-align: center">
							<tr>
								<td align="center">Empleado</td>
								<td align="center">Proceso</td>							
								<td align="center">Fecha Inicio</td>
								<td align="center">Fecha Fin</td>
								<td align="center">Cantidad</td>
								<td align="center">Observaciones</td>
								<td align="center"></td>
							</tr>
							<tr>
								<td><select name="RN_empleado" id="RN_empleado"></select></td>
								<td><input class="tamanio14" type="text" id="RN_proceso" name="RN_proceso"></input> </td>							
								<td><input class="" type="datetime-local" id="RN_fechaInicio" name="RN_fechaInicio" value=""></input></td>
								<td><input class="" type="datetime-local" id="RN_fechaFin" name="RN_fechaFin" value=""></input></td>
								<td><input class="tamanio7" type="number" id="RN_cantidad" name="RN_cantidad" value=""></input></td>
								<td><input class="" type="text" id="RN_observaciones" name="RN_observaciones" value=""></input></td>
								<td><input type="image" value="" src="imagenes/crear.png" style="width:20px; cursor:pointer;" onclick="comprobarInsertarRegistroHoraManual()" ></td>
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
			<table class="tabala">
				<tbody id="registrosHoras" name="registrosHoras"></tbody>
			</table>
		</td>
	</tr>	
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
<script  src="js/js_pdaGestion.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>




<script language="javascript">

	<?php 
		if ($_SESSION["permiso_pdaGestion"]==1)
		{		
			echo 'permisosSoloLectura = true;';		
		}
	?>




	cargarEmpleadosPDA("buscarEmpleado");
	cargarEmpleadosPDA("RN_empleado");
	//cargarRegistrosHoras();

	//buscarFechaInicio
	var fechaActual = new Date();
	fechaActual.setDate(fechaActual.getDate() - 7);
	var mes=0;
	mes = fechaActual.getMonth()+1;
	var mesText = "";
	mesText = mes.toString();
	if (mesText.length==1)
	{
		mesText = "0" + mesText;
	}
	document.getElementById("buscarFechaInicio").value = fechaActual.getFullYear()+"-"+mesText + "-" + fechaActual.getDate();
	

	cargarAutomaticosPda();
	document.getElementById("button-up").addEventListener("click", scrollUp);

</script>