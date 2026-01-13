<?php 
$cliente1 = "IMPRENTA CRUCES,  S.A. - 125";
echo chr(32);
echo $cliente1;
echo "<br>";
echo str_replace(" ",chr(32),$cliente1);
echo "<br>";
echo 0;



$cliente="";
$valores = explode(" - ", $cliente1);
$contador=0;

while ($contador<count($valores)-1)
{
	$cliente = $cliente.$valores[$contador]." - ";
	$contador++;
}

$cliente = substr($cliente,0,strlen($cliente)-3);

echo "<br>";
echo $cliente;






if(isset($_POST["accion"])&&$_POST["accion"]=="anadirFactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$presupuesto = $_POST["presupuesto"];
	
	$cliente1 = $_POST["cliente"];
	
	$cliente="";
	$valores = explode(" - ", $cliente1);
	$contador=0;
	
	while ($contador<count($valores)-1)
	{
		$cliente = $cliente.$valores[$contador]." - ";
		$contador++;
	}
	
	$cliente = substr($cliente,0,strlen($cliente)-3);
	
	
	$clayma = $_POST["clayma"];
	
	//$fecha = $_POST["fecha"];
	$fecha = date('d/m/Y');
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
	$datosNuevos = "Cliente: " . $cliente ."Clayma: " . $clayma . "|Neto: ".$neto. "|Iva: ".$iva."|Total: ".$total."|Provision: ".$provision."|aPagar: ".$aPagar;
		
	
	
	
	
	
	if ($clayma=="true")
	{		
		echo insertarFacturaClayma($conexion,$presupuesto,$cliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$total,$provision,$aPagar, $inicial);
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,1);	
	}	
	else
	{
		echo insertarFactura($conexion,$presupuesto,$cliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$total,$provision,$aPagar, $inicial);
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$presupuesto,0);	
	}
			
	
	
	
	
	
	
	
}

?>