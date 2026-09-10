<?php

if(isset($_POST["accion"]) && $_POST["accion"]=="verEstadoVerifactu")
{
	session_start();
	$ruta = '../';
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	require_once($ruta."Verifactu/wortice/lanzarFactura.php");

	$queueId = isset($_POST["queueId"]) ? $_POST["queueId"] : '';
	$clayma  = isset($_POST["clayma"]) && $_POST["clayma"]==1;

	$res = verEstado($queueId, $clayma);

	echo json_encode($res);
}

?>
