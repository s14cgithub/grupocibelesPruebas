<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="anadirFactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["presupuesto"];
	
	$cliente = $_POST["cliente"];
	//$fecha = $_POST["fecha"];
	$pedidoCliente = $_POST["pedidoCliente"];	
	$cantidad = $_POST["cantidad"];	
	$formaPago = $_POST["formaPago"];
	$numCuenta = $_POST["numCuenta"];	
	
	$campana = $_POST["campana"];
	$detallada = $_POST["detallada"];
	
	$neto = $_POST["neto"];
	$iva = $_POST["iva"];
	$total = $_POST["total"];
	$provision = $_POST["provision"];
	$aPagar = $_POST["aPagar"];
	$inicial = $_POST["inicial"];
	
	
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
	$datosNuevos = "Cliente: " . $cliente . "|Neto: ".$neto. "|Iva: ".$iva."|Total: ".$total."|Provision: ".$provision."|aPagar: ".$aPagar;
		
	insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto);	
	
	
		
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
	
	$resultado1 =  insertarFactura2($conexion,$presupuesto,$cliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$total,$provision,$aPagar, $inicial,$anioSeleccionado);
	
	$prueba = $resultado1;
	echo $prueba."||||".$anioSeleccionado; 
	
	//echo $resultado1[0]["numeroFactura"]."||||".$anioSeleccionado; 
	//echo $resultado1; 
	
	
	/*if (count($resultado1)<=0)
	{
		echo json_encode("");
		//echo ("Error2: No hay subprocesos para mostrar: ");
	}
	else
	{
		echo json_encode($resultado1);
	}*/

	
	
	
	
}

?>