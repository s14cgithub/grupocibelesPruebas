<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarRegistroEspecial")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$mes = $_POST["mes"];
	//$fechaFac=$_POST["fechaFac"];

	$resultado=verEstadoFacturacionFinMes($conexion);
	if ($resultado[0]["activado"]=="0")
	{
		$fechaFac = $resultado[0]["fechaImprimir"]->format("d-m-Y");
	}
	else
	{
		$fechaFac = date('d-m-Y');
	}
	
	
	$Date = date("d-m-Y");  
	
	$anio = date("Y");
	//$mes = date("m");
	$dia = date("d");
	
	$fecha = new DateTime($mes."-".$mes."-".$anio);
	
	$fecha->modify('first day of this month');
	
	$primerDia = $fecha->format('d/m/Y');
	
	$fecha = new DateTime($mes."-".$mes."-".$anio);
	
	$fecha->modify('last day of this month');
	
	$ultimoDia = $fecha->format('d/m/Y');
	
	
	//echo ('<br>\n'.$primerDia);
	//echo ('<br>\n'.$ultimoDia);
	
	echo insertarCertYrut($conexion,$fechaFac,$primerDia,$ultimoDia);
	echo $fechaFac;
	
	
	
	
}



?>