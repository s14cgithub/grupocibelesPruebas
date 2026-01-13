<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="comprobarLogin")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	//require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	$usuario = $_POST["usuario"];
	$contrasena = $_POST["contrasena"];
	
	$error = "";
	
	
	$longitud = strlen($usuario);
	
	if (strlen($usuario) == 0 /*|| strpos($usuario, "'") === false /*|| !strpos($usuario, "*") || !strpos($usuario, '"') || !strpos($usuario, "?") || !strpos($usuario, "&") || !strpos($usuario, "#")*/)
	{
		$error = "Error 1";	
	}	
	else if (strlen($contrasena)<=0 /*|| !strpos($contrasena, "'") || !strpos($contrasena, "*") || !strpos($contrasena, '"') || !strpos($contrasena, "?") || !strpos($contrasena, "&") || !strpos($contrasena, "#")*/)
	{
		$error = "Error 2";	
		//$error = "Error 2\n".strlen($contrasena)."\n".strpos($contrasena, "'");
	}
	//echo ("<br>".strlen($error)."<br>");
	
	if (strlen($error) == 0)
	{
		
		
		$login =comprobarLogin($conexion,$usuario,$contrasena);
		
		//echo ("\nNum registros: ".count($login));
		//echo ("\nValor primer registro: ".$login[0]["id"]);
				
		
		$numFilas = count($login);		
		
		
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
			
			$idUsuario = $login[0]["id"];
				
			session_start(); 
			$_SESSION = array();
			session_unset();
			session_destroy();
			session_start();
			session_regenerate_id();			
			
			$_SESSION['usuario'] = $usuario;
			$_SESSION["idEmpleado"] = $login[0]["idEmpleado"];
			
			
			//echo ("\nUsuario global: ".$_SESSION['usuario']);
			//echo ("\nUsuario: ".$usuario);
			
			//se guarda un registro de entrada
			
			/*$descripcion = "Iniciar sesion";
			insertarRegistro($conexion,$usuario, $descripcion,"","","","",0);	*/
			
			
			//insertarRegistro ($datosBBDD, $usuario1, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro)
		    //echo $ver;  
			
			$permisos = verPermisos($conexion, $idUsuario);
			
			//echo ("\nPermiso: ".$permisos[0]["clientes"]);
			
			//echo ("\nPermiso: ".$permisos[0]["pda"]);
			
			if ($permisos[0]["pda"] == 1)
			{
				echo ("pda");
			}
			else if ($permisos[0]["pdaConductor"] == 1)
			{
				echo ("pdaConductor");
			}
				
			//PERMISOS
			
			$_SESSION["permiso_clientes"] = $permisos[0]["clientes"];
			$_SESSION["permiso_pdaGestion"] = $permisos[0]["pdaGestion"];
			$_SESSION["permiso_registrosHorasManuales"] = $permisos[0]["pda_registrosHorasManuales"];
			$_SESSION["permiso_registrosHorasManuales_Comprobacion"] = $permisos[0]["pda_registrosHorasManuales_Comprobacion"];

			$_SESSION["permiso_InformesProduccion"] = $permisos[0]["informesProduccion"];
			$_SESSION["permiso_pdaAdjuntos"] = $permisos[0]["pdaAdjunto"];
			$_SESSION["permiso_presupuestos"] = $permisos[0]["presupuestos"];
			$_SESSION["permiso_nuevoProcesoPresu"] = $permisos[0]["nuevoProcesoPresu"];
			$_SESSION["permiso_fechaCompromisoPresu"] = $permisos[0]["cambiarFechaCompromisoPresu"];
			$_SESSION["permiso_fechaAceptacionPresu"] = $permisos[0]["cambiarFechaAceptacionPresu"];
			
			$_SESSION["permiso_otBajadaPresu"] = $permisos[0]["presuOtBajada"];
			$_SESSION["permiso_otAbiertaPresu"] = $permisos[0]["presuOtAbierta"];
			$_SESSION["permiso_fechaTerminadaPresu"] = $permisos[0]["presuOtTerminada"];
			$_SESSION["permiso_otBajadaAutomatico"] = $permisos[0]["otBajadaAutomatico"];
			
			$_SESSION["permiso_ot"] = $permisos[0]["ot"];
			$_SESSION["permiso_administracion"] = $permisos[0]["administracion"];
			$_SESSION["permiso_administracion_contabilidad"] = $permisos[0]["admContabilidad"];

			$_SESSION["permiso_administracion_contabilidad_domiciliados"] = $permisos[0]["admContabilidad_domiciados"];

			$_SESSION["permiso_administracion_facturacion"] = $permisos[0]["admFacturacion"];
			
			
			
			
			
			
			$_SESSION["permiso_grabarFranqueo"] =  $permisos[0]["grabarFranqueo"];
			
			$_SESSION["permiso_franqueoF12"] =  $permisos[0]["franqueoF12"];
			
			$_SESSION["permiso_actualizarDatos"] =  $permisos[0]["actualizarDatos"];
			
			$_SESSION["permiso_presupuestoMensual"] =  $permisos[0]["presupuestoMensual"];
			$_SESSION["permiso_rutas"] =  $permisos[0]["rutas"];
			$_SESSION["permiso_empleados"] =  $permisos[0]["empleados"];
			$_SESSION["permiso_comprasAterceros"] =  $permisos[0]["comprasAterceros"];
			$_SESSION["permiso_proveedores"] =  $permisos[0]["proveedores"];
			
			$_SESSION["provisionFondos"] = $permisos[0]["provisionFondos"];
			$_SESSION["facturasManipulacion"] = $permisos[0]["facturasManipulacion"];
			$_SESSION["facturasCorreos"] = $permisos[0]["facturasCorreos"];
			$_SESSION["facturas"] = $permisos[0]["facturas"];
			$_SESSION["certAlbGastAdicional"] = $permisos[0]["certAlbGastAdicional"];
			$_SESSION["soloGrabarRecogidasEntregas"] = $permisos[0]["soloGrabarRecogidasEntregas"];
			$_SESSION["admInformes"] = $permisos[0]["admInformes"];
			
			$_SESSION["permiso_noFacProcesado"] = $permisos[0]["noFacProcesado"];
			$_SESSION["permiso_soloNoFacturable"] = $permisos[0]["soloNoFacturable"];
			$_SESSION["permiso_almacen"] = $permisos[0]["almacen"];
			
			$_SESSION["permiso_almacen_Nuevo"] = $permisos[0]["almacen_nuevo"];
			$_SESSION["permiso_almacen_Albaran"] = $permisos[0]["almacen_albaran"];
			
			$_SESSION["permiso_estimacionFranqueo"] = $permisos[0]["estimacionFranqueo"];			
			$_SESSION["permiso_informeFacturaEstadisticas"] = $permisos[0]["informeFacturaEstadisticas"];
			
			$_SESSION["permiso_soloDireccion"] = $permisos[0]["soloDireccion"];
			$_SESSION["permiso_preEntradaGestion"] = $permisos[0]["preEntradaGestion"];
			
			
			$_SESSION["permiso_almacen_Listado"] = $permisos[0]["almacen_listado"];

			$_SESSION["permiso_clientesAutorizados_franqueo"] = $permisos[0]["clientesAutorizadosFranqueo"];

			$_SESSION["permiso_tarifas"] = $permisos[0]["tarifas"];
			$_SESSION["permiso_materialesPapel"] = $permisos[0]["materialesPapel"];

			
			

					
		}
		
		
		
		
	}
	else
	{
		echo $error;	
	}
	
	
	
	
	
	

		
		
		
}

?>
