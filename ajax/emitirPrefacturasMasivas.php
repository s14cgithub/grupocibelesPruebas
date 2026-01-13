<?php 

if(isset($_POST["accion"])&&$_POST["accion"]=="prefacturasMasivas")
{
	//echo "<br>0.1<br>";
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
		
	
	
	$fecha = $_POST["fecha"]; 
	$clayma = $_POST["clayma"];
	


	$clayma1=0;
	if ($clayma == "true")
	{
		$clayma1=1;
	}


	$fechaFin = date("d-m-Y", strtotime($fecha."+ 1 days"));


	$condicion =  " where tabla.fechaTerminado < '".$fechaFin."'";
	$condicion .=  " order by fechaTerminado, presupuesto";

	

	$presupuestoAfacturar = mostrarFacturasSinEmitir($conexion, $clayma1,$condicion);

	

	foreach ($presupuestoAfacturar as $registroPresupuesto)
	{	
		$numPresupuesto = $registroPresupuesto["presupuesto"];
		$nombreEmpresa = $registroPresupuesto["cliente"];

		$provisionDeFondo =  verProvisionDeFondoPorPresupuesto($conexion,$numPresupuesto);

		$provisionDeFondo_importe = 0.00;

		foreach ($provisionDeFondo as $registroProvisionDeFondo) //se suma el importe de las provisiones de fondo
		{
			$provisionDeFondo_importe += $registroProvisionDeFondo["importe"];
		}


		if ($clayma == "true")
		{
			$datosCliente = cargarClientesClayma($conexion," where nombre_empresa='".$nombreEmpresa."' and codigo = codigo_saldo");			
		}
		else
		{			
			$datosCliente = cargarClientes($conexion," where nombre_empresa='".$nombreEmpresa."' and codigo = codigo_saldo");
		}

		

		if(count($datosCliente)>0)
		{

			$texto = $numPresupuesto;
			$queBusca=presupuesto_Presupuesto;
			$orden=1;
			$desc="";	
			
			if ($clayma == "true") //se recoge los datos del presupuesto
			{
				$datosPresupuestos = mostrarPresupuestosClayma($conexion,$orden,$desc,$texto,$queBusca);				
			}
			else
			{			
				$datosPresupuestos = mostrarPresupuestos($conexion,$orden,$desc,$texto,$queBusca);	
			}

			$codigo_saldo = $datosCliente[0]["codigo_saldo"];
			$pedidoCliente = $datosPresupuestos[0]["pedcli"];

			$cantidad = $datosPresupuestos[0]["cantidad2"];
			if ($cantidad<=0 || $cantidad=='' || $cantidad=='NULL' )
			{
				$cantidad =  $datosPresupuestos[0]["cantidad"];
			}

			$inicialComercial = $datosPresupuestos[0]["inicialComercial"];
			$formaPago = $datosPresupuestos[0]["textoFormaPago"];
			$numCuenta = $datosCliente[0]["nuestraCuenta"];
			$campana = $datosPresupuestos[0]["campana"];
			$detallada = 1;
			$prefactura = 1;

			/////////////////////////////////////
			
			$iva = 0;
			$irpf = 0;
			$total = 0 ;
			$aPagar = 0;
			$neto =0;
			/////////////////////////////////////////////


			
			if ($clayma == "true")
			{	//echo "entra";
				$resultado=verEstadoFacturacionFinMesClayma($conexion);
				if ($resultado[0]["activado"]=="0")
				{
					$fechaFacturacion = $resultado[0]["fechaImprimir"]->format("d/m/Y");
				}
				else
				{
					$fechaFacturacion = date('d/m/Y');
				}
				
				$anioSeleccionado = substr($fechaFacturacion, -4); 
				
				$resultadoFactura =  insertarFacturaClayma($conexion,$numPresupuesto,$nombreEmpresa,$fechaFacturacion,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$irpf,$total,$provisionDeFondo_importe,$aPagar, $inicialComercial,$anioSeleccionado,0,$prefactura);
				
				if ($resultadoFactura == "Factura Grabada")
				{				
					$numFactura=mostrarNumFacturaPorPresupuestoClayma($conexion,$numPresupuesto);
					
					$numeroDeFactura = $numFactura[0]["numero"];
					$numeroFacturaCompleto = $numFactura[0]["numeroFacturaCompleto"]; 

					echo copiarPresupuestoDetalleAAFacturaDetalleClayma($conexion, $numeroDeFactura,$numPresupuesto,$anioSeleccionado);
					
					
					
					$totalNeto=0.00;
					$totalNetoIVAincluido=0.00;
					$irpf=0.00;
					$iva=0.00;
					
					//$resultadoDetallesPresupuesto = cargarDetallesPresupuesto($conexion,$numPresupuesto,$orden="ninguno");
					$resultadoDetallesPresupuesto = verFacturaDetallePresupuestoClayma2($conexion,$numeroDeFactura,$anioSeleccionado);
				                                 	
					foreach ($resultadoDetallesPresupuesto as $RegistroResultadoDetallesPresupuesto)
					{						

						$totalNeto += $RegistroResultadoDetallesPresupuesto["total"];

						if ($RegistroResultadoDetallesPresupuesto["exentoIVA"]!=1 && $RegistroResultadoDetallesPresupuesto["exentoIVA"]!="1")
						{
							$totalNetoIVAincluido += $RegistroResultadoDetallesPresupuesto["total"];
						}
					}

					$iva = $totalNetoIVAincluido*0.21;
					$irpf = $totalNeto*0.19*-1;

					if ($datosCliente[0]["sinIva"]==1)
					{ 
						$iva=0;
					}
					if ($datosCliente[0]["retencion"]!=1)
					{ 
						$irpf = 0;
					}

					//echo "\nFactura: ".$numeroDeFactura." Total: ".$total. " totalNeto: ".$totalNeto ." iva: ".$iva. "\n";
					

					$total = ($totalNeto + $iva + $irpf);
					$aPagar = $totalNeto + $iva + $irpf - $provisionDeFondo_importe;
				
					echo modificarPreciosFacturaClaymaMasivo($conexion, $numeroDeFactura, $anioSeleccionado, $totalNetoIVAincluido, $iva,$irpf,$total,$aPagar,$provisionDeFondo_importe);
					
					modificarProvisionNumFacturaPorPresupuesto($conexion,$numPresupuesto,$numeroDeFactura,$anioSeleccionado,$numeroFacturaCompleto);
				}
			} 
			else //CIBELES
			{
				$resultado=verEstadoFacturacionFinMes($conexion);
				if ($resultado[0]["activado"]=="0")
				{
					$fechaFacturacion = $resultado[0]["fechaImprimir"]->format("d/m/Y");
				}
				else
				{
					$fechaFacturacion = date('d/m/Y');
				}
				
				$anioSeleccionado = substr($fechaFacturacion, -4); 
		
				$resultadoFactura =  insertarFactura($conexion,$numPresupuesto,$nombreEmpresa,$fechaFacturacion,$pedidoCliente,$cantidad,$formaPago,$numCuenta,$campana,$detallada,$neto,$iva,$irpf,$total,$provisionDeFondo_importe,$aPagar, $inicialComercial,$anioSeleccionado,0,$prefactura);
				//echo "Error Resultado de factura: ".$resultadoFactura."  aaaa";
				if ($resultadoFactura == "Factura Grabada")
				{ //echo "entra 2";
					$numFactura=mostrarNumFacturaPorPresupuesto($conexion,$numPresupuesto);
					$numeroDeFactura = $numFactura[0]["numero"];
					$numeroFacturaCompleto = $numFactura[0]["numeroFacturaCompleto"]; 

					echo copiarPresupuestoDetalleAAFacturaDetalle($conexion, $numeroDeFactura,$numPresupuesto,$anioSeleccionado);


					$totalNeto=0.00;
					$totalNetoIVAincluido=0.00;
					$irpf=0.00;
					$iva=0.00;
					
					//$resultadoDetallesPresupuesto = cargarDetallesPresupuesto($conexion,$numPresupuesto,$orden="ninguno");
					$resultadoDetallesPresupuesto = verFacturaDetallePresupuesto2($conexion,$numeroDeFactura,$anioSeleccionado);

					foreach ($resultadoDetallesPresupuesto as $RegistroResultadoDetallesPresupuesto)
					{
						//$totalNeto += number_format($RegistroResultadoDetallesPresupuesto["exentoIVA"]."<br>");
						
						$totalNeto += $RegistroResultadoDetallesPresupuesto["total"];
						
						//echo "entra0: ".$RegistroResultadoDetallesPresupuesto["precio"]."<br>";
						if ($RegistroResultadoDetallesPresupuesto["exentoIVA"]!=1 && $RegistroResultadoDetallesPresupuesto["exentoIVA"]!="1")
						{							
								$totalNetoIVAincluido += $RegistroResultadoDetallesPresupuesto["total"];
						}
					}

					$iva = $totalNetoIVAincluido*0.21;
					$irpf = $totalNeto*0.19*-1;

					if ($datosCliente[0]["sinIva"]==1)
					{ 
						$iva=0;
					}
					if ($datosCliente[0]["retencion"]!=1)
					{ 
						$irpf = 0;
					}

					$total = $totalNeto + $iva + $irpf;
					$aPagar = $totalNeto + $iva + $irpf - $provisionDeFondo_importe;


					echo modificarPreciosFacturaMasivo($conexion, $numeroDeFactura, $anioSeleccionado, $totalNetoIVAincluido, $iva,$irpf,$total,$aPagar,$provisionDeFondo_importe);

					modificarProvisionNumFacturaPorPresupuesto($conexion,$numPresupuesto,$numeroDeFactura,$anioSeleccionado,$numeroFacturaCompleto);


				}
			
			}


	
			
		}


	}




/*
	if (count($presupuestoAfacturar)<=0)
	{
		echo json_encode("");		
	}
	else
	{
		echo json_encode($presupuestoAfacturar);
	}
	
	*/		
	
}


?>
