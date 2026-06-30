<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="modificarCliente")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$datos=isset($_POST["datos"])?json_decode($_POST["datos"], true):array();
	$filtros=isset($_POST["filtros"])?json_decode($_POST["filtros"], true):array();
	$filtrosOperadores=isset($_POST["filtrosOperadores"])?json_decode($_POST["filtrosOperadores"], true):array();
	$datosIncremento = array();
	
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];	

	$res = modificarClientes($conn, $bbddSql, $datos, $filtros, $filtrosOperadores, $datosIncremento);
	
	sqlsrv_close($conn);	
	echo json_encode($res);	


	/*
	$codigoCliente = $_POST["codigoCliente"];
	
	$direccion = $_POST["direccion"];
	$localidad = $_POST["localidad"];
	$provincia = $_POST["provincia"];
	$comercial = $_POST["comercial"];
	$cp = $_POST["cp"];
	$pais = $_POST["pais"];
	$codigoPais = $_POST["codigoPais"];
	
	if ($comercial==null || $comercial == "null" || $comercial == "")
	{
		$comercial=0;
	}
	 
	$codigoSIDI = $_POST["codigoSIDI"];
	
	$diasApagar = $_POST["diasApagar"];
	$formaPago = $_POST["formaPago"];
	$email = $_POST["email"];
	$numCuenta = $_POST["numCuenta"];
	

	if ($codigoPais=="")
	{
		$codigoPais="ES";
	}


	if ($_POST["correoDiario"]==="true")
		$correoDiario = 1;
	else
		$correoDiario = 0;
	

	if ($_POST["activo"]==="true")
		$activo = 1;
	else
		$activo = 0;
	
	if ($_POST["domiciliado"]==="true")
		$domiciliado = 1;
	else
		$domiciliado = 0;
	
	
	if ($_POST["sinIva"]==="true")
		$sinIva = 1;
	else
		$sinIva = 0;
	
	if ($_POST["retener"]==="true")
		$retener = 1;
	else
		$retener = 0;
	
	if ($_POST["preFactura"]==="true")
		$preFactura = 1;
	else
		$preFactura = 0;

	if ($_POST["noAplicarPF"]==="true")
		$noAplicarPF = 1;
	else
		$noAplicarPF = 0;

	if ($_POST["retencion"]==="true")
		$retencion = 1;
	else
		$retencion = 0;
	
	
	
	//echo "sinIva: ".$sinIva;
	
	$nuestraCuenta = $_POST["nuestraCuenta"];
	
	$cuotaRecogida = $_POST["cuotaRecogida"];
	$periodo = $_POST["periodo"];	
	$prodNoBon = $_POST["prodNoBon"];
	$otrosConceptos = $_POST["otrosConceptos"];
	$importeOtrosConceptos = $_POST["importeOtrosConceptos"];
	$provFondos = $_POST["provFondos"];
	$pfFijaImporte = $_POST["pfFijaImporte"];
	$cobroUnitarioEnvio = $_POST["cobroUnitarioEnvio"];
	
	
	$envio_Att = $_POST["envio_Att"];
	$envio_Nombre = $_POST["envio_Nombre"];
	$envio_Direccion = $_POST["envio_Direccion"];
	$envio_cp = $_POST["envio_cp"];
	$envio_poblacion = $_POST["envio_poblacion"];
	$envio_provincia = $_POST["envio_provincia"];
	$envio_pais = $_POST["envio_pais"];
	
	$pedidoCliente = $_POST["pedidoCliente"];
	$vencimiento = $_POST["vencimiento"];
	
	if ($periodo=="")
	{
		$periodo="null";
	}
	if ($provFondos==="")
		$provFondos = 0;
	
	if ($cuotaRecogida==="")
		$cuotaRecogida = 0;
	
	if ($prodNoBon==="")
		$prodNoBon = 0;
	
	if ($importeOtrosConceptos==="")
		$importeOtrosConceptos = 0;
	
	if ($cobroUnitarioEnvio==="")
		$cobroUnitarioEnvio = 0;
	
	
	if ($pfFijaImporte==="")
		$pfFijaImporte = 0;
	
	
	
	
	$clayma=$_POST["clayma"];
	
	
	
	if ($clayma=="true")
	{
		echo modificarClienteClayma($conexion,$codigoCliente,$direccion,$localidad,$provincia,$cp,$comercial,$diasApagar,$formaPago,$email,$numCuenta,$cuotaRecogida,$periodo,$prodNoBon,$otrosConceptos,$importeOtrosConceptos,$provFondos,$cobroUnitarioEnvio,$envio_Att,$envio_Nombre,$envio_Direccion,$envio_cp,$envio_poblacion,$envio_provincia,$envio_pais,$correoDiario, $activo, $pfFijaImporte,$domiciliado, $nuestraCuenta,$sinIva,$retener, $pedidoCliente,$vencimiento,$preFactura,$noAplicarPF,$retencion,$pais,$codigoPais);	
	}
	else
	{
		echo modificarCliente($conexion,$codigoCliente,$direccion,$localidad,$provincia,$cp,$comercial,$diasApagar,$formaPago,$email,$numCuenta,$cuotaRecogida,$periodo,$prodNoBon,$otrosConceptos,$importeOtrosConceptos,$provFondos,$cobroUnitarioEnvio,$envio_Att,$envio_Nombre,$envio_Direccion,$envio_cp,$envio_poblacion,$envio_provincia,$envio_pais,$correoDiario, $activo, $pfFijaImporte,$domiciliado, $nuestraCuenta,$sinIva,$retener, $pedidoCliente,$vencimiento,$preFactura,$noAplicarPF,$retencion, $codigoSIDI,$pais,$codigoPais);	
	}
	
	
	*/
		
	
	
			
	
}


?>
