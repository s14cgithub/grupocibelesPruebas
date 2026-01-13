<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarPermisos")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$idLogin=$_POST["idLogin"];
	
	$pms_pda=$_POST["pms_pda"];
	$pms_pda_gestion=$_POST["pms_pda_gestion"];
	$pms_pdaAdjunto=$_POST["pms_pdaAdjunto"];
	$pms_informesProduccion=$_POST["pms_informesProduccion"];
	$pms_presupuestos=$_POST["pms_presupuestos"];
	$pms_nuevoProcesoPresu=$_POST["pms_nuevoProcesoPresu"];
	$pms_cambiarFechaCompromisoPresu=$_POST["pms_cambiarFechaCompromisoPresu"];
	$pms_cambiarFechaAceptacionPresu=$_POST["pms_cambiarFechaAceptacionPresu"];
	$pms_otBajada=$_POST["pms_otBajada"];
	$pms_otAbierta=$_POST["pms_otAbierta"];
	$pms_otTerminada=$_POST["pms_otTerminada"];
	$pms_otBajadaAutomatico=$_POST["pms_otBajadaAutomatico"];
	$pms_prodOt=$_POST["pms_prodOt"];
	
	$pms_administracion=$_POST["pms_administracion"];
	$pms_admContabilidad=$_POST["pms_admContabilidad"];
	$pms_admFacturacion=$_POST["pms_admFacturacion"];
	
	$pms_prodGrabarFranqueo=$_POST["pms_prodGrabarFranqueo"];
	$pms_prodFranqueoF12=$_POST["pms_prodFranqueoF12"];
	$pms_actualizarDatos=$_POST["pms_actualizarDatos"];
	$pms_presupuestoMensual=$_POST["pms_presupuestoMensual"];
	$pms_rutas=$_POST["pms_rutas"];
	$pms_pdaConductor=$_POST["pms_pdaConductor"];
	
	
	if ($pms_administracion=="true")
	{
		$pms_administracion=2;
	}
	else
	{
		$pms_administracion=0;
	}
	
	if ($pms_admContabilidad=="true")
	{
		$pms_admContabilidad=2;
	}
	else
	{
		$pms_admContabilidad=0;
	}
	
	if ($pms_admFacturacion=="true")
	{
		$pms_admFacturacion=2;
	}
	else
	{
		$pms_admFacturacion=0;
	}
	
	/*if ($pms_pda=="true")
	{
		$pms_pda=2;
	}
	else
	{
		$pms_pda=0;
	}*/
	
	if ($pms_pda_gestion=="true")
	{
		$pms_pda_gestion=2;
	}
	else
	{
		$pms_pda_gestion=0;
	}
	
	if ($pms_pdaAdjunto=="true")
	{
		$pms_pdaAdjunto=2;
	}
	else
	{
		$pms_pdaAdjunto=0;
	}
	
	if ($pms_informesProduccion=="true")
	{
		$pms_informesProduccion=2;
	}
	else
	{
		$pms_informesProduccion=0;
	}
	
	if ($pms_presupuestos=="true")
	{
		$pms_presupuestos=2;
	}
	else
	{
		$pms_presupuestos=0;
	}
	
	if ($pms_nuevoProcesoPresu=="true")
	{
		$pms_nuevoProcesoPresu=2;
	}
	else
	{
		$pms_nuevoProcesoPresu=0;
	}
	
	if ($pms_cambiarFechaCompromisoPresu=="true")
	{
		$pms_cambiarFechaCompromisoPresu=2;
	}
	else
	{
		$pms_cambiarFechaCompromisoPresu=0;
	}
	
	if ($pms_cambiarFechaAceptacionPresu=="true")
	{
		$pms_cambiarFechaAceptacionPresu=2;
	}
	else
	{
		$pms_cambiarFechaAceptacionPresu=0;
	}
	
	if ($pms_otBajada=="true")
	{
		$pms_otBajada=2;
	}
	else
	{
		$pms_otBajada=0;
	}
	
	if ($pms_otAbierta=="true")
	{
		$pms_otAbierta=2;
	}
	else
	{
		$pms_otAbierta=0;
	}
	
	if ($pms_otTerminada=="true")
	{
		$pms_otTerminada=2;
	}
	else
	{
		$pms_otTerminada=0;
	}
	
	if ($pms_otBajadaAutomatico=="true")
	{
		$pms_otBajadaAutomatico=2;
	}
	else
	{
		$pms_otBajadaAutomatico=0;
	}
	
	if ($pms_prodOt=="true")
	{
		$pms_prodOt=2;
	}
	else
	{
		$pms_prodOt=0;
	}
	
	
	
	if ($pms_prodGrabarFranqueo=="true")
	{
		$pms_prodGrabarFranqueo=2;
	}
	else
	{
		$pms_prodGrabarFranqueo=0;
	}
	
	if ($pms_prodFranqueoF12=="true")
	{
		$pms_prodFranqueoF12=2;
	}
	else
	{
		$pms_prodFranqueoF12=0;
	}
	
	if ($pms_actualizarDatos=="true")
	{
		$pms_actualizarDatos=2;
	}
	else
	{
		$pms_actualizarDatos=0;
	}
	
	if ($pms_presupuestoMensual=="true")
	{
		$pms_presupuestoMensual=2;
	}
	else
	{
		$pms_presupuestoMensual=0;
	}
	
	if ($pms_rutas=="true")
	{
		$pms_rutas=2;
	}
	else
	{
		$pms_rutas=0;
	}
	
	/*if ($pms_pdaConductor=="true")
	{
		$pms_pdaConductor=2;
	}
	else
	{
		$pms_pdaConductor=0;
	}*/
	
	
	
	echo modificarPermisos($conexion,$idLogin,$pms_administracion,$pms_pda,$pms_pda_gestion,$pms_pdaAdjunto,$pms_informesProduccion,$pms_presupuestos,$pms_nuevoProcesoPresu,$pms_cambiarFechaCompromisoPresu,$pms_cambiarFechaAceptacionPresu,$pms_otBajada,$pms_otAbierta,$pms_otTerminada,$pms_otBajadaAutomatico,$pms_prodOt,$pms_admContabilidad,$pms_prodGrabarFranqueo,$pms_prodFranqueoF12,$pms_actualizarDatos,$pms_presupuestoMensual,$pms_rutas,$pms_pdaConductor, $pms_admFacturacion);
	
		
}

?>
