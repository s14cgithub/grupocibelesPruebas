<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="insertarMovimientoAbono")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");	
	
	$abono =  $_POST["abono"];
	
	$clayma = $_POST["clayma"];
	
	$clayma1 = 0;
	
	if ($clayma=="true")
	{
		$clayma1=1;
	}
	
	
	
	
	echo insertarMovimientoAbonoCibeles($conexion,$abono,$clayma1);	
	
	if (if ($clayma=="true")
	{
	}
	else
	{
		$resultado = verPresupuestoDelAbono($conexion, $abono);
	
		$pos = strpos($resultado[0]["presupuesto"], "mensual");

		if ($pos === false)
		{		
		}
		else
		{
			$fecha = date("d-m-Y");
			$importe = $resultado[0]["aPagar"];
			$idClienteSaldo = $resultado[0]["codigo_saldo"];

			modificarDatosPFenCliente($conexion,$idClienteSaldo,$fecha,$importe);	
		}
	}
	
	
	
	
	
	
	
	
}


?>
