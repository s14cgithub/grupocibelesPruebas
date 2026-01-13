

<?php 

if(isset($_POST["accion"])&$_POST["accion"]=="mostrarFacturas")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$fechaInicio = $_POST["fechaInicio"];
	$fechaFin = $_POST["fechaFin"];
	
	$fechaInicio1 = date("d-m-Y", strtotime($fechaInicio));
	$fechaFin1 = date("d-m-Y", strtotime($fechaFin));

	$condicion = "where fecha>='".$fechaInicio1."' and fecha <= '".$fechaFin1."' order by codigo_saldo";
	
	$resultado = mostrarFacturasCorreosTodos($conexion,$condicion);
		
	
	
	
	
	$fp = fopen("../archivosDescargas/exportarFacturasCorreos.txt", "w");
	
	fwrite($fp,"Asiento|CodigoCuenta|CargoAbono|ImporteAsiento|Ejercicio|FechaAsiento|Comentario|Periodo");
	
	
	$delimitador = "|";
	$contador=0;
	foreach ($resultado as $row) 
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
	
	
	$fclose($fp);
	
	
}


?>