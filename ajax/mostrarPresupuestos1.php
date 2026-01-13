<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarPresupuesto")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$orden = $_POST["orden"];
	$desc = $_POST["desc"];
	$texto =  $_POST["texto"];
	$queBusca =  $_POST["queBusca"];
	
	$bajada = $_POST["bajada"];
	$abierta = $_POST["abierta"];
	
	$meses = $_POST["meses"];

	$fechaAceptacion=$_POST["fechaAceptacion"];
	
	


	//guardar busqueda
	$_SESSION["presupuestoListado_texto"] = $texto;
	$_SESSION["presupuestoListado_queBusca"] = $queBusca;
	$_SESSION["presupuestoListado_Bajada"] = $bajada;
	$_SESSION["presupuestoListado_Abierta"] = $abierta;
	$_SESSION["presupuestoListado_meses"] = $meses;
	$_SESSION["presupuestoListado_orden"] = $orden;
	$_SESSION["presupuestoListado_Desc"] = $desc;
	$_SESSION["presupuestoListado_fechaAceptacion"] = $fechaAceptacion;


	



	
	$fecha = "";
	//$meses=0;


	if ($meses>0)
	{
		$meses = $meses-1;
		$fechaActual = date('d-m-Y');
		$fechaInicio = date("01-m-Y",strtotime($fechaActual."- ".$meses." month")); 
		$fecha = " fecha >='".$fechaInicio."'";
	}
	
	
	$resultado = mostrarPresupuestos1($conexion,$orden,$desc,$texto,$queBusca, $bajada, $abierta, $fecha,$fechaAceptacion);
	
	if (count($resultado)<=0)
	{		
		//siempre debe de haber un registro
		echo  ("");
	}	
	else
	{
		echo  json_encode($resultado);
		//echo $resultado;
	}
}


?>