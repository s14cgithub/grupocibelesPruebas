

<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarFacturas")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	$clayma = $_POST["clayma"];
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];

	$campos = [
			'numero',
			'nif',
			'cliente',
			'precioTotal',
			'fecha',
			'abono',
			'origenFactura',
			'numeroFacturaCompleto',
			'precioNeto',
			'iva',
			'tipoIva',
			'idCodigoCliente'		
		];

		$joins = array('tabla2');

		$filtros = array();


		$fechaInicio = date("d-m-Y", strtotime($fechaInicio));
		$fechaFin = date("d-m-Y", strtotime($fechaFin));

		$filtrosOperadores = array(
				array(
					'campo1' => 'fecha',
					'operador' => '>=',
					'valor' => $fechaInicio
				),
				array(
					'campo1' => 'fecha',
					'operador' => '<=',
					'valor' => $fechaFin
				)
			);
			
		$order = array(
			array('campo' => 'numero', 'dir' => 'ASC')
		);

	if ($clayma=="true")
	{
		$resultado = mostrarFacturacionClayma($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);
	}
	else
	{
		$resultado = mostrarFacturacion($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);
	}
	
	echo json_encode($resultado);
	sqlsrv_close($conn);
	
	
	$fp = fopen("../archivosDescargas/exportarFacturas.txt", "w");
	
	fwrite($fp,"Asiento|CodigoCuenta|CargoAbono|CifDni|Nombre|ImporteAsiento|Factura|Ejercicio|FechaAsiento|FechaFactura|Comentario|BaseIva1|CuotaIva1|ImporteFactura|TipoFactura|PorIva1|Periodo|ejercicioOriginal|serieOriginal|numeroFacOriginal");
	
	
	$delimitador = "|";
	foreach ($resultado["datos"] as $row) 
	{
		fwrite($fp,PHP_EOL);
		fwrite($fp,$row["numero"]);//asiento
		fwrite($fp,$delimitador);
		
		if ($clayma=="true")
		{
			$codigoCuenta = 4300000 + $row["idCodigoCliente"];//codigo cuenta
		}
		else
		{
			$codigoCuenta = 430000000 + $row["idCodigoCliente"];//codigo cuenta
		}
		
		fwrite($fp,$codigoCuenta);
		fwrite($fp,$delimitador);
		
		fwrite($fp,'D');//cargo abono
		fwrite($fp,$delimitador);
		
		
		fwrite($fp,$row["nif"]);//CifDni
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["cliente"]);//Nombre
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["precioTotal"]);//ImporteAsiento
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["numeroFacturaCompleto"]);//Factura
		fwrite($fp,$delimitador);
		
		//$ejercicio = date("Y", $row["fecha"]);;
		$ejercicio = date_format($row["fecha"],"Y");
		
		fwrite($fp,$ejercicio);//Ejercicio
		fwrite($fp,$delimitador);
		
		$fechaFactura =  date_format($row["fecha"],"d/m/Y");
		fwrite($fp,$fechaFactura); //Fecha Asiento
		fwrite($fp,$delimitador);
		
		fwrite($fp,$fechaFactura); //Fecha Factura
		fwrite($fp,$delimitador);
		
		$anio2Digitos =  date_format($row["fecha"],"y");
		//fwrite($fp,"N/Factura Num. ".$row["numero"]."/".$anio2Digitos);//Comentario
		
		fwrite($fp,"N/Factura Num. ".$row["numeroFacturaCompleto"]);//Comentario
		
		
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["precioNeto"]);//BaseIva
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["iva"]);//CuotaIva
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["precioTotal"]);//ImporteFactura
		fwrite($fp,$delimitador);
		
		fwrite($fp,'E'); //TipoFactura
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["tipoIva"]);//porIva
		fwrite($fp,$delimitador);
		
		
		$mes = date_format($row["fecha"],"n");
		fwrite($fp,$mes);// /Periodo

		fwrite($fp,$delimitador);

		//echo '\n'.$row["origenFactura"];
		$facturaQueRectifica="";
		if (strpos($row["origenFactura"], "FAC") === 0 ||strpos($row["origenFactura"], "REST") === 0 || strpos($row["origenFactura"], "SUST") === 0 || strpos($row["origenFactura"], "NEG") === 0)
		{
			$facturaQueRectifica = $row["origenFactura"];
			//echo "\nentra1:";
			if (preg_match('/^([A-Z]+)\s+(\d+)\/(\d+)$/', $facturaQueRectifica, $m)) 
			{//echo '\nentra2';
				$tipo   = $m[1]; // RECT
				$num1   = $m[2]; // 3
				$num2   = $m[3]; // 25

				fwrite($fp,"20".$num2);//ejercicio de la factura a la que rectifica
				fwrite($fp,$delimitador); 

				fwrite($fp,$tipo);//tipo de serie de factura a  la que rectifica
				fwrite($fp,$delimitador); 
				
				fwrite($fp,$row["origenFactura"]);//numero de factura a  la que rectifica
				//fwrite($fp,$delimitador); 
			}	
			else
			{//echo '\nentra3';
				fwrite($fp,$delimitador); 
				fwrite($fp,$delimitador); 
			}		
		}
		else
		{
			fwrite($fp,$delimitador); 
			fwrite($fp,$delimitador); 
		}
		
		
		
		//47
		fwrite($fp,PHP_EOL);
		fwrite($fp,$row["numero"]);//asiento
		fwrite($fp,$delimitador);
		
		if ($clayma=="true")
		{
			$codigoCuenta = 4770000;//codigo cuenta
		}
		else
		{
			$codigoCuenta = 477000000;//codigo cuenta
		}
		
		
		fwrite($fp,$codigoCuenta);
		fwrite($fp,$delimitador);
		
		fwrite($fp,'H');//cargo abono
		fwrite($fp,$delimitador);
		
		
		fwrite($fp,"");//CifDni
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//Nombre
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["iva"]);//ImporteAsiento
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//Factura
		fwrite($fp,$delimitador);
		
		//$ejercicio = date("Y", $row["fecha"]);;
		$ejercicio = date_format($row["fecha"],"Y");
		
		fwrite($fp,$ejercicio);//Ejercicio
		fwrite($fp,$delimitador);
		
		$fechaFactura =  date_format($row["fecha"],"d/m/Y");
		fwrite($fp,$fechaFactura); //Fecha Asiento
		fwrite($fp,$delimitador);
		
		fwrite($fp,$fechaFactura); //Fecha Factura
		fwrite($fp,$delimitador);
		
		$anio2Digitos =  date_format($row["fecha"],"y");
				
		
		fwrite($fp,"N/Factura Num. ".$row["numeroFacturaCompleto"]);//Comentario
		
		
		
		
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//BaseIva
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//CuotaIva
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//ImporteFactura
		fwrite($fp,$delimitador);
		
		fwrite($fp,''); //TipoFactura
		fwrite($fp,$delimitador);
		
		fwrite($fp,'');//porIva
		fwrite($fp,$delimitador);
		
		
		$mes = date_format($row["fecha"],"n");
		fwrite($fp,$mes);// /Periodo
		fwrite($fp,$delimitador);


		$facturaQueRectifica="";
		if (strpos($row["origenFactura"], "REST") === 0 || strpos($row["origenFactura"], "SUST") === 0 || strpos($row["origenFactura"], "NEG") === 0)
		{
			$facturaQueRectifica =  $row["origenFactura"];

			if (preg_match('/^([A-Z]+)\s+(\d+)\/(\d+)$/', $texto, $m)) 
			{
				$tipo   = $m[1]; // RECT
				$num1   = $m[2]; // 3
				$num2   = $m[3]; // 25

				fwrite($fp,"20".$num2);//ejercicio de la factura a la que rectifica
				fwrite($fp,$delimitador); 

				fwrite($fp,$tipo);//tipo de serie de factura a  la que rectifica
				fwrite($fp,$delimitador); 
				
				fwrite($fp,$row["origenFactura"]);//numero de factura a  la que rectifica
				//fwrite($fp,$delimitador); 
			}
			else
			{//echo '\nentra3';
				fwrite($fp,$delimitador); 
				fwrite($fp,$delimitador); 
			}		
		}
		else
		{
			fwrite($fp,$delimitador); 
			fwrite($fp,$delimitador); 
		}
		
		
		//70
		fwrite($fp,PHP_EOL);
		fwrite($fp,$row["numero"]);//asiento
		fwrite($fp,$delimitador);
		
		if ($clayma=="true")
		{
			$codigoCuenta = 7050000;//codigo cuenta
		}
		else
		{
			$codigoCuenta = 705000000;//codigo cuenta
		}
		
		
		fwrite($fp,$codigoCuenta);
		fwrite($fp,$delimitador);
		
		fwrite($fp,'H');//cargo abono
		fwrite($fp,$delimitador);
		
		
		fwrite($fp,"");//CifDni
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//Nombre
		fwrite($fp,$delimitador);
		
		fwrite($fp,$row["precioNeto"]);//ImporteAsiento
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//Factura
		fwrite($fp,$delimitador);
		
		
		$ejercicio = date_format($row["fecha"],"Y");
		
		fwrite($fp,$ejercicio);//Ejercicio
		fwrite($fp,$delimitador);
		
		$fechaFactura =  date_format($row["fecha"],"d/m/Y");
		fwrite($fp,$fechaFactura); //Fecha Asiento
		fwrite($fp,$delimitador);
		
		fwrite($fp,$fechaFactura); //Fecha Factura
		fwrite($fp,$delimitador);
		
		$anio2Digitos =  date_format($row["fecha"],"y");
		
		
		
		
		fwrite($fp,"N/Factura Num. ".$row["numeroFacturaCompleto"]);//Comentario
		
		
		
		//fwrite($fp,"N/Factura Num. ".$row["numero"]."/".$anio2Digitos);//Comentario		
		//fwrite($fp,$data);//Comentario
		
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//BaseIva
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//CuotaIva
		fwrite($fp,$delimitador);
		
		fwrite($fp,"");//ImporteFactura
		fwrite($fp,$delimitador);
		
		fwrite($fp,''); //TipoFactura
		fwrite($fp,$delimitador);
		
		fwrite($fp,'');//porIva
		fwrite($fp,$delimitador);		
		
		$mes = date_format($row["fecha"],"n");
		fwrite($fp,$mes);// /Periodo

		fwrite($fp,$delimitador);



		$facturaQueRectifica="";
		if (strpos($row["origenFactura"], "REST") === 0 || strpos($row["origenFactura"], "SUST") === 0 || strpos($row["origenFactura"], "NEG") === 0)
		{
			$facturaQueRectifica = $row["origenFactura"];

			if (preg_match('/^([A-Z]+)\s+(\d+)\/(\d+)$/', $texto, $m)) 
			{
				$tipo   = $m[1]; // RECT
				$num1   = $m[2]; // 3
				$num2   = $m[3]; // 25

				fwrite($fp,"20".$num2);//ejercicio de la factura a la que rectifica
				fwrite($fp,$delimitador); 

				fwrite($fp,$tipo);//tipo de serie de factura a  la que rectifica
				fwrite($fp,$delimitador); 
				
				fwrite($fp,$row["origenFactura"]);//numero de factura a  la que rectifica
				//fwrite($fp,$delimitador); 
			}	else
			{//echo '\nentra3';
				fwrite($fp,$delimitador); 
				fwrite($fp,$delimitador); 
			}		
		}
		else
		{
			fwrite($fp,$delimitador); 
				fwrite($fp,$delimitador); 
		}
		
		
	}
	
	
	fclose($fp);
	
	
}


?>