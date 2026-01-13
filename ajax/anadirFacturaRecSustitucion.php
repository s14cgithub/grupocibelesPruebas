<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="anadirFacturaRecSustitucion")
{
	
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	$motivo = $_POST["motivo"];
	$facturaOriginal = $_POST["facturaOriginal"];
	$fechaFacturaOriginal = $_POST["fechaFacturaOriginal"];
	$idClienteOriginal = $_POST["idClienteOriginal"];
	
	$cliente = $_POST["nombreCliente"];
	$codigoCliente = $_POST["idCliente"];
	$clayma = $_POST["clayma"];

	$clayma1=0;
	if ($clayma=="true")
		$clayma1=1;
	
	$detallada = $_POST["detallada"];

	$detallada1=0;
	if ($detallada=="true")
		$detallada1=1;

	$pedidoCliente = $_POST["pedidoCliente"];
	$presupuesto = $_POST["presupuesto"];
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
	
	$fechaActual = date('Y-m-d');

	$datosOrigenFactura = explode('/',$facturaOriginal);  //RECT 6     y    25
	$anioFacturaOriginalDosDigitos = $datosOrigenFactura[1];//25
	$anioFacturaOriginal = $anioFacturaOriginalDosDigitos+2000; //2025
	$datosOrigenFactura1 = explode(' ',$datosOrigenFactura[0]); //RECT 6 
	$tipoFacturaOriginal = $datosOrigenFactura1[0]; //RECT
	$soloNumeroFacOriginal = $datosOrigenFactura1[1]; //6

	/////////PROVISION DE FONDO//////////////

	/*
	$resultado2 = provisionDeFondo_verSumatorioPorPresupuesto($conexion,$clayma1,$soloNumeroFacOriginal,$anioFacturaOriginal,$facturaOriginal);
	
	$provisionDeFondoArecuperar = $resultado2[0]["importeTotal"];

	if ($clayma=="true")
	{
		modificarDatosPFenClienteClayma($conexion,$idClienteOriginal,$fechaActual,$provisionDeFondoArecuperar);
		$resultado3 = cargarClientesClayma($conexion, " where codigo = ".$idClienteOriginal);
	}
	else
	{
		modificarDatosPFenCliente($conexion,$idClienteOriginal,$fechaActual,$provisionDeFondoArecuperar);
		$resultado3 = cargarClientes($conexion, " where codigo = ".$idClienteOriginal);
	}
	
	echo "\nresultado3: ".count($resultado3);
	$saldoActual = $resultado3[0]["importePF"];
	if ($saldoActual=="" || $saldoActual ==NULL)
	{
		$saldoActual=0;
	}
	echo "\nsaldo: ".$saldoActual;
	echo "\nempresa: ".$resultado3[0]["nombre_empresa"];
	
	$informacionCuadre = "Movimiento por factura de sustitucion";
	echo insertarMovimientoPF ($conexion,$idClienteOriginal,$fechaActual,'',$provisionDeFondoArecuperar,$facturaOriginal,'',$informacionCuadre,$saldoActual,$clayma1, 0);
	*/


	echo anularProvisionFondoAplicadaAFactura ($conexion,$facturaOriginal);



	



	
	//se mira el numero de presupuesto de la factura original. Si esta vacio es porque no hay provision de fondo en el factura original




	
	



//Se borrar en la provision de fondo la formaPago, numFacturaAplicada, numfacturaAplicadaAnio



	////////////////////////////////////////



	
	
	$descripcion = log_creacion;
	$tabla = facturasRecSustitutiva_tabla;
	$columna = "todas";
	//$idRegistro = $presupuesto;
	$idRegistro = 0;
	$usuario = $_SESSION['usuario'];
	
	$datosAntiguos = "";
	$datosNuevos = "Cliente: " . $cliente ."|CodigoCliente: ". $codigoCliente . "|Clayma: " . $clayma . "|Neto: ".$neto. "|Iva: ".$iva. "|Irpf: ".$irpf."|Total: ".$total."|Provision: ".$provision."|aPagar: ".$aPagar."|facturaOriginal: ".$facturaOriginal."|fechaFacturaOriginal: ".$fechaFacturaOriginal;
	//echo "clayma: ".$clayma;	
	$inicial = "";
	
	
	$prefactura =0;
	
	if ($clayma=="true") 
	{	
		
		$resultado=verEstadoFacturacionFinMesClayma($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("Y-m-d");
		}
		else
		{
			$fecha = date('Y-m-d');
		}
		
		

		if (trim($tipoFacturaOriginal)=="FAC") //se indica que la factura original tiene una factura rectificativa sustitutiva
        {
            modificarFactura_facturaRecSustClayma($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="RECT")
        {
            modificarFacturaRec_facturaRecSustClayma($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
		else if (trim($tipoFacturaOriginal)=="SUST")
        {
            modificarFacturaRecSust_facturaReSustClayma($conexion,$facturaOriginal,$anioFacturaOriginal);
        }

		///////////////////////////////

		$anioSeleccionado = substr($fecha, 0,4);
		$resultado2 =  insertarFacturaRecSustClayma($conexion,$motivo,$facturaOriginal, $fechaFacturaOriginal,$cliente,$codigoCliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada1,$neto,$iva,$irpf,$total,$provision,$aPagar,$anioSeleccionado,0);
		if (count($resultado2)<=0) 
		{
			echo  json_encode("");
		}	
		else
		{
			$numFactura  = $resultado2["data"][0]["numero"];
			$numeroFacturaCompleto = $resultado2["data"][0]["numeroFacturaCompleto"];
			$anioSeleccionado    = $resultado2["data"][0]["anio"];

			modificarProvisionNumFacturaPorPresupuesto($conexion,$presupuesto,$numFactura,$anioSeleccionado,$numeroFacturaCompleto);
			
			echo  json_encode($resultado2);
		}
		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$facturaOriginal,1);	
	
	}	
	else
	{
		
		$resultado=verEstadoFacturacionFinMes($conexion);
		if ($resultado[0]["activado"]=="0")
		{
			$fecha = $resultado[0]["fechaImprimir"]->format("Y-m-d");
		}
		else
		{
			$fecha = date('Y-m-d');
		}
		/////////////////////////
		
      

        //echo ("\ntipoFacOriginal".$tipoFacturaOriginal);

		if (trim($tipoFacturaOriginal)=="FAC")
        {
			//echo ("\nEntra en Fac");
            modificarFactura_facturaRecSust($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
        else if (trim($tipoFacturaOriginal)=="RECT")
        {  //echo ("\nEntra en Rect");
            modificarFacturaRec_facturaRecSust($conexion,$facturaOriginal,$anioFacturaOriginal);
        }
		else if (trim($tipoFacturaOriginal)=="SUST")
        {
			//echo ("\nEntra en Sust");
            modificarFacturaRecSust_facturaRecSust($conexion,$facturaOriginal,$anioFacturaOriginal);
        }

		///////////////////////////////

		$anioSeleccionado = substr($fecha, 0,4); 
		$resultado2 =  insertarFacturaRecSust($conexion,$motivo,$facturaOriginal, $fechaFacturaOriginal,$cliente,$codigoCliente,$fecha,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada1,$neto,$iva,$irpf,$total,$provision,$aPagar,$anioSeleccionado,0);
		if (count($resultado2)<=0) 
		{
			echo  json_encode("");
		}	
		else
		{
			$numFactura  = $resultado2["data"][0]["numero"];
			$numeroFacturaCompleto = $resultado2["data"][0]["numeroFacturaCompleto"];
			$anioSeleccionado    = $resultado2["data"][0]["anio"];

			modificarProvisionNumFacturaPorPresupuesto($conexion,$presupuesto,$numFactura,$anioSeleccionado,$numeroFacturaCompleto);	
			
			echo  json_encode($resultado2);
		}

		insertarRegistro ($conexion, $usuario, $descripcion, $datosAntiguos, $datosNuevos, $tabla,$columna, $idRegistro,$facturaOriginal,0);	
	}
			
	
	
	
	
	
	
	
}

?>