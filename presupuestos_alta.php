

<?php 

session_start(); 

if ($_SESSION["permiso_presupuestos"] == 2)
{
	$_SESSION['titulo']="PRESUPUESTOS - ALTAS";
}
else if ($_SESSION["permiso_presupuestos"] == 1)
{
	$_SESSION['titulo']="PRESUPUESTOS - CONSULTAS";
}

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

if ($_SESSION["usuario"]<>"")
{
	/*if ($_SESSION["permiso_pdaGestion"]==1)
	{
		$soloLectura="readonly";
	}*/
	
	echo '<table align="center" border="0"  class="sinBorde">';
	
	//$comerciales = cargarComerciales($conexion);
	//$comerciales = cargarPresupuestadores($conexion);
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'id',
		'nombre',
		'telefono',
		'inicial'
	];

	$filtros = [
			//'cliente' => 'EMPRESA SL'
		];

	$order = [
			//['campo' => 'fecha', 'dir' => 'DESC'],
			['campo' => 'nombre', 'dir' => 'ASC']
		];
	
	$comerciales = cargarPresupuestadores($conn,$bbddSql, $campos, $filtros, $order);
	
	sqlsrv_close($conn);
	
	$numero = count($comerciales);
	
	echo ('<tr><td colspan="7" id="numPresupuestoTd" align="center">
	<span id="nombrePresupuesto" style="font-size: 20px;font-weight: bold;"></span>
	<span id="numPresupuesto" style="font-size: 30px;font-weight: bold;"></span>
	<span id="letraPresupuesto" style="font-size: 30px;font-weight: bold;"></span>&nbsp;&nbsp;&nbsp;&nbsp;
	<span id="botonVerDatosGenericosPresupuesto"></span>
	
	
	</td></tr>');
	
	//echo ('<tr><td colspan="7" id="numPresupuesto"></td></tr>');
	echo '<tr><td colspan="7"><hr class=""></td></tr>';
	
	
	
	
	
	echo '<tbody id="datosPresupuestoGenerico">';
	/////////////////////////////////////////////////////////////
	if ($_SESSION["permiso_presupuestoMensual"]==1 || $_SESSION["permiso_presupuestoMensual"]==2)
	{
		echo '<tr>';
		echo '<td align="left">Mensual:</td><td colspan="2"><input type="checkbox" id="presu_mensual" name="presu_mensual" value="" onchange="visualizarCamposPresuMensual()"></input></td>';
		//echo '<tbody id="inputMensuales"  style="visibility: hidden;display: none;">';
		echo '<td id="tdNumPresuMensual" style="visibility: hidden;display: none;" colspan="3">Nº Presu.<input type="text" id="numPresuMensual"></input><img src="imagenes/facepalm1.gif" loop=infinite style="width:25px !important; visibility: hidden;display: none;" id="imagen_numPresuMensual" /></td>';
		echo '</tr>';
	}
	else
	{
		echo '<tr style="visibility:hidden;display:none">';
		echo '<td align="left">Mensual:</td><td><input type="checkbox" id="presu_mensual" name="presu_mensual" value="" onchange="visualizarCamposPresuMensual()"></input></td>';
		//echo '<tbody id="inputMensuales"  style="visibility: hidden;display: none;">';
		//echo '<td></td>';
		echo '<td id="tdNumPresuMensual" style="visibility: hidden;display: none;" colspan="3"><input type="text" id="numPresuMensual"></input><img src="imagenes/facepalm1.gif" loop=infinite style="width:25px !important; visibility: hidden;display: none;" id="imagen_numPresuMensual" /></td>';
		echo '</tr>';
	}
	//echo '</tbody>';
	
	/////////////////////////////////////////////////////////////
	
	
	
	
	
	echo '<tr>';
	
	echo '<td>Comercial:</td><td colspan="3"><select name="comercial" id="comercial"  style="width:100%" >
			
			<option value="">Elegir Comercial</option>';
	
	for ($i = 0; $i < $numero; $i++)
	{
		echo '<option value="'.$comerciales[$i]["id"].'">'.$comerciales[$i]["nombre"].'</option>';
	}
	echo '</select>';
	
	echo '</td>';
	//echo '<td colspan="2" style="text-align:center">Detallada:&nbsp&nbsp<input type="checkbox" id="detallada" name="detallada" value="detallada"></input></td>';
	echo '<td colspan="3" style="text-align:right" >Fecha:<input type="date" id="fechaCreacion" style="" readonly></input></td>';
	//echo '<td ROWSPAN="8" align="center" style="border: 1px solid grey;"><input type="image" id="modificarDetalle" value="" src="imagenes/modificar.png" style="width:15px;" onclick="modificarPresupuesto()" ></td></tr>';
	
	
	//echo '</tr>';
	echo '<tr>';
	echo '<td>Clayma:</td>';
	
	echo '<td  align="left" colspan="2"><input type="checkbox" id="clienteOrigen" name="clienteOrigen" value="" onchange="gestionDeCargarClientes()" style=""></input></td>';	

	echo '<td><label id="codigoCliente" name="codigoCliente"></label><label style="visibility:hidden" id="codigoSaldoCliente" name="codigoSaldoCliente"></label></td>';
	
	echo '<td  align="right">Trabajo Iniciado:  <input type="checkbox" id="trabajoIniciado" name="trabajoIniciado" value="" style=""></input></td>';

	echo '</tr>';
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = ['subcliente','codigo','codigo_saldo'];

	$filtros = [
			'activo' => 1			
		];
	
	$filtrosOperadores = array(
    array(
        'campo1' => 'codigo_saldo',
        'operador' => '=',
        'campo2' => 'codigo'
    	)
	);

	$order = [
			['campo' => 'nombre_empresa', 'dir' => 'ASC'],
			['campo' => 'subcliente', 'dir' => 'ASC']
		];
	
	$clientes = cargarClientes($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order);
	sqlsrv_close($conn);


	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = ['subcliente','codigo','codigo_saldo'];

	$filtros = ['activo' => 1];
	
	$filtrosOperadores = array();

	$order = [
			['campo' => 'nombre_empresa', 'dir' => 'ASC'],
			['campo' => 'subcliente', 'dir' => 'ASC']
		];
	
	$clientesClayma = cargarClientesClayma($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order);
	sqlsrv_close($conn);

	
	$debug = false;

	if ($debug) {
    echo '<pre>';
    print_r($clientes['sql']);
    print_r($clientes['params']);
	print_r($clientesClayma['sql']);
    print_r($clientesClayma['params']);
    echo '</pre>';

	}


	
	$numero2 = count($clientes['datos']);
	$listadoClientes = '';
	
	
	
	
	echo '<tr>';
	
	echo '<td>Clientes:</td><td colspan="5"><input id="clientes" name="clientes" value="" onBlur="cargarDatosPresupuesto()" style="width:100%"></td>';
	//echo '<td><input type="hidden" id="codigoCliente" name="codigoCliente"></td>';

	for ($i = 0; $i < $numero2; $i++)
	{
		$nombreCliente = $clientes['datos'][$i]["subcliente"];	
		$codigo = 	$clientes['datos'][$i]["codigo"];	
		$codigoSaldo = 	$clientes['datos'][$i]["codigo_saldo"];		
		
		$nombreCliente = str_replace('"', "'",$nombreCliente);
		
		//$listadoClientes .= '{"label":"'.$nombreCliente.'", "value":"'.$nombreCliente.'" },';
		$listadoClientes .= '{"label":"'.$nombreCliente.'", "value":"'.$nombreCliente.'", "codigo":"'.$codigo.'", "codigoSaldo":"'.$codigoSaldo.'" },';
		
	}
	$listadoClientes = substr($listadoClientes,0, strlen($listadoClientes)-1);	
	
	
	
	
	$listadoClientesClayma = '';
	$numero2 = count($clientesClayma['datos']);
	
	for ($i = 0; $i < $numero2; $i++)
	{
		$nombreClienteClayma = $clientesClayma['datos'][$i]["subcliente"];		
		$codigoClayma = $clientesClayma['datos'][$i]["codigo"];
		$codigoSaldoClayma = $clientesClayma['datos'][$i]["codigo_saldo"];	

		$nombreClienteClayma = str_replace('"', "'",$nombreClienteClayma);
		
		$listadoClientesClayma .= '{"label":"'.$nombreClienteClayma.'", "value":"'.$nombreClienteClayma.'" , "codigo":"'.$codigoClayma.'", "codigoSaldo":"'.$codigoSaldoClayma.'" },';
		
	}
	
	$listadoClientesClayma = substr($listadoClientesClayma,0, strlen($listadoClientesClayma)-1);	
	
	echo '</tr>';
	
	echo '<tr><td>Obsv. Campaña:</td><td colspan="6"><input type="text" id="campanaObservacionPresu" style="width:100%"></input></td></tr>';
	echo '<tr><td>Campaña:</td><td colspan="6"><input type="text" id="campanaPresu" style="width:100%"></input></td></tr>';
	
	echo '<tr>
	<td>Cantidad:</td><td><input type="number" id="cantidadPresu"></input></td>
	<td style="text-align:right">Pedido Cliente:</td><td colspan="4"><input type="text" id="pedidoClientePresu" style="width:100%"></input></td>
	
	</tr>';
	echo '<tr><td>Dirección:</td><td colspan="6"><input type="text" id="direccionPresu" style="width:100%"></input></td></tr>';
	
	echo '<tr>';
	echo '<td>CP:</td><td><input type="text" id="cpPresu"></input></td>
	<td style="text-align:right">Población:</td><td colspan="4"><input type="text" id="poblacionPresu"  style="width:100%"></input></td>
	
	</tr>';
	
	//$formasDepago = cargarFormasDePago($conexion);
	
	//$numero = count($formasDepago);
	
	
	echo '<tr><td colspan="1">Forma de pago:</td><td colspan="6"> <select name="formaPago" id="formaPago">';
			
	
	
	
	/*for ($i = 0; $i < $numero; $i++)
	{
		if ($formasDepago[$i]["concepto"] == "Al contado")
		{
			echo '<option value="'.$formasDepago[$i]["id"].'" selected>'.$formasDepago[$i]["concepto"].'</option>';
		}
		else
		{
			echo '<option value="'.$formasDepago[$i]["id"].'">'.$formasDepago[$i]["concepto"].'</option>';
		}
		
	}*/
	echo '</select>&nbsp;';
	//echo '</select></td></tr>';
	//echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#NuevaFormaPagoProcesoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;">NFP</button></td></tr>';
	
	echo '</td></tr>';
	
	echo '<tr><td>Persona:</td><td colspan="6"><input type="text" id="personaPresu" style="width:100%"></input></td></tr>';
	echo '<tr><td>Notas:</td><td colspan="6"><input type="text" id="notasPresu" style="width:100%"></input></td></tr>';
	echo '<tr style="visibility: hidden;display: none;"><td>Observaciones2:</td><td colspan="6"><input type="text" id="observaciones2Presu" style="width:100%"></input></td></tr>';
	
	
	echo '<tr><td>Para Imprimir:</td><td colspan="6">Detallada:&nbsp&nbsp<input type="checkbox" id="detallada" name="detallada" value="detallada"></input>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspMostrar Total:&nbsp&nbsp<input type="checkbox" id="totalPresu" name="totalPresu" value="" checked></input></td></tr>';
	
	echo '<tr><td colspan="1"></td><td colspan="6">Mostrar Total Franqueo:&nbsp&nbsp<select name="totalFraqueo" id="totalFraqueo" onchange="desactivarImporteFranqueoPresupuesto()"> </select>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspImporte:&nbsp&nbsp<input type="number" id="TotalFranqueoImporte"></input></td></tr>';
	
	
	
	echo '<tr><td colspan="7" align="center">';

	if ($_SESSION["permiso_presupuestos"] == 2)
	{
		echo '<button type="button" class="btn btn-info" id="botonNuevoPresupuesto" onClick="location.href = \'presupuestos_alta.php\';" style="visibility: hidden;display: none;">NUEVO</button>';
		echo '<button type="button" class="btn btn-info" id="botonModificarPresupuesto" style="visibility: hidden;display: none;" onClick="modificarPresupuesto()">MODIFICAR</button>';
		echo '<button type="button" class="btn btn-info" id="botonCrearPresupuesto" onClick="crearPresupuesto()">CREAR</button>';
		echo '&nbsp';
	}
	
	
	
	echo '<button type="button" class="btn btn-info" onClick="gestionarPresuBuscarModal()">BUSCAR</button>';
	echo '&nbsp';
	
	
	if ($_SESSION["permiso_presupuestos"] == 2)
	{
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#copiaModal" data-whatever="@mdo"  id="botonCopiarPresupuesto" >COPIAR</button>';
		echo '&nbsp';
		echo '<button type="button" class="btn btn-info" id="botonVersionPresupuesto" onClick="crearNuevaVersion()" style="visibility: hidden;display: none;">NUEVA VERSION</button>';
		echo '&nbsp';
	//	echo '<button type="button" class="btn btn-info" id="botonImprimirPresupuesto" onClick="imprimirPresupuesto()">IMPRIMIR</button>';
		
		
		echo '<button id="botonPrevisualizarPresupuesto" type="button" class="btn btn-info"  style="visibility: hidden;display: none;" onClick="previsualizarPresupuesto()">PREVISUALIZAR</button>';
		
		echo '<button id="botonImprimirPresupuesto" type="button" class="btn btn-info" data-toggle="modal" data-target="#nombrePresupuestoModal" data-whatever="@mdo" style="visibility: hidden;display: none;">IMPRIMIR</button>';
		
	}
	
	echo '<button id="botonVerPresupuesto" type="button" class="btn btn-info" data-toggle="modal" data-target="#verPresupuestoModal" data-whatever="@mdo" style="visibility: hidden;display: none;" onClick="cargarListadoParaVerPresupuesto()">VER</button>';
	
	
	
	echo '&nbsp';
	echo '<button id="botonProvisionFondo" type="button" class="btn btn-info" data-toggle="modal" data-target="#provisionFondoModal" data-whatever="@mdo" style="visibility: hidden;display: none;" >P.F.</button>';
	
	if ($_SESSION["permiso_presupuestos"] == 2)
	{
		echo '&nbsp';
		echo '<button id="botonModPedCliente" type="button" class="btn btn-info" data-toggle="modal" onclick="modificarPedidoYNotasClienteDesdePresupuesto()"  style="visibility: hidden;display: none;" >Modificar Pedido Y Notas</button>';
	}
	
	echo '</tr>';
	echo '</tbody>';
	
	
	
	
	echo '<tbody id="detallesPresupuesto" name="detallesPresupuesto" style="visibility: hidden;display: none;"></tbody>';
	
	//echo '<tr><td colspan="7" align="center" style="border=0px"><h3>NUEVO PROCESO</h3></td></tr>';
	echo '<tr><td colspan="7" align="center"><b>AÑADIR NUEVO PROCESO</b><hr class="hrborde2px"></td></tr>';

	
	echo '<tbody id="anadirDetallesPresupuesto" style="visibility: hidden;display: none;">';
	
	$conn1 = conectarSQL($conexion);

	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
		'id',
		'tipoProceso'
	];

	$filtros = [						
		];

	$order = [
			['campo' => 'tipoProceso', 'dir' => 'ASC']			
		];
	
	$tiposProceso = cargarTipoDeProceso($conn,$bbddSql, $campos, $filtros, $order);
	sqlsrv_close($conn);
	
	//$tiposProceso = cargarTipoDeProceso($conexion);
	
	$numero = count($tiposProceso);
	
	
	echo '<tr><td><Tipo de Proceso:</td><td colspan="2"> <select name="tipoProceso" id="tipoProceso" onChange="cargarSubprocesos(\'proceso\');">';
	
	for ($i = 0; $i < $numero; $i++)
	{		
		echo '<option value="'.$tiposProceso[$i]["id"].'">'.$tiposProceso[$i]["tipoProceso"].'</option>';
	}
	
	echo '</select></td>
	<td align="right">Departamento:</td>
	<td colspan="1"> 
	<select name="departamentoProceso" id="departamentoProceso" onChange="cargarSubprocesos(\'proceso\');">';	
	echo '</select></td><td align="right">Exento de IVA:</td><td><input type="checkbox" id="exentoIVA"></input></td></tr>';
	
	echo '</select></td></tr>';
	
	
	echo '<tr id="filaProcesoGuardados">';
	
	
	echo '<td>Proceso:</td><td colspan="6"><select id="proceso"></select>';
	
	
	if ($_SESSION["permiso_nuevoProcesoPresu"]==1 || $_SESSION["permiso_nuevoProcesoPresu"] == 2)
	{
		echo '&nbsp;<button type="button" class="btn btn-info" data-toggle="modal" data-target="#crearProcesoModal" data-whatever="@mdo"  style="height: =5px;line-height: 5px;">nuevo proceso</button>';
	}
	
	
	echo '</td>';
	
	
	echo '</tr>';
	
	

	echo '<tr>';
	echo '<td colspan="1">Material:</td>';
	echo '<td colspan="5">';
	echo 'Tamaño Origen:<select id="tamanioDetalle"></select>';
	echo ' &nbsp; &nbsp;Tamaño Final:<select id="tamanioFinalDetalle"></select>';
	echo ' &nbsp; &nbsp;Tipo:<select id="tipoDetalle"></select>';
	echo ' &nbsp; &nbsp;Acabado:<select id="acabadoDetalle"></select>';
	echo ' &nbsp; &nbsp;Gramaje:<select id="gramajeDetalle"></select>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="1">Impresora:</td>';
	echo '<td><select id="impresoraDetalle"></select></td>';
	echo '<td>Nº Caras:<select id="numeroCarasDetalles"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select>';
	echo '</td>';
	echo '</tr>';



	echo '<tr>';
	echo '<td colspan="1">GF-Material:</td>';
	echo '<td colspan="5">';
	echo 'Tipo de Proceso:<select id="gf_tipoProceso" onchange="cargarGFSubConcepto1(\'gf_material\')"></select>';	
	echo ' &nbsp; &nbsp;Material:<select id="gf_material" onchange="cargarSubConcepto2(\'gf_concepto\');"></select>';
	echo ' &nbsp; &nbsp;Concepto:<select id="gf_concepto"></select>';
	echo ' &nbsp; &nbsp;m²:<input type="number" id="gf_metrosCuadrados" style="width:70px;"></input>';	
	echo '</td>';
	echo '</tr>';


	
	echo '<tr><td>Descripcion:</td><td colspan="3"><input type="text" id="descripcionDetalle" style="width:100%" placeholder="Introducir Descripcion"></input></td>';
	echo '<td align="right">Peso (Gramos):</td><td><input type="number" id="pesoDetalle"></input></td>';
	echo '</tr>';
	echo '<tr><td>Nota Cibeles:</td><td colspan="6"><input type="text" id="notaDetalle" style="width:100%" placeholder="Introducir Notas de Cibeles"></input></td></tr>';
	echo '<tr><td>Nota Adm-Pro:</td><td colspan="6"><input type="text" id="notaDetalleAdmonProd" style="width:100%" placeholder="Introducir Notas de Administracion-Produccion"></input></td></tr>';
	echo '<tr>
	<td>Unidades:</td><td><input type="number" id="unidadesDetalle"></input></td>
	<td align="right">Precio:</td><td><input type="number" id="precioDetalle"></input></td>
	<td align="right">Orden:</td><td  colspan="2"><input type="number" id="ordenDetalle"></input></td>
	</tr>';
	
	echo '<tr><td colspan="7" align="center"><button type="button" class="btn btn-info" onClick="anadirDetallePresupuesto()">AÑADIR DETALLE</button></tr>';
	
	echo '</tbody>';
	
	echo '</table>';
	
	
}
else
{
	header('Location: index.php');	
}

?>






</div> <!-- class="tabla" -->

					

<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Open modal for @mdo</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Open modal for @fat</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Open modal for @getbootstrap</button>-->

<div class="modal fade" id="copiaModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Copiar Presupuesto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>		
        </button>		  
      </div>
   
		<!--<div class="modal-body">
        <form>
          <div class="form-group">
			  
            <label for="recipient-name" class="col-form-label">Numero de presupuesto para duplicar:</label>
            <input type="text" class="form-control" id="numPresuCopiar">
          </div>         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="copiarPresupuesto()">Copiar</button>
      </div>-->
		  <?php if ($_SESSION["permiso_presupuestoMensual"]==1 || $_SESSION["permiso_presupuestoMensual"]==2) { echo '
		<div class="modal-body">
		<form>
			<div class="form-group">
			<label for="recipient-name" class="col-form-label">Mensual <input type="checkbox" id="presu_mensualModal" name="presu_mensual" value="" onchange="visualizarCamposPresuMensualModal()"></input></label>
			</div>
		</form>
		</div>
	  ';
	 }
	  ?>
	  	
		<span id="copiarPresuNoMensualModal">
			<div class="modal-body">
				<form>
					<div class="form-group">			  
						<label for="recipient-name" class="col-form-label">Numero de presupuesto para duplicar:</label>
						<input type="text" class="form-control" id="numPresuCopiar">
					</div>         
				</form>
      		</div>
		 	<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" onClick="copiarPresupuesto()">Copiar</button>
		  	</div>
		</span>
	
	  	<span id="copiarPresuMensualModal"   style="visibility: hidden;display: none;" > 
			<div class="modal-body">
				<form>
				
					<div class="form-group">	
						<label for="recipient-name" class="col-form-label"><h5>Rango de presupuestos que se quiere copiar</h5></label><br>
						<label for="recipient-name" class="col-form-label">Primer Presupuesto:</label>
						<input type="text" class="form-control" id="numPresuInicioMensualModal">
					</div>  
					<div class="form-group">			  
						<label for="recipient-name" class="col-form-label">Ultimo Presupuesto:</label>
						<input type="text" class="form-control" id="numPresuUltimoMensualModal">
					</div>
					
					<div class="form-group">
						<label for="recipient-name" class="col-form-label"><h5>Valores de los nuevos Presupuestos</h5></label><br>
						<label for="recipient-name" class="col-form-label">Año:</label>
						<select id="anioCopiaModal" class="form-control">
							<option value="26" selected>2026</option>
							<option value="25">2025</option>	
							<option value="24">2024</option>
							<option value="23">2023</option>
							<option value="22">2022</option>
						</select>
					</div>
					<div class="form-group">
						<label for="recipient-name" class="col-form-label">Mes:</label>
						<select id="mesCopiaModal" class="form-control">
							<option value="01">Enero</option>
							<option value="02">Febrero</option>
							<option value="03">Marzo</option>
							<option value="04">Abril</option>
							<option value="05">Mayo</option>
							<option value="06">Junio</option>
							<option value="07">Julio</option>
							<option value="08">Agosto</option>
							<option value="09">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</div>
				</form>
      		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<!--<button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirPresupuestoMensuales()">Imprimir</button>-->
				<button type="button" class="btn btn-primary" data-dismiss="" onClick="copiarPresupuestoMensuales()">Copiar</button>
			</div>
		</span>
		
		
		
		
		
    </div>
  </div>
</div>





<div class="modal fade" id="buscarModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Buscar Presupuesto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Numero de presupuesto para buscar:</label>
            <input type="text" class="form-control" id="numPresuBuscar">
          </div>
          <!--<div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>-->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="buscarPresupuesto()">Buscar</button>
      </div>
    </div>
  </div>
</div>

<!-- CREAR NUEVO PROCESO -->
<div class="modal fade" id="crearProcesoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Nuevo Proceso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Proceso:</label>
            <input type="text" class="form-control" id="nuevoProceso">
          </div>
		 <div class="form-group">
            <label for="recipient-name" class="col-form-label">Descripcion:</label>
            <input type="text" class="form-control" id="nuevaDescripcion">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onClick="crearNuevoProceso()">Crear Proceso</button>
      </div>
    </div>
  </div>
</div>



<!--<label for="recipient-name" >Carpeta de presupuesto:</label>
<select name="formaPago" id="rutaCarpetaCliente"></select>-->


<!--ELEGIR CARPETA CORRESPONDIENTE DEL PRESUPUESTO -->
<div class="modal fade" id="nombrePresupuestoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Introducir el nombre del Presupuesto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                     
		<!--<div class="form-group">
            <label for="recipient-name" class="col-form-label">Ruta:</label>
            <input type="text" class="form-control" id="rutaCarpetaCliente2">
          </div>	-->
		<form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre Presupuesto:</label>
            <input type="text" class="form-control" id="nombrePresupuestoCarpeta">
          </div>		         
        </form>

		<form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label" style="color:red !important ;" id="imprimirPresupuestoModalError"></label>
            
          </div>		         
        </form>

      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="cerrarModalImprimirPresupuesto()">Cerrar</button>
        <button type="button" class="btn btn-primary" data-dismiss="" onClick="imprimirPresupuesto()">Aceptar</button>
      </div>
    </div>
  </div>
</div>
	


<!-- PROVISION DE FONDOS -->
<div class="modal fade" id="provisionFondoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">PROVISIONES DE FONDO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           
		<!--<form style="text-align: center">                  -->
			<div class="form-group" align="center">
				<label for="recipient-name" class="col-form-label"><span id="historicoPF" style="text-align: center"></span></label>            
			</div>		         
       <!-- </form>  
		  
		
		<form>   -->               
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Importe:</label>
            <input type="number" class="form-control" id="importeProvisionFondo">
          </div>		         
       <!-- </form>
		 <form>  -->                
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Tipo:</label>
           	<select id="tipoProvisionFondo" class="form-control"></select>
          </div>		         
       <!-- </form>-->
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		
		<?php 
		if ($_SESSION["permiso_presupuestos"]==2)
		{		
			echo '<button type="button" class="btn btn-primary"  onClick="insertarProvisionDeFondos_presupuesto()" id="botonAnadirProvision">Añadir</button>';		
		}
		?>
        
		<!--<button type="button" class="btn btn-primary"  onClick="imprimirProvisionDeFondos_presupuesto()" id="botonImprimirProvision">Imprimir PF</button>
		<button type="button" class="btn btn-primary"  onClick="imprimirPagoACuenta_presupuesto()" id="botonImprimirProvisionPagoCuenta">Imprimir PAGO A CUENTA</button>-->
      </div>
    </div>
  </div>
</div>


<!-- IMPRIMIR PROVISION DE FONDOS -->
<div class="modal fade" id="imprimirProvisionFondoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">IMPRIMIR PROVISION DE FONDO <br><span id="numPresupuesto1"></span>
			<!--<span id="numPresupuesto2" style="visibility: hidden; display: none"></span>-->
			<span id="numPresupuesto2"></span>
		  </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  
		 <!--  <form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Cliente: <span id="clienteProvisionFondoModal"></span></label>
           	 
          </div>
        </form>-->
		<form>                  
		<div class="form-group">
            <label for="recipient-name" class="col-form-label">Importe: <span id="importeProvisionFondoModal"></span></label>            
          </div>		         
        </form>
		
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
		<button type="button" class="btn btn-primary"  onClick="imprimirProvisionDeFondos_presupuesto()" id="botonImprimirProvision">Imprimir PF</button>
		<button type="button" class="btn btn-primary"  onClick="imprimirPagoACuenta_presupuesto()" id="botonImprimirProvisionPagoCuenta">Imprimir PAGO A CUENTA</button>
      </div>
    </div>
  </div>
</div>





<!-- VER PRESUPUESTO YA GENERADO -->
<div class="modal fade" id="verPresupuestoModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">LISTADO DE ARCHIVOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<form>                  
		<div class="form-group">
			<span id="listadoArchivosPresupuestoVer">
            	<!--<label for="recipient-name" class="col-form-label">Importe:</label>-->
			
			</span>
          </div>		         
        </form>
		 
      </div>
		
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
      </div>
    </div>
  </div>
</div>
	
<form id="formImprimir" name="formImprimir" method="post"  target="_blank" action="imprimirPresupuesto.php">
	<input type="hidden" id="imprimirNumPresupuesto" name="imprimirNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>
	<input type="hidden" id="nombreCarpetaForm" name="nombreCarpetaForm" value=""></input>
	<!--<input type="submit" name="submit" value="submit">-->
</form>





<form id="formPrevisualizar" name="formPrevisualizar" method="post"  target="_blank" action="previsualizarPresupuesto.php">
	<input type="hidden" id="previsualizarNumPresupuesto" name="previsualizarNumPresupuesto" value="">
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="previsualizarPresu">
</form>

<form id="formImprimirPresMensuales" name="formImprimirPresMensuales" method="post"  target="_blank" action="imprimirPresupuesto.php">
	<input type="hidden" id="imprimirNumPresupuesto" name="imprimirNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>
	<input type="hidden" id="nombreCarpetaForm" name="nombreCarpetaForm" value=""></input>
	<input type="hidden" id="mes" name="mes" value=""></input>
	<input type="hidden" id="anio" name="anio" value=""></input>


	
</form>








<!--<form id="formImprimirProvisionDeFondo" name="formImprimir" method="post"  target="_blank" action="imprimirProvisionDeFondo.php">
	<input type="hidden" id="imprimirProvisionFondoNumPresupuesto" name="imprimirNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>

<form id="formImprimirPagoACuenta" name="formImprimir" method="post"  target="_blank" action="imprimirPagoACuenta.php">
	<input type="hidden" id="imprimirPagoACuentaNumPresupuesto" name="imprimirPagoACuentaNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>-->




<form id="formImprimirProvisionDeFondo" name="formImprimir" method="post"  target="_blank" action="imprimirProvisionDeFondo.php">
	<input type="hidden" id="imprimirProvisionFondoNumPresupuesto" name="imprimirProvisionFondoNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirProvisionFondoContador" name="imprimirProvisionFondoContador" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>

<form id="formImprimirPagoACuenta" name="formImprimir" method="post"  target="_blank" action="imprimirPagoACuenta.php">
	<input type="hidden" id="imprimirPagoACuentaNumPresupuesto" name="imprimirPagoACuentaNumPresupuesto" value=""></input>
	<input type="hidden" id="imprimirPagoACuentaNumPresupuestoContador" name="imprimirPagoACuentaNumPresupuestoContador" value=""></input>
	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>


<form id="formImprimirProvisionDeFondoClayma" name="formImprimir" method="post"  target="_blank" action="imprimirProvisionDeFondoClayma.php">
	<input type="hidden" id="imprimirProvisionFondoNumPresupuestoClayma" name="imprimirProvisionFondoNumPresupuestoClayma" value=""></input>
	<input type="hidden" id="imprimirProvisionFondoContadorClayma" name="imprimirProvisionFondoContadorClayma" value=""></input>
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>

<form id="formImprimirPagoACuentaClayma" name="formImprimir" method="post"  target="_blank" action="imprimirPagoACuentaClayma.php">
	<input type="hidden" id="imprimirPagoACuentaNumPresupuestoClayma" name="imprimirPagoACuentaNumPresupuestoClayma" value=""></input>
	<input type="hidden" id="imprimirPagoACuentaNumPresupuestoContadorClayma" name="imprimirPagoACuentaNumPresupuestoContadorClayma" value=""></input>
	
	<input type="hidden" id="imprimirAccion" name="imprimirAccion" value="imprimirPresu"></input>	
</form>



<!--<input id="prueba" type="file"  onchange="browseResult(event)" webkitdirectory directory/>
<input id="fileselector" type="file" onchange="browseResult(event)" webkitdirectory directory multiple="false" style="display:none" />
<button onclick="getElementById('prueba').click()">browse</button>
<input type="file" id="FileUpload" onchange="selectFolder(event)" webkitdirectory mozdirectory msdirectory odirectory directory multiple />-->

<div class="button-up" id="button-up">
	<i class="fas fa-chevron-up"></i>
</div>



<?php

/*if ($_SESSION["permiso_fechaCompromisoPresu"]==1 || $_SESSION["permiso_fechaCompromisoPresu"] == 2)
{
	echo '<input type="hidden" id="cambiarFechaCompromiso" value="1" required></input>';
}
else
{
	echo '<input type="hidden" id="cambiarFechaCompromiso" value="0" required></input>';
}*/



echo '<input type="hidden" id="permiso_otBajadaAutomatico" value="'.$_SESSION["permiso_otBajadaAutomatico"].'"></input>';

echo ("</div>");
echo ("</body>");
echo ("</html>");

?>


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_presupuestosAlta.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<script language="javascript">


	<?php 
	if ($_SESSION["permiso_presupuestos"]==1)
	{		
		echo 'permisosSoloLectura = true;';		
	}
	?>
	
	var clientesAutocompletar="";
	function gestionDeCargarClientes()
	{
		var auxMTF = document.getElementById("totalFraqueo").value;
		var auxMTFImporte = document.getElementById("TotalFranqueoImporte").value;
		
		cargarTotalFranqueoIVA();
		
		if ( document.getElementById("clienteOrigen").checked)
		{
			

			clientesAutocompletar = [<?php echo $listadoClientesClayma; ?>];
			$('#clientes').autocomplete({ 
				 source: clientesAutocompletar,
				 select: function (event, ui) {
					// Guardar el código en el input hidden
					$('#codigoCliente').text(ui.item.codigo);
					$('#codigoSaldoCliente').text(ui.item.codigoSaldo);
				},
				 change: function (event, ui) { 
					if (!ui.item) {
					$('#codigoCliente').text('0');
					$('#codigoSaldoCliente').text('0');
				}

				  } });
			
			if (auxMTF=="3")
			{
				document.getElementById("TotalFranqueoImporte").value="";
				document.getElementById("totalFraqueo").value="1";
			}
			else
			{
				document.getElementById("totalFraqueo").value = auxMTF;
				document.getElementById("TotalFranqueoImporte").value = auxMTFImporte;
			}
		}
		else
		{
			document.getElementById("totalFraqueo").value = auxMTF;
			document.getElementById("TotalFranqueoImporte").value = auxMTFImporte;
			
			clientesAutocompletar = [<?php echo $listadoClientes; ?>];
			$('#clientes').autocomplete({ 
				 source: clientesAutocompletar,
				 select: function (event, ui) {
					// Guardar el código en el input hidden
					$('#codigoCliente').text(ui.item.codigo);
					$('#codigoSaldoCliente').text(ui.item.codigoSaldo);
				},
				 change: function (event, ui) { 
					if (!ui.item) {
					$('#codigoCliente').text('0');
					$('#codigoSaldoCliente').text('0');
				}

				  } });
		}
		
		
		document.getElementById("clientes").value = "";
		document.getElementById("codigoCliente").innerHTML = "0";
		document.getElementById("codigoSaldoCliente").innerHTML = "0";

		
		
		
		
	}

	function ponerCodigoClientePorNombre(nombreCliente) {
		$('#codigoCliente').text('0');
		$('#codigoSaldoCliente').text('0');

		for (var i = 0; i < clientesAutocompletar.length; i++) {
			if (clientesAutocompletar[i].value === nombreCliente) {
				$('#codigoCliente').text(clientesAutocompletar[i].codigo || '');
				$('#codigoSaldoCliente').text(clientesAutocompletar[i].codigo || '');
				break;
			}
		}
	}
	
	////////////////////////
	
	/*var clientesAutocompletar = [<?php //echo $listadoClientes; ?>];
	$('#clientes').autocomplete({ 
			 source: clientesAutocompletar,
			 change: function (event, ui) {  } });	*/
	
	cargarFormasDePago();	
	cargarDepartamentosProceso();
	cargarSubprocesos("proceso");
	cargarTotalFranqueoIVA();	
	cargarTipoProvisionFondo('tipoProvisionFondo','limitacionA');
	gestionDeCargarClientes();
	cargarTamanios("tamanioDetalle");
	cargarTamanios("tamanioFinalDetalle");
	cargarTipos("tipoDetalle");
	cargarAcabado("acabadoDetalle");
	cargarGramaje("gramajeDetalle");
	cargarTipoImpresoras("impresoraDetalle");
	cargarGfTipoProceso("gf_tipoProceso");
	//cargarGFSubConcepto1("gf_material");
	//cargarSubConcepto2("gf_concepto");

	
	
	
	
	

	//cargarNombresCarpetasPresupuestos();
	/*function browseResult(e)
	{
	  var fileselector = document.getElementById('prueba');
		
	  alert(document.getElementById("prueba").files[0].webkitRelativePath.split("/")[0]);
	}*/
	
	
	let params = new URLSearchParams(window.location.search);
	var parametro=params.get("presupuesto");
	
	if (parametro!=null)
	{	
		//alert(params.get("presupuesto"));
		document.getElementById("numPresuBuscar").value = params.get("presupuesto");
		buscarPresupuesto();
	}
	//gestionDeCargarClientes();

/*
	function botonNuevaVersiongetion() {
		<?php 
		/*
			$usuario = $_SESSION["idEmpleado"];
			if ($usuario==1 || $usuario==2 || $usuario==29 || $usuario==23 || $usuario==24)
			{
				echo 'document.getElementById("botonVersionPresupuesto").style.visibility = "visible";';
				echo 'document.getElementById("botonVersionPresupuesto").style.display = "table-cell";';


				
				echo 'document.getElementById("botonModificarPresupuesto").style.visibility = "visible";';
				echo 'document.getElementById("botonModificarPresupuesto").style.display = "table-cell";';

				
				echo 'document.getElementById("botonImprimirPresupuesto").style.visibility = "visible";';
				echo 'document.getElementById("botonImprimirPresupuesto").style.display = "table-cell";';
				
				echo 'document.getElementById("anadirDetallesPresupuesto").style.visibility = "visible";';
				echo 'document.getElementById("anadirDetallesPresupuesto").style.display = "table-row-group";';

				echo 'document.getElementById("botonPrevisualizarPresupuesto").style.visibility = "visible";';
				echo 'document.getElementById("botonPrevisualizarPresupuesto").style.display = "table-cell";';
				


				//echo 'document.getElementById("anadirDetallesPresupuesto").colSpan = "6";';
				
			}*/
		?>
		
	}
*/
	
	
	/*$(document).on('hidden.bs.modal', function (event) {
  if ($('.modal:visible').length) {
    $('body').addClass('modal-open');
  }
});*/

if (typeof iniciarComprobacionSesion === "function") {
    iniciarComprobacionSesion();
}
	
document.getElementById("button-up").addEventListener("click", scrollUp);
</script>