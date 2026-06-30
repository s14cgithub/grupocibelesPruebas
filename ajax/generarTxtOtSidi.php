

<?php 
//echo 'entra0<br>';
if(isset($_POST["accion"]) && $_POST["accion"]=="generarTxtSIDI")
{
	session_start(); 
	$ruta = '../';	
	require($ruta."Archivos Comunes/constantes.php");
	require($ruta."Archivos Comunes/codigoInclude.php");
	
	
	$otSidiModal = $_POST["otSidiModal"];
	
	$rutaArchivo = "../archivosDescargas/otSidi.txt";

	if (file_exists($rutaArchivo)) 
	{	
		unlink($rutaArchivo);
	}

	


	$fp = fopen($rutaArchivo, "w");
	
	fwrite($fp,"");
	
	
	$delimitador = "\t";
	$delimitador2 = ";";
	$saltoLinea = "\n";
	$ots = explode($saltoLinea, $otSidiModal);

	$contador = 0;

	//echo 'entra1<br>';
	foreach ($ots as $otDatos) 
	{

		$datosLinea = explode($delimitador2, $otDatos);

		$ot =  $datosLinea[0];
		$codigoClienteSidi = $datosLinea[1];
		$descripcion = $datosLinea[2];

		$contador++;
		fwrite($fp,$contador);
		fwrite($fp,$delimitador);
		fwrite($fp,$codigoClienteSidi);
		fwrite($fp,$delimitador);
		fwrite($fp,$descripcion);
		fwrite($fp,$delimitador);
		fwrite($fp,"OT ".$ot);
		fwrite($fp,$delimitador);
		fwrite($fp,$delimitador);
		fwrite($fp,$delimitador);
		fwrite($fp,"1");		
		
		fwrite($fp,PHP_EOL);



		/*//echo 'ot: '.$ot.'\n';
		$resultado = verPresupuestoParaOt($conexion,$ot);

		

		$contador++;
		fwrite($fp,$contador);
		fwrite($fp,$delimitador);
		fwrite($fp,$resultado[0]["codigoSidi"]);
		fwrite($fp,$delimitador);
		fwrite($fp,$resultado[0]["campana2"]);
		fwrite($fp,$delimitador);
		fwrite($fp,"OT ".$ot);
		fwrite($fp,$delimitador);
		fwrite($fp,$delimitador);
		fwrite($fp,$delimitador);
		fwrite($fp,"1");		
		
		fwrite($fp,PHP_EOL);
		*/
	}
	
	
	fclose($fp);
	
	
}


?>