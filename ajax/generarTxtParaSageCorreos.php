

<?php 

if(isset($_POST["accion"]) && $_POST["accion"]=="mostrarFacturas")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	


	$campos = isset($_POST["campos"]) ? json_decode($_POST["campos"], true) : array();
	$filtros = isset($_POST["filtros"]) ? json_decode($_POST["filtros"], true) : array();
	$joins = isset($_POST["joins"]) ? json_decode($_POST["joins"], true) : array();
	$filtrosOperadores = isset($_POST["filtrosOperadores"]) ? json_decode($_POST["filtrosOperadores"], true) : array();
	$order = isset($_POST["order"]) ? json_decode($_POST["order"], true) : array();


	foreach ($filtrosOperadores as &$f)
	{
		if (isset($f['campo1']) && $f['campo1'] == 'fecha' && !empty($f['valor']))
		{
			$f['valor'] = date("d-m-Y", strtotime($f['valor']));
		}
	}
	unset($f);
/*
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));
*/
	
	$conn1 = conectarSQL($conexion);
	$conn = $conn1['conn'];
	$bbddSql = $conn1['bbdd'];
	
	$resultado = mostrarFacturacionCorreos($conn, $bbddSql, $campos, $joins, $filtros, $filtrosOperadores, $order);
		
	
	sqlsrv_close($conn);
	//exit;
	
	
	$fp = fopen("../archivosDescargas/exportarFacturasCorreos.txt", "w");
	
	fwrite($fp,"Asiento|CodigoCuenta|CargoAbono|ImporteAsiento|Ejercicio|FechaAsiento|Comentario|Periodo");
	
	
	$delimitador = "|";
	$contador=0;
	foreach ($resultado["datos"] as $row) 
	{
		$contador++;
		fwrite($fp,PHP_EOL);
		fwrite($fp,$contador);//asiento
		fwrite($fp,$delimitador);
		
		
		$codigoCuenta = 438000000 + $row["codigo_saldo"];//codigo cuenta
		
		
		fwrite($fp,$codigoCuenta);
		fwrite($fp,$delimitador);
		
		fwrite($fp,'D');//cargo abono
		fwrite($fp,$delimitador);  
		
		
		
		
		fwrite($fp,$row["importe"]);//ImporteAsiento
		fwrite($fp,$delimitador);

		
		$ejercicio = date_format($row["fecha"],"Y"); 
		
		fwrite($fp,$ejercicio);//Ejercicio
		fwrite($fp,$delimitador);
		
		$fechaFactura =  date_format($row["fecha"],"d/m/Y");
		fwrite($fp,$fechaFactura); //Fecha Asiento
		fwrite($fp,$delimitador);		
		
		fwrite($fp,"Fra. Correos: ".$row["numeroOficial"]);//Comentario
		fwrite($fp,$delimitador);	
		
		
		$mes = date_format($row["fecha"],"n");
		fwrite($fp,$mes);// /Periodo





		////////////////////H//////////

		fwrite($fp,PHP_EOL);
		fwrite($fp,$contador);//asiento
		fwrite($fp,$delimitador);
		
		
		$codigoCuenta = 440000000;//codigo cuenta
		
		
		fwrite($fp,$codigoCuenta);
		fwrite($fp,$delimitador);
		
		fwrite($fp,'H');//cargo abono
		fwrite($fp,$delimitador);  
		
		
		
		
		fwrite($fp,$row["importe"]);//ImporteAsiento
		fwrite($fp,$delimitador);

		
		$ejercicio = date_format($row["fecha"],"Y"); 
		
		fwrite($fp,$ejercicio);//Ejercicio
		fwrite($fp,$delimitador);
		
		$fechaFactura =  date_format($row["fecha"],"d/m/Y");
		fwrite($fp,$fechaFactura); //Fecha Asiento
		fwrite($fp,$delimitador);		
		
		fwrite($fp,"Fra. Correos: ".$row["numeroOficial"]);//Comentario
		fwrite($fp,$delimitador);	
		
		
		$mes = date_format($row["fecha"],"n");
		fwrite($fp,$mes);// /Periodo
	
		
		
		
		
		
		
	}
	
	
	fclose($fp);
	echo json_encode($resultado);
	
}


?>