<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="crearClienteNuevo")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$codigoSaldo = $_POST["codigoSaldo"];	
	$subCliente = $_POST["subCliente"];	
	$nombreEmpresa = $_POST["nombreEmpresa"];
	$nif = $_POST["nif"];
	
	$nifSubCliente = $_POST["nifSubCliente"];
	$direccion = $_POST["direccion"];
	$localidad = $_POST["localidad"];
	$provincia = $_POST["provincia"];
	$cp = $_POST["cp"];

	$pais = $_POST["pais"];
	$codigoPais = $_POST["codigoPais"];
	
	$nombreFranqueo = $_POST["nombreFranqueo"];
	$comercial = $_POST["comercial"];
	$diasApagar = $_POST["diasApagar"];
	$formaPago = $_POST["formaPago"];
	$EmailFactura = $_POST["EmailFactura"];
	$numCuenta = $_POST["numCuenta"];
	$nuestraCuenta = $_POST["nuestraCuenta"];
	
	$cuotaRecogida = $_POST["cuotaRecogida"]==""?0:$_POST["cuotaRecogida"];
	$periodoFacturacion = $_POST["periodoFacturacion"];
	$prodNoBon = $_POST["prodNoBon"]==""?0:$_POST["prodNoBon"];
	$otrosConceptos = $_POST["otrosConceptos"];
	$importeFijoOtrosConceptos = $_POST["importeFijoOtrosConceptos"]==""?0:$_POST["importeFijoOtrosConceptos"];
	$provisionFondos = $_POST["provisionFondos"];
	$pfFijaImporte = $_POST["pfFijaImporte"];	
	$cobroUnitarioEnvio = $_POST["cobroUnitarioEnvio"]==""?0:$_POST["cobroUnitarioEnvio"];
	$envAtt = $_POST["envAtt"];
	$envNombre = $_POST["envNombre"];
	$envDireccion = $_POST["envDireccion"];
	$envCp = $_POST["envCp"];
	$envPoblacion = $_POST["envPoblacion"];
	$envProvincia = $_POST["envProvincia"];
	$envPais = $_POST["envPais"];
	$pedidoCliente = $_POST["pedidoCliente"];
	$vencimiento = $_POST["vencimiento"];
	
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
	
	
	if ($provisionFondos==="")
		$provisionFondos = 0;
	
	
	if ($_POST["sinIva"]==="true")
		$sinIva = 1;
	else
		$sinIva = 0;
	
	if ($_POST["retener"]==="true")
		$retener = 1;
	else
		$retener = 0;
	
	
	if ($_POST["prefactura"]==="true")
		$prefactura = 1;
	else
		$prefactura = 0;

	
	if ($_POST["noAplicarPF"]==="true")
		$noAplicarPF = 1;
	else
		$noAplicarPF = 0;


	if ($_POST["retencion"]==="true")
		$retencion = 1;
	else
		$retencion = 0;
		

		
	
	
	
	if ($pfFijaImporte==="")
		$pfFijaImporte=0;
	
	
	$clayma = $_POST["clayma"];
	
	if ($clayma=="true")
	{
		$datos=crearClienteNuevoClayma($conexion,$codigoSaldo,$subCliente,$nombreEmpresa,$nif,$nifSubCliente,$direccion,$localidad,$provincia,$cp,$nombreFranqueo,$comercial,$diasApagar,$formaPago,$EmailFactura,$cuotaRecogida,$periodoFacturacion,$prodNoBon,$otrosConceptos,$importeFijoOtrosConceptos,$provisionFondos,$cobroUnitarioEnvio,$envAtt,$envNombre,$envDireccion,$envCp,$envPoblacion,$envProvincia,$envPais,$numCuenta,$correoDiario,$activo,$pfFijaImporte,$domiciliado, $nuestraCuenta,$sinIva,$retener,$pedidoCliente,$vencimiento,$prefactura,$noAplicarPF,$retencion,$pais,$codigoPais);
	}
	else
	{
		$datos=crearClienteNuevo($conexion,$codigoSaldo,$subCliente,$nombreEmpresa,$nif,$nifSubCliente,$direccion,$localidad,$provincia,$cp,$nombreFranqueo,$comercial,$diasApagar,$formaPago,$EmailFactura,$cuotaRecogida,$periodoFacturacion,$prodNoBon,$otrosConceptos,$importeFijoOtrosConceptos,$provisionFondos,$cobroUnitarioEnvio,$envAtt,$envNombre,$envDireccion,$envCp,$envPoblacion,$envProvincia,$envPais,$numCuenta,$correoDiario,$activo,$pfFijaImporte,$domiciliado, $nuestraCuenta,$sinIva,$retener,$pedidoCliente,$vencimiento,$prefactura,$noAplicarPF,$retencion,$pais,$codigoPais);
	}
	
	
		
	
	
	
	
	
	
		echo json_encode($datos);
	
	
	
	
}

?>