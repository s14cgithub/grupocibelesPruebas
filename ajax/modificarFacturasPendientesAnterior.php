<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificarFacturasPendientes")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	
	$numFactura = $_POST["factura"];
	$formaPago = $_POST["formaPago"];
	$origen = $_POST["origen"];
	$anio = $_POST["anio"];	
	$importe=$_POST["importe"];
	$cliente = $_POST["cliente"];	
	
	
	if ($anio=="2021" && $origen=="CIBELES")
	{
		$resultado = modificarFacturasPendientesAnteriores($conexion, $numFactura, $formaPago, $anio);
	}
	else if ($anio=="2021" && $origen=="CIBELES - CD")
	{
		$resultado = modificarFacturasPendientesAnterioresCD($conexion, $numFactura, $formaPago, $anio);
		
		$fecha = date("d-m-Y");
		
		$resultado2 = verCodigoSaldoPorNombreSubCliente($conexion, $cliente);
		$idClienteSaldo = $resultado2[0]["codigo_saldo"];
		
		$importe=str_replace('.','',$importe);
		$importe=str_replace(',','.',$importe);		
		
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$idClienteSaldo);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='';
		$fechaCuadre='';
		
		$presupuesto=$numFactura;		
		insertarMovimientoPF($conexion,$idClienteSaldo,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo,0,$anio);
		
		echo modificarDatosPFenCliente($conexion,$idClienteSaldo,$fecha,$importe);		
		
	}
	else if ($anio=="2021" && $origen=="CLAYMA")
	{
		$resultado = modificarFacturasPendientesAnterioresClayma($conexion, $numFactura, $formaPago, $anio);
	}
	else if ($anio=="2020" && $origen=="CIBELES")
	{
		$resultado = modificarFacturasPendientesAnteriores($conexion, $numFactura, $formaPago, $anio);
	}
	else if ($anio=="2020" && $origen=="CIBELES - CD")
	{
		$resultado = modificarFacturasPendientesAnterioresCD($conexion, $numFactura, $formaPago, $anio);
		
		$fecha = date("d-m-Y");
		
		$resultado2 = verCodigoSaldoPorNombreSubCliente($conexion, $cliente);
		$idClienteSaldo = $resultado2[0]["codigo_saldo"];		
		
		$importe=str_replace('.','',$importe);
		$importe=str_replace(',','.',$importe);		
		
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$idClienteSaldo);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='';
		$fechaCuadre='';
		
		$presupuesto=$numFactura;		
		insertarMovimientoPF($conexion,$idClienteSaldo,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo,0,$anio);
		
		echo modificarDatosPFenCliente($conexion,$idClienteSaldo,$fecha,$importe);		
		
	}
	
	else if ($anio=="2020" && $origen=="CLAYMA")
	{		
		$resultado = modificarFacturasPendientesAnterioresClayma($conexion, $numFactura, $formaPago, $anio);
	}
	else if ($anio=="2019" && $origen=="CIBELES")
	{
		$resultado = modificarFacturasPendientesAnteriores($conexion, $numFactura, $formaPago, $anio);
	}
	else if ($anio=="2019" && $origen=="CIBELES - CD")
	{
		$resultado = modificarFacturasPendientesAnterioresCD($conexion, $numFactura, $formaPago, $anio);
		
		$fecha = date("d-m-Y");
		
		$resultado2 = verCodigoSaldoPorNombreSubCliente($conexion, $cliente);
		$idClienteSaldo = $resultado2[0]["codigo_saldo"];		
		
		$importe=str_replace('.','',$importe);
		$importe=str_replace(',','.',$importe);		
		
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$idClienteSaldo);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='';
		$fechaCuadre='';
		
		$presupuesto=$numFactura;		
		insertarMovimientoPF($conexion,$idClienteSaldo,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo,0,$anio);
		
		echo modificarDatosPFenCliente($conexion,$idClienteSaldo,$fecha,$importe);		
		
	}
	
	else if ($anio=="2019" && $origen=="CLAYMA")
	{		
		$resultado = modificarFacturasPendientesAnterioresClayma($conexion, $numFactura, $formaPago, $anio);
	}
	
	else if ($anio=="2018" && $origen=="CIBELES")
	{
		$resultado = modificarFacturasPendientesAnteriores($conexion, $numFactura, $formaPago, $anio);
	}
	else if ($anio=="2018" && $origen=="CIBELES - CD")
	{
		$resultado = modificarFacturasPendientesAnterioresCD($conexion, $numFactura, $formaPago, $anio);
		
		$fecha = date("d-m-Y");
		
		$resultado2 = verCodigoSaldoPorNombreSubCliente($conexion, $cliente);
		$idClienteSaldo = $resultado2[0]["codigo_saldo"];		
		
		$importe=str_replace('.','',$importe);
		$importe=str_replace(',','.',$importe);		
		
		$saldo = cargarClientes($conexion," where codigo_saldo = ".$idClienteSaldo);
		$nuevoSaldo = $saldo[0]["importePF"] + $importe;	
		$informacionCuadre='';
		$fechaCuadre='';
		
		$presupuesto=$numFactura;		
		insertarMovimientoPF($conexion,$idClienteSaldo,$fecha,$formaPago,$importe,$presupuesto,$fechaCuadre,$informacionCuadre,$nuevoSaldo,0,$anio);
		
		echo modificarDatosPFenCliente($conexion,$idClienteSaldo,$fecha,$importe);		
		
	}
	
	else if ($anio=="2018" && $origen=="CLAYMA")
	{		
		$resultado = modificarFacturasPendientesAnterioresClayma($conexion, $numFactura, $formaPago, $anio);
	}
	
	
	
	
}


?>