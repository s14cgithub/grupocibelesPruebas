<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="comprobarLogin")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	//require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();

	


	$error = "";
	////////////////////////////////////////////////////////////////
	
	
	
	
	//$longitud = strlen($usuario);
	
	if (strlen($filtros['usuario']) <= 0 /*|| strpos($usuario, "'") === false /*|| !strpos($usuario, "*") || !strpos($usuario, '"') || !strpos($usuario, "?") || !strpos($usuario, "&") || !strpos($usuario, "#")*/)
	{
		$error = "Error: Introducir Usuario";	
	}	
	else if (strlen($filtros['contrasena'])<=0 /*|| !strpos($contrasena, "'") || !strpos($contrasena, "*") || !strpos($contrasena, '"') || !strpos($contrasena, "?") || !strpos($contrasena, "&") || !strpos($contrasena, "#")*/)
	{
		$error = "Error: Introducir Contraseña";	
		//$error = "Error 2\n".strlen($contrasena)."\n".strpos($contrasena, "'");
	}
	//echo ("<br>".strlen($error)."<br>");
	
	if (strlen($error) == 0)
	{
		$campos=['usuario','id','idEmpleado'];
		$filtros['activo']=1;
		$filtrosOperadores = array();
		$order = array();


		$conn1 = conectarSQL($conexion);

		$conn = $conn1['conn'];
		$bbddSql = $conn1['bbdd'];	
		
		$login = cargarLogin($conn,$bbddSql, $campos, $filtros,$filtrosOperadores, $order);
		//echo $cargarClientes['sql'];
		
		
		
		
		//echo ("\nNum registros: ".count($login));
		//echo ("\nValor primer registro: ".$login[0]["id"]);
				
		
		$numFilas = count($login['datos']);		
		
		
		if ($numFilas > 1)
		{			
			echo ("Error: Hay más de una persona con el mismo usuario.\nContactar con el administrador.");
		}
		else if ($numFilas<=0)
		{			
			echo ("Error: Datos incorrectos");
		}
		else if ($numFilas == 1)
		{
			
			$idUsuario = $login['datos'][0]["id"];
				
			session_start(); 
			$_SESSION = array();
			session_unset();
			session_destroy();
			session_start();
			session_regenerate_id();	
			
			$_SESSION['usuario'] = $login['datos'][0]["usuario"];
			$_SESSION["idEmpleado"] = $login['datos'][0]["idEmpleado"];
			
			
			//echo ("\nUsuario global: ".$_SESSION['usuario']);
			//echo ("\nUsuario: ".$usuario);
			
			//se guarda un registro de entrada
			
			/*$descripcion = "Iniciar sesion";
			insertarRegistro($conexion,$usuario, $descripcion,"","","","",0);	*/
			
			
			//insertarRegistro ($datosBBDD, $usuario1, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro)
		    //echo $ver;  
			

			$campos2=[
				'pdaConductor',
				'pda',
				'clientes',
				'pdaGestion',
				'pda_registrosHorasManuales',
				'informesProduccion',
				'pdaAdjunto',
				'presupuestos',
				'nuevoProcesoPresu',
				'cambiarFechaCompromisoPresu',
				'cambiarFechaAceptacionPresu',
				'presuOtBajada',
				'presuOtAbierta',
				'presuOtTerminada',
				'otBajadaAutomatico',
				'ot',
				'administracion',
				'admContabilidad',
				'admContabilidad_domiciados',
				'admFacturacion',
				'grabarFranqueo',
				'franqueoF12',
				'actualizarDatos',
				'presupuestoMensual',
				'rutas',
				'empleados',
				'comprasAterceros',
				'proveedores',
				'provisionFondos',
				'facturasManipulacion',
				'facturasCorreos',
				'facturas',
				'certAlbGastAdicional',
				'soloGrabarRecogidasEntregas',
				'almacen',
				'almacen_nuevo',
				'almacen_albaran',
				'estimacionFranqueo',
				'informeFacturaEstadisticas',
				'soloDireccion',
				'preEntradaGestion',
				'almacen_listado',
				'clientesAutorizadosFranqueo',
				'tarifas',
				'materialesPapel',
				'noFacProcesado',
				'soloNoFacturable',
				'admInformes'

				];
			$filtros2 = [
					'id_usuario' => $idUsuario
				];			
			$filtrosOperadores2 = array();
			$order2 = array();

			$permisos = cargarPermisos($conn,$bbddSql, $campos2, $filtros2,$filtrosOperadores2, $order2);
	
			//echo ("\nPermiso: ".$permisos['datos'][0]["clientes"]);
			
			//echo ("\nPermiso: ".$permisos['datos'][0]["pda"]);
			
			if ($permisos['datos'][0]["pda"] == 1)
			{
				echo ("pda");
			}
			else if ($permisos['datos'][0]["pdaConductor"] == 1)
			{
				echo ("pdaConductor");
			}
				
			//PERMISOS
			
			$_SESSION["permiso_clientes"] = $permisos['datos'][0]["clientes"];
			$_SESSION["permiso_pdaGestion"] = $permisos['datos'][0]["pdaGestion"];
			$_SESSION["permiso_registrosHorasManuales"] = $permisos['datos'][0]["pda_registrosHorasManuales"];
			$_SESSION["permiso_registrosHorasManuales_Comprobacion"] = $permisos['datos'][0]["pda_registrosHorasManuales_Comprobacion"];

			$_SESSION["permiso_InformesProduccion"] = $permisos['datos'][0]["informesProduccion"];
			$_SESSION["permiso_pdaAdjuntos"] = $permisos['datos'][0]["pdaAdjunto"];
			$_SESSION["permiso_presupuestos"] = $permisos['datos'][0]["presupuestos"];
			$_SESSION["permiso_nuevoProcesoPresu"] = $permisos['datos'][0]["nuevoProcesoPresu"];
			$_SESSION["permiso_fechaCompromisoPresu"] = $permisos['datos'][0]["cambiarFechaCompromisoPresu"];
			$_SESSION["permiso_fechaAceptacionPresu"] = $permisos['datos'][0]["cambiarFechaAceptacionPresu"];
			
			$_SESSION["permiso_otBajadaPresu"] = $permisos['datos'][0]["presuOtBajada"];
			$_SESSION["permiso_otAbiertaPresu"] = $permisos['datos'][0]["presuOtAbierta"];
			$_SESSION["permiso_fechaTerminadaPresu"] = $permisos['datos'][0]["presuOtTerminada"];
			$_SESSION["permiso_otBajadaAutomatico"] = $permisos['datos'][0]["otBajadaAutomatico"];
			
			$_SESSION["permiso_ot"] = $permisos['datos'][0]["ot"];
			$_SESSION["permiso_administracion"] = $permisos['datos'][0]["administracion"];
			$_SESSION["permiso_administracion_contabilidad"] = $permisos['datos'][0]["admContabilidad"];

			$_SESSION["permiso_administracion_contabilidad_domiciliados"] = $permisos['datos'][0]["admContabilidad_domiciados"];

			$_SESSION["permiso_administracion_facturacion"] = $permisos['datos'][0]["admFacturacion"];
			
			
			
			
			
			
			$_SESSION["permiso_grabarFranqueo"] =  $permisos['datos'][0]["grabarFranqueo"];
			
			$_SESSION["permiso_franqueoF12"] =  $permisos['datos'][0]["franqueoF12"];
			
			$_SESSION["permiso_actualizarDatos"] =  $permisos['datos'][0]["actualizarDatos"];
			
			$_SESSION["permiso_presupuestoMensual"] =  $permisos['datos'][0]["presupuestoMensual"];
			$_SESSION["permiso_rutas"] =  $permisos['datos'][0]["rutas"];
			$_SESSION["permiso_empleados"] =  $permisos['datos'][0]["empleados"];
			$_SESSION["permiso_comprasAterceros"] =  $permisos['datos'][0]["comprasAterceros"];
			$_SESSION["permiso_proveedores"] =  $permisos['datos'][0]["proveedores"];
			
			$_SESSION["provisionFondos"] = $permisos['datos'][0]["provisionFondos"];
			$_SESSION["facturasManipulacion"] = $permisos['datos'][0]["facturasManipulacion"];
			$_SESSION["facturasCorreos"] = $permisos['datos'][0]["facturasCorreos"];
			$_SESSION["facturas"] = $permisos['datos'][0]["facturas"];
			$_SESSION["certAlbGastAdicional"] = $permisos['datos'][0]["certAlbGastAdicional"];
			$_SESSION["soloGrabarRecogidasEntregas"] = $permisos['datos'][0]["soloGrabarRecogidasEntregas"];
			$_SESSION["admInformes"] = $permisos['datos'][0]["admInformes"];
			
			$_SESSION["permiso_noFacProcesado"] = $permisos['datos'][0]["noFacProcesado"];
			$_SESSION["permiso_soloNoFacturable"] = $permisos['datos'][0]["soloNoFacturable"];
			$_SESSION["permiso_almacen"] = $permisos['datos'][0]["almacen"];
			
			$_SESSION["permiso_almacen_Nuevo"] = $permisos['datos'][0]["almacen_nuevo"];
			$_SESSION["permiso_almacen_Albaran"] = $permisos['datos'][0]["almacen_albaran"];
			
			$_SESSION["permiso_estimacionFranqueo"] = $permisos['datos'][0]["estimacionFranqueo"];			
			$_SESSION["permiso_informeFacturaEstadisticas"] = $permisos['datos'][0]["informeFacturaEstadisticas"];
			
			$_SESSION["permiso_soloDireccion"] = $permisos['datos'][0]["soloDireccion"];
			$_SESSION["permiso_preEntradaGestion"] = $permisos['datos'][0]["preEntradaGestion"];
			
			
			$_SESSION["permiso_almacen_Listado"] = $permisos['datos'][0]["almacen_listado"];

			$_SESSION["permiso_clientesAutorizados_franqueo"] = $permisos['datos'][0]["clientesAutorizadosFranqueo"];

			$_SESSION["permiso_tarifas"] = $permisos['datos'][0]["tarifas"];
			$_SESSION["permiso_materialesPapel"] = $permisos['datos'][0]["materialesPapel"];

			
			

					
		}
		
		sqlsrv_close($conn);
		
		
	}
	else
	{
		echo $error;	
	}
	
	
	
	
	
	

		
		
		
}

?>
