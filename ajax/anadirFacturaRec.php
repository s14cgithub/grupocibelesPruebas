<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="anadirFactura")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$motivo = $_POST["motivo"];
	$facturaOriginal = $_POST["facturaOriginal"];
	
	$cliente = $_POST["nombreCliente"];
	$codigoCliente = $_POST["idCliente"];
	$clayma = $_POST["clayma"];
	$detallada = $_POST["detallada"];
	$pedidoCliente = $_POST["pedidoCliente"];
	$cantidad = $_POST["cantidad"];	
	$formaPago = $_POST["formaPago"];
	$numCuenta = $_POST["numCuenta"];	
	$campana = $_POST["campana"];

	
	$neto = $_POST["neto"];
	$iva = $_POST["iva"];
	$irpf = $_POST["irpf"];	
	$total = $_POST["total"];
	$provision = $_POST["provision"];
	$aPagar = $_POST["aPagar"];
	//$inicial = $_POST["inicial"];
	//$prefactura = $_POST["prefactura"];
	
	
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
	$tabla = facturasRec_tabla;
	$columna = "todas";
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	
	$datosAntiguos = "";
	$datosNuevos = "Cliente: " . $cliente ."|CodigoCliente: ". $codigoCliente . "|Clayma: " . $clayma . "|Neto: ".$neto. "|Iva: ".$iva. "|Irpf: ".$irpf."|Total: ".$total."|Provision: ".$provision."|aPagar: ".$aPagar." facturaOriginal: ".$facturaOriginal;
	//echo "clayma: ".$clayma;	
	$inicial = "";
	
	
	$prefactura =0;
	
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
		
		$datosOrigenFactura = explode('/',$facturaOriginal);  //RECT 6     y    25
        $anioFacturaOriginalDosDigitos = $datosOrigenFactura[1];//25
        $anioFacturaOriginal = $anioFacturaOriginalDosDigitos+2000; //2025
		$datosOrigenFactura1 = explode(' ',$datosOrigenFactura[0]); //RECT 6 
        $tipoFacturaOriginal = $datosOrigenFactura1[0]; //RECT

		if (trim($tipoFacturaOriginal)=="FAC")
        {
            modificarFactura_facturaRecClayma($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="RECT")
        {
            modificarFacturaRec_facturaRecClayma($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
		else if (trim($tipoFacturaOriginal)=="SUST")
        {
            modificarFacturaRecSust_facturaRecClayma($conexion,$facturaOriginal,$anioFacturaOriginal);
        }

		///////////////////////////////

		$anioSeleccionado = substr($fecha, -4); 
		$resultado2 =  insertarFacturaRecClayma($conexion,$motivo,$facturaOriginal,$cliente,$codigoCliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$irpf,$total,$provision,$aPagar,$anioSeleccionado,0);
		if (count($resultado2)<=0) 
		{
			echo  json_encode("");
		}	
		else
		{
			echo  json_encode($resultado2);
		}
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$facturaOriginal,1);	
	
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
		/////////////////////////
		
      

        $datosOrigenFactura = explode('/',$facturaOriginal);  //RECT 6     y    25
        $anioFacturaOriginalDosDigitos = $datosOrigenFactura[1];//25
        $anioFacturaOriginal = $anioFacturaOriginalDosDigitos+2000; //2025
		$datosOrigenFactura1 = explode(' ',$datosOrigenFactura[0]); //RECT 6 
        $tipoFacturaOriginal = $datosOrigenFactura1[0]; //RECT

		if (trim($tipoFacturaOriginal)=="FAC")
        {
            modificarFactura_facturaRec($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="RECT")
        {
            modificarFacturaRec_facturaRec($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
		else if (trim($tipoFacturaOriginal)=="SUST")
        {			
            modificarFacturaRecSust_facturaRec($conexion,$facturaOriginal,$anioFacturaOriginal);
        }

		///////////////////////////////

		$anioSeleccionado = substr($fecha, -4); 
		$resultado2 =  insertarFacturaRec($conexion,$motivo,$facturaOriginal,$cliente,$codigoCliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$irpf,$total,$provision,$aPagar,$anioSeleccionado,0);
		if (count($resultado2)<=0) 
		{
			echo  json_encode("");
		}	
		else
		{
			echo  json_encode($resultado2);
		}
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$facturaOriginal,0);	
	}
			
	
	
	
	
	
	
	
}

?>