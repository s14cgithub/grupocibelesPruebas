<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarPermisos")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");

	$idLogin = $_POST["idLogin"];

	// pda y pdaConductor se guardan tal cual llegan (string "true"/"false"), igual que el codigo original;
	// el resto se convierte a 2 (marcado) / 0 (desmarcado)
	$camposBooleanos = array(
		'administracion', 'admContabilidad', 'admFacturacion', 'pdaGestion', 'pdaAdjunto',
		'informesProduccion', 'presupuestos', 'nuevoProcesoPresu', 'cambiarFechaCompromisoPresu',
		'cambiarFechaAceptacionPresu', 'presuOtBajada', 'presuOtAbierta', 'presuOtTerminada',
		'otBajadaAutomatico', 'ot', 'grabarFranqueo', 'franqueoF12', 'actualizarDatos',
		'presupuestoMensual', 'rutas'
	);

	$camposPost = array(
		'administracion' => 'pms_administracion',
		'admContabilidad' => 'pms_admContabilidad',
		'admFacturacion' => 'pms_admFacturacion',
		'pdaGestion' => 'pms_pda_gestion',
		'pdaAdjunto' => 'pms_pdaAdjunto',
		'informesProduccion' => 'pms_informesProduccion',
		'presupuestos' => 'pms_presupuestos',
		'nuevoProcesoPresu' => 'pms_nuevoProcesoPresu',
		'cambiarFechaCompromisoPresu' => 'pms_cambiarFechaCompromisoPresu',
		'cambiarFechaAceptacionPresu' => 'pms_cambiarFechaAceptacionPresu',
		'presuOtBajada' => 'pms_otBajada',
		'presuOtAbierta' => 'pms_otAbierta',
		'presuOtTerminada' => 'pms_otTerminada',
		'otBajadaAutomatico' => 'pms_otBajadaAutomatico',
		'ot' => 'pms_prodOt',
		'grabarFranqueo' => 'pms_prodGrabarFranqueo',
		'franqueoF12' => 'pms_prodFranqueoF12',
		'actualizarDatos' => 'pms_actualizarDatos',
		'presupuestoMensual' => 'pms_presupuestoMensual',
		'rutas' => 'pms_rutas',
		'pda' => 'pms_pda',
		'pdaConductor' => 'pms_pdaConductor'
	);

	$datos = array();
	foreach ($camposPost as $campo => $nombrePost) {
		$valor = isset($_POST[$nombrePost]) ? $_POST[$nombrePost] : 'false';
		if (in_array($campo, $camposBooleanos)) {
			$datos[$campo] = ($valor == 'true') ? 2 : 0;
		} else {
			// pda / pdaConductor: se guardan tal cual, sin convertir
			$datos[$campo] = $valor;
		}
	}

	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$resultado = modificarPermisos($conn, $bbddSql, $datos, array('id_usuario' => $idLogin));

	sqlsrv_close($conn);

	if (!$resultado['ok']) {
		echo json_encode(array('error' => $resultado['error']));
	} else {
		echo json_encode(array('error' => '', 'mensaje' => 'Permisos Modificados'));
	}
}

?>