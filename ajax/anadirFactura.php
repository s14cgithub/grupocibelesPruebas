<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="anadirFactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["presupuesto"];
	
	$cliente1 = $_POST["cliente"];
	
	$cliente="";
	$idCliente = 0;

	$valores = explode(" - ", $cliente1);	
	
	
	$contador=0;
	while ($contador<count($valores)-1)
	{
		$cliente = $cliente.$valores[$contador]." - ";
		$contador++;
	}
	
	$cliente = substr($cliente,0,strlen($cliente)-3);
	
	$valores = explode(" - ", $cliente1);	
	$codigoCliente = array_pop($valores);   // lo que hay después del último " - "
	
	$clayma = $_POST["clayma"];
	
	//$fecha = $_POST["fecha"];
	
	$pedidoCliente = $_POST["pedidoCliente"];	
	$cantidad = $_POST["cantidad"];	
	$formaPago = $_POST["formaPago"];
	$numCuenta = $_POST["numCuenta"];	
	
	$campana = $_POST["campana"];
	$detallada = $_POST["detallada"];
	
	$neto = $_POST["neto"];
	$iva = $_POST["iva"];
	$irpf = $_POST["irpf"];	
	$total = $_POST["total"];
	$provision = $_POST["provision"];
	$aPagar = $_POST["aPagar"];
	$inicial = $_POST["inicial"];
	$prefactura = $_POST["prefactura"];
	
	
	if ($neto==""||$neto==null)
	{
		$neto=0;
	}
	if ($iva==""||$iva==null)
	{
		$iva=0;
	}
	if ($total==""||$total==null)
	{
		$total=0;
	}
	if ($provision==""||$provision==null)
	{
		$provision=0;
	}
	if ($aPagar==""||$aPagar==null)
	{
		$aPagar=0;
	}

	
	
	
	
	
	
	
	
	
	$descripcion = log_creacion;
	$tabla = facturas_tabla;
	$columna = "todas";
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	
	$datosAntiguos = "";
	$datosNuevos = "Cliente: " . $cliente ."|CodigoCliente: ". $codigoCliente . "|Clayma: " . $clayma . "|Neto: ".$neto. "|Iva: ".$iva. "|Irpf: ".$irpf."|Total: ".$total."|Provision: ".$provision."|aPagar: ".$aPagar." Prefactura: ".$prefactura;
		
	
	
	
	
	
	if ($clayma=="true")
	{	
		
		$resultado=verEstadoFacturacionFinMesClayma($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("d/m/Y");
		}
		else
		{
			$fecha = date('d/m/Y');
		}
		
		$anioSeleccionado = substr($fecha, -4); 
		
		echo insertarFacturaClayma($conexion,$presupuesto,$cliente,$codigoCliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$irpf,$total,$provision,$aPagar, $inicial,$anioSeleccionado,0,$prefactura);
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,1);	
	}	
	else
	{
		
		$resultado=verEstadoFacturacionFinMes($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("d/m/Y");
		}
		else
		{
			$fecha = date('d/m/Y');
		}
		
		$anioSeleccionado = substr($fecha, -4); 
		
		echo insertarFactura($conexion,$presupuesto,$cliente,$codigoCliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$irpf,$total,$provision,$aPagar, $inicial,$anioSeleccionado,0,$prefactura);
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,0);	
	}
			
	
	
	
	
	
	
	
}

?>