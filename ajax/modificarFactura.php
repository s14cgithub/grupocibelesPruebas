<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="modificar348!Fac")
{
	$ruta = '../';
	//require($ruta.$rutaCabecera);
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	session_start(); 
	
	
	$numFactura = $_POST["numFactura"];
	$anioSeleccionado = $_POST["anioSeleccionado"];
	$clayma = $_POST["clayma"];
	
	
	
	//$idEmpleado = $_SESSION["idEmpleado"];
	//$numPresupuesto=$_POST["numPresupuesto"];
	$clayma=$_POST["clayma"];
	
	//$idCliente=$_POST["idCliente"];
	$pedido=$_POST["pedido"];
	$cantidad=$_POST["cantidad"];
	$formaPago=$_POST["formaPago"];
	$campana=$_POST["campana"];
	$detallada=$_POST["detallada"];
	$neto=$_POST["neto"];
	$iva=$_POST["iva"];
	$irpf=$_POST["irpf"];
	$total=$_POST["total"];
	//$provision=$_POST["provision"];
	$aPagar=$_POST["aPagar"];
	
	

	$clayma1=0;
	if ($clayma=="true")
	{
		$clayma1=1;
	}


	
	$detallada1=0;
	if ($detallada=="true")
	{
		$detallada1=1;
	}
	
	
	echo modificarFactura($conexion,$numFactura,$anioSeleccionado,$clayma1,$pedido,$cantidad,$formaPago,$campana,$detallada1,$neto,$iva,$irpf,$total,$aPagar);
		
}

?>
