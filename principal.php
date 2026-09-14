
<?php  //NUEVA VERSION

session_start(); 
$_SESSION['titulo']="Menú Principal";

//$_SESSION['usuario']="";
$ruta="/";

require($ruta."comprobarSesion.php");

require($ruta."Archivos Comunes/cabecera.php");







?>

<!--<button type="button" class="btn btn-primary">Primary</button>
<button type="button" class="btn btn-secondary">Secondary</button>
<button type="button" class="btn btn-success">Success</button>
<button type="button" class="btn btn-danger">Danger</button>
<button type="button" class="btn btn-warning">Warning</button>
<button type="button" class="btn btn-info">Info</button>
<button type="button" class="btn btn-light">Light</button>
<button type="button" class="btn btn-dark">Dark</button>-->


<?php

//ALMACEN
	if ($_SESSION["permiso_almacen"]==1 || $_SESSION["permiso_almacen"] == 2)
	{
		//echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M160-120q-50 0-85-35t-35-85q0-26 10-49.5T80-330v-190h80v-240h320l188 443q6 14 9 28t3 29q0 58-41 99t-99 41q-41 0-75.5-21.5T413-200H273q-13 36-44 58t-69 22Zm560-40v-640h80v560h120v80H720Zm-560-40q17 0 28.5-11.5T200-240q0-17-11.5-28.5T160-280q-17 0-28.5 11.5T120-240q0 17 11.5 28.5T160-200Zm380 0q25 0 42.5-17.5T600-260q0-25-17.5-42.5T540-320q-25 0-42.5 17.5T480-260q0 25 17.5 42.5T540-200Zm-267-80h128q2-11 4.5-20.5T413-320h-90L206-440h-46v80q38 0 69 22t44 58Zm84-120h189L427-680H240v160l117 120Zm-34 80-18.5-19q-18.5-19-40-41.5t-40-41L206-440h-46 46l117 120h90-90Z"/></svg>ALMACEN</h4>');
		
		
		if ($_SESSION["permiso_almacen"]==1 || $_SESSION["permiso_almacen_Listado"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacen.php\'">Listado</button>');	
		}		
		
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacenProveedor.php\'">Proveedor</button>');
		
		if ($_SESSION["permiso_almacen_Nuevo"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacenProducto.php\'">Productos</button>');
		}
		
		if ($_SESSION["permiso_almacen_Albaran"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'almacenHueco.php\'">Huecos</button>');
		}
		if ($_SESSION["permiso_preEntradaGestion"]==2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'almacenPreEntrada.php\'">preEntrada - gestion</button>';
		}
		
		echo '<button type="button" class="btn btn-info" onclick="cargarImagenes()" data-toggle="modal" data-target="#exampleModal">Ver - PreEntrada</button>';
			
		
	}

	//PDA_GESTION
	if ($_SESSION["permiso_pdaGestion"]==1 || $_SESSION["permiso_pdaGestion"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>PDA - Horas Realizadas</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'pda_gestion.php\'">REGISTROS PDA</button>');	
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales.php\'">REGISTROS INFORMATICA</button>');
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManualesGF.php\'">REGISTROS G.F.</button>');	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales_Comprobacion.php\'">COMPROBAR REG. MANUALES</button>');	
	}
	else if($_SESSION["permiso_registrosHorasManuales"]==1 || $_SESSION["permiso_registrosHorasManuales"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>PDA - Horas Realizadas</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManualesGF.php\'">REGISTROS G.F.</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales.php\'">REGISTROS INFORMATICA</button>');	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManualesAlmacen.php\'">REGISTROS ALMACEN</button>');	
		
	}
	else if ( $_SESSION["permiso_registrosHorasManuales_Comprobacion"]==2)
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>PDA - Horas Realizadas</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'registrosHorasManuales_Comprobacion.php\'">COMPROBAR INFORMATICA</button>');
	}

	//INFORMES PRODUCCION
	if ($_SESSION["permiso_InformesProduccion"]==1 || $_SESSION["permiso_InformesProduccion"] == 2)
	{
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'informesProduccion.php\'">Informes de Producción</button>');		
	}

	
	//PRESUPUESTOS
	if ($_SESSION["permiso_presupuestos"]==1 || $_SESSION["permiso_presupuestos"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>PRESUPUESTOS</h4>');	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'presupuestos.php\'">Presupuestos</button>');		
	}

	//MATERIALES
	if ($_SESSION["permiso_materialesPapel"]==1 || $_SESSION["permiso_materialesPapel"] == 2)
	{	
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'materiales.php\'">Materiales</button>');		
	}

	
	//OT - PRODUCCION
	if ($_SESSION["permiso_ot"]==1 || $_SESSION["permiso_ot"] == 2)
	{
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'ot.php\'">OT</button>');		
	}

	

	//COMPRAS A TERCEROS
	if ($_SESSION["permiso_comprasAterceros"]==1 || $_SESSION["permiso_comprasAterceros"] == 2 || $_SESSION["permiso_proveedores"]==1 || $_SESSION["permiso_proveedores"] == 2)	
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z"/></svg>Compras a Terceros</h4>');
		if($_SESSION["permiso_proveedores"]==1 || $_SESSION["permiso_proveedores"] == 2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'proveedores_listado.php\'">Proveedores</button>');
		}				
		if ($_SESSION["permiso_comprasAterceros"]==1 || $_SESSION["permiso_comprasAterceros"] == 2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'comprasTercerosListado.php\'">Compras a Terceros</button>');
			//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'comprasTercerosListadoAntiguo.php\'">Antiguos</button>');
		}
		
		if ($_SESSION["permiso_administracion_contabilidad"] == 1 || $_SESSION["permiso_administracion_contabilidad"] == 2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'comprasTercerosContabilidad.php\'">GESTION</button>';
		}
	}

	//OT - ADMINISTRACION
	if ($_SESSION["permiso_administracion"]==1 || $_SESSION["permiso_administracion"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M200-120q-33 0-56.5-23.5T120-200v-640h80v640h640v80H200Zm40-120v-360h160v360H240Zm200 0v-560h160v560H440Zm200 0v-200h160v200H640Z"/></svg>ADMINISTRACION</h4>');
		/*echo ('<button type="button" class="btn btn-info" onClick="location.href = \'administracion.php\'">ADMINISTRACION</button>');*/	
		
		if ($_SESSION["permiso_administracion_contabilidad"] == 1 || $_SESSION["permiso_administracion_contabilidad"] == 2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'admContabilidad.php\'">CONTABILIDAD</button>';
		}

		if ($_SESSION["permiso_administracion_facturacion"] == 1 || $_SESSION["permiso_administracion_facturacion"] == 2)
		{
			echo '<button type="button" class="btn btn-info" onClick="location.href = \'admFacturacion.php\'">FACTURACION</button>';
		}
		
	}

	

	//FRANQUEO-GRABAR
	if ($_SESSION["permiso_grabarFranqueo"]==1 || $_SESSION["permiso_grabarFranqueo"] == 2)
	{
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M560-520h280v-200H560v200Zm140-50-100-70v-40l100 70 100-70v40l-100 70ZM80-120q-33 0-56.5-23.5T0-200v-560q0-33 23.5-56.5T80-840h800q33 0 56.5 23.5T960-760v560q0 33-23.5 56.5T880-120H80Zm556-80h244v-560H80v560h4q42-75 116-117.5T360-360q86 0 160 42.5T636-200ZM360-400q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM182-200h356q-34-38-80.5-59T360-280q-51 0-97 21t-81 59Zm178-280q-17 0-28.5-11.5T320-520q0-17 11.5-28.5T360-560q17 0 28.5 11.5T400-520q0 17-11.5 28.5T360-480Zm120 0Z"/></svg>FRANQUEO</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoGrabacion.php\'">GRABAR FRANQUEO</button>');
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'importarCertificadosNotificaciones.php\'">IMPORTAR CERTIFICADOS O NOTIFICACIONES</button>');
		
		if ($_SESSION["permiso_grabarFranqueo"]==2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'importarExcelIndra.php\'">IMPORTAR EXCEL INDRA</button>');
		}
		
		
	}
	
	if ($_SESSION["permiso_franqueoF12"]==1||$_SESSION["permiso_franqueoF12"] == 2)
	{
		
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'f12.php\'">F12</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoRegistros.php\'">REGISTROS</button>');
		
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#registroOtSidi" data-whatever="@mdo">OT\'s SIDI</button>';
		echo '<button type="button" class="btn btn-info" data-toggle="modal" data-target="#importarOTSidiGrabarOtSidi" data-whatever="@mdo">Importar OT SIDI</button>';
		//echo '<button type="button" class="btn btn-info" onclick="actualizarOtSidiACibeles()">Actualizar OT</button>';
		
		if ($_SESSION["permiso_franqueoF12"] == 2)
		{
			echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoPagadoGrabacion.php\'">GRABAR FRANQUEO PAGADO</button>');
		}
		
		
		//echo ('<button type="button" class="btn btn-info" onClick="cargarListadoClientesInformeFranqueo()">FRANQUEO por Cliente</button>');
	}
	/*if ($_SESSION["permiso_actualizarDatos"]==1||$_SESSION["permiso_actualizarDatos"]==2)
	{
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'actualizarDatos.php\'">ActualizarDatos</button>');
	}*/

	if ($_SESSION["permiso_clientesAutorizados_franqueo"]==1||$_SESSION["permiso_clientesAutorizados_franqueo"] == 2)
	{
		
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'franqueoClientesAutorizados.php\'">Clientes Autorizados</button>');
		

	}

	//RUTAS
	if ($_SESSION["permiso_rutas"]==1||$_SESSION["permiso_rutas"]==2)
	{	
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M280-160q-50 0-85-35t-35-85H60l18-80h113q17-19 40-29.5t49-10.5q26 0 49 10.5t40 29.5h167l84-360H182l4-17q6-28 27.5-45.5T264-800h456l-37 160h117l120 160-40 200h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85H400q0 50-35 85t-85 35Zm357-280h193l4-21-74-99h-95l-28 120Zm-19-273 2-7-84 360 2-7 34-146 46-200ZM20-427l20-80h220l-20 80H20Zm80-146 20-80h260l-20 80H100Zm180 333q17 0 28.5-11.5T320-280q0-17-11.5-28.5T280-320q-17 0-28.5 11.5T240-280q0 17 11.5 28.5T280-240Zm400 0q17 0 28.5-11.5T720-280q0-17-11.5-28.5T680-320q-17 0-28.5 11.5T640-280q0 17 11.5 28.5T680-240Z"/></svg>RUTAS</h4>');		
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutas.php\'">RUTAS</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasAdicionales.php\'">RUTAS Adicionales</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasVinculaciones.php\'">RUTAS Vinculaciones</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasHistorico.php\'">RUTAS Historico</button>');
	}

	if ($_SESSION["permiso_empleados"]==1||$_SESSION["permiso_empleados"]==2)
	{	
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M680-360q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM480-160v-56q0-24 12.5-44.5T528-290q36-15 74.5-22.5T680-320q39 0 77.5 7.5T832-290q23 9 35.5 29.5T880-216v56H480Zm-80-320q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-160ZM80-160v-112q0-34 17-62.5t47-43.5q60-30 124.5-46T400-440q35 0 70 6t70 14l-34 34-34 34q-18-5-36-6.5t-36-1.5q-58 0-113.5 14T180-306q-10 5-15 14t-5 20v32h240v80H80Zm320-80Zm0-320q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Z"/></svg>EMPLEADOS</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'empleados.php\'">EMPLEADOS</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'loginGestion.php\'">LOGIN</button>');
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'rutasVinculaciones.php\'">PERMISOS</button>');
		
	}


	if ($_SESSION["permiso_tarifas"]==1||$_SESSION["permiso_tarifas"]==2)
	{	
		echo ('<br><br>');	
		echo ('<h4 class="menuSeccion"><svg class="menuSeccionIcono" xmlns="http://www.w3.org/2000/svg" height="1em" width="1em" viewBox="0 -960 960 960" fill="currentColor"><path d="M446-80q-15 0-30-6t-27-18L103-390q-12-12-17.5-26.5T80-446q0-15 5.5-30t17.5-27l352-353q11-11 26-17.5t31-6.5h287q33 0 56.5 23.5T879-800v287q0 16-6 30.5T856-457L503-104q-12 12-27 18t-30 6Zm0-80 353-354v-286H513L160-446l286 286Zm253-480q25 0 42.5-17.5T759-700q0-25-17.5-42.5T699-760q-25 0-42.5 17.5T639-700q0 25 17.5 42.5T699-640ZM480-480Z"/></svg>TARIFAS</h4>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'tarifasPapel.php\'">Papel</button>');
		echo ('<button type="button" class="btn btn-info" onClick="location.href = \'tarifasTipoImpresora.php\'">Tipo Impresora</button>');	
		//echo ('<button type="button" class="btn btn-info" onClick="location.href = \'tarifasGranFormato.php\'">Gran Formato</button>');	
		
	}


?>

<!--VISUALIZAR IMAGENES ALMACEN-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">       
		  		<h5 class="modal-title" id="ModalLabel">ALMACEN - PRE-ENTRADA</h5>
      		</div>
      		<div class="modal-body">
		 
				<div class="row">

  				<!-- Grid column -->
			 		<!--<div class="col-md-12 d-flex justify-content-center mb-5">
						
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="all">Todos</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="arranque">Arraque</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="calidad">Calidad</button>
						<button type="button" class="btn btn-outline-black waves-effect filter" data-rel="incidencia">Incidencia</button>
			  		</div>-->
 				 <!-- Grid column -->

				</div>
				<div class="gallery" id="gallery"></div>

		  
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
       
      		</div>
    	</div>
  </div>
</div>


<!--  OT'S DE SIDI  -->

<a href="archivosDescargas/otSidi.txt" download="otSidi.txt" id="otSidiTXT" style="visibility: hidden">button</a>

<div class="modal fade" id="registroOtSidi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">       
		  		<h5 class="modal-title" id="ModalLabel">REGISTROS DE OT PARA SIDI</h5>
      		</div>
      		<div class="modal-body">
        		<form>					
					<div class="form-group">
            			<label for="recipient-name" class="col-form-label">OT's:</label>
						<textarea id="otSidiModal" class="form-control" rows="15"></textarea>
          			</div>	
					
		
          			
        		</form>
      		</div>
			  <div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="generarTxtSIDI()">Generar TXT</button>
      		</div>
    	</div>
  </div>
</div>


<!--  GRABAR OT SIDI  -->

<div class="modal fade" id="importarOTSidiGrabarOtSidi" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="ModalLabel">IMPORTACION OT's SIDI</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form>					
					<!--<div class="form-group">
            			<label for="recipient-name" class="col-form-label">Cliente:</label>
						<select id="clientesImportacionMensualesModal" class="form-control"></select>
          			</div>-->
					<div class="" style="color:black;">					
						<input type="file" class="" id="elArchivoExcelOtSidi" name="elArchivoExcelOtSidi" accept=".xlsx"></input>
          			</div>	
						
        		</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        		<button type="button" class="btn btn-primary" data-dismiss="" onClick="subirOtSidiDesdeExcel()">Importar Datos</button>
      		</div>
    	</div>
	</div>
</div> 


<script  src="js/js_global.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>
<script  src="js/js_principal.js?<?php echo (versionCibeles); ?>" type="text/javascript" language="JavaScript" charset="UTF-8"></script>

<span style="float: none; width: 100%"><br>&nbsp;</span>
<?php

echo ("</div>");

echo ("</html>");



?>



<script language="javascript">

	
$(function() {
var selectedClass = "";
$(".filter").click(function(){
selectedClass = $(this).attr("data-rel");
$("#gallery").fadeTo(100, 0.1);
$("#gallery div").not("."+selectedClass).fadeOut().removeClass('animation');
setTimeout(function() {
$("."+selectedClass).fadeIn().addClass('animation');
$("#gallery").fadeTo(300, 1);
}, 300);
});
});
	

	

	
	

	//alert("La resolución de tu pantalla es: " + screen.width + " x " + screen.height) ;

</script>

<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>-->


<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>


    

<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>-->

<!-- 2012120  -->

<style>
.gallery {	
-webkit-column-count: 3;
-moz-column-count: 3;
column-count: 3;
-webkit-column-width: 33%;
-moz-column-width: 33%;
column-width: 33%; }
.gallery .pics {
-webkit-transition: all 350ms ease;
transition: all 350ms ease; }
.gallery .animation {
-webkit-transform: scale(1);
-ms-transform: scale(1);
transform: scale(1); }

@media (max-width: 450px) {
.gallery {
-webkit-column-count: 1;
-moz-column-count: 1;
column-count: 1;
-webkit-column-width: 100%;
-moz-column-width: 100%;
column-width: 100%;
}
}

@media (max-width: 400px) {
.btn.filter {
padding-left: 1.1rem;
padding-right: 1.1rem;
}
}
</style>

